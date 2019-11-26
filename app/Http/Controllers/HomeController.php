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
use App\Alt_taxonomy;
use App\Servicetaxonomy;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home($value='')
    {
        $home = Layout::find(1);
        $map = Map::find(1);
        // $taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->orderBy('taxonomy_name', 'asc')->get();
        // $grandparent_taxonomies = Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_grandparent_name')->pluck('taxonomy_grandparent_name')->toArray();
        // $parent_taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_parent_name')->pluck('taxonomy_parent_name')->toArray();
        $grandparent_taxonomies = Alt_taxonomy::all();
        
        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0)
        {
            foreach ($grandparent_taxonomies as $key => $grandparent) {
                $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                $terms = $grandparent->terms()->get();
                $taxonomy_parent_name_list = [];
                foreach ($terms as $term_key => $term) {
                    array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                }

                $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                $parent_taxonomy = [];
                $grandparent_service_count = 0;
                foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                    $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    if ($parent_count == $term_count) {
                        $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                        $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                        $child_data['child_taxonomies'] = $child_taxonomies;
                        array_push($parent_taxonomy, $child_data);
                    } else {
                        foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        }
        else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }
        

        return view('frontEnd.home', compact('home', 'map', 'grandparent_taxonomies', 'taxonomy_name_list'))->with('taxonomy_tree', $taxonomy_tree);
    }

    public function about($value ='')
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
        $home = Layout::find(1);
        $map = Map::find(1);

        $grandparent_taxonomies = Alt_taxonomy::all();
        $taxonomy_tree = [];
        foreach ($grandparent_taxonomies as $key => $grandparent) {

            $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
            $terms = $grandparent->terms()->get();
            $taxonomy_parent_name_list = [];
            foreach ($terms as $term_key => $term) {
                array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
            }

            $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

            $parent_taxonomy = [];
            $grandparent_service_count = 0;
            foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                if ($parent_count == $term_count) {
                    $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                    $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                    $child_data['child_taxonomies'] = $child_taxonomies;
                    array_push($parent_taxonomy, $child_data);
                } else {
                    foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                        $child_data['parent_taxonomy'] = $child_term;
                        $child_data['child_taxonomies'] = "";
                        array_push($parent_taxonomy, $child_data);
                    }
                }
            }
            $taxonomy_ids = $grandparent->terms()->allRelatedIds();
            $grand_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $taxonomy_ids)->groupBy('service_recordid')->pluck('service_recordid')->toArray();
            $grandparent_service_count = Service::whereIn('service_recordid',$grand_service_ids)->count();

            $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
            $taxonomy_data['service_count'] = $grandparent_service_count;
            array_push($taxonomy_tree, $taxonomy_data);
        }

        return view('frontEnd.about', compact('about', 'home', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree'));
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

        $services= Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('details', function ($q)  use($chip_service){
                    $q->where('detail_value', 'like', '%'.$chip_service.'%');
                })->paginate(10);
        $search_results = Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('details', function ($q)  use($chip_service){
                    $q->where('detail_value', 'like', '%'.$chip_service.'%');
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
