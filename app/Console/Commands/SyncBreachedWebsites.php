<?php

namespace App\Console\Commands;

use App\Models\{BreachedClass, BreachedWebsite};
use App\Repositories\HBIP;
use Illuminate\Console\Command;

class SyncBreachedWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:websites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync website data with HBIP';

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
     * @return mixed
     */
    public function handle()
    {
        $websites = (new HBIP())->allBreachedWebsite();
        foreach ($websites as $website) {
            $data = BreachedWebsite::updateOrCreate([
                'name' => $website->Name,
            ], [
                    'domain' => $website->Domain,
                    'logo' => $website->LogoPath,
                    'date' => $website->BreachDate
                ]
            );
            $ids = BreachedClass::whereIn('name', $website->DataClasses)->get()->pluck('id')->toArray();
            $data->classes()->sync($ids);
        }
    }
}
