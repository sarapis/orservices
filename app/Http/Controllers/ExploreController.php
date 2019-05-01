<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use App\Organization;
use App\Taxonomy;
use App\Servicetaxonomy;
use App\Serviceorganization;
use App\Servicelocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Geolocation;
use Geocode;
use App\Location;
use App\Map;

class ExploreController extends Controller
{

    public function geolocation(Request $request)
    {
        $ip= \Request::ip();
        // echo $ip;
        $data = \Geolocation::get($ip);

        $chip_title = "";
        $chip_name = "Search Near Me";
        // $auth = new Location();
        // $locations = $auth->geolocation(40.573414, -73.987818);
        // var_dump($locations);


        $lat =floatval($data->latitude);
        $lng =floatval($data->longitude);

        // $lat =37.3422;
        // $lng = -121.905;

        $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
        ->having('distance', '<', 2)
        ->orderBy('distance')
        ->get();

        $services = [];
        foreach ($locations as $key => $location) {
            
            $values = Service::where('service_locations', 'like', '%'.$location->location_recordid.'%')->get();
            foreach ($values as $key => $value) {
                $services[] = $value;
            }
        }

        $map = Map::find(1);

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        
        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations'));
    }

    public function geocode(Request $request)
    {
        $ip= \Request::ip();

        $chip_title = "Search Address:";
        $chip_address = $request->input('search_address');

        if($chip_address == null){

            return redirect('services')->with('address', 'Search Address is not exist');
        }

        $response = Geocode::make()->address($chip_address);
    //     $response = Geocode::make()->address('1 Infinite Loop');
    //     if ($response) {
    //         echo $response->latitude();
    //         echo $response->longitude();
    //         echo $response->formattedAddress();
    //         echo $response->locationType();
    // //         echo $response->raw()->address_components[8]['types'][0];
    // // echo $response->raw()->address_components[8]['long_name'];
    //        dd($response);
    //     }

        $lat =$response->latitude();
        $lng =$response->longitude();



        // $lat =37.3422;
        // $lng = -121.905;

        $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
        ->having('distance', '<', 2)
        ->orderBy('distance')
        ->get();

        $services = [];
        foreach ($locations as $key => $location) {
            
            $values = Service::where('service_locations', 'like', '%'.$location->location_recordid.'%')->get();
            foreach ($values as $key => $value) {
                $services[] = $value;
            }
        }
        $map = Map::find(1);

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];

        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations'));

    }

    public function filter(Request $request)
    {

        $parents = $request->input('parents');
        $childs = $request->input('childs');
        $checked = $request->input('organizations');
        $pagination = strval($request->input('paginate'));

        $sort = $request->input('sort');

        
        $services = \App\Service::with('taxonomy');
        $locations = \App\Location::with('services','organization');

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];


        if($parents!=null){

            $parent_taxonomy = Taxonomy::whereIn('taxonomy_recordid', $parents)->pluck('taxonomy_recordid');
            $parent_taxonomy = json_decode(json_encode($parent_taxonomy));

            $taxonomy = Taxonomy::whereIn('taxonomy_parent_name', $parents)->pluck('taxonomy_recordid');

            $service_ids = Servicetaxonomy::whereIn('taxonomy_recordid', $taxonomy)->groupBy('service_recordid')->pluck('service_recordid');
 
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');

        }

        if($childs!=null){
            $child_taxonomy = Taxonomy::whereIn('taxonomy_recordid', $childs)->pluck('taxonomy_recordid');
            $child_taxonomy = json_decode(json_encode($child_taxonomy));
            
            $service_ids = Servicetaxonomy::whereIn('taxonomy_recordid', $childs)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($checked!=null){
            $checked_organizations = Organization::whereIn('organization_recordid', $checked)->pluck('organization_recordid');
            $checked_organizations = json_decode(json_encode($checked_organizations));
            
            $service_ids = Serviceorganization::whereIn('organization_recordid', $checked)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($sort == 'Service Name'){
            $services = $services->orderBy('service_name');
        }

        if($sort == 'Organization Name'){
            $services = $services->with(['organizations' => function($query) {
                $query->orderBy('id');
            }]);
        }

       


            
        $services = $services->paginate($pagination);
        $locations = $locations->get();
       
        $map = Map::find(1);

        return view('frontEnd.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'pagination', 'sort'));

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
