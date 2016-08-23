<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ScheduledMaintenence;
use App\ScheduledMaintenenceDate;
use File;
use Illuminate\Console\Command;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $path = storage_path('config/backup/'.date('d-m-Y'));
        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $allArtefact = Artefact::get()->toArray();
        File::put(storage_path('config/backup/'.date('d-m-Y').'/artefact_'.date('d-m-Y').".json"),json_encode($allArtefact));
        $this->info("Artefact Backup done on ".date('d-m-Y'));

        $maintain = ScheduledMaintenenceDate::get()->toArray();
        File::put(storage_path('config/backup/'.date('d-m-Y').'/maintenence_date_'.date('d-m-Y').".json"),json_encode($maintain));
        $this->info("Miantenence Backup done on ".date('d-m-Y'));


        $maintain1 = ScheduledMaintenence::get()->toArray();
        File::put(storage_path('config/backup/'.date('d-m-Y').'/maintenence_'.date('d-m-Y').".json"),json_encode($maintain1));
        $this->info("Miantenence Backup done on ".date('d-m-Y'));

    }
}
