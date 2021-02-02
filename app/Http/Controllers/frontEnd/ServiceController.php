<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\ServiceLocationImport;
use App\Imports\Services;
use App\Exports\ServiceExport;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\Airtable_v2;
use App\Model\Alt_taxonomy;
use App\Model\Contact;
use App\Model\CSV_Source;
use App\Model\Detail;
use App\Model\Layout;
use App\Model\Location;
use App\Model\Map;
use App\Model\MetaFilter;
use App\Model\Organization;
use App\Model\Phone;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceContact;
use App\Model\ServiceDetail;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServicePhone;
use App\Model\ServiceSchedule;
use App\Model\ServiceTaxonomy;
use App\Model\Source_data;
use App\Model\DetailType;
use App\Model\Taxonomy;
use App\Services\Stringtoint;
use Carbon\Carbon;
use App\Model\csv;
use App\Model\Language;
use App\Model\PhoneType;
use App\Model\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $service_state_filter = 'Verified';
        // $services = Service::with('locations')->orderBy('service_name')->where('service_status', '=', $service_state_filter);
        $services = Service::with('locations')->orderBy('service_name');
        $locations = Location::with('services', 'organization', 'address');

        $sort_by_distance_clickable = false;
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
        $meta_status = 'On';

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
                        $serviceids = Serviceaddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    if ($meta->operations == 'Exclude') {
                        $serviceids = Serviceaddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    $address_serviceids = array_merge($serviceids, $address_serviceids);
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
                if ($meta->facet == 'Service_status') {

                    if ($meta->operations == 'Include') {
                        $serviceids = Service::whereIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    if ($meta->operations == 'Exclude') {
                        $serviceids = Service::whereNotIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                    }

                    $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                }
            }

            // $services = $services->whereIn('service_recordid', $address_serviceids)->whereIn('service_recordid', $taxonomy_serviceids);

            if ($address_serviceids) {
                $services = $services->whereIn('service_recordid', $address_serviceids);
            }
            if ($taxonomy_serviceids) {
                $services = $services->whereIn('service_recordid', $taxonomy_serviceids);
            }

            $services_ids = $services->pluck('service_recordid')->toArray();
            $locations_ids = Servicelocation::whereIn('service_recordid', $services_ids)->pluck('location_recordid')->toArray();
            $locations = $locations->whereIn('location_recordid', $locations_ids);
        }
        $services = $services->paginate(10);

        $service_taxonomy_info_list = [];
        $service_taxonomy_badge_color_list = [];
        foreach ($services as $key => $service) {
            $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
            foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {
                $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int) ($service_taxonomy_recordid))->first();
                if (isset($taxonomy)) {
                    $service_taxonomy_name = $taxonomy->taxonomy_name;
                    $service_taxonomy_info_list[$service_taxonomy_recordid] = $service_taxonomy_name;
                    $service_taxonomy_badge_color_list[$service_taxonomy_recordid] = $taxonomy->badge_color;
                }
            }
        }
        $locations = $locations->get();

        $organization_tags = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();

        $tag_list = [];
        foreach ($organization_tags as $key => $value) {
            $tags = explode(",", trim($value->organization_tag));
            $tag_list = array_merge($tag_list, $tags);
        }
        $tag_list = array_unique($tag_list);
        $organization_tagsArray = [];
        foreach ($tag_list as $key => $value) {
            $organization_tagsArray[$value] = $value;
        }


        //======================updated alt taxonomy tree======================

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


        return view('frontEnd.services.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'meta_status', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list', 'service_taxonomy_badge_color_list', 'organization_tagsArray'))->with('taxonomy_tree', $taxonomy_tree);
    }

    public function tb_services()
    {
        $services = Service::with('locations', 'organizations', 'locations', 'taxonomy', 'phone', 'schedules', 'contact', 'details', 'address')->orderBy('service_recordid', 'asc')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_services', compact('services', 'source_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);

        if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
            $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
            $organization_names = Organization::select("organization_name")->whereIn('organization_recordid', $organization_recordid)->distinct()->get();
        } else {
            $organization_names = Organization::select("organization_name")->distinct()->get();
        }
        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);


        $facility_info_list = Location::select('location_recordid', 'location_name')->orderBy('location_recordid')->distinct()->get();

        $service_status_list = ['Yes', 'No'];

        // $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $layout = Layout::findOrFail(1);
        $exclude_vocabulary = [];
        if ($layout) {
            $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
        }
        $taxonomy_info_list = Taxonomy::whereNull('taxonomy_parent_name')->Where(function ($query) use ($exclude_vocabulary) {
            for ($i = 0; $i < count($exclude_vocabulary); $i++) {
                $query->where('taxonomy_name', 'not like',  '%' . $exclude_vocabulary[$i] . '%');
            }
        })->get();
        $taxonomyArray = [];
        $taxonomy_info_list = $taxonomy_info_list->filter(function ($value) use ($taxonomyArray) {
            if ($value->taxonomy_parent_name == null) {
                // $taxonomyArray[] = $value;
                $t = Taxonomy::where('taxonomy_parent_name', $value->taxonomy_recordid)->whereNull('exclude_vocabulary')->orderBy('order')->get();
                foreach ($t as $key => $value1) {
                    $taxonomyArray[] = $value1;
                }
                $value->taxonomyArray = $taxonomyArray;
            }
            return true;
        });
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();

        $all_contacts = Contact::orderBy('contact_recordid')->with('phone')->distinct()->get();
        $all_locations = Location::orderBy('location_recordid')->with('phones', 'address', 'schedules')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();



        $phone_languages = Language::pluck('language', 'language_recordid');

        $phone_type = PhoneType::pluck('type', 'id');
        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        // $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $detail_info_list = Detail::pluck('detail_value', 'detail_recordid')->unique();

        $address_cities = Address::select("address_city")->distinct()->get();
        $address_states = Address::select("address_state_province")->distinct()->get();

        $address_states_list = [];
        foreach ($address_states as $key => $value) {
            $state = explode(", ", trim($value->address_state_province));
            $address_states_list = array_merge($address_states_list, $state);
        }
        $address_states_list = array_unique($address_states_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", ", trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);

        $detail_types = DetailType::pluck('type', 'type');
        $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Category')->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Eligibility')->pluck('taxonomy_name', 'taxonomy_recordid');
        $programs = Program::pluck('name', 'program_recordid');

        return view('frontEnd.services.create', compact('map', 'organization_name_list', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list', 'all_contacts', 'all_locations', 'phone_languages', 'phone_type', 'service_info_list', 'address_city_list', 'address_states_list', 'detail_types', 'service_category_types', 'service_eligibility_types', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
            'service_organization' => 'required',
        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }

        try {
            $service = new Service;

            $service_recordids = Service::select("service_recordid")->distinct()->get();
            $service_recordid_list = array();
            foreach ($service_recordids as $key => $value) {
                $service_recordid = $value->service_recordid;
                array_push($service_recordid_list, $service_recordid);
            }
            $service_recordid_list = array_unique($service_recordid_list);

            $new_recordid = Service::max('service_recordid') + 1;
            if (in_array($new_recordid, $service_recordid_list)) {
                $new_recordid = Service::max('service_recordid') + 1;
            }
            $service->service_recordid = $new_recordid;

            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_url = $request->service_url;
            // $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            $service->service_status = '';
            if ($request->service_status = 'Yes') {
                $service->service_status = 'Verified';
            }
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;

            $organization_name = $request->service_organization;
            $service_organization = Organization::where('organization_name', '=', $organization_name)->first();
            $service_organization_id = $service_organization["organization_recordid"];
            $service->service_organization = $service_organization_id;

            if ($request->service_program) {
                $program = new Program();
                $program->program_recordid = Program::max('program_recordid') + 1;
                $program->name = $request->service_program;
                if ($request->program_alternate_name) {
                    $program->alternate_name = $request->program_alternate_name;
                }
                $program->services = $new_recordid;
                $recordids = [];
                $recordids[] = $new_recordid;
                $program->service()->sync($recordids);
                $program->save();
            }

            // if ($request->service_locations) {
            //     foreach ($request->service_locations as $key => $locationId) {
            //         ServiceLocation::create([
            //             'service_recordid' => $new_recordid,
            //             'location_recordid' => $locationId
            //         ]);
            //     }
            //     $service->service_locations = join(',', $request->service_locations);
            // } else {
            //     $service->service_locations = '';
            // }
            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                for ($i = 0; $i < count($request->location_name); $i++) {
                    $location_address_recordid_list = [];
                    $location_phone_recordid_list = [];
                    // location phone section
                    $location_phone_numbers = $request->location_phone_numbers && count($request->location_phone_numbers) ? json_decode($request->location_phone_numbers[0], true) : [];
                    $location_phone_extensions = $request->location_phone_extensions && count($request->location_phone_extensions) ? json_decode($request->location_phone_extensions[0], true) : [];
                    $location_phone_types = $request->location_phone_types && count($request->location_phone_types) ? json_decode($request->location_phone_types[0], true) : [];
                    $location_phone_languages = $request->location_phone_languages && count($request->location_phone_languages) ? json_decode($request->location_phone_languages[0], true) : [];



                    $location_phone_descriptions = $request->location_phone_descriptions && count($request->location_phone_descriptions) ? json_decode($request->location_phone_descriptions[0], true) : [];

                    // location schedule section
                    $opens_at_location_monday_datas = $request->opens_at_location_monday_datas  ? json_decode($request->opens_at_location_monday_datas, true) : [];
                    $closes_at_location_monday_datas = $request->closes_at_location_monday_datas  ? json_decode($request->closes_at_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas  ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_at_location_tuesday_datas = $request->opens_at_location_tuesday_datas  ? json_decode($request->opens_at_location_tuesday_datas, true) : [];
                    $closes_at_location_tuesday_datas = $request->closes_at_location_tuesday_datas  ? json_decode($request->closes_at_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas  ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_at_location_wednesday_datas = $request->opens_at_location_wednesday_datas  ? json_decode($request->opens_at_location_wednesday_datas, true) : [];
                    $closes_at_location_wednesday_datas = $request->closes_at_location_wednesday_datas  ? json_decode($request->closes_at_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas  ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_at_location_thursday_datas = $request->opens_at_location_thursday_datas  ? json_decode($request->opens_at_location_thursday_datas, true) : [];
                    $closes_at_location_thursday_datas = $request->closes_at_location_thursday_datas  ? json_decode($request->closes_at_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas  ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_at_location_friday_datas = $request->opens_at_location_friday_datas  ? json_decode($request->opens_at_location_friday_datas, true) : [];
                    $closes_at_location_friday_datas = $request->closes_at_location_friday_datas  ? json_decode($request->closes_at_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas  ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_at_location_saturday_datas = $request->opens_at_location_saturday_datas  ? json_decode($request->opens_at_location_saturday_datas, true) : [];
                    $closes_at_location_saturday_datas = $request->closes_at_location_saturday_datas  ? json_decode($request->closes_at_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas  ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_at_location_sunday_datas = $request->opens_at_location_sunday_datas  ? json_decode($request->opens_at_location_sunday_datas, true) : [];
                    $closes_at_location_sunday_datas = $request->closes_at_location_sunday_datas  ? json_decode($request->closes_at_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas  ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates  ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates  ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats  ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats  ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds  ? json_decode($request->location_holiday_closeds, true) : [];



                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = Location::max('location_recordid') + 1;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = $location_alternate_name[$i];
                        $location->location_transportation = $location_transporation[$i];
                        $location->location_description = $location_description[$i];
                        $location->location_details = $location_details[$i];
                        // if ($location_service) {
                        //     $location->location_services = join(',', $location_service[$i]);
                        // } else {
                        //     $location->location_services = '';
                        // }
                        // $location->services()->sync($location_service[$i]);

                        // if ($location_schedules[$i]) {
                        //     $location->location_schedule = join(',', $location_schedules[$i]);
                        // } else {
                        //     $location->location_schedule = '';
                        // }
                        // $location->schedules()->sync($location_schedules[$i]);
                        // location address
                        $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                        if ($address_info) {
                            $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                            $address_info->address_1 = $request->location_address[$i];
                            $address_info->address_city = $request->location_city[$i];
                            $address_info->address_state_province = $request->location_state[$i];
                            $address_info->address_postal_code = $request->location_zipcode[$i];
                            $address_info->save();
                            array_push($location_address_recordid_list, $address_info->address_recordid);
                        } else {
                            $new_address = new address;
                            $new_address_recordid = address::max('address_recordid') + 1;
                            $new_address->address_recordid = $new_address_recordid;
                            $new_address->address_1 = $request->location_address[$i];
                            $new_address->address_city = $request->location_city[$i];
                            $new_address->address_state_province = $request->location_state[$i];
                            $new_address->address_postal_code = $request->location_zipcode[$i];
                            $new_address->save();
                            $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $location_phone_types[$i][$p];

                                    $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $location_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {
                                $schedules = Schedule::where('schedule_locations', $location_recordid)->where('byday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedules->schedule_recordid;
                                } else {
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location_recordid;
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                }
                            }
                        }

                        if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                            Schedule::where('schedule_locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                //     $schedules->closes_at = $request->closes_at[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->schedule_locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                if ($location_holiday_closeds[$i][$hs] == 1) {
                                    $schedules->schedule_closed = '1';
                                }
                                $schedules->schedule_holiday = '1';
                                $schedules->save();
                                $schedule_locations[] = $schedule_recordid;
                                // }
                            }
                        }
                        $location->location_schedule = join(',', $schedule_locations);

                        $location->schedules()->sync($schedule_locations);
                        $location->phones()->sync($location_phone_recordid_list);

                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = $location_alternate_name[$i];
                            $location->location_transportation = $location_transporation[$i];
                            $location->location_description = $location_description[$i];
                            $location->location_details = $location_details[$i];
                            // if ($location_service) {
                            //     $location->location_services = join(',', $location_service[$i]);
                            // } else {
                            //     $location->location_services = '';
                            // }
                            // $location->services()->sync($location_service[$i]);

                            // if ($location_schedules[$i]) {
                            //     $location->location_schedule = join(',', $location_schedules[$i]);
                            // } else {
                            //     $location->location_schedule = '';
                            // }
                            // $location->schedules()->sync($location_schedules[$i]);
                            // location address
                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                                $address_info->address_1 = $request->location_address[$i];
                                $address_info->address_city = $request->location_city[$i];
                                $address_info->address_state_province = $request->location_state[$i];
                                $address_info->address_postal_code = $request->location_zipcode[$i];
                                $address_info->save();
                                array_push($location_address_recordid_list, $address_info->address_recordid);
                            } else {
                                $new_address = new address;
                                $new_address_recordid = address::max('address_recordid') + 1;
                                $new_address->address_recordid = $new_address_recordid;
                                $new_address->address_1 = $request->location_address[$i];
                                $new_address->address_city = $request->location_city[$i];
                                $new_address->address_state_province = $request->location_state[$i];
                                $new_address->address_postal_code = $request->location_zipcode[$i];
                                $new_address->save();
                                $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                        $phone_info->save();
                                        array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                    } else {
                                        $new_phone = new Phone;
                                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                        $new_phone->phone_recordid = $new_phone_recordid;
                                        $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                        $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                        $new_phone->phone_type = $location_phone_types[$i][$p];
                                        $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }

                            // schedule section
                            $schedule_locations = [];

                            if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('schedule_locations', $location->location_recordid)->where('byday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedules->schedule_recordid;
                                    } else {
                                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                        $schedules = new Schedule();
                                        $schedules->schedule_recordid = $schedule_recordid;
                                        $schedules->schedule_locations = $location->location_recordid;
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedule_recordid;
                                    }
                                }
                            }

                            if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                                Schedule::where('schedule_locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                    //     $schedules->closes_at = $request->closes_at[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                    if ($location_holiday_closeds[$i][$hs] == 1) {
                                        $schedules->schedule_closed = '1';
                                    }
                                    $schedules->schedule_holiday = '1';
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                    // }
                                }
                            }
                            $location->location_schedule = join(',', $schedule_locations);

                            $location->schedules()->sync($schedule_locations);
                            $location->phones()->sync($location_phone_recordid_list);
                            $location->address()->sync($location_address_recordid_list);
                            $location->save();
                        }
                        array_push($service_locations, $request->location_recordid[$i]);
                    }
                }
            }
            $service->service_locations = join(',', $service_locations);
            $service->locations()->sync($service_locations);

            // if ($request->service_taxonomies) {
            //     $service->service_taxonomy = join(',', $request->service_taxonomies);
            // } else {
            //     $service->service_taxonomy = '';
            // }
            // $service->taxonomy()->sync($request->service_taxonomies);
            $service_taxonomies = [];
            // if ($request->service_category_type) {
            //     $service_taxonomies = array_merge($service_taxonomies, $request->service_category_type);
            //     $service_taxonomies = array_merge($service_taxonomies, $request->service_category_term);
            // } elseif ($request->service_eligibility_type) {
            //     $service_taxonomies = array_merge($service_taxonomies, $request->service_eligibility_type);
            //     $service_taxonomies = array_merge($service_taxonomies, $request->service_eligibility_term);
            // }

            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if ($service_category_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_category_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Category';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        $service_taxonomies[] = $service_category_term[$key];
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = $request->service_eligibility_type;
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        $service_taxonomies[] = $service_eligibility_term[$key];
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = array_unique(array_values(array_filter($service_taxonomies)));
            }
            $service->service_taxonomy = join(',', $service_taxonomies);
            $service->taxonomy()->sync($service_taxonomies);

            // $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
            // $phone_recordid_list = array();
            // foreach ($phone_recordids as $key => $value) {
            //     $phone_recordid = $value->phone_recordid;
            //     array_push($phone_recordid_list, $phone_recordid);
            // }
            // $phone_recordid_list = array_unique($phone_recordid_list);

            // $service_phones = $request->service_phones;
            // $cell_phone = Phone::where('phone_number', '=', $service_phones)->first();
            // if ($cell_phone != null) {
            //     $cell_phone_id = $cell_phone["phone_recordid"];
            //     $service->service_phones = $cell_phone_id;
            // } else {
            //     $phone = new Phone;
            //     $new_recordid = Phone::max('phone_recordid') + 1;
            //     if (in_array($new_recordid, $phone_recordid_list)) {
            //         $new_recordid = Phone::max('phone_recordid') + 1;
            //     }
            //     $phone->phone_recordid = $new_recordid;
            //     $phone->phone_number = $cell_phone;
            //     $phone->phone_type = "voice";
            //     $service->service_phones = $phone->phone_recordid;
            //     $phone->save();
            // }

            // $service_phone_info_list = array();
            // array_push($service_phone_info_list, $service->service_phones);
            // $service_phone_info_list = array_unique($service_phone_info_list);
            // $service->phone()->sync($service_phone_info_list);

            $service->service_phones = '';
            $phone_recordid_list = [];

            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                // foreach ($service_phone_number_list as $key => $service_phone_number) {

                //     if ($phone_info) {
                //         $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($service_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $service_phone_number_list[$i];
                        $phone_info->phone_extension = $service_phone_extension_list[$i];
                        $phone_info->phone_type = $service_phone_type_list[$i];
                        $phone_info->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 ?  implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 ?  implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            // if ($request->service_schedules) {
            //     $service->service_schedule = join(',', $request->service_schedules);
            // } else {
            //     $service->service_schedule = '';
            // }
            // $service->schedules()->sync($request->service_schedules);

            $schedule_services = [];

            if ($request->byday) {
                for ($i = 0; $i < 7; $i++) {
                    $schedules = Schedule::where('schedule_services', $service->service_recordid)->where('byday', $request->byday[$i])->first();
                    if ($schedules) {
                        $schedules->byday = $request->byday[$i];
                        $schedules->opens_at = $request->opens_at[$i];
                        $schedules->closes_at = $request->closes_at[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $i + 1;
                        } else {
                            $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedules->schedule_recordid;
                    } else {
                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                        $schedules = new Schedule();
                        $schedules->schedule_recordid = $schedule_recordid;
                        $schedules->schedule_services = $service->service_recordid;
                        $schedules->byday = $request->byday[$i];
                        $schedules->opens_at = $request->opens_at[$i];
                        $schedules->closes_at = $request->closes_at[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $i + 1;
                        } else {
                            $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedule_recordid;
                    }
                }
            }
            if ($request->holiday_start_date && $request->holiday_end_date && $request->holiday_open_at && $request->holiday_close_at && (count($request->holiday_start_date) == 0 && $request->holiday_start_date[0] != null)) {
                Schedule::where('schedule_services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    // $schedules =
                    // if($schedules){
                    //     $schedules->dtstart = $request->holiday_start_date[$i];
                    //     $schedules->until = $request->holiday_end_date[$i];
                    //     $schedules->opens_at = $request->holiday_open_at[$i];
                    //     $schedules->closes_at = $request->closes_at[$i];
                    //     if(in_array(($i+1),$request->schedule_closed)){
                    //         $schedules->schedule_closed = $i+1;
                    //     }
                    //     $schedules->save();
                    //     $schedule_services[] = $schedules->schedule_recordid;
                    // }else{
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->schedule_services = $service->service_recordid;
                    $schedules->dtstart = $request->holiday_start_date[$i];
                    $schedules->until = $request->holiday_end_date[$i];
                    $schedules->opens_at = $request->holiday_open_at[$i];
                    $schedules->closes_at = $request->holiday_close_at[$i];
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            $service->schedules()->sync($schedule_services);

            // if ($request->service_contacts) {
            //     $service->service_contacts = join(',', $request->service_contacts);
            // } else {
            //     $service->service_contacts = '';
            // }
            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                // $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
                for ($i = 0; $i < count($request->contact_name); $i++) {
                    $contact_phone_recordid_list = [];
                    $contact_phone_numbers = $request->contact_phone_numbers && count($request->contact_phone_numbers) ? json_decode($request->contact_phone_numbers[0]) : [];
                    $contact_phone_extensions = $request->contact_phone_extensions && count($request->contact_phone_extensions) ? json_decode($request->contact_phone_extensions[0]) : [];
                    $contact_phone_types = $request->contact_phone_types && count($request->contact_phone_types) ? json_decode($request->contact_phone_types[0]) : [];
                    $contact_phone_languages = $request->contact_phone_languages && count($request->contact_phone_languages) ? json_decode($request->contact_phone_languages[0]) : [];
                    $contact_phone_descriptions = $request->contact_phone_descriptions && count($request->contact_phone_descriptions) ? json_decode($request->contact_phone_descriptions[0]) : [];

                    if ($request->contactRadio[$i] == 'new_data') {
                        $contact = new Contact();
                        $contact->contact_recordid = Contact::max('contact_recordid') + 1;
                        $contact->contact_name = $request->contact_name[$i];
                        $contact->contact_title = $request->contact_title[$i];
                        $contact->contact_email = $request->contact_email[$i];
                        $contact->contact_department = $contact_department[$i];
                        // $contact->contact_phones = $request->contact_phone[$i];
                        // this is contact phone section
                        $contact->contact_phones = '';
                        for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                            $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                            if ($phone_info) {
                                $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                $phone_info->phone_type = $contact_phone_types[$i][$p];
                                $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                $phone_info->save();
                                array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                            } else {
                                $new_phone = new Phone;
                                $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                $new_phone->phone_recordid = $new_phone_recordid;
                                $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                $new_phone->phone_type = $contact_phone_types[$i][$p];
                                $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                $new_phone->save();
                                $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                array_push($contact_phone_recordid_list, $new_phone_recordid);
                            }
                        }
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                        array_push($service_contacts, Contact::max('contact_recordid'));
                    } else {
                        $contact = Contact::where('contact_recordid', $request->contact_recordid[$i])->first();
                        if ($contact) {
                            $contact->contact_name = $request->contact_name[$i];
                            $contact->contact_title = $request->contact_title[$i];
                            $contact->contact_email = $request->contact_email[$i];
                            $contact->contact_department = $contact_department[$i];
                            // $phone_info = Phone::where('phone_number', '=', $request->contact_phone[$i])->first();
                            $contact->contact_phones = '';
                            for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $contact_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $contact_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                    array_push($contact_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                            $contact->phone()->sync($contact_phone_recordid_list);
                            $contact->save();
                        }
                        array_push($service_contacts, $request->contact_recordid[$i]);
                    }
                }
            }
            $service->service_contacts = join(',', $service_contacts);
            $service->contact()->sync($service_contacts);

            // if ($request->service_details) {
            //     $service->service_details = join(',', $request->service_details);
            // } else {
            //     $service->service_details = '';
            // }
            // $service->details()->sync($request->service_details);

            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if ($term_type[$key] == 'new') {
                        $detail = new Detail();
                        $detail_recordid = Detail::max('detail_recordid') + 1;
                        $detail->detail_recordid = $detail_recordid;
                        $detail->detail_type = $value;
                        $detail->detail_value = $detail_term[$key];
                        $detail->save();

                        $detail_ids[] = $detail_recordid;
                    } else {
                        $detail_ids[] = $detail_term[$key];
                    }
                }
            }
            $service->service_details = join(',', $detail_ids);
            $service->details()->sync($detail_ids);

            if ($request->service_address) {
                $service->service_address = join(',', $request->service_address);
            } else {
                $service->service_address = '';
            }
            $service->address()->sync($request->service_address);

            $service->save();

            Session::flash('message', 'Service created successfully');
            Session::flash('status', 'success');
            return redirect('services');
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('services');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $service = Service::find($id);
        // return response()->json($service);

        try {
            $service = Service::where('service_recordid', '=', $id)->first();
            if ($service) {
                $organzation_recordid = $service->service_organization;
                $organization = Organization::where('organization_recordid', '=', $organzation_recordid)->first();

                $service_phones_info = $service->service_phones;
                if (strpos($service_phones_info, ',') !== false) {
                    $service_phone_recordid_list = explode(',', $service_phones_info);
                    $phone1_recordid = $service_phone_recordid_list[0];
                    $phone2_recordid = $service_phone_recordid_list[1];
                } else {
                    $phone1_recordid = $service_phones_info;
                    $phone2_recordid = null;
                }
                $phone_number_info = '';
                if ($phone1_recordid) {
                    $phone1_number = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();
                    $phone_number_info = $phone1_number ? $phone1_number->phone_number : '';
                }
                if ($phone2_recordid) {
                    $phone2_number = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();
                    if ($phone_number_info) {
                        $phone_number_info = $phone_number_info . ', ' . $phone2_number->phone_number;
                    } else {
                        $phone_number_info = $phone2_number ? $phone2_number->phone_number : '';
                    }
                }

                $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
                $service_taxonomy_info_list = [];
                foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {
                    $service_taxonomy_info = (object) [];
                    $service_taxonomy_info->taxonomy_recordid = $service_taxonomy_recordid;

                    $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int) ($service_taxonomy_recordid))->first();
                    if (isset($taxonomy)) {
                        $service_taxonomy_name = $taxonomy->taxonomy_name;
                        $service_taxonomy_info->taxonomy_name = $service_taxonomy_name;
                        $service_taxonomy_info->badge_color = $taxonomy->badge_color;
                    }

                    array_push($service_taxonomy_info_list, $service_taxonomy_info);
                }

                // $location = Location::with('organization', 'address')->where('location_services', 'like', '%' . $id . '%')->get();

                $locations = $service->locations;

                if ($locations) {
                    $locations->filter(function ($value, $key) use ($service) {
                        $value->service = $service->service_name;
                        $value->service_recordid = $service->service_recordid;
                        $value->organization_name = $value->organization ? $value->organization->organization_name : '';
                        $value->organization_recordid = $value->organization ? $value->organization->organization_recordid : '';
                        $value->address_name = $value->address && count($value->address) > 0 ? $value->address[0]->address_1 : '';
                    });
                }
                if (count($service->contact) > 0) {
                    foreach ($service->contact as $key => $value) {
                        $service_contacts_recordid_list[] = $value->contact_recordid;
                    }
                } else {
                    $service_contacts_recordid_list = explode(',', $service->service_contacts);
                }

                $contact_info_list = Contact::whereIn('contact_recordid', $service_contacts_recordid_list)->get();

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

                //======================updated alt taxonomy tree======================

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
                return view('frontEnd.services.show', compact('service', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'service_taxonomy_info_list', 'contact_info_list', 'phone_number_info', 'organization'));
            } else {
                Session::flash('message', 'This record has been deleted.');
                Session::flash('status', 'warning');
                return redirect('services');
            }
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('services');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $map = Map::find(1);
        $service = Service::where('service_recordid', '=', $id)->first();

        if ($service) {
            $addressIds = $service->address ? $service->address->pluck('address_recordid')->toArray() : [];

            // $service_organization_list = Organization::select('organization_recordid', 'organization_name')->get();

            if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
                $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
                $organization_names = Organization::select("organization_name", "organization_recordid")->whereIn('organization_recordid', $organization_recordid)->distinct()->get();
            } else {
                $organization_names = Organization::select("organization_name", "organization_recordid")->distinct()->get();
            }
            // $organization_name_list = [];
            // foreach ($organization_names as $key => $value) {
            //     $org_names = explode(", ", trim($value->organization_name));
            //     $organization_name_list = array_merge($organization_name_list, $org_names);
            // }
            $service_organization_list = $organization_names;

            $service_location_list = Location::select('location_recordid', 'location_name')->get();
            $service_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
            // $service_taxonomy_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->get();

            $layout = Layout::findOrFail(1);
            $exclude_vocabulary = [];
            if ($layout) {
                $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
            }
            $service_taxonomy_list = Taxonomy::whereNull('taxonomy_parent_name')->whereNull('exclude_vocabulary')->Where(function ($query) use ($exclude_vocabulary) {
                for ($i = 0; $i < count($exclude_vocabulary); $i++) {
                    $query->where('taxonomy_name', 'not like',  '%' . $exclude_vocabulary[$i] . '%');
                }
            })->get();

            $taxonomyArray = [];
            $service_taxonomy_list = $service_taxonomy_list->filter(function ($value) use ($taxonomyArray) {
                if ($value->taxonomy_parent_name == null) {
                    // $taxonomyArray[] = $value;
                    $t = Taxonomy::where('taxonomy_parent_name', $value->taxonomy_recordid)->whereNull('exclude_vocabulary')->orderBy('order')->get();
                    foreach ($t as $key => $value1) {
                        $taxonomyArray[] = $value1;
                    }
                    $value->taxonomyArray = $taxonomyArray;
                }
                return true;
            });


            $service_details_list = Detail::select('detail_recordid', 'detail_value')->get();

            $location_info_list = explode(',', $service->service_locations);
            $contact_info_list = explode(',', $service->service_contacts);
            $taxonomy_info_list = explode(',', $service->service_taxonomy);

            $service_locations_data = Location::whereIn('location_recordid', $location_info_list)->with('phones', 'address', 'schedules')->get();
            $service_locations_data = $service_locations_data->filter(function ($value) {
                $address = $value->address && count($value->address) > 0  ? $value->address[count($value->address) - 1] : '';
                $phones = $value->phones && count($value->phones) > 0  ? $value->phones[count($value->phones) - 1] : '';
                $value->location_address = $address ? $address->address_1 : '';
                $value->location_city = $address ? $address->address_city : '';
                $value->location_state = $address ? $address->address_state_province : '';
                $value->location_zipcode = $address ? $address->address_postal_code : '';
                $value->location_phone = $phones ? $phones->phone_number : '';
                return true;
            });

            $service_address_id = $service->service_address;
            $service_address_street = Address::where('address_recordid', '=', $service_address_id)->select('address_1')->first();
            $service_address_city = Address::where('address_recordid', '=', $service_address_id)->select('address_city')->first();
            $service_address_state = Address::where('address_recordid', '=', $service_address_id)->select('address_state_province')->first();
            $service_address_postal_code = Address::where('address_recordid', '=', $service_address_id)->select('address_postal_code')->first();
            $phone_recordids = $service->service_phones;

            if (strpos($phone_recordids, ',') !== false) {
                $phone_recordid_list = explode(',', $phone_recordids);
                $phone1_recordid = $phone_recordid_list[0];
                $phone2_recordid = $phone_recordid_list[1];
            } else {
                $phone1_recordid = $phone_recordids;
                $phone2_recordid = '';
            }
            $ServiceSchedule = $service->service_schedule ? explode(',', $service->service_schedule) : [];

            $ServiceDetails = $service->service_details ? explode(',', $service->service_details) : [];

            if ($service->details) {
                $serviceDetailsList = $service->details ? $service->details->pluck('detail_recordid')->toarray() : [];
                $ServiceDetails = array_unique(array_merge($ServiceDetails, $serviceDetailsList));
            }

            $detail_types = DetailType::pluck('type', 'type');

            $serviceDetailsData = Detail::whereIn('detail_recordid', $ServiceDetails)->get();

            $service_phone1 = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();
            $service_phone2 = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();

            // $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->orderBy('opens_at')->distinct()->get();
            $monday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'monday')->first();
            $tuesday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'tuesday')->first();
            $wednesday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'wednesday')->first();
            $friday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'friday')->first();
            $saturday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'saturday')->first();
            $thursday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'thursday')->first();
            $sunday = Schedule::where('schedule_services', $service->service_recordid)->where('byday', 'sunday')->first();
            $holiday_schedules = Schedule::where('schedule_services', $service->service_recordid)->where('schedule_holiday', '1')->get();


            $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
            $all_contacts = Contact::orderBy('contact_recordid')->with('phone', 'service')->distinct()->get();
            $all_locations = Location::orderBy('location_recordid')->with('phones', 'address', 'services', 'schedules')->distinct()->get();
            $phone_languages = Language::pluck('language', 'language_recordid');

            // location section
            $exclude_vocabulary = [];
            if ($layout) {
                $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
            }
            // $taxonomy_info_list = Taxonomy::whereNull('taxonomy_parent_name')->Where(function ($query) use ($exclude_vocabulary) {
            //     for ($i = 0; $i < count($exclude_vocabulary); $i++) {
            //         $query->where('taxonomy_name', 'not like',  '%' . $exclude_vocabulary[$i] . '%');
            //     }
            // })->get();
            // $taxonomyArray = [];
            // $taxonomy_info_list = $taxonomy_info_list->filter(function ($value) use ($taxonomyArray) {
            //     if ($value->taxonomy_parent_name == null) {
            //         // $taxonomyArray[] = $value;
            //         $t = Taxonomy::where('taxonomy_parent_name', $value->taxonomy_recordid)->whereNull('exclude_vocabulary')->get();
            //         foreach ($t as $key => $value1) {
            //             $taxonomyArray[] = $value1;
            //         }
            //         $value->taxonomyArray = $taxonomyArray;
            //     }
            //     return true;
            // });
            $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();
            $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
            $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
            $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
            $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

            $address_cities = Address::select("address_city")->distinct()->get();
            $address_states = Address::select("address_state_province")->distinct()->get();

            $address_states_list = [];
            foreach ($address_states as $key => $value) {
                $state = explode(", ", trim($value->address_state_province));
                $address_states_list = array_merge($address_states_list, $state);
            }
            $address_states_list = array_unique($address_states_list);

            $address_city_list = [];
            foreach ($address_cities as $key => $value) {
                $cities = explode(", ", trim($value->address_city));
                $address_city_list = array_merge($address_city_list, $cities);
            }
            $address_city_list = array_unique($address_city_list);
            $phone_type = PhoneType::pluck('type', 'id');

            $location_alternate_name = [];
            $location_transporation = [];
            $location_service = [];
            $location_schedules = [];
            $location_description = [];
            $location_details = [];
            foreach ($service_locations_data as $key => $locationData) {
                $location_alternate_name[] = $locationData->location_alternate_name;
                $location_transporation[] = $locationData->location_transportation;
                $location_service[] = $locationData->services ? $locationData->services->pluck('service_recordid')->toArray() : [];
                $location_schedules[] = $locationData->schedules ? $locationData->schedules->pluck('schedule_recordid')->toArray() : [];
                $location_description[] = $locationData->location_description;
                $location_details[] = $locationData->location_details;
            }
            $location_alternate_name = json_encode($location_alternate_name);
            $location_transporation = json_encode($location_transporation);
            $location_service = json_encode($location_service);
            $location_schedules = json_encode($location_schedules);
            $location_description = json_encode($location_description);
            $location_details = json_encode($location_details);

            $contact_service = [];
            $contact_department = [];
            foreach ($service->contact as $key => $contactData) {

                $contact_service[] = $contactData->service ? $contactData->service->pluck('service_recordid')->toarray() : [];
                $contact_department[] = $contactData->contact_department;
            }
            $contact_service = json_encode($contact_service);
            $contact_department = json_encode($contact_department);

            // contact phone section
            $contact_phones_data = $service->contact()->with('phone')->get();
            $contact_phone_numbers = [];
            $contact_phone_extensions = [];
            $contact_phone_types = [];
            $contact_phone_languages = [];
            $contact_phone_descriptions = [];
            foreach ($contact_phones_data as $key => $phoneContact) {
                if ($phoneContact->phone && count($phoneContact->phone) > 0) {
                    foreach ($phoneContact->phone as $key1 => $phone) {
                        $contact_phone_numbers[$key][] = $phone->phone_number;
                        $contact_phone_extensions[$key][] = $phone->phone_extension;
                        $contact_phone_types[$key][] = $phone->phone_type;
                        $contact_phone_languages[$key][] = $phone->phone_language ? explode(',', $phone->phone_language) : [];
                        $contact_phone_descriptions[$key][] = $phone->phone_description;
                    }
                } else {
                    $contact_phone_numbers[$key][] = '';
                    $contact_phone_extensions[$key][] = '';
                    $contact_phone_types[$key][] = '';
                    $contact_phone_languages[$key][] = [];
                    $contact_phone_descriptions[$key][] = '';
                }
            }
            $contact_phone_numbers = json_encode($contact_phone_numbers);
            $contact_phone_extensions = json_encode($contact_phone_extensions);
            $contact_phone_types = json_encode($contact_phone_types);
            $contact_phone_languages = json_encode($contact_phone_languages);
            $contact_phone_descriptions = json_encode($contact_phone_descriptions);

            // location phone section
            $location_phones_data = $service_locations_data;
            $location_phone_numbers = [];
            $location_phone_extensions = [];
            $location_phone_types = [];
            $location_phone_languages = [];
            $location_phone_descriptions = [];
            foreach ($location_phones_data as $key => $phonelocation) {
                if ($phonelocation->phone && count($phonelocation->phone) > 0) {
                    foreach ($phonelocation->phones as $key1 => $phone) {
                        $location_phone_numbers[$key][] = $phone->phone_number;
                        $location_phone_extensions[$key][] = $phone->phone_extension;
                        $location_phone_types[$key][] = $phone->phone_type;
                        $location_phone_languages[$key][] = $phone->phone_language ? explode(',', $phone->phone_language) : [];
                        $location_phone_descriptions[$key][] = $phone->phone_description;
                    }
                } else {
                    $location_phone_numbers[$key][] = '';
                    $location_phone_extensions[$key][] = '';
                    $location_phone_types[$key][] = '';
                    $location_phone_languages[$key][] = [];
                    $location_phone_descriptions[$key][] = '';
                }
            }
            $location_phone_numbers = json_encode($location_phone_numbers);
            $location_phone_extensions = json_encode($location_phone_extensions);
            $location_phone_types = json_encode($location_phone_types);
            $location_phone_languages = json_encode($location_phone_languages);
            $location_phone_descriptions = json_encode($location_phone_descriptions);


            // location schedule section
            $opens_at_location_monday_datas = [];
            $closes_at_location_monday_datas = [];
            $schedule_closed_monday_datas = [];
            $opens_at_location_tuesday_datas = [];
            $closes_at_location_tuesday_datas = [];
            $schedule_closed_tuesday_datas = [];
            $opens_at_location_wednesday_datas = [];
            $closes_at_location_wednesday_datas = [];
            $schedule_closed_wednesday_datas = [];
            $opens_at_location_thursday_datas = [];
            $closes_at_location_thursday_datas = [];
            $schedule_closed_thursday_datas = [];
            $opens_at_location_friday_datas = [];
            $closes_at_location_friday_datas = [];
            $schedule_closed_friday_datas = [];
            $opens_at_location_saturday_datas = [];
            $closes_at_location_saturday_datas = [];
            $schedule_closed_saturday_datas = [];
            $opens_at_location_sunday_datas = [];
            $closes_at_location_sunday_datas = [];
            $schedule_closed_sunday_datas = [];
            $location_holiday_start_dates = [];
            $location_holiday_end_dates = [];
            $location_holiday_open_ats = [];
            $location_holiday_close_ats = [];
            $location_holiday_closeds = [];
            $j = 0;
            foreach ($service_locations_data as $key => $value) {
                if ($value->schedules && !empty($value->schedules) && count($value->schedules) > 0) {
                    foreach ($value->schedules as $key1 => $schedule) {
                        if ($schedule->schedule_holiday == 1) {
                            $location_holiday_start_dates[$j][] = $schedule->dtstart;
                            $location_holiday_end_dates[$j][] = $schedule->until;
                            $location_holiday_open_ats[$j][] = $schedule->opens_at;
                            $location_holiday_close_ats[$j][] = $schedule->closes_at;
                            $location_holiday_closeds[$j][] = $schedule->schedule_closed;
                        } else {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($i = 0; $i < 7; $i++) {
                                if ($schedule->byday == $weekdays[$i]) {
                                    ${'opens_at_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->opens_at;
                                    ${'closes_at_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->closes_at;
                                    ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = $schedule->schedule_closed;
                                }
                                //  else {
                                //     ${'opens_at_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                                //     ${'closes_at_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                                //     ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = '';
                                // }
                            }
                        }
                    }
                    $j = $j + 1;
                }
            }

            $opens_at_location_monday_datas = json_encode($opens_at_location_monday_datas);
            $closes_at_location_monday_datas = json_encode($closes_at_location_monday_datas);
            $schedule_closed_monday_datas = json_encode($schedule_closed_monday_datas);
            $opens_at_location_tuesday_datas = json_encode($opens_at_location_tuesday_datas);
            $closes_at_location_tuesday_datas = json_encode($closes_at_location_tuesday_datas);
            $schedule_closed_tuesday_datas = json_encode($schedule_closed_tuesday_datas);
            $opens_at_location_wednesday_datas = json_encode($opens_at_location_wednesday_datas);
            $closes_at_location_wednesday_datas = json_encode($closes_at_location_wednesday_datas);
            $schedule_closed_wednesday_datas = json_encode($schedule_closed_wednesday_datas);
            $opens_at_location_thursday_datas = json_encode($opens_at_location_thursday_datas);
            $closes_at_location_thursday_datas = json_encode($closes_at_location_thursday_datas);
            $schedule_closed_thursday_datas = json_encode($schedule_closed_thursday_datas);
            $opens_at_location_friday_datas = json_encode($opens_at_location_friday_datas);
            $closes_at_location_friday_datas = json_encode($closes_at_location_friday_datas);
            $schedule_closed_friday_datas = json_encode($schedule_closed_friday_datas);
            $opens_at_location_saturday_datas = json_encode($opens_at_location_saturday_datas);
            $closes_at_location_saturday_datas = json_encode($closes_at_location_saturday_datas);
            $schedule_closed_saturday_datas = json_encode($schedule_closed_saturday_datas);
            $opens_at_location_sunday_datas = json_encode($opens_at_location_sunday_datas);
            $closes_at_location_sunday_datas = json_encode($closes_at_location_sunday_datas);
            $schedule_closed_sunday_datas = json_encode($schedule_closed_sunday_datas);
            $location_holiday_start_dates = json_encode($location_holiday_start_dates);
            $location_holiday_end_dates = json_encode($location_holiday_end_dates);
            $location_holiday_open_ats = json_encode($location_holiday_open_ats);
            $location_holiday_close_ats = json_encode($location_holiday_close_ats);
            $location_holiday_closeds = json_encode($location_holiday_closeds);


            $service_status_list = ['Yes', 'No'];

            $phone_language_data = [];
            if ($service->phone) {
                foreach ($service->phone as $key => $value) {
                    $phone_language_data[$key] = $value->phone_language ? explode(',', $value->phone_language) : [];
                }
            }
            $phone_language_data = json_encode($phone_language_data);

            $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Category')->pluck('taxonomy_name', 'taxonomy_recordid');
            $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Eligibility')->pluck('taxonomy_name', 'taxonomy_recordid');

            $service_category_term_data = $service->taxonomy()->where('taxonomy_vocabulary', 'Service Category')->get();
            // ->whereNotNull('taxonomy_parent_name')
            $service_category_type_data = [];

            foreach ($service_category_term_data as $value) {
                if ($value->taxonomy_parent_name) {
                    $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $value->taxonomy_parent_name)->first();
                    if ($taxonomyParentData) {
                        $taxonomyParentData->selectedTermId = $value->taxonomy_recordid;
                        $taxonomyParentData->selectedTermName = $value->taxonomy_name;
                        $service_category_type_data[] = $taxonomyParentData;
                    }
                } else {
                    $service_category_type_data[] = $value;
                }
            }

            $service_eligibility_term_data = $service->taxonomy()->where('taxonomy_vocabulary', 'Service Eligibility')->get();
            $service_eligibility_type_data = [];
            // dd($service_eligibility_term_data);
            // dd($service->taxonomy);

            foreach ($service_eligibility_term_data as $value) {
                if ($value->taxonomy_parent_name) {
                    $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $value->taxonomy_parent_name)->first();
                    if ($taxonomyParentData) {
                        $taxonomyParentData->selectedTermId = $value->taxonomy_recordid;
                        $taxonomyParentData->selectedTermName = $value->taxonomy_name;
                        $service_eligibility_type_data[] = $taxonomyParentData;
                    }
                } else {
                    $service_eligibility_type_data[] = $value;
                }
            }
            $program = $service->program && count($service->program) > 0 ? $service->program[0] : '';

            return view('frontEnd.services.edit', compact('service', 'map', 'service_address_street', 'service_address_city', 'service_address_state', 'service_address_postal_code', 'service_organization_list', 'service_location_list', 'service_phone1', 'service_phone2', 'service_contacts_list', 'service_taxonomy_list', 'service_details_list', 'location_info_list', 'contact_info_list', 'taxonomy_info_list', 'detail_info_list', 'ServiceSchedule', 'ServiceDetails', 'monday', 'tuesday', 'wednesday', 'friday', 'saturday', 'thursday', 'sunday', 'holiday_schedules', 'all_contacts', 'service_locations_data', 'all_locations', 'phone_languages', 'phone_type', 'location_alternate_name', 'location_transporation', 'location_service', 'location_schedules', 'location_description', 'location_details', 'contact_service', 'contact_department', 'service_info_list', 'address_states_list', 'address_city_list', 'schedule_info_list', 'contact_phone_numbers', 'contact_phone_extensions', 'contact_phone_types', 'contact_phone_languages', 'contact_phone_descriptions', 'location_phone_numbers', 'location_phone_extensions', 'location_phone_types', 'location_phone_languages', 'location_phone_descriptions', 'opens_at_location_monday_datas', 'closes_at_location_monday_datas', 'schedule_closed_monday_datas', 'opens_at_location_tuesday_datas', 'closes_at_location_tuesday_datas', 'schedule_closed_tuesday_datas', 'opens_at_location_wednesday_datas', 'closes_at_location_wednesday_datas', 'schedule_closed_wednesday_datas', 'opens_at_location_thursday_datas', 'closes_at_location_thursday_datas', 'schedule_closed_thursday_datas', 'opens_at_location_friday_datas', 'closes_at_location_friday_datas', 'schedule_closed_friday_datas', 'opens_at_location_saturday_datas', 'closes_at_location_saturday_datas', 'schedule_closed_saturday_datas', 'opens_at_location_sunday_datas', 'closes_at_location_sunday_datas', 'schedule_closed_sunday_datas', 'location_holiday_start_dates', 'location_holiday_end_dates', 'location_holiday_open_ats', 'location_holiday_close_ats', 'location_holiday_closeds', 'service_status_list', 'address_info_list', 'addressIds', 'serviceDetailsData', 'detail_types', 'phone_language_data', 'service_category_term_data', 'service_category_type_data', 'service_category_types', 'service_eligibility_types', 'service_eligibility_term_data', 'service_eligibility_type_data', 'program'));
        } else {
            Session::flash('message', 'This record has been deleted.');
            Session::flash('status', 'warning');
            return redirect('services');
        }
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
        $this->validate($request, [
            'service_name' => 'required',
            'service_organization' => 'required',

        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }
        try {
            $service = Service::where('service_recordid', $id)->first();
            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_description = $request->service_description;
            $service->service_url = $request->service_url;
            $service->service_email = $request->service_email;
            $service->service_status = $request->service_status;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accreditations;
            $service->service_licenses = $request->service_licenses;
            $service->service_organization = $request->service_organization;

            if ($request->service_program) {
                if ($request->program_recordid) {
                    $program = Program::where('program_recordid', $request->program_recordid)->first();
                } else {
                    $program = new Program();
                    $program->program_recordid = Program::max('program_recordid') + 1;
                }
                $program->name = $request->service_program;
                if ($request->program_alternate_name) {
                    $program->alternate_name = $request->program_alternate_name;
                }
                $program->services = $service->service_recordid;
                $recordids = [];
                $recordids[] = $service->service_recordid;
                $program->service()->sync($recordids);
                $program->save();
            }

            // if ($request->service_locations) {
            //     ServiceLocation::where('service_recordid', $id)->delete();
            //     foreach ($request->service_locations as $key => $locationId) {
            //         ServiceLocation::create([
            //             'service_recordid' => $id,
            //             'location_recordid' => $locationId
            //         ]);
            //     }
            //     $service->service_locations = join(',', $request->service_locations);
            // } else {
            //     $service->service_locations = '';
            // }
            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0]) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0]) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0]) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0]) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0]) : [];
                for ($i = 0; $i < count($request->location_name); $i++) {
                    $location_address_recordid_list = [];
                    $location_phone_recordid_list = [];

                    // location phone section
                    $location_phone_numbers = $request->location_phone_numbers && count($request->location_phone_numbers) ? json_decode($request->location_phone_numbers[0], true) : [];
                    $location_phone_extensions = $request->location_phone_extensions && count($request->location_phone_extensions) ? json_decode($request->location_phone_extensions[0], true) : [];
                    $location_phone_types = $request->location_phone_types && count($request->location_phone_types) ? json_decode($request->location_phone_types[0], true) : [];
                    $location_phone_languages = $request->location_phone_languages && count($request->location_phone_languages) ? json_decode($request->location_phone_languages[0], true) : [];
                    $location_phone_descriptions = $request->location_phone_descriptions && count($request->location_phone_descriptions) ? json_decode($request->location_phone_descriptions[0], true) : [];

                    // location schedule section
                    $opens_at_location_monday_datas = $request->opens_at_location_monday_datas  ? json_decode($request->opens_at_location_monday_datas, true) : [];
                    $closes_at_location_monday_datas = $request->closes_at_location_monday_datas  ? json_decode($request->closes_at_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas  ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_at_location_tuesday_datas = $request->opens_at_location_tuesday_datas  ? json_decode($request->opens_at_location_tuesday_datas, true) : [];
                    $closes_at_location_tuesday_datas = $request->closes_at_location_tuesday_datas  ? json_decode($request->closes_at_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas  ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_at_location_wednesday_datas = $request->opens_at_location_wednesday_datas  ? json_decode($request->opens_at_location_wednesday_datas, true) : [];
                    $closes_at_location_wednesday_datas = $request->closes_at_location_wednesday_datas  ? json_decode($request->closes_at_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas  ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_at_location_thursday_datas = $request->opens_at_location_thursday_datas  ? json_decode($request->opens_at_location_thursday_datas, true) : [];
                    $closes_at_location_thursday_datas = $request->closes_at_location_thursday_datas  ? json_decode($request->closes_at_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas  ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_at_location_friday_datas = $request->opens_at_location_friday_datas  ? json_decode($request->opens_at_location_friday_datas, true) : [];
                    $closes_at_location_friday_datas = $request->closes_at_location_friday_datas  ? json_decode($request->closes_at_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas  ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_at_location_saturday_datas = $request->opens_at_location_saturday_datas  ? json_decode($request->opens_at_location_saturday_datas, true) : [];
                    $closes_at_location_saturday_datas = $request->closes_at_location_saturday_datas  ? json_decode($request->closes_at_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas  ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_at_location_sunday_datas = $request->opens_at_location_sunday_datas  ? json_decode($request->opens_at_location_sunday_datas, true) : [];
                    $closes_at_location_sunday_datas = $request->closes_at_location_sunday_datas  ? json_decode($request->closes_at_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas  ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates  ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates  ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats  ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats  ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds  ? json_decode($request->location_holiday_closeds, true) : [];

                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = Location::max('location_recordid') + 1;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = $location_alternate_name[$i];
                        $location->location_transportation = $location_transporation[$i];
                        $location->location_description = $location_description[$i];
                        $location->location_details = $location_details[$i];
                        // if ($location_service) {
                        //     $location->location_services = join(',', $location_service[$i]);
                        // } else {
                        //     $location->location_services = '';
                        // }
                        // $location->services()->sync($location_service[$i]);

                        if ($location_schedules[$i]) {
                            $location->location_schedule = join(',', $location_schedules[$i]);
                        } else {
                            $location->location_schedule = '';
                        }
                        $location->schedules()->sync($location_schedules[$i]);
                        // location address
                        $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                        if ($address_info) {
                            $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                            $address_info->address_1 = $request->location_address[$i];
                            $address_info->address_city = $request->location_city[$i];
                            $address_info->address_state_province = $request->location_state[$i];
                            $address_info->address_postal_code = $request->location_zipcode[$i];
                            $address_info->save();
                            array_push($location_address_recordid_list, $address_info->address_recordid);
                        } else {
                            $new_address = new address;
                            $new_address_recordid = address::max('address_recordid') + 1;
                            $new_address->address_recordid = $new_address_recordid;
                            $new_address->address_1 = $request->location_address[$i];
                            $new_address->address_city = $request->location_city[$i];
                            $new_address->address_state_province = $request->location_state[$i];
                            $new_address->address_postal_code = $request->location_zipcode[$i];
                            $new_address->save();
                            $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $location_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $location_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {

                                $schedules = Schedule::where('schedule_locations', $location_recordid)->where('byday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedules->schedule_recordid;
                                } else {
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location_recordid;
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i];
                                    $schedules->closes_at = ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i];
                                    if (${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                }
                            }
                        }

                        if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                            Schedule::where('schedule_locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                //     $schedules->closes_at = $request->closes_at[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->schedule_locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                if ($location_holiday_closeds[$i][$hs] == 1) {
                                    $schedules->schedule_closed = '1';
                                }
                                $schedules->schedule_holiday = '1';
                                $schedules->save();
                                $schedule_locations[] = $schedule_recordid;
                                // }
                            }
                        }
                        $location->location_schedule = join(',', $schedule_locations);

                        $location->schedules()->sync($schedule_locations);

                        $location->phones()->sync($location_phone_recordid_list);

                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = $location_alternate_name[$i];
                            $location->location_transportation = $location_transporation[$i];
                            $location->location_description = $location_description[$i];
                            $location->location_details = $location_details[$i];
                            // if ($location_service) {
                            //     $location->location_services = join(',', $location_service[$i]);
                            // } else {
                            //     $location->location_services = '';
                            // }
                            // $location->services()->sync($location_service[$i]);

                            if ($location_schedules[$i]) {
                                $location->location_schedule = join(',', $location_schedules[$i]);
                            } else {
                                $location->location_schedule = '';
                            }
                            $location->schedules()->sync($location_schedules[$i]);
                            // location address
                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                                $address_info->address_1 = $request->location_address[$i];
                                $address_info->address_city = $request->location_city[$i];
                                $address_info->address_state_province = $request->location_state[$i];
                                $address_info->address_postal_code = $request->location_zipcode[$i];
                                $address_info->save();
                                array_push($location_address_recordid_list, $address_info->address_recordid);
                            } else {
                                $new_address = new address;
                                $new_address_recordid = address::max('address_recordid') + 1;
                                $new_address->address_recordid = $new_address_recordid;
                                $new_address->address_1 = $request->location_address[$i];
                                $new_address->address_city = $request->location_city[$i];
                                $new_address->address_state_province = $request->location_state[$i];
                                $new_address->address_postal_code = $request->location_zipcode[$i];
                                $new_address->save();
                                $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                        $phone_info->save();
                                        array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                    } else {
                                        $new_phone = new Phone;
                                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                        $new_phone->phone_recordid = $new_phone_recordid;
                                        $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                        $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                        $new_phone->phone_type = $location_phone_types[$i][$p];
                                        $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            // schedule section
                            $schedule_locations = [];
                            if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('schedule_locations', $location->location_recordid)->where('byday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedules->schedule_recordid;
                                    } else {
                                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                        $schedules = new Schedule();
                                        $schedules->schedule_recordid = $schedule_recordid;
                                        $schedules->schedule_locations = $location->location_recordid;
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedule_recordid;
                                    }
                                }
                            }

                            if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                                Schedule::where('schedule_locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                    //     $schedules->closes_at = $request->closes_at[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                    if ($location_holiday_closeds[$i][$hs] == 1) {
                                        $schedules->schedule_closed = '1';
                                    }
                                    $schedules->schedule_holiday = '1';
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                    // }
                                }
                            }
                            $location->location_schedule = join(',', $schedule_locations);

                            $location->schedules()->sync($schedule_locations);
                            $location->phones()->sync($location_phone_recordid_list);
                            $location->address()->sync($location_address_recordid_list);
                            $location->save();
                        }
                        array_push($service_locations, $request->location_recordid[$i]);
                    }
                }
            }
            $service->service_locations = join(',', $service_locations);
            $service->locations()->sync($service_locations);

            // if ($request->service_contacts) {
            //     $service->service_contacts = join(',', $request->service_contacts);
            //     $service->contact()->sync($request->service_contacts);
            // } else {
            //     $service->service_contacts = '';
            // }
            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                // $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
                for ($i = 0; $i < count($request->contact_name); $i++) {
                    $contact_phone_recordid_list = [];
                    $contact_phone_numbers = $request->contact_phone_numbers && count($request->contact_phone_numbers) ? json_decode($request->contact_phone_numbers[0]) : [];
                    $contact_phone_extensions = $request->contact_phone_extensions && count($request->contact_phone_extensions) ? json_decode($request->contact_phone_extensions[0]) : [];
                    $contact_phone_types = $request->contact_phone_types && count($request->contact_phone_types) ? json_decode($request->contact_phone_types[0]) : [];
                    $contact_phone_languages = $request->contact_phone_languages && count($request->contact_phone_languages) ? json_decode($request->contact_phone_languages[0]) : [];

                    $contact_phone_descriptions = $request->contact_phone_descriptions && count($request->contact_phone_descriptions) ? json_decode($request->contact_phone_descriptions[0]) : [];
                    if ($request->contactRadio[$i] == 'new_data') {
                        $contact = new Contact();
                        $contact->contact_recordid = Contact::max('contact_recordid') + 1;
                        $contact->contact_name = $request->contact_name[$i];
                        $contact->contact_title = $request->contact_title[$i];
                        $contact->contact_email = $request->contact_email[$i];
                        $contact->contact_department = $contact_department[$i];
                        // $contact->contact_phones = $request->contact_phone[$i];
                        // this is contact phone section
                        $contact->contact_phones = '';
                        if (isset($contact_phone_numbers[$i])) {
                            for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $contact_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $contact_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                    array_push($contact_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                        array_push($service_contacts, Contact::max('contact_recordid'));
                    } else {
                        $contact = Contact::where('contact_recordid', $request->contact_recordid[$i])->first();
                        if ($contact) {
                            $contact->contact_name = $request->contact_name[$i];
                            $contact->contact_title = $request->contact_title[$i];
                            $contact->contact_email = $request->contact_email[$i];
                            $contact->contact_department = $contact_department[$i];
                            $contact->contact_phones = '';
                            // this is contact phone section
                            if (isset($contact_phone_numbers[$i])) {
                                for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $contact_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                        $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                        $phone_info->save();
                                        array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                                    } else {
                                        $new_phone = new Phone;
                                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                        $new_phone->phone_recordid = $new_phone_recordid;
                                        $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                        $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                        $new_phone->phone_type = $contact_phone_types[$i][$p];
                                        $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                        array_push($contact_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            $contact->phone()->sync($contact_phone_recordid_list);
                            $contact->save();
                        }
                        array_push($service_contacts, $request->contact_recordid[$i]);
                    }
                }
            }
            $service->service_contacts = join(',', $service_contacts);
            $service->contact()->sync($service_contacts);

            // if ($request->service_taxonomy) {
            //     $service->service_taxonomy = join(',', $request->service_taxonomy);
            //     $service->taxonomy()->sync($request->service_taxonomy);
            // } else {
            //     $service->service_taxonomy = '';
            // }
            $service_taxonomies = [];
            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if ($service_category_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_category_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Category';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        $service_taxonomies[] = $service_category_term[$key];
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = $request->service_eligibility_type;
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        $service_taxonomies[] = $service_eligibility_term[$key];
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = array_unique(array_values(array_filter($service_taxonomies)));
            }
            $service->service_taxonomy = join(',', $service_taxonomies);
            $service->taxonomy()->sync($service_taxonomies);

            // if ($request->service_details) {
            //     $service->service_details = join(',', $request->service_details);
            //     $service->details()->sync($request->service_details);
            // } else {
            //     $service->service_details = '';
            // }
            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if ($term_type[$key] == 'new') {
                        $detail = new Detail();
                        $detail_recordid = Detail::max('detail_recordid') + 1;
                        $detail->detail_recordid = $detail_recordid;
                        $detail->detail_type = $value;
                        $detail->detail_value = $detail_term[$key];
                        $detail->save();

                        $detail_ids[] = $detail_recordid;
                    } else {
                        $detail_ids[] = $detail_term[$key];
                    }
                }
            }

            $service->service_details = join(',', $detail_ids);
            $service->details()->sync($detail_ids);

            // $service->service_phones = '';
            // $phone_recordid_list = [];
            // if ($request->service_phones) {
            //     $service_phone_number_list = $request->service_phones;
            //     foreach ($service_phone_number_list as $key => $service_phone_number) {
            //         $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
            //         if ($phone_info) {
            //             $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
            //             array_push($phone_recordid_list, $phone_info->phone_recordid);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $service_phone_number;
            //             $new_phone->save();
            //             $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
            //             array_push($phone_recordid_list, $new_phone_recordid);
            //         }
            //     }
            // }
            // $service->phone()->sync($phone_recordid_list);

            // service phone section
            $service->service_phones = '';
            $phone_recordid_list = [];
            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                // foreach ($service_phone_number_list as $key => $service_phone_number) {

                //     if ($phone_info) {
                //         $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($service_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $service_phone_number_list[$i];
                        $phone_info->phone_extension = $service_phone_extension_list[$i];
                        $phone_info->phone_type = $service_phone_type_list[$i];
                        $phone_info->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->removePhoneDataId) {
                $removePhoneDataId = explode(',', $request->removePhoneDataId);
                ServicePhone::whereIn('phone_recordid', $removePhoneDataId)->where('service_recordid', $service->service_recordid)->delete();
            }
            if ($request->deletePhoneDataId) {
                $deletePhoneDataId = explode(',', $request->deletePhoneDataId);
                ServicePhone::whereIn('phone_recordid', $deletePhoneDataId)->where('service_recordid', $service->service_recordid)->delete();
                Phone::whereIn('phone_recordid', $deletePhoneDataId)->delete();
            }




            $service_address_info = $request->service_address;
            // $address_infos = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->distinct()->get();
            // $address_recordid = [];
            // $full_address_info_list = array();
            // foreach ($address_infos as $key => $value) {
            //     $full_address_info = $value->address_1 . ', ' . $value->address_city . ', ' . $value->address_state_province . ', ' . $value->address_postal_code;
            //     array_push($full_address_info_list, $full_address_info);
            // }
            // $full_address_info_list = array_unique($full_address_info_list);
            // if ($service_address_info) {
            //     if (!in_array($service_address_info, $full_address_info_list)) {
            //         $new_recordid = Address::max('address_recordid') + 1;
            //         $service->service_address = $new_recordid;
            //         $address_recordid[] = $new_recordid;
            //         $address = new Address();
            //         $address->address_recordid = $new_recordid;
            //         $explodeServiceAddress = $service_address_info ? explode(', ', $service_address_info) : [];
            //         $address->address_1 = count($explodeServiceAddress) >= 1 ? $explodeServiceAddress[0] : '';
            //         $address->address_city = count($explodeServiceAddress) >= 2 ? $explodeServiceAddress[1] : '';
            //         $address->address_state_province = count($explodeServiceAddress) >= 3 ? $explodeServiceAddress[2] : '';
            //         $address->address_postal_code = count($explodeServiceAddress) >= 4 ? $explodeServiceAddress[3] : '';
            //         // $address->address_city = explode(', ', $service_address_info)[1];
            //         // $address->address_state_province = explode(', ', $service_address_info)[2];
            //         // $address->address_postal_code = explode(', ', $service_address_info)[3];
            //         $address->save();
            //     } else {
            //         foreach ($address_infos as $key => $value) {
            //             $full_address_info = $value->address_1 . ', ' . $value->address_city . ', ' . $value->address_state_province . ', ' . $value->address_postal_code;
            //             if ($full_address_info == $service_address_info) {
            //                 $service->service_address = $value->address_recordid;
            //                 $address_recordid[] = $value->address_recordid;
            //             }
            //         }
            //     }
            // } else {
            // }
            if (!empty($service_address_info)) {
                $service->service_address = implode(',', $service_address_info);
                $service->address()->sync($service_address_info);
            }
            // if ($request->service_schedules) {
            //     $service->service_schedule = join(',', $request->service_schedules);
            // } else {
            //     $service->service_schedule = '';
            // }
            $schedule_services = [];

            if ($request->byday) {
                for ($i = 0; $i < 7; $i++) {
                    $schedules = Schedule::where('schedule_services', $service->service_recordid)->where('byday', $request->byday[$i])->first();
                    if ($schedules) {
                        $schedules->byday = $request->byday[$i];
                        $schedules->opens_at = $request->opens_at[$i];
                        $schedules->closes_at = $request->closes_at[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $i + 1;
                        } else {
                            $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedules->schedule_recordid;
                    } else {
                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                        $schedules = new Schedule();
                        $schedules->schedule_recordid = $schedule_recordid;
                        $schedules->schedule_services = $service->service_recordid;
                        $schedules->byday = $request->byday[$i];
                        $schedules->opens_at = $request->opens_at[$i];
                        $schedules->closes_at = $request->closes_at[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $i + 1;
                        } else {
                            $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedule_recordid;
                    }
                }
            }
            if ($request->holiday_start_date && $request->holiday_end_date && $request->holiday_open_at && $request->holiday_close_at && (count($request->holiday_start_date) == 0 && $request->holiday_start_date[0] != null)) {
                Schedule::where('schedule_services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    // $schedules =
                    // if($schedules){
                    //     $schedules->dtstart = $request->holiday_start_date[$i];
                    //     $schedules->until = $request->holiday_end_date[$i];
                    //     $schedules->opens_at = $request->holiday_open_at[$i];
                    //     $schedules->closes_at = $request->closes_at[$i];
                    //     if(in_array(($i+1),$request->schedule_closed)){
                    //         $schedules->schedule_closed = $i+1;
                    //     }
                    //     $schedules->save();
                    //     $schedule_services[] = $schedules->schedule_recordid;
                    // }else{
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->schedule_services = $service->service_recordid;
                    $schedules->dtstart = $request->holiday_start_date[$i];
                    $schedules->until = $request->holiday_end_date[$i];
                    $schedules->opens_at = $request->holiday_open_at[$i];
                    $schedules->closes_at = $request->holiday_close_at[$i];
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            $service->schedules()->sync($schedule_services);

            // if ($request->service_details) {
            //     $service->service_details = join(',', $request->service_details);
            // } else {
            //     $service->service_details = '';
            // }
            // $service->details()->sync($request->service_details);

            $service->save();

            $service_organization = $request->service_organization;
            $organization = Organization::where('organization_recordid', '=', $service_organization)->select('organization_recordid', 'updated_at')->first();
            if ($organization) {
                $organization->updated_at = date("Y-m-d H:i:s");
                $organization->save();
            }
            Session::flash('message', 'Service updated successfully!');
            Session::flash('status', 'success');
            return redirect('services/' . $id);
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
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
    public function test_airtable($api_key, $base_url)
    {
        // var_dump($api_key);
        // var_dump($base_url);
        // var_dump("this is test function for auto sync");
        var_dump($api_key);
        var_dump($base_url);
        $response_text = "this is test function for auto sync";
        echo $response_text;

        return $response_text;
    }

    public function airtable($api_key, $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->api_key = $api_key;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            Service::truncate();
            ServiceLocation::truncate();
            ServiceAddress::truncate();
            ServicePhone::truncate();
            ServiceDetail::truncate();
            ServiceOrganization::truncate();
            ServiceContact::truncate();
            ServiceTaxonomy::truncate();
            ServiceSchedule::truncate();

            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'api_key' => $api_key,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('services');
            $size = '';
            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {

                    $service = new Service();
                    $strtointclass = new Stringtoint();
                    $service->service_recordid = $strtointclass->string_to_int($record['id']);

                    $service->service_name = isset($record['fields']['Name']) ? $record['fields']['Name'] : null;

                    if (isset($record['fields']['Organization'])) {
                        $i = 0;
                        foreach ($record['fields']['Organization'] as $value) {
                            $service_organization = new ServiceOrganization();
                            $service_organization->service_recordid = $service->service_recordid;
                            $service_organization->organization_recordid = $strtointclass->string_to_int($value);
                            $service_organization->save();
                            $serviceorganization = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_organization = $service->service_organization . ',' . $serviceorganization;
                            } else {
                                $service->service_organization = $serviceorganization;
                            }

                            $i++;
                        }
                    }

                    $service->service_alternate_name = isset($record['fields']['Alternate Name']) ? $record['fields']['Alternate Name'] : null;
                    $service->service_description = isset($record['fields']['Description']) ? $record['fields']['Description'] : null;

                    if (isset($record['fields']['locations'])) {
                        $i = 0;
                        foreach ($record['fields']['locations'] as $value) {
                            $service_location = new Servicelocation();
                            $service_location->service_recordid = $service->service_recordid;
                            $service_location->location_recordid = $strtointclass->string_to_int($value);
                            $service_location->save();
                            $servicelocation = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_locations = $service->service_locations . ',' . $servicelocation;
                            } else {
                                $service->service_locations = $servicelocation;
                            }

                            $i++;
                        }
                    }

                    $service->service_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                    $service->service_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                    $service->service_status = isset($record['fields']['status']) ? $record['fields']['status'] : null;

                    if (isset($record['fields']['taxonomy'])) {
                        $i = 0;
                        foreach ($record['fields']['taxonomy'] as $value) {
                            $service_taxonomy = new Servicetaxonomy();
                            $service_taxonomy->service_recordid = $service->service_recordid;
                            $service_taxonomy->taxonomy_recordid = $strtointclass->string_to_int($value);
                            $service_taxonomy->save();
                            $servicetaxonomy = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_taxonomy = $service->service_taxonomy . ',' . $servicetaxonomy;
                            } else {
                                $service->service_taxonomy = $servicetaxonomy;
                            }

                            $i++;
                        }
                    }

                    $service->service_application_process = isset($record['fields']['application_process']) ? $record['fields']['application_process'] : null;
                    $service->service_wait_time = isset($record['fields']['wait_time']) ? $record['fields']['wait_time'] : null;
                    $service->service_fees = isset($record['fields']['fees']) ? $record['fields']['fees'] : null;
                    $service->service_accreditations = isset($record['fields']['accreditations']) ? $record['fields']['accreditations'] : null;
                    $service->service_licenses = isset($record['fields']['licenses']) ? $record['fields']['licenses'] : null;

                    if (isset($record['fields']['phones'])) {
                        $i = 0;
                        foreach ($record['fields']['phones'] as $value) {
                            $service_phone = new Servicephone();
                            $service_phone->service_recordid = $service->service_recordid;
                            $service_phone->phone_recordid = $strtointclass->string_to_int($value);
                            $service_phone->save();
                            $servicephone = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_phones = $service->service_phones . ',' . $servicephone;
                            } else {
                                $service->service_phones = $servicephone;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['schedule'])) {
                        $i = 0;
                        foreach ($record['fields']['schedule'] as $value) {
                            $service_schedule = new Serviceschedule();
                            $service_schedule->service_recordid = $service->service_recordid;
                            $service_schedule->schedule_recordid = $strtointclass->string_to_int($value);
                            $service_schedule->save();
                            $serviceschedule = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_schedule = $service->service_schedule . ',' . $serviceschedule;
                            } else {
                                $service->service_schedule = $serviceschedule;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['contacts'])) {
                        $i = 0;
                        foreach ($record['fields']['contacts'] as $value) {
                            $service_contact = new Servicecontact();
                            $service_contact->service_recordid = $service->service_recordid;
                            $service_contact->contact_recordid = $strtointclass->string_to_int($value);
                            $service_contact->save();
                            $servicecontact = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_contacts = $service->service_contacts . ',' . $servicecontact;
                            } else {
                                $service->service_contacts = $servicecontact;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['details'])) {
                        $i = 0;
                        foreach ($record['fields']['details'] as $value) {
                            $service_detail = new Servicedetail();
                            $service_detail->service_recordid = $service->service_recordid;
                            $service_detail->detail_recordid = $strtointclass->string_to_int($value);
                            $service_detail->save();
                            $servicedetail = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_details = $service->service_details . ',' . $servicedetail;
                            } else {
                                $service->service_details = $servicedetail;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['address'])) {
                        $i = 0;
                        foreach ($record['fields']['address'] as $value) {
                            $service_addresses = new Serviceaddress();
                            $service_addresses->service_recordid = $service->service_recordid;
                            $service_addresses->address_recordid = $strtointclass->string_to_int($value);
                            $service_addresses->save();
                            $serviceaddress = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_address = $service->service_address . ',' . $serviceaddress;
                            } else {
                                $service->service_address = $serviceaddress;
                            }

                            $i++;
                        }
                    }

                    $service->service_metadata = isset($record['fields']['metadata']) ? $record['fields']['metadata'] : null;

                    $service->service_airs_taxonomy_x = isset($record['fields']['AIRS Taxonomy-x']) ? implode(",", $record['fields']['AIRS Taxonomy-x']) : null;

                    $service->save();
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtables::where('name', '=', 'Services')->first();
            $airtable->records = Service::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function airtable_v2($api_key, $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->api_key = $api_key;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            Service::truncate();
            ServiceLocation::truncate();
            ServiceAddress::truncate();
            ServicePhone::truncate();
            ServiceDetail::truncate();
            ServiceOrganization::truncate();
            ServiceContact::truncate();
            ServiceTaxonomy::truncate();
            ServiceSchedule::truncate();

            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'api_key' => $api_key,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('services');
            $size = '';
            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {

                    $service = new Service();
                    $strtointclass = new Stringtoint();
                    $service->service_recordid = $strtointclass->string_to_int($record['id']);

                    $service->service_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                    if (isset($record['fields']['organizations'])) {
                        $i = 0;
                        foreach ($record['fields']['organizations'] as $value) {
                            $service_organization = new ServiceOrganization();
                            $service_organization->service_recordid = $service->service_recordid;
                            $service_organization->organization_recordid = $strtointclass->string_to_int($value);
                            $service_organization->save();
                            $serviceorganization = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_organization = $service->service_organization . ',' . $serviceorganization;
                            } else {
                                $service->service_organization = $serviceorganization;
                            }

                            $i++;
                        }
                    }

                    $service->service_alternate_name = isset($record['fields']['alternative_name']) ? $record['fields']['alternative_name'] : null;
                    $service->service_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                    if (isset($record['fields']['locations'])) {
                        $i = 0;
                        foreach ($record['fields']['locations'] as $value) {
                            $service_location = new Servicelocation();
                            $service_location->service_recordid = $service->service_recordid;
                            $service_location->location_recordid = $strtointclass->string_to_int($value);
                            $service_location->save();
                            $servicelocation = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_locations = $service->service_locations . ',' . $servicelocation;
                            } else {
                                $service->service_locations = $servicelocation;
                            }

                            $i++;
                        }
                    }

                    $service->service_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                    $service->service_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                    $service->service_status = isset($record['fields']['status']) ? $record['fields']['status'] : null;

                    if (isset($record['fields']['taxonomy'])) {
                        $i = 0;
                        foreach ($record['fields']['taxonomy'] as $value) {
                            $service_taxonomy = new ServiceTaxonomy();
                            $service_taxonomy->service_recordid = $service->service_recordid;
                            $service_taxonomy->taxonomy_recordid = $strtointclass->string_to_int($value);
                            $service_taxonomy->save();
                            $servicetaxonomy = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_taxonomy = $service->service_taxonomy . ',' . $servicetaxonomy;
                            } else {
                                $service->service_taxonomy = $servicetaxonomy;
                            }

                            $i++;
                        }
                    }

                    $service->service_application_process = isset($record['fields']['application_process']) ? $record['fields']['application_process'] : null;
                    $service->service_wait_time = isset($record['fields']['wait_time']) ? $record['fields']['wait_time'] : null;
                    $service->service_fees = isset($record['fields']['fees']) ? $record['fields']['fees'] : null;
                    $service->service_accreditations = isset($record['fields']['accreditations']) ? $record['fields']['accreditations'] : null;
                    $service->service_licenses = isset($record['fields']['licenses']) ? $record['fields']['licenses'] : null;

                    if (isset($record['fields']['phones'])) {
                        $i = 0;
                        foreach ($record['fields']['phones'] as $value) {
                            $service_phone = new Servicephone();
                            $service_phone->service_recordid = $service->service_recordid;
                            $service_phone->phone_recordid = $strtointclass->string_to_int($value);
                            $service_phone->save();
                            $servicephone = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_phones = $service->service_phones . ',' . $servicephone;
                            } else {
                                $service->service_phones = $servicephone;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['schedule'])) {
                        $i = 0;
                        foreach ($record['fields']['schedule'] as $value) {
                            $service_schedule = new Serviceschedule();
                            $service_schedule->service_recordid = $service->service_recordid;
                            $service_schedule->schedule_recordid = $strtointclass->string_to_int($value);
                            $service_schedule->save();
                            $serviceschedule = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_schedule = $service->service_schedule . ',' . $serviceschedule;
                            } else {
                                $service->service_schedule = $serviceschedule;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['contacts'])) {
                        $i = 0;
                        foreach ($record['fields']['contacts'] as $value) {
                            $service_contact = new Servicecontact();
                            $service_contact->service_recordid = $service->service_recordid;
                            $service_contact->contact_recordid = $strtointclass->string_to_int($value);
                            $service_contact->save();
                            $servicecontact = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_contacts = $service->service_contacts . ',' . $servicecontact;
                            } else {
                                $service->service_contacts = $servicecontact;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['x-details'])) {
                        $i = 0;
                        foreach ($record['fields']['x-details'] as $value) {
                            $service_detail = new Servicedetail();
                            $service_detail->service_recordid = $service->service_recordid;
                            $service_detail->detail_recordid = $strtointclass->string_to_int($value);
                            $service_detail->save();
                            $servicedetail = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_details = $service->service_details . ',' . $servicedetail;
                            } else {
                                $service->service_details = $servicedetail;
                            }

                            $i++;
                        }
                    }

                    if (isset($record['fields']['address'])) {
                        $i = 0;
                        foreach ($record['fields']['address'] as $value) {
                            $service_addresses = new Serviceaddress();
                            $service_addresses->service_recordid = $service->service_recordid;
                            $service_addresses->address_recordid = $strtointclass->string_to_int($value);
                            $service_addresses->save();
                            $serviceaddress = $strtointclass->string_to_int($value);

                            if ($i != 0) {
                                $service->service_address = $service->service_address . ',' . $serviceaddress;
                            } else {
                                $service->service_address = $serviceaddress;
                            }

                            $i++;
                        }
                    }

                    $service->service_metadata = isset($record['fields']['metadata']) ? $record['fields']['metadata'] : null;

                    $service->service_airs_taxonomy_x = isset($record['fields']['AIRS Taxonomy-x']) ? implode(",", $record['fields']['AIRS Taxonomy-x']) : null;

                    $service->save();
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Services')->first();
            $airtable->records = Service::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function csv(Request $request)
    {
        try {
            // $file = $request->file('csv_file');

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $file->move(public_path('/csv/'), $filename);

            // if ($filename != 'services.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            Service::truncate();
            ServiceOrganization::truncate();

            Excel::import(new Services, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services')->first();
            $csv_source->records = Service::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            $response = array(
                'status' => 'success',
                'result' => 'Services imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function export_services()
    {
        try {
            return Excel::download(new ServiceExport, 'export.csv');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function csv_services_location(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'services_at_location.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            // if (count($data) > 0) {
            //     $csv_header_fields = [];
            //     foreach ($data[0] as $key => $value) {
            //         $csv_header_fields[] = $key;
            //     }
            //     $csv_data = $data;
            // }

            ServiceLocation::truncate();

            Excel::import(new ServiceLocationImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
            $csv_source->records = ServiceLocation::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Service location imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'false',
                'result' => $th->getMessage(),
            );
            return $response;
        }
    }
    public function create_in_organization($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        $facility_info_list = Location::select('location_recordid', 'location_name')->orderBy('location_recordid')->distinct()->get();

        $service_status_list = ['Yes', 'No'];

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();

        $phone_languages = Language::pluck('language', 'language_recordid');

        $phone_type = PhoneType::pluck('type', 'id');
        $all_contacts = Contact::orderBy('contact_recordid')->with('phone')->distinct()->get();
        $all_locations = Location::orderBy('location_recordid')->with('phones', 'address', 'schedules')->distinct()->get();

        $address_cities = Address::select("address_city")->distinct()->get();
        $address_states = Address::select("address_state_province")->distinct()->get();

        $address_states_list = [];
        foreach ($address_states as $key => $value) {
            $state = explode(", ", trim($value->address_state_province));
            $address_states_list = array_merge($address_states_list, $state);
        }
        $address_states_list = array_unique($address_states_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", ", trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);
        $detail_types = DetailType::pluck('type', 'type');

        $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Category')->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Eligibility')->pluck('taxonomy_name', 'taxonomy_recordid');

        return view('frontEnd.services.service-create-in-organization', compact('map', 'organization', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list', 'phone_languages', 'phone_type', 'all_contacts', 'all_locations', 'address_states_list', 'address_city_list', 'detail_types', 'service_eligibility_types', 'service_category_types'));
    }

    public function create_in_facility($id)
    {
        $map = Map::find(1);
        $facility = Location::where('location_recordid', '=', $id)->first();

        $service_status_list = ['Yes', 'No'];

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $phone_languages = Language::pluck('language', 'language_recordid');

        $phone_type = PhoneType::pluck('type', 'id');
        $all_contacts = Contact::orderBy('contact_recordid')->with('phone')->distinct()->get();
        $all_locations = Location::orderBy('location_recordid')->with('phones', 'address', 'schedules')->distinct()->get();

        $address_cities = Address::select("address_city")->distinct()->get();
        $address_states = Address::select("address_state_province")->distinct()->get();

        $address_states_list = [];
        foreach ($address_states as $key => $value) {
            $state = explode(", ", trim($value->address_state_province));
            $address_states_list = array_merge($address_states_list, $state);
        }
        $address_states_list = array_unique($address_states_list);

        $address_city_list = [];
        foreach ($address_cities as $key => $value) {
            $cities = explode(", ", trim($value->address_city));
            $address_city_list = array_merge($address_city_list, $cities);
        }
        $address_city_list = array_unique($address_city_list);
        $detail_types = DetailType::pluck('type', 'type');

        $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Category')->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy_vocabulary', 'Service Eligibility')->pluck('taxonomy_name', 'taxonomy_recordid');

        return view('frontEnd.services.service-create-in-facility', compact('map', 'facility', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'phone_languages', 'phone_type', 'all_contacts', 'all_locations', 'address_states_list', 'address_city_list', 'detail_types', 'service_category_types', 'service_eligibility_types'));
    }
    public function add_new_service_in_organization(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }
        try {
            $service = new Service;

            $service_recordids = Service::select("service_recordid")->distinct()->get();
            $service_recordid_list = array();
            foreach ($service_recordids as $key => $value) {
                $service_recordid = $value->service_recordid;
                array_push($service_recordid_list, $service_recordid);
            }
            $service_recordid_list = array_unique($service_recordid_list);

            $new_recordid = Service::max('service_recordid') + 1;
            if (in_array($new_recordid, $service_recordid_list)) {
                $new_recordid = Service::max('service_recordid') + 1;
            }
            $service->service_recordid = $new_recordid;

            $organization_name = $request->service_organization;
            $service_organization = Organization::where('organization_name', '=', $organization_name)->first();
            $service_organization_id = $service_organization["organization_recordid"];
            $service->service_organization = $service_organization_id;

            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_url = $request->service_url;
            $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            $service->service_status = '';
            if ($request->service_status = 'Yes') {
                $service->service_status = 'Verified';
            }
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;

            // if ($request->service_locations) {
            //     $service->service_locations = join(',', $request->service_locations);
            // } else {
            //     $service->service_locations = '';
            // }
            // $service->locations()->sync($request->service_locations);
            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                for ($i = 0; $i < count($request->location_name); $i++) {
                    $location_address_recordid_list = [];
                    $location_phone_recordid_list = [];
                    // location phone section
                    $location_phone_numbers = $request->location_phone_numbers && count($request->location_phone_numbers) ? json_decode($request->location_phone_numbers[0], true) : [];
                    $location_phone_extensions = $request->location_phone_extensions && count($request->location_phone_extensions) ? json_decode($request->location_phone_extensions[0], true) : [];
                    $location_phone_types = $request->location_phone_types && count($request->location_phone_types) ? json_decode($request->location_phone_types[0], true) : [];
                    $location_phone_languages = $request->location_phone_languages && count($request->location_phone_languages) ? json_decode($request->location_phone_languages[0], true) : [];
                    $location_phone_descriptions = $request->location_phone_descriptions && count($request->location_phone_descriptions) ? json_decode($request->location_phone_descriptions[0], true) : [];

                    // location schedule section
                    $opens_at_location_monday_datas = $request->opens_at_location_monday_datas  ? json_decode($request->opens_at_location_monday_datas, true) : [];
                    $closes_at_location_monday_datas = $request->closes_at_location_monday_datas  ? json_decode($request->closes_at_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas  ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_at_location_tuesday_datas = $request->opens_at_location_tuesday_datas  ? json_decode($request->opens_at_location_tuesday_datas, true) : [];
                    $closes_at_location_tuesday_datas = $request->closes_at_location_tuesday_datas  ? json_decode($request->closes_at_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas  ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_at_location_wednesday_datas = $request->opens_at_location_wednesday_datas  ? json_decode($request->opens_at_location_wednesday_datas, true) : [];
                    $closes_at_location_wednesday_datas = $request->closes_at_location_wednesday_datas  ? json_decode($request->closes_at_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas  ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_at_location_thursday_datas = $request->opens_at_location_thursday_datas  ? json_decode($request->opens_at_location_thursday_datas, true) : [];
                    $closes_at_location_thursday_datas = $request->closes_at_location_thursday_datas  ? json_decode($request->closes_at_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas  ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_at_location_friday_datas = $request->opens_at_location_friday_datas  ? json_decode($request->opens_at_location_friday_datas, true) : [];
                    $closes_at_location_friday_datas = $request->closes_at_location_friday_datas  ? json_decode($request->closes_at_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas  ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_at_location_saturday_datas = $request->opens_at_location_saturday_datas  ? json_decode($request->opens_at_location_saturday_datas, true) : [];
                    $closes_at_location_saturday_datas = $request->closes_at_location_saturday_datas  ? json_decode($request->closes_at_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas  ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_at_location_sunday_datas = $request->opens_at_location_sunday_datas  ? json_decode($request->opens_at_location_sunday_datas, true) : [];
                    $closes_at_location_sunday_datas = $request->closes_at_location_sunday_datas  ? json_decode($request->closes_at_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas  ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates  ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates  ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats  ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats  ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds  ? json_decode($request->location_holiday_closeds, true) : [];



                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = Location::max('location_recordid') + 1;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = $location_alternate_name[$i];
                        $location->location_transportation = $location_transporation[$i];
                        $location->location_description = $location_description[$i];
                        $location->location_details = $location_details[$i];
                        // if ($location_service) {
                        //     $location->location_services = join(',', $location_service[$i]);
                        // } else {
                        //     $location->location_services = '';
                        // }
                        // $location->services()->sync($location_service[$i]);

                        // if ($location_schedules[$i]) {
                        //     $location->location_schedule = join(',', $location_schedules[$i]);
                        // } else {
                        //     $location->location_schedule = '';
                        // }
                        // $location->schedules()->sync($location_schedules[$i]);
                        // location address
                        $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                        if ($address_info) {
                            $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                            $address_info->address_1 = $request->location_address[$i];
                            $address_info->address_city = $request->location_city[$i];
                            $address_info->address_state_province = $request->location_state[$i];
                            $address_info->address_postal_code = $request->location_zipcode[$i];
                            $address_info->save();
                            array_push($location_address_recordid_list, $address_info->address_recordid);
                        } else {
                            $new_address = new address;
                            $new_address_recordid = address::max('address_recordid') + 1;
                            $new_address->address_recordid = $new_address_recordid;
                            $new_address->address_1 = $request->location_address[$i];
                            $new_address->address_city = $request->location_city[$i];
                            $new_address->address_state_province = $request->location_state[$i];
                            $new_address->address_postal_code = $request->location_zipcode[$i];
                            $new_address->save();
                            $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $location_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $location_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {
                                $schedules = Schedule::where('schedule_locations', $location_recordid)->where('byday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedules->schedule_recordid;
                                } else {
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location_recordid;
                                    $schedules->byday = $weekdays[$s];
                                    $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                        $schedules->schedule_closed = $s + 1;
                                    } else {
                                        $schedules->schedule_closed = null;
                                    }
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                }
                            }
                        }

                        if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                            Schedule::where('schedule_locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                //     $schedules->closes_at = $request->closes_at[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->schedule_locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                if ($location_holiday_closeds[$i][$hs] == 1) {
                                    $schedules->schedule_closed = '1';
                                }
                                $schedules->schedule_holiday = '1';
                                $schedules->save();
                                $schedule_locations[] = $schedule_recordid;
                                // }
                            }
                        }
                        $location->location_schedule = join(',', $schedule_locations);

                        $location->schedules()->sync($schedule_locations);
                        $location->phones()->sync($location_phone_recordid_list);

                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = $location_alternate_name[$i];
                            $location->location_transportation = $location_transporation[$i];
                            $location->location_description = $location_description[$i];
                            $location->location_details = $location_details[$i];
                            // if ($location_service) {
                            //     $location->location_services = join(',', $location_service[$i]);
                            // } else {
                            //     $location->location_services = '';
                            // }
                            // $location->services()->sync($location_service[$i]);

                            // if ($location_schedules[$i]) {
                            //     $location->location_schedule = join(',', $location_schedules[$i]);
                            // } else {
                            //     $location->location_schedule = '';
                            // }
                            // $location->schedules()->sync($location_schedules[$i]);
                            // location address
                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $location->location_address . $address_info->address_recordid . ',';
                                $address_info->address_1 = $request->location_address[$i];
                                $address_info->address_city = $request->location_city[$i];
                                $address_info->address_state_province = $request->location_state[$i];
                                $address_info->address_postal_code = $request->location_zipcode[$i];
                                $address_info->save();
                                array_push($location_address_recordid_list, $address_info->address_recordid);
                            } else {
                                $new_address = new address;
                                $new_address_recordid = address::max('address_recordid') + 1;
                                $new_address->address_recordid = $new_address_recordid;
                                $new_address->address_1 = $request->location_address[$i];
                                $new_address->address_city = $request->location_city[$i];
                                $new_address->address_state_province = $request->location_state[$i];
                                $new_address->address_postal_code = $request->location_zipcode[$i];
                                $new_address->save();
                                $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $phone_info->phone_description = $location_phone_descriptions[$i][$p];
                                        $phone_info->save();
                                        array_push($location_phone_recordid_list, $phone_info->phone_recordid);
                                    } else {
                                        $new_phone = new Phone;
                                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                        $new_phone->phone_recordid = $new_phone_recordid;
                                        $new_phone->phone_number = $location_phone_numbers[$i][$p];
                                        $new_phone->phone_extension = $location_phone_extensions[$i][$p];
                                        $new_phone->phone_type = $location_phone_types[$i][$p];
                                        $new_phone->phone_language = isset($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }

                            // schedule section
                            $schedule_locations = [];

                            if ($opens_at_location_monday_datas && isset($opens_at_location_monday_datas[$i]) &&  $opens_at_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('schedule_locations', $location->location_recordid)->where('byday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedules->schedule_recordid;
                                    } else {
                                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                        $schedules = new Schedule();
                                        $schedules->schedule_recordid = $schedule_recordid;
                                        $schedules->schedule_locations = $location->location_recordid;
                                        $schedules->byday = $weekdays[$s];
                                        $schedules->opens_at = isset(${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes_at = isset(${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_at_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) &&  ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                                            $schedules->schedule_closed = $s + 1;
                                        } else {
                                            $schedules->schedule_closed = null;
                                        }
                                        $schedules->save();
                                        $schedule_locations[] = $schedule_recordid;
                                    }
                                }
                            }

                            if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                                Schedule::where('schedule_locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens_at = $request->holiday_open_at[$hs];
                                    //     $schedules->closes_at = $request->closes_at[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->schedule_locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens_at = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes_at = $location_holiday_close_ats[$i][$hs];
                                    if ($location_holiday_closeds[$i][$hs] == 1) {
                                        $schedules->schedule_closed = '1';
                                    }
                                    $schedules->schedule_holiday = '1';
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                    // }
                                }
                            }
                            $location->location_schedule = join(',', $schedule_locations);

                            $location->schedules()->sync($schedule_locations);
                            $location->phones()->sync($location_phone_recordid_list);
                            $location->address()->sync($location_address_recordid_list);
                            $location->save();
                        }
                        array_push($service_locations, $request->location_recordid[$i]);
                    }
                }
            }
            $service->service_locations = join(',', $service_locations);
            $service->locations()->sync($service_locations);

            // if ($request->service_taxonomies) {
            //     $service->service_taxonomy = join(',', $request->service_taxonomies);
            // } else {
            //     $service->service_taxonomy = '';
            // }
            // $service->taxonomy()->sync($request->service_taxonomies);
            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if ($service_category_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_category_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Category';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        $service_taxonomies[] = $service_category_term[$key];
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = $request->service_eligibility_type;
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        $service_taxonomies[] = $service_eligibility_term[$key];
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = array_unique(array_values(array_filter($service_taxonomies)));
            }
            $service->service_taxonomy = join(',', $service_taxonomies);
            $service->taxonomy()->sync($service_taxonomies);

            // $phone_recordids = Phone::select("phone_recordid")->distinct()->get();
            // $phone_recordid_list = array();
            // foreach ($phone_recordids as $key => $value) {
            //     $phone_recordid = $value->phone_recordid;
            //     array_push($phone_recordid_list, $phone_recordid);
            // }
            // $phone_recordid_list = array_unique($phone_recordid_list);

            // $service_phones = $request->service_phones;
            // $cell_phone = Phone::where('phone_number', '=', $service_phones)->first();
            // if ($cell_phone != null) {
            //     $cell_phone_id = $cell_phone["phone_recordid"];
            //     $service->service_phones = $cell_phone_id;
            // } else {
            //     $phone = new Phone;
            //     $new_recordid = Phone::max('phone_recordid') + 1;
            //     if (in_array($new_recordid, $phone_recordid_list)) {
            //         $new_recordid = Phone::max('phone_recordid') + 1;
            //     }
            //     $phone->phone_recordid = $new_recordid;
            //     $phone->phone_number = $cell_phone;
            //     $phone->phone_type = "voice";
            //     $service->service_phones = $phone->phone_recordid;
            //     $phone->save();
            // }

            // $service_phone_info_list = array();
            // array_push($service_phone_info_list, $service->service_phones);
            // $service_phone_info_list = array_unique($service_phone_info_list);
            // $service->phone()->sync($service_phone_info_list);

            // $service->service_phones = '';
            // $phone_recordid_list = [];
            // if ($request->service_phones) {
            //     $service_phone_number_list = $request->service_phones;
            //     foreach ($service_phone_number_list as $key => $service_phone_number) {
            //         $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
            //         if ($phone_info) {
            //             $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
            //             array_push($phone_recordid_list, $phone_info->phone_recordid);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $service_phone_number;
            //             $new_phone->save();
            //             $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
            //             array_push($phone_recordid_list, $new_phone_recordid);
            //         }
            //     }
            // }
            // $service->phone()->sync($phone_recordid_list);
            $service->service_phones = '';
            $phone_recordid_list = [];

            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                // foreach ($service_phone_number_list as $key => $service_phone_number) {

                //     if ($phone_info) {
                //         $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($service_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $service_phone_number_list[$i];
                        $phone_info->phone_extension = $service_phone_extension_list[$i];
                        $phone_info->phone_type = $service_phone_type_list[$i];
                        $phone_info->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            // if ($request->service_contacts) {
            //     $service->service_contacts = join(',', $request->service_contacts);
            // } else {
            //     $service->service_contacts = '';
            // }
            // $service->contact()->sync($request->service_contacts);

            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                // $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
                for ($i = 0; $i < count($request->contact_name); $i++) {
                    $contact_phone_recordid_list = [];
                    $contact_phone_numbers = $request->contact_phone_numbers && count($request->contact_phone_numbers) ? json_decode($request->contact_phone_numbers[0]) : [];
                    $contact_phone_extensions = $request->contact_phone_extensions && count($request->contact_phone_extensions) ? json_decode($request->contact_phone_extensions[0]) : [];
                    $contact_phone_types = $request->contact_phone_types && count($request->contact_phone_types) ? json_decode($request->contact_phone_types[0]) : [];
                    $contact_phone_languages = $request->contact_phone_languages && count($request->contact_phone_languages) ? json_decode($request->contact_phone_languages[0]) : [];
                    $contact_phone_descriptions = $request->contact_phone_descriptions && count($request->contact_phone_descriptions) ? json_decode($request->contact_phone_descriptions[0]) : [];

                    if ($request->contactRadio[$i] == 'new_data') {
                        $contact = new Contact();
                        $contact->contact_recordid = Contact::max('contact_recordid') + 1;
                        $contact->contact_name = $request->contact_name[$i];
                        $contact->contact_title = $request->contact_title[$i];
                        $contact->contact_email = $request->contact_email[$i];
                        $contact->contact_department = $contact_department[$i];
                        // $contact->contact_phones = $request->contact_phone[$i];
                        // this is contact phone section
                        $contact->contact_phones = '';
                        for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                            $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                            if ($phone_info) {
                                $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                $phone_info->phone_type = $contact_phone_types[$i][$p];
                                $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                $phone_info->save();
                                array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                            } else {
                                $new_phone = new Phone;
                                $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                $new_phone->phone_recordid = $new_phone_recordid;
                                $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                $new_phone->phone_type = $contact_phone_types[$i][$p];
                                $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                $new_phone->save();
                                $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                array_push($contact_phone_recordid_list, $new_phone_recordid);
                            }
                        }
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                        array_push($service_contacts, Contact::max('contact_recordid'));
                    } else {
                        $contact = Contact::where('contact_recordid', $request->contact_recordid[$i])->first();
                        if ($contact) {
                            $contact->contact_name = $request->contact_name[$i];
                            $contact->contact_title = $request->contact_title[$i];
                            $contact->contact_email = $request->contact_email[$i];
                            $contact->contact_department = $contact_department[$i];
                            // $phone_info = Phone::where('phone_number', '=', $request->contact_phone[$i])->first();
                            $contact->contact_phones = '';
                            for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $contact_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $contact_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                    array_push($contact_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                            $contact->phone()->sync($contact_phone_recordid_list);
                            $contact->save();
                        }
                        array_push($service_contacts, $request->contact_recordid[$i]);
                    }
                }
            }
            $service->service_contacts = join(',', $service_contacts);
            $service->contact()->sync($service_contacts);

            // if ($request->service_details) {
            //     $service->service_details = join(',', $request->service_details);
            // } else {
            //     $service->service_details = '';
            // }
            // $service->details()->sync($request->service_details);
            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if ($term_type[$key] == 'new') {
                        $detail = new Detail();
                        $detail_recordid = Detail::max('detail_recordid') + 1;
                        $detail->detail_recordid = $detail_recordid;
                        $detail->detail_type = $value;
                        $detail->detail_value = $detail_term[$key];
                        $detail->save();

                        $detail_ids[] = $detail_recordid;
                    } else {
                        $detail_ids[] = $detail_term[$key];
                    }
                }
            }
            $service->service_details = join(',', $detail_ids);
            $service->details()->sync($detail_ids);

            if ($request->service_address) {
                $service->service_address = join(',', $request->service_address);
            } else {
                $service->service_address = '';
            }
            $service->address()->sync($request->service_address);

            $service->save();

            Session::flash('message', 'Services created successfully!');
            Session::flash('status', 'success');

            return redirect('organizations/' . $service_organization_id);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
        }
    }

    public function add_new_service_in_facility(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }
        try {
            $service = new Service;

            $service_recordids = Service::select("service_recordid")->distinct()->get();
            $service_recordid_list = array();
            foreach ($service_recordids as $key => $value) {
                $service_recordid = $value->service_recordid;
                array_push($service_recordid_list, $service_recordid);
            }
            $service_recordid_list = array_unique($service_recordid_list);

            $new_recordid = Service::max('service_recordid') + 1;
            if (in_array($new_recordid, $service_recordid_list)) {
                $new_recordid = Service::max('service_recordid') + 1;
            }
            $service->service_recordid = $new_recordid;

            $service->service_locations = '';
            $service_location_recordid = '';
            if ($request->service_locations) {
                $service_location_recordid = $request->service_locations;
                $service->service_locations = $service_location_recordid;
            }
            $service_locations_info_list = [];
            array_push($service_locations_info_list, $request->service_locations);
            $service->locations()->sync($service_locations_info_list);

            $service_organization_id = $request->service_organization;
            $service->service_organization = $service_organization_id;

            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_url = $request->service_url;
            $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            $service->service_status = '';
            if ($request->service_status = 'Yes') {
                $service->service_status = 'Verified';
            }
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;

            // if ($request->service_taxonomies) {
            //     $service->service_taxonomy = join(',', $request->service_taxonomies);
            // } else {
            //     $service->service_taxonomy = '';
            // }
            // $service->taxonomy()->sync($request->service_taxonomies);

            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if ($service_category_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_category_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Category';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        $service_taxonomies[] = $service_category_term[$key];
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = $request->service_eligibility_type;
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key];
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        $service_taxonomies[] = $service_eligibility_term[$key];
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = array_unique(array_values(array_filter($service_taxonomies)));
            }
            $service->service_taxonomy = join(',', $service_taxonomies);
            $service->taxonomy()->sync($service_taxonomies);

            // $service->service_phones = '';
            // $phone_recordid_list = [];
            // if ($request->service_phones) {
            //     $service_phone_number_list = $request->service_phones;
            //     foreach ($service_phone_number_list as $key => $service_phone_number) {
            //         $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
            //         if ($phone_info) {
            //             $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
            //             array_push($phone_recordid_list, $phone_info->phone_recordid);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $service_phone_number;
            //             $new_phone->save();
            //             $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
            //             array_push($phone_recordid_list, $new_phone_recordid);
            //         }
            //     }
            // }
            // $service->phone()->sync($phone_recordid_list);

            $service->service_phones = '';
            $phone_recordid_list = [];

            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                // foreach ($service_phone_number_list as $key => $service_phone_number) {

                //     if ($phone_info) {
                //         $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($service_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $service_phone_number_list[$i];
                        $phone_info->phone_extension = $service_phone_extension_list[$i];
                        $phone_info->phone_type = $service_phone_type_list[$i];
                        $phone_info->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            // if ($request->service_contacts) {
            //     $service->service_contacts = join(',', $request->service_contacts);
            // } else {
            //     $service->service_contacts = '';
            // }
            // $service->contact()->sync($request->service_contacts);
            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                // $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
                for ($i = 0; $i < count($request->contact_name); $i++) {
                    $contact_phone_recordid_list = [];
                    $contact_phone_numbers = $request->contact_phone_numbers && count($request->contact_phone_numbers) ? json_decode($request->contact_phone_numbers[0]) : [];
                    $contact_phone_extensions = $request->contact_phone_extensions && count($request->contact_phone_extensions) ? json_decode($request->contact_phone_extensions[0]) : [];
                    $contact_phone_types = $request->contact_phone_types && count($request->contact_phone_types) ? json_decode($request->contact_phone_types[0]) : [];
                    $contact_phone_languages = $request->contact_phone_languages && count($request->contact_phone_languages) ? json_decode($request->contact_phone_languages[0]) : [];
                    $contact_phone_descriptions = $request->contact_phone_descriptions && count($request->contact_phone_descriptions) ? json_decode($request->contact_phone_descriptions[0]) : [];

                    if ($request->contactRadio[$i] == 'new_data') {
                        $contact = new Contact();
                        $contact->contact_recordid = Contact::max('contact_recordid') + 1;
                        $contact->contact_name = $request->contact_name[$i];
                        $contact->contact_title = $request->contact_title[$i];
                        $contact->contact_email = $request->contact_email[$i];
                        $contact->contact_department = $contact_department[$i];
                        // $contact->contact_phones = $request->contact_phone[$i];
                        // this is contact phone section
                        $contact->contact_phones = '';
                        for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                            $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                            if ($phone_info) {
                                $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                $phone_info->phone_type = $contact_phone_types[$i][$p];
                                $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                $phone_info->save();
                                array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                            } else {
                                $new_phone = new Phone;
                                $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                $new_phone->phone_recordid = $new_phone_recordid;
                                $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                $new_phone->phone_type = $contact_phone_types[$i][$p];
                                $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                $new_phone->save();
                                $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                array_push($contact_phone_recordid_list, $new_phone_recordid);
                            }
                        }
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                        array_push($service_contacts, Contact::max('contact_recordid'));
                    } else {
                        $contact = Contact::where('contact_recordid', $request->contact_recordid[$i])->first();
                        if ($contact) {
                            $contact->contact_name = $request->contact_name[$i];
                            $contact->contact_title = $request->contact_title[$i];
                            $contact->contact_email = $request->contact_email[$i];
                            $contact->contact_department = $contact_department[$i];
                            // $phone_info = Phone::where('phone_number', '=', $request->contact_phone[$i])->first();
                            $contact->contact_phones = '';
                            for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $contact_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $phone_info->phone_description = $contact_phone_descriptions[$i][$p];
                                    $phone_info->save();
                                    array_push($contact_phone_recordid_list, $phone_info->phone_recordid);
                                } else {
                                    $new_phone = new Phone;
                                    $new_phone_recordid = Phone::max('phone_recordid') + 1;
                                    $new_phone->phone_recordid = $new_phone_recordid;
                                    $new_phone->phone_number = $contact_phone_numbers[$i][$p];
                                    $new_phone->phone_extension = $contact_phone_extensions[$i][$p];
                                    $new_phone->phone_type = $contact_phone_types[$i][$p];
                                    $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                    array_push($contact_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                            $contact->phone()->sync($contact_phone_recordid_list);
                            $contact->save();
                        }
                        array_push($service_contacts, $request->contact_recordid[$i]);
                    }
                }
            }
            $service->service_contacts = join(',', $service_contacts);
            $service->contact()->sync($service_contacts);

            // if ($request->service_details) {
            //     $service->service_details = join(',', $request->service_details);
            // } else {
            //     $service->service_details = '';
            // }
            // $service->details()->sync($request->service_details);
            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if ($term_type[$key] == 'new') {
                        $detail = new Detail();
                        $detail_recordid = Detail::max('detail_recordid') + 1;
                        $detail->detail_recordid = $detail_recordid;
                        $detail->detail_type = $value;
                        $detail->detail_value = $detail_term[$key];
                        $detail->save();

                        $detail_ids[] = $detail_recordid;
                    } else {
                        $detail_ids[] = $detail_term[$key];
                    }
                }
            }
            $service->service_details = join(',', $detail_ids);
            $service->details()->sync($detail_ids);

            if ($request->service_address) {
                $service->service_address = join(',', $request->service_address);
            } else {
                $service->service_address = '';
            }
            $service->address()->sync($request->service_address);

            $service->save();

            Session::flash('message', 'Service created successfully!');
            Session::flash('status', 'success');
            return redirect('facilities/' . $service_location_recordid);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function taxonomy($id)
    {
        $chip_name = Taxonomy::where('taxonomy_recordid', '=', $id)->first()->taxonomy_name;
        $chip_title = 'Category:';

        $serviceids = Servicetaxonomy::where('taxonomy_recordid', '=', $id)->pluck('service_recordid')->toArray();
        $services = Service::whereIn('service_recordid', $serviceids)->orderBy('service_name')->paginate(10);

        $locationids = Servicelocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->toArray();

        $locations = Location::whereIn('location_recordid', $locationids)->with('services', 'organization')->get();

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

        return view('frontEnd.services', compact('services', 'locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $service = Service::where('service_recordid', '=', $id)->first();
        $service_name = $service->service_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.services.service_download', compact('service', 'layout'));
        $service_name = str_replace('"', '', $service_name);

        return $pdf->download($service_name . '111.pdf');
    }
    public function download_csv($id)
    {
        $csvExporter = new \Laracsv\Export();

        $layout = Layout::find(1);

        $services = Service::where('service_recordid', '=', $id)->get();

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

        // return $pdf->download($service_name . '111.pdf');
    }
    public function delete_service(Request $request)
    {
        try {
            Service::where('service_recordid', $request->service_recordid)->delete();

            Session::flash('message', 'Service deleted successfully!');
            Session::flash('status', 'success');
            return redirect('/services');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getDetailTerm(Request $request)
    {
        try {
            $detail_type = $request->value;

            $detail_info_list = Detail::where('detail_type', $detail_type)->pluck('detail_value', 'detail_recordid')->unique();

            return response()->json([
                'success' => true,
                'data' => $detail_info_list,
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }
    public function getTaxonomyTerm(Request $request)
    {
        try {
            $taxonomy_recordid = $request->value;
            $taxonomy_parent_name = Taxonomy::where('taxonomy_recordid', $taxonomy_recordid)->first();
            $taxonomy_info_list = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $taxonomy_recordid . '%')->get();
            // $taxonomy_info_list = $this->parent()->get();

            // while ($taxonomy_info_list->last() && $taxonomy_info_list->last()->taxonomy_parent_name !== null) {
            //     $parent = $taxonomy_info_list->last()->parent()->get();
            //     $taxonomy_info_list = $taxonomy_info_list->merge($parent);
            // }
            // dd($taxonomy_info_list);
            $taxonomy_array = [];
            foreach ($taxonomy_info_list as $value) {
                $taxonomy_array[$value->taxonomy_recordid] = '- ' . $value->taxonomy_name;
                $taxonomy_child_list = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->get();
                if ($taxonomy_child_list) {
                    foreach ($taxonomy_child_list as $value1) {
                        $taxonomy_array[$value1->taxonomy_recordid] = '-- '  . $value1->taxonomy_name;
                        $taxonomy_child_list1 = Taxonomy::where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->get();
                        if ($taxonomy_child_list1) {
                            foreach ($taxonomy_child_list1 as $value2) {
                                $taxonomy_array[$value2->taxonomy_recordid] = '--- '  . $value2->taxonomy_name;
                            }
                        }
                    }
                }
            }


            return response()->json([
                'success' => true,
                'data' => $taxonomy_array,
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }
}
