<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ScheduledMaintenenceDate;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMaintenenceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Archive Notification';

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
        /**
         * SELECT artefacts.*
         * FROM artefacts
         * LEFT JOIN scheduled_maintenences sm ON artefacts.id = sm.artefact_id
         * LEFT JOIN scheduled_maintenence_dates ON sm.id = scheduled_maintenence_dates.scheduled_maintenence_id;
         */
        $artefacts = \DB::table('artefacts')
            ->select(array(
                'artefacts.artefact_name',
                'artefact_types.artefact_type_long'
            ))
            ->leftJoin('artefact_types', 'artefacts.artefact_type', '=', 'artefact_types.id')
            ->leftJoin('scheduled_maintenences', 'artefacts.id', '=', 'scheduled_maintenences.artefact_id')
            ->leftJoin('scheduled_maintenence_dates', 'scheduled_maintenences.id', '=', 'scheduled_maintenence_dates.scheduled_maintenence_id')
            ->whereRaw('scheduled_maintenence_dates.maintenence_date = ?', [Carbon::now()->toDateString()])
            ->get();
        if ($artefacts) {
            foreach (User::get() as $user) {
                if ($user->email) {
                    \Mail::send('email.notification', array(
                        'artefacts' => $artefacts,
                        'name' => $user->fname . " " . $user->lname,
                    ), function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->fname . " " . $user->lname)
                            ->subject('Conditional Report Notification!');
                    });

                    \Log::info("Email Sent To : " . $user->email);
                }
            }
        }
    }
}
