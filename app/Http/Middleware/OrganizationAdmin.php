<?php

namespace App\Http\Middleware;

use App\Model\Layout;
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
        $layout = Layout::find(1);

        $routeName = $request->route()->getName();
        if ($routeName == 'account.show' && $user->roles == null) {
            Session::flash('message', 'Warning! Your Account is Under Review');
            Session::flash('status', 'warning');
            return redirect()->back();
        }

        if ($user && $user->roles && $user->roles->name == 'Organization Admin' && ($routeName == 'users_lists.edit' || $routeName == 'users_lists.index' || $routeName == 'users_lists.create' || $routeName == 'tracking.edit' || $routeName == 'tracking.index' || $routeName == 'tracking.create')) {
            Session::flash('message', "Error: You don't have permission to view that page. Please contact the site administrator for more details.");
            Session::flash('status', 'error');
            return redirect('/');
        }
        if (($user && $user->roles && $user->roles->name == 'System Admin' || $user && $user->roles && ($user->roles->name == 'Organization Admin' || $user->roles->name == 'Section Admin') || $user && $routeName != 'contacts.index' && $routeName != 'facilities.index' && $routeName != 'facilities.edit' && $routeName != 'facilities.update' && $routeName != 'facilities.show' && $routeName != 'contacts.edit' && $routeName != 'organizations.create' && $routeName != 'organizations.edit' && $routeName != 'services.edit' && $routeName != 'users_lists.edit' && $routeName != 'users_lists.index' && $routeName != 'users_lists.create') || ($user == null && ($routeName == 'organizations.index' || $routeName == 'organizations.show')) || $user == null && ($routeName == 'services.index' || $routeName == 'services.show' || ((($layout->show_suggest_menu == 1 && Auth::check()) || $layout->show_suggest_menu == 0) && $routeName == 'suggest.create')) || $routeName == 'suggest.store' || $routeName == 'about') {
            return $next($request);
        } else {
            if (!empty($api)) {
                return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
            } else {
                Session::flash('message', "Error: You don't have permission to view that page. Please contact the site administrator for more details.");
                Session::flash('status', 'error');
                return redirect('/');
            }
        }
    }
}
