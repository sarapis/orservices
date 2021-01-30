<?php

namespace App\Http\Middleware;

use Closure;

class TimeZoneMiddleware
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
        if (env('TIME_ZONE')) {
            // \Config::set('app.timezone', env('TIME_ZONE'));
            date_default_timezone_set(env('TIME_ZONE'));
        } else {
            date_default_timezone_set(env('UTC'));
        }
        return $next($request);
    }
}
