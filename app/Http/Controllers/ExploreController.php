<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use App\District;
use App\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Geolocation;
use Geocode;
use App\Location;

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
        
        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_name'));
    }

    public function geocode(Request $request)
    {
        $ip= \Request::ip();

        $chip_title = "Search Address:";
        $chip_name = $request->input('search_address');
        $response = Geocode::make()->address($chip_name);

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
        
        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_name'));

    }
    public function index(Request $request)
    {
            $districts = District::orderBy('name')->get();
            $states = Project::orderBy('project_status')->distinct()->get(['project_status']);
            $categories = Project::orderBy('category_type_topic_standardize')->distinct()->get(['category_type_topic_standardize']);
            $cities = Project::orderBy('name_dept_agency_cbo')->distinct()->get(['name_dept_agency_cbo']);
            $address_district= District::where('name', '=', 1)->get();
            


           

            if ($request->input('search')) {

                $search = $request->input('search');
                $projects= Project::with('district')->where('project_title', 'like', '%'.$search.'%')->orwhere('project_description', 'like', '%'.$search.'%')->orwhere('neighborhood', 'like', '%'.$search.'%')->orwhereHas('district', function ($q)  use($search){
                    $q->where('name', 'like', '%'.$search.'%');
                })->sortable()->paginate(20);

                return view('frontEnd.explore', compact('projects', 'districts', 'states', 'categories', 'cities', 'address_district'));
            }

            if ($request->input('address')) {
                $location = $request->get('address');
                // var_dump($location);
                // exit();
                $location = str_replace("+","%20",$location);
                $location = str_replace(",",",",$location);
                $location = str_replace(" ","%20",$location);
                

                $content = file_get_contents("https://geosearch.planninglabs.nyc/v1/autocomplete?text=".$location);


                $result  = json_decode($content);
                
                // var_dump($result->features[0]);
                // exit();
                //$housenumber=$result->features[3]->properties->housenumber;
                // var_dump($housenumber);
                // exit();
                $name=$result->features[0]->properties->name;
                $zip=$result->features[0]->properties->postalcode;
                // var_dump($street, $zipcode);
                // exit();
                $name = str_replace(" ","%20",$name);
                $url = 'https://api.cityofnewyork.us/geoclient/v1/place.json?name=' . $name . '&zip=' . $zip . '&app_id=0359f714&app_key=27da16447759b5111e7dcc067d73dfc8';

                $geoclient = file_get_contents($url);

                $geo  = json_decode($geoclient);

                $cityCouncilDistrict=$geo->place->cityCouncilDistrict;
                
                $projects= Project::with('district')->orwhereHas('district', function ($q)  use($cityCouncilDistrict){
                    $q->where('cityCouncilDistrict', '=', $cityCouncilDistrict);
                })->sortable()->paginate(20);

                $address_district=District::where('cityCouncilDistrict', '=', $cityCouncilDistrict)->first();
                
                
                if($address_district == NULL){
                    return redirect('/explore')->with('success', 'no rpoject');
                }
                
                $address_district=$address_district->name;
                
                return view('frontEnd.explore', compact('projects', 'districts', 'states', 'categories', 'cities', 'address_district','location'));
            }

        $projects = Project::sortable()->paginate(20);

        $location_maps = Project::all();
        
        return view('frontEnd.explore', compact('projects', 'districts', 'states', 'categories', 'cities', 'count', 'address_district', 'location_maps'));
    }

  

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $project = Project::find($id);
        $district = $project->district_ward_name;
        $contact = Contact::where('district_ward_name', 'like', '%'.$district.'%')->first();
        return view('frontEnd.profile', compact('project', 'contact'));
    }


    public function filterValues(Request $request)
    {
        $price_min = (int)$request->input('price_min');
        $price_max = (int)$request->input('price_max');
        $year_min = $request->input('year_min');
        $year_max = $request->input('year_max');
        $vote_min = (int)$request->input('vote_min');
        $vote_max = (int)$request->input('vote_max');
                

        $projects = Project::with('process')->whereBetween('cost_num', [$price_min, $price_max])->whereBetween('votes', [$vote_min, $vote_max])->whereHas('process', function ($q)  use($year_min, $year_max){
               $q->whereBetween('vote_year', [$year_min, $year_max]); })->sortable()->paginate(20);

        $districts = District::orderBy('name')->get();
        $states = Project::orderBy('project_status')->distinct()->get(['project_status']);
        $categories = Project::orderBy('category_type_topic_standardize')->distinct()->get(['category_type_topic_standardize']);
        $cities = Project::orderBy('name_dept_agency_cbo')->distinct()->get(['name_dept_agency_cbo']);
      
        // var_dump($projects);
        // exit();
      return view('frontEnd.explore1', compact('projects'))->render();
            //return response()->json($projects);

    }
    public function filterValues1(Request $request)
    {
       
                
                $price_min = (int)$request->input('price_min');
                $price_max = (int)$request->input('price_max');
                $year_min = $request->input('year_min');
                $year_max = $request->input('year_max');
                $vote_min = (int)$request->input('vote_min');
                $vote_max = (int)$request->input('vote_max');

                $search = $request->input('Search');


                $district = $request->input('District');
                $status = $request->input('Status');
                $category = $request->input('Category');        
                $city = $request->input('City');
                $sort = $request->input('selected_sort');
                $location = $request->input('address');

                // var_dump($price_min,$price_max,$year_min,$year_max,$vote_min,$vote_max,$district,$status,$category,$city);
                //  exit(); 

                $projects = Project::whereBetween('cost_num', [$price_min, $price_max])->whereBetween('votes', [$vote_min, $vote_max])->whereBetween('vote_year', [$year_min, $year_max]);
                           
                 // var_dump($price_min,$price_max,$year_min,$year_max,$vote_min,$vote_max,$district,$status,$category,$city,count($projects));
                 // exit(); 
               

                if($district!=NULL){

                    $district = District::where('name', '=', $district)->first();
                    $district = $district->recordid;
                    $projects = $projects->where('district_ward_name', '=', $district);
                    
                }
                
                if($status!=NULL){

                    $projects = $projects->where('project_status_category', 'like', '%'.$status.'%');
                }

                if($category!=NULL){
                    $projects = $projects->where('category_type_topic_standardize', '=', $category);
                }

                if($city!=NULL){
                    $projects = $projects->where('name_dept_agency_cbo', '=', $city);
                }
                
                if($sort!=NULL){

                    if($sort=='Price: Low to High'){
                        $projects = $projects->orderBy('cost_num');
                    }

                    if($sort=='Price: High to Low'){
                        $projects = $projects->orderBy('cost_num', 'desc');
                    }

                    if($sort=='Year: Low to High'){
                        $projects = $projects->orderBy('vote_year');
                    }

                    if($sort=='Year: High to Low'){
                        $projects = $projects->orderBy('vote_year', 'desc');
                    }

                    if($sort=='Votes: Low to High'){
                        $projects = $projects->orderBy('votes');
                    }

                    if($sort=='Votes: High to Low'){
                        $projects = $projects->orderBy('votes', 'desc');
                    }

                    if($sort=='Status: Complete to Needed'){
                        $projects = $projects->orderBy('project_status_category');
                    }

                    if($sort=='Status: Needed to Complete'){
                        $projects = $projects->orderBy('project_status_category', 'desc');
                    }

                }
                $address_district="";

                if($location != NULL)
                {
                    
                    $location = str_replace("+","%20",$location);
                    $location = str_replace(",",",",$location);
                    $location = str_replace(" ","%20",$location);
                    

                    $content = file_get_contents("https://geosearch.planninglabs.nyc/v1/autocomplete?text=".$location);


                    $result  = json_decode($content);
                    
                    // var_dump($result->features[0]);
                    // exit();
                    //$housenumber=$result->features[3]->properties->housenumber;
                    // var_dump($housenumber);
                    // exit();
                    $name=$result->features[0]->properties->name;
                    $zip=$result->features[0]->properties->postalcode;
                    // var_dump($street, $zipcode);
                    // exit();
                    $name = str_replace(" ","%20",$name);
                    $url = 'https://api.cityofnewyork.us/geoclient/v1/place.json?name=' . $name . '&zip=' . $zip . '&app_id=0359f714&app_key=27da16447759b5111e7dcc067d73dfc8';

                    $geoclient = file_get_contents($url);

                    $geo  = json_decode($geoclient);

                    $cityCouncilDistrict=$geo->place->cityCouncilDistrict;
                    
                    $projects= $projects->with('district')->orwhereHas('district', function ($q)  use($cityCouncilDistrict){
                        $q->where('cityCouncilDistrict', '=', $cityCouncilDistrict);
                    });

                    $address_district=District::where('cityCouncilDistrict', '=', $cityCouncilDistrict)->first();
                
                
                    if($address_district == NULL){
                        return redirect('/explore')->with('success', 'no rpoject');
                    }
                    
                    $address_district=$address_district->name;
                }
                 if($search != NULL)
                {
                    // $projects = $projects->with('district')->where('project_title', 'like', '%'.$search.'%')->orwhere('project_description', 'like', '%'.$search.'%')->orwhere('neighborhood', 'like', '%'.$search.'%')->orwhereHas('district', function ($q)  use($search){
                    // $q->where('name', 'like', '%'.$search.'%');
                    // });

                    $projects = $projects->with('district')->where(function($q) use($search){
                        $q->where('project_title', 'like', '%'.$search.'%')->orwhere('project_description', 'like', '%'.$search.'%')->orwhere('neighborhood', 'like', '%'.$search.'%')->orwhereHas('district',function($qq) use($search) {
                            $qq->where('name', 'like', '%'.$search.'%');
                        });
                    });
                }
                $projects = $projects->get();


                return view('frontEnd.explore1', compact('projects','address_district'))->render();


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
