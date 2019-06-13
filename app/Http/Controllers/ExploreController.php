<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use App\Organization;
use App\Taxonomy;
use App\Detail;
use App\Servicetaxonomy;
use App\Serviceorganization;
use App\Servicelocation;
use App\Servicedetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Geolocation;
use Geocode;
use App\Location;
use App\Map;
use PDF;
use App\Layout;
use App\CSV;

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
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        
        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals'));
    }

    public function geocode(Request $request)
    {
        $ip= \Request::ip();

        $chip_title = "Search Address:";
        $chip_address = $request->input('search_address');

        if($chip_address == null){

            return redirect('services')->with('address', 'Please enter an address to search by location');
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
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];

        return view('frontEnd.near', compact('services','locations', 'chip_title', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals'));

    }

    public function filter(Request $request)
    {

        $parents = $request->input('parents');
        $childs = $request->input('childs');
        $checked = $request->input('organizations');
        $details = $request->input('insurances');
        $ages = $request->input('ages');
        $languages = $request->input('languages');
        $service_settings = $request->input('service_settings');
        $culturals = $request->input('culturals');
        $pdf = $request->input('pdf');
        $csv = $request->input('csv');

        $pagination = strval($request->input('paginate'));

        $sort = $request->input('sort');
        // var_dump($sort);
        // exit();

        
        $services = \App\Service::with('taxonomy');
        $locations = \App\Location::with('services','organization');

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];

        $child_taxonomy_names = '';
        $checked_organization_names ='';
        $checked_insurance_names = '';
        $checked_age_names = '';
        $checked_service_setting_names = '';
        $checked_cultural_names = '';


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
            $child_taxonomy_names = Taxonomy::whereIn('taxonomy_recordid', $childs)->pluck('taxonomy_name');

            $child_taxonomy = json_decode(json_encode($child_taxonomy));
            
            $service_ids = Servicetaxonomy::whereIn('taxonomy_recordid', $childs)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($checked!=null){
            $checked_organizations = Organization::whereIn('organization_recordid', $checked)->pluck('organization_recordid');
            $checked_organization_names = Organization::whereIn('organization_recordid', $checked)->pluck('organization_name');

            $checked_organizations = json_decode(json_encode($checked_organizations));
            
            $service_ids = Serviceorganization::whereIn('organization_recordid', $checked)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($details!=null){
            $checked_insurances = Detail::whereIn('detail_recordid', $details)->pluck('detail_recordid');
            $checked_insurance_names = Detail::whereIn('detail_recordid', $details)->pluck('detail_value');

            $checked_insurances = json_decode(json_encode($checked_insurances));
            
            $service_ids = Servicedetail::whereIn('detail_recordid', $details)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($ages!=null){
            $checked_ages = Detail::whereIn('detail_recordid', $ages)->pluck('detail_recordid');
            $checked_age_names = Detail::whereIn('detail_recordid', $ages)->pluck('detail_value');

            $checked_ages = json_decode(json_encode($checked_ages));
            
            $service_ids = Servicedetail::whereIn('detail_recordid', $ages)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($languages!=null){
            $checked_languages = Detail::whereIn('detail_recordid', $languages)->pluck('detail_recordid');
            $checked_language_names = Detail::whereIn('detail_recordid', $languages)->pluck('detail_value');

            $checked_languages = json_decode(json_encode($checked_languages));
            
            $service_ids = Servicedetail::whereIn('detail_recordid', $languages)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($service_settings!=null){
            $checked_settings = Detail::whereIn('detail_recordid', $service_settings)->pluck('detail_recordid');
            $checked_setting_names = Detail::whereIn('detail_recordid', $service_settings)->pluck('detail_value');

            $checked_settings = json_decode(json_encode($checked_settings));
            
            $service_ids = Servicedetail::whereIn('detail_recordid', $service_settings)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_settings)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }

        if($culturals!=null){
            $checked_culturals = Detail::whereIn('detail_recordid', $culturals)->pluck('detail_recordid');
            $checked_cultural_names = Detail::whereIn('detail_recordid', $culturals)->pluck('detail_value');

            $checked_culturals = json_decode(json_encode($checked_culturals));
            
            $service_ids = Servicedetail::whereIn('detail_recordid', $culturals)->groupBy('service_recordid')->pluck('service_recordid');
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

        if($pdf == 'pdf'){

            $layout = Layout::find(1);

            $services = $services->get();

            $pdf = PDF::loadView('frontEnd.services_download', compact('services', 'layout'));

            return $pdf->download('services.pdf');
        }

        if($csv == 'csv'){
            $csvExporter = new \Laracsv\Export();

            $layout = Layout::find(1);

            $services = $services->whereNotNull('service_name')->get();

            foreach ($services as $service) {
                $taxonomies = '';
                $organizations = '';
                $phones = '';
                $address1 ='';
                $contacts ='';
                $details = '';

                foreach($service->taxonomy as $key => $taxonomy){
                    $taxonomies = $taxonomies.$taxonomy->taxonomy_name.',';
                }
                $service['taxonomies'] = $taxonomies;

                foreach ($service->organizations as $organization) {
                    $organizations = $organizations.$organization->organization_name;
                }    
                $service['organizations'] = $organizations;

                foreach($service->phone as $phone1){
                    $phones = $phones.$phone1->phone_number;
                }
                $service['phones'] = $phones;

                foreach($service->address as $address){
                    $address1 = $address1.$address->address_1.' '.$address->address_city.' '.$address->address_state_province.' '.$address->address_postal_code;
                }
                $service['address1'] = $address1;

                foreach($service->contact as $contact){
                    $contacts = $contacts.$contact->contact_name;
                }
                $service['contacts'] = $contacts;



                $show_details = [];
                           
                foreach($service->details as $detail)
                {
                    for($i = 0; $i < count($show_details); $i ++){
                        if($show_details[$i]['detail_type'] == $detail->detail_type)
                            break;
                    }
                    if($i == count($show_details)){
                        $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=> $detail->detail_value);
                    }
                    else{
                        $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].', '.$detail->detail_value;
                    }
                                                               
                }  
                foreach($show_details as $detail){
                    $details = $details.$detail['detail_type'].':'.$detail['detail_value'].'; ';
                }
                $service['details'] = $details;           
             } 


            $csv = CSV::find(1);

            $source = $layout->footer_csv;
            $csv->description = $source;
            $csv->save();

            $csv = CSV::find(2);
            $description = '';
            if($child_taxonomy_names != ""){
                $filter_category ='';
                foreach($child_taxonomy_names as $child_taxonomy_name){
                    $filter_category = $filter_category.$child_taxonomy_name.',';
                }

                $description = $description."Category: ".$filter_category;
            }
            if($checked_organization_names != ""){
                $filter_organization ='';
                foreach($checked_organization_names as $checked_organization_name){
                    $filter_organization = $filter_organization.$checked_organization_name.',';
                }

                $description = $description."Organization: ".$filter_organization;
            }
            if($checked_insurance_names != ""){
                $filter_insurance ='';
                foreach($checked_insurance_names as $checked_insurance_name){
                    $filter_insurance = $filter_insurance.$checked_insurance_name.',';
                }

                $description = $description."Insurance: ".$filter_insurance;
            }
            

            $csv->description = $description;
            $csv->save();

            $csv = CSV::find(3);
            $csv->description = date('m/d/Y H:i:s');
            $csv->save();
            // var_dump($projects);
            // var_dump($collection);
            // exit();
            $csv = CSV::all();


            return $csvExporter->build($services, ['service_name'=>'Service Name', 'service_alternate_name'=>'Service Alternate Name', 'taxonomies'=>'Category', 'organizations'=>'Organization', 'phones'=>'Phone', 'address1'=>'Address', 'contacts'=>'Contact', 'service_description'=>'Service Description', 'service_url'=>'URL','service_application_process'=>'Application Process', 'service_wait_time'=>'Wait Time', 'service_fees'=>'Fees', 'service_accreditations'=>'Accreditations', 'service_licenses'=>'Licenses', 'details'=>'Details'])->build($csv, ['name'=>'', 'description'=>''])->download();
        }

    
        $services = $services->paginate($pagination);
        $locations = $locations->get();
       
        $map = Map::find(1);

        return view('frontEnd.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'pagination', 'sort'));

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
