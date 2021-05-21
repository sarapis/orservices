<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Throwable;

// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        $layout = Layout::first();
        return view('auth.login', compact('layout'));
    }
    public function login(Request $request)
    {

        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $email = $request->email;
            $password = $request->password;

            if ($validation->fails()) {
                return Redirect::back()->withErrors($validation)->withInput();
            }
            $remember = ($request->get('remember') == 'on') ? true : false;

            if ($user = Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                
                return redirect()->intended('home');
            }

            return Redirect::back()->withErrors(['global' => 'Invalid password or this user does not exist']);
        } catch (Throwable $th) {
            return Redirect::back()->withErrors(['global' => 'This user is not activated', 'activate_contact' => 1]);
        }
        // catch (ThrottlingException $e) {
        //     $delay = $e->getDelay();
        //     return Redirect::back()->withErrors(['global' => 'You are temporary susspended' . ' ' . $delay . ' seconds', 'activate_contact' => 1]);
        // }

        return Redirect::back()->withErrors(['global' => 'Login problem please contact the administrator']);
    }

    protected function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
