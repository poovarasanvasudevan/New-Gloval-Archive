<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\Cico;
use App\Location;
use App\Page;
use App\PickData;
use App\Role;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Grids;
use HTML;
use Illuminate\Support\Facades\Config;
use Mail;
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
use Validator;

class GlobalController extends Controller
{
    //
    public function login()
    {
        $userName = request()->input("username");
        $password = request()->input("password");

        $user = User::whereAbhyasiid(strtolower($userName))->wherePassword(md5($password));
        if ($user && $user->count() == 1) {
            Auth::login($user->first());
            return response()->redirectTo("/home");
        } else {
            return response()->redirectTo("/")->withErrors(['Invalid Username or Password']);
        }
    }


    public function logout()
    {
        Auth::logout();
        return response()->redirectTo("/")->withErrors(['Logout Succesfully']);
    }

    public function home()
    {

        if (Auth::user()) {

            $artefactTypes = User::find(Auth::user()->id)->artefact_type()->get();

            return view("home")
                ->with('artefacttypes', $artefactTypes);
        } else {
            return response()->redirectTo("/");
        }
    }

    public function resetPassword()
    {
        if (Auth::user()) {

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

        $user = User::whereAbhyasiid(Auth::user()->abhyasiid)->wherePassword(md5($currentPassword));
        if ($user && $user->count() == 1) {

            $update = true;
        } else {
            $error = true;
            array_push($errordata, "Invalid Current Password");
        }

        if ($error) {
            return response()->redirectTo("/reset-password")->withErrors($errordata);
        } else {
            User::whereAbhyasiid(Auth::user()->abhyasiid)
                ->wherePassword(md5($currentPassword))
                ->update(['password' => md5($newPassword)]);

            Auth::logout();
            return response()->redirectTo("/")->withErrors(["Succesfully Updated"]);
        }
    }

    public function definition()
    {
        if (Auth::user()) {

            $artefactTypes = User::find(Auth::user()->id)->artefact_type()->get();
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

        $artefactFind->where('active', true);
        $artefactFinds = $artefactFind->get();
        $result = array();
        foreach ($artefactFinds as $af) {

            if (Artefact::whereParentId($af->id)->where('active', true)->count() > 0) {
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
        if (Auth::user()) {
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
        if (Auth::user()) {

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
        if (Auth::user()) {

            $user = User::find($id);

            $fname = request()->input("fname");
            $lname = request()->input("lname");
            $email = request()->input("email");
            $abhyasiid = request()->input("abhayasiId");
            $role = request()->input("role");
            $location = request()->input("location");
            $artefact_type = request()->input("artefact");

            $validator = Validator::make($request->all(), [
                'fname' => 'required|max:25',
                'lname' => 'required',
                'abhayasiId' => 'required',
                'role' => 'required',
                'location' => 'required',
                'email' => 'required',
            ]);

            if ($role == 0 || $role == '0') {
                $validator->errors()->add('field', 'Please select Valid Role');
            }
            if (is_array($artefact_type)) {
                if (sizeof($artefact_type) == 0) {
                    $validator->errors()->add('field', 'Please select minimum one artefacts to access');
                }

            }

            if ($artefact_type == null) {
                $validator->errors()->add('field', 'Please select minimum one artefacts to access');
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

    function addArtefact($type, $id, $val)
    {
        $parentId = null;
        if ($id != 0) {
            $parentId = $id;
        }

        $newArtefcat = new Artefact();
        $newArtefcat->artefact_type = $type;
        $newArtefcat->parent_id = $parentId;
        $newArtefcat->artefact_name = $val;
        $newArtefcat->old_artefact_id = '00000';
        $newArtefcat->user_id = Auth::user()->id;
        $newArtefcat->location = Auth::user()->location;

        if ($newArtefcat->save()) {
            return response()->json(array(
                'status' => 200
            ));
        } else {
            return response()->json(array(
                'status' => 100
            ));
        }
    }

    function deleteArtefact($id)
    {
        $delArtefact = Artefact::find($id);
        $delArtefact->active = false;
        if ($delArtefact->save()) {
            return response()->json(array(
                'status' => 200
            ));
        } else {
            return response()->json(array(
                'status' => 100
            ));
        }
    }

    function renameArtefact($id, $newName)
    {
        $delArtefact = Artefact::find($id);
        $delArtefact->artefact_name = $newName;
        if ($delArtefact->save()) {
            return response()->json(array(
                'status' => 200
            ));
        } else {
            return response()->json(array(
                'status' => 100
            ));
        }
    }

    function moveArtefact($id, $newParent)
    {
        $moveArtefact = Artefact::find($id);
        $moveArtefact->parent_id = $newParent;
        if ($moveArtefact->save()) {
            return response()->json(array(
                'status' => 200
            ));
        } else {
            return response()->json(array(
                'status' => 100
            ));
        }
    }

    function allartefact()
    {
        return response()->json(DB::table('artefacts')->pluck('artefact_name'));
    }

    function cico()
    {
        if (Auth::user()) {
            return view('cico');
        } else {
            return response()->redirectTo("/");
        }
    }

    function cin()
    {
        if (Auth::user()) {
            return view('checkin');
        } else {
            return response()->redirectTo("/");
        }
    }

    function getCheckout()
    {
        $result = DB::table('artefacts')
            ->leftJoin('cico', 'artefacts.id', '=', 'cico.artefact_id')
            ->select('artefacts.artefact_name', 'artefacts.id')
            ->whereRaw('artefacts.id NOT IN (SELECT artefact_id
                           FROM cico where check_out_status=true)')
            ->get();

        return response()->json($result);
    }

    function checkout()
    {
        $id = request()->input('artefactid');
        $desc = request()->input('checkoutreason');

        $cico = new Cico();
        $cico->artefact_id = $id;
        $cico->user_id = Auth::user()->id;
        $cico->check_out_description = $desc;

        if ($cico->save()) {
            flash('Checkout Successfull', 'success');
            return response()->redirectTo('/cico');
        } else {
            flash('Failed to checkout', 'error');
            return response()->redirectTo('/cico');
        }
    }

    function checkin()
    {
        $id = request()->input('artefactid');
        $desc = request()->input('checkinreason');

        $cico = Cico::whereCheckOutStatus(true)->whereArtefactId($id)->first();
        $cico->check_in_description = $desc;
        $cico->check_out_status = false;
        $cico->user_id = Auth::user()->id;
        if ($cico->save()) {
            flash('Checkin Successfull', 'success');
            return response()->redirectTo('/cico');
        } else {
            flash('Failed to checkin', 'error');
            return response()->redirectTo('/cico');
        }
    }

    function checkInAutocomplete()
    {
        $result = DB::table('artefacts')
            ->leftJoin('cico', 'artefacts.id', '=', 'cico.artefact_id')
            ->select('artefacts.artefact_name', 'artefacts.id')
            ->whereRaw('artefacts.id IN (SELECT artefact_id
                           FROM cico where check_out_status=true)')
            ->get();

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function userCreate(Request $request)
    {
        if (Auth::user()) {

            $user = new User();

            $fname = request()->input("fname");
            $lname = request()->input("lname");
            $email = request()->input("email");
            $abhyasiid = request()->input("abhayasiId");
            $role = request()->input("role");
            $location = request()->input("location");
            $artefact_type = request()->input("artefact");

            $validator = Validator::make($request->all(), [
                'fname' => 'required|max:25',
                'lname' => 'required',
                'abhayasiId' => 'required',
                'role' => 'required',
                'location' => 'required',
                'email' => 'required',
                'artefact' => 'required'
            ]);

            if ($role == 0 || $role == '0') {
                $validator->errors()->add('field', 'Please select Valid Role');
            }
            if (is_array($artefact_type)) {
                if (sizeof($artefact_type) == 0) {
                    $validator->errors()->add('field', 'Please select minimum one artefacts to access');
                }
            }

            if ($artefact_type == null) {
                $validator->errors()->add('field', 'Please select minimum one artefacts to access');
            }
            if ($validator->fails()) {
                return response()
                    ->redirectTo('/newUser')
                    ->withErrors($validator);
            } else {

                $password = str_random(8);

                $user->fname = $fname;
                $user->lname = $lname;
                $user->email = $email;
                $user->abhyasiid = $abhyasiid;
                $user->role = $role;
                $user->password = md5($password);
                $user->location = $location;
                $user->artefact_type()->sync($artefact_type);
                if ($user->save()) {

                    Mail::send('email.newuser', array(
                        'username' => $abhyasiid,
                        'password' => $password,
                        'url' => base_path('/')
                    ), function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->fname . " " . $user->lname)
                            ->subject('Welcome to Global Archive!');
                    });

                    flash('User created succesfully', 'success');
                    return response()
                        ->redirectTo('/userrole/');
                } else {
                    flash('failed to create user', 'error');
                    return response()
                        ->redirectTo('/userrole/');
                }
            }

        } else {
            return response()->redirectTo("/");
        }
    }


    function newUser()
    {
        if (Auth::user()) {
            \Debugbar::addMessage(Page::getUserPage());
            return response()->view('newUser');
        } else {
            return response()->redirectTo('/');
        }
    }

    function addRole()
    {
        if (Auth::user()) {

            $name = request()->input('rolename');
            $pages = request()->input('pages');
            $fullPage = array();

            if ($pages == null) {
                $pages = Page::where('is_default', true);
                foreach ($pages as $page) {
                    array_push($fullPage, $page->id);
                }
            } elseif (is_array($pages)) {
                $p = Page::where('is_default', true)->get();
                foreach ($pages as $page) {
                    array_push($fullPage, $page);
                }

                foreach ($p as $p1) {
                    array_push($fullPage, $p1->id);
                }

                array_unique($fullPage);
            }

            $role = new Role();
            $role->short_name = strtoupper(preg_replace('/\s+/', '', $name));
            $role->long_name = $name;
            $role->save();
            \Debugbar::addMessage($role);
            \Debugbar::addMessage($fullPage);
            $role->pages()->sync($fullPage);
            flash("Role added succesfully", "success");
            return response()->redirectTo('/editRole/0');
        } else {
            return response()->redirectTo('/');
        }
    }

    function editRole($id)
    {
        if (Auth::user()) {

            $role = Role::get();
            $pages = Page::get();

            \Debugbar::addMessage($role);
            return view('editRole')
                ->with('roleSelected', $id)
                ->with('pages', $pages)
                ->with('roles', $role);
        } else {
            return response()->redirectTo('/');
        }
    }

    function allroles()
    {
        return response()->json(Role::get());
    }

    function updateRole()
    {
        if (Auth::user()) {

            $role = Role::find(request()->input('id'));
            $role->short_name = request()->input('short_name');
            $role->long_name = request()->input('long_name');
            $role->active = request()->input('active');
            if ($role->save()) {
                return response()->json($role);
            }
        } else {
            return response()->redirectTo('/');
        }
    }

    function maintenence()
    {
        if (Auth::user()) {
            $location = Location::get();
            $artefactTypes = User::find(Auth::user()->id)->artefact_type()->get();
            return view('maintenance')
                ->with('artefacttypes', $artefactTypes)
                ->with('locations', $location);

        } else {
            return response()->redirectTo('/');
        }
    }
}
