<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\Location;
use App\Page;
use App\PickData;
use App\Role;
use App\User;
use Carbon\Carbon;
use Config;
use Dotenv\Dotenv;
use Excel;
use Illuminate\Http\Request;

use App\Http\Requests;
use JavaScript;
use Log;
use Mail;
use Nayjest\Builder\Env;
use Setting;

class AdminController extends Controller
{
    //

    function index()
    {
        $users = User::withoutGlobalScopes()->count();
        $artefact = Artefact::withoutGlobalScopes()->count();
        return view('admin.home')
            ->with('artefact', $artefact)
            ->with('user', $users);
    }

    function artefacttypes()
    {

        return view('admin.artefacttypes');
    }

    function getAllArtefactTypes()
    {
        return response()->json(ArtefactType::withoutGlobalScopes()->get());
    }

    function updateArtefactTypes()
    {
        if (request()->input('active') == false) {
            if (ArtefactType::find(request()->input('id'))->artefact()->count() > 0) {
                flash("Unable to deactive the itemable Artefacttype", "error");
                return response()->redirectTo('/admin/artefacttypes');
            }
        } else {
            $aType = ArtefactType::find(request()->input('id'));
            $aType->artefact_type_long = request()->input('artefact_type_long');
            $aType->artefact_type_short = request()->input('artefact_type_short');
            $aType->artefact_description = request()->input('artefact_description');
            $aType->active = request()->input('active');

            if ($aType->save()) {
                return response()->json($aType);
            }
        }
    }

    function deleteArtefactTypes()
    {
        $aType = ArtefactType::find(request()->input('id'));

        if ($aType->artefact()->count() > 0) {
            flash("Unable to Delete the itemable Artefacttype", "error");
            return response()->redirectTo('/admin/artefacttypes');
        } else {
            $aType->delete();
            flash("Artefacttype deleted succesfully", "success");
            return response()->redirectTo('/admin/artefacttypes');
        }
    }

    function addArtefactTypes()
    {
        $aType = new ArtefactType();
        $aType->artefact_type_long = request()->input('artefact_type_long');
        $aType->artefact_type_short = request()->input('artefact_type_short');
        $aType->artefact_description = request()->input('artefact_description');
        $aType->active = true;
        if ($aType->save()) {
            return response()->json($aType);
        }
    }

    function attributes($id)
    {

        flash("Deleting an Item Which is Not Recoverable ,Please be careful", "warning");

        if ($id == 0) {
            JavaScript::put([
                "Adata" => null,
                "AType" => $id
            ]);
            return view('admin.attributes')
                ->with('ats', ArtefactType::withoutGlobalScopes()->get());
        } else {
            JavaScript::put([
                "AType" => $id,
                "Adata" => ArtefactTypeAttribute::withoutGlobalScopes()->whereArtefactTypeId($id)->orderBy('sequence_number')->get()
            ]);
            return view('admin.attributes')
                ->with('ats', ArtefactType::withoutGlobalScopes()->get());
        }

    }

    function updateAttributes()
    {
        $att = ArtefactTypeAttribute::find(request()->input('id'));
        $att->active = request()->input('active');
        $att->attribute_title = request()->input('attribute_title');
        $att->html_type = request()->input('html_type');
        $att->is_searchable = request()->input('is_searchable');
        $att->is_box = request()->input('is_box');
        $att->sequence_number = request()->input('sequence_number');
        $att->select_pick_data = explode(",", request()->input('select_pick_data'));
        $att->pick_flag = request()->input('pick_flag');
        if ($att->save()) {
            return response()->json($att);
        }
    }

    function addAttributes($id)
    {
        $att = new ArtefactTypeAttribute();
        $att->active = true;
        $att->pick_flag = request()->input('pick_flag');
        $att->attribute_title = request()->input('attribute_title');
        $att->html_type = request()->input('html_type');
        $att->sequence_number = request()->input('sequence_number');
        $att->is_box = request()->input('is_box');
        $att->is_searchable = request()->input('is_searchable');
        $att->select_pick_data = explode(",", request()->input('select_pick_data'));
        $att->artefact_type_id = $id;

        if ($att->save()) {
            return response()->json($att);
        }

    }

