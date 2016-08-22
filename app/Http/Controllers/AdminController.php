<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\Location;
use App\PickData;
use App\Role;
use App\User;
use Config;
use Dotenv\Dotenv;
use Illuminate\Http\Request;

use App\Http\Requests;
use JavaScript;
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
                "Adata" => ArtefactTypeAttribute::withoutGlobalScopes()->whereArtefactTypeId($id)->orderBy('id')->get()
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
        $att->sequence_number = request()->input('sequence_number');
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
        $att->is_searchable = request()->input('is_searchable');
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
        return view('admin.logs')
            ->with('logs', file_get_contents(storage_path('logs/laravel.log')));
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


        return view('admin.config')
            ->with('mail_config', $mail_setting)
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
}
