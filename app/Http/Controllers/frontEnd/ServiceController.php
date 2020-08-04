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
use App\Model\Taxonomy;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Auth;

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
        $locations = Location::with('services', 'organization');

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
        $locations = $locations->get();

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

        return view('frontEnd.services.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'meta_status', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list'))->with('taxonomy_tree', $taxonomy_tree);
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

        if(Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin')
        {
            $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
            $organization_names = Organization::select("organization_name")->whereIn('organization_recordid',$organization_recordid)->distinct()->get();
        }else{
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

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'schedule_opens_at', 'schedule_closes_at')->whereNotNull('schedule_opens_at')->orderBy('schedule_opens_at')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();

        return view('frontEnd.services.create', compact('map', 'organization_name_list', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list'));
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
            'service_organization' => 'required'
        ]);

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

            $organization_name = $request->service_organization;
            $service_organization = Organization::where('organization_name', '=', $organization_name)->first();
            $service_organization_id = $service_organization["organization_recordid"];
            $service->service_organization = $service_organization_id;

            if ($request->service_locations) {
                foreach ($request->service_locations as $key => $locationId) {
                    ServiceLocation::create([
                        'service_recordid' => $new_recordid,
                        'location_recordid' => $locationId
                    ]);
                }
                $service->service_locations = join(',', $request->service_locations);
            } else {
                $service->service_locations = '';
            }
            $service->locations()->sync($request->service_locations);

            if ($request->service_taxonomies) {
                $service->service_taxonomy = join(',', $request->service_taxonomies);
            } else {
                $service->service_taxonomy = '';
            }
            $service->taxonomy()->sync($request->service_taxonomies);

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
                foreach ($service_phone_number_list as $key => $service_phone_number) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number;
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            if ($request->service_contacts) {
                $service->service_contacts = join(',', $request->service_contacts);
            } else {
                $service->service_contacts = '';
            }
            $service->contact()->sync($request->service_contacts);

            if ($request->service_details) {
                $service->service_details = join(',', $request->service_details);
            } else {
                $service->service_details = '';
            }
            $service->details()->sync($request->service_details);

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
                }

                array_push($service_taxonomy_info_list, $service_taxonomy_info);
            }

            $location = Location::with('organization', 'address')->where('location_services', 'like', '%' . $id . '%')->get();
            if(count($service->contact) > 0){
                foreach ($service->contact as $key => $value) {
                    $service_contacts_recordid_list[] = $value->contact_recordid;
                }
            }else{
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
            return view('frontEnd.services.show', compact('service', 'location', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'service_taxonomy_info_list', 'contact_info_list', 'phone_number_info', 'organization'));
        } catch (\Throwable $th) {
            dd($th);
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
        $service_organization_list = Organization::select('organization_recordid', 'organization_name')->get();
        $service_location_list = Location::select('location_recordid', 'location_name')->get();
        $service_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
        $service_taxonomy_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->get();
        $service_details_list = Detail::select('detail_recordid', 'detail_value')->get();

        $location_info_list = explode(',', $service->service_locations);
        $contact_info_list = explode(',', $service->service_contacts);
        $taxonomy_info_list = explode(',', $service->service_taxonomy);

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

        $service_phone1 = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();
        $service_phone2 = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();

        $schedule_info_list = Schedule::select('schedule_recordid', 'schedule_opens_at', 'schedule_closes_at')->whereNotNull('schedule_opens_at')->orderBy('schedule_opens_at')->distinct()->get();

        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

        return view('frontEnd.services.edit', compact('service', 'map', 'service_address_street', 'service_address_city', 'service_address_state', 'service_address_postal_code', 'service_organization_list', 'service_location_list', 'service_phone1', 'service_phone2', 'service_contacts_list', 'service_taxonomy_list', 'service_details_list', 'location_info_list', 'contact_info_list', 'taxonomy_info_list', 'schedule_info_list', 'detail_info_list', 'ServiceSchedule', 'ServiceDetails'));
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

            if ($request->service_locations) {
                ServiceLocation::where('service_recordid', $id)->delete();
                foreach ($request->service_locations as $key => $locationId) {
                    ServiceLocation::create([
                        'service_recordid' => $id,
                        'location_recordid' => $locationId
                    ]);
                }
                $service->service_locations = join(',', $request->service_locations);
            } else {
                $service->service_locations = '';
            }

            if ($request->service_contacts) {
                $service->service_contacts = join(',', $request->service_contacts);
                $service->contact()->sync($request->service_contacts);
            } else {
                $service->service_contacts = '';
            }

            if ($request->service_taxonomy) {
                $service->service_taxonomy = join(',', $request->service_taxonomy);
            } else {
                $service->service_taxonomy = '';
            }

            if ($request->service_details) {
                $service->service_details = join(',', $request->service_details);
            } else {
                $service->service_details = '';
            }

            $service->service_phones = '';
            $phone_recordid_list = [];
            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                foreach ($service_phone_number_list as $key => $service_phone_number) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number;
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            $service_address_info = $request->service_address;
            $address_infos = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->distinct()->get();

            $full_address_info_list = array();
            foreach ($address_infos as $key => $value) {
                $full_address_info = $value->address_1 . ', ' . $value->address_city . ', ' . $value->address_state_province . ', ' . $value->address_postal_code;
                array_push($full_address_info_list, $full_address_info);
            }
            $full_address_info_list = array_unique($full_address_info_list);
            if ($service_address_info) {
                if (!in_array($service_address_info, $full_address_info_list)) {
                    $new_recordid = Address::max('address_recordid') + 1;
                    $service->service_address = $new_recordid;
                    $address = new Address();
                    $address->address_recordid = $new_recordid;
                    $explodeServiceAddress = $service_address_info ? explode(', ', $service_address_info) : [];
                    $address->address_1 = count($explodeServiceAddress) >= 1 ? $explodeServiceAddress[0] : '';
                    $address->address_city = count($explodeServiceAddress) >= 2 ? $explodeServiceAddress[1] : '';
                    $address->address_state_province = count($explodeServiceAddress) >= 3 ? $explodeServiceAddress[2] : '';
                    $address->address_postal_code = count($explodeServiceAddress) >= 4 ? $explodeServiceAddress[3] : '';
                    // $address->address_city = explode(', ', $service_address_info)[1];
                    // $address->address_state_province = explode(', ', $service_address_info)[2];
                    // $address->address_postal_code = explode(', ', $service_address_info)[3];
                    $address->save();
                } else {
                    foreach ($address_infos as $key => $value) {
                        $full_address_info = $value->address_1 . ', ' . $value->address_city . ', ' . $value->address_state_province . ', ' . $value->address_postal_code;
                        if ($full_address_info == $service_address_info) {
                            $service->service_address = $value->address_recordid;
                        }
                    }
                }
            } else {
                $service->service_address = $service_address_info;
            }

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            if ($request->service_details) {
                $service->service_details = join(',', $request->service_details);
            } else {
                $service->service_details = '';
            }
            $service->details()->sync($request->service_details);

            $service->save();

            $service_organization = $request->service_organization;
            $organization = Organization::where('organization_recordid', '=', $service_organization)->select('organization_recordid', 'updated_at')->first();
            $organization->updated_at = date("Y-m-d H:i:s");
            $organization->save();
            Session::flash('message', 'Service updated successfully!');
            Session::flash('status', 'success');
            return redirect('services/' . $id);
        } catch (\Throwable $th) {
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
        $schedule_info_list = Schedule::select('schedule_recordid', 'schedule_opens_at', 'schedule_closes_at')->whereNotNull('schedule_opens_at')->orderBy('schedule_opens_at')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();

        return view('frontEnd.services.service-create-in-organization', compact('map', 'organization', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list'));
    }

    public function create_in_facility($id)
    {
        $map = Map::find(1);
        $facility = Location::where('location_recordid', '=', $id)->first();

        $service_status_list = ['Yes', 'No'];

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'schedule_opens_at', 'schedule_closes_at')->whereNotNull('schedule_opens_at')->orderBy('schedule_opens_at')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

        return view('frontEnd.services.service-create-in-facility', compact('map', 'facility', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list'));
    }
    public function add_new_service_in_organization(Request $request)
    {
        $this->validate($request,[
            'service_name' => 'required',
            'service_email' => 'required'
        ]);
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

            if ($request->service_locations) {
                $service->service_locations = join(',', $request->service_locations);
            } else {
                $service->service_locations = '';
            }
            $service->locations()->sync($request->service_locations);

            if ($request->service_taxonomies) {
                $service->service_taxonomy = join(',', $request->service_taxonomies);
            } else {
                $service->service_taxonomy = '';
            }
            $service->taxonomy()->sync($request->service_taxonomies);

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
                foreach ($service_phone_number_list as $key => $service_phone_number) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number;
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            if ($request->service_contacts) {
                $service->service_contacts = join(',', $request->service_contacts);
            } else {
                $service->service_contacts = '';
            }
            $service->contact()->sync($request->service_contacts);

            if ($request->service_details) {
                $service->service_details = join(',', $request->service_details);
            } else {
                $service->service_details = '';
            }
            $service->details()->sync($request->service_details);

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
        $this->validate($request,[
            'service_name' => 'required',
            'service_email' => 'required'
        ]);
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

            if ($request->service_taxonomies) {
                $service->service_taxonomy = join(',', $request->service_taxonomies);
            } else {
                $service->service_taxonomy = '';
            }
            $service->taxonomy()->sync($request->service_taxonomies);

            $service->service_phones = '';
            $phone_recordid_list = [];
            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                foreach ($service_phone_number_list as $key => $service_phone_number) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number)->select('phone_recordid')->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number;
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            if ($request->service_schedules) {
                $service->service_schedule = join(',', $request->service_schedules);
            } else {
                $service->service_schedule = '';
            }
            $service->schedules()->sync($request->service_schedules);

            if ($request->service_contacts) {
                $service->service_contacts = join(',', $request->service_contacts);
            } else {
                $service->service_contacts = '';
            }
            $service->contact()->sync($request->service_contacts);

            if ($request->service_details) {
                $service->service_details = join(',', $request->service_details);
            } else {
                $service->service_details = '';
            }
            $service->details()->sync($request->service_details);

            if ($request->service_address) {
                $service->service_address = join(',', $request->service_address);
            } else {
                $service->service_address = '';
            }
            $service->address()->sync($request->service_address);

            $service->save();

            Session::flash('message','Service created successfully!');
            Session::flash('status','success');
            return redirect('facilities/' . $service_location_recordid);
        } catch (\Throwable $th) {
            Session::flash('message',$th->getMessage());
            Session::flash('status','error');
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
}
