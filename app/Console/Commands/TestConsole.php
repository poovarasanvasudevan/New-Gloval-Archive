<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\User;
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

        $this->warn("Importing Letter Conditional Report");
        $arrayTite = array(
            "General" => array(
                "22",
                "23",
                "24",
                "25",
                "26",
                "27",
                "28",
                "29",
                "30",
                "31",
                "32"

            ),
            "Supports Condition Recto" => array(
                "33",
                "34",
                "35",
                "36",
                "37",
                "38",
                "40",
                "41",
                "42",
                "43",
                "44",
                "45",
                "46",
                "47",
                "48",
                "49"
            ),
            "Media Condition Recto-Verso" => array(
                "51",
                "52",
                "53",
                "54",
                "55",
                "56",
                "58",
                "59",
            ),
            "Done By" => array(
                "64",
                "65"
            )
        );

        foreach ($arrayTite as $k => $v) {
            $segment = new  ConditionalReportsSegment();
            $segment->artefact_type_id = 3;
            $segment->segment_name = preg_replace('/\s+/', '', $k);
            $segment->segment_title = $k;
            $segment->save();

            foreach ($v as $val) {
                $checklist = \DB::connection('mysql2')->table("checklist")->where("CheckListPK", $val)->first();
                if ($checklist) {
                    $conditionalReport = new ConditionalReport();
                    $conditionalReport->conditional_reports_segment_id = $segment->id;
                    $conditionalReport->conditional_report_name = str_random(8);
                    $conditionalReport->conditional_report_title = $checklist->CheckListItem;
                    $conditionalReport->conditional_report_html_type = $checklist->DataType;

                    if ($checklist->pickflag == 'y') {
                        $conditionalReport->conditional_report_pick_flag = true;
                        $pick = \DB::connection('mysql2')->table('attributelist')->where('AlistCode', $checklist->pickcode)->get();
                        $pick_item = array();
                        foreach ($pick as $picks) {
                            array_push($pick_item, $picks->AlistValue);
                        }
                        $conditionalReport->conditional_report_pick_data = $pick_item;
                    }
                    $conditionalReport->save();
                }
            }
        }
        $this->info("done");

        $this->info("importing photos report");
        $photos = json_decode(file_get_contents(storage_path("config/crreport/photos.json")));
        foreach ($photos as $photo) {
            $segment = new  ConditionalReportsSegment();
            $segment->artefact_type_id = 6;
            $segment->segment_name = preg_replace('/\s+/', '', $photo->desc);
            $segment->segment_title = $photo->desc;
            $segment->save();


            foreach ($photo->items as $item) {
                $conditionalReport = new ConditionalReport();
                $conditionalReport->conditional_reports_segment_id = $segment->id;
                $conditionalReport->conditional_report_name = str_random(8);
                $conditionalReport->conditional_report_title = $item->name;
                $conditionalReport->conditional_report_html_type = $item->type;


                if (is_array($item->pick)) {
                    $pick_item = array();
                    foreach ($item->pick as $item1) {
                        array_push($pick_item, $item1);
                    }
                    $conditionalReport->conditional_report_pick_data = $pick_item;
                }
                $conditionalReport->save();
            }
        }

        $this->info("importing book report");
        $photos = json_decode(file_get_contents(storage_path("config/crreport/book.json")));
        foreach ($photos as $photo) {
            $segment = new  ConditionalReportsSegment();
            $segment->artefact_type_id = 2;
            $segment->segment_name = preg_replace('/\s+/', '', $photo->desc);
            $segment->segment_title = $photo->desc;
            $segment->save();

            foreach ($photo->items as $item) {
                $conditionalReport = new ConditionalReport();
                $conditionalReport->conditional_reports_segment_id = $segment->id;
                $conditionalReport->conditional_report_name = str_random(8);
                $conditionalReport->conditional_report_title = $item->name;
                $conditionalReport->conditional_report_html_type = $item->type;

                if (is_array($item->pick)) {
                    $pick_item = array();
                    foreach ($item->pick as $item1) {
                        array_push($pick_item, $item1);
                    }
                    $conditionalReport->conditional_report_pick_data = $pick_item;
                }
                $conditionalReport->save();
            }
        }

    }
}
