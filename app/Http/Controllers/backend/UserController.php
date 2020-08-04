<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Organization;
use App\Model\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth')->except('orders');
        // $this->middleware('auth');
    }
    protected function validator(Request $request, $id = '')
    {
        return Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:35|string',
            'last_name' => 'required|min:2|max:35|string',
            // 'email' => Sentinel::inRole('Admin') ? 'required|email|min:3|max:50|string' : (Sentinel::check() ? 'required|email|min:3|max:50|string|unique:users,email,' . $id : 'required|email|min:3|max:50|unique:users|string'),
            'password' => 'min:6|max:50|confirmed',
            //'gender' => 'required',
            'role' => 'required',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $type = $request->type;
            $users = User::all();
            $layout = Layout::find(1);
            $authUser = Auth::user();
            if ($type) {
                $role = Role::where('name', $type)->first();

                $users = User::where('role_id', $role->id)->get();
            }

            return View('backEnd.users.index', compact('users', 'layout', 'authUser'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $layout = Layout::find(1);

        $roles = Role::get()->pluck('name', 'id');
        return View('backEnd.users.create', compact('roles', 'layout'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        try {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role;
            $user->created_by = Auth::id();
            $user->save();
            Session::flash('message', 'Success! User is created successfully.');
            Session::flash('status', 'success');
            return redirect()->route('user.index');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $layout = Layout::find(1);

        $type = $user->role;

        if ($request->is('api/*')) {
            $user = User::where('id', $id)->with('activations', 'roles')->get();
            return response()->json(compact('user'));
        }
        return View('backEnd.users.show', compact('user', 'type', 'layout'));
    }
    public function accountFrontEnd(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->roles->name = 'System Admin') {
            $user = User::findOrFail($id);
            return view('frontend.userAcount', compact('user'));
        }

        return view('frontend.userAcount', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $layout = Layout::find(1);

        $roles = Role::get()->pluck('name', 'id');
        $organization_list = Organization::select('organization_recordid', 'organization_name')->get();
        $account_organization_list = explode(',', $user->user_organization);
        return View('backEnd.users.edit', compact('user', 'roles', 'layout','organization_list','account_organization_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // $update_user = Validator::make($request->all(), [
        //     'first_name' => 'min:2|max:35|string',
        //     'last_name' => 'min:2|max:35|string',
        // ]);

        // if ($update_user->fails()) {
        //     return redirect()->back()
        //         ->withErrors($update_user)
        //         ->withInput();
        // }

        $user = User::find($id);
        if ($user) {

            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }
            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }
            if ($request->email) {
                $user->email = $request->email;
            }
            if ($request->user_organizations) {
              $user->user_organization = join(',', $request->user_organizations);
              }
            if ($request->new_password && $request->new_password_confirmation) {
                if ($request->new_password == $request->new_password_confirmation) {
                    $user->password = Hash::make($request->new_password);
                } else {
                    Session::flash('message', 'Your old password is incorrect.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withErrors(['old_password', 'your old password is incorrect']);
                }
            }
            if ($request->role) {
                $user->role_id = $request->role;
            }
            if ($request->user_organizations) {
              $user->organizations()->sync($request->user_organizations);
            }
            $user->update();
            Session::flash('message', 'Success! User is updated successfully.');
            Session::flash('status', 'success');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Session::flash('message', 'Success! User is deleted successfully.');
        Session::flash('status', 'success');

        return redirect()->route('user.index');
    }

    public function permissions($id)
    {
        $user = User::findOrfail($id);
        $routes = Route::getRoutes();
        $layout = Layout::find(1);

        //Api Route
        // $api = app('api.router');
        // /** @var $api \Dingo\Api\Routing\Router */
        // $routeCollector = $api->getRoutes(config('api.version'));
        // /** @var $routeCollector \FastRoute\RouteCollector */
        // $api_route = $routeCollector->getRoutes();

        $actions = [];
        foreach ($routes as $route) {
            if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
                $actions[] = $route->getName();
            }
        }

        //remove store option
        $input = preg_quote("store", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //remove update option
        $input = preg_quote("update", '~');
        $var = preg_grep('~' . $input . '~', $actions);
        $actions = array_values(array_diff($actions, $var));

        //Api all names
        // foreach ($api_route as $route) {
        //     if ($route->getName() != "" && !substr_count($route->getName(), 'payment')) {
        //         $actions[] = $route->getName();
        //     }
        // }

        $var = [];
        $i = 0;
        foreach ($actions as $action) {

            $input = preg_quote(explode('.', $action)[0] . ".", '~');
            $var[$i] = preg_grep('~' . $input . '~', $actions);
            $actions = array_values(array_diff($actions, $var[$i]));
            $i += 1;
        }

        $actions = array_filter($var);
        // dd (array_filter($actions));

        return View('backEnd.users.permissions', compact('user', 'actions', 'layout'));
    }

    public function save($id, Request $request)
    {
        //return $request->permissions;
        $user = User::findOrfail($id);
        // $user->permissions = [];
        $permissions = $user->permissions ? json_decode($user->permissions) : [];
        $permissions = [];
        if ($request->permissions) {
            foreach ($request->permissions as $key => $permission) {
                // if (explode('.', $permission)[1] == 'create') {
                //     $user->addPermission($permission);
                //     $user->addPermission(explode('.', $permission)[0] . ".store");
                // } else if (explode('.', $permission)[1] == 'edit') {
                //     $user->addPermission($permission);
                //     $user->addPermission(explode('.', $permission)[0] . ".update");
                // } else {
                //     $user->addPermission($permission);
                // }
                if (explode('.', $permission)[1] == 'create') {
                    // $permissions[$key] = $permission;
                    // $permissions[$key] = explode('.', $permission)[0] . ".store";
                    if (!in_array($permission, $permissions)) {
                        array_push($permissions, $permission);
                        array_push($permissions, explode('.', $permission)[0] . ".store");
                    } else {
                    }

                    // $role->addPermission(explode('.', $permission)[0] . ".store");
                } else if (explode('.', $permission)[1] == 'edit') {
                    // $permissions[$key] = $permission;
                    // $permissions[$key] = explode('.', $permission)[0] . ".update";
                    if (!in_array($permission, $permissions)) {
                        array_push($permissions, $permission);
                        array_push($permissions, explode('.', $permission)[0] . ".update");
                    }
                } else {
                    if (!in_array($permission, $permissions)) {
                        array_push($permissions, $permission);
                    }
                }
            }
        }
        $user->permissions = json_encode($permissions);

        $user->save();

        Session::flash('message', 'Success! Permissions are stored successfully.');
        Session::flash('status', 'success');

        return redirect()->route('user.index');
    }

    public function activate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->status == 1) {
            Session::flash('message', 'Warning! The user is already activated.');
            Session::flash('status', 'warning');

            return redirect('user');
        }
        // $activation = Activation::create($user);
        // $activation = Activation::complete($user, $activation->code);
        $user->status = '1';
        $user->save();

        Session::flash('message', 'Success! The user is activated successfully.');
        Session::flash('status', 'success');

        // $role = $user->roles()->first()->name;

        return redirect()->route('user.index');
    }

    public function deactivate(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->status = '0';
        $user->save();

        Session::flash('message', 'Success! The user is deactivated successfully.');
        Session::flash('status', 'success');

        return redirect()->route('user.index');
    }
    public function ajax_all(Request $request)
    {
        try {
            if ($request->action == 'delete') {
                foreach ($request->all_id as $id) {
                    $user = User::findOrFail($id)->delete();
                }
                Session::flash('message', 'Success! Users are deleted successfully.');
                Session::flash('status', 'success');
                return response()->json(['success' => true, 'status' => 'Sucesfully Deleted']);
            }
            if ($request->action == 'deactivate') {
                foreach ($request->all_id as $id) {
                    User::whereId($id)->update([
                        'status' => '0',
                    ]);
                }
                Session::flash('message', 'Success! Users are deactivate successfully.');
                Session::flash('status', 'success');
                return response()->json(['success' => true, 'status' => 'Sucesfully deactivate']);
            }
            if ($request->action == 'activate') {
                foreach ($request->all_id as $id) {
                    $user = User::findOrFail($id);
                    // $activation = Activation::completed($user);
                    // if ($activation == '') {
                    //     $activation = Activation::create($user);
                    //     $activation = Activation::complete($user, $activation->code);
                    // }
                    $user->status = '1';
                    $user->save();
                }
                Session::flash('message', 'Success! Users are Activated successfully.');
                Session::flash('status', 'success');
                return response()->json(['success' => true, 'status' => 'Sucesfully Activated']);
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function profile()
    {
        try {
            $layout = Layout::find(1);
            $user = Auth::user();
            return view('backEnd.users.profile', compact('user', 'layout'));
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function saveProfile(Request $request, $id)
    {
        if ($request->has('change_password')) {
            $this->validate($request, [
                'password' => 'required|confirmed|min:5',
            ]);
        }
        try {
            $user = User::whereId($id)->first();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            if ($request->has('change_password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            Session::flash('message', 'Profile updated successfully!');
            Session::flash('status', 'success');
            return redirect('/user');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
}
