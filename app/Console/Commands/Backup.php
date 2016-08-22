<?php

namespace App\Console\Commands;

use App\Artefact;
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
        $allArtefact = Artefact::get()->toArray();
        File::put(storage_path('config/backup/artefact_'.date('d-m-Y').".json"),json_encode($allArtefact));
        $this->info("Artefact Backup done on ".date('d-m-Y'));

        $maintain = ScheduledMaintenenceDate::get()->toArray();
        File::put(storage_path('config/backup/maintenence_'.date('d-m-Y').".json"),json_encode($maintain));
        $this->info("Miantenence Backup done on ".date('d-m-Y'));


    }
}
