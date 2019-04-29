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

    	return view('frontEnd.home', compact('home', 'taxonomies', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations'));
    }

    public function about($value='')
    {
        $about = Page::where('name', 'About')->first();
        $home = Page::where('name', 'Home')->first();
        return view('frontEnd.about', compact('about', 'home'));
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
        $chip_name = $request->input('find');
        $chip_title ="Search for Services:";

        $services= Service::with(['organizations', 'taxonomy'])->where('service_name', 'like', '%'.$chip_name.'%')->orwhere('service_description', 'like', '%'.$chip_name.'%')->orwhereHas('organizations', function ($q)  use($chip_name){
                    $q->where('organization_name', 'like', '%'.$chip_name.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_name){
                    $q->where('taxonomy_name', 'like', '%'.$chip_name.'%');
                })->paginate(10);
        $locations = Location::with('services','organization')->get();
        $map = Map::find(1);

        // $services =Service::where('service_name',  'like', '%'.$search.'%')->get();
        return view('frontEnd.chip', compact('services','locations', 'chip_title', 'chip_name', 'map'));
    }
}
