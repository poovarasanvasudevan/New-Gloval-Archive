<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\Location;
use App\PickData;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Grids;
use HTML;
use Illuminate\Support\Facades\Config;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\ShowingRecords;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\DbalDataProvider;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;


use App\Http\Requests;

class GlobalController extends Controller
{
    //
    public function login()
    {
        $userName = request()->input("username");
        $password = request()->input("password");

        $user = User::whereAbhyasiid(strtolower($userName))->wherePassword(md5($password));
        if ($user && $user->count() == 1) {
            \Auth::login($user->first());
            return response()->redirectTo("/home");
        } else {
            return response()->redirectTo("/")->withErrors(['Invalid Username or Password']);
        }
    }


    public function logout()
    {
        \Auth::logout();
        return response()->redirectTo("/")->withErrors(['Logout Succesfully']);
    }

    public function home()
    {

        if (\Auth::user()) {

            $artefactTypes = User::find(\Auth::user()->id)->artefact_type()->get();

            return view("home")
                ->with('artefacttypes', $artefactTypes);
        } else {
            return response()->redirectTo("/");
        }
    }

    public function resetPassword()
    {
        if (\Auth::user()) {

            return response()->view('resetpassword');
        } else {
            return response()->redirectTo("/");
        }
    }


    public function resetMyPassword()
    {
        $currentPassword = request()->input("currentPassword");
        $newPassword = request()->input("newPassword");
        $renewPassword = request()->input("renewPassword");

        $error = false;
        $update = false;
        $errordata = array();
        if ($newPassword != $renewPassword) {
            $error = true;
            array_push($errordata, "New Password and Re Enter Password must Match");
        }
        if (strlen($newPassword) < 8) {
            $error = true;
            array_push($errordata, "New Password length should be greater than 8");
        }

        if ($currentPassword == $newPassword) {
            $error = true;
            array_push($errordata, "New Password cannot be same as current Password");
        }

        $user = User::whereAbhyasiid(\Auth::user()->abhyasiid)->wherePassword(md5($currentPassword));
        if ($user && $user->count() == 1) {

            $update = true;
        } else {
            $error = true;
            array_push($errordata, "Invalid Current Password");
        }

        if ($error) {
            return response()->redirectTo("/reset-password")->withErrors($errordata);
        } else {
            User::whereAbhyasiid(\Auth::user()->abhyasiid)
                ->wherePassword(md5($currentPassword))
                ->update(['password' => md5($newPassword)]);

            \Auth::logout();
            return response()->redirectTo("/")->withErrors(["Succesfully Updated"]);
        }
    }

    public function definition()
    {
        if (\Auth::user()) {

            $artefactTypes = User::find(\Auth::user()->id)->artefact_type()->get();
            $location = Location::active()->get();
            return view('definition')
                ->with('locations', $location)
                ->with('artefacttypes', $artefactTypes);
        } else {
            return response()->redirectTo("/");
        }
    }

    public function loadTree($artefacttype, $parent)
    {
        $parentId = null;
        if ($parent == 0) {
            $parentId = null;
        } else {
            $parentId = $parent;
        }
        $artefactFind = Artefact::whereArtefactType($artefacttype);
        if ($parentId == null)
            $artefactFind->whereNull("parent_id");
        else
            $artefactFind->where('parent_id', $parentId);

        $artefactFinds = $artefactFind->get();
        $result = array();
        foreach ($artefactFinds as $af) {

            if (Artefact::whereParentId($af->id)->count() > 0) {
                array_push($result, array(
                    "title" => $af->artefact_name,
                    "key" => $af->id,
                    "lazy" => true,
                    "folder" => true
                ));
            } else {
                array_push($result, array(
                    "title" => $af->artefact_name,
                    "key" => $af->id,
                    "folder" => false,
                    "lazy" => false
                ));
            }

        }

        return response()->json($result);
    }

