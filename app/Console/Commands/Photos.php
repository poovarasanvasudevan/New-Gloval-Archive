<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactType;
use Excel;
use Illuminate\Console\Command;

class Photos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:photos';

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
        $this->info("Deleting old photos record");
        \DB::table('artefacts')->where('artefact_type', 6)->delete();
        $this->info("Done");


        //
        $this->info("Migrating Excel Attributes for photo given on 20-08-2016");
        $this->newMigratePhotos(storage_path('config/data/Photos.xlsx'));
        $this->info("Done");

        $this->info("Migrating Excel Attributes for photo given on 19-09-2016");
        $this->newMigratePhotos(storage_path('config/data/photo.csv'));
        $this->info("Done");
    }

    function newMigratePhotos($path)
    {
        ini_set('memory_limit', '-1');


        Excel::selectSheetsByIndex(0)
            ->load($path, function ($reader) {

            //$this->info(json_encode($reader->first()->toArray()));

            $my = $reader->toArray();
            $boxes = array();

            $this->warn("Total rows : " . sizeof($my));


            foreach ($my as $row) {
                if (isset($row['box_number'])) {
                    array_push($boxes, $row['box_number']);
                }
            }

            //  $this->info("done");

            $bar = $this->output->createProgressBar(count($boxes));
            foreach ($boxes as $box) {
                $parent = Artefact::firstOrNew(['artefact_name' => trim($box), 'artefact_type' => 6]);
                $parent->artefact_type = 6;
                $parent->location = 1;
                $parent->artefact_name = trim($box);
                $parent->parent_id = null;
                $parent->old_artefact_id = 0000;
                $parent->user_id = 3;

                $parent->save();
                $bar->advance();
            }
            $bar->finish();
            // $artefact_name = $reader->artefact_code;
            $dValues = array(
                'archive_id_code' => 67,
                'year_yyyy' => 68,
                'month_mm' => 69,
                'day_dd' => 70,
                'hour' => 71,
                'min' => 72,
                'continent' => 73,
                'country' => 74,
                'state' => 75,
                'city' => 76,
                'placecenter' => 77,
                'location' => 78,
                'subject' => 79,
                'tagged_persons' => 80,
                'event' => 81,
                'description' => 82,
                'credit' => 83,
                'photograper' => 84,
                'comments' => 85,
                'hat_selection' => 86,
                'team_selection' => 87,
                'final_selection' => 88,
                'determined' => 89,
                'box_number' => 90
            );

            $bar1 = $this->output->createProgressBar(count($my));
            foreach ($my as $row) {

                $tmp1 = array();
                foreach ($dValues as $k => $v) {
                    $tmp = array();
                    $attrId = 'data_' . $v;
                    $tmp['attr_id'] = $attrId;
                    $tmp['attr_value'] = isset($row[$k]) ? $row[$k] : "";

                    $tmp1[$attrId] = $tmp;
                }

                if (isset($row['artefact_code'])) {
                    $child = Artefact::firstOrNew([
                        'artefact_name' => $row['artefact_code'],
                        'artefact_type' => 6,
                        'parent_id' => Artefact::whereArtefactName($row['box_number'])->first()->id
                    ]);
                    $child->location = 1;
                    $child->artefact_type = 6;
                    $child->parent_id = Artefact::whereArtefactName($row['box_number'])->first()->id;
                    $child->user_id = 3;
                    $child->old_artefact_id = 0000;
                    $child->artefact_name = $row['artefact_code'];
                    $child->artefact_values = $tmp1;
                    $child->save();
                }
                $bar1->advance();
            }

            $bar1->finish();

        });

    }

}
