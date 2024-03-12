<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\ServiceLocationImport;
use App\Imports\Services;
use App\Exports\ServiceExport;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\Airtable_v2;
use App\Model\Alt_taxonomy;
use App\Model\City;
use App\Model\Code;
use App\Model\CodeCategory;
use App\Model\CodeLedger;
use App\Model\Comment;
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
use App\Model\OrganizationTag;
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
use App\Model\Disposition;
use App\Model\FeeOption;
use App\Model\Helptext;
use App\Model\InteractionMethod;
use App\Model\InterpretationService;
use App\Model\TaxonomyType;
use App\Model\Language;
use App\Model\OrganizationStatus;
use App\Model\PhoneType;
use App\Model\Program;
use App\Model\Region;
use App\Model\RequiredDocument;
use App\Model\ServiceArea;
use App\Model\ServiceStatus;
use App\Model\ServiceTag;
use App\Model\SessionData;
use App\Model\SessionInteraction;
use App\Model\State;
use App\Model\TaxonomyEmail;
use App\Services\ServiceDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SendGrid;
use SendGrid\Mail\Mail;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public $commonController, $serviceDataService;

    public function __construct(CommonController $commonControllerData, ServiceDataService $serviceDataService)
    {
        $this->commonController = $commonControllerData;
        $this->serviceDataService = $serviceDataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $service_state_filter = 'Verified';
        // $services = Service::with('locations')->orderBy('service_name')->where('service_status', '=', $service_state_filter);
        $services = Service::whereNotNull('service_name')->with('locations')->orderBy('service_name');
        $service_locations = ServiceLocation::pluck('location_recordid')->unique()->toArray();
        // $locations = Location::with('services', 'organization', 'address');
        $locations = Location::whereIn('location_recordid', $service_locations)->with('services', 'organization', 'address');



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
        $layout = Layout::findOrFail(1);

        $metas = MetaFilter::all();
        $count_metas = MetaFilter::count();
        $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;
        if ($layout->meta_filter_activate == 1 && $count_metas > 0 && $filter_label == 'on_label') {
            // $address_serviceids = Service::pluck('service_recordid')->toArray();
            // $taxonomy_serviceids = Service::pluck('service_recordid')->toArray();
            $address_serviceids = [];
            $taxonomy_serviceids = [];

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

                        if ($meta->operations == 'Include') {
                            $serviceids = Service::whereIn('service_status', $values)->pluck('service_recordid')->toArray();
                            // $serviceids = Service::whereIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                        }

                        if ($meta->operations == 'Exclude') {
                            $serviceids = Service::whereNotIn('service_status', $values)->pluck('service_recordid')->toArray();
                        }
                        $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'service_tag') {
                        $operations = $meta->operations;
                        $service_tag_ids = Service::where(function ($query) use ($values, $operations) {
                            foreach ($values as $keyword) {
                                if ($keyword && $operations == 'Include') {
                                    $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                                }
                                if ($keyword && $operations == 'Exclude') {
                                    $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', service_tag)');
                                }
                            }
                            return $query;
                        })->pluck('service_recordid')->toArray();
                        $taxonomy_serviceids = array_merge($service_tag_ids, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'organization_status') {

                        $organization_service_recordid = [];
                        if ($values && count($values) > 0) {
                            $organizations_status_ids = [];
                            $operations = $meta->operations;
                            if ($values) {
                                $organizations_status_ids = Organization::where(function ($query) use ($values, $operations) {
                                    foreach ($values as $keyword) {
                                        // $organization_status = OrganizationStatus::whereId($keyword)->first();
                                        if ($operations == 'Include') {
                                            $query = $query->orWhere('organization_status_x', 'LIKE', "%$keyword%");
                                        }
                                        if ($operations == 'Exclude') {
                                            $query = $query->orWhere('organization_status_x', 'NOT LIKE', "%$keyword%");
                                        }
                                    }
                                    return $query;
                                })->pluck('organization_recordid')->toArray();
                            }
                            $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_status_ids)->pluck('service_recordid')->toArray();
                        }
                        $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                    }
                    if ($meta->facet == 'organization_tag') {

                        $organization_service_recordid = [];
                        if ($values && count($values) > 0) {
                            $organizations_tags_ids = [];
                            $operations = $meta->operations;
                            if ($values) {
                                $organizations_tags_ids = Organization::where(function ($query) use ($values, $operations) {
                                    foreach ($values as $keyword) {
                                        // $organization_status = OrganizationStatus::whereId($keyword)->first();
                                        if ($keyword && $operations == 'Include') {
                                            $query = $query->orWhereRaw('find_in_set(' . $keyword . ', organization_tag)');
                                        }
                                        if ($keyword && $operations == 'Exclude') {
                                            $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', organization_tag)');
                                        }
                                    }
                                    return $query;
                                })->pluck('organization_recordid')->toArray();
                            }
                            $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid')->toArray();
                        }
                        $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
                    }
                }
            }
            $services = $services->whereIn('service_recordid', $address_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids);
            $services_ids = $services->pluck('service_recordid')->toArray();
            $locations_ids = Servicelocation::whereIn('service_recordid', $services_ids)->pluck('location_recordid')->toArray();
            $locations = $locations->whereIn('location_recordid', $locations_ids);
        }
        if (!Auth::check()) {
            $services =  $services->where('access_requirement', '!=', 'yes');
            $locations = $locations->whereHas('services', function ($q) {
                $q->where('access_requirement', '!=', 'yes');
            });
        }
        $services = $services->paginate(10);

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
        $locations = $locations->get();

        $organization_tagsArray = OrganizationTag::get();
        $organization_tagsArray = json_encode($organization_tagsArray);
        $service_tagsArray = ServiceTag::get();
        $service_tagsArray = json_encode($service_tagsArray);

        $categoryIds = Service::whereNotNull('code_category_ids')->where('code_category_ids', '!=', '')->pluck('code_category_ids')->toArray();
        $tempCate = [];
        foreach ($categoryIds as $key => $value) {
            $tempCate = array_merge(explode(',', $value), $tempCate);
        }
        $tempCate = array_values(array_unique($tempCate));

        // $sdoh_codes_category_Array = Code::whereIn('id', $tempCate)->pluck('category')->unique()->toArray();
        $sdoh_codes_category_Array = CodeCategory::select('name', 'id')->get();

        // if (count($sdoh_codes_category_Array)) {
        //     $sdoh_codes_category_Array = array_values(array_filter($sdoh_codes_category_Array));
        // }
        $sdoh_codes_category_Array = json_encode($sdoh_codes_category_Array);


        $codesIds = Service::whereNotNull('SDOH_code')->where('SDOH_code', '!=', '')->pluck('SDOH_code')->toArray();
        $tempCode = [];
        foreach ($codesIds as $key => $value) {
            $tempCode = array_merge(explode(',', $value), $tempCode);
        }
        $tempCode = array_values(array_unique($tempCode));

        $sdoh_codes_Array = Code::whereIn('id', $tempCode)->select('code', 'id')->whereNotNull('code')->get();
        // if (count($sdoh_codes_Array)) {
        //     $sdoh_codes_Array = array_values(array_filter($sdoh_codes_Array));
        // }
        // $sdoh_codes_Array = json_encode($sdoh_codes_Array);

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
            $serviceCategoryId = TaxonomyType::orderBy('order')->first();
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name');
            $taxonomy_recordids = Taxonomy::getTaxonomyRecordids();
            if (count($taxonomy_recordids) > 0) {
                $parent_taxonomies->whereIn('taxonomy_recordid', $taxonomy_recordids);
            }

            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies->get();
        }

        $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
        return view('frontEnd.services.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'meta_status', 'grandparent_taxonomies', 'sort_by_distance_clickable', 'service_taxonomy_info_list', 'service_taxonomy_badge_color_list', 'organization_tagsArray', 'layout', 'service_tagsArray', 'sdoh_codes_category_Array', 'sdoh_codes_Array', 'organizationStatus'))->with('taxonomy_tree', $taxonomy_tree);
    }

    public function tb_services(Request $request)
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

        if (Auth::user() && Auth::user()->user_organization && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) {
            $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid')->toArray() : [];
            if (Auth::user()->organization_tags) {
                $organization_tags = explode(',', Auth::user()->organization_tags);
                foreach ($organization_tags as $key => $value) {
                    $organizations = Organization::where('organization_tag', 'LIKE', '%' . $value . '%')->pluck('organization_recordid')->toArray();
                    $organization_recordid = array_unique(array_merge($organization_recordid, $organizations));
                }
            }
            $organization_names = Organization::orderBy("organization_name")->select("organization_name")->whereIn('organization_recordid', $organization_recordid)->distinct()->get();
        } else {
            $organization_names = Organization::orderBy("organization_name")->select("organization_name")->distinct()->get();
        }
        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);


        $facility_info_list = Location::select('location_recordid', 'location_name')->orderBy('location_recordid')->distinct()->get();

        $service_status_list = ['Yes' => 'Yes', 'No' => 'No'];

        // $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $layout = Layout::findOrFail(1);
        $exclude_vocabulary = [];
        if ($layout) {
            $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
        }
        $taxonomy_info_list = Taxonomy::whereNull('taxonomy_parent_name')->Where(function ($query) use ($exclude_vocabulary) {
            for ($i = 0; $i < count($exclude_vocabulary); $i++) {
                $query->where('taxonomy_name', 'not like', '%' . $exclude_vocabulary[$i] . '%');
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
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens', 'closes')->whereNotNull('opens')->where('opens', '!=', '')->orderBy('opens')->distinct()->get();

        $all_contacts = Contact::orderBy('contact_name')->with('phone')->distinct()->get();
        $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'services', 'schedules', 'accessibilities', 'regions')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();


        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');

        $phone_type = PhoneType::orderBy('order')->orderBy('type')->pluck('type', 'id');
        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        // $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $detail_info_list = Detail::pluck('detail_value', 'detail_recordid')->unique();

        $address_city_list = City::orderBy('city')->pluck('city', 'city');
        $address_states_list = State::orderBy('state')->pluck('state', 'state');

        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');

        $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
        $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

        $service_category_types = Taxonomy::orderBy('taxonomy_name')->whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::orderBy('taxonomy_name')->whereNull('taxonomy_parent_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $programs = Program::pluck('name', 'program_recordid');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);

        $help_text = Helptext::first();

        $conditions = Code::where('resource', 'Condition')->get()->groupBy('category');
        $goals = Code::where('resource', 'Goal')->get()->groupBy('category');
        $activities = Code::where('resource', 'Procedure')->get()->groupBy('category');


        $service_area = ServiceArea::pluck('name', 'id');
        $fee_options = FeeOption::pluck('fees', 'id');

        $regions = Region::pluck('region', 'id');

        // $codes = Code::whereNotNull('category')->where('category', '!=', '')->whereIn('resource', ['Condition', 'Goal', 'Procedure'])->orderBy('category')->pluck('category', 'id')->unique();
        $codes = CodeCategory::pluck('name', 'id');
        $selected_ids = [];

        $procedure_grouping = [];

        $languages = Language::pluck('language', 'id');
        $interpretation_services = InterpretationService::pluck('name', 'id');
        $requiredDocumentTypes = Detail::whereDetailType('Required Document')->pluck('detail_value', 'id');
        $all_programs = Program::with('organization')->distinct()->get();

        $accessibilities = Accessibility::pluck('accessibility', 'id');

        return view('frontEnd.services.create', compact('map', 'organization_name_list', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list', 'all_contacts', 'all_locations', 'phone_languages', 'phone_type', 'service_info_list', 'address_city_list', 'address_states_list', 'detail_types', 'service_category_types', 'service_eligibility_types', 'programs', 'all_phones', 'phone_language_data', 'conditions', 'goals', 'activities', 'help_text', 'layout', 'service_area', 'fee_options', 'regions', 'codes', 'selected_ids', 'procedure_grouping', 'languages', 'interpretation_services', 'requiredDocumentTypes', 'all_programs', 'accessibilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'service_name' => 'required',
            'service_organization' => 'required',
            'service_description' => 'required',
        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }
        try {
            $layout = Layout::findOrFail(1);
            $service = new Service;
            $service_recordids = Service::select("service_recordid")->distinct()->get();
            $service_recordid_list = array();
            foreach ($service_recordids as $key => $value) {
                $service_recordid = $value->service_recordid;
                $service_recordid_list[] = $service_recordid;
            }
            $service_recordid_list = array_unique($service_recordid_list);

            $new_recordid = Service::max('service_recordid') + 1;
            if (in_array($new_recordid, $service_recordid_list)) {
                $new_recordid = Service::max('service_recordid') + 1;
            }
            $service_recordid = $new_recordid;
            $service->service_recordid = $new_recordid;

            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_url = $request->service_url;
            // $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            // $service->service_status = '';
            if ($layout && $layout->default_service_status) {
                $service->service_status = $layout->default_service_status;
            }
            $service->service_status = $request->service_status;
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;
            $service->service_code = $request->service_code;
            $service->access_requirement = $request->access_requirement;
            $service->alert = $request->alert;
            $service->funding = $request->funding;

            $this->saveRequiredDocument($request, $service_recordid);

            $service->eligibility_description = $request->eligibility_description;
            $service->minimum_age = $request->minimum_age;
            $service->maximum_age = $request->maximum_age;
            $service->service_alert = $request->service_alert;
            $service->service_language = $request->service_language ? implode(',', $request->service_language) : '';
            $service->service_interpretation = $request->service_interpretation;
            // $service->service_interpretation = $request->service_interpretation ? implode(',', $request->service_interpretation) : '';

            $organization_name = $request->service_organization;
            $service_organization = Organization::where('organization_name', '=', $organization_name)->first();
            $service_organization_id = $service_organization["organization_recordid"];
            $service->service_organization = $service_organization_id;

            // $service->service_area = is_array($request->service_area) ? implode(',', array_values(array_filter($request->service_area))) : '';
            // $service->fee_option = is_array($request->fee_option) ? implode(',', array_values(array_filter($request->fee_option))) : '';
            if ($request->service_area)
                $service->areas()->sync($request->service_area);
            if ($request->fee_option)
                $service->fees()->sync($request->fee_option);

            $servicePrograms = $this->saveServiceProgram($request, $service_recordid);

            $service->service_program = '';
            if (count($servicePrograms) > 0) {
                $service->service_program = implode(',', $servicePrograms);
            }
            $service->program()->sync($servicePrograms);

            if ($request->procedure_grouping && is_array($request->procedure_grouping) && count($request->procedure_grouping) > 0) {
                foreach ($request->procedure_grouping as $key => $procedure_grouping) {
                    $code_ids = explode('|', $procedure_grouping);
                    if (count($code_ids) > 0 && isset($code_ids[1])) {
                        $code = Code::where('code_id', $code_ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => 3,
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                $service->procedure_grouping = serialize($request->procedure_grouping);
            }

            // service code section

            if ($request->code_category_ids && count($request->code_category_ids) > 0) {
                $service->code_category_ids = implode(',', $request->code_category_ids);
            } else {
                $service->code_category_ids = '';
            }

            $service_codes = [];
            if ($request->code_conditions) {
                $code_conditions = is_array($request->code_conditions) ? array_values(array_filter($request->code_conditions)) : [];
                foreach ($code_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                // $service_codes = array_merge($service_codes, array_map('intval', $request->code_conditions));
            }
            if ($request->goal_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->goal_conditions));
                $goal_conditions = is_array($request->goal_conditions) ? array_values(array_filter($request->goal_conditions)) : [];
                foreach ($goal_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
            }
            if ($request->activities_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->activities_conditions));
                $activities_conditions = is_array($request->activities_conditions) ? array_values(array_filter($request->activities_conditions)) : [];
                foreach ($activities_conditions as $key => $code_id) {
                    // $ids = explode('_', $code_id);
                    $code = Code::whereId($code_id)->first();
                    if ($code) {
                        $service_codes[] = $code_id;
                        CodeLedger::create([
                            'rating' => 3,
                            'service_recordid' => $new_recordid,
                            'organization_recordid' => $service_organization_id,
                            'SDOH_code' => $code->id,
                            'resource' => $code->resource,
                            'description' => $code->description,
                            'code_type' => $code->code_system,
                            'code' => $code->code,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
            if (count($service_codes) > 0) {
                $service->SDOH_code = implode(',', $service_codes);
            }
            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                // accessibility
                $location_accessibility = $request->location_accessibility && count($request->location_accessibility) > 0 ? json_decode($request->location_accessibility[0], true) : [];
                $location_accessibility_details = $request->location_accessibility_details && count($request->location_accessibility_details) > 0 ? json_decode($request->location_accessibility_details[0], true) : [];
                $location_regions = $request->location_regions && count($request->location_regions) > 0 ? json_decode($request->location_regions[0], true) : [];

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
                    $opens_location_monday_datas = $request->opens_location_monday_datas ? json_decode($request->opens_location_monday_datas, true) : [];
                    $closes_location_monday_datas = $request->closes_location_monday_datas ? json_decode($request->closes_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_location_tuesday_datas = $request->opens_location_tuesday_datas ? json_decode($request->opens_location_tuesday_datas, true) : [];
                    $closes_location_tuesday_datas = $request->closes_location_tuesday_datas ? json_decode($request->closes_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_location_wednesday_datas = $request->opens_location_wednesday_datas ? json_decode($request->opens_location_wednesday_datas, true) : [];
                    $closes_location_wednesday_datas = $request->closes_location_wednesday_datas ? json_decode($request->closes_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_location_thursday_datas = $request->opens_location_thursday_datas ? json_decode($request->opens_location_thursday_datas, true) : [];
                    $closes_location_thursday_datas = $request->closes_location_thursday_datas ? json_decode($request->closes_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_location_friday_datas = $request->opens_location_friday_datas ? json_decode($request->opens_location_friday_datas, true) : [];
                    $closes_location_friday_datas = $request->closes_location_friday_datas ? json_decode($request->closes_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_location_saturday_datas = $request->opens_location_saturday_datas ? json_decode($request->opens_location_saturday_datas, true) : [];
                    $closes_location_saturday_datas = $request->closes_location_saturday_datas ? json_decode($request->closes_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_location_sunday_datas = $request->opens_location_sunday_datas ? json_decode($request->opens_location_sunday_datas, true) : [];
                    $closes_location_sunday_datas = $request->closes_location_sunday_datas ? json_decode($request->closes_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds ? json_decode($request->location_holiday_closeds, true) : [];


                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = $location_recordid;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                        $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                        $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                        $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;


                        // accessesibility

                        if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                            // Accessibility::create([
                            //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                            //     'accessibility' => $location_accessibility[$i],
                            //     'accessibility_details' => $location_accessibility_details[$i],
                            //     'accessibility_location' => $location_recordid
                            // ]);
                            $location->accessibility_recordid = $location_accessibility[$i];
                            $location->accessibility_details = $location_accessibility_details[$i];
                        }
                        if (isset($location_regions[$i])) {
                            $location_regions[$i] = is_array($location_regions[$i]) ? array_values(array_filter($location_regions[$i])) : [];
                            $location->regions()->sync($location_regions[$i]);
                        }

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
                            $location->location_address = $address_info->address_recordid;
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
                            $location->location_address = $new_address_recordid;
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
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
                                    // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {
                                $schedules = Schedule::where('locations', $location_recordid)->where('weekday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                    $schedules->locations = $location_recordid;
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                            Schedule::where('locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens = $request->holiday_open_at[$hs];
                                //     $schedules->closes = $request->closes[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes = $location_holiday_close_ats[$i][$hs];
                                if ($location_holiday_closeds[$i][$hs] == 1) {
                                    $schedules->schedule_closed = '1';
                                }
                                $schedules->schedule_holiday = '1';
                                $schedules->save();
                                $schedule_locations[] = $schedule_recordid;
                                // }
                            }
                        }
                        $schedule_locations = is_array($schedule_locations) ? array_values(array_filter($schedule_locations)) : [];
                        $location->location_schedule = join(',', $schedule_locations);

                        $location->schedules()->sync($schedule_locations);

                        $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                        $location->phones()->sync($location_phone_recordid_list);
                        $location->location_phones = '';
                        $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                        $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];
                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                            $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                            $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                            $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;

                            // accessesibility
                            if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                                // Accessibility::updateOrCreate([
                                //     'accessibility_location' => $request->location_recordid[$i]
                                // ], [
                                //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                                //     'accessibility' => $location_accessibility[$i],
                                //     'accessibility_details' => $location_accessibility_details[$i],
                                // ]);
                                $location->accessibility_recordid = $location_accessibility[$i];
                                $location->accessibility_details = $location_accessibility_details[$i];
                            }
                            if (isset($location_regions[$i])) {
                                $location_regions[$i] = is_array($location_regions[$i]) ? array_values(array_filter($location_regions[$i])) : [];
                                $location->regions()->sync($location_regions[$i]);
                            }

                            // location address
                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $address_info->address_recordid;
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
                                $location->location_address = $new_address_recordid;
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
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
                                        // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }

                            // schedule section
                            $schedule_locations = [];

                            if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('locations', $location->location_recordid)->where('weekday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                        $schedules->locations = $location->location_recordid;
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                Schedule::where('locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens = $request->holiday_open_at[$hs];
                                    //     $schedules->closes = $request->closes[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes = $location_holiday_close_ats[$i][$hs];
                                    if ($location_holiday_closeds[$i][$hs] == 1) {
                                        $schedules->schedule_closed = '1';
                                    }
                                    $schedules->schedule_holiday = '1';
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                    // }
                                }
                            }
                            $schedule_locations = is_array($schedule_locations) ? array_values(array_filter($schedule_locations)) : [];
                            $location->location_schedule = join(',', $schedule_locations);

                            $location->schedules()->sync($schedule_locations);
                            $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                            $location->phones()->sync($location_phone_recordid_list);

                            $location->location_phones = '';
                            $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                            $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];
                            $location->address()->sync($location_address_recordid_list);
                            $location->save();
                        }
                        array_push($service_locations, $request->location_recordid[$i]);
                    }
                }
            }
            $service_locations = is_array($service_locations) ? array_values(array_filter($service_locations)) : [];
            $service->service_locations = join(',', $service_locations);
            $service->locations()->sync($service_locations);


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
                        $taxonomy->status = 'Unpublished';
                        $taxonomy->created_by = Auth::id();
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        // $service_taxonomies[] = $service_category_term[$key];
                        $service_taxonomies = array_merge($service_taxonomies, $service_category_term[$key]);
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = array_filter($request->service_eligibility_type);
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key][0] ?? '';
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->status = 'Unpublished';
                        $taxonomy->created_by = Auth::id();
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        // $service_taxonomies[] = $service_eligibility_term[$key];
                        $service_taxonomies = array_merge($service_taxonomies, $service_eligibility_term[$key]);
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = is_array($service_taxonomies) ? array_unique(array_values(array_filter($service_taxonomies))) : [];
            }
            $service->service_taxonomy = join(',', $service_taxonomies);
            $service->taxonomy()->sync($service_taxonomies);

            $service->service_phones = '';
            $phone_recordid_list = [];

            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                $service_main_priority_list = $request->main_priority;
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
                        $phone_info->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && count($service_phone_language_list) > 0 && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $phone_recordid_list = is_array($phone_recordid_list) ? array_values(array_filter($phone_recordid_list)) : [];
            $service->phone()->sync($phone_recordid_list);


            $schedule_services = [];
            $schedule_services = $this->saveServiceSchedule($request, $service);


            if ($request->holiday_start_date && $request->holiday_end_date && $request->holiday_open_at && $request->holiday_close_at) {
                Schedule::where('services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->services = $service->service_recordid;
                    $schedules->dtstart = $request->holiday_start_date[$i];
                    $schedules->until = $request->holiday_end_date[$i];
                    $schedules->opens = $request->holiday_open_at[$i];
                    $schedules->closes = $request->holiday_close_at[$i];
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    if ($request->holiday_open_24_hours && in_array(($i + 1), $request->holiday_open_24_hours)) {
                        $schedules->open_24_hours = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            if (count($schedule_services)) {
                $service->service_schedule = join(',', $schedule_services);
            }
            $schedule_services = is_array($schedule_services) ? array_values(array_filter($schedule_services)) : [];
            $service->schedules()->sync($schedule_services);

            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                $contact_visibility = $request->contact_visibility && count($request->contact_visibility) > 0 ? $request->contact_visibility : [];
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
                        $contact->visibility = $contact_visibility[$i];
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
                        $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
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
                            $contact->visibility = $contact_visibility[$i];
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
                            $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
                            $contact->phone()->sync($contact_phone_recordid_list);
                            $contact->save();
                        }
                        array_push($service_contacts, $request->contact_recordid[$i]);
                    }
                }
            }
            $service_contacts = is_array($service_contacts) ? array_values(array_filter($service_contacts)) : [];
            $service->service_contacts = join(',', $service_contacts);
            $service->contact()->sync($service_contacts);

            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_ids = $detail_term;
                            // $detail_ids[] = array_merge($detail_ids, $detail_term[$key]);
                        }
                    }
                }
            }
            $detail_ids = is_array($detail_ids) ? array_values(array_filter($detail_ids)) : [];
            $service->service_details = join(',', $detail_ids);
            $service->details()->sync($detail_ids);

            $service_address = $request->service_address;
            $service_address = is_array($service_address) ? array_values(array_filter($service_address)) : [];
            if ($service_address) {
                $service->service_address = join(',', $service_address);
            } else {
                $service->service_address = '';
            }
            $service->address()->sync($service_address);
            $service->created_by = Auth::id();
            $service->save();

            $audit = Audit::where('auditable_id', $service->service_recordid)->first();

            if ($audit) {
                $audit->auditable_id = $service_recordid;
                $audit->save();
            }

            Session::flash('message', 'Service created successfully');
            Session::flash('status', 'success');
            return redirect('services');
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in create service : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('services');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $service = Service::find($id);
        // return response()->json($service);

        try {
            $service = Service::where('service_recordid', '=', $id)->with('locations', 'phone', 'locations.address', 'contact', 'schedules')->first();
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
            if ($service && (Auth::check() || (!Auth::check() && $service->organizations && $service->organizations->organization_status_x && isset($organizationStatus[$service->organizations->organization_status_x]) && ($organizationStatus[$service->organizations->organization_status_x] != 'Out of Business' && $organizationStatus[$service->organizations->organization_status_x] != 'Inactive')) || !$service->organizations || !$service->organizations->organization_status_x)) {
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
                $mainPhoneNumber = [];
                $phone_number_info_array = [];

                if (isset($service->phone) && count($service->phone) > 0) {
                    foreach ($service->phone()->with('type')->get() as $valueV) {
                        if ($valueV->phone_language) {
                            $languageId = $valueV->phone_language ? explode(',', $valueV->phone_language) : [];
                            $languages = Language::whereIn('language_recordid', $languageId)->pluck('language')->unique()->toArray();
                            $valueV->phone_language = implode(', ', $languages);
                        }
                        if ($valueV->main_priority == '1') {
                            $mainPhoneNumber[] = $valueV;
                        } else {
                            $phone_number_info_array[] = $valueV;
                        }
                    }
                }
                $mainPhoneNumber = array_filter(array_merge($mainPhoneNumber, $phone_number_info_array));


                $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
                $service_taxonomy_info_list = [];
                foreach ($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid) {
                    $service_taxonomy_info = (object)[];
                    $service_taxonomy_info->taxonomy_recordid = $service_taxonomy_recordid;

                    $taxonomy = Taxonomy::where('taxonomy_recordid', '=', (int)($service_taxonomy_recordid))->first();
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
                        if (!$value->location_latitude || !$value->location_longitude || $value->location_latitude == 0 || $value->location_longitude == 0) {
                            app('App\Http\Controllers\frontEnd\CommonController')->apply_geocode($value);
                        }
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
                $contactCount = 0;
                foreach ($contact_info_list as $key => $value) {
                    if ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && $value->organization && str_contains(Auth::user()->user_organization, $value->organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || !Auth::check() && $value->visibility != 'private') {
                        $contactCount += 1;
                    }
                }
                $comment_list = Comment::where('comments_service', '=', $id)->get();
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
                $layout = Layout::find(1);
                $serviceAudits = $this->commonController->serviceSection($service);
                $allTags = ServiceTag::pluck('tag', 'id')->put('create_new', '+ Create New');
                $disposition_list = Disposition::pluck('name', 'id');
                $method_list = InteractionMethod::pluck('name', 'id');
                $serviceStatus = ServiceStatus::pluck('status', 'id');

                $categoryids = $service->code_category_ids ? explode(',', $service->code_category_ids) : [];

                $categoryData = Code::whereIn('id', $categoryids)->get();
                $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');

                return view('frontEnd.services.show', compact('service', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'service_taxonomy_info_list', 'contact_info_list', 'phone_number_info', 'organization', 'serviceAudits', 'mainPhoneNumber', 'contactCount', 'layout', 'comment_list', 'allTags', 'disposition_list', 'method_list', 'serviceStatus', 'categoryData', 'organizationStatus'));
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $map = Map::find(1);
        $service = Service::where('service_recordid', '=', $id)->first();

        $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
        if ($service && (Auth::check() || (!Auth::check() && $service->organizations && $service->organizations->organization_status_x && isset($organizationStatus[$service->organizations->organization_status_x]) && ($organizationStatus[$service->organizations->organization_status_x] != 'Out of Business' && $organizationStatus[$service->organizations->organization_status_x] != 'Inactive')) || !$service->organizations || !$service->organizations->organization_status_x)) {

            if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $service->organizations()->first()->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->service_tags && $service->service_tag && count(array_intersect(explode(',', Auth::user()->service_tags), explode(',', $service->service_tag))) > 0) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'Section Admin' && Auth::user()->organization_tags && $service->organizations()->first()->organization_tag && count(array_intersect(explode(',', Auth::user()->organization_tags), explode(',', $service->organizations()->first()->organization_tag))) > 0)) {

                $addressIds = $service->address ? $service->address->pluck('address_recordid')->toArray() : [];
                $service_codes = $service->codes()->select(DB::raw('CONCAT(rating, "_", SDOH_code) as code_id'))->where('code', '!=', 'None')->where('code_type', '!=', 'Gravity Grouping')->pluck('code_id')->toArray();

                // $service_organization_list = Organization::select('organization_recordid', 'organization_name')->get();
                $contactOrganization = $service->organizations ? $service->organizations->contact : [];

                if (Auth::user() && Auth::user()->user_organization && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) {
                    $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid')->toArray() : [];
                    if (Auth::user()->organization_tags) {
                        $organization_tags = explode(',', Auth::user()->organization_tags);
                        foreach ($organization_tags as $key => $value) {
                            $organizations = Organization::where('organization_tag', 'LIKE', '%' . $value . '%')->pluck('organization_recordid')->toArray();
                            $organization_recordid = array_unique(array_merge($organization_recordid, $organizations));
                        }
                    }
                    $organization_names = Organization::orderBy("organization_name")->select("organization_name", "organization_recordid")->whereIn('organization_recordid', $organization_recordid)->distinct()->get();
                } else {
                    $organization_names = Organization::orderBy("organization_name")->select("organization_name", "organization_recordid")->distinct()->get();
                }

                $service_organization_list = $organization_names;

                $service_location_list = Location::select('location_recordid', 'location_name')->get();
                $service_contacts_list = Contact::select('contact_recordid', 'contact_name')->get();
                $layout = Layout::findOrFail(1);
                $exclude_vocabulary = [];
                if ($layout) {
                    $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
                }
                $service_taxonomy_list = Taxonomy::whereNull('taxonomy_parent_name')->whereNull('exclude_vocabulary')->Where(function ($query) use ($exclude_vocabulary) {
                    for ($i = 0; $i < count($exclude_vocabulary); $i++) {
                        $query->where('taxonomy_name', 'not like', '%' . $exclude_vocabulary[$i] . '%');
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

                $location_data = $service->locations->pluck('location_recordid')->toArray();
                if (count($location_data) > 0) {
                    $location_info_list = array_merge($location_data, $location_data);
                    $location_info_list = is_array($location_info_list) ? array_values(array_unique($location_info_list)) : [];
                }

                $service_locations_data = Location::whereIn('location_recordid', $location_info_list)->with('phones', 'address', 'schedules')->get();
                $service_locations_data = $service_locations_data->filter(function ($value) {
                    $address = $value->address && count($value->address) > 0 ? $value->address[count($value->address) - 1] : '';
                    $phones = $value->phones && count($value->phones) > 0 ? $value->phones[count($value->phones) - 1] : '';
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

                $detail_types = DetailType::orderBy('order')->pluck('type', 'type');

                $serviceDetailsData = Detail::whereIn('detail_recordid', $ServiceDetails)->get();
                $selected_details_value = [];
                $selected_details_types = [];
                foreach ($serviceDetailsData as $key => $v) {
                    if (!in_array($v->detail_type, $selected_details_types)) {
                        $selected_details_types[] = $v->detail_type;
                    }
                    $selected_details_value[$v->detail_type][] = $v->detail_recordid;
                }

                $service_phone1 = Phone::where('phone_recordid', '=', $phone1_recordid)->select('phone_number')->first();
                $service_phone2 = Phone::where('phone_recordid', '=', $phone2_recordid)->select('phone_number')->first();

                // $schedule_info_list = Schedule::select('schedule_recordid', 'opens', 'closes')->whereNotNull('opens')->orderBy('opens')->distinct()->get();
                $monday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['monday'])->orderBy('updated_at', 'desc')->first();
                $tuesday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['tuesday'])->orderBy('updated_at', 'desc')->first();
                $wednesday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['wednesday'])->orderBy('updated_at', 'desc')->first();
                $thursday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['thursday'])->orderBy('updated_at', 'desc')->first();
                $friday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['friday'])->orderBy('updated_at', 'desc')->first();
                $saturday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['saturday'])->orderBy('updated_at', 'desc')->first();
                $sunday = Schedule::where('services', $service->service_recordid)->whereRaw('FIND_IN_SET(?, weekday)', ['sunday'])->orderBy('updated_at', 'desc')->first();
                $holiday_schedules = Schedule::where('services', $service->service_recordid)->where('schedule_holiday', '1')->get();


                $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
                $all_contacts = Contact::orderBy('contact_name')->with('phone', 'service')->distinct()->get();
                $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'services', 'schedules')->distinct()->get();
                $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
                // $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();

                $all_phones = $service->organizations ? $service->organizations->contact : [];

                // location section
                $exclude_vocabulary = [];
                if ($layout) {
                    $exclude_vocabulary = explode(',', $layout->exclude_vocabulary);
                }
                $schedule_info_list = Schedule::select('schedule_recordid', 'opens', 'closes')->whereNotNull('opens')->where('opens', '!=', '')->orderBy('opens')->distinct()->get();
                $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
                $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
                $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
                $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

                $address_city_list = City::orderBy('city')->pluck('city', 'city');
                $address_states_list = State::orderBy('state')->pluck('state', 'state');
                $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');

                $location_alternate_name = [];
                $location_transporation = [];
                $location_service = [];
                $location_schedules = [];
                $location_description = [];
                $location_details = [];
                $location_accessibility = [];
                $location_accessibility_details = [];
                $external_identifier = [];
                $external_identifier_type = [];
                $accessesibility_url = [];
                $location_regions = [];
                foreach ($service_locations_data as $key => $locationData) {
                    $location_alternate_name[] = $locationData->location_alternate_name;
                    $location_transporation[] = $locationData->location_transportation;
                    $location_service[] = $locationData->services ? $locationData->services->pluck('service_recordid')->toArray() : [];
                    $location_schedules[] = $locationData->schedules ? $locationData->schedules->pluck('schedule_recordid')->toArray() : [];
                    $location_description[] = $locationData->location_description;
                    $location_details[] = $locationData->location_details;
                    $external_identifier[] = $locationData->external_identifier;
                    $external_identifier_type[] = $locationData->external_identifier_type;
                    $accessesibility_url[] = $locationData->accessesibility_url;
                    if ($locationData->accessibility_recordid) {
                        $location_accessibility[$key] = $locationData->accessibility_recordid;
                        $location_accessibility_details[$key] = $locationData->accessibility_details;
                    }
                    $location_regions[] = $locationData->regions ? $locationData->regions->pluck('id')->toArray() : [];
                }
                $location_alternate_name = json_encode($location_alternate_name);
                $location_transporation = json_encode($location_transporation);
                $location_service = json_encode($location_service);
                $location_schedules = json_encode($location_schedules);
                $location_description = json_encode($location_description);
                $location_details = json_encode($location_details);
                $location_accessibility = json_encode($location_accessibility);
                $location_accessibility_details = json_encode($location_accessibility_details);
                $location_regions = json_encode($location_regions);
                $external_identifier = json_encode($external_identifier);
                $external_identifier_type = json_encode($external_identifier_type);
                $accessesibility_url = json_encode($accessesibility_url);

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
                    if ($phonelocation->phones && count($phonelocation->phones) > 0) {
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
                $opens_location_monday_datas = [];
                $closes_location_monday_datas = [];
                $schedule_closed_monday_datas = [];
                $opens_location_tuesday_datas = [];
                $closes_location_tuesday_datas = [];
                $schedule_closed_tuesday_datas = [];
                $opens_location_wednesday_datas = [];
                $closes_location_wednesday_datas = [];
                $schedule_closed_wednesday_datas = [];
                $opens_location_thursday_datas = [];
                $closes_location_thursday_datas = [];
                $schedule_closed_thursday_datas = [];
                $opens_location_friday_datas = [];
                $closes_location_friday_datas = [];
                $schedule_closed_friday_datas = [];
                $opens_location_saturday_datas = [];
                $closes_location_saturday_datas = [];
                $schedule_closed_saturday_datas = [];
                $opens_location_sunday_datas = [];
                $closes_location_sunday_datas = [];
                $schedule_closed_sunday_datas = [];
                $location_holiday_start_dates = [];
                $location_holiday_end_dates = [];
                $location_holiday_open_ats = [];
                $location_holiday_close_ats = [];
                $location_holiday_closeds = [];
                $j = 0;
                foreach ($service_locations_data as $key => $value) {
                    $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    if ($value->schedules && !empty($value->schedules) && count($value->schedules) > 0) {
                        foreach ($value->schedules as $key1 => $schedule) {
                            if ($schedule->schedule_holiday == 1) {
                                $location_holiday_start_dates[$j][] = $schedule->dtstart;
                                $location_holiday_end_dates[$j][] = $schedule->until;
                                $location_holiday_open_ats[$j][] = $schedule->opens;
                                $location_holiday_close_ats[$j][] = $schedule->closes;
                                $location_holiday_closeds[$j][] = $schedule->schedule_closed;
                            } else {
                                for ($i = 0; $i < 7; $i++) {
                                    if ($schedule->weekday == $weekdays[$i]) {
                                        ${'opens_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->opens;
                                        ${'closes_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->closes;
                                        ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = $schedule->schedule_closed;
                                    }
                                }
                            }
                        }
                    } else {
                        $location_holiday_start_dates[$j][] = '';
                        $location_holiday_end_dates[$j][] = '';
                        $location_holiday_open_ats[$j][] = '';
                        $location_holiday_close_ats[$j][] = '';
                        $location_holiday_closeds[$j][] = '';
                        for ($i = 0; $i < 7; $i++) {
                            ${'opens_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                            ${'closes_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                            ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = '';
                        }
                    }
                    $j = $j + 1;
                }
                $opens_location_monday_datas = json_encode($opens_location_monday_datas);
                $closes_location_monday_datas = json_encode($closes_location_monday_datas);
                $schedule_closed_monday_datas = json_encode($schedule_closed_monday_datas);
                $opens_location_tuesday_datas = json_encode($opens_location_tuesday_datas);
                $closes_location_tuesday_datas = json_encode($closes_location_tuesday_datas);
                $schedule_closed_tuesday_datas = json_encode($schedule_closed_tuesday_datas);
                $opens_location_wednesday_datas = json_encode($opens_location_wednesday_datas);
                $closes_location_wednesday_datas = json_encode($closes_location_wednesday_datas);
                $schedule_closed_wednesday_datas = json_encode($schedule_closed_wednesday_datas);
                $opens_location_thursday_datas = json_encode($opens_location_thursday_datas);
                $closes_location_thursday_datas = json_encode($closes_location_thursday_datas);
                $schedule_closed_thursday_datas = json_encode($schedule_closed_thursday_datas);
                $opens_location_friday_datas = json_encode($opens_location_friday_datas);
                $closes_location_friday_datas = json_encode($closes_location_friday_datas);
                $schedule_closed_friday_datas = json_encode($schedule_closed_friday_datas);
                $opens_location_saturday_datas = json_encode($opens_location_saturday_datas);
                $closes_location_saturday_datas = json_encode($closes_location_saturday_datas);
                $schedule_closed_saturday_datas = json_encode($schedule_closed_saturday_datas);
                $opens_location_sunday_datas = json_encode($opens_location_sunday_datas);
                $closes_location_sunday_datas = json_encode($closes_location_sunday_datas);
                $schedule_closed_sunday_datas = json_encode($schedule_closed_sunday_datas);
                $location_holiday_start_dates = json_encode($location_holiday_start_dates);
                $location_holiday_end_dates = json_encode($location_holiday_end_dates);
                $location_holiday_open_ats = json_encode($location_holiday_open_ats);
                $location_holiday_close_ats = json_encode($location_holiday_close_ats);
                $location_holiday_closeds = json_encode($location_holiday_closeds);

                $service_status_list = ['Yes' => 'Yes', 'No' => 'No'];
                $phone_language_data = [];
                $phone_language_name = [];
                if ($service->phone) {
                    foreach ($service->phone()->with('type')->get() as $key => $value) {
                        $phone_language_data[$key] = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languageId = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languages = Language::whereIn('language_recordid', $languageId)->pluck('language')->unique()->toArray();

                        $phone_language_name[$key] = implode(', ', $languages);
                    }
                }
                $phone_language_data = json_encode($phone_language_data);

                $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
                $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

                $service_category_types = Taxonomy::orderBy('taxonomy_name')->whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId ? $serviceCategoryId->taxonomy_type_recordid : '')->pluck('taxonomy_name', 'taxonomy_recordid');
                $service_eligibility_types = Taxonomy::orderBy('taxonomy_name')->whereNull('taxonomy_parent_name')->where('taxonomy', $serviceEligibilityId ? $serviceEligibilityId->taxonomy_type_recordid : '')->pluck('taxonomy_name', 'taxonomy_recordid');


                $service_category_term_data = $serviceCategoryId ? $service->taxonomy()->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->get() : [];
                // $service_category_term_data = $service->taxonomy()->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->get();
                // ->whereNotNull('taxonomy_parent_name')

                $service_category_type_data = [];
                $categoryRemovableIds = [];
                $selected_category_ids = [];
                $parent_ids = [];
                foreach ($service_category_term_data as $value) {
                    if ($value->taxonomy_parent_name == null) {
                        $parent_ids[] = $value->taxonomy_recordid;
                        $service_category_type_data[] = $value;
                    }
                }
                foreach ($service_category_term_data as $value) {
                    if ($value->taxonomy_parent_name) {
                        $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $value->taxonomy_parent_name)->first();
                        if ($taxonomyParentData->taxonomy_parent_name) {
                            $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                            if ($taxonomyParentData->taxonomy_parent_name) {
                                $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                                if ($taxonomyParentData->taxonomy_parent_name) {
                                    $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                                    if ($taxonomyParentData->taxonomy_parent_name) {
                                        $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                                    }
                                }
                            }
                        }
                        if ($taxonomyParentData) {
                            // $taxonomyParentData->selectedTermId = $value->taxonomy_recordid;
                            // $service_category_type_data[] = $taxonomyParentData;
                            // dd(!in_array($value->taxonomy_recordid, $parent_ids), $value, $parent_ids);
                            // dd($parent_ids, $taxonomyParentData->taxonomy_recordid);
                            if (!in_array($taxonomyParentData->taxonomy_recordid, $parent_ids) && $taxonomyParentData->taxonomy_parent_name == null) {
                                $parent_ids[] = $taxonomyParentData->taxonomy_recordid;
                                $service_category_type_data[] = $taxonomyParentData;
                            }
                            foreach ($service_category_type_data as $key => $value1) {
                                // if (str_contains($taxonomyParentData->taxonomy_parent_name, $value1->taxonomy_recordid))
                                $selected_category_ids[$value1->taxonomy_recordid][] = $value->taxonomy_recordid;
                            }
                            // $taxonomyParentData->selectedTermName = $value->taxonomy_name;
                            // // $service_category_type_data[] = $taxonomyParentData;
                            // $categoryRemovableIds[] = $taxonomyParentData->taxonomy_recordid;
                        }
                    }
                }
                // $splicable_key = [];
                // $t = 0;
                // foreach ($service_category_type_data as $k => $v) {
                //     if (in_array($v->taxonomy_recordid, $categoryRemovableIds) && !isset($v->selectedTermId)) {
                //         // $splicable_key[] = $value->taxonomy_recordid;
                //         array_splice($service_category_type_data, $t, 1);
                //         $t--;
                //     }
                //     $t++;
                // }
                $service_eligibility_term_data = $serviceEligibilityId ? $service->taxonomy()->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->get() : [];
                $service_eligibility_type_data = [];
                $removableIds = [];
                $selected_eligibility_ids = [];
                $parent_ids = [];
                foreach ($service_eligibility_term_data as $value) {
                    if ($value->taxonomy_parent_name == null) {
                        $parent_ids[] = $value->taxonomy_recordid;
                        $service_eligibility_type_data[] = $value;
                    }
                }
                foreach ($service_eligibility_term_data as $value) {
                    if ($value->taxonomy_parent_name) {
                        $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $value->taxonomy_parent_name)->first();
                        if ($taxonomyParentData && $taxonomyParentData->taxonomy_parent_name) {
                            $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                            if ($taxonomyParentData && $taxonomyParentData->taxonomy_parent_name) {
                                $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                                if ($taxonomyParentData && $taxonomyParentData->taxonomy_parent_name) {
                                    $taxonomyParentData = Taxonomy::where('taxonomy_recordid', $taxonomyParentData->taxonomy_parent_name)->first();
                                }
                            }
                        }
                        if ($taxonomyParentData) {
                            // $taxonomyParentData->selectedTermId = $value->taxonomy_recordid;
                            // $taxonomyParentData->selectedTermName = $value->taxonomy_name;
                            if (!in_array($taxonomyParentData->taxonomy_recordid, $parent_ids) && $taxonomyParentData->taxonomy_parent_name == null) {
                                $parent_ids[] = $taxonomyParentData->taxonomy_recordid;
                                $service_eligibility_type_data[] = $taxonomyParentData;
                            }
                            // $removableIds[] = $taxonomyParentData->taxonomy_recordid;
                            foreach ($service_eligibility_type_data as $key => $value1) {
                                $selected_eligibility_ids[$value1->taxonomy_recordid][] = $value->taxonomy_recordid;
                            }
                        }
                        // } else {
                        //     $service_eligibility_type_data[] = $value;
                    }
                }

                $program = $service->program && count($service->program) > 0 ? $service->program[0] : '';

                $program_names = [];
                $program_descriptions = [];
                // $program_service_relationships = [];
                foreach ($service->program as $keys => $programData) {
                    $program_names[] = $programData->name;
                    $program_descriptions[] = $programData->alternate_name;
                    // $program_service_relationships[] = $programData->program_service_relationship;
                }

                $program_names = json_encode($program_names);
                $program_descriptions = json_encode($program_descriptions);

                $serviceAudits = $this->commonController->serviceSection($service);


                $all_programs = Program::with('organization')->distinct()->get();


                $help_text = Helptext::first();

                $conditions = Code::where('resource', 'Condition')->get()->groupBy('category');
                $goals = Code::where('resource', 'Goal')->get()->groupBy('category');
                $activities = Code::where('resource', 'Procedure')->get()->groupBy('category');

                $areas = $service->areas()->count() > 0 ? $service->areas->pluck('id')->toArray() : [];
                $fees = $service->fees()->count() > 0 ? $service->fees->pluck('id')->toArray() : [];

                $service_area = ServiceArea::pluck('name', 'id');
                $fee_options = FeeOption::pluck('fees', 'id');
                $regions = Region::pluck('region', 'id');

                // $codes = Code::whereNotNull('category')->where('category', '!=', '')->whereIn('resource', ['Condition', 'Goal', 'Procedure'])->orderBy('category')->pluck('category', 'id')->unique();
                $codes = CodeCategory::pluck('name', 'id');

                $selected_ids = $service->code_category_ids ? explode(',', $service->code_category_ids) : [];

                $procedure_grouping = $service->procedure_grouping ? unserialize($service->procedure_grouping) : [];

                $disposition_list = Disposition::pluck('name', 'id');
                $method_list = InteractionMethod::pluck('name', 'id');
                $serviceStatus = ServiceStatus::pluck('status', 'id');

                $languages = Language::pluck('language', 'id');
                $interpretation_services = InterpretationService::pluck('name', 'id');

                $requiredDocumentTypes = Detail::whereDetailType('Required Document')->pluck('detail_value', 'id');

                $accessibilities = Accessibility::pluck('accessibility', 'id');

                return view('frontEnd.services.edit', compact('service', 'map', 'service_address_street', 'service_address_city', 'service_address_state', 'service_address_postal_code', 'service_organization_list', 'service_location_list', 'service_phone1', 'service_phone2', 'service_contacts_list', 'service_taxonomy_list', 'service_details_list', 'location_info_list', 'contact_info_list', 'taxonomy_info_list', 'detail_info_list', 'ServiceSchedule', 'ServiceDetails', 'monday', 'tuesday', 'wednesday', 'friday', 'saturday', 'thursday', 'sunday', 'holiday_schedules', 'all_contacts', 'service_locations_data', 'all_locations', 'phone_languages', 'phone_type', 'location_alternate_name', 'location_transporation', 'location_service', 'location_schedules', 'location_description', 'location_details', 'contact_service', 'contact_department', 'service_info_list', 'address_states_list', 'address_city_list', 'schedule_info_list', 'contact_phone_numbers', 'contact_phone_extensions', 'contact_phone_types', 'contact_phone_languages', 'contact_phone_descriptions', 'location_phone_numbers', 'location_phone_extensions', 'location_phone_types', 'location_phone_languages', 'location_phone_descriptions', 'opens_location_monday_datas', 'closes_location_monday_datas', 'schedule_closed_monday_datas', 'opens_location_tuesday_datas', 'closes_location_tuesday_datas', 'schedule_closed_tuesday_datas', 'opens_location_wednesday_datas', 'closes_location_wednesday_datas', 'schedule_closed_wednesday_datas', 'opens_location_thursday_datas', 'closes_location_thursday_datas', 'schedule_closed_thursday_datas', 'opens_location_friday_datas', 'closes_location_friday_datas', 'schedule_closed_friday_datas', 'opens_location_saturday_datas', 'closes_location_saturday_datas', 'schedule_closed_saturday_datas', 'opens_location_sunday_datas', 'closes_location_sunday_datas', 'schedule_closed_sunday_datas', 'location_holiday_start_dates', 'location_holiday_end_dates', 'location_holiday_open_ats', 'location_holiday_close_ats', 'location_holiday_closeds', 'service_status_list', 'address_info_list', 'addressIds', 'serviceDetailsData', 'detail_types', 'phone_language_data', 'service_category_term_data', 'service_category_type_data', 'service_category_types', 'service_eligibility_types', 'service_eligibility_term_data', 'service_eligibility_type_data', 'program', 'all_programs', 'program_names', 'program_descriptions', 'serviceAudits', 'contactOrganization', 'all_phones', 'phone_language_name', 'conditions', 'goals', 'activities', 'service_codes', 'help_text', 'layout', 'service_area', 'fee_options', 'areas', 'fees', 'location_accessibility', 'location_accessibility_details', 'location_regions', 'regions', 'codes', 'selected_ids', 'procedure_grouping', 'selected_category_ids', 'selected_eligibility_ids', 'selected_details_value', 'selected_details_types', 'disposition_list', 'method_list', 'serviceStatus', 'languages', 'interpretation_services', 'requiredDocumentTypes', 'accessibilities', 'external_identifier', 'external_identifier_type', 'accessesibility_url'));
            } else {
                Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                return redirect('/');
            }
        } else {
            Session::flash('message', 'This record has been deleted.');
            Session::flash('status', 'warning');
            return redirect('services');
        }
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
        $this->validate($request, [
            'service_name' => 'required',
            'service_organization' => 'required',
            'service_description' => 'required',
        ]);
        if ($request->service_email) {
            $this->validate($request, [
                'service_email' => 'email'
            ]);
        }
        // dd($request);
        try {
            $service = Service::where('service_recordid', $id)->first();
            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_description = $request->service_description;
            $service->service_url = $request->service_url;
            $service->service_email = $request->service_email;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accreditations;
            $service->service_licenses = $request->service_licenses;
            $service->service_organization = $request->service_organization;
            $service->service_code = $request->service_code;
            $service->access_requirement = $request->access_requirement;

            $service->eligibility_description = $request->eligibility_description;
            $service->minimum_age = $request->minimum_age;
            $service->maximum_age = $request->maximum_age;
            $service->service_alert = $request->service_alert;
            $service->alert = $request->alert;
            $service->funding = $request->funding;
            $service->service_language = $request->service_language ? implode(',', $request->service_language) : '';
            $service->service_interpretation = $request->service_interpretation;
            $this->saveRequiredDocument($request, $id);
            $service_area = [];
            if ($request->service_area)
                $service_area = $request->service_area;

            $service_area = is_array($service_area) ? array_values(array_filter($service_area)) : [];
            $service->areas()->sync($service_area);
            $fee_option = [];
            if ($request->fee_option)
                $fee_option = $request->fee_option;

            $fee_option = is_array($fee_option) ? array_values(array_filter($fee_option)) : [];
            $service->fees()->sync($request->fee_option);

            $servicePrograms = $this->saveServiceProgram($request, $service->service_recordid);

            $service->service_program = '';
            if (count($servicePrograms) > 0) {
                $service->service_program = implode(',', $servicePrograms);
            }
            $old_procedure_grouping = $service->procedure_grouping ? unserialize($service->procedure_grouping) : [];
            $new_procedure_grouping = [];

            if ($request->procedure_grouping && is_array($request->procedure_grouping) && count($request->procedure_grouping) > 0) {
                foreach ($request->procedure_grouping as $key => $procedure_grouping) {
                    $new_procedure_grouping[] = $procedure_grouping;
                    $code_ids = explode('|', $procedure_grouping);
                    if (count($code_ids) > 0 && isset($code_ids[1])) {
                        $code = Code::where('code_id', $code_ids[1])->first();
                        if ($code) {
                            $code_ledger = CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->first();
                            if ($code_ledger) {
                                CodeLedger::whereId($code_ledger->id)->update([
                                    'rating' => 3,
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            } else {
                                CodeLedger::create([
                                    'rating' => 3,
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                    }
                }
                $service->procedure_grouping = serialize($request->procedure_grouping);
            }
            if (count($new_procedure_grouping) > 0) {
                if (count($old_procedure_grouping) > 0) {
                    $diff_array = array_diff($old_procedure_grouping, $new_procedure_grouping);
                    // dd($request, $old_procedure_grouping, $new_procedure_grouping, $diff_array);
                    foreach ($diff_array as $key => $procedure_grouping) {
                        $code_ids = explode('|', $procedure_grouping);
                        if (count($code_ids) > 0 && isset($code_ids[1])) {
                            $code = Code::where('code_id', $code_ids[1])->first();
                            if ($code)
                                CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->delete();
                        }
                    }
                }
            } else {
                if (count($old_procedure_grouping) > 0) {
                    foreach ($old_procedure_grouping as $key => $procedure_grouping) {
                        $code_ids = explode('|', $procedure_grouping);
                        if (count($code_ids) > 0 && isset($code_ids[1])) {
                            $code = Code::where('code_id', $code_ids[1])->first();
                            if ($code)
                                CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->delete();
                        }
                    }
                }
            }
            $servicePrograms = is_array($servicePrograms) ? array_values(array_filter($servicePrograms)) : [];
            $service->program()->sync($servicePrograms);
            // service code section
            $service_codes = [];
            $old_service_codes = $service->SDOH_code ? explode(',', $service->SDOH_code) : [];
            if ($request->code_category_ids && count($request->code_category_ids) > 0) {
                $service->code_category_ids = implode(',', $request->code_category_ids);
            } else {
                $service->code_category_ids = '';
            }

            // CodeLedger::where('service_recordid', $id)->delete();
            if ($request->code_conditions) {
                $code_conditions = is_array($request->code_conditions) ? array_values(array_filter($request->code_conditions)) : [];
                foreach ($code_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            $code_ledger = CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->first();
                            if ($code_ledger) {
                                CodeLedger::whereId($code_ledger->id)->update([
                                    'rating' => $ids[0],
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            } else {
                                CodeLedger::create([
                                    'rating' => $ids[0],
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                    }
                }
                // $service_codes = array_merge($service_codes, array_map('intval', $request->code_conditions));
            }

            if ($request->goal_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->goal_conditions));
                $goal_conditions = is_array($request->goal_conditions) ? array_values(array_filter($request->goal_conditions)) : [];
                foreach ($goal_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            $code_ledger = CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->first();
                            if ($code_ledger) {
                                CodeLedger::whereId($code_ledger->id)->update([
                                    'rating' => $ids[0],
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            } else {
                                CodeLedger::create([
                                    'rating' => $ids[0],
                                    'service_recordid' => $id,
                                    'organization_recordid' => $request->service_organization,
                                    'SDOH_code' => $code->id,
                                    'resource' => $code->resource,
                                    'description' => $code->description,
                                    'code_type' => $code->code_system,
                                    'code' => $code->code,
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                    }
                }
            }

            if ($request->activities_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->activities_conditions));
                $activities_conditions = is_array($request->activities_conditions) ? array_values(array_filter($request->activities_conditions)) : [];
                foreach ($activities_conditions as $key => $code_id) {
                    // $ids = explode('_', $code_id);
                    $code = Code::whereId($code_id)->first();
                    if ($code) {
                        $service_codes[] = $code_id;
                        $code_ledger = CodeLedger::where('service_recordid', $id)->where('SDOH_code', $code->id)->first();
                        if ($code_ledger) {
                            CodeLedger::whereId($code_ledger->id)->update([
                                'rating' => 3,
                                'service_recordid' => $id,
                                'organization_recordid' => $request->service_organization,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        } else {
                            CodeLedger::create([
                                'rating' => 3,
                                'service_recordid' => $id,
                                'organization_recordid' => $request->service_organization,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
            }
            if (count($service_codes) > 0) {
                if (count($old_service_codes) > 0) {
                    // if (count($old_service_codes) > count($service_codes)) {
                    //     $diff_array = array_diff($old_service_codes, $service_codes);
                    // } else if (count($old_service_codes) < count($service_codes)) {
                    //     $diff_array = array_diff($service_codes, $old_service_codes);
                    // } else {
                    $diff_array = array_diff($old_service_codes, $service_codes);
                    // }
                    CodeLedger::where('service_recordid', $id)->whereIn('SDOH_code', $diff_array)->delete();
                }
                $service->SDOH_code = implode(',', $service_codes);
            } else {
                $service->SDOH_code = '';
                CodeLedger::where('service_recordid', $id)->delete();
            }
            // $service_codes = array_values(array_filter($service_codes));

            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0]) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0]) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0]) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0]) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0]) : [];

                $external_identifier = $request->external_identifier && count($request->external_identifier) > 0 ? json_decode($request->external_identifier[0]) : [];
                $external_identifier_type = $request->external_identifier_type && count($request->external_identifier_type) > 0 ? json_decode($request->external_identifier_type[0]) : [];
                $accessesibility_url = $request->accessesibility_url && count($request->accessesibility_url) > 0 ? json_decode($request->accessesibility_url[0]) : [];
                // accessibility
                $location_accessibility = $request->location_accessibility && count($request->location_accessibility) > 0 ? json_decode($request->location_accessibility[0], true) : [];

                $location_accessibility_details = $request->location_accessibility_details && count($request->location_accessibility_details) > 0 ? json_decode($request->location_accessibility_details[0], true) : [];
                $location_regions = $request->location_regions && count($request->location_regions) > 0 ? json_decode($request->location_regions[0], true) : [];

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
                    $opens_location_monday_datas = $request->opens_location_monday_datas ? json_decode($request->opens_location_monday_datas, true) : [];
                    $closes_location_monday_datas = $request->closes_location_monday_datas ? json_decode($request->closes_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_location_tuesday_datas = $request->opens_location_tuesday_datas ? json_decode($request->opens_location_tuesday_datas, true) : [];
                    $closes_location_tuesday_datas = $request->closes_location_tuesday_datas ? json_decode($request->closes_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_location_wednesday_datas = $request->opens_location_wednesday_datas ? json_decode($request->opens_location_wednesday_datas, true) : [];
                    $closes_location_wednesday_datas = $request->closes_location_wednesday_datas ? json_decode($request->closes_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_location_thursday_datas = $request->opens_location_thursday_datas ? json_decode($request->opens_location_thursday_datas, true) : [];
                    $closes_location_thursday_datas = $request->closes_location_thursday_datas ? json_decode($request->closes_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_location_friday_datas = $request->opens_location_friday_datas ? json_decode($request->opens_location_friday_datas, true) : [];
                    $closes_location_friday_datas = $request->closes_location_friday_datas ? json_decode($request->closes_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_location_saturday_datas = $request->opens_location_saturday_datas ? json_decode($request->opens_location_saturday_datas, true) : [];
                    $closes_location_saturday_datas = $request->closes_location_saturday_datas ? json_decode($request->closes_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_location_sunday_datas = $request->opens_location_sunday_datas ? json_decode($request->opens_location_sunday_datas, true) : [];
                    $closes_location_sunday_datas = $request->closes_location_sunday_datas ? json_decode($request->closes_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds ? json_decode($request->location_holiday_closeds, true) : [];

                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = $location_recordid;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                        $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                        $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                        $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;
                        $location->external_identifier = isset($external_identifier[$i]) ? $external_identifier[$i] : null;
                        $location->external_identifier_type = isset($external_identifier_type[$i]) ? $external_identifier_type[$i] : null;
                        $location->accessesibility_url = isset($accessesibility_url[$i]) ? $accessesibility_url[$i] : null;

                        // accessesibility

                        if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                            // Accessibility::create([
                            //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                            //     'accessibility' => $location_accessibility[$i],
                            //     'accessibility_details' => $location_accessibility_details[$i],
                            //     'accessibility_location' => $location_recordid
                            // ]);
                            $location->accessibility_recordid = $location_accessibility[$i];
                            $location->accessibility_details = $location_accessibility_details[$i];
                        }
                        if (!empty($location_regions[$i])) {
                            $location_regions[$i] = is_array($location_regions[$i]) ? array_values(array_filter($location_regions[$i])) : [];
                            $location->regions()->sync($location_regions[$i]);
                        }

                        // if ($location_service) {
                        //     $location->location_services = join(',', $location_service[$i]);
                        // } else {
                        //     $location->location_services = '';
                        // }
                        // $location->services()->sync($location_service[$i]);

                        // $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
                        // if ($location_schedules[$i]) {
                        //     $location->location_schedule = join(',', $location_schedules[$i]);
                        // } else {
                        //     $location->location_schedule = '';
                        // }
                        // $location->schedules()->sync($location_schedules[$i]);
                        // location address
                        $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                        if ($address_info) {
                            $location->location_address = $address_info->address_recordid;
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
                            $location->location_address = $new_address_recordid;
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
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
                                    // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {

                                $schedules = Schedule::where('locations', $location_recordid)->where('weekday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                    $schedules->locations = $location_recordid;
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = ${'opens_location_' . $weekdays[$s] . '_datas'}[$i];
                                    $schedules->closes = ${'closes_location_' . $weekdays[$s] . '_datas'}[$i];
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
                            Schedule::where('locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens = $request->holiday_open_at[$hs];
                                //     $schedules->closes = $request->closes[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes = $location_holiday_close_ats[$i][$hs];
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
                        $schedule_locations = is_array($schedule_locations) ? array_values(array_filter($schedule_locations)) : [];
                        $location->schedules()->sync($schedule_locations);

                        $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];

                        $location->phones()->sync($location_phone_recordid_list);

                        $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                        $location->location_phones = '';
                        $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                        $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];
                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                            $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                            $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                            $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;
                            $location->external_identifier = isset($external_identifier[$i]) ? $external_identifier[$i] : null;
                            $location->external_identifier_type = isset($external_identifier_type[$i]) ? $external_identifier_type[$i] : null;
                            $location->accessesibility_url = isset($accessesibility_url[$i]) ? $accessesibility_url[$i] : null;


                            // accessesibility
                            if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                                // Accessibility::updateOrCreate([
                                //     'accessibility_location' => $request->location_recordid[$i]
                                // ], [
                                //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                                //     'accessibility' => $location_accessibility[$i],
                                //     'accessibility_details' => $location_accessibility_details[$i],
                                // ]);
                                $location->accessibility_recordid = $location_accessibility[$i];
                                $location->accessibility_details = $location_accessibility_details[$i];
                            }
                            if (isset($location_regions[$i])) {
                                $location_regions[$i] = is_array($location_regions[$i]) ? array_values(array_filter($location_regions[$i])) : [];
                                $location->regions()->sync($location_regions[$i]);
                            }

                            // if ($location_service) {
                            //     $location->location_services = join(',', $location_service[$i]);
                            // } else {
                            //     $location->location_services = '';
                            // }
                            // $location->services()->sync($location_service[$i]);

                            // $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
                            // if ($location_schedules[$i]) {
                            //     $location->location_schedule = join(',', $location_schedules[$i]);
                            // } else {
                            //     $location->location_schedule = '';
                            // }
                            // $location->schedules()->sync($location_schedules[$i]);
                            // location address
                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $address_info->address_recordid;
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
                                $location->location_address = $new_address_recordid;
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) ? implode(',', $location_phone_languages[$i][$p]) : '';
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
                                        // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            // schedule section
                            $schedule_locations = [];
                            if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('locations', $location->location_recordid)->where('weekday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                        $schedules->locations = $location->location_recordid;
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                Schedule::where('locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens = $request->holiday_open_at[$hs];
                                    //     $schedules->closes = $request->closes[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes = $location_holiday_close_ats[$i][$hs];
                                    if ($location_holiday_closeds[$i][$hs] == 1) {
                                        $schedules->schedule_closed = '1';
                                    }
                                    $schedules->schedule_holiday = '1';
                                    $schedules->save();
                                    $schedule_locations[] = $schedule_recordid;
                                    // }
                                }
                            }
                            $schedule_locations = is_array($schedule_locations) ? array_values(array_filter($schedule_locations)) : [];
                            $location->location_schedule = join(',', $schedule_locations);

                            $location->schedules()->sync($schedule_locations);

                            $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                            $location->phones()->sync($location_phone_recordid_list);

                            $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                            $location->location_phones = '';
                            $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';
                            $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];

                            $location->address()->sync($location_address_recordid_list);
                            $location->save();
                        }
                        array_push($service_locations, $request->location_recordid[$i]);
                    }
                }
            } else {
                if ($service->service_locations) {
                    $service->service_locations = join(',', $service_locations);
                }
            }
            $service_locations = is_array($service_locations) ? array_values(array_filter($service_locations)) : [];
            $service->locations()->sync($service_locations);
            // contact section
            $service_contacts = [];
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                $contact_visibility = $request->contact_visibility && count($request->contact_visibility) > 0 ? $request->contact_visibility : [];
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
                        $contact->visibility = $contact_visibility[$i];
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
                            $contact->visibility = $contact_visibility[$i];
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
                                        $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) ? implode(',', $contact_phone_languages[$i][$p]) : '';
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

            $service_contacts = is_array($service_contacts) ? array_values(array_filter($service_contacts)) : [];
            $service->contact()->sync($service_contacts);

            $service_taxonomies = [];

            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if (isset($service_category_term_type[$key]) && isset($service_category_type[$key]) && isset($service_category_term[$key])) {
                        if ($service_category_term_type[$key] == 'new') {
                            $taxonomy = new Taxonomy();
                            $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                            $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                            $taxonomy->taxonomy_name = $service_category_term[$key];
                            $taxonomy->taxonomy_parent_name = $value;
                            $taxonomy->taxonomy_vocabulary = 'Service Category';
                            $taxonomy->status = 'Unpublished';
                            $taxonomy->created_by = Auth::id();
                            $taxonomy->save();
                            $service_taxonomies[] = $taxonomy_recordid;
                            $service_taxonomies[] = $value;
                        } else {
                            $service_taxonomies[] = $service_category_type[$key];
                            // $service_taxonomies[] = $service_category_term[$key];
                            $service_taxonomies = array_merge($service_taxonomies, $service_category_term[$key]);
                        }
                    } else {
                        if (isset($service_category_type[$key])) {
                            $service_taxonomies[] = $service_category_type[$key];
                        }
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = array_filter($request->service_eligibility_type);
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if (isset($service_eligibility_type[$key]) && isset($service_eligibility_term[$key]) && isset($service_eligibility_term_type[$key])) {
                        if ($service_eligibility_term_type[$key] == 'new') {
                            $taxonomy = new Taxonomy();
                            $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                            $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                            $taxonomy->taxonomy_name = $service_eligibility_term[$key][0] ?? '';
                            $taxonomy->taxonomy_parent_name = $value;
                            $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                            $taxonomy->status = 'Unpublished';
                            $taxonomy->created_by = Auth::id();
                            $taxonomy->save();
                            $service_taxonomies[] = $taxonomy_recordid;
                            $service_taxonomies[] = $value;
                        } else {
                            $service_taxonomies[] = $service_eligibility_type[$key];
                            // $service_taxonomies[] = $service_eligibility_term[$key];
                            $service_taxonomies = array_merge($service_taxonomies, $service_eligibility_term[$key]);
                        }
                    } else {
                        if (isset($service_eligibility_type[$key])) {
                            $service_taxonomies[] = $service_eligibility_type[$key];
                        }
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = is_array($service_taxonomies) ? array_unique(array_values(array_filter($service_taxonomies))) : [];
                $service->service_taxonomy = join(',', $service_taxonomies);
            }
            $service->taxonomy()->sync($service_taxonomies);

            $detail_ids = [];
            if ($request->detail_type && $request->detail_type[0] != null) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            // $detail_ids[] = $detail_term[$key];
                            $detail_ids = $detail_term;
                        }
                    }
                }
            }
            $service->service_details = '';
            if (count($detail_ids) > 0) {
                // $detail_ids = array_filter($detail_ids);
                $detail_ids = is_array($detail_ids) ? array_values(array_filter($detail_ids)) : [];
                $service->service_details = join(',', $detail_ids);
            }
            $service->details()->sync($detail_ids);

            // service phone section

            $phone_recordid_list = [];
            if ($request->service_phones) {
                $service_phone_number_list = $request->service_phones;
                $service_phone_extension_list = $request->phone_extension;
                $service_phone_type_list = $request->phone_type;
                $service_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $service_phone_description_list = $request->phone_description;
                $service_main_priority_list = $request->main_priority;
                for ($i = 0; $i < count($service_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $service_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $service_phone_number_list[$i];
                        $phone_info->phone_extension = $service_phone_extension_list[$i];
                        $phone_info->phone_type = $service_phone_type_list[$i];
                        $phone_info->phone_language = isset($service_phone_language_list[$i]) && count($service_phone_language_list) > 0 && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = isset($service_phone_language_list[$i]) && count($service_phone_language_list) > 0 && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            } else {
                if ($service->service_phones) {
                    $service->service_phones = '';
                }
            }
            $phone_recordid_list = is_array($phone_recordid_list) ? array_values(array_filter($phone_recordid_list)) : [];
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

            if (!empty($service_address_info)) {
                $service_address_info = is_array($service_address_info) ? array_values(array_filter($service_address_info)) : [];
                $service->service_address = implode(',', $service_address_info);
                $service->address()->sync($service_address_info);
            }
            Schedule::where('services', $service->service_recordid)->delete();

            $schedule_services = $this->saveServiceSchedule($request, $service);

            // && $request->holiday_open_at && $request->holiday_close_at && isset($request->holiday_open_at[0]) && isset($request->holiday_close_at[0])
            if ($request->holiday_start_date && $request->holiday_end_date && isset($request->holiday_start_date[0]) && isset($request->holiday_end_date[0])) {
                Schedule::where('services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    // $schedules =
                    // if($schedules){
                    //     $schedules->dtstart = $request->holiday_start_date[$i];
                    //     $schedules->until = $request->holiday_end_date[$i];
                    //     $schedules->opens = $request->holiday_open_at[$i];
                    //     $schedules->closes = $request->closes[$i];
                    //     if(in_array(($i+1),$request->schedule_closed)){
                    //         $schedules->schedule_closed = $i+1;
                    //     }
                    //     $schedules->save();
                    //     $schedule_services[] = $schedules->schedule_recordid;
                    // }else{
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->services = $service->service_recordid;
                    $schedules->dtstart = isset($request->holiday_start_date[$i]) ? $request->holiday_start_date[$i] : null;
                    $schedules->until = isset($request->holiday_end_date[$i]) ? $request->holiday_end_date[$i] : null;
                    $schedules->opens = isset($request->holiday_open_at[$i]) ? $request->holiday_open_at[$i] : null;
                    $schedules->closes = isset($request->holiday_close_at[$i]) ? $request->holiday_close_at[$i] : null;
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    if ($request->holiday_open_24_hours && in_array(($i + 1), $request->holiday_open_24_hours)) {
                        $schedules->open_24_hours = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            $old_service_schedule = explode(',', $service->service_schedule);
            // dd($schedule_services, $service->service_schedule, array_diff($old_service_schedule, $schedule_services), $old_service_schedule);
            $schedule_services = is_array($schedule_services) ? array_values(array_filter($schedule_services)) : [];
            if (count($schedule_services)) {
                $service->service_schedule = join(',', $schedule_services);
            }
            $service->schedules()->sync($schedule_services);

            // interaction section
            if ($request->service_notes == '1') {
                $session = new SessionData();
                $new_recordid = SessionData::max('session_recordid') + 1;
                $session->session_recordid = $new_recordid;
                $user = Auth::user();
                $date_time = date("Y-m-d h:i:sa");
                $session->session_name = 'session' . $new_recordid;
                $session->session_service = $service->service_recordid;
                $session->session_method = $request->interaction_method;
                $session->session_disposition = $request->interaction_disposition;
                $session->session_notes = $request->interaction_notes;
                $session->service_status = $request->service_status;
                $session->session_records_edited = $request->interaction_records_edited;

                if ($user) {
                    $session->session_performed_by = $user->id;
                }

                $session->session_performed_at = Carbon::now();
                $session->session_edits = '0';
                $session->save();
                // add new interaction session
                $interaction = new SessionInteraction();
                $session_recordid = $new_recordid;
                $interaction->interaction_session = $session_recordid;

                $new_recordid = SessionInteraction::max('interaction_recordid') + 1;
                $interaction->interaction_recordid = $new_recordid;

                $interaction->interaction_method = $request->interaction_method;
                $interaction->interaction_disposition = $request->interaction_disposition;
                $interaction->interaction_notes = $request->interaction_notes;
                $interaction->interaction_records_edited = $request->interaction_records_edited;
                $date_time = date("Y-m-d h:i:sa");
                $interaction->interaction_timestamp = $date_time;

                $interaction->save();

                $service->service_status = $request->service_status;
            }
            $service->updated_by = Auth::id();

            $service->updated_at = date("Y-m-d H:i:s");
            $service->save();

            // if ($service->wasChanged()) {
            //     $service->updated_at = date("Y-m-d H:i:s");
            //     $service->save();
            // }

            // $service_organization = $request->service_organization;
            // $organization = Organization::where('organization_recordid', '=', $service_organization)->select('organization_recordid', 'updated_at')->first();
            // if ($organization) {
            //     $organization->updated_at = date("Y-m-d H:i:s");
            //     $organization->save();
            // }
            Session::flash('message', 'Service updated successfully!');
            Session::flash('status', 'success');
            return redirect('services/' . $id);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in update service : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
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

    public function airtable($access_token, $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->access_token = $access_token;
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

            $airtable = new Airtable(array(
                'access_token' => $access_token,
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

                    if (isset($record['fields']['status'])) {
                        $service_status = ServiceStatus::firstOrCreate(
                            ['status' => $record['fields']['status']],
                            ['status' => $record['fields']['status'], 'created_by' => Auth::id()]
                        );
                        $service->service_status = $service_status->id;
                    }
                    // $service->service_status = isset($record['fields']['status']) ? $record['fields']['status'] : null;


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
            Log::error('Error in sync service v1 : ' . $th);
        }
    }

    public function airtable_v2($access_token, $base_url)
    {
        try {

            // Service::truncate();
            // ServiceLocation::truncate();
            // ServiceAddress::truncate();
            // ServicePhone::truncate();
            // ServiceDetail::truncate();
            // ServiceOrganization::truncate();
            // ServiceContact::truncate();
            // ServiceTaxonomy::truncate();
            // ServiceSchedule::truncate();

            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('services');
            $size = '';
            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_service = Service::where('service_recordid', $recordId)->where('service_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_service == null) {

                        $service = new Service();
                        $service->service_recordid = $strtointclass->string_to_int($record['id']);

                        $service->service_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        if (isset($record['fields']['organizations'])) {
                            foreach ($record['fields']['organizations'] as $key => $value) {
                                if ($key == 0) {
                                    $service_organization = new ServiceOrganization();
                                    $service_organization->service_recordid = $service->service_recordid;
                                    $service_organization->organization_recordid = $strtointclass->string_to_int($value);
                                    $service_organization->save();
                                    $serviceorganization = $strtointclass->string_to_int($value);

                                    $service->service_organization = $serviceorganization;
                                }
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
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Services')->first();
            $airtable->records = Service::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Service: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->serviceDataService->service_airtable_v3($access_token, $base_url);
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
            Log::error('Error in service csv : ' . $th);
        }
    }

    public function export_services()
    {
        try {
            // return Excel::download(new ServiceExport, 'export.csv');
            return;
        } catch (\Throwable $th) {
            Log::error('Error in export service : ' . $th);
            return redirect('services');
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
        $layout = Layout::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'services', 'schedules')->distinct()->get();;
        $facility_info_list = Location::select('location_recordid', 'location_name')->orderBy('location_recordid')->distinct()->get();

        $service_status_list = ['Yes' => 'Yes', 'No' => 'No'];

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens', 'closes')->whereNotNull('opens')->where('opens', '!=', '')->orderBy('opens')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();

        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');

        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');
        // $all_contacts = Contact::orderBy('contact_recordid')->with('phone')->distinct()->get();
        $all_contacts = $organization->contact()->orderBy('contact_name')->with('phone')->distinct()->get();

        $address_city_list = City::orderBy('city')->pluck('city', 'city');
        $address_states_list = State::orderBy('state')->pluck('state', 'state');

        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');

        $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
        $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

        $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);

        $conditions = Code::where('resource', 'Condition')->get()->groupBy('category');
        $goals = Code::where('resource', 'Goal')->get()->groupBy('category');
        $activities = Code::where('resource', 'Procedure')->get()->groupBy('category');

        $help_text = Helptext::first();

        $service_area = ServiceArea::pluck('name', 'id');
        $fee_options = FeeOption::pluck('fees', 'id');

        $regions = Region::pluck('region', 'id');

        $codes = CodeCategory::pluck('name', 'id');
        $selected_ids = [];

        $procedure_grouping = [];

        $languages = Language::pluck('language', 'id');
        $interpretation_services = InterpretationService::pluck('name', 'id');
        $requiredDocumentTypes = Detail::whereDetailType('Required Document')->pluck('detail_value', 'id');
        $all_programs = Program::with('organization')->distinct()->get();

        $accessibilities = Accessibility::pluck('accessibility', 'id');

        return view('frontEnd.services.service-create-in-organization', compact('map', 'organization', 'facility_info_list', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'address_info_list', 'phone_languages', 'phone_type', 'all_contacts', 'all_locations', 'address_states_list', 'address_city_list', 'detail_types', 'service_eligibility_types', 'service_category_types', 'all_phones', 'phone_language_data', 'conditions', 'goals', 'activities', 'help_text', 'layout', 'service_area', 'fee_options', 'regions', 'codes', 'selected_ids', 'procedure_grouping', 'languages', 'interpretation_services', 'requiredDocumentTypes', 'all_programs', 'accessibilities'));
    }

    public function create_in_facility($id)
    {
        $map = Map::find(1);
        $layout = Layout::find(1);
        $facility = Location::where('location_recordid', '=', $id)->first();

        $service_status_list = ['Yes' => 'Yes', 'No' => 'No'];

        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens', 'closes')->whereNotNull('opens')->where('opens', '!=', '')->orderBy('opens')->distinct()->get();

        $contact_info_list = Contact::select('contact_recordid', 'contact_name')->orderBy('contact_recordid')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');

        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');
        $all_contacts = Contact::orderBy('contact_name')->with('phone')->distinct()->get();
        $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'schedules')->distinct()->get();


        $address_city_list = City::orderBy('city')->pluck('city', 'city');
        $address_states_list = State::orderBy('state')->pluck('state', 'state');

        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');

        $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
        $serviceEligibilityId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Eligibility')->first();

        $service_category_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $service_eligibility_types = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceEligibilityId->taxonomy_type_recordid)->pluck('taxonomy_name', 'taxonomy_recordid');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);

        $conditions = Code::where('resource', 'Condition')->get()->groupBy('category');
        $goals = Code::where('resource', 'Goal')->get()->groupBy('category');
        $activities = Code::where('resource', 'Procedure')->get()->groupBy('category');

        $help_text = Helptext::first();

        $service_area = ServiceArea::pluck('name', 'id');
        $fee_options = FeeOption::pluck('fees', 'id');

        $regions = Region::pluck('region', 'id');

        $codes = CodeCategory::pluck('name', 'id');
        $selected_ids = [];

        $procedure_grouping = [];

        $languages = Language::pluck('language', 'id');
        $interpretation_services = InterpretationService::pluck('name', 'id');
        $requiredDocumentTypes = Detail::whereDetailType('Required Document')->pluck('detail_value', 'id');
        $all_programs = Program::with('organization')->distinct()->get();

        return view('frontEnd.services.service-create-in-facility', compact('map', 'facility', 'service_status_list', 'taxonomy_info_list', 'schedule_info_list', 'contact_info_list', 'detail_info_list', 'phone_languages', 'phone_type', 'all_contacts', 'all_locations', 'address_states_list', 'address_city_list', 'detail_types', 'service_category_types', 'service_eligibility_types', 'all_phones', 'phone_language_data', 'conditions', 'goals', 'activities', 'help_text', 'layout', 'service_area', 'fee_options', 'regions', 'codes', 'selected_ids', 'procedure_grouping', 'languages', 'interpretation_services', 'requiredDocumentTypes', 'all_programs'));
    }

    public function add_new_service_in_organization(Request $request)
    {
        try {
            $this->validate($request, [
                'service_name' => 'required',
            ]);
            if ($request->service_email) {
                $this->validate($request, [
                    'service_email' => 'email'
                ]);
            }
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
            if ($service_organization) {
                $service_organization->updated_by = Auth::id();
                $service_organization->updated_at = Carbon::now();
                $service_organization->save();
            }
            $service_organization_id = $service_organization["organization_recordid"];
            $service->service_organization = $service_organization_id;

            $service->service_name = $request->service_name;
            $service->service_alternate_name = $request->service_alternate_name;
            $service->service_url = $request->service_url;
            // $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            // $service->service_status = '';
            // if ($request->service_status = 'Yes') {
            //     $service->service_status = 'Yes';
            // }
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;
            $service->access_requirement = $request->access_requirement;

            $service->access_requirement = $request->access_requirement;

            $this->saveRequiredDocument($request, $service_recordid);

            $service->eligibility_description = $request->eligibility_description;
            $service->minimum_age = $request->minimum_age;
            $service->maximum_age = $request->maximum_age;
            $service->service_alert = $request->service_alert;
            $service->service_language = $request->service_language ? implode(',', $request->service_language) : '';
            $service->service_interpretation = $request->service_interpretation;

            if ($request->service_area)
                $service->areas()->sync($request->service_area);
            if ($request->fee_option)
                $service->fees()->sync($request->fee_option);

            $servicePrograms = $this->saveServiceProgram($request, $service_recordid);

            $service->service_program = '';
            if (count($servicePrograms) > 0) {
                $service->service_program = implode(',', $servicePrograms);
            }
            $service->program()->sync($servicePrograms);

            if ($request->procedure_grouping && is_array($request->procedure_grouping) && count($request->procedure_grouping) > 0) {
                foreach ($request->procedure_grouping as $key => $procedure_grouping) {
                    $code_ids = explode('|', $procedure_grouping);
                    if (count($code_ids) > 0 && isset($code_ids[1])) {
                        $code = Code::where('code_id', $code_ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => 3,
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                $service->procedure_grouping = serialize($request->procedure_grouping);
            }

            // service code section

            if ($request->code_category_ids && count($request->code_category_ids) > 0) {
                $service->code_category_ids = implode(',', $request->code_category_ids);
            } else {
                $service->code_category_ids = '';
            }

            $service_codes = [];
            if ($request->code_conditions) {
                $code_conditions = is_array($request->code_conditions) ? array_values(array_filter($request->code_conditions)) : [];
                foreach ($code_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                // $service_codes = array_merge($service_codes, array_map('intval', $request->code_conditions));
            }
            if ($request->goal_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->goal_conditions));
                $goal_conditions = is_array($request->goal_conditions) ? array_values(array_filter($request->goal_conditions)) : [];
                foreach ($goal_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
            }
            if ($request->activities_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->activities_conditions));
                $activities_conditions = is_array($request->activities_conditions) ? array_values(array_filter($request->activities_conditions)) : [];
                foreach ($activities_conditions as $key => $code_id) {
                    // $ids = explode('_', $code_id);
                    $code = Code::whereId($code_id)->first();
                    if ($code) {
                        $service_codes[] = $code_id;
                        CodeLedger::create([
                            'rating' => 3,
                            'service_recordid' => $new_recordid,
                            'organization_recordid' => $service_organization_id,
                            'SDOH_code' => $code->id,
                            'resource' => $code->resource,
                            'description' => $code->description,
                            'code_type' => $code->code_system,
                            'code' => $code->code,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
            if (count($service_codes) > 0) {
                $service->SDOH_code = implode(',', $service_codes);
            }

            // if ($request->service_locations) {
            //     $service->service_locations = join(',', $request->service_locations);
            // } else {
            //     $service->service_locations = '';
            // }
            // $service->locations()->sync($request->service_locations);
            // location section
            // location section
            $service_locations = [];
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                // $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0]) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                // accessibility
                $location_accessibility = $request->location_accessibility && count($request->location_accessibility) > 0 ? json_decode($request->location_accessibility[0], true) : [];
                $location_accessibility_details = $request->location_accessibility_details && count($request->location_accessibility_details) > 0 ? json_decode($request->location_accessibility_details[0], true) : [];
                $location_regions = $request->location_regions && count($request->location_regions) > 0 ? json_decode($request->location_regions[0], true) : [];

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
                    $opens_location_monday_datas = $request->opens_location_monday_datas ? json_decode($request->opens_location_monday_datas, true) : [];
                    $closes_location_monday_datas = $request->closes_location_monday_datas ? json_decode($request->closes_location_monday_datas, true) : [];
                    $schedule_closed_monday_datas = $request->schedule_closed_monday_datas ? json_decode($request->schedule_closed_monday_datas, true) : [];

                    $opens_location_tuesday_datas = $request->opens_location_tuesday_datas ? json_decode($request->opens_location_tuesday_datas, true) : [];
                    $closes_location_tuesday_datas = $request->closes_location_tuesday_datas ? json_decode($request->closes_location_tuesday_datas, true) : [];
                    $schedule_closed_tuesday_datas = $request->schedule_closed_tuesday_datas ? json_decode($request->schedule_closed_tuesday_datas, true) : [];

                    $opens_location_wednesday_datas = $request->opens_location_wednesday_datas ? json_decode($request->opens_location_wednesday_datas, true) : [];
                    $closes_location_wednesday_datas = $request->closes_location_wednesday_datas ? json_decode($request->closes_location_wednesday_datas, true) : [];
                    $schedule_closed_wednesday_datas = $request->schedule_closed_wednesday_datas ? json_decode($request->schedule_closed_wednesday_datas, true) : [];

                    $opens_location_thursday_datas = $request->opens_location_thursday_datas ? json_decode($request->opens_location_thursday_datas, true) : [];
                    $closes_location_thursday_datas = $request->closes_location_thursday_datas ? json_decode($request->closes_location_thursday_datas, true) : [];
                    $schedule_closed_thursday_datas = $request->schedule_closed_thursday_datas ? json_decode($request->schedule_closed_thursday_datas, true) : [];

                    $opens_location_friday_datas = $request->opens_location_friday_datas ? json_decode($request->opens_location_friday_datas, true) : [];
                    $closes_location_friday_datas = $request->closes_location_friday_datas ? json_decode($request->closes_location_friday_datas, true) : [];
                    $schedule_closed_friday_datas = $request->schedule_closed_friday_datas ? json_decode($request->schedule_closed_friday_datas, true) : [];

                    $opens_location_saturday_datas = $request->opens_location_saturday_datas ? json_decode($request->opens_location_saturday_datas, true) : [];
                    $closes_location_saturday_datas = $request->closes_location_saturday_datas ? json_decode($request->closes_location_saturday_datas, true) : [];
                    $schedule_closed_saturday_datas = $request->schedule_closed_saturday_datas ? json_decode($request->schedule_closed_saturday_datas, true) : [];

                    $opens_location_sunday_datas = $request->opens_location_sunday_datas ? json_decode($request->opens_location_sunday_datas, true) : [];
                    $closes_location_sunday_datas = $request->closes_location_sunday_datas ? json_decode($request->closes_location_sunday_datas, true) : [];
                    $schedule_closed_sunday_datas = $request->schedule_closed_sunday_datas ? json_decode($request->schedule_closed_sunday_datas, true) : [];
                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds ? json_decode($request->location_holiday_closeds, true) : [];


                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $location->location_recordid = $location_recordid;
                        $location->location_name = $request->location_name[$i];
                        $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                        $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                        $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                        $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;


                        // accessesibility

                        if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                            // Accessibility::create([
                            //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                            //     'accessibility' => $location_accessibility[$i],
                            //     'accessibility_details' => $location_accessibility_details[$i],
                            //     'accessibility_location' => $location_recordid
                            // ]);
                            $location->accessibility_recordid = $location_accessibility[$i];
                            $location->accessibility_details = $location_accessibility_details[$i];
                        }
                        if (isset($location_regions[$i])) {
                            $location->regions()->sync($location_regions[$i]);
                        }

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
                        if (isset($request->location_address[$i])) {

                            $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                            if ($address_info) {
                                $location->location_address = $address_info->address_recordid;
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
                                $location->location_address = $new_address_recordid;
                                array_push($location_address_recordid_list, $new_address_recordid);
                            }
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
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
                                    // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = [];

                        if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            for ($s = 0; $s < 7; $s++) {
                                $schedules = Schedule::where('locations', $location_recordid)->where('weekday', $weekdays[$s])->first();
                                if ($schedules) {
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                    $schedules->locations = $location_recordid;
                                    $schedules->weekday = $weekdays[$s];
                                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                            Schedule::where('locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                // $schedules =
                                // if($schedules){
                                //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                //     $schedules->until = $request->holiday_end_date[$hs];
                                //     $schedules->opens = $request->holiday_open_at[$hs];
                                //     $schedules->closes = $request->closes[$hs];
                                //     if(in_array(($hs+1),$request->schedule_closed)){
                                //         $schedules->schedule_closed = $hs+1;
                                //     }
                                //     $schedules->save();
                                //     $schedule_services[] = $schedules->schedule_recordid;
                                // }else{
                                $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                $schedules = new Schedule();
                                $schedules->schedule_recordid = $schedule_recordid;
                                $schedules->locations = $location_recordid;
                                $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                $schedules->until = $location_holiday_end_dates[$i][$hs];
                                $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                $schedules->closes = $location_holiday_close_ats[$i][$hs];
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
                        $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                        $location->location_phones = '';
                        $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                        $location->address()->sync($location_address_recordid_list);
                        $location->save();

                        array_push($service_locations, location::max('location_recordid'));
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                            $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                            $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                            $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;

                            // accessesibility
                            if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                                // Accessibility::updateOrCreate([
                                //     'accessibility_location' => $request->location_recordid[$i]
                                // ], [
                                //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                                //     'accessibility' => $location_accessibility[$i],
                                //     'accessibility_details' => $location_accessibility_details[$i],
                                // ]);
                                $location->accessibility_recordid = $location_accessibility[$i];
                                $location->accessibility_details = $location_accessibility_details[$i];
                            }
                            if (isset($location_regions[$i])) {
                                $location->regions()->sync($location_regions[$i]);
                            }

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
                            if (isset($request->location_address[$i])) {
                                $address_info = Address::where('address_1', '=', $request->location_address[$i])->first();
                                if ($address_info) {
                                    $location->location_address = $address_info->address_recordid;
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
                                    $location->location_address = $new_address_recordid;
                                    array_push($location_address_recordid_list, $new_address_recordid);
                                }
                            }
                            // location phone
                            // this is contact phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
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
                                        // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }

                            // schedule section
                            $schedule_locations = [];

                            if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($s = 0; $s < 7; $s++) {
                                    $schedules = Schedule::where('locations', $location->location_recordid)->where('weekday', $weekdays[$s])->first();
                                    if ($schedules) {
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                        $schedules->locations = $location->location_recordid;
                                        $schedules->weekday = $weekdays[$s];
                                        $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                                        if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
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
                                Schedule::where('locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
                                    // $schedules =
                                    // if($schedules){
                                    //     $schedules->dtstart = $request->holiday_start_date[$hs];
                                    //     $schedules->until = $request->holiday_end_date[$hs];
                                    //     $schedules->opens = $request->holiday_open_at[$hs];
                                    //     $schedules->closes = $request->closes[$hs];
                                    //     if(in_array(($hs+1),$request->schedule_closed)){
                                    //         $schedules->schedule_closed = $hs+1;
                                    //     }
                                    //     $schedules->save();
                                    //     $schedule_services[] = $schedules->schedule_recordid;
                                    // }else{
                                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                                    $schedules = new Schedule();
                                    $schedules->schedule_recordid = $schedule_recordid;
                                    $schedules->locations = $location->location_recordid;
                                    $schedules->dtstart = $location_holiday_start_dates[$i][$hs];
                                    $schedules->until = $location_holiday_end_dates[$i][$hs];
                                    $schedules->opens = $location_holiday_open_ats[$i][$hs];
                                    $schedules->closes = $location_holiday_close_ats[$i][$hs];
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

                            $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                            $location->location_phones = '';
                            $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

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
            if ($request->service_category_type && $request->service_category_type[0] != null) {
                $service_category_type = $request->service_category_type;
                $service_category_term = $request->service_category_term;
                $service_category_term_type = $request->service_category_term_type;
                foreach ($service_category_type as $key => $value) {
                    if (isset($service_category_type[$key]) && isset($service_category_term[$key])) {
                        if ($service_category_term_type[$key] == 'new') {
                            $taxonomy = new Taxonomy();
                            $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                            $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                            $taxonomy->taxonomy_name = $service_category_term[$key];
                            $taxonomy->taxonomy_parent_name = $value;
                            $taxonomy->taxonomy_vocabulary = 'Service Category';
                            $taxonomy->status = 'Unpublished';
                            $taxonomy->created_by = Auth::id();
                            $taxonomy->save();
                            $service_taxonomies[] = $taxonomy_recordid;
                            $service_taxonomies[] = $value;
                        } else {
                            $service_taxonomies[] = $service_category_type[$key];
                            // $service_taxonomies[] = $service_category_term[$key];
                            $service_taxonomies = array_merge($service_taxonomies, $service_category_term[$key]);
                        }
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = array_filter($request->service_eligibility_type);
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if (isset($service_eligibility_type[$key]) && isset($service_eligibility_term[$key])) {
                        if ($service_eligibility_term_type[$key] == 'new') {
                            $taxonomy = new Taxonomy();
                            $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                            $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                            $taxonomy->taxonomy_name = $service_eligibility_term[$key][0] ?? '';
                            $taxonomy->taxonomy_parent_name = $value;
                            $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                            $taxonomy->status = 'Unpublished';
                            $taxonomy->created_by = Auth::id();
                            $taxonomy->save();
                            $service_taxonomies[] = $taxonomy_recordid;
                            $service_taxonomies[] = $value;
                        } else {
                            $service_taxonomies[] = $service_eligibility_type[$key];
                            // $service_taxonomies[] = $service_eligibility_term[$key];
                            $service_taxonomies = array_merge($service_taxonomies, $service_eligibility_term[$key]);
                        }
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = is_array($service_taxonomies) ? array_unique(array_values(array_filter($service_taxonomies))) : [];
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
                $service_main_priority_list = $request->main_priority;
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
                        $phone_info->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            $schedule_services = $this->saveServiceSchedule($request, $service);

            if ($request->holiday_start_date && $request->holiday_end_date && $request->holiday_open_at && $request->holiday_close_at) {
                Schedule::where('services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->services = $service->service_recordid;
                    $schedules->dtstart = $request->holiday_start_date[$i];
                    $schedules->until = $request->holiday_end_date[$i];
                    $schedules->opens = $request->holiday_open_at[$i];
                    $schedules->closes = $request->holiday_close_at[$i];
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            if (count($schedule_services)) {
                $service->service_schedule = join(',', $schedule_services);
            }
            $service->schedules()->sync($schedule_services);

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
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_ids = $detail_term;
                            // $detail_ids[] = $detail_term[$key];
                        }
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
            $organization = Organization::where('organization_recordid', $service_organization_id)->first();
            $organization_services = $organization->getServices->pluck('service_recordid')->toArray();
            $organization_services[] = $new_recordid;
            $organization->getServices()->sync($organization_services);

            Session::flash('message', 'Services created successfully!');
            Session::flash('status', 'success');

            return redirect('organizations/' . $service_organization_id);
        } catch (\Throwable $th) {
            Log::error('Erro from add service from organization :' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
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
            // $service->service_program = $request->service_program;
            $service->service_email = $request->service_email;
            // $service->service_status = '';
            // if ($request->service_status = 'Yes') {
            //     $service->service_status = 'Verified';
            // }
            $service->service_description = $request->service_description;
            $service->service_application_process = $request->service_application_process;
            $service->service_wait_time = $request->service_wait_time;
            $service->service_fees = $request->service_fees;
            $service->service_accreditations = $request->service_accrediations;
            $service->service_licenses = $request->service_licenses;
            $service->service_metadata = $request->service_metadata;
            $service->service_airs_taxonomy_x = $request->service_airs_taxonomy_x;
            $service->access_requirement = $request->access_requirement;

            $service->access_requirement = $request->access_requirement;

            $this->saveRequiredDocument($request, $service_recordid);

            $service->eligibility_description = $request->eligibility_description;
            $service->minimum_age = $request->minimum_age;
            $service->maximum_age = $request->maximum_age;
            $service->service_alert = $request->service_alert;
            $service->service_language = $request->service_language ? implode(',', $request->service_language) : '';
            $service->service_interpretation = $request->service_interpretation;

            if ($request->service_area)
                $service->areas()->sync($request->service_area);
            if ($request->fee_option)
                $service->fees()->sync($request->fee_option);

            $servicePrograms = $this->saveServiceProgram($request, $service_recordid);

            $service->service_program = '';
            if (count($servicePrograms) > 0) {
                $service->service_program = implode(',', $servicePrograms);
            }
            $service->program()->sync($servicePrograms);

            if ($request->procedure_grouping && is_array($request->procedure_grouping) && count($request->procedure_grouping) > 0) {
                foreach ($request->procedure_grouping as $key => $procedure_grouping) {
                    $code_ids = explode('|', $procedure_grouping);
                    if (count($code_ids) > 0 && isset($code_ids[1])) {
                        $code = Code::where('code_id', $code_ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => 3,
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                $service->procedure_grouping = serialize($request->procedure_grouping);
            }

            // service code section

            if ($request->code_category_ids && count($request->code_category_ids) > 0) {
                $service->code_category_ids = implode(',', $request->code_category_ids);
            } else {
                $service->code_category_ids = '';
            }

            $service_codes = [];
            if ($request->code_conditions) {
                $code_conditions = is_array($request->code_conditions) ? array_values(array_filter($request->code_conditions)) : [];
                foreach ($code_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
                // $service_codes = array_merge($service_codes, array_map('intval', $request->code_conditions));
            }
            if ($request->goal_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->goal_conditions));
                $goal_conditions = is_array($request->goal_conditions) ? array_values(array_filter($request->goal_conditions)) : [];
                foreach ($goal_conditions as $key => $code_id) {
                    $ids = explode('_', $code_id);
                    if (count($ids) > 1) {
                        $service_codes[] = $ids[1];
                        $code = Code::whereId($ids[1])->first();
                        if ($code) {
                            CodeLedger::create([
                                'rating' => $ids[0],
                                'service_recordid' => $new_recordid,
                                'organization_recordid' => $service_organization_id,
                                'SDOH_code' => $code->id,
                                'resource' => $code->resource,
                                'description' => $code->description,
                                'code_type' => $code->code_system,
                                'code' => $code->code,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                }
            }
            if ($request->activities_conditions) {
                // $service_codes = array_merge($service_codes, array_map('intval', $request->activities_conditions));
                $activities_conditions = is_array($request->activities_conditions) ? array_values(array_filter($request->activities_conditions)) : [];
                foreach ($activities_conditions as $key => $code_id) {
                    // $ids = explode('_', $code_id);
                    $code = Code::whereId($code_id)->first();
                    if ($code) {
                        $service_codes[] = $code_id;
                        CodeLedger::create([
                            'rating' => 3,
                            'service_recordid' => $new_recordid,
                            'organization_recordid' => $service_organization_id,
                            'SDOH_code' => $code->id,
                            'resource' => $code->resource,
                            'description' => $code->description,
                            'code_type' => $code->code_system,
                            'code' => $code->code,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
            if (count($service_codes) > 0) {
                $service->SDOH_code = implode(',', $service_codes);
            }

            // if ($request->service_taxonomies) {
            //     $service->service_taxonomy = join(',', $request->service_taxonomies);
            // } else {
            //     $service->service_taxonomy = '';
            // }
            // $service->taxonomy()->sync($request->service_taxonomies);
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
                        $taxonomy->status = 'Unpublished';
                        $taxonomy->created_by = Auth::id();
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_category_type[$key];
                        // $service_taxonomies[] = $service_category_term[$key];
                        $service_taxonomies = array_merge($service_taxonomies, $service_category_term[$key]);
                    }
                }
            }
            if ($request->service_eligibility_type && $request->service_eligibility_type[0] != null) {
                $service_eligibility_type = array_filter($request->service_eligibility_type);
                $service_eligibility_term = $request->service_eligibility_term;
                $service_eligibility_term_type = $request->service_eligibility_term_type;
                foreach ($service_eligibility_type as $key => $value) {
                    if ($service_eligibility_term_type[$key] == 'new') {
                        $taxonomy = new Taxonomy();
                        $taxonomy_recordid = Taxonomy::max('taxonomy_recordid') + 1;
                        $taxonomy->taxonomy_recordid = $taxonomy_recordid;
                        $taxonomy->taxonomy_name = $service_eligibility_term[$key][0] ?? '';
                        $taxonomy->taxonomy_parent_name = $value;
                        $taxonomy->taxonomy_vocabulary = 'Service Eligibility';
                        $taxonomy->status = 'Unpublished';
                        $taxonomy->created_by = Auth::id();
                        $taxonomy->save();
                        $service_taxonomies[] = $taxonomy_recordid;
                        $service_taxonomies[] = $value;
                    } else {
                        $service_taxonomies[] = $service_eligibility_type[$key];
                        // $service_taxonomies[] = $service_eligibility_term[$key];
                        $service_taxonomies = array_merge($service_taxonomies, $service_eligibility_term[$key]);
                    }
                }
            }
            if (count($service_taxonomies) > 0) {
                $service_taxonomies = is_array($service_taxonomies) ? array_unique(array_values(array_filter($service_taxonomies))) : [];
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
                $service_main_priority_list = $request->main_priority;
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
                        $phone_info->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        $phone_info->save();
                        array_push($phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $service_phone_number_list[$i];
                        $new_phone->phone_extension = $service_phone_extension_list[$i];
                        $new_phone->phone_type = $service_phone_type_list[$i];
                        $new_phone->phone_language = $service_phone_language_list && isset($service_phone_language_list[$i]) && is_array($service_phone_language_list[$i]) ? implode(',', $service_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $service_phone_description_list[$i];
                        if (isset($service_main_priority_list[0]) && $service_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $service->phone()->sync($phone_recordid_list);

            $schedule_services = $this->saveServiceSchedule($request, $service);

            if ($request->holiday_start_date && $request->holiday_end_date && $request->holiday_open_at && $request->holiday_close_at) {
                Schedule::where('services', $service->service_recordid)->where('schedule_holiday', '1')->delete();
                for ($i = 0; $i < count($request->holiday_start_date); $i++) {
                    // $schedules =
                    // if($schedules){
                    //     $schedules->dtstart = $request->holiday_start_date[$i];
                    //     $schedules->until = $request->holiday_end_date[$i];
                    //     $schedules->opens = $request->holiday_open_at[$i];
                    //     $schedules->closes = $request->closes[$i];
                    //     if(in_array(($i+1),$request->schedule_closed)){
                    //         $schedules->schedule_closed = $i+1;
                    //     }
                    //     $schedules->save();
                    //     $schedule_services[] = $schedules->schedule_recordid;
                    // }else{
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->services = $service->service_recordid;
                    $schedules->dtstart = $request->holiday_start_date[$i];
                    $schedules->until = $request->holiday_end_date[$i];
                    $schedules->opens = $request->holiday_open_at[$i];
                    $schedules->closes = $request->holiday_close_at[$i];
                    if ($request->holiday_closed && in_array(($i + 1), $request->holiday_closed)) {
                        $schedules->schedule_closed = $i + 1;
                    }
                    $schedules->schedule_holiday = '1';
                    $schedules->save();
                    $schedule_services[] = $schedule_recordid;
                    // }
                }
            }
            if (count($schedule_services)) {
                $service->service_schedule = join(',', $schedule_services);
            }
            $service->schedules()->sync($schedule_services);

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
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_ids = $detail_term;
                            // $detail_ids[] = array_merge($detail_ids, $detail_term[$key]);
                        }
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

            $organization = Organization::where('organization_recordid', $service_organization_id)->first();
            $organization_services = $organization->getServices->pluck('service_recordid')->toArray();
            $organization_services[] = $new_recordid;
            $organization->getServices()->sync($organization_services);

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
        if ($service) {
            $service_name = $service->service_name;

            $layout = Layout::find(1);

            $pdf = PDF::loadView('frontEnd.services.service_download', compact('service', 'layout'));
            $service_name = str_replace('"', '', $service_name);

            return $pdf->download($service_name . '_' . time() . '.pdf');
        } else {
            Session::flash('message', 'Something went wrong, Please try again later.');
            Session::flash('status', 'error');
            return redirect()->back();
        }
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

            if (isset($service->service_organization)) {
                if ($service->service_organization != 0) {
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
            // Service::where('service_recordid', $request->service_recordid)->delete();
            $service = Service::find($request->service_recordid);
            if ($service) {
                $service->delete();
            }

            Session::flash('message', 'Service deleted successfully!');
            Session::flash('status', 'success');
            return redirect('/services');
        } catch (\Throwable $th) {
            Log::error('Error in delete service : ' . $th);
            return redirect('/services');
        }
    }

    public function getDetailTerm(Request $request)
    {
        try {
            $detail_type = $request->value;

            $detail_info_list = Detail::orderBy('detail_value')->where('detail_type', $detail_type)->pluck('detail_value', 'detail_recordid')->unique();

            return response()->json([
                'success' => true,
                'data' => $detail_info_list,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in get detai term service : ' . $th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }

    public function addDetailTerm(Request $request)
    {
        try {
            $detail_type = $request->detail_type_name;
            $detail_term = $request->detail_term;
            $detail = new Detail();
            $detail_recordid = Detail::max('detail_recordid') + 1;
            $detail->detail_recordid = $detail_recordid;
            $detail->detail_type = $detail_type;
            $detail->detail_value = $detail_term;
            $detail->save();

            return response()->json([
                'success' => true,
                'data' => $detail_recordid,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in get detai term service : ' . $th);
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
            $taxonomy_parent_name = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_recordid', $taxonomy_recordid)->first();
            $taxonomy_info_list = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $taxonomy_recordid . '%')->where('status', 'Published')->get();
            // $taxonomy_info_list = $this->parent()->get();

            // while ($taxonomy_info_list->last() && $taxonomy_info_list->last()->taxonomy_parent_name !== null) {
            //     $parent = $taxonomy_info_list->last()->parent()->get();
            //     $taxonomy_info_list = $taxonomy_info_list->merge($parent);
            // }
            $taxonomy_array = [];
            foreach ($taxonomy_info_list as $value) {
                $taxonomy_array[$value->taxonomy_recordid] = '- ' . $value->taxonomy_name;
                $taxonomy_child_list = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value->taxonomy_recordid . '%')->where('status', 'Published')->get();
                if ($taxonomy_child_list) {
                    foreach ($taxonomy_child_list as $value1) {
                        $taxonomy_array[$value1->taxonomy_recordid] = '-- ' . $value1->taxonomy_name;
                        $taxonomy_child_list1 = Taxonomy::orderBy('taxonomy_name')->where('taxonomy_parent_name', 'LIKE', '%' . $value1->taxonomy_recordid . '%')->where('status', 'Published')->get();
                        if ($taxonomy_child_list1) {
                            foreach ($taxonomy_child_list1 as $value2) {
                                $taxonomy_array[$value2->taxonomy_recordid] = '--- ' . $value2->taxonomy_name;
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
            Log::error('Error in get taxonomy term service : ' . $th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }

    public function saveTaxonomyTerm(Request $request)
    {
        try {
            $taxonomyType = TaxonomyType::where('name', 'Service Category')->first();
            $taxonomy = Taxonomy::where('taxonomy_recordid', $request->get('category_type_recordid'))->first();
            $latestTaxonomyId = Taxonomy::max('taxonomy_recordid') + 1;
            Taxonomy::create([
                'taxonomy_recordid' => Taxonomy::max('taxonomy_recordid') + 1,
                'taxonomy_name' => $request->get('service_category_term'),
                'taxonomy_parent_name' => $request->get('category_type_recordid'),
                'temp_service_recordid' => $request->get('service_recordid'),
                'temp_organization_recordid' => $request->get('organization_recordid'),
                'status' => 'Unpublished',
                'added_term' => '1',
                'created_by' => Auth::id(),
                'taxonomy' => $taxonomyType->taxonomy_type_recordid ?? ''
            ]);
            $lastInsetedTaxonomy = Taxonomy::where('taxonomy_recordid', $latestTaxonomyId)->first();

            if ($lastInsetedTaxonomy && $taxonomyType->taxonomy_type_recordid) {
                $lastInsetedTaxonomy->taxonomy_type()->sync($taxonomyType->taxonomy_type_recordid);
            }
            $service_name = '';
            $organization_name = '';
            if ($request->has('service_recordid')) {
                $service = Service::where('service_recordid', $request->get('service_recordid'))->first();
                if ($service) {
                    $service_name = $service->service_name;
                    $serviceOrganizations = $service->getOrganizations()->pluck('organization_name')->toArray();
                    $organization_name = count($serviceOrganizations) > 0 ? implode(', ', $serviceOrganizations) : '';
                }
            }
            $contact_email_list = TaxonomyEmail::select('email')->pluck('email')->toArray();
            if (count($contact_email_list) > 0) {

                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                // $from_phone = env('MAIL_FROM_PHONE');

                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = 'New Taxonomy Term Added to ORServices';
                $email->setSubject($subject);
                $user = Auth::user();

                $message = '<html><body>';
                $message .= '<h1 style="color:#424242;">New Taxonomy Term Added to ORServices</h1>';
                // $message .= '<p style="color:#424242;font-size:18px;">The following change was suggested at  ' . $site_name . ' website.</p>';
                $message .= '<p style="color:#424242;font-size:12px;">User: ' . $user->first_name . ' ' . $user->last_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Term: ' . $request->get('service_category_term') . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Parent term: ' . $taxonomy->taxonomy_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Service: ' . $service_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $organization_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
                // $message .= '<p style="color:#424242;font-size:12px;">Phone: '. $from_phone .'</p>';
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';


                foreach ($contact_email_list as $key => $contact_email) {
                    $email->addTo($contact_email, $username);
                }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
            }
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in save taxonomy term servie : ' . $th);
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function saveEligibilityTaxonomyTerm(Request $request)
    {
        try {
            $taxonomyType = TaxonomyType::where('name', 'Service Eligibility')->first();
            $taxonomy = Taxonomy::where('taxonomy_recordid', $request->get('eligibility_type_recordid'))->first();
            $latestTaxonomyId = Taxonomy::max('taxonomy_recordid') + 1;
            Taxonomy::create([
                'taxonomy_recordid' => Taxonomy::max('taxonomy_recordid') + 1,
                'taxonomy_name' => $request->get('service_eligibility_term'),
                'taxonomy_parent_name' => $request->get('eligibility_type_recordid'),
                'temp_service_recordid' => $request->get('service_recordid'),
                'temp_organization_recordid' => $request->get('organization_recordid'),
                'status' => 'Unpublished',
                'added_term' => '1',
                'created_by' => Auth::id(),
                'taxonomy' => $taxonomyType->taxonomy_type_recordid ?? ''
            ]);
            $lastInsetedTaxonomy = Taxonomy::where('taxonomy_recordid', $latestTaxonomyId)->first();

            if ($lastInsetedTaxonomy && $taxonomyType->taxonomy_type_recordid) {
                $lastInsetedTaxonomy->taxonomy_type()->sync($taxonomyType->taxonomy_type_recordid);
            }
            $service_name = '';
            $organization_name = '';
            if ($request->has('service_recordid')) {
                $service = Service::where('service_recordid', $request->get('service_recordid'))->first();
                if ($service) {
                    $service_name = $service->service_name;
                    $serviceOrganizations = $service->getOrganizations()->pluck('organization_name')->toArray();
                    $organization_name = count($serviceOrganizations) > 0 ? implode(', ', $serviceOrganizations) : '';
                }
            }
            $contact_email_list = TaxonomyEmail::select('email')->pluck('email')->toArray();
            if (count($contact_email_list) > 0) {

                $from = env('MAIL_FROM_ADDRESS');
                $name = env('MAIL_FROM_NAME');
                // $from_phone = env('MAIL_FROM_PHONE');

                $email = new Mail();
                $email->setFrom($from, $name);
                // $subject = 'A Suggested Change was Submitted at ' . $site_name;
                $subject = 'New Taxonomy Term Added to ORServices';
                $email->setSubject($subject);
                $user = Auth::user();

                $message = '<html><body>';
                $message .= '<h1 style="color:#424242;">New Taxonomy Term Added to ORServices</h1>';
                // $message .= '<p style="color:#424242;font-size:18px;">The following change was suggested at  ' . $site_name . ' website.</p>';
                $message .= '<p style="color:#424242;font-size:12px;">User: ' . $user->first_name . ' ' . $user->last_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Term: ' . $request->get('service_eligibility_term') . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Parent term: ' . $taxonomy->taxonomy_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Service: ' . $service_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Organization: ' . $organization_name . '</p>';
                $message .= '<p style="color:#424242;font-size:12px;">Timestamp: ' . Carbon::now() . '</p>';
                // $message .= '<p style="color:#424242;font-size:12px;">Phone: '. $from_phone .'</p>';
                $message .= '</body></html>';

                $email->addContent("text/html", $message);
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));

                $error = '';

                $username = '';


                foreach ($contact_email_list as $key => $contact_email) {
                    $email->addTo($contact_email, $username);
                }
                $response = $sendgrid->send($email);
                if ($response->statusCode() == 401) {
                    $error = json_decode($response->body());
                }
            }
            return response()->json([
                'success' => true,
                'taxonomy_recordid' => $latestTaxonomyId
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in save taxonomy term servie : ' . $th);
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function add_comment(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        try {
            $comment_content = $request->comment;
            $user = Auth::user();
            $date_time = date("Y-m-d H:i:s");
            $comment = new Comment();

            $comment_recordids = Comment::select("comments_recordid")->distinct()->get();
            $comment_recordid_list = array();
            foreach ($comment_recordids as $key => $value) {
                $comment_recordid = $value->comments_recordid;
                array_push($comment_recordid_list, $comment_recordid);
            }
            $comment_recordid_list = array_unique($comment_recordid_list);
            $new_recordid = Comment::max('comments_recordid') + 1;
            if (in_array($new_recordid, $comment_recordid_list)) {
                $new_recordid = Comment::max('comments_recordid') + 1;
            }

            $comment->comments_recordid = $new_recordid;
            $comment->comments_content = $comment_content;
            $comment->comments_user = $user->id;
            $comment->comments_user_firstname = $user->first_name;
            $comment->comments_user_lastname = $user->last_name;
            $comment->comments_service = $id;
            $comment->comments_datetime = $date_time;
            $comment->save();

            Session::flash('message', 'Comment added successfully!');
            Session::flash('status', 'success');
            return redirect('services/' . $id);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function addServiceTag(Request $request)
    {
        try {
            $id = $request->id;
            $service = Service::whereId($id)->first();
            $service->service_tag = $request->val && is_array($request->val) ? implode(',', $request->val) : '';
            $service->updated_at = Carbon::now();
            $service->updated_by = Auth::id();
            $service->save();
            return response()->json([
                'message' => 'tags added successfully!',
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function createNewServiceTag(Request $request, $id)
    {
        try {
            if (!$request->tag) {
                Session::flash('message', 'Service tag can`t be blank!');
                Session::flash('status', 'error');
                return redirect()->back();
            }
            $serviceTag = ServiceTag::create([
                'tag' => $request->tag,
                'created_by' => Auth::id()
            ]);
            $service = Service::whereId($id)->first();
            $serTag = $service->service_tag != null ? explode(',', $service->service_tag) : [];
            $serTag[] = $serviceTag->id;
            if (!empty($serTag)) {
                $service->service_tag = implode(',', $serTag);
                $service->save();
            }
            Session::flash('message', 'Service tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }

    public function saveRequiredDocument($request, $id)
    {
        $document_type = $request->document_type && is_array($request->document_type) ? array_filter($request->document_type) : [];
        if (count($document_type) > 0) {
            if ($request->deletedRequireDocument) {
                $deletedRequireDocument = json_decode($request->deletedRequireDocument, true);
                RequiredDocument::whereIn('id', $deletedRequireDocument)->delete();
            }
            $requiredDocumentTypes = Detail::whereDetailType('Required Document')->pluck('detail_value', 'id')->toArray();
            foreach ($document_type as $key => $value) {
                if (is_array($request->requireDocumentId) && isset($request->requireDocumentId[$key])) {
                    RequiredDocument::whereId($request->requireDocumentId[$key])->update([
                        'service_recordid' => $id,
                        'detail_id' => $value,
                        'document_type' => isset($requiredDocumentTypes[$value]) ? $requiredDocumentTypes[$value] : null,
                        'document_link' => is_array($request->document_url) && isset($request->document_url[$key]) ? $request->document_url[$key] : null,
                        'document_title' => is_array($request->document_title) && isset($request->document_title[$key]) ? $request->document_title[$key] : null,
                    ]);
                } else {
                    RequiredDocument::create([
                        'service_recordid' => $id,
                        'detail_id' => $value,
                        'document_type' => isset($requiredDocumentTypes[$value]) ? $requiredDocumentTypes[$value] : null,
                        'document_link' => is_array($request->document_url) && isset($request->document_url[$key]) ? $request->document_url[$key] : null,
                        'document_title' => is_array($request->document_title) && isset($request->document_title[$key]) ? $request->document_title[$key] : null,
                        'created_by' => Auth::id()
                    ]);
                }
            }
        }
        return true;
    }

    /**
     * @param $request
     * @param $service
     * @return array
     */
    public function saveServiceSchedule($request, $service): array
    {
        $schedule_services = [];
        if ($request->weekday) {
            for ($i = 0; $i < 7; $i++) {
                $schedules = Schedule::where('services', $service->service_recordid)->where('opens', $request->opens[$i])->where('closes', $request->closes[$i])->first();
                if ($request->weekday[$i] && (((is_array($request->schedule_closed) && in_array(($i + 1), $request->schedule_closed)) || (is_array($request->open_24_hours) && in_array(($i + 1), $request->open_24_hours))) || $request->opens[$i])) {
                    if ($schedules) {
                        $schedules->weekday = $schedules->weekday ? (str_contains($schedules->weekday, $request->weekday[$i]) ? $schedules->weekday : ($schedules->weekday . ',' . $request->weekday[$i])) : $request->weekday[$i];
                        $schedules->opens = $request->opens[$i];
                        $schedules->closes = $request->closes[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $schedules->schedule_closed ? (str_contains($schedules->schedule_closed, ($i + 1)) ? $schedules->schedule_closed : $schedules->schedule_closed . ',' . ($i + 1)) : ($i + 1);
                            // $schedules->open_24_hours = null;
                        } else {
                            if (str_contains($schedules->schedule_closed, ($i + 1))) {
                                $schedule_closed = explode(',', $schedules->schedule_closed);
                                if (is_array($schedule_closed) && count($schedule_closed) > 0) {
                                    array_splice($schedule_closed, array_search(($i + 1), $schedule_closed), 1);
                                    $schedules->schedule_closed = implode(',', $schedule_closed);
                                }
                            }
                        }

                        if ($request->open_24_hours && in_array(($i + 1), $request->open_24_hours)) {
                            // $schedules->open_24_hours = $i + 1;
                            $schedules->open_24_hours = $schedules->open_24_hours ? (str_contains($schedules->open_24_hours, ($i + 1)) ? $schedules->open_24_hours : $schedules->open_24_hours . ',' . ($i + 1)) : ($i + 1);
                            // $schedules->schedule_closed = null;
                        } else {
                            if (str_contains($schedules->open_24_hours, ($i + 1))) {
                                $open_24_hours = explode(',', $schedules->open_24_hours);
                                if (is_array($open_24_hours) && count($open_24_hours) > 0) {
                                    array_splice($open_24_hours, array_search(($i + 1), $open_24_hours), 1);
                                    $schedules->open_24_hours = implode(',', $open_24_hours);
                                }
                            }
                            // $schedules->open_24_hours = null;
                            // $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedules->schedule_recordid;
                    } else {
                        $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                        $schedules = new Schedule();
                        $schedules->schedule_recordid = $schedule_recordid;
                        $schedules->services = $service->service_recordid;
                        $schedules->weekday = $request->weekday[$i];
                        $schedules->opens = $request->opens[$i];
                        $schedules->closes = $request->closes[$i];
                        if ($request->schedule_closed && in_array(($i + 1), $request->schedule_closed)) {
                            $schedules->schedule_closed = $schedules->schedule_closed ? (str_contains($schedules->schedule_closed, ($i + 1)) ? $schedules->schedule_closed : $schedules->schedule_closed . ',' . ($i + 1)) : ($i + 1);
                            // $schedules->open_24_hours = null;
                        } else {
                            if (str_contains($schedules->schedule_closed, ($i + 1))) {
                                $schedule_closed = explode(',', $schedules->schedule_closed);
                                if (is_array($schedule_closed) && count($schedule_closed) > 0) {
                                    array_splice($schedule_closed, array_search(($i + 1), $schedule_closed), 1);
                                    $schedules->schedule_closed = implode(',', $schedule_closed);
                                }
                            }
                        }
                        if ($request->open_24_hours && in_array(($i + 1), $request->open_24_hours)) {
                            // $schedules->open_24_hours = $i + 1;
                            $schedules->open_24_hours = $schedules->open_24_hours ? (str_contains($schedules->open_24_hours, ($i + 1)) ? $schedules->open_24_hours : $schedules->open_24_hours . ',' . ($i + 1)) : ($i + 1);
                            // $schedules->schedule_closed = null;
                        } else {
                            if (str_contains($schedules->open_24_hours, ($i + 1))) {
                                $open_24_hours = explode(',', $schedules->open_24_hours);
                                if (is_array($open_24_hours) && count($open_24_hours) > 0) {
                                    array_splice($open_24_hours, array_search(($i + 1), $open_24_hours), 1);
                                    $schedules->open_24_hours = implode(',', $open_24_hours);
                                }
                            }
                            // $schedules->open_24_hours = null;
                            // $schedules->schedule_closed = null;
                        }
                        $schedules->save();
                        $schedule_services[] = $schedule_recordid;
                    }
                }
            }
        }
        return $schedule_services;
    }
    public function saveServiceProgram($request, $service_recordid)
    {
        $servicePrograms = [];
        if ($request->program_name) {
            $program_name = $request->program_name;
            $program_alternate_name = $request->program_alternate_name;
            $program_description = $request->program_description;
            // $program_service_relationship = $request->program_service_relationship;
            $programRadio = $request->programRadio;
            $program_recordid = $request->program_recordid;

            for ($i = 0; $i < count($program_name); $i++) {
                if ($programRadio[$i] == 'new_data') {
                    $program = new Program();
                    $program->program_recordid = Program::max('program_recordid') + 1;
                    $servicePrograms[] = Program::max('program_recordid') + 1;
                } else {
                    $program = Program::where('program_recordid', $program_recordid[$i])->first();
                    $servicePrograms[] = $program->program_recordid;
                }
                $program->name = $program_name[$i];
                $program->alternate_name = $program_alternate_name[$i];
                $program->description = $program_description[$i];
                // $program->program_service_relationship = $program_service_relationship[$i];
                $program->services = $service_recordid;
                $recordids = [];
                $recordids[] = $service_recordid;
                $program->save();
            }
        }
        return $servicePrograms;
    }
}
