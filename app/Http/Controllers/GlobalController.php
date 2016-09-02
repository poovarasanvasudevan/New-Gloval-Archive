<?php

namespace App\Http\Controllers;

use App\Artefact;
use App\ArtefactType;
use App\ArtefactTypeAttribute;
use App\Cico;
use App\ConditionalReportsSegment;
use App\Location;
use App\Page;
use App\PickData;
use App\Role;
use App\ScheduledMaintenence;
use App\ScheduledMaintenenceDate;
use App\User;
use Auth;
use Carbon\Carbon;
use Curl;
use DB;
use DebugBar\DebugBar;
use GitHub;
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

        $user = User::whereAbhyasiid(strtolower($userName))
            ->wherePassword(md5($password))
            ->whereActive(true);
        if ($user && $user->count() == 1) {
            Auth::login($user->first());
            return response()->redirectTo("/home");
        } else {
            return response()->redirectTo("/")->withErrors(['Invalid Username or Password']);
        }
    }

    function artefactview($id = 0)
    {
        $artefact = Artefact::find($id);
        $attr = ArtefactTypeAttribute::whereArtefactTypeId($artefact->artefact_type)->get();

        if ($artefact) {
            return view('artefactview')
                ->with('artefact', $artefact)
                ->with('attr', $attr);
        } else {
            return response()->redirectTo('/');
        }
        //3477
    }

    function artefactprint($id = 0)
    {
        $artefact = Artefact::find($id);
        $attr = ArtefactTypeAttribute::whereArtefactTypeId($artefact->artefact_type)->get();

        if ($artefact) {
            $pdf = \PDF::loadView('pdf.artefactprint', array(
                'artefact' => $artefact,
                'attr' => $attr,
            ));

            return $pdf->stream();
        } else {
            return response()->redirectTo('/');
        }

    }

    function forget()
    {
        return response()->view('forget');
    }

    function reset()
    {
        $user = User::whereAbhyasiid(request()->input('abhyasiid'))
            ->whereEmail(request()->input('email'))
            ->get();

        if ($user->count() == 1) {
            $userGet = $user->first();
            $pwd = str_random(8);
            \Debugbar::addMessage($userGet);

            $u = User::find($userGet->id);
            $u->password = md5($pwd);
            if ($u->save()) {


                Mail::send('email.resetpassword', array(
                    'username' => $u->abhyasiid,
                    'password' => $pwd,
                    'url' => base_path('/')
                ), function ($message) use ($u) {
                    $message
                        ->to($u->email, $u->fname . " " . $u->lname)
                        ->subject(env('APP_NAME') . 'Password Reset!');
                });

                flash("Password Reset Succesfully,Check Your Email", "success");
                return response()->redirectTo('/forget');
            } else {
                flash("Password Reset Failed", "error");
                return response()->redirectTo('/forget');
            }

        } else {
            flash("Password Reset Failed unknown User", "error");
            return response()->redirectTo('/forget');
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
            $location = Location::active()->whereIsArchiveLocation(true)->get();
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
            $location = Location::active()->get();
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
            $archive_location = request()->input("archive_location");
            $artefact_type = request()->input("artefact");

            $validator = Validator::make($request->all(), [
                'fname' => 'required|max:25',
                'lname' => 'required',
                'abhayasiId' => 'required',
                'role' => 'required',
                'location' => 'required',
                'archive_location' => 'required',
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
                $user->archive_location = $archive_location;
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
                $attrId = "data_" . $k;
                $tmp['attr_id'] = $attrId;
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
        $newArtefcat->location = Auth::user()->archive_location;

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

        $sett = \Setting::get('cico_mail');

        if ($cico->save()) {

            Mail::send('email.cico', ['cico' => $cico], function ($message) use ($sett) {
                foreach ($sett as $s) {
                    $message->to($s);
                }
                $message->subject(env('APP_NAME') . "Artefact Checkout");
            });

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
            $archivelocation = request()->input("archivelocation");
            $artefact_type = request()->input("artefact");

            $validator = Validator::make($request->all(), [
                'fname' => 'required|max:25',
                'lname' => 'required',
                'abhayasiId' => 'required',
                'role' => 'required',
                'location' => 'required',
                'email' => 'required',
                'archivelocation' => 'required',
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
                $user->archive_location = $archivelocation;
                if ($user->save()) {

                    $user->artefact_type()->sync($artefact_type);

                    Mail::send('email.newuser', array(
                        'username' => $abhyasiid,
                        'password' => $password,
                        'url' => base_path('/')
                    ), function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->fname . " " . $user->lname)
                            ->subject('Welcome to ' . env('APP_NAME') . '!');
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
            $location = Location::active()->whereIsArchiveLocation(true)->get();
            $artefactTypes = User::find(Auth::user()->id)->artefact_type()->get();
            return view('maintenance')
                ->with('artefacttypes', $artefactTypes)
                ->with('locations', $location);

        } else {
            return response()->redirectTo('/');
        }
    }

    function addSperodicMaintenance()
    {
        $date = Carbon::createFromFormat('d/m/Y', request()->input("scheduleDate"));
        $scheduledMaintenence = new ScheduledMaintenence();
        $scheduledMaintenence->artefact_id = request()->input('artefact_id');
        $scheduledMaintenence->maintenence_type = 'Sperodic';
        $scheduledMaintenence->maintenence_description = request()->input('maintenence_description');
        $scheduledMaintenence->save();
        if ($scheduledMaintenence->id) {
            $scDate = new ScheduledMaintenenceDate();
            $scDate->scheduled_maintenence_id = $scheduledMaintenence->id;
            $scDate->maintenence_date = $date;
            $scDate->save();

            flash("Maintenence Scheduled Succesfully", "success");
            return response()->redirectTo('/maintenence');
        } else {
            flash("Failed to Create Maintenence Scheduled", "error");
            return response()->redirectTo('/maintenence');
        }
    }

    function getSchedule($id)
    {
        return response()->json(ScheduledMaintenence::whereArtefactId($id)->get());
    }

    function addPerodicMaintenance()
    {

        $start_date = request()->input('start_date');
        $start = Carbon::createFromFormat('d/m/Y', $start_date);
        $end_date = request()->input("end_date");
        $end = Carbon::createFromFormat('d/m/Y', $end_date);
        $type = request()->input('type');
        $occurance_number = request()->input('occurance_number');

        $days = array();
        $success = false;
        if ($type == 'week') {
            $weekdays = request()->input('weekdays');
            $start->next($weekdays);
            $scheduledMaintenence = new ScheduledMaintenence();
            $scheduledMaintenence->artefact_id = request()->input('artefact_id');
            $scheduledMaintenence->maintenence_type = 'Perodic';
            $scheduledMaintenence->maintenence_description = request()->input('maintenence_description');
            $scheduledMaintenence->save();

            while ($start->lte($end)) {
                $scDate = new ScheduledMaintenenceDate();
                $scDate->scheduled_maintenence_id = $scheduledMaintenence->id;
                $scDate->maintenence_date = $start->addWeeks($occurance_number);
                $scDate->save();
            }
            $success = true;
        } else {
            $monthDay = request()->input("month_day");
            $start_d = Carbon::createFromDate(null, null, $monthDay);
            $scheduledMaintenence = new ScheduledMaintenence();
            $scheduledMaintenence->artefact_id = request()->input('artefact_id');
            $scheduledMaintenence->maintenence_type = 'Perodic';
            $scheduledMaintenence->maintenence_description = request()->input('maintenence_description');
            $scheduledMaintenence->save();
            while ($start_d->lt($end)) {
                $scDate = new ScheduledMaintenenceDate();
                $scDate->scheduled_maintenence_id = $scheduledMaintenence->id;
                $scDate->maintenence_date = $start_d->addMonths($occurance_number);
                $scDate->save();
            }
            $success = true;
        }

        if ($success) {
            flash("Maintenence Scheduled Succesfully", "success");
            return response()->redirectTo('/maintenence');
        } else {
            flash("Failed to Create Maintenence Scheduled", "error");
            return response()->redirectTo('/maintenence');
        }
    }

    function task($id = 0)
    {
        if (Auth::user()) {
            $result = "";
            $data = $artefacts = \DB::table('artefacts')
                ->select(array(
                    'artefacts.artefact_name',
                    'artefact_types.artefact_type_long',
                    'scheduled_maintenence_dates.maintenence_date',
                    'scheduled_maintenences.maintenence_description',
                    'scheduled_maintenence_dates.id'
                ))
                ->leftJoin('artefact_types', 'artefacts.artefact_type', '=', 'artefact_types.id')
                ->leftJoin('scheduled_maintenences', 'artefacts.id', '=', 'scheduled_maintenences.artefact_id')
                ->leftJoin('scheduled_maintenence_dates', 'scheduled_maintenences.id', '=', 'scheduled_maintenence_dates.scheduled_maintenence_id');


            switch ($id) {
                case 0:
                    $result = $data
                        ->whereRaw('scheduled_maintenence_dates.maintenence_date <= ?', [Carbon::now()->endOfWeek()->toDateString()])
                        ->whereRaw("scheduled_maintenence_dates.is_completed = ?", [false])
                        ->orderBy('scheduled_maintenence_dates.maintenence_date')
                        ->get();

                    break;
                case 1:
                    $result = $data
                        ->whereRaw('scheduled_maintenence_dates.maintenence_date <= ?', [Carbon::now()->endOfWeek()->toDateString()])
                        ->whereRaw("scheduled_maintenence_dates.is_completed = ?", [false])
                        ->orderBy('scheduled_maintenence_dates.maintenence_date')
                        ->get();

                    break;
                case 2:
                    $result = $data
                        ->whereRaw('scheduled_maintenence_dates.maintenence_date <= ?', [Carbon::now()->endOfMonth()->toDateString()])
                        ->whereRaw("scheduled_maintenence_dates.is_completed = ?", [false])
                        ->orderBy('scheduled_maintenence_dates.maintenence_date')
                        ->get();

                    break;
                case 3:
                    $result = $data
                        ->whereRaw('scheduled_maintenence_dates.maintenence_date <= ?', [Carbon::now()->endOfYear()->toDateString()])
                        ->whereRaw("scheduled_maintenence_dates.is_completed = ?", [false])
                        ->orderBy('scheduled_maintenence_dates.maintenence_date')
                        ->get();

                    break;
                default:
                    $result = $data
                        ->whereRaw('scheduled_maintenence_dates.maintenence_date <= ?', [Carbon::now()->addWeek()->toDateString()])
                        ->whereRaw("scheduled_maintenence_dates.is_completed = ?", [false])
                        ->orderBy('scheduled_maintenence_dates.maintenence_date')
                        ->get();

                    break;
            }
            return view('task')->with('result', $result);

        } else {
            return response()->redirectTo("/");
        }
    }

    function doTask($taskId = 0)
    {
        if (Auth::user()) {
            if ($taskId != 0) {
                /***
                 *
                 * SELECT conditional_reports.*
                 * FROM conditional_reports
                 * LEFT JOIN conditional_reports_segments segment ON conditional_reports.conditional_reports_segments_id = segment.id
                 * LEFT JOIN artefact_types at on segment.artefact_type_id = at.id
                 * LEFT JOIN artefacts a ON at.id = a.artefact_type
                 * LEFT JOIN scheduled_maintenences sm ON a.id = sm.artefact_id
                 * LEFT JOIN scheduled_maintenence_dates  ON sm.id = scheduled_maintenence_dates.scheduled_maintenence_id
                 * WHERE scheduled_maintenence_dates.id=170;
                 */
//
//                $results = DB::table('conditional_reports_segments')
//                    ->select(array(
//                        "conditional_reports_segments.id",
//                        "conditional_reports_segments.segment_title",
//                        "conditional_reports.conditional_report_title",
//                        "conditional_reports.conditional_report_html_type",
//                        "conditional_reports.conditional_report_pick_flag",
//                        "conditional_reports.conditional_report_pick_data",
//                    ))
//                    ->leftJoin('conditional_reports_segments', "conditional_reports.conditional_reports_segments_id", " = ", "conditional_reports_segments.id")
//                    ->leftJoin('artefact_types', "conditional_reports_segments.artefact_type_id", " = ", "artefact_types.id")
//                    ->leftJoin('artefacts', "artefact_types.id", " = ", "artefacts.artefact_type")
//                    ->leftJoin('scheduled_maintenences', "artefacts.id", " = ", "scheduled_maintenences.artefact_id")
//                    ->leftJoin('scheduled_maintenence_dates', "scheduled_maintenences.id", " = ", "scheduled_maintenence_dates.scheduled_maintenence_id")
//                    ->where("scheduled_maintenence_dates.id", $taskId)
//                    ->get();


                $full_report = array();

                $res = ScheduledMaintenenceDate::find($taskId)
                    ->scheduledMaintenence()->first()->artefactId()->first()->artefactType()->first()->segment()->get();

                $artefact = Artefact::find(ScheduledMaintenenceDate::find($taskId)
                    ->scheduledMaintenence()->first()->artefact_id);

                $type = ArtefactType::find($artefact->artefact_type);

                \Debugbar::addMessage($artefact);
                return view('dotask')
                    ->with('artefact', $artefact)
                    ->with('type', $type)
                    ->with('taskId', $taskId)
                    ->with('segments', $res);

            } else {
                return response()->redirectTo('/');
            }
        } else {
            return response()->redirectTo('/');
        }
    }

    function saveConditionalReport()
    {
        $taskId = "";
        $requestData = array();
        foreach (request()->all() as $key => $reqData) {
            if ($key == 'taskId') {
                $taskId = $reqData;
            } else {
                array_push($requestData, array(
                    "cr_id" => $key,
                    "cr_value" => $reqData
                ));
            }

        }
        $crd = ScheduledMaintenenceDate::find($taskId);
        $crd->is_completed = true;
        $crd->conditional_report_result_data = $requestData;
        $crd->user_id = Auth::user()->id;
        if ($crd->save()) {
            flash("Report Saved Succesfully", "success");
            return response()->redirectTo('/task');
        } else {
            flash("Failed to save Report", "error");
            return response()->redirectTo('/doTask/' . $taskId);
        }

        // dd(json_encode($requestData));
    }

    function crview($id = 0)
    {
        if (Auth::user()) {
            if ($id == 0) {
                return response()->redirectTo('/');
            } else {


                /***
                 *
                 * SELECT scheduled_maintenence_dates.maintenence_date,
                 * scheduled_maintenence_dates.updated_at,
                 * scheduled_maintenence_dates.created_at,
                 * artefacts.artefact_name
                 * FROM scheduled_maintenence_dates
                 * LEFT JOIN scheduled_maintenences ON scheduled_maintenence_dates.scheduled_maintenence_id = scheduled_maintenences.id
                 * LEFT JOIN artefacts ON scheduled_maintenences.artefact_id = artefacts.id
                 * WHERE scheduled_maintenence_dates.is_completed=true
                 * AND artefacts.id=1091
                 */
                $result = DB::table('scheduled_maintenence_dates')
                    ->select(array(
                        'scheduled_maintenence_dates.maintenence_date',
                        'scheduled_maintenence_dates.id',
                        'scheduled_maintenence_dates.updated_at',
                        'scheduled_maintenence_dates.created_at',
                        'artefacts.artefact_name'
                    ))
                    ->leftJoin('scheduled_maintenences', 'scheduled_maintenence_dates.scheduled_maintenence_id', '=', 'scheduled_maintenences.id')
                    ->leftJoin('artefacts', 'scheduled_maintenences.artefact_id', '=', 'artefacts.id')
                    ->whereRaw('scheduled_maintenence_dates.is_completed = ? AND artefacts.id = ?', [true, $id])
                    ->get();

                $artefact_name = Artefact::find($id)->get();
                //dd($result);
                return view('report.crview')
                    ->with('artefact', $artefact_name)
                    ->with('schedule', $result);
            }
        } else {
            return response()->redirectTo('/');
        }
    }

    function crReportPrint($id = 0)
    {
        if ($id != 0) {
            $report = ScheduledMaintenenceDate::find($id);
            $res = ScheduledMaintenenceDate::find($id)
                ->scheduledMaintenence()->first()->artefactId()->first()->artefactType()->first()->segment()->get();
            //dd($report);
            $user_name = User::find($report->user_id)->fname . " " . User::find($report->user_id)->lname;
            if ($report) {
                $pdf = \PDF::loadView('pdf.crreport', array(
                    'report2' => $report,
                    'segments' => $res,
                    'user' => $user_name,
                    'type' => $report->scheduledMaintenence()->first()->artefactId()->first()->artefactType()->first()->artefact_type_long,
                    'artefact_name' => $report->scheduledMaintenence()->first()->artefactId()->first()->artefact_name
                ));
//                return view('pdf.crreport')
//                    ->with('report2',$report)
//                    ->with('user',$user_name)
//                    ->with('artefact_name',$report->scheduledMaintenence()->first()->artefactId()->first()->artefact_name)
//                    ->with('type',$report->scheduledMaintenence()->first()->artefactId()->first()->artefactType()->first()->artefact_type_long)
//                    ->with('segments',$res);
                return $pdf->stream();
            } else {
                return response()->redirectTo('/');
            }
        } else {
            return response()->redirectTo('/');
        }
    }

    function reports()
    {
        if (Auth::user()) {

            return view('reports');
        } else {
            return response()->redirectTo('/');
        }
    }

    function getCicoWithDates()
    {
        if (Auth::user()) {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');

            $cico = Cico::with('artefact', 'user')
                ->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->orWhereBetween('updated_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->get();
            return response()->json($cico);
        } else {
            return response()->redirectTo('/');
        }
    }


    function getCRWithDates()
    {
        if (Auth::user()) {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');

            $cico = ScheduledMaintenenceDate::with('scheduledMaintenence', 'users', 'scheduledMaintenence.artefactId')
                ->where('is_completed', true)
                ->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->orWhereBetween('updated_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->get();
            return response()->json($cico);
        } else {
            return response()->redirectTo('/');
        }
    }

    function cicoReportPrint($start = 0, $end = 0)
    {
        if (Auth::user()) {
            $start_date = $start;
            $end_date = $end;

            if ($start == 0 || $end == 0) {
                return response()->redirectTo('/');
            }
            $cico = Cico::with('artefact', 'user')
                ->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->orWhereBetween('updated_at', [Carbon::createFromFormat('d-m-Y', $start_date), Carbon::createFromFormat('d-m-Y', $end_date)])
                ->get();
            $pdf = \PDF::loadView('pdf.cicoreport', array(
                'datas' => $cico
            ))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } else {
            return response()->redirectTo('/');
        }
    }

    function help()
    {
        return response()->file(storage_path('config/manual/GAS_manual.pdf'));
    }

    function search($id = 0)
    {
        if (Auth::user()) {
            $artefactTypes = User::find(Auth::user()->id)->artefact_type()->get();
            $location = Location::active()->whereIsArchiveLocation(true)->get();

            $codes = null;

            if ($id != 0) {
                $codes = ArtefactTypeAttribute::whereArtefactTypeId($id)
                    ->whereActive(true)
                    ->whereIsSearchable(true)
                    ->get();
            }
            return view('search')
                ->with('artefacttypes', $artefactTypes)
                ->with('locations', $location)
                ->with('attr_id', $id)
                ->with('attributes', $codes);
        } else {
            flash('Login first', 'error');
            return response()->redirectTo('/');
        }
    }

    function getAutocomplete($attr)
    {
        $dValue = DB::table('artefact')
            ->select('artefact_values->' . $attr . '->attr_value')
            ->get();

        return response()->json($dValue);
    }

    function searchTable($page = 0)
    {

        $myResult = Artefact::with("location", "parent", "user")
            ->select(array(
                "artefacts.id",
                "artefact_name",
                "artefact_values",
                "parent_id"
            ))
            ->where('artefact_type', request()->input('artefact_type'))
            ->where('active', true);
        $i = 0;
        foreach (request()->all() as $key => $data) {
            if ($key == 'artefact_name') {
                if (trim($data) != '') {
                    $myResult->where('artefact_name', 'like', '%' . trim($data) . '%');
                }
            } else if ($key != 'artefact_type') {
                if (trim($data) != "") {
                    $i++;
                    $key_column = "data_" . $key;
                    if ($i == 1) {
                        $myResult->where("artefact_values->" . $key_column . "->attr_value", 'like', '%' . trim($data) . '%');
                        // $myResult->whereRaw("artefact_values->'" . $key_column . "'->'attr_value' like '%?%'", [$data]);
                    } else {
                        $myResult->orwhere("artefact_values->" . $key_column . "->attr_value", 'like', '%' . trim($data) . '%');
                        //$myResult->orWhereRaw("artefact_values->'" . $key_column . "'->'attr_value' like '%?%'", [$data]);
                    }
                }
            }
        }
        //ini_set('zend.enable_gc', '0');


        $res = $myResult->take(env('LIMIT_RANGE', 30))->skip($page * env('LIMIT_RANGE', 30));
        \Log::info($res->toSql());
        return response()->json($res->get());
    }


    function about()
    {
        $org = json_decode(\Guzzle::get(env('APP_GIT'))->getBody());

        $detail = json_decode(\Guzzle::get('https://api.github.com/repos/poovarasanvasudevan/New-Gloval-Archive')->getBody());

        return view('about')
            ->with('details', $detail)
            ->with('data', $org);
    }
}
