<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\Location;
use App\Page;
use App\Pages;
use App\PickData;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class ArchiveMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Existing Archive to new Version';

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

        $this->info("Refreshing Migration");
        \Artisan::call("migrate:reset");
        \Artisan::call("migrate");

        $this->warn("Creating Location : ");
        $location = array('CHN' => "Chennai", "HYD" => "Hyderabad", "All" => "All Location");
        foreach ($location as $k => $v) {
            $location = new Location();
            $location->short_name = $k;
            $location->long_name = $v;
            $location->is_archive_location = true;
            $location->save();
        }
        $this->info("done");

        $this->warn("Creating User : ");
        $users = \DB::connection("mysql2")->table("user")->get();
        foreach ($users as $user) {
            $user1 = new User();
            $user1->abhyasiid = strtolower($user->AbhyasiID);
            $user1->fname = $user->FirstName;
            $user1->lname = $user->LastName;
            $user1->password = md5("password");
            $user1->save();
        }
        $this->info("done");

        $artefactTypes = array(
            "Audio",
            "Books",
            "Letters",
            "Magazine",
            "PersonalItems",
            "Photos",
            "Video",
            "LTO Tapes"
        );

        $this->warn("Creating Artefact Types : ");

        foreach ($artefactTypes as $artefactType) {
            $artefactt = new ArtefactType();
            $artefactt->artefact_type_short = strtoupper($artefactType);
            $artefactt->artefact_type_long = $artefactType;
            $artefactt->artefact_description = "Set of all " . $artefactType;
            $artefactt->save();
        }
        $this->info("done");

        $this->warn("Creating Pages : ");
        $pages = json_decode(file_get_contents(storage_path("config/pages.json")));
        foreach ($pages as $page) {
            $p = new Page();
            $p->short_name = $page->name;
            $p->long_name = $page->title;
            $p->url = $page->url;
            $p->is_default = $page->default;
            $p->is_admin_page = $page->isadmin;
            $p->order = $page->order;

            $p->save();
        }
        $this->info("done");

        $this->warn("Creating Admin Role : ");
        $role = new Role();
        $role->short_name = 'admin';
        $role->long_name = "Administrator";
        $role->save();
        $this->info("done");

        $this->warn("Creating Developer Role : ");
        $role = new Role();
        $role->short_name = 'developer';
        $role->long_name = "Developer";
        $role->is_developer = true;
        $role->save();
        $this->info("done");

        $this->warn("Assigning Developer Page Permission  : ");
        Role::find(1)->pages()->sync(Page::all());
        $this->info("done");


        $this->warn("Assigning Developer Page Permission  : ");
        Role::find(2)->pages()->sync(Page::withoutGlobalScopes()->get());
        $this->info("done");


        $this->warn("Assigning Artefact Type Permission to Admin : ");
        User::find(3)->artefact_type()->sync(ArtefactType::all());
        $this->info("done");


        $this->warn("Making INKSAD408 to Admin : ");
        $user = User::whereAbhyasiid("inksad408")->first();
        $user->role = 1;
        $user->save();
        $this->info("done");

        $this->warn("Migrating parent Artefact : ");
        $artefacts = \DB::connection("mysql2")->table("artefact")->where('artefactpid', null)->get();


        $bar = $this->output->createProgressBar(count($artefacts));

        foreach ($artefacts as $artefact) {
            $a = new Artefact();
            $a->artefact_type = $this->getArtefactCode($artefact->ArtefactTypeCode);
            $a->location = 1;
            $a->old_artefact_id = $artefact->ArtefactCode;
            $a->artefact_name = $artefact->ArtefactName;
            $a->parent_id = null;
            $a->user_id = 3;
            $a->save();

            $bar->advance();
        }

        $bar->finish();
        $this->info("done");


        $this->warn("Migrating Child Artefact : ");
        $artefactsChild = \DB::connection("mysql2")
            ->table("artefact")
            ->whereNotNull('artefactpid')->get();


        $bar = $this->output->createProgressBar(count($artefactsChild));

        foreach ($artefactsChild as $artefact) {

            //$this->info($artefact->artefactcode);
            if ($this->getArtefactCode($artefact->ArtefactTypeCode) != 3) {
                if ($artefact->ArtefactPID != '') {

                    $a = new Artefact();
                    $a->artefact_type = $this->getArtefactCode($artefact->ArtefactTypeCode);
                    $a->location = 1;
                    $a->old_artefact_id = $artefact->ArtefactCode;
                    $a->parent_id = Artefact::whereArtefactName($artefact->ArtefactPID)->first()->id;
                    $a->artefact_name = $artefact->ArtefactName;
                    $a->user_id = 3;
                    $a->save();
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("done");

        $this->info("Migrating Attributes : ");


        $attributes = \DB::connection('mysql2')->table('attributes')->get();
        $bar = $this->output->createProgressBar(count($attributes));
        foreach ($attributes as $attribute) {
            $artefacttypecode = $this->getArtefactCode($attribute->ArtefactTypeCode);
            $pickFlag = false;
            $aListCode = "";
            $htmlType = $attribute->DataType;
            if ($attribute->PickFlag == 'y' || $attribute->PickFlag == 'Y') {
                $pickFlag = true;
                $aListCode = $attribute->AListCode;
                $htmlType = 'select';
            }
            if ($htmlType == 'Varchar' || $htmlType == 'vatchat' || $htmlType == 'varchar') {
                $htmlType = 'text';
            }
            if ($htmlType == 'int') {
                $htmlType = 'number';
            }

            $attributeObj = new ArtefactTypeAttribute();
            $attributeObj->artefact_type_id = $artefacttypecode;
            $attributeObj->html_type = $htmlType;
            $attributeObj->attribute_title = $attribute->Attributes;
            $attributeObj->pick_flag = $pickFlag;

            $attributeObj->save();

            if ($pickFlag) {
                $attributeId = $attributeObj->id;

                $lists = \DB::connection('mysql2')->table('attributelist')->where('alistcode', $aListCode)->get();

                foreach ($lists as $list) {
                    $pick = new PickData();
                    $pick->attribute_id = $attributeId;
                    $pick->pick_data_value = $list->AlistValue;
                    $pick->save();
                }

            }
            $bar->advance();

        }
        $bar->finish();
        $this->info("done");


        $this->warn("migrating Video Data : ");
        $this->migrateData('videoattributes', 7);
        $this->info("done");


        $this->warn("migrating Photo Data : ");
        $this->migrateData('photoboxattributes', 6);
        $this->info("done");

        $this->warn("migrating Book Data : ");
        $this->migrateData('bboxattributes', 2);
        $this->info("done");


        $this->warn("migrating Audio Data : ");
        $this->migrateData('audioattributes', 1);
        $this->info("done");

        $this->warn("migrating Letters Data : ");
        $this->migrateData('lboxattributes', 3);
        $this->info("done");



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

    function migrateData($tableName, $attId)
    {
        $videoDatas = \DB::connection('mysql2')->table($tableName)->get();
        $bar = $this->output->createProgressBar(count($videoDatas));
        foreach ($videoDatas as $k => $v) {
            $tmp1 = array();
            foreach (json_decode(json_encode($v)) as $key => $val) {

                $my_attrs = ArtefactTypeAttribute::whereArtefactTypeId($attId)
                    ->whereRaw("LOWER(attribute_title) = ?", [str_ireplace('"', '', strtolower($key))]);

                if ($my_attrs->count() == 1) {

                    $tmp = array();
                    $attrId = 'data_'.$my_attrs->first()->id;
                    $tmp['attr_id'] = $attrId;
                    $tmp['attr_value'] = $val;

                    $tmp1[$attrId] = $tmp;
                }
            }

            $oldCode = "";
            if ($tableName == 'videoattributes') {
                $oldCode = $v->artefactCode;
            } else {
                $oldCode = $v->artefactcode;
            }

            $arte = Artefact::whereOldArtefactId($oldCode);

            if ($arte->count() == 1) {
                $arte1 = $arte->first();
                $arte1->artefact_values = $tmp1;
                $arte1->save();
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info("done");


    }

    function getArtefactCode($artefact)
    {

        $artefactArray = array(
            "Brochure" => 2,
            "Book" => 2,
            "Souvenir" => 2,
            "LFile" => 3,
            "ATrack" => 1,
            "BBox" => 2,
            "Audio" => 1,
            "Bbox" => 2,
            "Photos" => 6,
            "LBox" => 3,
            "Video" => 7,
            "VTrack" => 7,
            "PhotoBox" => 6,
            "Letter" => 3
        );
        return $artefactArray[$artefact];

    }
}

//https://github.com/poovarasanvasudevan/gassystem