    public function roleUsers()
    {
        if (\Auth::user()) {
            $grid = new Grid(
                (new GridConfig)
                    ->setDataProvider(
                        new EloquentDataProvider(User::query())
                    )
                    ->setName('usergrid')
                    ->setPageSize(10)
                    ->setColumns([
                        (new FieldConfig)
                            ->setName('id')
                            ->setLabel('ID')
                            ->setSortable(true)
                            ->setSorting(Grid::SORT_ASC)
                        ,
                        (new FieldConfig)
                            ->setName('fname')
                            ->setLabel('First Name')
                            ->setSortable(true)
                            ->addFilter(
                                (new FilterConfig)
                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                            )
                        ,
                        (new FieldConfig)
                            ->setName('lname')
                            ->setLabel('Last Name')
                            ->setSortable(true)
                            ->addFilter(
                                (new FilterConfig)
                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                            )
                        ,
                        (new FieldConfig)
                            ->setName('abhyasiid')
                            ->setLabel('AbhyasiId')
                            ->setSortable(true)
                            ->addFilter(
                                (new FilterConfig)
                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                            )
                        ,
                        (new FieldConfig)
                            ->setName('email')
                            ->setLabel('Email')
                            ->setSortable(true)
                            ->setCallback(function ($val) {
                                $icon = '<span class="glyphicon glyphicon-envelope"></span>&nbsp;';
                                return
                                    '<small>'
                                    . $icon
                                    . HTML::link("mailto:$val", $val)
                                    . '</small>';
                            })
                            ->addFilter(
                                (new FilterConfig)
                                    ->setOperator(FilterConfig::OPERATOR_LIKE)
                            )
                        , (new FieldConfig)
                            ->setName('id')
                            ->setLabel('Action')
                            ->setCallback(function ($val) {
                                $icon = '<span class="glyphicon glyphicon-pencil"></span>&nbsp;';
                                return "<a href='/userEdit/{$val}' class='btn btn-primary'>" . $icon . " Edit</a> ";
                            })
                    ])

            );
            $grid = $grid->render();
            return view('userrole', compact('grid'));
        } else {
            return response()->redirectTo("/");
        }
    }

    public function userEdit($id)
    {
        if (\Auth::user()) {

            $user = User::find($id);
            $location = Location::all();
            $role = Role::all();
            $artefacts = ArtefactType::all();
            $availableArtefact = $user->artefact_type()->get();

            return view('useredit')
                ->with('user', $user)
                ->with('locations', $location)
                ->with('roles', $role)
                ->with('availables', $availableArtefact)
                ->with('artefacts', $artefacts);
        } else {
            return response()->redirectTo("/");
        }
    }

    public function updateUser(Request $request, $id)
    {
        if (\Auth::user()) {

            $user = User::find($id);

            $fname = request()->input("fname");
            $lname = request()->input("lname");
            $email = request()->input("email");
            $abhyasiid = request()->input("abhayasiId");
            $role = request()->input("role");
            $location = request()->input("location");
            $artefact_type = request()->input("artefact");

            $validator = \Validator::make($request->all(), [
                'fname' => 'required|max:25',
                'lname' => 'required',
                'abhayasiId' => 'required',
                'role' => 'required',
                'location' => 'required',
                'email' => 'required',
            ]);

            if (is_array($artefact_type)) {
                if (sizeof($artefact_type) == 0) {
                    $validator->errors()->add('field', 'Please select minimum one artefacts to access');
                }
            }
            if ($validator->fails()) {
                return response()
                    ->redirectTo('/userEdit/' . $id)
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $user->fname = $fname;
                $user->lname = $lname;
                $user->email = $email;
                $user->abhyasiid = $abhyasiid;
                $user->role = $role;
                $user->location = $location;
                $user->artefact_type()->sync($artefact_type);
                $user->save();


                return response()
                    ->redirectTo('/userEdit/' . $id)
                    ->withErrors(['User Saved Succesfully']);
            }

        } else {
            return response()->redirectTo("/");
        }
    }

    public function getArtefact($type, $id)
    {
        $artefact = Artefact::find($id);
        $attributes = ArtefactType::find($type)->attributes()->get();

        return view('artefactdetail')
            ->with('artefact', $artefact)
            ->with('attributes', $attributes);
    }

    function attrs()
    {
        $pickData = PickData::distinct('pick_data_value')->get();
        $fullResult = array();
        foreach ($pickData as $pick) {
            array_push($fullResult, array(
                'label' => $pick->pick_data_value,
                'value' => $pick->pick_data_value
            ));
        }

        //echo "<pre>";
        //echo json_encode($fullResult);
        return response()->json($fullResult);

    }

    function saveArtefact()
    {
        $datas = request()->all();
        $artefact_id = "";
        $fullreq = array();
        foreach ($datas as $k => $data) {
            if ($k == 'artefactId') {
                $artefact_id = $data;
            } else {
                $tmp = array();
                $attrId = $k;
                $tmp['attr_id'] = $k;
                $tmp['attr_value'] = $data;
                $fullreq[$attrId] = $tmp;
            }
        }

        $artefact = Artefact::find($artefact_id);
        $artefact->artefact_values = $fullreq;
        if ($artefact->save()) {
            return response()->json(array(
                'status' => 200
            ));
        } else {
            return response()->json(array(
                'status' => 100
            ));
        }
    }
}
