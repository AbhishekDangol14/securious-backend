<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Option;
use App\Models\SolutionPartnerProductRelations;
use App\Models\User;
use App\Repositories\Userstack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DetectOsBrowserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detect:further-assets {user} {ua} {--queue=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically detect OS & Browser`s further assest based on user`s OS & Browser from intro questions';

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
        $user = $this->argument('user') ?? '';
        $ua = $this->argument('ua') ?? '';

        // DON'T DELETE FOLLOWING LINE USED FOR TESTING PURPOSE ANYTIME
        //$this->logger('Website scan start:' . $website);

        if (!empty($user)) {
            $this->detectOsBrowser($user, $ua);
            $this->autoDetectedAssets($user);
        }
    }

    private function logger($param)
    {
        $this->info($param);
        Log::channel('detect-assets')->info($param);
    }

    private function detectOsBrowser($user, $ua)
    {
        $detectedOsBrowsers = (new Userstack())->whichOsBrowser($ua);

        Option::updateOrCreate(
            ['reference_id' => $user['id'], 'type' => 'auto_detected_os_browser_assets'],
            ['key' => $user['id'], 'value' => json_encode($detectedOsBrowsers), 'reference_id' => $user['id'], 'type' => 'auto_detected_os_browser_assets']
        );
    }

    private function autoDetectedAssets($user)
    {        
        $autoDetectedAssets = Option::where('reference_id', $user['id'])->whereIn('type', ['auto_detected_os_browser_assets'])->get();

        $os = $browser = '';

        foreach($autoDetectedAssets as $key => $autoDetectedAsset):
            // Mapped each autodetected technologies with admin assigned in option table
            if (!empty($autoDetectedAsset)) :
                $optionValue = json_decode($autoDetectedAsset->value);
                
                if (!empty($optionValue->os)):
                    $os = $optionValue->os->name;
                endif;
                
                if (!empty($optionValue->browser)):
                    $browser = $optionValue->browser->name;
                endif;
            endif;
        endforeach;

        // Fetch mapped assets from option table inserted by admin to get the meta
        $mappedAssets = Option::select('reference_id')->whereRaw("'{$os}' LIKE CONCAT('%', options.key, '%')")->orWhereRaw("'{$browser}' LIKE CONCAT('%', options.key, '%')")->where('type', 'admin_product_asset')->groupBy('reference_id')->get()->toArray();
        
        if (!empty($mappedAssets)) :
            foreach ($mappedAssets as $assets) :
                SolutionPartnerProductRelation::updateOrCreate(
                    [
                        'solution_services_products_id' => $assets['reference_id'],
                        'relation_table_id' => $user['company_id'],
                        'relation_table_name' => 'company',
                    ],
                    [
                        'solution_services_products_id' => $assets['reference_id'],
                        'relation_table_id' => $user['company_id'],
                        'relation_table_name' => 'company',
                        'status' => 'active',
                        'label' => 'auto_detected'
                    ]
                );
            endforeach;
        endif;
    }
}
