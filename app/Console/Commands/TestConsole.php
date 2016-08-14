<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactTypeAttribute;
use Illuminate\Console\Command;

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
        //


        $videoDatas = \DB::connection('mysql2')->table('videoattributes')->get();
        $bar = $this->output->createProgressBar(count($videoDatas));
        foreach ($videoDatas as $k => $v) {
            $tmp1 = array();
            foreach (json_decode(json_encode($v)) as $key => $val) {

                $my_attrs = ArtefactTypeAttribute::whereArtefactTypeId(7)
                    ->whereRaw("LOWER(attribute_title) = ?", [str_ireplace('"', '', strtolower($key))]);

                $this->info($my_attrs->count());
                if ($my_attrs->count() == 1) {


                    $tmp = array();
                    $attrId = $my_attrs->first()->id;
                    $tmp['attr_id'] = $attrId;
                    $tmp['attr_value'] = $val;

                    $tmp1[$attrId] = $tmp;
                }


            }

            $this->info(json_encode($tmp1));
            break;

        }
    }
}
