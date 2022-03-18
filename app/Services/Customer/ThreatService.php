<?php

namespace App\Services\Customer;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use App\Models\AnalysisQuestion;
use App\Http\Resources\GetCustomerThreatResource;
use App\Repositories\ThreatRepository;
use App\Models\Threat;

class ThreatService extends BaseService
{
    public function __construct(ThreatRepository $repository){
        $this->repository = $repository;
    }

    public function getThreats()
    {
        $user = auth()->user();

        $threats = $user->threatsForCustomer()->notNeutralized($user->company_id)->get();

        foreach ($threats as $key => $threat) {
            if ($threat->isNeutralized($user->company)) {
                $threat['total_points'] = $user->getTotalPointsForAnalyzedThreat($threat);
                $threat['achieved_points'] = $user->getTotalObtainablePointsForAnalyzedThreat($threat);
            } else {
                $threat['total_points'] = $threat->getPoints();
                $threat['achieved_points'] = $threat->achievedPoints($user) ?? 0;
            }

            $threat['is_important'] = $threat->isImportant($user->company);
            $threat['progress'] = $threat['total_points'] == 0 ? 0 : ($threat['achieved_points'] / $threat['total_points']) * 100;
            //get image from mediable
            $threat['user'] = $threat->employee()->select('users.id','email','profiles.first_name', 'profiles.last_name')->leftJoin('profiles', 'profiles.user_id', 'users.id')->get()->toArray();
        }

        return $threats;
    }

    public function getNeutralizedThreats()
    {
        $user = auth()->user();

        $neutralizedThreats = $user->neutralizedThreats()->get();

        foreach ($neutralizedThreats as $threat) {
            $threat['is_important'] = $threat->isImportant($user->company);
            $threat['total_points'] = $user->getTotalPointsForAnalyzedThreat($threat);
            $threat['achieved_points'] = $user->getTotalObtainablePointsForAnalyzedThreat($threat);
            $threat['progress'] = $threat['total_points'] == 0 ? 0 : ($threat['achieved_points'] / $threat['total_points']) * 100;
            $threat['user'] = $threat->employee()->select('users.id','email','profiles.first_name', 'profiles.last_name')
                    ->leftJoin('profiles', 'profiles.user_id', 'users.id')
                    ->get()->toArray();
        }

        return $neutralizedThreats;
    }

    public function getReAnalyzedThreats()
    {
        $user = auth()->user();

        $toReAnalyzedThreats = $user->toReAnalyzedThreats()->get();

        foreach ($toReAnalyzedThreats as $threat) {
            $threat['is_important'] = $threat->isImportant($company);
            $threat['total_points'] = $user->getTotalPointsForAnalyzedThreat($threat);
            $threat['achieved_points'] = $user->getTotalObtainablePointsForAnalyzedThreat($threat);
            $threat['progress'] = $threat['total_points'] == 0 ? 0 : ($threat['achieved_points'] / $threat['total_points']) * 100;
            $threat['user'] = $threat->employee()->select('users.id','email','profiles.first_name', 'profiles.last_name', 'profiles.profile_image')
                    ->leftJoin('profiles', 'profiles.user_id', 'users.id')
                    ->get()->toArray();
            $threat['neutralizedDate'] = $threat['updated_at']->diffForHumans();
            $threat['neutralized_at'] = $threat->getNeutralizedDate($user->id);
        }

        return $toReAnalyzedThreats;
    }

    public function getShowThreat($id)
    {
        $user = auth()->user();
        $threat = Threat::with('translations')->find($id);
        $threat['question'] = $threat->questionsForCustomers($user)->orderBy('order', 'asc')->get();
        return $threat;
    }

    public function getNextThreat($id)
    {
        $user = auth()->user();
        $nextThreat = $user->threatsForCustomer()->notNeutralized($user->company_id)->where('threats.id', '!=', $id)->first();
    }
}
