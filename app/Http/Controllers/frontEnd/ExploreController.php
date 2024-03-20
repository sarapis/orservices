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
use App\Model\TaxonomyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Stevebauman\Location\Facades\Location as FacadesLocation;
use Spatie\Geocoder\Geocoder;
use App\Exports\OrganizationExport;
use App\Model\Code;
use App\Model\CodeCategory;
use App\Model\OrganizationStatus;
use App\Model\OrganizationTag;
use App\Model\ServiceTag;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ExploreController extends Controller
{
    public function __construct(public Geocoder $geocoder)
    {
    }
    public function geolocation(Request $request)
    {
        $ip = $request->ip();
        // $ip = '38.125.59.248';
        $data = FacadesLocation::get($ip);

        $chip_title = "";
        $chip_name = "Search Near Me";
        // $auth = new Location();
        // $locations = $auth->geolocation(40.573414, -73.987818);
        // var_dump($locations);

        $sort_by_distance_clickable = false;

        $lat = floatval($data->latitude);
        $lng = floatval($data->longitude);

        // $lat = 38.9327313;
        // $lng = -77.0373987;

        $locations = Location::with('services', 'organization', 'address')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance');

        $locationids = $locations->pluck('location_recordid')->toArray();

        $location_serviceids = ServiceLocation::whereIn('location_recordid', $locationids)->pluck('service_recordid')->toArray();

        $services = Service::whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

        $search_results = $services->count();

        $services = $services->paginate(10);

        $locations = $locations->get();
        // if($locations){
        //     $locations->filter(function($value,$key){
        //         $value->service = $service->service_name;
        //         $value->service_recordid = $service->service_recordid;
        //         $value->organization_name = $value->organization ? $value->organization->organization_name : '';
        //         $value->organization_recordid = $value->organization ? $value->organization->organization_recordid : '';
        //         $value->address_name = $value->address && count($value->address) > 0 ? $value->address[0]->address_1 : '';
        //     });
        // }
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
        $service_taxonomy_badge_color_list = [];
        foreach ($services as $key => $service) {
            $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);

            foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {

                $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
                if (isset($taxonomy)) {
                    $service_taxonomy_name = $taxonomy->taxonomy_name;
                    $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                    $service_taxonomy_badge_color_list[$service_taxonomy_recordid] = $taxonomy->badge_color;
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

            $response = $this->geocoder->getCoordinatesForAddress($chip_address);
            if ($response) {
                $lat = $response['lat'];
                $lng = $response['lng'];


                $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
                    ->having('distance', '<', 2)
                    ->orderBy('distance');

                $location_locationids = $locations->pluck('location_recordid')->toArray();

                $location_serviceids = ServiceLocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid')->toArray();
            }
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
        try {
            $checked_taxonomies = $request->input('selected_taxonomies');

            $sort_by_distance_clickable = false;

            $target_populations = $request->input('target_populations');
            $target_all = $request->input('target_all');

            $pdf = $request->input('pdf');
            $csv = $request->input('csv');
            $layout = Layout::find(1);

            $filter_label = $request->filter_label ? $request->filter_label : (Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label);
            Session::put('filter_label', $filter_label);

            $pagination = strval($request->input('paginate'));

            $sort = $request->input('sort');
            $meta_status = $request->input('meta_status');

            $parent_taxonomy = [];
            $child_taxonomy = [];

            $chip_service = $request->input('find');

            $chip_address = $request->input('search_address');

            $sdoh_codes_category = $request->sdoh_codes_category;

            $selected_code_category_array = [];
            $selected_category_ids = [];

            $codes = Code::whereNotNull('category')->where('category', '!=', '')->whereIn('resource', ['Condition', 'Goal', 'Procedure'])->orderBy('category')->pluck('category', 'id')->unique()->toArray();

            if ($sdoh_codes_category) {
                $sdoh_codes_category = json_decode($sdoh_codes_category);
                if (count($sdoh_codes_category) > 0 && !Auth::check()) {
                    Session::flash('message', 'Only registered users can access this link.');
                    Session::flash('status', 'error');
                    return redirect('/services');
                }

                foreach ($sdoh_codes_category as $key => $value) {
                    $category = CodeCategory::whereId($value)->first();
                    if ($category) {
                        $selected_code_category_array[] = $category->name;
                        $selected_category_ids[] = $category->id;
                    }
                }
            }

            $sdoh_codes_data = $request->sdoh_codes_data;
            $sdoh_codes_data = json_decode($sdoh_codes_data);
            if (is_array($sdoh_codes_data) && count($sdoh_codes_data) > 0 && !Auth::check()) {
                Session::flash('message', 'Only registered users can access this link.');
                Session::flash('status', 'error');
                return redirect('/services');
            }
            // $selected_ids = $service->code_category_ids ? explode(',', $service->code_category_ids) : [];

            $source_data = Source_data::find(1);

            $location_serviceids = [];
            $location_locationids = Location::with('services', 'organization')->pluck('location_recordid');
            $service_locationids = [];

            $organization_tags = $request->get('organization_tags');
            $service_tags = $request->get('service_tags');
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');


            $serviceids = Service::where('service_name', 'like', '%' . $chip_service . '%')->orwhere('service_description', 'like', '%' . $chip_service . '%')->orwhere('service_alternate_name', 'like', '%' . $chip_service . '%')->orwhere('service_airs_taxonomy_x', 'like', '%' . $chip_service . '%')->pluck('service_recordid')->toArray();

            $organization_recordids = Organization::where('organization_name', 'like', '%' . $chip_service . '%')->pluck('organization_recordid')->unique();

            // $organizations = Organization::where('organization_name', 'like', '%' . $chip_service . '%');

            $organization_serviceids = ServiceOrganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid')->unique();


            $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%' . $chip_service . '%')->pluck('taxonomy_recordid')->unique();
            $taxonomy_serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid')->unique();

            $service_locationids = ServiceLocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->unique();

            $avarageLatitude = '';
            $avarageLongitude = '';
            if ($chip_address != null || ($request->lat && $request->long)) {
                $sort = $sort == null ? 'Distance from Address' : $sort;
                if (($request->lat && $request->long)) {
                    $lat = floatval($request->lat);
                    $lng = floatval($request->long);
                    // $chip_address = 'search near by';
                    // $chip_service = 'search near by';
                } else {
                    $response = $this->geocoder->getCoordinatesForAddress($chip_address);
                    if ($response) {
                        $lat = $response['lat'];
                        $lng = $response['lng'];
                    }
                }
                // $client = new \GuzzleHttp\Client();
                // $geocoder = new Geocoder($client);
                // $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
                // $geocoder->setApiKey($geocode_api_key);
                // $response = $geocoder->getCoordinatesForAddress($chip_address);
                // $lat = $response['lat'];
                // $lng = $response['lng'];
                if (isset($lat) && isset($lng)) {
                    $avarageLatitude = $lat;
                    $avarageLongitude = $lng;


                    $locations = Location::with('services', 'organization', 'address')->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
                        ->having('distance', '<', 5)
                        ->orderBy('distance');

                    $location_locationids = $locations->pluck('location_recordid');
                    $location_serviceids = ServiceLocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid')->toArray();
                    $sort_by_distance_clickable = true;
                }
            }

            if ($chip_service != null && isset($serviceids)) {
                $service_ids = Service::whereIn('service_recordid', $serviceids)->orWhereIn('service_recordid', $organization_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids)->pluck('service_recordid');

                $services = Service::whereIn('service_recordid', $service_ids)->orderBy('service_name');


                $locations = Location::with('services', 'organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
            } else {
                if (isset($services)) {
                    $services = $services->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');
                } else {
                    $services = Service::whereIn('service_recordid', $location_serviceids)->orderBy('service_name');
                }

                $locations = Location::with('services', 'organization', 'address')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
            }
            if ($chip_service == null && $chip_address == null) {
                $services = Service::orderBy('service_name');
                $locations = Location::with('services', 'organization', 'address');
            }

            if (!Auth::check()) {
                $organizations = Organization::get();
                $inactiveOrganizationIds = [];
                foreach ($organizations as $key => $org) {
                    if ($org->organization_status_x && isset($organizationStatus[$org->organization_status_x]) && ($organizationStatus[$org->organization_status_x] == 'Out of Business' || $organizationStatus[$org->organization_status_x] == 'Inactive')) {
                        $inactiveOrganizationIds[] = $org->organization_recordid;
                    }
                }
                $services = $services->whereNotIn('service_organization', $inactiveOrganizationIds);
                $service_location_ids = [];
                $service_location_ids = $services->pluck('service_recordid')->toArray();
                $service_locationid = ServiceLocation::whereIn('service_recordid', $service_location_ids)->pluck('location_recordid')->unique();

                $locations = $locations->whereIn('location_recordid', $service_locationid);
            }

            if ($chip_service) {

                $serviceTagsIds = ServiceTag::whereNotNull('tag')->where('tag', 'LIKE', '%' . $chip_service . '%')->pluck('id')->toArray();
                if (count($serviceTagsIds) > 0) {
                    $services = $services->orWhere(function ($query) use ($serviceTagsIds) {
                        foreach ($serviceTagsIds as $keyword) {
                            if ($keyword) {
                                $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                            }
                        }
                        return $query;
                    });
                }
            }
            if ($selected_category_ids && count($selected_category_ids) > 0) {

                $services = $services->orWhere(function ($query) use ($selected_category_ids) {
                    foreach ($selected_category_ids as $keyword) {
                        $query = $query->orWhereRaw('find_in_set(' . $keyword . ', code_category_ids)');
                    }
                    return $query;
                });

                $service_location_ids = [];
                $service_location_ids = $services->pluck('service_recordid')->toArray();


                $service_locationid = ServiceLocation::whereIn('service_recordid', $service_location_ids)->pluck('location_recordid')->unique();

                $locations = $locations->whereIn('location_recordid', $service_locationid);
            }
            if ($sdoh_codes_data && count($sdoh_codes_data) > 0) {

                $services = $services->orWhere(function ($query) use ($sdoh_codes_data) {
                    foreach ($sdoh_codes_data as $keyword) {
                        $query = $query->orWhereRaw('find_in_set(' . $keyword . ', SDOH_code)');
                    }
                    return $query;
                });

                $service_location_ids = [];
                $service_location_ids = $services->pluck('service_recordid')->toArray();


                $service_locationid = ServiceLocation::whereIn('service_recordid', $service_location_ids)->pluck('location_recordid')->unique();

                $locations = $locations->whereIn('location_recordid', $service_locationid);
            }


            $organization_tags = $request->organization_tags != null ? json_decode($organization_tags) : [];
            if ($request->organization_tags && count($organization_tags) > 0) {
                $organizations_tags_ids = [];
                if ($organization_tags) {
                    // $organizations_tags_ids = Organization::whereIn('organization_tag', $organization_tags)->pluck('organization_recordid')->toArray();
                    $organizations_tags_ids = Organization::where(function ($query) use ($organization_tags) {
                        foreach ($organization_tags as $keyword) {
                            $query = $query->orWhere('organization_tag', 'LIKE', "%$keyword%");
                        }
                        return $query;
                    })->pluck('organization_recordid')->toArray();
                }

                $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid');

                $service_locationids_org_tags = ServiceLocation::whereIn('service_recordid', $organization_service_recordid)->pluck('location_recordid')->unique();

                $locations = $locations->orWhereIn('location_recordid', $service_locationids_org_tags);

                $services = Service::whereIn('service_recordid', $organization_service_recordid)->orWhereIn('service_organization', $organizations_tags_ids)->orWhereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            }

            $service_tags = $request->service_tags != null ? json_decode($service_tags) : [];
            if ($request->service_tags && count($service_tags) > 0) {
                $services_tags_ids = [];
                if ($service_tags) {
                    $services_tags_ids = Service::where(function ($query) use ($service_tags) {
                        foreach ($service_tags as $keyword) {
                            $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                        }
                        return $query;
                    })->pluck('service_recordid')->toArray();
                }
                $service_locationids_service_tags = ServiceLocation::whereIn('service_recordid', $services_tags_ids)->pluck('location_recordid')->unique();

                $locations = $locations->orWhereIn('location_recordid', $service_locationids_service_tags);

                $services = Service::whereIn('service_recordid', $services_tags_ids)->orWhereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            }

            $metas = MetaFilter::all();
            $count_metas = MetaFilter::count();

            if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label') {
                $address_serviceids = [];
                $taxonomy_serviceids = [];
                $serviceAddressIds = [];

                foreach ($metas as $key => $meta) {
                    $values = explode(",", $meta->values);
                    if ($meta->facet == 'Postal_code') {
                        $address_serviceids = [];
                        if ($meta->operations == 'Include') {
                            $serviceAddressIds = ServiceAddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                        }

                        if ($meta->operations == 'Exclude') {
                            $serviceAddressIds = ServiceAddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                        }

                        $address_serviceids = array_merge($serviceAddressIds, $address_serviceids);
                    }
                    if ($meta->facet == 'Taxonomy') {
                        $serviceTaxonomyIds = [];
                        if ($meta->operations == 'Include') {
                            $serviceTaxonomyIds = Servicetaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                        }

                        if ($meta->operations == 'Exclude') {
                            $serviceTaxonomyIds = Servicetaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                        }

                        $taxonomy_serviceids = array_merge($serviceTaxonomyIds, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'Service_status') {
                        $serviceStatusIds = Service::getServiceStatusMeta($values, $meta->operations);
                        $taxonomy_serviceids = array_merge($serviceStatusIds, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'service_tag') {
                        $service_tag_ids = Service::getServiceTagMeta($values, $meta->operations);
                        $taxonomy_serviceids = array_merge($service_tag_ids, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'organization_status') {
                        $organizations_status_ids = Organization::getOrganizationStatusMeta($values, $meta->operations);
                        $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_status_ids)->pluck('service_recordid')->toArray();
                        $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'organization_tag') {
                        $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta->operations);
                        $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid')->toArray();
                        $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                    }
                }
                $metaServices = array_unique(array_merge($address_serviceids, $taxonomy_serviceids));

                $services = $services->whereIn('service_recordid', $metaServices);

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

                $services = $services->get();

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
                        // if (is_array($service->organizations)) {
                        //     foreach ($service->organizations as $organization) {
                        $organizations = $service->organizations->organization_name;
                        //     }
                        // }
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
            if (!Auth::check()) {
                $services =  $services->where('access_requirement', '!=', 'yes');
                $locations = $locations->whereHas('services', function ($q) {
                    $q->where('access_requirement', '!=', 'yes');
                });
            }
            $services = $services->paginate($pagination);

            $miles = '';
            $services1 = $services->filter(function ($value, $key) use ($avarageLatitude, $avarageLongitude, $miles) {
                if ($avarageLatitude != '' && $avarageLongitude != '') {
                    $lat2 = $avarageLatitude;
                    $lon2 = $avarageLongitude;
                    $location = $value->locations()->first();

                    $miles = 0;
                    if ($location) {
                        $lat1 = $location->location_latitude;
                        $lon1 = $location->location_longitude;
                        $theta = $lon1 - $lon2;
                        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                    }

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
            // dd($services->where('service_recordid', '1350703837531758')->first());
            $services = $services->setCollection($services1);
            $service_taxonomy_info_list = [];
            $service_taxonomy_badge_color_list = [];
            foreach ($services as $key => $service) {
                $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);

                foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {

                    $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
                    if (isset($taxonomy)) {
                        $service_taxonomy_name = $taxonomy->taxonomy_name;
                        $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                        $service_taxonomy_badge_color_list[$service_taxonomy_recordid] = $taxonomy->badge_color;
                    }
                }
            }

            $service_details_info_list = [];
            foreach ($services as $key => $service) {
                $service_details_recordid_list = explode(',', $service->service_details);
                foreach ($service_details_recordid_list as $key => $service_details_recordid) {
                    $detail = Detail::where('detail_recordid', '=', (int)($service_details_recordid))->first();
                    if (isset($detail)) {
                        $service_detail_type = $detail->detail_type;
                        $service_details_info_list[$service_details_recordid] = $service_detail_type;
                    }
                }
            }

            $locations = $locations->get();

            // if($locations){
            //     $locations->filter(function($value,$key){
            //         $value->service = $service->service_name;
            //         $value->service_recordid = $service->service_recordid;
            //         $value->organization_name = $value->organization ? $value->organization->organization_name : '';
            //         $value->organization_recordid = $value->organization ? $value->organization->organization_recordid : '';
            //         $value->address_name = $value->address && count($value->address) > 0 ? $value->address[0]->address_1 : '';
            //         return true;
            //     });
            // }

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
                // $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
                // $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId ? $serviceCategoryId->taxonomy_type_recordid : '');
                $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name');
                $taxonomy_recordids = Taxonomy::getTaxonomyRecordids();
                if (count($taxonomy_recordids) > 0) {
                    $parent_taxonomies->whereIn('taxonomy_recordid', $taxonomy_recordids);
                }
                $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies->get();
            }
            // $selected_organization = join(',', $organization_tags);
            $selected_organization = json_encode($organization_tags);
            $selected_service_tags = json_encode($service_tags);

            $organization_tagsArray = OrganizationTag::get();
            $organization_tagsArray = json_encode($organization_tagsArray);
            $service_tagsArray = ServiceTag::get();
            $service_tagsArray = json_encode($service_tagsArray);

            $categoryIds = Service::whereNotNull('code_category_ids')->where('code_category_ids', '!=', '')->pluck('code_category_ids')->toArray();
            $tempCate = [];
            foreach ($categoryIds as $key => $value) {
                $tempCate = array_merge(explode(',', $value), $tempCate);
            }
            $sdoh_codes_category_Array = CodeCategory::select('name', 'id')->get();
            $sdoh_codes_category_Array = json_encode($sdoh_codes_category_Array);

            $sdoh_codes_category = $sdoh_codes_category ? json_encode($sdoh_codes_category) : json_encode([]);
            // codes


            $selectedCodesName = $sdoh_codes_data && count($sdoh_codes_data) > 0 ? Code::whereIn('id', $sdoh_codes_data)->pluck('code')->toArray() : [];
            $selected_sdoh_code = ($sdoh_codes_data);

            $codesIds = Service::whereNotNull('SDOH_code')->where('SDOH_code', '!=', '')->pluck('SDOH_code')->toArray();
            $tempCode = [];
            foreach ($codesIds as $key => $value) {
                $tempCode = array_merge(explode(',', $value), $tempCode);
            }
            $tempCode = array_values(array_unique($tempCode));

            $sdoh_codes_Array = Code::whereIn('id', $tempCode)->select('code', 'id')->whereNotNull('code')->get();
            // $sdoh_codes_Array = $sdoh_codes_Array ? json_encode($sdoh_codes_Array) : json_encode([]);

            // $selectedCodesName = count($selectedCodesName) > 0 ? implode(', ', $selectedCodesName) : '';
            $selectedCodesName = $selectedCodesName;

            // $selectedCategoryName = count($selected_code_category_array) > 0 ? implode(', ', $selected_code_category_array) : '';
            $selectedCategoryName = $selected_code_category_array;


            return view('frontEnd.services.services', compact('services', 'locations', 'chip_service', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'search_results', 'pagination', 'sort', 'meta_status', 'target_populations', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list', 'service_details_info_list', 'avarageLatitude', 'avarageLongitude', 'service_taxonomy_badge_color_list', 'organization_tagsArray', 'selected_organization', 'layout', 'filter_label', 'service_tagsArray', 'selected_service_tags', 'sdoh_codes_category', 'sdoh_codes_category_Array', 'selected_sdoh_code', 'sdoh_codes_Array', 'selectedCodesName', 'selectedCategoryName', 'organizationStatus'))->with('taxonomy_tree', $taxonomy_tree);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('/');
        }
    }

    public function filter_organization(Request $request)
    {
        try {
            $organization_tag_list = Organization::whereNotNull('organization_tag')->select('organization_tag')->pluck('organization_tag')->toArray();
            $chip_organization = $request->input('find');
            $sort = $request->input('sort');
            $organization_tags = $request->get('organization_tags');
            $layout = Layout::find(1);


            $checked_taxonomies = $request->input('selected_taxonomies');
            // $organizations = Organization::where('organization_name', 'like', '%'.$chip_organization.'%')->orwhere('organization_description', 'like', '%'.$chip_organization.'%');
            // $organizations = Organization::where('organization_name', 'like', '%' . $chip_organization . '%')->orwhere('organization_alternate_name', 'like', '%' . $chip_organization . '%');
            $organizations = Organization::select('*');
            $metas = MetaFilter::all();
            $count_metas = MetaFilter::count();

            $filter_label = $request->filter_label ? $request->filter_label : (Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label);
            Session::put('filter_label', $filter_label);

            if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label') {
                $filterServiceRecordId = [];
                foreach ($metas as $key => $meta) {
                    $values = explode(",", $meta->values);
                    if (count($values) > 0) {
                        if ($meta->facet == 'organization_status') {
                            $organizations_status_ids = Organization::getOrganizationStatusMeta($values, $meta->operations);
                            $organizations = $organizations->whereIn('organization_recordid', array_unique($organizations_status_ids));
                        }
                        if ($meta->facet == 'organization_tag') {
                            $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta->operations);
                            $organizations = $organizations->whereIn('organization_recordid', array_unique($organizations_tags_ids));
                        }
                        if ($meta->facet == 'Service_status') {
                            $serviceStatusIds = Service::getServiceStatusMeta($values, $meta->operations);
                            $filterServiceRecordId = array_merge($filterServiceRecordId, $serviceStatusIds);
                        }
                        if ($meta->facet == 'service_tag') {
                            $serviceTagIds = Service::getServiceTagMeta($values, $meta->operations);
                            $filterServiceRecordId = array_merge($filterServiceRecordId, $serviceTagIds);
                        }
                    }
                }
                if ($layout && $layout->hide_organizations_with_no_filtered_services == 1 && count($filterServiceRecordId) > 0) {
                    $organizationIds = ServiceOrganization::whereIn('service_recordid', $filterServiceRecordId)->pluck('organization_recordid')->toArray();
                    $organizations = $organizations->whereIn('organization_recordid', array_unique($organizationIds));
                }
            }
            if ($chip_organization) {
                if ($chip_organization[0] == '"' && $chip_organization[strlen($chip_organization) - 1] == '"') {
                    $string = trim($chip_organization, '"');
                    $organizations->where('organization_name', 'like', $string . '%');
                } else {
                    $organizations->where('organization_name', 'like', '%' . $chip_organization . '%');
                }
            }

            $organization_tags = $organization_tags != null ? json_decode($organization_tags) : [];
            if ($request->organization_tags && count($organization_tags) > 0) {
                // $organization_tags = explode(',', $organization_tags);
                // $organizations = $organizations->whereIn('organization_tag', $organization_tags);
                $orgIds = [];
                foreach ($organization_tags as $keyword) {
                    $ids = Organization::where('organization_tag', 'LIKE', '%' . $keyword . '%')->pluck('id')->toArray();
                    $orgIds = array_merge($orgIds, $ids);
                }
                $organizations->whereIn('id', $orgIds);

                // $organizations = $organizations->where(function ($query) use ($organization_tags) {
                //     foreach ($organization_tags as $keyword) {
                //         $query->where('organization_tag', 'LIKE', '% ' . $keyword . ' %');
                //     }
                //     return $query;
                // });
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

            if ($request->organization_csv == 'csv') {
                if ($request->has('organization_recordid')) {
                    $organizations = Organization::where('organization_recordid', $request->organization_recordid);
                }
                return Excel::download(new OrganizationExport($organizations), 'Organization.csv');
            }
            if ($request->organization_pdf == 'pdf') {
                $layout = Layout::find(1);

                if ($request->has('organization_recordid')) {
                    $organizations = Organization::where('organization_recordid', $request->organization_recordid);
                }

                // $organizations = Organization::select('*');
                // if ($chip_organization) {
                //     $organizations = Organization::where('organization_name', 'like', '%' . $chip_organization . '%');
                // }
                // if ($request->organization_tags && count($organization_tags) > 0) {
                //     // $organization_tags = explode(',', $organization_tags);
                //     $organizations = $organizations->whereIn('organization_tag', $organization_tags);
                // }
                // // if ($organization_tags) {
                // //     $organization_tags = explode(',', $organization_tags);
                // //     $organizations = $organizations->whereIn('organization_tag', $organization_tags);
                // // }
                // if (strpos(url()->previous(), '/organizations/') !== false) {
                //     $url = url()->previous();
                //     $recordedId = explode('organizations/', $url);
                //     if (count($recordedId) > 1) {
                //         $organizations = $organizations->where('organization_recordid', $recordedId[1]);
                //     }
                // }
                // if ($sort == 'Most Recently Updated') {
                //     $organizations = $organizations->orderBy('updated_at', 'desc')->get();
                // } else if ($sort == 'Least Recently Updated') {
                //     $organizations = $organizations->orderBy('updated_at')->get();
                // } else {
                //     $organizations = $organizations->orderBy('updated_at', 'desc')->get();
                // }
                $pdf = PDF::loadView('frontEnd.organizations.organizations_download', compact('organizations', 'layout'));

                return $pdf->download('organizations.pdf');
            }


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

                            $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
                            if (isset($taxonomy)) {
                                $service_taxonomy_name = $taxonomy->taxonomy_name;
                                $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                                $service_taxonomy_badge_color_list[$service_taxonomy_recordid] = $taxonomy->badge_color;
                            }
                        }
                    }
                }
            }

            $map = Map::find(1);
            // if ($organization_tags != '') {
            //     $organization_tags = implode(',', $organization_tags);
            // }
            $organization_tags = json_encode($organization_tags);
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
            return view('frontEnd.organizations.index', compact('map', 'organizations', 'chip_organization', 'search_results', 'organization_tag_list', 'pagination', 'sort', 'organization_tags', 'filter_label', 'organizationStatus'));
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
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

    public function fetchService(Request $request)
    {
        try {
            $query = $request->get('query');
            if ($query) {
                $metas = MetaFilter::all();
                $layout = Layout::find(1);
                $count_metas = MetaFilter::count();
                $serviceNames = Service::query();
                $organization_names = Organization::where('organization_name', 'like', '%' . $query . '%');
                $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;
                if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label') {
                    $address_serviceids = [];
                    $taxonomy_serviceids = [];
                    $organization_serviceids = [];

                    foreach ($metas as $key => $meta) {
                        $values = explode(",", $meta->values);
                        if (count($values) > 0) {

                            if ($meta->facet == 'Postal_code') {
                                $address_serviceids = [];
                                if ($meta->operations == 'Include') {
                                    $serviceids = ServiceAddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                                }

                                if ($meta->operations == 'Exclude') {
                                    $serviceids = ServiceAddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                                }

                                $address_serviceids = array_merge($serviceids, $address_serviceids);
                            }
                            if ($meta->facet == 'Taxonomy') {

                                if ($meta->operations == 'Include') {
                                    $serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                                }

                                if ($meta->operations == 'Exclude') {
                                    $serviceids = ServiceTaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                                }

                                $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                            }
                            if ($meta->facet == 'Service_status') {

                                $serviceids = Service::getServiceStatusMeta($values, $meta->operations);
                                $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                            }
                            if ($meta->facet == 'service_tag') {
                                $operations = $meta->operations;
                                $service_tag_ids = Service::getServiceTagMeta($values, $operations);
                                $taxonomy_serviceids = array_merge($service_tag_ids, $taxonomy_serviceids);
                            }
                            if ($meta->facet == 'organization_status') {
                                if ($values && count($values) > 0) {
                                    $organizations_status_ids = Organization::getOrganizationStatusMeta($values, $meta->operations);
                                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_status_ids)->pluck('service_recordid')->toArray();
                                    $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                                    $organization_serviceids = $organizations_status_ids;
                                }
                            }
                            if ($meta->facet == 'organization_tag') {
                                if ($values && count($values) > 0) {
                                    $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta->operations);
                                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid')->toArray();
                                    $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                                }
                            }
                        }
                    }
                    $serviceRecordIds = array_merge($taxonomy_serviceids, $address_serviceids);
                    $serviceNames->where(function ($q) use ($serviceRecordIds) {
                        return $q->whereIn('service_recordid', $serviceRecordIds);
                    });

                    $organization_names = $organization_names->whereIn('organization_recordid', $organization_serviceids);
                }
                $serviceNames->where(function ($q) use ($query) {
                    $q->where('service_name', 'like', '%' . $query . '%')->orwhere('service_description', 'like', '%' . $query . '%')->orwhere('service_airs_taxonomy_x', 'like', '%' . $query . '%');
                });

                // $serviceTagsIds = ServiceTag::whereNotNull('tag')->where('tag', 'LIKE', '%' . $query . '%')->pluck('id')->toArray();

                // if (count($serviceTagsIds) > 0) {
                //     $serviceNames = $serviceNames->orWhere(function ($query) use ($serviceTagsIds) {
                //         foreach ($serviceTagsIds as $keyword) {
                //             $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                //         }
                //         return $query;
                //     });
                // }

                $taxonomy_names = Taxonomy::where('taxonomy_name', 'like', '%' . $query . '%');
                $taxonomy_recordids = Taxonomy::getTaxonomyRecordids();
                if (count($taxonomy_recordids) > 0) {
                    $taxonomy_names = $taxonomy_names->whereIn('taxonomy_recordid', array_values($taxonomy_recordids));
                }
                $taxonomy_names = $taxonomy_names->pluck('taxonomy_name')->toArray();
                $serviceNames = $serviceNames->pluck('service_name')->toArray();

                $organization_names = $organization_names->pluck('organization_name')->toArray();

                $data = array_merge($serviceNames, $organization_names, $taxonomy_names);
                $output = '<ul class="dropdown-menu" style="display:block;position:absolute;max-height:300px;overflow:auto;width: 100%;">';
                if (count($data) > 0) {
                    foreach ($data as $key => $value) {
                        $output .= '<li><a href="javascript:void(0)" style="color:#000;">' . $value . '</a></li> ';
                    }
                } else {
                    $output .= '<li><a href="javascript:void(0)" style="color:#000;">No record found!</a></li> ';
                }

                $output .= '</ul>';
                echo $output;
            }
        } catch (\Throwable $th) {
            Log::error('Error in fetchService : ' . $th);
        }
    }

    public function fetchOrganization(Request $request)
    {
        try {
            $query = $request->get('query');
            if ($query) {
                $organization_names = Organization::where('organization_name', 'like', '%' . $query . '%')->orWhere('organization_alternate_name', 'like', '%' . $query . '%');

                $metas = MetaFilter::all();
                $layout = Layout::find(1);
                $count_metas = MetaFilter::count();
                $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;

                if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label') {
                    $organization_serviceids = [];

                    foreach ($metas as $key => $meta) {
                        $values = explode(",", $meta->values);
                        if (count($values) > 0) {
                            if ($meta->facet == 'organization_status') {
                                $organization_serviceids = Organization::getOrganizationStatusMeta($values, $meta->operations);
                            }
                            if ($meta->facet == 'organization_tag') {
                                $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta->operations);
                                $organization_serviceids = array_merge($organization_serviceids, $organizations_tags_ids);
                            }
                        }
                    }
                    $organization_names = $organization_names->whereIn('organization_recordid', $organization_serviceids);
                }

                $data = $organization_names->pluck('organization_name')->toArray();


                $output = '<ul class="dropdown-menu" style="display:block;position:absolute;max-height:300px;overflow:auto;width: 100%;">';
                if (count($data) > 0) {
                    foreach ($data as $key => $value) {
                        $output .= '<li><a href="#">' . $value . '</a></li> ';
                    }
                } else {
                    $output .= '<li><a href="#">No record found!</a></li> ';
                }

                $output .= '</ul>';
                echo $output;
            }
        } catch (\Throwable $th) {
            Log::error('Error in fetchOrganization : ' . $th);
        }
    }
}
