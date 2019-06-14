<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Layout;
use App\Service;
use App\Organization;
use App\Taxonomy;
use App\Map;
use App\Location;
use App\Analytic;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home($value='')
    {
        $home = Layout::find(1);
        $map = Map::find(1);
        $taxonomies = Taxonomy::where('taxonomy_parent_name', '=', NULL)->orderBy('taxonomy_name', 'asc')->get();
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.home', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    public function about($value='')
    {
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        $about = Page::where('name', 'About')->first();
        $home = Page::where('name', 'Home')->first();
        $map = Map::find(1);

        return view('frontEnd.about', compact('about', 'home', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    public function feedback($value='')
    {
        $feedback = Page::where('name', 'Feedback')->first();
        return view('frontEnd.feedback', compact('feedback'));
    }

    public function YourhomePage($value='')
    {
        return view('home');
    }

    public function dashboard($value='')
    {
        $layout = Layout::first();
        return view('backEnd.dashboard', compact('layout'));
    }

    public function logviewerdashboard($value='')
    {
        return redirect('log-viewer');
    }

    public function search(Request $request)
    {
        $chip_service = $request->input('find');
        $chip_title ="Search for Services:";

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        $services= Service::with(['organizations', 'taxonomy'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->paginate(10);
        $search_results = Service::with(['organizations', 'taxonomy'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->count();
        $locations = Location::with('services','organization')->get();
        $map = Map::find(1);

        $analytic = Analytic::where('search_term', '=', $chip_service)->first();
        if(isset($analytic)){
            $analytic->search_term = $chip_service;
            $analytic->search_results = $search_results;
            $analytic->times_searched = $analytic->times_searched + 1;
            $analytic->save();
        }
        else{
            $new_analytic = new Analytic();
            $new_analytic->search_term = $chip_service;
            $new_analytic->search_results = $search_results;
            $new_analytic->times_searched = 1;
            $new_analytic->save();
        }
        // $services =Service::where('service_name',  'like', '%'.$search.'%')->get();
        return view('frontEnd.services', compact('services','locations', 'chip_title', 'chip_service', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results'));
    }
}
