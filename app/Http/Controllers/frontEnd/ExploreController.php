<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Alt_taxonomy;
use App\Model\Analytic;
use App\Model\csv;
use App\Model\Detail;
use App\Model\Layout;
use App\Model\Location;
use App\Model\Map;
use App\Model\MetaFilter;
use App\Model\Organization;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServiceTaxonomy;
use App\Model\Source_data;
use App\Model\Taxonomy;
use Geocode;
use Geolocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Stevebauman\Location\Facades\Location as FacadesLocation;
use Spatie\Geocoder\Geocoder;
use App\Exports\OrganizationExport;
use Excel;

class ExploreController extends Controller
{

    public function geolocation(Request $request)
    {
        $ip = $request->ip();

        $data = FacadesLocation::get($ip);

        $chip_title = "";
        $chip_name = "Search Near Me";
        // $auth = new Location();
        // $locations = $auth->geolocation(40.573414, -73.987818);
        // var_dump($locations);

        $sort_by_distance_clickable = false;

        $lat = floatval($data->latitude);
        $lng = floatval($data->longitude);

        // $lat =37.3422;
        // $lng = -121.905;

        $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance');

        $locationids = $locations->pluck('location_recordid')->toArray();

        $location_serviceids = ServiceLocation::whereIn('location_recordid', $locationids)->pluck('service_recordid')->toArray();

        $services = Service::whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

        $search_results = $services->count();

        $services = $services->paginate(10);

        $locations = $locations->get();

        $map = Map::find(1);

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours = [];

        $service_taxonomy_info_list = [];
        foreach ($services as $key => $service) {
            $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);

            foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {

                $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int) ($service_taxonomy_recordid))->first();
                if (isset($taxonomy)) {
                    $service_taxonomy_name = $taxonomy->taxonomy_name;
                    $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                }
            }
        }

        //=====================updated tree==========================//

        $grandparent_taxonomies = Alt_taxonomy::all();

        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0) {
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
                        foreach ($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        } else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }

        return view('frontEnd.services.services', compact('services', 'locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results', 'taxonomy_tree', 'sort_by_distance_clickable', 'service_taxonomy_info_list'));
    }

    public function geocode(Request $request)
    {
        $chip_service = $request->input('find');
        $chip_address = $request->input('search_address');

        $source_data = Source_data::find(1);

        $location_serviceids = Service::pluck('service_recordid')->toArray();
        $location_locationids = Location::with('services', 'organization')->pluck('location_recordid')->toArray();

        if ($source_data->active == 1) {
            $services = Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%' . $chip_service . '%')->orwhere('service_description', 'like', '%' . $chip_service . '%')->orwhere('service_airs_taxonomy_x', 'like', '%' . $chip_service . '%')->orwhereHas('organizations', function ($q) use ($chip_service) {
                $q->where('organization_name', 'like', '%' . $chip_service . '%');
            })->orwhereHas('taxonomy', function ($q) use ($chip_service) {
                $q->where('taxonomy_name', 'like', '%' . $chip_service . '%');
            })->orwhereHas('details', function ($q) use ($chip_service) {
                $q->where('detail_value', 'like', '%' . $chip_service . '%');
            })->select('services.*');
        } else {
            $serviceids = Service::where('service_name', 'like', '%' . $chip_service . '%')->orwhere('service_description', 'like', '%' . $chip_service . '%')->orwhere('service_airs_taxonomy_x', 'like', '%' . $chip_service . '%')->pluck('service_recordid')->toArray();
        }

        $organization_recordids = Organization::where('organization_name', 'like', '%' . $chip_service . '%')->pluck('organization_recordid')->toArray();
        $organization_serviceids = ServiceOrganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid')->toArray();
        $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%' . $chip_service . '%')->pluck('taxonomy_recordid')->toArray();
        $taxonomy_serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid')->toArray();

        $service_locationids = ServiceLocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->toArray();

        if ($chip_address != null) {

            $response = Geocode::make()->address($chip_address);

            $lat = $response->latitude();
            $lng = $response->longitude();

            $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
                ->having('distance', '<', 2)
                ->orderBy('distance');

            $location_locationids = $locations->pluck('location_recordid')->toArray();

            $location_serviceids = ServiceLocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid')->toArray();
        }

        if ($chip_service != null) {

            $service_ids = Service::whereIn('service_recordid', $serviceids)->orWhereIn('service_recordid', $organization_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids)->pluck('service_recordid')->toArray();

            $services = Service::whereIn('service_recordid', $serviceids)->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

            $locations = Location::with('services', 'organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        } else {
            $services = Service::WhereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            $locations = Location::with('services', 'organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        if ($chip_service == null && $chip_address == null) {
            $services = Service::orderBy('service_name');
            $locations = Location::with('services', 'organization');
        }

        $search_results = $services->count();

        $services = $services->paginate(10);

        $locations = $locations->get();

        $analytic = Analytic::where('search_term', '=', $chip_service)->orWhere('search_term', '=', $chip_address)->first();
        if (isset($analytic)) {
            $analytic->search_term = $chip_service;
            $analytic->search_results = $search_results;
            $analytic->times_searched = $analytic->times_searched + 1;
            $analytic->save();
        } else {
            $new_analytic = new Analytic();
            $new_analytic->search_term = $chip_service;
            $new_analytic->search_results = $search_results;
            $new_analytic->times_searched = 1;
            $new_analytic->save();
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
        $checked_transportations = [];
        $checked_hours = [];
        $checked_transportations = [];
        $checked_hours = [];

        return view('frontEnd.services', compact('services', 'locations', 'chip_service', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results'));
    }

    public function filter(Request $request)
    {
        $checked_taxonomies = $request->input('selected_taxonomies');
        // $service_state_filter = 'Verified';

        $sort_by_distance_clickable = false;

        $target_populations = $request->input('target_populations');
        $target_all = $request->input('target_all');

        // var_dump($grandparents);
        // var_dump($parents);
        // var_dump($childs);
        // var_dump($checked_grandparents);

        $pdf = $request->input('pdf');
        $csv = $request->input('csv');

        $pagination = strval($request->input('paginate'));

        $sort = $request->input('sort');
        $meta_status = $request->input('meta_status');

        $parent_taxonomy = [];
        $child_taxonomy = [];

        $chip_service = $request->input('find');

        $chip_address = $request->input('search_address');

        $source_data = Source_data::find(1);

        $location_serviceids = Service::pluck('service_recordid');
        $location_locationids = Location::with('services', 'organization')->pluck('location_recordid');
        $service_locationids = [];

        // if($source_data->active == 1){
        //     $services= Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->select('services.*');
        // }
        // else{
        //     $serviceids= Service::where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->pluck('service_recordid');

        //     $organization_recordids = Organization::where('organization_name', 'like', '%'.$chip_service.'%')->pluck('organization_recordid');
        //     $organization_serviceids = ServiceOrganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid');
        //     $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%'.$chip_service.'%')->pluck('taxonomy_recordid');
        //     $taxonomy_serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid');

        //     $service_locationids = ServiceLocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid');
        // }

        $serviceids = Service::where('service_name', 'like', '%' . $chip_service . '%')->orwhere('service_description', 'like', '%' . $chip_service . '%')->orwhere('service_airs_taxonomy_x', 'like', '%' . $chip_service . '%')->pluck('service_recordid');

        $organization_recordids = Organization::where('organization_name', 'like', '%' . $chip_service . '%')->pluck('organization_recordid');
        $organization_serviceids = ServiceOrganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid');
        $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%' . $chip_service . '%')->pluck('taxonomy_recordid');
        $taxonomy_serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid');

        $service_locationids = ServiceLocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid');
        $avarageLatitude = '';
        $avarageLongitude = '';
        if ($chip_address != null) {
            $sort = $sort == null ? 'Distance from Address' : $sort;
            $response = Geocode::make()->address($chip_address);
            $lat = $response->latitude();
            $lng = $response->longitude();
            // $client = new \GuzzleHttp\Client();
            // $geocoder = new Geocoder($client);
            // $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            // $geocoder->setApiKey($geocode_api_key);
            // $response = $geocoder->getCoordinatesForAddress($chip_address);
            // $lat = $response['lat'];
            // $lng = $response['lng'];

            $avarageLatitude = $lat;
            $avarageLongitude = $lng;


            $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
                ->having('distance', '<', 5)
                ->orderBy('distance');

            $location_locationids = $locations->pluck('location_recordid');

            $location_serviceids = ServiceLocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid');
            $sort_by_distance_clickable = true;
        }

        if ($chip_service != null && isset($serviceids)) {
            $service_ids = Service::whereIn('service_recordid', $serviceids)->orWhereIn('service_recordid', $organization_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids)->pluck('service_recordid');

            $services = Service::whereIn('service_recordid', $serviceids)->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

            $locations = Location::with('services', 'organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        } else {
            if (isset($services)) {
                $services = $services->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            } else {
                $services = Service::whereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            }

            $locations = Location::with('services', 'organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        if ($chip_service == null && $chip_address == null) {
            $services = Service::orderBy('service_name');
            $locations = Location::with('services', 'organization');
        }

        // var_dump($sort);
        // exit();

        $metas = MetaFilter::all();
        $count_metas = MetaFilter::count();

        if ($meta_status == 'On' && $count_metas > 0) {
            $address_serviceids = Service::pluck('service_recordid')->toArray();
            $taxonomy_serviceids = Service::pluck('service_recordid')->toArray();

            foreach ($metas as $key => $meta) {
                $values = explode(",", $meta->values);
                if ($meta->facet == 'Postal_code') {
                    $address_serviceids = [];
                    if ($meta->operations == 'Include') {
                        $serviceids = ServiceAddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    if ($meta->operations == 'Exclude') {
                        $serviceids = ServiceAddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    $address_serviceids = array_merge($serviceids, $address_serviceids);
                    // var_dump($address_serviceids);
                    // exit();
                }
                if ($meta->facet == 'Taxonomy') {

                    if ($meta->operations == 'Include') {
                        $serviceids = Servicetaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    if ($meta->operations == 'Exclude') {
                        $serviceids = Servicetaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                }
            }

            $services = $services->whereIn('service_recordid', $address_serviceids)->whereIn('service_recordid', $taxonomy_serviceids);

            $services_ids = $services->pluck('service_recordid')->toArray();
            $locations_ids = ServiceLocation::whereIn('service_recordid', $services_ids)->pluck('location_recordid')->toArray();
            $locations = $locations->whereIn('location_recordid', $locations_ids);
        }

        $selected_taxonomies = [];
        if ($checked_taxonomies != null) {
            $assert_selected_taxonomies = explode(',', $checked_taxonomies);
            for ($i = 0; $i < count($assert_selected_taxonomies); $i++) {
                $assert_selected_taxonomy = explode('child_', $assert_selected_taxonomies[$i]);
                if (count($assert_selected_taxonomy) > 1) {
                    array_push($selected_taxonomies, $assert_selected_taxonomy[1]);
                } else {
                    array_push($selected_taxonomies, $assert_selected_taxonomy[0]);
                }
            }
        }
        // var_dump($selected_taxonomies);
        // $child_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $selected_taxonomies)->groupBy('service_recordid')->pluck('service_recordid')->toArray();
        $child_service_ids = [];

        for ($i = 0; $i < count($selected_taxonomies); $i++) {
            $service_ids = Service::where('service_taxonomy', 'like', '%' . $selected_taxonomies[$i] . '%')->groupBy('service_recordid')->pluck('service_recordid')->toArray();
            $child_service_ids = array_merge($child_service_ids, $service_ids);
        }
        $child_service_ids = array_unique($child_service_ids);

        $child_location_ids = ServiceLocation::whereIn('service_recordid', $child_service_ids)->groupBy('location_recordid')->pluck('location_recordid')->toArray();
        $target_service_ids = [];
        $target_location_ids = [];
        if ($target_populations != null) {

            $target_populations_ids = Taxonomy::whereIn('taxonomy_recordid', $target_populations)->pluck('category_id')->toArray();
            $target_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $target_populations_ids)->groupBy('service_recordid')->pluck('service_recordid')->toArray();
            $target_location_ids = ServiceLocation::whereIn('service_recordid', $target_service_ids)->groupBy('location_recordid')->pluck('location_recordid')->toArray();
        }

        if (isset($target_all) && $target_all == 'all') {

            $target_populations_ids = Taxonomy::where('taxonomy_parent_name', '=', 'Target Populations')->pluck('category_id')->toArray();

            $target_populations = Taxonomy::where('taxonomy_parent_name', '=', 'Target Populations')->pluck('taxonomy_recordid')->toArray();

            $target_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $target_populations_ids)->groupBy('service_recordid')->pluck('service_recordid')->toArray();

            $target_location_ids = ServiceLocation::whereIn('service_recordid', $target_service_ids)->groupBy('location_recordid')->pluck('location_recordid')->toArray();
            // $services = $services->whereIn('service_recordid', $target_service_ids);
            // $locations = $locations->whereIn('location_recordid', $target_location_ids)->with('services','organization');
        }

        $total_service_ids = array_merge($child_service_ids, $target_service_ids);
        $total_location_ids = array_merge($child_location_ids, $target_location_ids);

        if ($total_service_ids) {
            $services = $services->whereIn('service_recordid', $total_service_ids);
            $locations = $locations->whereIn('location_recordid', $total_location_ids)->with('services', 'organization');
        }

        $map = Map::find(1);

        if ($pdf == 'pdf') {

            $layout = Layout::find(1);

            $services = $services->paginate(10);

            $pdf = PDF::loadView('frontEnd.services.services_download', compact('services', 'layout'));

            return $pdf->download('services.pdf');
        }

        if ($csv == 'csv') {
            $csvExporter = new \Laracsv\Export();

            $layout = Layout::find(1);

            $services = $services->whereNotNull('service_name')->get();

            foreach ($services as $service) {
                $taxonomies = '';
                $organizations = '';
                $phones = '';
                $address1 = '';
                $contacts = '';
                $details = '';

                foreach ($service->taxonomy as $key => $taxonomy) {
                    $taxonomies = $taxonomies . $taxonomy->taxonomy_name . ',';
                }
                $service['taxonomies'] = $taxonomies;

                if (isset($service->organizations)) {
                    if (is_array($service->organizations)) {
                        foreach ($service->organizations as $organization) {
                            $organizations = $organizations . $organization->organization_name;
                        }
                    }
                }

                $service['organizations'] = $organizations;

                foreach ($service->phone as $phone1) {
                    $phones = $phones . $phone1->phone_number;
                }
                $service['phones'] = $phones;

                foreach ($service->address as $address) {
                    $address1 = $address1 . $address->address_1 . ' ' . $address->address_city . ' ' . $address->address_state_province . ' ' . $address->address_postal_code;
                }
                $service['address1'] = $address1;

                foreach ($service->contact as $contact) {
                    $contacts = $contacts . $contact->contact_name;
                }
                $service['contacts'] = $contacts;

                $show_details = [];

                foreach ($service->details as $detail) {
                    for ($i = 0; $i < count($show_details); $i++) {
                        if ($show_details[$i]['detail_type'] == $detail->detail_type) {
                            break;
                        }
                    }
                    if ($i == count($show_details)) {
                        $show_details[$i] = array('detail_type' => $detail->detail_type, 'detail_value' => $detail->detail_value);
                    } else {
                        $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'] . ', ' . $detail->detail_value;
                    }
                }
                foreach ($show_details as $detail) {
                    $details = $details . $detail['detail_type'] . ':' . $detail['detail_value'] . '; ';
                }
                $service['details'] = $details;
            }

            $csv = csv::find(1);

            $source = $layout->footer_csv;
            $csv->description = $source;
            $csv->save();

            $csv = csv::find(2);
            $description = '';
            if (isset($child_taxonomy_names)) {
                if ($child_taxonomy_names != "") {
                    $filter_category = '';
                    foreach ($child_taxonomy_names as $child_taxonomy_name) {
                        $filter_category = $filter_category . $child_taxonomy_name . ',';
                    }
                    $description = $description . "Category: " . $filter_category;
                }
            }

            if (isset($checked_organization_names)) {
                if ($checked_organization_names != "") {
                    $filter_organization = '';
                    foreach ($checked_organization_names as $checked_organization_name) {
                        $filter_organization = $filter_organization . $checked_organization_name . ',';
                    }

                    $description = $description . "Organization: " . $filter_organization;
                }
            }

            if (isset($checked_insurance_names)) {
                if ($checked_insurance_names != "") {
                    $filter_insurance = '';
                    foreach ($checked_insurance_names as $checked_insurance_name) {
                        $filter_insurance = $filter_insurance . $checked_insurance_name . ',';
                    }

                    $description = $description . "Insurance: " . $filter_insurance;
                }
            }

            if (isset($checked_age_names)) {
                if ($checked_age_names != "") {
                    $filter_age = '';
                    foreach ($checked_age_names as $checked_age_name) {
                        $filter_age = $filter_age . $checked_age_name . ',';
                    }

                    $description = $description . "Age: " . $filter_age;
                }
            }

            if (isset($checked_language_names)) {
                if ($checked_language_names != "") {
                    $filter_language = '';
                    foreach ($checked_language_names as $checked_language_name) {
                        $filter_language = $filter_language . $checked_language_name . ',';
                    }

                    $description = $description . "Language: " . $filter_language;
                }
            }

            if (isset($checked_setting_names)) {
                if ($checked_setting_names != "") {
                    $filter_setting = '';
                    foreach ($checked_setting_names as $checked_setting_name) {
                        $filter_setting = $filter_setting . $checked_setting_name . ',';
                    }

                    $description = $description . "Setting: " . $filter_setting;
                }
            }

            if (isset($checked_cultural_names)) {
                if ($checked_cultural_names != "") {
                    $filter_cultural = '';
                    foreach ($checked_cultural_names as $checked_cultural_name) {
                        $filter_cultural = $filter_cultural . $checked_cultural_name . ',';
                    }

                    $description = $description . "Cultural: " . $filter_cultural;
                }
            }

            if (isset($checked_transportation_names)) {
                if ($checked_transportation_names != "") {
                    $filter_transportation = '';
                    foreach ($checked_transportation_names as $checked_transportation_name) {
                        $filter_transportation = $filter_cultural . $checked_transportation_name . ',';
                    }

                    $description = $description . "Transportation: " . $filter_transportation;
                }
            }

            if (isset($checked_hour_names)) {
                if ($checked_hour_names != "") {
                    $filter_hour = '';
                    foreach ($checked_hour_names as $checked_hour_name) {
                        $filter_hour = $filter_hour . $checked_hour_name . ',';
                    }

                    $description = $description . "Additional Hour: " . $filter_hour;
                }
            }

            $csv->description = $description;
            $csv->save();

            $csv = csv::find(3);
            $csv->description = date('m/d/Y H:i:s');
            $csv->save();

            $csv = csv::all();
            // var_dump($services);

            // return $csvExporter->build($services, ['service_name'=>'Service Name', 'service_alternate_name'=>'Service Alternate Name', 'taxonomies'=>'Category', 'organizations'=>'Organization', 'phones'=>'Phone', 'address1'=>'Address', 'contacts'=>'Contact', 'service_description'=>'Service Description', 'service_url'=>'URL','service_application_process'=>'Application Process', 'service_wait_time'=>'Wait Time', 'service_fees'=>'Fees', 'service_accreditations'=>'Accreditations', 'service_licenses'=>'Licenses', 'details'=>'Details'])->build($csv, ['name'=>'', 'description'=>''])->download();

            return $csvExporter->build($services, ['service_name' => 'Service Name', 'service_alternate_name' => 'Service Alternate Name', 'taxonomies' => 'Category', 'organizations' => 'Organization', 'phones' => 'Phone', 'address1' => 'Address', 'contacts' => 'Contact', 'service_description' => 'Service Description', 'service_url' => 'URL', 'service_application_process' => 'Application Process', 'service_wait_time' => 'Wait Time', 'service_fees' => 'Fees', 'service_accreditations' => 'Accreditations', 'service_licenses' => 'Licenses', 'details' => 'Details'])->build($csv, ['name' => '', 'description' => ''])->download();
        }

        $search_results = $services->count();

        // if ($sort == 'Service Name') {
        //     $services = $services->orderBy('service_name');
        // }

        // if ($sort == 'Organization Name') {
        //     // $services = Service::whereIn('services.service_recordid', $services_ids);
        //     $services = $services->leftjoin('organizations', 'services.service_organization', '=', 'organizations.organization_recordid')->orderBy('organization_name');
        // }

        $services = $services->paginate($pagination);

        $miles = '';
        $services1 = $services->filter(function ($value, $key) use ($avarageLatitude, $avarageLongitude, $miles) {
            if ($avarageLatitude != '' && $avarageLongitude != '') {
                $lat2 = $avarageLatitude;
                $lon2 = $avarageLongitude;
                $location = $value->locations()->first();

                $lat1 = $location->location_latitude;
                $lon1 = $location->location_longitude;
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;

                $value->miles = $miles;
            }
            $value->organization_name = $value->organizations ? $value->organizations->organization_name : '';
            return true;
        });
        if ($sort == 'Distance from Address') {
            $services1 = $services1->sortBy('miles');
        } else if ($sort == 'Service Name') {
            $services1 = $services1->sortBy('service_name');
        } else if ($sort == 'Organization Name') {
            $services1 = $services1->sortBy('organization_name');
        }
        $services = $services->setCollection($services1);

        $service_taxonomy_info_list = [];
        foreach ($services as $key => $service) {
            $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);

            foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {

                $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int) ($service_taxonomy_recordid))->first();
                if (isset($taxonomy)) {
                    $service_taxonomy_name = $taxonomy->taxonomy_name;
                    $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                }
            }
        }

        $service_details_info_list = [];
        foreach ($services as $key => $service) {
            $service_details_recordid_list = explode(',', $service->service_details);
            foreach ($service_details_recordid_list as $key => $service_details_recordid) {
                $detail = Detail::where('detail_recordid', '=', (int) ($service_details_recordid))->first();
                if (isset($detail)) {
                    $service_detail_type = $detail->detail_type;
                    $service_details_info_list[$service_details_recordid] = $service_detail_type;
                }
            }
        }

        $locations = $locations->get();

        $analytic = Analytic::where('search_term', '=', $chip_service)->orWhere('search_term', '=', $chip_address)->first();
        if (isset($analytic)) {
            $analytic->search_term = $chip_service;
            $analytic->search_results = $search_results;
            $analytic->times_searched = $analytic->times_searched + 1;
            $analytic->save();
        } else {
            $new_analytic = new Analytic();
            $new_analytic->search_term = $chip_service;
            $new_analytic->search_results = $search_results;
            $new_analytic->times_searched = 1;
            $new_analytic->save();
        }

        $map = Map::find(1);

        //=====================updated tree==========================//

        $grandparent_taxonomies = Alt_taxonomy::all();

        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0) {
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
                        foreach ($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        } else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }

        // var_dump('============parents============');
        // var_dump($parents);
        // var_dump('============grandparents============');
        // var_dump($grandparents);
        // var_dump('============$childs============');
        // var_dump($childs);
        return view('frontEnd.services.services', compact('services', 'locations', 'chip_service', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'search_results', 'pagination', 'sort', 'meta_status', 'target_populations', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list', 'service_details_info_list', 'avarageLatitude', 'avarageLongitude'))->with('taxonomy_tree', $taxonomy_tree);
    }

    public function filter_organization(Request $request)
    {
        $organization_tag_list = Organization::whereNotNull('organization_tag')->select('organization_tag')->pluck('organization_tag')->toArray();
        $chip_organization = $request->input('find');
        $sort = $request->input('sort');
        $organization_tags = $request->get('organization_tags');

        $checked_taxonomies = $request->input('selected_taxonomies');
        // $organizations = Organization::where('organization_name', 'like', '%'.$chip_organization.'%')->orwhere('organization_description', 'like', '%'.$chip_organization.'%');
        $organizations = Organization::where('organization_name', 'like', '%' . $chip_organization . '%');

        if ($organization_tags) {
            $organization_tags = explode(',', $organization_tags);
            $organizations = $organizations->whereIn('organization_tag', $organization_tags);
        }
        $selected_taxonomies = [];
        if ($checked_taxonomies != null) {
            $assert_selected_taxonomies = explode(',', $checked_taxonomies);
            for ($i = 0; $i < count($assert_selected_taxonomies); $i++) {
                $assert_selected_taxonomy = explode('child_', $assert_selected_taxonomies[$i]);
                if (count($assert_selected_taxonomy) > 1) {
                    array_push($selected_taxonomies, $assert_selected_taxonomy[1]);
                } else {
                    array_push($selected_taxonomies, $assert_selected_taxonomy[0]);
                }
            }
        }

        $taxonomies = Taxonomy::whereIn('taxonomy_recordid', $selected_taxonomies)->get();
        $organizationsIds = [];
        foreach ($taxonomies as $key => $taxonomy) {
            $services = $taxonomy->service()->select('service_organization')->get();
            foreach ($services as $key => $service) {
                $organizationsIds[] = $service->service_organization;
            }
        }
        // if (count($organizationsIds) > 0) {
        //     $organizations = $organizations->whereIn('organization_recordid', $organizationsIds);
        // }
        // if ($request->has('organization_tags')) {
        //     $organization_tags = $request->get('organization_tags');
        //     $organizations = $organizations->where('organization_tag', $request->get('organization_tags'));
        // }


        $search_results = $organizations->count();

        $pagination = strval($request->input('paginate'));

        if ($sort == 'Most Recently Updated') {
            $organizations = $organizations->orderBy('updated_at', 'desc')->paginate($pagination);
        } else if ($sort == 'Least Recently Updated') {
            $organizations = $organizations->orderBy('updated_at')->paginate($pagination);
        } else {
            $organizations = $organizations->orderBy('updated_at', 'desc')->paginate($pagination);
        }

        foreach ($organizations as $key => $organization) {
            if (isset($organization->services) && $organization->services->count() > 0) {
                foreach ($organization->services as $key => $service) {
                    $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);

                    foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {

                        $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int) ($service_taxonomy_recordid))->first();
                        if (isset($taxonomy)) {
                            $service_taxonomy_name = $taxonomy->taxonomy_name;
                            $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                        }
                    }
                }
            }
        }

        $map = Map::find(1);
        if ($organization_tags != '') {
            $organization_tags = implode(',', $organization_tags);
        }
        if ($request->organization_csv == 'csv') {
            return Excel::download(new OrganizationExport($request), 'Organization.csv');
        }
        if ($request->organization_pdf == 'pdf') {
            $layout = Layout::find(1);

            $organizations = Organization::select('*');
            if ($chip_organization) {
                $organizations = Organization::where('organization_name', 'like', '%' . $chip_organization . '%');
            }
            if ($organization_tags) {
                $organization_tags = explode(',', $organization_tags);
                $organizations = $organizations->whereIn('organization_tag', $organization_tags);
            }
            if (strpos(url()->previous(), '/organizations/') !== false) {
                $url = url()->previous();
                $recordedId = explode('organizations/', $url);
                if (count($recordedId) > 1) {
                    $organizations = $organizations->where('organization_recordid', $recordedId[1]);
                }
            }
            if ($sort == 'Most Recently Updated') {
                $organizations = $organizations->orderBy('updated_at', 'desc')->get();
            } else if ($sort == 'Least Recently Updated') {
                $organizations = $organizations->orderBy('updated_at')->get();
            } else {
                $organizations = $organizations->orderBy('updated_at', 'desc')->get();
            }

            $pdf = PDF::loadView('frontEnd.organizations.organizations_download', compact('organizations', 'layout'));

            return $pdf->download('organizations.pdf');
        }
        return view('frontEnd.organizations.index', compact('map', 'organizations', 'chip_organization', 'search_results', 'organization_tag_list', 'pagination', 'sort', 'organization_tags'));
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
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}
