<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactTypeAttribute;
use Excel;
use Illuminate\Console\Command;

class HI8 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:hi8';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import hi8 attributes';

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
        ini_set('memory_limit', '-1');

        $this->info("Importing HI8 Attributes : ");
        $attr_array = array();
        Excel::load(
            storage_path('config/data/HI8/HI8.xlsx'),
            function ($reader)
            use (&$attr_array) {
                $reader->noHeading();

                $seq = 0;
                foreach ($reader->first()->toArray() as $item) {

                    foreach ($item as $k => $v) {
                        if ($v != null) {
                            $seq++;


                            $attr = new ArtefactTypeAttribute();
                            $attr->artefact_type_id = 9;
                            $attr->attribute_title = $v;
                            $attr->html_type = "text";
                            $attr->sequence_number = $seq;
                            $attr->is_searchable = true;
                            $attr->pick_flag = false;
                            $attr->active = true;
                            $attr->save();

                            $key = strtolower($v);
                            $data = str_replace(' ', '_', $key);
                            $attr_array[$data] = $attr->id;

                            $this->info($data);
                        }
                    }
                    break;

                }
            });


        $this->info(json_encode($attr_array));
        $this->info("Importing HI8 Attributes Data: ");

        Excel::load(storage_path('config/data/HI8/HI8.xlsx'), function ($reader) use ($attr_array) {
            $reader->each(function ($sheet) use ($attr_array) {

                $this->info("Processing The Year : " . $sheet->getTitle());
                $this->info("Creating Parent :" . $sheet->getTitle());
                $parent = Artefact::firstOrNew(['artefact_name' => $sheet->getTitle(), 'artefact_type' => 9]);
                $parent->artefact_type = 9;
                $parent->old_artefact_id = 0000;
                $parent->artefact_name = $sheet->getTitle();
                $parent->location = 1;
                $parent->user_id = 3;

                if ($parent->save()) {
                    $count = sizeof($sheet->toArray());
                    $bar = $this->output->createProgressBar($count);
                    $sheet->each(function ($row) use ($bar, $parent, $attr_array) {
                        if ($row->mediaid != null) {
                            $child = Artefact::firstOrNew([
                                'artefact_name' => $row->mediaid,
                                'artefact_type' => 9,
                                'parent_id' => $parent->id
                            ]);
                            $child->artefact_type = 9;
                            $child->old_artefact_id = 0000;
                            $child->parent_id = $parent->id;
                            $child->artefact_name = $row->mediaid;
                            $child->location = 1;
                            $child->user_id = 3;

                            $tmp1 = array();
                            foreach ($row->toArray() as $k => $v) {
                                $tmp = array();
                                if (isset($attr_array[$k])) {
                                    $attrId = 'data_' . $attr_array[$k];
                                    $tmp['attr_id'] = $attrId;
                                    $tmp['attr_value'] = $v;

                                    $tmp1[$attrId] = $tmp;
                                }
                            }

                            $child->artefact_values = $tmp1;
                            if ($child->save()) {
                                $bar->advance();
                            }
                        }
                    });
                    $bar->finish();
                    $this->info("Done : " . $sheet->getTitle());
                }
            });

        });

    }
}
