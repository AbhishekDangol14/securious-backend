<?php

namespace App\Console\Commands;

use App\Models\{BreachedClass};
use App\Repositories\HBIP;
use Illuminate\Console\Command;

class SyncDataClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:dataclass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync HBIP data classes daily just in case there is an update';

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
        $classes = (new HBIP())->dataClasses();
        foreach ($classes as $key => $class) {
            BreachedClass::firstOrCreate([
                'name' => $class
            ]);
        }
    }
}
