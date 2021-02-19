<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrganizationAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $routeName = $request->route()->getName();
        if ($routeName == 'account.show' && $user->roles == null) {
            Session::flash('message', 'Warning! Your Account is Under Review');
            Session::flash('status', 'warning');
            return redirect()->back();
        }
        if ($routeName != 'contacts.index' && $routeName != 'facilities.index' && $routeName != 'organizations.create' || $user && $user->roles->name != 'Organization Admin') {
            return $next($request);
        } else {
            if (!empty($api)) {
                return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
            } else {

                Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                return redirect()->back();
            }
        }
    }
}
