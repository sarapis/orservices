<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\EmailTemplate;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Role;
use App\Model\ServiceTag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use SendGrid;
use SendGrid\Mail\Mail;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('*');
        $authUser = Auth::user();
        // if ($authUser->roles && $authUser->roles->name == 'System Admin') {
        $users = $users->get();
        // } else {
        //     $users = $users->where('created_by', Auth::id())->get();
        // }
        $map = Map::find(1);
        $organizations = Organization::pluck('organization_name', 'organization_name');
        return view('frontEnd.users.index', compact('users', 'authUser', 'map', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $organizations = Organization::orderBy('organization_name')->pluck('organization_name', "organization_recordid");
        $roles = Role::where('name', 'Organization Admin')->pluck('name', 'id');

        return view('frontEnd.users.create', compact('map', 'organizations', 'roles'));
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
            // $EmailTemplate = EmailTemplate::whereId(1)->where('status', 1)->first();

            // if ($EmailTemplate) {
            //     $from = env('MAIL_FROM_ADDRESS');
            //     $name = env('MAIL_FROM_NAME');
            //     $email = new Mail();
            //     $email->setFrom($from, $name);
            //     // $subject = 'A Suggested Change was Submitted at ' . $site_name;
            //     $subject = $EmailTemplate->subject;

            //     $email->setSubject($subject);

            //     // $body = $request->message;
            //     $data = array(
            //         '{first_name}' => $request->get('first_name') . ' ' . $request->get('last_name'),
            //         '{password}' => $request->get('password'),
            //     );
            //     $body = $EmailTemplate->body;

            //     foreach ($data as $key => $value) {
            //         //replace the email template body string to user detail
            //         $body = str_replace($key, $value, $body);
            //     }

            //     $message = '<html><body>';
            //     $message .= $body;
            //     $message .= '</body></html>';

            //     $email->addContent("text/html", $message);
            //     $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

            //     $error = '';

            //     $username = '';
            //     // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

            //     // foreach ($contact_email_list as $key => $contact_email) {
            //     $email->addTo($request->email, $username);
            //     // }
            //     $response = $sendgrid->send($email);
            //     if ($response->statusCode() == 401) {
            //         $error = json_decode($response->body());
            //     }
            // }
            // $ActivationEmail = EmailTemplate::whereId(2)->where('status', 1)->first();
            // if ($ActivationEmail && $request->role) {
            //     $from = env('MAIL_FROM_ADDRESS');
            //     $name = env('MAIL_FROM_NAME');
            //     $email = new Mail();
            //     $email->setFrom($from, $name);
            //     // $subject = 'A Suggested Change was Submitted at ' . $site_name;
            //     $subject = $ActivationEmail->subject;

            //     $email->setSubject($subject);

            //     // $body = $request->message;
            //     $data = array(
            //         '{first_name}' => $request->get('first_name') . ' ' . $request->get('last_name'),
            //         '{password}' => $request->get('password'),
            //     );
            //     $body = $ActivationEmail->body;

            //     foreach ($data as $key => $value) {
            //         //replace the email template body string to user detail
            //         $body = str_replace($key, $value, $body);
            //     }

            //     $message = '<html><body>';
            //     $message .= $body;
            //     $message .= '</body></html>';

            //     $email->addContent("text/html", $message);
            //     $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

            //     $error = '';

            //     $username = '';
            //     // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

            //     // foreach ($contact_email_list as $key => $contact_email) {
            //     $email->addTo($request->email, $username);
            //     // }
            //     $response = $sendgrid->send($email);
            //     if ($response->statusCode() == 401) {
            //         $error = json_decode($response->body());
            //     }
            // }
            Session::flash('message', 'Success! User is created successfully.');
            Session::flash('status', 'success');
            return redirect('users_lists');
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $layout = Layout::find(1);
        $map = Map::find(1);

        $roles = Role::get()->pluck('name', 'id');
        $organization_list = Organization::select('organization_recordid', 'organization_name')->get();
        $account_organization_list = explode(',', $user->user_organization);
        $account_organization_tag_list = explode(',', $user->organization_tags);
        $account_service_tag_list = explode(',', $user->service_tags);
        $organization_tags = OrganizationTag::pluck('tag', 'id');
        $service_tags = ServiceTag::pluck('tag', 'id');
        return View('frontEnd.users.edit', compact('user', 'roles', 'layout', 'organization_list', 'account_organization_list', 'organization_tags', 'account_organization_tag_list', 'service_tags', 'account_service_tag_list', 'map'));
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
            return redirect('users_lists');
            Session::flash('message', 'Success! User is updated successfully.');
            Session::flash('status', 'success');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
