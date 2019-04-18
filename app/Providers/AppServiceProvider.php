<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('layouts.sidebar', function($view)
        {
            $organizations = \App\Organization::orderBy('organization_name', 'asc')->get();
            $view->with('organizations', $organizations);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $taxonomies = \App\Taxonomy::orderBy('taxonomy_name', 'asc')->get();
            $view->with('taxonomies', $taxonomies);
        });

        view()->composer('layouts.style', function($view)
        {
            $map = \App\Map::find(1);
            $view->with('map', $map);
        });

        view()->composer('layouts.header', function($view)
        {
            $view->with('layout', \App\Layout::first());
        });

        view()->composer('layouts.footer', function($view)
        {
            $view->with('layout', \App\Layout::first());
        });

        view()->composer('backLayout.sidebarMenu', function($view)
        {
            $view->with('layout', \App\Layout::first());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
