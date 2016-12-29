<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\User;
use Excel;
use Illuminate\Console\Command;
use Mail;
use Queue;

class TestConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
        ini_set('memory_limit', '-1');

        $this->info("Importing HI8 Attributes : ");
        Excel::selectSheetsByIndex(0)
            ->load(storage_path('app/public/shpt/cod.xlsx'), function ($reader) {

                //$this->info(json_encode($reader->first()->toArray()));

                $my = $reader->toArray();
                $boxes = array();

                $this->warn("Total rows : " . sizeof($my));


                foreach ($my as $row) {
                    if($row['postal_code'] !=null) {
                       // $this->info(json_encode($row));

                        $ddf = "INSERT INTO `cod_pincodes` (`pin_id`, `state`, `city`, `pin_code`, `shipping_vendor`, `active`, `created_date`) VALUES (NULL, '".$row['state']."', '".$row['city_name']."', '".$row['postal_code']."', 'Fedex', '1', CURRENT_TIMESTAMP);";

                        \Log::info($ddf);
                        $this->info($ddf);
                    }
                }
            });

    }
}
