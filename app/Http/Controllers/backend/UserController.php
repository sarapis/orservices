<?php

namespace App\Http\Controllers\backEnd;

use App\Http\Controllers\Controller;
use App\Model\EmailTemplate;
use App\Model\Layout;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Role;
use App\Model\ServiceTag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use OwenIt\Auditing\Models\Audit;
use SendGrid;
use SendGrid\Mail\Mail;

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
            $users = User::orderBy('id', 'desc')->get();
            $layout = Layout::find(1);
            $authUser = Auth::user();
            $organizations = Organization::pluck('organization_name', 'organization_name');
            if ($type) {
                $role = Role::where('name', $type)->first();

                $users = User::where('role_id', $role->id)->get();
            }
            $roles = Role::pluck('name', 'name');

            return View('backEnd.users.index', compact('users', 'layout', 'authUser', 'organizations', 'roles'));
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in user controller index : ' . $th);
            return redirect('dashboard');
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
            $EmailTemplate = EmailTemplate::whereId(1)->where('status', 1)->first();

            if ($EmailTemplate) {
                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = $EmailTemplate->subject;

                $email->setSubject($subject);

                // $body = $request->message;
                $data = array(
                    '{first_name}' => $request->get('first_name') . ' ' . $request->get('last_name'),
                    '{password}' => $request->get('password'),
                );
                $body = $EmailTemplate->body;

                foreach ($data as $key => $value) {
                    //replace the email template body string to user detail
                    $body = str_replace($key, $value, $body);
                }

                $message = '<html><body>';
                $message .= $body;
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';
                // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                // foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($request->email, $username);
                // }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
            }
            $ActivationEmail = EmailTemplate::whereId(2)->where('status', 1)->first();
            if ($ActivationEmail && $request->role) {
                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = $ActivationEmail->subject;

                $email->setSubject($subject);

                // $body = $request->message;
                $data = array(
                    '{first_name}' => $request->get('first_name') . ' ' . $request->get('last_name'),
                    '{password}' => $request->get('password'),
                );
                $body = $ActivationEmail->body;

                foreach ($data as $key => $value) {
                    //replace the email template body string to user detail
                    $body = str_replace($key, $value, $body);
                }

                $message = '<html><body>';
                $message .= $body;
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';
                // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                // foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($request->email, $username);
                // }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
            }
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
        $account_organization_tag_list = explode(',', $user->organization_tags);
        $account_service_tag_list = explode(',', $user->service_tags);
        $organization_tags = OrganizationTag::pluck('tag', 'id');
        $service_tags = ServiceTag::pluck('tag', 'id');
        return View('backEnd.users.edit', compact('user', 'roles', 'layout', 'organization_list', 'account_organization_list', 'organization_tags', 'account_organization_tag_list', 'service_tags', 'account_service_tag_list'));
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
            if ($request->verifier) {
                $user->verifier = $request->verifier;
            }
            if ($request->user_organizations) {
                $user->user_organization = join(',', $request->user_organizations);
            } else {
                $user->user_organization = '';
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
                $ActivationEmail = EmailTemplate::whereId(2)->where('status', 1)->first();
                $userData = User::whereId($id)->first();

                if ($ActivationEmail && $request->role && $userData && $request->role != $userData->role_id) {
                    $from = env('MAIL_FROM_ADDRESS');
                    $name = env('MAIL_FROM_NAME');
                    $email = new Mail();
                    $email->setFrom($from, $name);
                    // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                    $subject = $ActivationEmail->subject;

                    $email->setSubject($subject);

                    // $body = $request->message;
                    $data = array(
                        '{first_name}' => $request->get('first_name') . ' ' . $request->get('last_name'),
                        '{password}' => $request->get('password'),
                    );
                    $body = $ActivationEmail->body;

                    foreach ($data as $key => $value) {
                        //replace the email template body string to user detail
                        $body = str_replace($key, $value, $body);
                    }

                    $message = '<html><body>';
                    $message .= $body;
                    $message .= '</body></html>';

                    $email->addContent("text/html", $message);
                    $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                    $error = '';

                    $username = '';
                    // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                    // foreach ($contact_email_list as $key => $contact_email) {
                    $email->addTo($request->email, $username);
                    // }
                    $response = $sendgrid->send($email);
                    if ($response->statusCode() == 401) {
                        $error = json_decode($response->body());
                    }
                }
            }
            if ($request->user_organizations) {
                $user->organizations()->sync($request->user_organizations);
            } else {
                $user->organizations()->sync([]);
            }
            if ($request->organization_tags) {
                $user->organization_tags = implode(',', $request->organization_tags);
            } else {
                $user->organization_tags = '';
            }
            if ($request->service_tags) {
                $user->service_tags = implode(',', $request->service_tags);
            } else {
                $user->service_tags = '';
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
            Log::error('Error in ahax_all : ' . $th);
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
    public function changelog($id)
    {
        try {
            $user = User::whereId($id)->first();
            $audits = Audit::where('user_id', $id)->get();
            return view('backEnd.users.editsLogs', compact('audits', 'user'));
        } catch (\Throwable $th) {
            Log::error('Error in ChangeLog : ' . $th);
        }
    }
    public function send_activation($id)
    {
        try {
            $ActivationEmail = EmailTemplate::whereId(2)->where('status', 1)->first();
            $user = User::whereId($id)->first();
            if ($ActivationEmail) {
                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = $ActivationEmail->subject;

                $email->setSubject($subject);

                // $body = $request->message;
                $data = array(
                    '{first_name}' => $user->first_name . ' ' . $user->last_name,
                    '{password}' => '',
                );
                $body = $ActivationEmail->body;

                foreach ($data as $key => $value) {
                    //replace the email template body string to user detail
                    $body = str_replace($key, $value, $body);
                }

                $message = '<html><body>';
                $message .= $body;
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';
                // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                // foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($user->email, $username);
                // }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                    dd($error);
                }
                Session::flash('message', 'Success! Activation Email Send successfully.');
                Session::flash('status', 'success');
            } else {
                Session::flash('message', 'Error! Something Went Wrong, Please Try Again.');
                Session::flash('status', 'error');
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! Something Went Wrong, Please Try Again.');
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function invite_user($id)
    {
        try {
            $invitationEmail = EmailTemplate::whereId(3)->where('status', 1)->first();
            $user = User::whereId($id)->first();
            if ($invitationEmail) {
                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = $invitationEmail->subject;

                $email->setSubject($subject);

                // $body = $request->message;
                $data = array(
                    '{first_name}' => $user->first_name . ' ' . $user->last_name,
                    '{password}' => '',
                );
                $body = $invitationEmail->body;

                foreach ($data as $key => $value) {
                    //replace the email template body string to user detail
                    $body = str_replace($key, $value, $body);
                }

                $message = '<html><body>';
                $message .= $body;
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';
                // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                // foreach ($contact_email_list as $key => $contact_email) {
                $email->addTo($user->email, $username);
                // }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
                Session::flash('message', 'Success! Invitation link Send successfully.');
                Session::flash('status', 'success');
            } else {
                Session::flash('message', 'Error! Something Went Wrong, Please Try Again.');
                Session::flash('status', 'error');
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! Something Went Wrong, Please Try Again.');
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
}
