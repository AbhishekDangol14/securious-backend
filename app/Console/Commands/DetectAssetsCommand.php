<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Option;
use App\Models\SolutionPartnerProductRelation;
use App\Models\SolutionPartnerProduct;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Repositories\WhatCms;

class DetectAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detect:assets {url?} {user?} {intro?} {--queue=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command descriptionAutomatically detect assest based on user`s domain from intro questions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $website = $this->argument('url') ?? '';
        $user = $this->argument('user') ?? '';

        if (empty(!$user) && empty(!$website)) {
            $this->detectCMS($website, $user);
            $this->autoDetectedAssets($user);
        } else {
            $this->forAllUsers();
        }
    }

    private function logger($param)
    {
        $this->info($param);
        Log::channel('detect-assets')->info($param);
    }

    private function detectCMS($website, $user)
    {
        $detectedTech = (new WhatCms())->checkWhatCms($website);

        Option::updateOrCreate(
            ['reference_id' => $user['id'], 'type' => 'auto_detected_assets'],
            ['key' => $website, 'value' => json_encode($detectedTech), 'reference_id' => $user['id'], 'type' => 'auto_detected_assets']
        );

        // DON'T DELETE FOLLOWING LINE USED FOR TESTING PURPOSE ANYTIME
        // $this->logger('Detected data:');
        // $this->logger(json_encode($detectedTech));
    }

    private function autoDetectedAssets($user)
    {        
        $autoDetectedAssets = Option::where('reference_id', $user['id'])->whereIn('type', ['auto_detected_assets'])->get();
        $autoDetectedTechnologies = [];

        foreach($autoDetectedAssets as $key => $autoDetectedAsset){
            // Mapped each autodetected technologies with admin assigned in option table
            if (!empty($autoDetectedAsset)) {
                $optionValue = json_decode($autoDetectedAsset->value);
                $autoDetectedTechnologies = array_column($optionValue->results, 'name');
            }
        }
        dd($autoDetectedTechnologies);
        // Fetch mapped assets from option table inserted by admin to get the meta
        $mappedAssets = Option::select('reference_id')->whereIn('key', $autoDetectedTechnologies)->where('type', 'admin_product_asset')->groupBy('reference_id')->get()->toArray();
        dd($mappedAssets);
        if (!empty($mappedAssets)) {
            foreach ($mappedAssets as $assets) {
                if ($solutionServicesRelations = SolutionPartnerProductRelation::where('solution_services_products_id',$assets['reference_id'])->where('relation_table_id', $user['company_id'])->first()) {
                    $solutionServicesRelations->update(
                        [
                            'solution_services_products_id' => $assets['reference_id'],
                            'relation_table_id' => $user['company_id'],
                            'relation_table_name' => 'company',
                            'status' => 'active',
                            'label' => 'auto_detected'
                        ]
                    );
                    if (empty($this->argument('intro')) && $user->user_type_id == 5)
                        $service_product = SolutionPartnerProduct::find($assets['reference_id']);
                        // Mail::to($user->email)->send(new AssetAlertMail($user,$service_product->getTranslations('de_f')[0]));
                }
                else
                {
                    SolutionPartnerProductRelation::Create(
                        [
                            'solution_services_products_id' => $assets['reference_id'],
                            'relation_table_id' => $user['company_id'],
                            'relation_table_name' => 'company',
                            'status' => 'active',
                            'label' => 'auto_detected'
                        ]
                    );
                    if (empty($this->argument('intro'))) {
                        $service_product = SolutionPartnerProduct::find($assets['reference_id']);
                        // Mail::to($user->email)->send(new AssetAlertMail($user,$service_product->getTranslations('de_f')[0]));
                    }
                }
            }
        }
    }

    private function forAllUsers()
    {
        $users = User::whereHas('role', function($query) {
            $query->where('role_id','!=',1);
        })->get();

        foreach ($users as $user){
            $company = $user->company;
            if (!empty($company)){
                $website = $company->company_website;
                if (!empty($website)){
                    $this->detectCMS($website, $user);
                    $this->autoDetectedAssets($user);
                }
            }
            sleep(11);
        }
    }
}
