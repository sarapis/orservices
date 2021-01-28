<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Email;
use App\Model\EmailTemplate;
use App\Model\Layout;
use App\Model\Organization;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use SendGrid;
use SendGrid\Mail\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function showRegistrationForm()
    {
        $layout = Layout::first();
        $organization_info_list = Organization::select("organization_name", "organization_recordid")->distinct()->get();
        return view('auth.register', compact('layout', 'organization_info_list'));
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|min:10',
            'organization' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            // 'required'  => 'Please enter a valid :attribute',
            'email.required'  => 'Please enter a valid email address',
        ]);
        // dd($request);
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_organization' =>  $request->organization,
                'phone_number' =>  $request->phone_number,
                'message' =>  $request->message,
                // 'role_id' => '2'
            ]);
            $organizations = [];
            $organizationIds = [];
            if ($request->organization) {
                $organizationIds[] = $request->organization;
            }
            if ($user) {
                $user->user_organization = join(',', $organizationIds);
                $user->save();
                $user->organizations()->sync($organizationIds);
                if ($request->organization) {
                    $organizations = Organization::whereIn('organization_recordid', $organizationIds)->pluck('organization_name')->toArray();
                }

                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                // $from_phone = env('MAIL_FROM_PHONE');

                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = 'Registraion';
                $email->setSubject($subject);

                $body = $request->message;

                $message = '<html><body>';
                $message .= '<h1 style="color:#424242;">New user registration!</h1>';
                // $message .= '<p style="color:#424242;font-size:18px;">The following change was suggested at  ' . $site_name . ' website.</p>';
                $message .= '<p style="color:#424242;font-size:12px;">ID: ' . $user->id . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . implode(',', $organizations) . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Message: ' . $body . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">From: ' . $request->first_name . ' ' . $request->last_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Email: ' . $request->email . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Phone: ' . $request->phone_number . '</p>';
                // $message .= '<p style="color:#424242;font-size:12px;">Phone: '. $from_phone .'</p>';
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = 'Larable Team';
                $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                foreach ($contact_email_list as $key => $contact_email) {
                    $email->addTo($contact_email, $username);
                }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }

                $EmailTemplate = EmailTemplate::whereId(1)->where('status', 1)->first();

                if ($EmailTemplate) {
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

                    $username = 'Larable Team';
                    // $contact_email_list = Email::select('email_info')->pluck('email_info')->toArray();

                    // foreach ($contact_email_list as $key => $contact_email) {
                    $email->addTo($request->email, $username);
                    // }
                    $response = $sendgrid->send($email);
                    if ($response->statusCode() == 401) {
                        $error = json_decode($response->body());
                    }
                }
                // $user->roles()->sync([2]); // 2 = client
                Session::flash('message', 'Thank you for submitting a registration request. Our team is evaluating it and will contact you with further instructions.');
                Session::flash('status', 'success');
            }
            return redirect('/');
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', 'There was an error with the registration');
            Session::flash('status', 'error');
            return Redirect::back();
        }
    }
}