    function deleteAttributes()
    {
        $att = ArtefactTypeAttribute::find(request()->input('id'));
        $att->delete();
    }

    function logs()
    {
        $dte = date('Y-m-d');
        return view('admin.logs')
            ->with('logs', file_get_contents(storage_path('logs/laravel-' . $dte . '.log')));
    }

    function getAllAttributes($id)
    {
        return response()->json();
    }

    function attributelist()
    {
        JavaScript::put([
            "Adata" => PickData::withoutGlobalScopes()->distinct('pick_data_value')->orderBy('id')->get()
        ]);
        return view('admin.attrlist');
    }

    function updatepick()
    {
        $pick = PickData::find(request()->input('id'));
        $pick->pick_data_value = request()->input('pick_data_value');
        $pick->active = request()->input('active');
        if ($pick->save()) {
            return response()->json($pick);
        }
    }

    function deletepick()
    {
        $pick = PickData::find(request()->input('id'));
        $pick->delete();
    }

    function insertpick()
    {
        $pick = new PickData();
        $pick->pick_data_value = request()->input('pick_data_value');
        $pick->attribute_id = 1;
        $pick->active = request()->input('active');

        if ($pick->save()) {
            return response()->json($pick);
        }
    }

    function users()
    {
        JavaScript::put([
            "Adata" => User::withoutGlobalScopes()->orderBy('id')->get(),
            "loc" => Location::withoutGlobalScopes()->get(),
            "role" => Role::withoutGlobalScopes()->get()
        ]);
        return view('admin.users');
    }

    function updateuser()
    {
        $user = User::find(request()->input('id'));
        $pwf = false;
        if (request()->input('password') == "" || request()->input('password') == null) {

        } else {
            $user->password = md5(request()->input('password'));
            $pwf = true;
        }
        $user->abhyasiid = strtolower(request()->input('abhyasiid'));
        $user->fname = request()->input('fname');
        $user->lname = request()->input('lname');
        $user->email = request()->input('email');
        $user->role = request()->input('role');
        $user->location = request()->input('location');
        $user->is_developer = request()->input('is_developer');

        if ($user->save()) {
            if ($pwf) {
                if ($user->email) {
                    Mail::send('email.updateuser', array(
                        'username' => $user->abhyasiid,
                        'password' => request()->input('password'),
                        'url' => base_path('/')
                    ), function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->fname . " " . $user->lname)
                            ->subject('Global Archive Password Updated!');
                    });
                }
            }

