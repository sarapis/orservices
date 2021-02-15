<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $api = "")
    {
        // $routes = \Route::getRoutes();

        $user = Auth::user();
        $routeName = $request->route()->getName();
        if ($user->roles && $user->roles->permissions && in_array($routeName, json_decode($user->roles->permissions))) {
            return $next($request);
        } else {
            if (!empty($api)) {
                return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
            } else {
                Session::flash('message', 'Warning! You don`t have permission to access this section. Please contact a system administrator for more information.');
                Session::flash('status', 'warning');
                return redirect()->back();
            }
        }
    }
}
