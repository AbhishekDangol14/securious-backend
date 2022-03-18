<?php

namespace App\Services;

use App\Traits\HasIndustryService;
use App\Repositories\IntroRepository;
use App\Models\Profile;
use App\Models\Company;
use App\Models\UserRelation;
use Illuminate\Support\Facades\Artisan;

class IntroService extends BaseService
{
    use HasIndustryService;

    public function __construct(IntroRepository $repository){
        $this->repository = $repository;
    }

    public function storeCompany($data,$user) {
        $company = new Company([
            'company_name' => $data['company_name'],
            'company_website' => $data['company_website'],
            'company_size' => $data['company_size'],
            'business_address' => $data['business_address'],
            'company_role_id' => $data['company_role_id'],
            'legal_role_id' => $data['legal_role_id'],
            'industry_id' => $data['industry_id'],
        ]);
        $model = $user->company()->save($company);
        return $model;
    }

    public function storeProfile($data,$user){
        
        $profile = new Profile([
            'title' => $data['title'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user->profile()->save($profile);

        $userRelation = UserRelation::where('child_id',$user->id)->first();
        
        if ($userRelation){
            ConsultantInvite::updateorCreate([
                'email' => $user->email], [
                'user_id' => $userRelation->parent_id,
                'salutation' => $request['title'],    
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name']
            ]);
        }
    }

    public function scanToDetectAssets($url,$user){
        $newUser = collect([
            'id' => $user->id,
            'company_id' => $user->company_id
        ]);
        
        // Deletec CMS and other assets
        if (!empty($url)) :
            Artisan::queue('detect:assets', [
                'url' => $url, 'user' => $newUser, '--queue' => 'default'
            ]);
        endif;

        // Detect only OS & Browser assets of current user
        $ua = $_SERVER['HTTP_USER_AGENT'];
        Artisan::call('detect:further-assets', [
            'user' => $newUser, 'ua' => $ua, '--queue' => 'default'
        ]);
    }

    public function updateUserCompany($id,$user)
    {
        $user->update(['company_id' => $id]);
    }

    public function updateIntroduction($user)
    {
        $user->update(['introduction' => 1]);
    }
}