            return response()->json($user);
        }

    }

    function crreport($type = 0, $section = 0)
    {
        $artefact_types = ArtefactType::withoutGlobalScopes()->get();
        if ($type == 0) {
            $segment = null;
            $segment_data = 0;

            JavaScript::put([
                "atype" => $type,
                "section" => $section,
                "segment" => "",
                "sectionData" => $segment_data
            ]);
            return view('admin.crreport')
                ->with('artefact_types', $artefact_types)
                ->with('segment', $segment);
        } else {
            if ($section == 0 || $section == null) {
                $segment = ConditionalReportsSegment::withoutGlobalScopes()->whereArtefactTypeId($type)->get();
                $segment_data = 0;
                JavaScript::put([
                    "atype" => $type,
                    "section" => $section,
                    "segment" => $segment,
                    "sectionData" => $segment_data
                ]);
                return view('admin.crreport')
                    ->with('artefact_types', $artefact_types)
                    ->with('segment', $segment);
            } else {

                $segment = ConditionalReportsSegment::withoutGlobalScopes()->whereArtefactTypeId($type)->get();
                $segment_data = ConditionalReportsSegment::find($section)->report()->get();

                JavaScript::put([
                    "atype" => $type,
                    "section" => $section,
                    "segment" => $segment,
                    "sectionData" => $segment_data
                ]);
                return view('admin.crreport')
                    ->with('artefact_types', $artefact_types)
                    ->with('segment', $segment);
            }
        }
    }

    function updatesegment()
    {
        $seg = ConditionalReportsSegment::find(request()->get('id'));
        $seg->segment_name = request()->input('segment_name');
        $seg->segment_title = request()->input('segment_title');
        $seg->sequence_number = request()->input('sequence_number');
        if ($seg->save()) {
            return response()->json($seg);
        }
    }

    function deletesegment()
    {
        $seg = ConditionalReportsSegment::find(request()->get('id'));
        $seg->delete();
    }

    function insertsegment($id)
    {
        $seg = new ConditionalReportsSegment();
        $seg->active = request()->input('active');
        $seg->artefact_type_id = $id;
        $seg->segment_name = request()->input('segment_name');
        $seg->segment_title = request()->input('segment_title');
        $seg->sequence_number = request()->input('sequence_number');
        if ($seg->save()) {
            return response()->json($seg);
        }
    }

    function updatesegmentvalue()
    {
        $seg = ConditionalReport::find(request()->input('id'));

        $seg->conditional_report_name = request()->input('conditional_report_name');
        $seg->conditional_report_title = request()->input('conditional_report_title');
        $seg->conditional_report_html_type = request()->input('conditional_report_html_type');
        $seg->conditional_report_pick_data = explode(",", request()->input('conditional_report_pick_data'));
        $seg->default_value = request()->input("default_value");
        $seg->sequence_number = request()->input('sequence_number');
        $seg->active = request()->input('active');

        if ($seg->save()) {
            return response()->json($seg);
        }
    }

    function insertsegmentvalue($id)
    {
        $seg = new ConditionalReport();
        $seg->conditional_reports_segment_id = $id;
        $seg->conditional_report_name = str_random(8);
        if (request()->input('conditional_report_pick_data') == "") {
            $seg->conditional_report_pick_data = null;
        } else {
            $seg->conditional_report_pick_data = explode(",", request()->input('conditional_report_pick_data'));
        }
        $seg->conditional_report_title = request()->input('conditional_report_title');
        $seg->conditional_report_html_type = request()->input('conditional_report_html_type');
        $seg->default_value = request()->input("default_value");
        $seg->sequence_number = request()->input('sequence_number');
        $seg->active = request()->input('active');

        if ($seg->save()) {
            return response()->json($seg);
        }
    }


    function config()
    {
        /**
         *
         * Setting::set('mail_config',array(
         * 'smtp_host'=>'smtp.gmail.com',
         * 'smtp_port'=>'smtp.gmail.com',
         * 'smtp_username'=>'smtp.gmail.com',
         * 'smtp_password'=>'smtp.gmail.com',
         * ));
         */
        $mail_setting = Setting::get('mail_config');
        $cico_mail = Setting::get('cico_mail', "");
        $version = Setting::get('version.number', "");


        return view('admin.config')
            ->with('mail_config', $mail_setting)
            ->with('version', $version)
            ->with('cico_mail', $cico_mail);
    }

    function saveMail()
    {
        Setting::set('mail_config', request()->all());
        flash('Mail setting saved Succesfully', 'success');

//        Config::set('mail.host',request()->input('smtp_host'));
//        Config::set('mail.port',request()->input('smtp_port'));
//        Config::set('mail.from.address',request()->input('smtp_username'));
//        Config::set('mail.username',request()->input('smtp_username'));
//        Config::set('mail.password',request()->input('smtp_password'));

        return response()->redirectTo('/admin/config');
    }

    function saveCicoMail()
    {
        $list = trim(request()->input('cico_mail'));
        Setting::set('cico_mail', explode(",", $list));
        flash('Cico Mail setting saved Succesfully', 'success');
        return response()->redirectTo('/admin/config');

    }

    function pages()
    {

        JavaScript::put([
            "datas" => Page::get()
        ]);
        return view('admin.pages');
    }

    function addPage()
    {
        $page = new Page();
        $page->short_name = request()->input('short_name');
        $page->long_name = request()->input('long_name');
        $page->url = request()->input('url');
        $page->sequence_number = request()->input('sequence_number');
        $page->is_default = request()->input('is_default');
        $page->active = request()->input('active');

        if ($page->save()) {
            return response()->json($page);
        }
    }

    function deletePage()
    {

    }

    function updatePage()
    {
        $page = Page::find(request()->input('id'));
        $page->short_name = request()->input('short_name');
        $page->long_name = request()->input('long_name');
        $page->url = request()->input('url');
        $page->sequence_number = request()->input('sequence_number');
        $page->is_default = request()->input('is_default');
        $page->active = request()->input('active');

        if ($page->save()) {
            return response()->json($page);
        }
    }

    function setVersion()
    {
        $v = array(
            'number' => request()->input('version'),
            'updated' => Carbon::today()->toDateString()
        );
        Setting::set('version', $v);
        flash('Version saved Succesfully', 'success');
        return response()->redirectTo('/admin/config');
    }

    function git()
    {
        $org = json_decode(\Guzzle::get(env('APP_GIT'))->getBody());
        return view('admin.git')->with('data', $org);
    }

    function location()
    {
        JavaScript::put([
            'AData' => Location::get()
        ]);

        return view('admin.location');
    }

    function updatelocation()
    {
        $loc = Location::find(request()->input('id'));
        $loc->short_name = request()->input('short_name');
        $loc->long_name = request()->input('long_name');
        $loc->is_archive_location = request()->input('is_archive_location');
        $loc->active = request()->input('active');

        if ($loc->save()) {
            return response()->json($loc);
        }
    }

    function insertlocation()
    {
        $loc = new Location();
        $loc->short_name = request()->input('short_name');
        $loc->long_name = request()->input('long_name');
        $loc->is_archive_location = request()->input('is_archive_location');
        $loc->active = request()->input('active');
        if ($loc->save()) {
            return response()->json($loc);
        }
    }

    function firetask()
    {
        \Artisan::call('archive:notification');
        \Artisan::call('test');

        flash("Notification Sent Succesfully...", "success");
        return response()->redirectTo('/admin/config');
    }

    function downServer()
    {
        \Artisan::call('down');
        return response()->redirectTo('/admin/config');
    }

    function upServer()
    {
        \Artisan::call('up');
        return response()->redirectTo('/admin/config');
    }

    function cacheclear()
    {
        \Artisan::call('cache:clear');
        flash("Cache Cleared Succesfully...", "success");
        return response()->redirectTo('/admin/config');
    }

    function excelImportSheet($id)
    {
        $artefactType = ArtefactType::find($id);

        Excel::create($artefactType->artefact_type_long . '_format', function ($excel) use ($artefactType) {
            $excel->setTitle('Excel format for ' . $artefactType->artefact_type_long);
            $excel->setCreator('SRCM')
                ->setCompany('SRCM');
            $excel->setDescription('Import excel for Archive');


            $excel->sheet('artefacts', function ($sheet) use ($artefactType) {
                $attributes = $artefactType->attributes;
                $data = array('Artefact Name');
                foreach ($attributes as $attribute) {
                    array_push($data, $attribute->attribute_title);
                }
                $sheet->fromArray($data, null, 'A1', true);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#3ba84b');
                    $row->setFontColor('#ffffff');
                    $row->setFontFamily('Calibri');
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');
                });
            });

        })->download('csv');
    }


    function importExcel()
    {

        $artefact_types = ArtefactType::withoutGlobalScopes()->get();
        return view('admin.importartefact')
            ->with('ats', $artefact_types);
    }


    function importArtefactExcel()
    {

        ini_set('max_execution_time', 600);

        $artefactName = request()->input("artefacttype");
        $artefactfile = request()->file('file');


        $fileName = "hello" . "." . $artefactfile->getClientOriginalExtension();
        $path = storage_path('config/excel/');
        $name = str_random(6) . "_" . $artefactName;
        $artefactfile->move($path, $name . '.' . "csv");


        \Artisan::call('archive:excel', ['id' => $name]);

        return response()->json([
            'result' => 'Success',
            'command' => 'php artisan archive:excel ' . $name
        ]);
    }
}
