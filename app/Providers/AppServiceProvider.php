<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();

        // Only roles that held the old arcanedev viewer's permission may read the logs.
        LogViewer::auth(function ($request) {
            $role = $request->user()?->roles;
            $permissions = $role ? json_decode($role->permissions ?? '[]', true) : [];

            return in_array('log-viewer::logs.list', $permissions ?: [], true);
        });
    }
}
