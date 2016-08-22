<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use JavaScript;

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

        flash("Deleting an Item Which is Not Recoverable ,Please be careful","warning");

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

    function logs(){
        return view('admin.logs')
            ->with('logs',file_get_contents(storage_path('logs/laravel.log')));
    }

    function getAllAttributes($id)
    {
        return response()->json();
    }
}
