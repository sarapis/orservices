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
            $taxonomies = \App\Taxonomy::where('taxonomy_parent_name', '=', NULL)->orderBy('taxonomy_name', 'asc')->get();
            $view->with('taxonomies', $taxonomies);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $ages = \App\Detail::where('detail_type', '=', 'Ages Served')->orderBy('detail_value', 'asc')->get();
            $view->with('ages', $ages);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $languages = \App\Detail::where('detail_type', '=', 'Language(s)')->orderBy('detail_value', 'asc')->get();
            $view->with('languages', $languages);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $service_settings = \App\Detail::where('detail_type', '=', 'Service Setting')->orderBy('detail_value', 'asc')->get();
            $view->with('service_settings', $service_settings);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $insurances = \App\Detail::where('detail_type', '=', 'insurance')->orderBy('detail_value', 'asc')->get();
            $view->with('insurances', $insurances);
        });

        view()->composer('layouts.sidebar', function($view)
        {
            $culturals = \App\Detail::where('detail_type', '=', 'Cultural Competencies')->orderBy('detail_value', 'asc')->get();
            $transportations = \App\Detail::where('detail_type', '=', 'Transportation')->orderBy('detail_value', 'asc')->get();

            $view->with('culturals', $culturals);
        });

        view()->composer('layouts.sidebar', function($view)
        {

            $transportations = \App\Detail::where('detail_type', '=', 'Transportation')->orderBy('detail_value', 'asc')->get();

            $view->with('transportations', $transportations);
        });

        view()->composer('layouts.sidebar', function($view)
        {

            $hours = \App\Detail::where('detail_type', '=', 'Additional Hours')->orderBy('detail_value', 'asc')->get();

            $view->with('hours', $hours);
        });

        view()->composer('layouts.script', function($view)
        {
            $map = \App\Map::find(1);
            $view->with('map', $map);
        });

        view()->composer('layouts.analytics', function($view)
        {
            $analytics = \App\Page::find(4);
            $view->with('analytics', $analytics);
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
