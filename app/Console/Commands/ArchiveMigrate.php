<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
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
            $user1->abhyasiid = strtolower($user->abhyasiid);
            $user1->fname = $user->firstname;
            $user1->lname = $user->lastname;
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

        $this->warn("Assigning Page Permission to Admin : ");
        Role::find(1)->pages()->sync(Page::all());
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
            $a->artefact_type = $this->getArtefactCode($artefact->artefacttypecode);
            $a->location = 1;
            $a->old_artefact_id = $artefact->artefactcode;
            $a->artefact_name = $artefact->artefactname;
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
            if ($this->getArtefactCode($artefact->artefacttypecode) != 3) {
                if ($artefact->artefactpid != '') {

                    $a = new Artefact();
                    $a->artefact_type = $this->getArtefactCode($artefact->artefacttypecode);
                    $a->location = 1;
                    $a->old_artefact_id = $artefact->artefactcode;
                    $a->parent_id = Artefact::whereArtefactName($artefact->artefactpid)->first()->id;
                    $a->artefact_name = $artefact->artefactname;
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
            $artefacttypecode = $this->getArtefactCode($attribute->artefacttypecode);
            $pickFlag = false;
            $aListCode = "";
            $htmlType = $attribute->datatype;
            if ($attribute->pickflag == 'y' || $attribute->pickflag == 'Y') {
                $pickFlag = true;
                $aListCode = $attribute->alistcode;
                $htmlType = 'select';
            }
            if($htmlType =='Varchar' || $htmlType =='vatchat' || $htmlType=='varchar') {
                $htmlType = 'text';
            }
            if($htmlType =='int') {
                $htmlType = 'number';
            }

            $attributeObj = new ArtefactTypeAttribute();
            $attributeObj->artefact_type_id = $artefacttypecode;
            $attributeObj->html_type = $htmlType;
            $attributeObj->attribute_title = $attribute->attributes;
            $attributeObj->pick_flag = $pickFlag;

            $attributeObj->save();

            if ($pickFlag) {
                $attributeId = $attributeObj->id;

                $lists = \DB::connection('mysql2')->table('attributelist')->where('alistcode', $aListCode)->get();

                foreach ($lists as $list) {
                    $pick = new PickData();
                    $pick->attribute_id = $attributeId;
                    $pick->pick_data_value = $list->alistvalue;
                    $pick->save();
                }

            }
            $bar->advance();

        }
        $bar->finish();
        $this->info("done");


        $this->warn("migrating Video Data : ");
        $this->migrateData('videoattributes',7);
        $this->info("done");


        $this->warn("migrating Photo Data : ");
        $this->migrateData('photoboxattributes',6);
        $this->info("done");

        $this->warn("migrating Book Data : ");
        $this->migrateData('bboxattributes',2);
        $this->info("done");


        $this->warn("migrating Audio Data : ");
        $this->migrateData('audioattributes',1);
        $this->info("done");


    }

    function migrateData($tableName,$attId) {
        $videoDatas = \DB::connection('mysql2')->table($tableName)->get();
        $bar = $this->output->createProgressBar(count($videoDatas));
        foreach ($videoDatas as $k => $v) {
            $tmp1 = array();
            foreach (json_decode(json_encode($v)) as $key => $val) {

                $my_attrs = ArtefactTypeAttribute::whereArtefactTypeId($attId)
                    ->whereRaw("LOWER(attribute_title) = ?", [str_ireplace('"', '', $key)]);

                if ($my_attrs->count() == 1) {

                    $tmp = array();
                    $attrId = $my_attrs->first()->id;
                    $tmp['attr_id'] = $attrId;
                    $tmp['attr_value'] = $val;

                    $tmp1[$attrId] = $tmp;
                }
            }

            $arte = Artefact::whereOldArtefactId($v->artefactcode);

            if($arte->count() ==1) {
                $arte1 = $arte->first();
                $arte1->artefact_values = $tmp1;
                $arte1->save();
            }
            $bar->advance();
        }
        $bar->finish();
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
            "PhotoBox" => 6
        );
        return $artefactArray[$artefact];

    }
}

//https://github.com/poovarasanvasudevan/gassystem
