<?php

namespace App\Console\Commands;

use App\Models\{BreachedWebsite, EmailReputation, EmailServerStatus, User, UserEmail};
use App\Repositories\EmailServerScanner;
use App\Repositories\HBIP;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DataLeakMail;

class ScanEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:scan {user?} {onlyUnchecked?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Check Email Breach';

    /**
     * @var boolean
     */
    protected $serverInfoSaved = false;

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
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        $this->showInfoToConsole('Loading Emails...', true);
        if ($this->argument('onlyUnchecked')) {
            $onlyUnchecked = (int)$this->argument('onlyUnchecked');
        } else {
            $onlyUnchecked = false;
        }
        $userEmails = $this->getEmails($onlyUnchecked);

        $this->iterateEmails($userEmails);
        $this->showInfoToConsole('Checking Finished. Total Time: ' . round((microtime(true) - $start), 2) . 'seconds', true);
    }

    public function showInfoToConsole($param, $printIfProduction = false)
    {
        if (env('APP_ENV') != 'production') {
            $this->info($param);
        }
        if ((env('APP_ENV') == 'production' && $printIfProduction) || env('APP_ENV') == 'local') {
            Log::channel('email-breach')->info('Info: ' . $param);
        }
    }

    /**
     * Fetch Single/All User's Email for security breach check
     * @param $onlyUnchecked bool // Default False
     * @return object|null
     */

    public function getEmails(bool $onlyUnchecked = false)
    {

        $user = !is_null($this->argument('user')) ? (int)$this->argument('user') : null;
        return UserEmail::when($user != null, function ($sQ) use ($user) {
            return $sQ->where('user_id', $user);
        })->when($onlyUnchecked, function ($sQ) {
            return $sQ->where('checked', 0);
        })->get();
    }

    /**
     * @param $userEmails object
     * @return void
     */
    public function iterateEmails($userEmails): void
    {
        foreach ($userEmails as $userEmail) {
            $this->showInfoToConsole('Sending Request to API For Email: ' . $userEmail->email, true);
            $securityBreachReports = (new HBIP())->breachedAccount($userEmail->email);
            $data = [];
            if ($securityBreachReports != null) {
                foreach ($securityBreachReports as $report) {
                    if (isset($report->Name)) {
                        $data[] = $report->Name;
                    }
                }
                $ids = BreachedWebsite::whereIn('name', $data)->get()->pluck('id')->toArray();
                $userEmail->breaches()->sync($ids);
                $this->showInfoToConsole('Security Breach Found For Email: ' . $userEmail->email);
                $user = User::find($userEmail->user_id);
//                Mail::to($user->email)->send(new DataLeakMail($user));
            }
            $userEmail->update([
                'checked' => 1
            ]);
        }
    }

    /**
     * @param $userEmail
     * @return void
     */

}
