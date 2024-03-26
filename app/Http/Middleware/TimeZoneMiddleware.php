<?php

namespace App\Http\Middleware;

use App\Model\Layout;
use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class TimeZoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $layout = Layout::find(1);
        if ($layout && $layout->timezone) {
            date_default_timezone_set($layout->timezone);
            Config::set('app.timezone', $layout->timezone);

            Artisan::call('cache:clear');
            Artisan::call('config:clear');
        } else {
            date_default_timezone_set('UTC');
        }

        return $next($request);
    }
}
