<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Functions\Airtable;
use App\Imports\OrganizationImport;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Organization;
use App\Model\OrganizationDetail;
use App\Model\Taxonomy;
use App\Model\Alt_taxonomy;
use App\Model\ServiceOrganization;
use App\Model\Servicetaxonomy;
use App\Model\Service;
use App\Model\Contact;
use App\Model\SessionData;
use App\Model\Comment;
use App\Model\Phone;
use App\Model\Location;
use App\Model\Airtablekeyinfo;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Airtables;
use App\Model\CSV_Source;
use App\Model\Source_data;
use App\Model\Airtable_v2;
use App\Model\City;
use App\Model\OrganizationStatus;
use App\Model\Detail;
use App\Model\Disposition;
use App\Model\InteractionMethod;
use App\Model\Language;
use App\Model\MetaFilter;
use App\Model\OrganizationPhone;
use App\Model\OrganizationTableFilter;
use App\Model\PhoneType;
use App\Model\Schedule;
use App\Model\OrganizationTag;
use App\Model\Program;
use App\Model\Region;
use App\Model\ServiceTag;
use App\Model\SessionInteraction;
use App\Model\State;
use App\Services\OrganizationService;
use App\Services\Stringtoint;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public $commonController, $organizationService;

    public function __construct(CommonController $commonController, OrganizationService $organizationService)
    {
        $this->commonController = $commonController;
        $this->organizationService = $organizationService;
    }

    public function airtable($access_token, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info) {
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->access_token = $access_token;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Organization::truncate();
        OrganizationDetail::truncate();

        $airtable = new Airtable(array(
            'access_token' => $access_token,
            'base' => $base_url,
        ));

        $request = $airtable->getContent('organizations');

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode($response, TRUE);

            foreach ($airtable_response['records'] as $record) {

                $organization = new Organization();
                $strtointclass = new Stringtoint();
                $organization->organization_recordid = $strtointclass->string_to_int($record['id']);
                $organization->organization_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                if (isset($record['fields']['logo-x'])) {
                    foreach ($record['fields']['logo-x'] as $key => $image) {
                        try {
                            $organization->organization_logo_x .= $image["url"];
                        } catch (\Exception $e) {
                            echo 'Caught exception: ', $e->getMessage(), "\n";
                        }
                    }
                }
                if (isset($record['fields']['forms-x'])) {
                    foreach ($record['fields']['forms-x'] as $key => $form) {
                        try {
                            $organization->organization_forms_x_filename .= $form["filename"];
                            $organization->organization_forms_x_url .= $form["url"];
                        } catch (\Exception $e) {
                            echo 'Caught exception: ', $e->getMessage(), "\n";
                        }
                    }
                }
                $organization->organization_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                $organization->organization_x_uid = isset($record['fields']['x-uid']) ? $record['fields']['x-uid'] : null;
                $organization->organization_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                $organization->organization_description = mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                $organization->organization_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                $organization->organization_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                // $organization->organization_status_x = isset($record['fields']['status-x']) ? $record['fields']['status-x'] : null;
                // if ($organization->organization_status_x == 'Vetted')
                //     $organization->organization_status_sort = 1;
                // if ($organization->organization_status_x == 'Vetting In Progress')
                //     $organization->organization_status_sort = 2;
                // if ($organization->organization_status_x == 'Not vetted')
                //     $organization->organization_status_sort = 3;
                // if ($organization->organization_status_x == null)
                //     $organization->organization_status_sort = 4;
                if (isset($record['fields']['status-x'])) {
                    $organization_status_x = OrganizationStatus::firstOrCreate(
                        ['status' => $record['fields']['status-x']],
                        ['status' => $record['fields']['status-x'], 'created_by' => Auth::id()]
                    );
                    $organization->organization_status_x = $organization_status_x->id;
                }

                $organization->organization_legal_status = isset($record['fields']['legal_status']) ? $record['fields']['legal_status'] : null;
                $organization->organization_tax_status = isset($record['fields']['tax_status']) ? $record['fields']['tax_status'] : null;
                $organization->organization_legal_status = isset($record['fields']['legal_status']) ? $record['fields']['legal_status'] : null;
                $organization->organization_tax_status = isset($record['fields']['tax_status']) ? $record['fields']['tax_status'] : null;
                $organization->organization_tax_id = isset($record['fields']['tax_id']) ? $record['fields']['tax_id'] : null;
                $organization->organization_year_incorporated = isset($record['fields']['year_incorporated']) ? $record['fields']['year_incorporated'] : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $organizationservice = $strtointclass->string_to_int($value);

                        if ($i != 0)
                            $organization->organization_services = $organization->organization_services . ',' . $organizationservice;
                        else
                            $organization->organization_services = $organizationservice;
                        $i++;
                    }
                }

                if (isset($record['fields']['phones'])) {
                    $i = 0;
                    foreach ($record['fields']['phones'] as $value) {

                        $organizationphone = $strtointclass->string_to_int($value);

                        if ($i != 0)
                            $organization->organization_phones = $organization->organization_phones . ',' . $organizationphone;
                        else
                            $organization->organization_phones = $organizationphone;
                        $i++;
                    }
                }


                if (isset($record['fields']['locations'])) {
                    $i = 0;
                    foreach ($record['fields']['locations'] as $value) {

                        $organizationlocation = $strtointclass->string_to_int($value);

                        if ($i != 0)
                            $organization->organization_locations = $organization->organization_locations . ',' . $organizationlocation;
                        else
                            $organization->organization_locations = $organizationlocation;
                        $i++;
                    }
                }
                $organization->organization_contact = isset($record['fields']['contact']) ? implode(",", $record['fields']['contact']) : null;
                $organization->organization_contact = $strtointclass->string_to_int($organization->organization_contact);

                if (isset($record['fields']['details'])) {
                    $i = 0;
                    foreach ($record['fields']['details'] as $value) {
                        $organization_detail = new OrganizationDetail();
                        $organization_detail->organization_recordid = $organization->organization_recordid;
                        $organization_detail->detail_recordid = $strtointclass->string_to_int($value);
                        $organization_detail->save();
                        $organizationdetail = $strtointclass->string_to_int($value);

                        if ($i != 0)
                            $organization->organization_details = $organization->organization_details . ',' . $organizationdetail;
                        else
                            $organization->organization_details = $organizationdetail;
                        $i++;
                    }
                }

                if (isset($record['fields']['AIRS Taxonomy-x'])) {
                    $i = 0;
                    foreach ($record['fields']['AIRS Taxonomy-x'] as $value) {

                        if ($i != 0)
                            $organization->organization_airs_taxonomy_x = $organization->organization_airs_taxonomy_x . ',' . $value;
                        else
                            $organization->organization_airs_taxonomy_x = $value;
                        $i++;
                    }
                }

                $organization->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Organizations')->first();
        $airtable->records = Organization::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function airtable_v2($access_token, $base_url, $organization_tag)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->access_token = $access_token;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            // Organization::truncate();
            // OrganizationDetail::truncate();

            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('organizations');

            do {


                $response = $request->getResponse();

                $airtable_response = json_decode($response, TRUE);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_organization = Organization::where('organization_recordid', $recordId)->where('organization_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_organization == null) {
                        $organization = new Organization();
                        $strtointclass = new Stringtoint();
                        $organization->organization_recordid = $strtointclass->string_to_int($record['id']);
                        $organization->organization_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        if (isset($record['fields']['x-logo'])) {
                            foreach ($record['fields']['x-logo'] as $key => $image) {
                                try {
                                    $organization->organization_logo_x .= $image["url"];
                                } catch (\Exception $e) {
                                    echo 'Caught exception: ', $e->getMessage(), "\n";
                                }
                            }
                        }
                        if (isset($record['fields']['x-forms'])) {
                            foreach ($record['fields']['x-forms'] as $key => $form) {
                                try {
                                    $organization->organization_forms_x_filename .= $form["filename"];
                                    $organization->organization_forms_x_url .= $form["url"];
                                } catch (\Exception $e) {
                                    echo 'Caught exception: ', $e->getMessage(), "\n";
                                }
                            }
                        }
                        $organization->organization_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                        $organization->organization_x_uid = isset($record['fields']['x-uid']) ? $record['fields']['x-uid'] : null;
                        $organization->organization_website_rating = isset($record['fields']['y-website_quality']) ? $record['fields']['y-website_quality'] : null;
                        $organization->organization_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        $organization->organization_description = mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                        $organization->organization_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                        $organization->organization_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                        $organization->organization_status_x = isset($record['fields']['x-status']) ? $record['fields']['x-status'] : null;
                        // if (isset($record['fields']['x-status']) && !is_array($record['fields']['x-status'])) {
                        //     OrganizationStatus::firstOrCreate(['status' => $record['fields']['x-status']]);
                        // }
                        // if ($organization->organization_status_x == 'Vetted')
                        //     $organization->organization_status_sort = 1;
                        // if ($organization->organization_status_x == 'Vetting In Progress')
                        //     $organization->organization_status_sort = 2;
                        // if ($organization->organization_status_x == 'Not vetted')
                        //     $organization->organization_status_sort = 3;
                        // if ($organization->organization_status_x == null)
                        //     $organization->organization_status_sort = 4;
                        // $organization->organization_tax_status = isset($record['fields']['tax_status']) ? $record['fields']['tax_status'] : null;

                        if (isset($record['fields']['status-x'])) {
                            $organization_status_x = OrganizationStatus::firstOrCreate(
                                ['status' => $record['fields']['status-x']],
                                ['status' => $record['fields']['status-x'], 'created_by' => Auth::id()]
                            );
                            $organization->organization_status_x = $organization_status_x->id;
                        }

                        $organization->organization_legal_status = isset($record['fields']['legal_status']) ? $record['fields']['legal_status'] : null;

                        $organization->organization_tax_status = isset($record['fields']['tax_status']) ? $record['fields']['tax_status'] : null;
                        $organization->organization_tax_id = isset($record['fields']['tax_id']) ? $record['fields']['tax_id'] : null;
                        $organization->organization_year_incorporated = isset($record['fields']['year_incorporated']) ? $record['fields']['year_incorporated'] : null;
                        // $organization->organization_tag = isset($record['fields']['x-tags']) ? implode(',', $record['fields']['x-tags']) : null;
                        $organization_tags = isset($record['fields']['x-tags']) ? $record['fields']['x-tags'] : [];
                        if (!empty($organization_tags)) {
                            // $organization->organization_tag = $organization->organization_tag . ',' . $organization_tag;
                            $orgTag = [];
                            foreach ($organization_tags as $key1 => $value1) {
                                $organization_tag = OrganizationTag::where('tag', 'LIKE', '%' . $value1 . '%')->first();
                                if ($organization_tag) {
                                    $orgTag[] = $organization_tag->id;
                                } else {
                                    $organization_tag = OrganizationTag::create([
                                        'tag' => $value1
                                    ]);
                                    $orgTag[] = $organization_tag->id;
                                }
                            }
                            $organization->organization_tag = implode(',', $orgTag);
                        }

                        if (isset($record['fields']['services'])) {
                            $i = 0;
                            foreach ($record['fields']['services'] as $value) {

                                $organizationservice = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_services = $organization->organization_services . ',' . $organizationservice;
                                else
                                    $organization->organization_services = $organizationservice;
                                $i++;
                            }
                        }

                        if (isset($record['fields']['phones'])) {
                            $i = 0;
                            foreach ($record['fields']['phones'] as $value) {

                                $organizationphone = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_phones = $organization->organization_phones . ',' . $organizationphone;
                                else
                                    $organization->organization_phones = $organizationphone;
                                $i++;
                            }
                        }


                        if (isset($record['fields']['locations'])) {
                            $i = 0;
                            foreach ($record['fields']['locations'] as $value) {

                                $organizationlocation = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_locations = $organization->organization_locations . ',' . $organizationlocation;
                                else
                                    $organization->organization_locations = $organizationlocation;
                                $i++;
                            }
                        }
                        $organization->organization_contact = isset($record['fields']['contacts']) ? implode(",", $record['fields']['contacts']) : null;
                        $organization->organization_contact = $strtointclass->string_to_int($organization->organization_contact);

                        if (isset($record['fields']['x-details'])) {
                            $i = 0;
                            foreach ($record['fields']['x-details'] as $value) {
                                $organization_detail = new OrganizationDetail();
                                $organization_detail->organization_recordid = $organization->organization_recordid;
                                $organization_detail->detail_recordid = $strtointclass->string_to_int($value);
                                $organization_detail->save();
                                $organizationdetail = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_details = $organization->organization_details . ',' . $organizationdetail;
                                else
                                    $organization->organization_details = $organizationdetail;
                                $i++;
                            }
                        }

                        if (isset($record['fields']['AIRS Taxonomy-x'])) {
                            $i = 0;
                            foreach ($record['fields']['AIRS Taxonomy-x'] as $value) {

                                if ($i != 0)
                                    $organization->organization_airs_taxonomy_x = $organization->organization_airs_taxonomy_x . ',' . $value;
                                else
                                    $organization->organization_airs_taxonomy_x = $value;
                                $i++;
                            }
                        }

                        $organization->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Organizations')->first();
            $airtable->records = Organization::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Organization sync : ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->organizationService->import_airtable_v3($access_token, $base_url);
    }

    public function csv(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename =  $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'organizations.csv') {
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

            Organization::truncate();
            OrganizationDetail::truncate();

            Excel::import(new OrganizationImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Organizations')->first();
            $csv_source->records = Organization::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            $response = array(
                'status' => 'success',
                'result' => 'Organization imported successfully',
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $layout = Layout::find(1);
        $organizations = Organization::orderBy('organization_name')->select('*');

        $metas = MetaFilter::all();
        $count_metas = MetaFilter::count();
        $filter_label = Session::has('filter_label') ? Session::get('filter_label') : $layout->default_label;

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
                $service_organizations = Service::whereIn('service_recordid', $filterServiceRecordId)->pluck('service_organization')->toArray();

                $organizationIds = array_values(array_unique(array_merge($organizationIds, $service_organizations)));

                $organizations = $organizations->orWhereIn('organization_recordid', array_unique($organizationIds));
            }
        }
        $organizations = $organizations->paginate(20);
        $organization_tag_list = Organization::whereNotNull('organization_tag')->select('organization_tag')->pluck('organization_tag')->toArray();

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
        $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');

        return view('frontEnd.organizations.index', compact('organizations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'organization_tag_list', 'organizationStatus'));
    }

    public function tb_organizations(Request $request)
    {
        // $organizations = Organization::orderBy('organization_recordid')->paginate(20);
        // $source_data = Source_data::find(1);

        // return view('backEnd.tables.tb_organization', compact('organizations', 'source_data'));
        try {
            $organizations = Organization::select('*');
            $organization_tags = OrganizationTag::pluck('tag', 'id');
            $service_tags = ServiceTag::pluck('tag', 'id');
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
            $saved_filters = OrganizationTableFilter::where('user_id', Auth::id())->get();

            if (!$request->ajax()) {
                return view('backEnd.tables.tb_organization', compact('organizations', 'organization_tags', 'organizationStatus', 'service_tags', 'saved_filters'));
            }
            return DataTables::of($organizations)
                ->editColumn('services', function ($row) {
                    $service_name = '';
                    if (isset($row->services)) {
                        foreach ($row->services as $key => $service) {
                            $service_name .= '<span class="badge bg-green"> ' . $service->service_name . '</span>';
                        }
                    }
                    return $service_name;
                })
                ->editColumn('phones', function ($row) {
                    $phone_number = '';
                    if (isset($row->phones)) {
                        foreach ($row->phones as $key => $phone) {
                            $phone_number .= '<span class="badge bg-blue"> ' . $phone->phone_number . '</span>';
                        }
                    }
                    return $phone_number;
                })
                ->editColumn('location', function ($row) {
                    $location_name = '';
                    if (isset($row->location)) {
                        foreach ($row->location as $key => $location) {
                            $location_name .= '<span class="badge bg-blue"> ' . $location->location_name . '</span>';
                        }
                    }
                    return $location_name;
                })
                ->editColumn('organization_details', function ($row) {
                    $organization_detail = '';
                    if (isset($row->organization_details)) {
                        foreach ($row->organization_details as $key => $organization_detail) {
                            $organization_detail .= '<span class="badge bg-purple"> ' . $organization_detail->detail_value . '</span>';
                        }
                    }
                    return $organization_detail;
                })
                ->editColumn('contact_name', function ($row) {
                    $location_name = '';
                    if (isset($row->contact()->first()->contact_name)) {
                        $location_name .= '<span class="badge bg-red">' . $row->contact()->first()->contact_name . '</span>';
                    }
                    return $location_name;
                })
                ->editColumn('bookmark', function ($row) {
                    if ($row->bookmark && $row->bookmark == 1) {
                        return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="' . ($row->bookmark ? $row->bookmark : 0) . '"><img src="/images/bookmark.svg"></a>';
                    } else {
                        return '<a href="javascript:void(0)" class="clickBookmark" data-id="' . $row->id . '" data-value="0"><img src="/images/unbookmark.svg"></a>';
                    }
                })
                ->editColumn('organization_description', function ($row) {
                    $organization_description = Str::limit($row->organization_description, 20, ' ...');

                    return $organization_description;
                })
                ->editColumn('organization_url', function ($row) {
                    return '<a href="' . $row->organization_url . '" target="_blank" style="text-decoration: underline;color: #0097c9;">' . $row->organization_url . '</a>';
                })
                ->editColumn('organization_name', function ($row) {
                    return '<a href="/organizations/' . $row->organization_recordid . '" target="_blank" style="text-decoration: underline;color: #0097c9;">' . $row->organization_name . '</a>';
                })
                ->editColumn('organization_status_x', function ($row) {
                    return $row->status_data ? $row->status_data->status : '';
                })
                ->editColumn('last_verified_by', function ($row) {
                    // return $row->get_latest_updated($row, 'updated_by');
                    return $row->get_last_verified_by ? $row->get_last_verified_by->first_name . ' ' . $row->get_last_verified_by->last_name : '';
                })
                ->editColumn('updated_by', function ($row) {
                    return $row->get_latest_updated($row, 'updated_by');
                    // return $row->get_updated_by ? $row->get_updated_by->first_name . ' ' . $row->get_updated_by->last_name : '';
                })
                ->editColumn('latest_updated_date', function ($row) {
                    $row->get_latest_updated($row, 'updated_at');

                    return date('Y-m-d H:i:s', strtotime($row->latest_updated_date));

                    // return $row->get_updated_date($row);
                })
                ->addColumn('last_note_date', function ($row) {
                    $note = SessionData::where('session_organization', $row->organization_recordid)->orderBy('id', 'desc')->first();

                    return $note ? '<a href="/organization_notes/' . $row->organization_recordid . '"> ' . $note->created_at . '</a>' : "";
                })
                ->addColumn('last_edit_date', function ($row) {
                    $audit = Audit::where('auditable_id', $row->organization_recordid)->orderBy('id', 'desc')->first();

                    return $audit ? '<a href="/organization_edits/0/' . $row->organization_recordid . '"> ' . $audit->created_at . '</a>' : "";
                })
                ->addColumn('service_tag', function ($row) {
                    $tags = [];
                    if (isset($row->services)) {
                        $services = $row->services;
                        foreach ($services as $key => $value) {
                            if ($value->service_tag) {
                                $tagsArray = explode(',', $value->service_tag);
                                foreach ($tagsArray as $key => $value1) {
                                    $service_tag = ServiceTag::whereId($value1)->first();
                                    if ($service_tag) {
                                        $tags[] = $service_tag->tag;
                                    }
                                }
                            }
                        }
                    }
                    return count($tags) > 0 ? implode(',', $tags) : '';
                })
                ->editColumn('organization_tag', function ($row) {
                    $organization_tag = [];
                    if ($row->organization_tag) {
                        $organization_tags = explode(',', $row->organization_tag);
                        foreach ($organization_tags as $key => $value) {
                            $tag = OrganizationTag::whereId($value)->first();
                            if ($tag) {
                                $organization_tag[] = '<a href="javascript:void(0)" data-id="' . $row->organization_recordid . '" class="organizationTags" data-tags="' . $row->organization_tag . '"> ' . $tag->tag . '</a>';
                            }
                        }
                        return implode(',', $organization_tag);
                    } else {
                        return '<button type="button" class="btn btn-sm btn-primary organizationTags" data-id="' . $row->organization_recordid . '" data-tags="">Add Tag</button>';
                    }
                })
                ->editColumn('organization_status_x', function ($row) {

                    $organization_status = [];
                    if ($row->organization_status_x) {
                        $organization_statuses = explode(',', $row->organization_status_x);
                        foreach ($organization_statuses as $key => $value) {
                            $status = OrganizationStatus::whereId($value)->first();
                            if ($status) {
                                $organization_status[] = '<a href="javascript:void(0)" data-id="' . $row->organization_recordid . '" class="organizationStatuses" data-status="' . $status->id . '"> ' . $status->status . '</a>';
                            }
                        }
                        return implode(',', $organization_status);
                    } else {
                        return '<button type="button" class="btn btn-sm btn-primary organizationStatuses" data-id="' . $row->organization_recordid . '" data-status="">Add Status</button>';
                    }
                })
                ->filter(function ($query) use ($request) {
                    $extraData = $request->get('extraData');

                    if ($extraData) {

                        if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                            $organization_tags = count($extraData['organization_tag']) > 0 ? array_filter($extraData['organization_tag']) : [];
                            $query = $query->where(function ($q) use ($organization_tags) {
                                foreach ($organization_tags as $key => $value) {
                                    $q->orWhere('organization_tag', 'LIKE', '%' . $value . '%');
                                }
                            });
                        }
                        if (isset($extraData['organization_bookmark_only']) && $extraData['organization_bookmark_only'] != null && $extraData['organization_bookmark_only'] == "true") {
                            $query = $query->where('bookmark', 1);
                        }
                        if (isset($extraData['service_tag']) && $extraData['service_tag'] != null) {
                            $service_tags = count($extraData['service_tag']) > 0 ? array_filter($extraData['service_tag']) : [];

                            $organization_recordids = Service::where(function ($q) use ($service_tags) {
                                foreach ($service_tags as $key => $value) {
                                    $q->orWhere('service_tag', 'LIKE', '%' . $value . '%');
                                }
                            })->pluck('service_organization')->toArray();
                            $query->whereIn('organization_recordid', $organization_recordids);
                        }
                        if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
                            $query = $query->whereDate('last_verified_at', '>=', $extraData['start_date']);
                        }
                        if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
                            $query = $query->whereDate('last_verified_at', '<=', $extraData['end_date']);
                        }
                        if (isset($extraData['status']) && $extraData['status'] != null) {
                            // $statuses = count($extraData['status']) > 0 ?  array_filter($extraData['status']) : [];
                            // $query = $query->where(function ($q) use ($statuses) {
                            //     foreach ($statuses as $key => $value) {
                            //         $q->orWhere('organization_status_x', 'LIKE', '%' . $value . '%');
                            //     }
                            // });
                            $query->whereIn('organization_status_x', $extraData['status']);
                        }
                    }
                    return $query;
                }, true)
                ->rawColumns(['services', 'phones', 'location', 'organization_details', 'contact_name', 'last_note_date', 'last_edit_date', 'organization_name', 'organization_tag', 'organization_url', 'bookmark', 'organization_status_x'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in organization table index : ' . $th);
            return redirect()->back();
        }
    }

    public function organizations()
    {
        $organizations = Organization::orderBy('organization_name')->paginate(20);
        $organization_tag_list = Organization::whereNotNull('organization_tag')->select('organization_tag')->pluck('organization_tag')->toArray();

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

        return view('frontEnd.organizations.index', compact('organizations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'organization_tag_list'));
    }

    public function organization_tag(Request $request, $id)
    {
        try {
            $organization = Organization::where('organization_recordid', $id)->first();
            $organization->organization_tag = $request->organization_tag ? implode(',', $request->organization_tag) : '';
            $organization->latest_updated_date = Carbon::now();
            $organization->updated_by = Auth::id();
            $organization->updated_at = date("Y-m-d H:i:s");
            $organization->save();
            Session::flash('message', 'Tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function saveOrganizationFilter(Request $request)
    {
        try {
            $extraData = json_decode($request->extraData, true);
            $organization_tag = isset($extraData['organization_tag']) ? implode(',', $extraData['organization_tag']) : '';
            $status = isset($extraData['status']) ? implode(',', $extraData['status']) : '';
            $service_tag = isset($extraData['service_tag']) ? implode(',', $extraData['service_tag']) : '';
            $bookmark_only = isset($extraData['organization_bookmark_only']) ? $extraData['organization_bookmark_only'] : '';
            $start_date = isset($extraData['start_date']) ? $extraData['start_date'] : null;
            $end_date = isset($extraData['end_date']) ? $extraData['end_date'] : null;

            $start_verified = isset($extraData['start_verified']) ? $extraData['start_verified'] : null;
            $end_verified = isset($extraData['end_verified']) ? $extraData['end_verified'] : null;
            $start_updated = isset($extraData['start_updated']) ? $extraData['start_updated'] : null;
            $end_updated = isset($extraData['end_updated']) ? $extraData['end_updated'] : null;
            $last_updated_by = isset($extraData['last_updated_by']) ? $extraData['last_updated_by'] : null;
            $last_verified_by = isset($extraData['last_verified_by']) ? $extraData['last_verified_by'] : null;

            OrganizationTableFilter::create([
                'user_id' => Auth::id(),
                'filter_name' => $request->filter_name,
                'organization_tags' => $organization_tag,
                'service_tags' => $service_tag,
                'status' => $status,
                'bookmark_only' => $bookmark_only,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_verified' => $start_verified,
                'end_verified' => $end_verified,
                'start_updated' => $start_updated,
                'end_updated' => $end_updated,
                'last_updated_by' => $last_updated_by,
                'last_verified_by' => $last_verified_by,
            ]);
            Session::flash('message', 'Filter Saved Successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function saveOrganizationStatus(Request $request)
    {
        try {
            $id = $request->organization_recordid;
            $organization = Organization::where('organization_recordid', $id)->first();
            $organization->organization_status_x = $request->organisation_status ? $request->organisation_status : '';
            $organization->updated_at = date("Y-m-d H:i:s");
            $organization->latest_updated_date = Carbon::now();
            $organization->updated_by = Auth::id();
            $organizationStatus = OrganizationStatus::whereId($request->organisation_status)->first();
            if ($organizationStatus && $organizationStatus->status == 'Verified') {
                $organization->last_verified_at = Carbon::now();
                $organization->last_verified_by = Auth::id();
            }
            $organization->save();
            Session::flash('message', 'Organization Status added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function download($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $organization_name = $organization->organization_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.organization_download', compact('organization', 'layout'));

        return $pdf->download($organization_name . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $services_info_list = Service::select('service_recordid', 'service_name')->get();
        $organization_contacts_list = Contact::orderBy('contact_name')->select('contact_recordid', 'contact_name')->get();
        $rating_info_list = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];
        $all_contacts = Contact::orderBy('contact_name')->with('phone', 'service')->distinct()->get();
        $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'services', 'schedules', 'accessibilities', 'regions')->distinct()->get();
        $all_services = Service::orderBy('service_name')->with('phone', 'address', 'taxonomy', 'schedules', 'details')->get();
        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
        // service pop section
        $layout = Layout::find(1);
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
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        // end here
        // location section
        // $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

        // $address_cities = Address::select("address_city")->distinct()->get();
        // $address_states = Address::select("address_state_province")->distinct()->get();

        // $address_states_list = [];
        // foreach ($address_states as $key => $value) {
        //     $state = explode(", ", trim($value->address_state_province));
        //     $address_states_list = array_merge($address_states_list, $state);
        // }
        // $address_states_list = array_unique($address_states_list);

        // $address_city_list = [];
        // foreach ($address_cities as $key => $value) {
        //     $cities = explode(", ", trim($value->address_city));
        //     $address_city_list = array_merge($address_city_list, $cities);
        // }
        // $address_city_list = array_unique($address_city_list);
        $address_city_list = City::orderBy('city')->pluck('city', 'city');
        $address_states_list = State::orderBy('state')->pluck('state', 'state');

        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');
        $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);
        $regions = Region::pluck('region', 'id');

        $parent_organizations = Organization::pluck('organization_name', 'organization_recordid');

        $all_programs = Program::with('organization')->distinct()->get();

        $accessibilities = Accessibility::pluck('accessibility', 'id');

        return view('frontEnd.organizations.create', compact('map', 'services_info_list', 'organization_contacts_list', 'rating_info_list', 'all_contacts', 'all_locations', 'phone_languages', 'all_services', 'taxonomy_info_list', 'schedule_info_list', 'detail_info_list', 'address_info_list', 'service_info_list', 'address_states_list', 'address_city_list', 'phone_type', 'organizationStatus', 'all_phones', 'phone_language_data', 'regions', 'parent_organizations', 'all_programs', 'accessibilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'organization_name' => 'required',
            // 'organization_email' => 'required|email',
            // 'organization_description' => 'required',
        ]);
        if ($request->organization_email) {
            $this->validate($request, [
                'organization_email' => 'email',
            ]);
        }
        try {
            $organization = new Organization;

            $new_recordid = Organization::max("organization_recordid") + 1;
            $organization->organization_recordid = $new_recordid;
            $organization_recordid = $new_recordid;
            $layout = Layout::find(1);
            $organization->organization_name = $request->organization_name;
            // $organization->organization_status_x = $request->organization_status_x;
            if ($layout && $layout->default_organization_status) {
                $organization->organization_status_x = $layout->default_organization_status;
            }
            $organization->organization_alternate_name = $request->organization_alternate_name;
            $organization->organization_description = $request->organization_description;
            $organization->organization_email = $request->organization_email;
            $organization->organization_url = $request->organization_url;
            $organization->organization_legal_status = $request->organization_legal_status;
            $organization->organization_tax_status = $request->organization_tax_status;
            $organization->organization_tax_id = $request->organization_tax_id;
            $organization->organization_website_rating = $request->organization_website_rating;
            $organization->organization_code = $request->organization_code;
            $organization->facebook_url = $request->facebook_url;
            $organization->twitter_url = $request->twitter_url;
            $organization->instagram_url = $request->instagram_url;
            $organization->parent_organization = $request->parent_organization;
            $organization->funding = $request->funding;

            $organization->organization_year_incorporated = $request->organization_year_incorporated;

            if ($request->has('logo_type')) {
                $link = '';
                if ($request->logo_type == 'file' && $request->hasFile('logo')) {
                    $link = $request->file('logo')->store('uploads', 'public');
                    $link = Storage::url($link);
                } else if ($request->logo_type == 'url' && $request->logo) {
                    $link = $request->logo;
                } else {
                    $link = $organization->logo;
                }
                $organization->logo = $link;
            }

            $this->saveOrganizationProgram($request, $organization);

            // service section
            $service_recordids = $this->saveOrganizationService($request, $new_recordid);

            $service_recordids = is_array($service_recordids) ? array_values(array_filter($service_recordids)) : [];
            $organization->getServices()->sync($service_recordids);
            // contact section

            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                $contact_visibility = $request->contact_visibility && count($request->contact_visibility) > 0 ? $request->contact_visibility : [];
                $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
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
                        $contact->contact_organizations = $new_recordid;
                        // $contact->contact_phones = $request->contact_phone[$i];
                        if ($contact_service) {
                            $contact->contact_services = join(',', $contact_service[$i]);
                        } else {
                            $contact->contact_services = '';
                        }
                        $contact_service[$i] = is_array($contact_service[$i]) ? array_values(array_filter($contact_service[$i])) : [];
                        $contact->service()->sync($contact_service[$i]);

                        // this is contact phone section
                        for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                            $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                            if ($phone_info) {
                                $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                $phone_info->phone_type = $contact_phone_types[$i][$p];
                                $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
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
                                $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                $new_phone->save();
                                $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                array_push($contact_phone_recordid_list, $new_phone_recordid);
                            }
                        }
                        $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                    } else {
                        $updating_contact = Contact::where('contact_recordid', '=', $request->contact_recordid[$i])->first();
                        if ($updating_contact) {
                            $updating_contact->contact_name = $request->contact_name[$i];
                            $updating_contact->contact_title = $request->contact_title[$i];
                            $updating_contact->contact_email = $request->contact_email[$i];
                            $updating_contact->contact_department = $contact_department[$i];
                            $updating_contact->contact_organizations = $new_recordid;
                            $updating_contact->visibility = $contact_visibility[$i];
                            // $contact->contact_phones = $request->contact_phone[$i];
                            if ($contact_service) {
                                $updating_contact->contact_services = join(',', $contact_service[$i]);
                            } else {
                                $updating_contact->contact_services = '';
                            }
                            $contact_service[$i] = is_array($contact_service[$i]) ? array_values(array_filter($contact_service[$i])) : [];
                            $updating_contact->service()->sync($contact_service[$i]);

                            for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                if ($phone_info) {
                                    $updating_contact->contact_phones = $updating_contact->contact_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $contact_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
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
                                    $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    $updating_contact->contact_phones = $updating_contact->contact_phones . $new_phone_recordid . ',';
                                    array_push($contact_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                            $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
                            $updating_contact->phone()->sync($contact_phone_recordid_list);
                            $updating_contact->save();
                        }
                    }
                }
            }

            // location section
            $organization->organization_locations = '';
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0], true) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                // accessibility
                $location_accessibility = $request->location_accessibility && count($request->location_accessibility) > 0 ? json_decode($request->location_accessibility[0], true) : [];
                $location_accessibility_details = $request->location_accessibility_details && count($request->location_accessibility_details) > 0 ? json_decode($request->location_accessibility_details[0], true) : [];
                $location_regions = $request->location_regions && count($request->location_regions) > 0 ? json_decode($request->location_regions[0], true) : [];

                // holiday section
                $location_holiday_start_dates = $request->location_holiday_start_dates ? json_decode($request->location_holiday_start_dates, true) : [];
                $location_holiday_end_dates = $request->location_holiday_end_dates ? json_decode($request->location_holiday_end_dates, true) : [];
                $location_holiday_open_ats = $request->location_holiday_open_ats ? json_decode($request->location_holiday_open_ats, true) : [];
                $location_holiday_close_ats = $request->location_holiday_close_ats ? json_decode($request->location_holiday_close_ats, true) : [];
                $location_holiday_closeds = $request->location_holiday_closeds ? json_decode($request->location_holiday_closeds, true) : [];

                for ($i = 0; $i < count($request->location_name); $i++) {
                    $location_address_recordid_list = [];
                    $location_phone_recordid_list = [];

                    // location phone section
                    $location_phone_numbers = $request->location_phone_numbers && count($request->location_phone_numbers) ? json_decode($request->location_phone_numbers[0]) : [];
                    $location_phone_extensions = $request->location_phone_extensions && count($request->location_phone_extensions) ? json_decode($request->location_phone_extensions[0]) : [];
                    $location_phone_types = $request->location_phone_types && count($request->location_phone_types) ? json_decode($request->location_phone_types[0]) : [];
                    $location_phone_languages = $request->location_phone_languages && count($request->location_phone_languages) ? json_decode($request->location_phone_languages[0]) : [];
                    $location_phone_descriptions = $request->location_phone_descriptions && count($request->location_phone_descriptions) ? json_decode($request->location_phone_descriptions[0]) : [];
                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $newLocationId = Location::max('location_recordid') + 1;
                        $location->location_recordid = $newLocationId;
                        $location->location_name = $request->location_name[$i];
                        $location->location_organization = $new_recordid;
                        $organization->organization_locations = $organization->organization_locations . ',' . $newLocationId;

                        $location->location_alternate_name = isset($location_alternate_name[$i]) ? $location_alternate_name[$i] : null;
                        $location->location_transportation = isset($location_transporation[$i]) ? $location_transporation[$i] : null;
                        $location->location_description = isset($location_description[$i]) ? $location_description[$i] : null;
                        $location->location_details = isset($location_details[$i]) ? $location_details[$i] : null;

                        // accessesibility

                        if (isset($location_accessibility[$i]) && isset($location_accessibility_details[$i])) {
                            // Accessibility::create([
                            //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                            //     'accessibility' => $location_accessibility[$i],
                            //     'accessibility_details' => $location_accessibility_details[$i],
                            //     'accessibility_location' => $newLocationId
                            // ]);
                            $location->accessibility_recordid = $location_accessibility[$i];
                            $location->accessibility_details = $location_accessibility_details[$i];
                        }
                        if (isset($location_regions[$i])) {
                            $location->regions()->sync($location_regions[$i]);
                        }

                        if ($location_service) {
                            $location->location_services = join(',', $location_service[$i]);
                        } else {
                            $location->location_services = '';
                        }
                        $location_service[$i] = is_array($location_service[$i]) ? array_values(array_filter($location_service[$i])) : [];
                        $location->services()->sync($location_service[$i]);

                        if ($location_schedules[$i]) {
                            $location->location_schedule = join(',', $location_schedules[$i]);
                        } else {
                            $location->location_schedule = '';
                        }
                        $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
                        $location->schedules()->sync($location_schedules[$i]);

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
                            $new_address = new Address;
                            $new_address_recordid = Address::max('address_recordid') + 1;
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
                                    $phone_info->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
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
                                    $new_phone->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = $this->saveLocationSchedule($request, $i, $location_recordid);

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
                        $location->location_phones = '';
                        $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                        $location->phones()->sync($location_phone_recordid_list);
                        $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];

                        $location->address()->sync($location_address_recordid_list);
                        $location->save();
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_organization = $new_recordid;
                            // location address
                            $organization->organization_locations = $organization->organization_locations . ',' . $request->location_recordid[$i];

                            $location->location_alternate_name = $location_alternate_name[$i];
                            $location->location_transportation = $location_transporation[$i];
                            $location->location_description = $location_description[$i];
                            $location->location_details = $location_details[$i];


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

                            if ($location_service) {
                                $location->location_services = join(',', $location_service[$i]);
                            } else {
                                $location->location_services = '';
                            }
                            $location_service[$i] = is_array($location_service[$i]) ? array_values(array_filter($location_service[$i])) : [];
                            $location->services()->sync($location_service[$i]);

                            if ($location_schedules[$i]) {
                                $location->location_schedule = join(',', $location_schedules[$i]);
                            } else {
                                $location->location_schedule = '';
                            }
                            $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
                            $location->schedules()->sync($location_schedules[$i]);

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
                            // this is location phone section
                            if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $location_phone_numbers[$i][$p])->first();
                                    if ($phone_info) {
                                        // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
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
                                        $new_phone->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            Schedule::where('locations', $location->location_recordid)->delete();
                            // schedule section
                            $schedule_locations = $this->saveLocationSchedule($request, $i, $location->location_recordid);
                            // $location->schedules()->sync($schedule_locations);

                            // if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
                            //     $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            //     for ($s = 0; $s < 7; $s++) {
                            //         $schedules = Schedule::where('schedule_locations', $location->location_recordid)->where('weekday', $weekdays[$s])->first();
                            //         if ($schedules) {
                            //             $schedules->weekday = $weekdays[$s];
                            //             $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                            //             $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                            //             if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                            //                 $schedules->schedule_closed = $s + 1;
                            //             } else {
                            //                 $schedules->schedule_closed = null;
                            //             }
                            //             $schedules->save();
                            //             $schedule_locations[] = $schedules->schedule_recordid;
                            //         } else {
                            //             $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                            //             $schedules = new Schedule();
                            //             $schedules->schedule_recordid = $schedule_recordid;
                            //             $schedules->schedule_locations = $location->location_recordid;
                            //             $schedules->weekday = $weekdays[$s];
                            //             $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                            //             $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : '';
                            //             if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                            //                 $schedules->schedule_closed = $s + 1;
                            //             } else {
                            //                 $schedules->schedule_closed = null;
                            //             }
                            //             $schedules->save();
                            //             $schedule_locations[] = $schedule_recordid;
                            //         }
                            //     }
                            // }

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
                            $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                            $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];
                            $location->phones()->sync($location_phone_recordid_list);
                            $location->address()->sync($location_address_recordid_list);

                            // $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                            $location->location_phones = '';
                            $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                            $location->save();
                        }
                    }
                }
            }


            $phone_recordids = [];
            // if ($request->organization_phones) {
            //     foreach ($request->organization_phones as $key => $number) {
            //         $phone = Phone::where('phone_number', $number);
            //         if ($phone->count() > 0) {
            //             $phone_record_id = $phone->first()->phone_recordid;
            //             array_push($phone_recordids, $phone_record_id);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $number;
            //             $new_phone->save();
            //             array_push($phone_recordids, $new_phone_recordid);
            //         }
            //     }
            // }
            if ($request->organization_phones) {
                $organization_phone_number_list = $request->organization_phones;
                $organization_phone_extension_list = $request->phone_extension;
                $organization_phone_type_list = $request->phone_type;
                $organization_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $organization_phone_description_list = $request->phone_description;
                $organization_main_priority_list = $request->main_priority;
                for ($i = 0; $i < count($organization_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $organization_phone_number_list[$i])->first();
                    if ($phone_info) {
                        // $organization->organization_phones = $organization->organization_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $organization_phone_number_list[$i];
                        $phone_info->phone_extension = $organization_phone_extension_list[$i];
                        $phone_info->phone_type = $organization_phone_type_list[$i];
                        $phone_info->phone_language = $organization_phone_language_list && isset($organization_phone_language_list[$i]) && is_array($organization_phone_language_list[$i]) ? implode(',', $organization_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $organization_phone_description_list[$i];
                        if (isset($organization_main_priority_list[0]) && $organization_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        // $phone_info->phone_type = $organization_phone_type_list ? $organization_phone_type_list[$i] : '';
                        // $phone_info->phone_language = $organization_phone_language_list ? $organization_phone_language_list[$i] : '';
                        $phone_info->phone_description = $organization_phone_description_list[$i];
                        $phone_info->save();
                        array_push($phone_recordids, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $organization_phone_number_list[$i];
                        $new_phone->phone_extension = $organization_phone_extension_list[$i];
                        $new_phone->phone_type = $organization_phone_type_list ? $organization_phone_type_list[$i] : '';
                        $new_phone->phone_language = $organization_phone_language_list && isset($organization_phone_language_list[$i]) && is_array($organization_phone_language_list[$i]) ? implode(',', $organization_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $organization_phone_description_list[$i];
                        if (isset($organization_main_priority_list[0]) && $organization_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        // $organization->organization_phones = $organization->organization_phones . $new_phone_recordid . ',';
                        array_push($phone_recordids, $new_phone_recordid);
                    }
                }
            }
            $phone_recordids = is_array($phone_recordids) ? array_values(array_filter($phone_recordids)) : [];
            $organization->phones()->sync($phone_recordids);

            // if ($request->organization_locations) {
            //     $organization->organization_locations = join(',', $request->organization_locations);
            // } else {
            //     $organization->organization_locations = '';
            // }

            $organization->updated_at = Carbon::now();
            $organization->created_by = Auth::id();

            $organization->save();

            $user = User::whereId(Auth::id())->first();
            if ($user && $user->role_id != 1) {
                $userOrganizationIds = explode(',', $user->user_organization);
                $userOrganizationIds = array_merge($userOrganizationIds, [$organization_recordid]);
                $user->organizations()->sync($userOrganizationIds);
                $user->user_organization = implode(',', $userOrganizationIds);
                $user->save();
            }

            $audit = Audit::where('auditable_id', $organization_recordid)->first();

            if ($audit) {
                $audit->auditable_id = $organization_recordid;
                $audit->save();
            }
            Session::flash('message', 'Organization created successfully!');
            Session::flash('status', 'success');
            return redirect('organizations');
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in create organization : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('organizations');
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
        // $organization= Organization::find($id);
        // return response()->json($organization);
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');
        if ($organization && (Auth::check() || (!Auth::check() && $organization->organization_status_x && isset($organizationStatus[$organization->organization_status_x]) && ($organizationStatus[$organization->organization_status_x] != 'Out of Business' && $organizationStatus[$organization->organization_status_x] != 'Inactive')) || !$organization->organization_status_x)) {
            $layout = Layout::find(1);
            $orgService = $organization->organization_service_data->with('locations')->get();
            $serviceLocationIds = [];
            foreach ($orgService as $key => $value) {
                if ($value->locations)
                    foreach ($value->locations as $key => $location) {
                        $serviceLocationIds[] = $location->location_recordid;
                        // $locations->push($location->with('services', 'address', 'phones')->first());
                    }
            }

            $organization_services = $organization->organization_service_data->paginate(10);
            $service_interations = $organization->organization_service_count > 0 ? $organization->organization_service_data->pluck('service_name', 'service_recordid') : [];

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

            $organization_contacts_recordid_list = explode(',', $organization->organization_contact);
            $contact_info_list = Contact::whereIn('contact_recordid', $organization_contacts_recordid_list)->get();

            $organization_locations_recordid_list = explode(',', $organization->organization_locations);
            // $location_info_list = Location::whereIn('location_recordid', $organization_locations_recordid_list)->orderBy('location_recordid')->paginate(10);
            // $location_info_list = array_map('intval', explode(',', $organization->organization_locations));
            $location_info_list = $organization->location->pluck('location_recordid')->toArray();
            //            $locationIds = $organization->location()->pluck('location_recordid')->toArray();
            //            if (count($locationIds) > 0) {
            //                $location_info_list = array_merge($location_info_list, $locationIds);
            //            }
            if (count($serviceLocationIds) > 0) {
                $location_info_list = array_merge($location_info_list, $serviceLocationIds);
            }
            $locations = Location::whereIn('location_recordid', $location_info_list)->with('phones', 'address', 'services', 'schedules')->get();
            $location_info_list = Location::whereIn('location_recordid', $location_info_list)->with('phones', 'address', 'services', 'schedules')->get();

            if ($locations) {
                $locations->filter(function ($value, $key) {
                    // $value->service_name = $value->services && count($value->services) > 0 ? $value->services->service_name : '';
                    // $value->service_recordid = $value->services && count($value->services) > 0 ? $value->services->service_recordid : '';
                    $value->organization_name = $value->organization ? $value->organization->organization_name : '';
                    $value->organization_recordid = $value->organization ? $value->organization->organization_recordid : '';
                    $value->address_name = $value->address && count($value->address) > 0 ? $value->address[0]->address_1 : '';
                    return true;
                });
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
            $existing_tag_element_list = Organization::whereNotNull('organization_tag')->get()->pluck('organization_tag');
            $existing_tags = [];
            foreach ($existing_tag_element_list as $key => $existing_tag_element) {
                $existing_tag_list = explode(",", $existing_tag_element);

                foreach ($existing_tag_list as $key => $existing_tag) {
                    if (!in_array($existing_tag, $existing_tags, true)) {
                        array_push($existing_tags, $existing_tag);
                    }
                }
            }
            $existing_tags = is_array($existing_tags) ? array_filter($existing_tags) : [];
            $tagList = [];
            foreach ($existing_tags as $t) {
                $tagList[$t] = $t;
            }
            $allTags = OrganizationTag::orderBy('order')->whereNotNull('tag')->pluck('tag', 'id')->put('create_new', '+ Create New');

            // $disposition_list = ['Success', 'Limited Success', 'Unable to Connect'];
            $comment_list = Comment::where('comments_organization', '=', $id)->get();
            $session_list = SessionData::where('session_organization', '=', $id)->select('session_recordid', 'session_edits', 'session_performed_at', 'session_verification_status')->get();

            $organizationAudits = $this->commonController->organizationSection($organization);

            $disposition_list = Disposition::pluck('name', 'id');
            $method_list = InteractionMethod::pluck('name', 'id');
            $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');

            return view('frontEnd.organizations.show', compact('organization', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree', 'contact_info_list', 'organization_services', 'location_info_list', 'existing_tags', 'comment_list', 'session_list', 'disposition_list', 'method_list', 'tagList', 'organizationAudits', 'allTags', 'service_interations', 'organizationStatus', 'layout', 'id'));
        } else {
            Session::flash('message', 'This record has been deleted.');
            Session::flash('status', 'warning');
            return redirect('organizations');
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
        $organization = Organization::where('organization_recordid', '=', $id)->with('contact', 'services')->first();
        if ($organization) {
            if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $id) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin') || Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'Section Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags), explode(',', $organization->organization_tag))) > 0)) {

                // $organization_service_list = explode(',', $organization->services());
                $contactServices = $organization->services()->pluck('service_name', 'service_recordid');
                $contactOrganization = $organization->contact;

                // $services_info_list = Service::select('service_recordid', 'service_name')->get();

                $organization_services_Ids1 = $organization->services() ? $organization->services()->pluck('service_recordid')->toArray() : [];
                $organization_services_Ids2 = [];
                if (count($organization->services) == 0) {

                    $organization_services_Ids2 = count($organization->getServices) > 0 ? $organization->getServices->pluck('service_recordid')->toArray() : [];
                }

                $organization_services_record_ids = array_merge($organization_services_Ids1, $organization_services_Ids2);

                $organization_service_list = [];

                $organization_locations_data = Location::where('location_organization', $organization->organization_recordid)->get();


                $organizationContacts = Contact::where('contact_organizations', '=', $id)->with('service')->get();

                $phone_info_list = explode(',', $organization->organization_phones);

                $location_info_list = explode(',', $organization->organization_locations);
                $locationIds = $organization->location()->pluck('location_recordid')->toArray();
                if (count($locationIds) > 0) {
                    $location_info_list = array_merge($location_info_list, $locationIds);
                }
                $location_info_list = array_values(array_unique(array_filter($location_info_list)));

                $rating_info_list = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];
                $organization_locations_data = Location::whereIn('location_recordid', $location_info_list)->with('phones', 'address', 'services', 'schedules')->get();

                $organization_locations_data = $organization_locations_data->filter(function ($value) {
                    $address = $value->address && count($value->address) > 0 ? $value->address[count($value->address) - 1] : '';
                    $phones = $value->phones && count($value->phones) > 0 ? $value->phones[count($value->phones) - 1] : '';
                    $value->location_address = $address ? $address->address_1 : '';
                    $value->location_city = $address ? $address->address_city : '';
                    $value->location_state = $address ? $address->address_state_province : '';
                    $value->location_zipcode = $address ? $address->address_postal_code : '';
                    $value->location_phone = $phones ? $phones->phone_number : '';
                    return true;
                });
                $phone_languages = Language::orderBy('order')->pluck('language', 'language_recordid');
                $all_contacts = Contact::orderBy('contact_name')->with('phone', 'service')->distinct()->get();
                $all_locations = Location::orderBy('location_name')->with('phones', 'address', 'services', 'schedules')->distinct()->get();
                $all_services = Service::with('phone', 'address', 'taxonomy', 'schedules', 'details')->get();
                $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
                // service pop section
                $layout = Layout::find(1);
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
                $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();
                $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
                // end here
                // location section
                $service_info_list = $organization->services()->select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
                // $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_recordid')->distinct()->get();
                $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
                $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

                $address_city_list = City::orderBy('city')->pluck('city', 'city');
                $address_states_list = State::orderBy('state')->pluck('state', 'state');

                $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');

                $service_alternate_name = [];
                $service_program = [];
                $service_status = [];
                $service_taxonomies = [];
                $service_application_process = [];
                $service_wait_time = [];
                $service_fees = [];
                $service_accreditations = [];
                $service_licenses = [];
                $service_schedules = [];
                $service_details = [];
                $service_address = [];
                $service_metadata = [];
                $service_airs_taxonomy_x = [];
                foreach ($organization_service_list as $key => $value) {

                    $service_alternate_name[] = $value->service_alternate_name;
                    $service_program[] = $value->service_program;
                    $service_status[] = $value->service_status == 'Verified' ? ['1'] : ['2'];
                    $service_taxonomies[] = $value->service_taxonomy ? explode(',', $value->service_taxonomy) : [];
                    $service_application_process[] = $value->service_application_process;
                    $service_wait_time[] = $value->service_wait_time;
                    $service_fees[] = $value->service_fees;
                    $service_accreditations[] = $value->service_accreditations;
                    $service_licenses[] = $value->service_licenses;
                    $service_schedules[] = $value->service_schedule ? explode(',', $value->service_schedule) : [];
                    $service_details[] = $value->service_details ? explode(',', $value->service_details) : [];
                    $service_address[] = $value->service_address ? explode(',', $value->service_address) : [];
                    $service_metadata[] = $value->service_metadata;
                    $service_airs_taxonomy_x[] = $value->service_airs_taxonomy_x;
                }

                $service_alternate_name = json_encode($service_alternate_name);
                $service_program = json_encode($service_program);
                $service_status = json_encode($service_status);
                $service_taxonomies = json_encode($service_taxonomies);
                $service_application_process = json_encode($service_application_process);
                $service_wait_time = json_encode($service_wait_time);
                $service_fees = json_encode($service_fees);
                $service_accreditations = json_encode($service_accreditations);
                $service_licenses = json_encode($service_licenses);
                $service_schedules = json_encode($service_schedules);
                $service_details = json_encode($service_details);
                $service_address = json_encode($service_address);
                $service_metadata = json_encode($service_metadata);
                $service_airs_taxonomy_x = json_encode($service_airs_taxonomy_x);

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
                foreach ($organization_locations_data as $key => $locationData) {
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
                        $location_accessibility[] = $locationData->accessibility_recordid;
                        $location_accessibility_details[] = $locationData->accessibility_details;
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
                foreach ($organizationContacts as $key => $contactData) {

                    $contact_service[] = $contactData->service ? $contactData->service->pluck('service_recordid')->toarray() : [];
                    $contact_department[] = $contactData->contact_department;
                }
                $contact_service = json_encode($contact_service);
                $contact_department = json_encode($contact_department);

                // contact phone section
                $contact_phones_data = $organizationContacts;
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
                $location_phones_data = $organization_locations_data;
                $location_phone_numbers = [];
                $location_phone_extensions = [];
                $location_phone_types = [];
                $location_phone_languages = [];
                $location_phone_descriptions = [];

                foreach ($location_phones_data as $key => $phonelocation) {
                    if (count($phonelocation->phones) > 0) {
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
                        $location_phone_languages[$key][] = '';
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

                foreach ($organization_locations_data as $key => $value) {
                    if ($value->schedules && !empty($value->schedules) && count($value->schedules) > 0) {
                        foreach ($value->schedules as $key1 => $schedule) {
                            if ($schedule->schedule_holiday == 1) {
                                $location_holiday_start_dates[$j][] = $schedule->dtstart;
                                $location_holiday_end_dates[$j][] = $schedule->until;
                                $location_holiday_open_ats[$j][] = $schedule->opens;
                                $location_holiday_close_ats[$j][] = $schedule->closes;
                                $location_holiday_closeds[$j][] = $schedule->schedule_closed;
                            } else {
                                $schedule_closed_array = explode(',', $schedule->schedule_closed);
                                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                for ($i = 0; $i < 7; $i++) {
                                    if (str_contains($schedule->weekday, $weekdays[$i])) {
                                        if (in_array(($i + 1), $schedule_closed_array)) {

                                            ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = in_array(($i + 1), $schedule_closed_array) ? ($i + 1) : '';
                                        } else {
                                            ${'opens_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->opens;
                                            ${'closes_location_' . $weekdays[$i] . '_datas'}[$j] = $schedule->closes;
                                        }
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
                        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        for ($i = 0; $i < 7; $i++) {
                            // if ($schedule->weekday == $weekdays[$i]) {
                            ${'opens_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                            ${'closes_location_' . $weekdays[$i] . '_datas'}[$j] = '';
                            ${'schedule_closed_' . $weekdays[$i] . '_datas'}[$j] = '';
                            // }
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

                $phone_language_data = [];
                $phone_language_name = [];
                if ($organization->phones) {
                    foreach ($organization->phones as $key => $value) {
                        $phone_language_data[$key] = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languageId = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languages = Language::whereIn('language_recordid', $languageId)->pluck('language')->unique()->toArray();
                        $phone_language_name[$key] = implode(', ', $languages);
                    }
                }
                $phone_language_data = json_encode($phone_language_data);
                $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
                $organizationAudits = $this->commonController->organizationSection($organization);
                $regions = Region::pluck('region', 'id');

                $organizationStatus = OrganizationStatus::orderBy('order')->pluck('status', 'id');

                $disposition_list = Disposition::pluck('name', 'id');
                $method_list = InteractionMethod::pluck('name', 'id');

                $parent_organizations = Organization::where('id', '!=', $organization->id)->pluck('organization_name', 'organization_recordid');

                $all_programs = Program::with('organization')->distinct()->get();

                $accessibilities = Accessibility::pluck('accessibility', 'id');

                return view('frontEnd.organizations.edit', compact('organization', 'map', 'organization_service_list', 'phone_info_list', 'location_info_list', 'rating_info_list', 'all_contacts', 'organizationContacts', 'organization_locations_data', 'all_locations', 'phone_languages', 'all_services', 'taxonomy_info_list', 'schedule_info_list', 'detail_info_list', 'address_info_list', 'service_info_list', 'address_states_list', 'address_city_list', 'phone_type', 'service_alternate_name', 'service_program', 'service_status', 'service_taxonomies', 'service_application_process', 'service_wait_time', 'service_fees', 'service_accreditations', 'service_licenses', 'service_schedules', 'service_details', 'service_address', 'service_metadata', 'service_airs_taxonomy_x', 'location_alternate_name', 'location_transporation', 'location_service', 'location_schedules', 'location_description', 'location_details', 'contact_service', 'contact_department', 'contact_phone_numbers', 'contact_phone_extensions', 'contact_phone_types', 'contact_phone_languages', 'contact_phone_descriptions', 'location_phone_numbers', 'location_phone_extensions', 'location_phone_types', 'location_phone_languages', 'location_phone_descriptions', 'opens_location_monday_datas', 'closes_location_monday_datas', 'schedule_closed_monday_datas', 'opens_location_tuesday_datas', 'closes_location_tuesday_datas', 'schedule_closed_tuesday_datas', 'opens_location_wednesday_datas', 'closes_location_wednesday_datas', 'schedule_closed_wednesday_datas', 'opens_location_thursday_datas', 'closes_location_thursday_datas', 'schedule_closed_thursday_datas', 'opens_location_friday_datas', 'closes_location_friday_datas', 'schedule_closed_friday_datas', 'opens_location_saturday_datas', 'closes_location_saturday_datas', 'schedule_closed_saturday_datas', 'opens_location_sunday_datas', 'closes_location_sunday_datas', 'schedule_closed_sunday_datas', 'location_holiday_start_dates', 'location_holiday_end_dates', 'location_holiday_open_ats', 'location_holiday_close_ats', 'location_holiday_closeds', 'phone_language_data', 'organizationAudits', 'organizationStatus', 'contactServices', 'contactOrganization', 'phone_language_name', 'all_phones', 'location_accessibility', 'location_accessibility_details', 'location_regions', 'regions', 'method_list', 'disposition_list', 'parent_organizations', 'all_programs', 'accessibilities', 'external_identifier', 'external_identifier_type', 'accessesibility_url'));
            } else {
                Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                return redirect('/');
            }
        } else {
            Session::flash('message', 'This record has been deleted.');
            Session::flash('status', 'warning');
            return redirect('organizations');
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
        // dd($request);
        $this->validate($request, [
            'organization_name' => 'required',
            // 'organization_description' => 'required',
        ]);
        if ($request->organization_email) {
            $this->validate($request, [
                'organization_email' => 'email',
            ]);
        }
        try {
            $organization = Organization::find($id);
            $organization->organization_name = $request->organization_name;
            $organization->organization_alternate_name = $request->organization_alternate_name;
            $organization->organization_description = $request->organization_description;
            $organization->organization_email = $request->organization_email;
            $organization->organization_url = $request->organization_url;
            $organization->organization_legal_status = $request->organization_legal_status;
            $organization->organization_tax_status = $request->organization_tax_status;
            $organization->organization_tax_id = $request->organization_tax_id;
            $organization->organization_website_rating = $request->organization_website_rating;
            $organization->organization_code = $request->organization_code;
            // $organization->organization_status_x = $request->organization_status_x;
            $organization->facebook_url = $request->facebook_url;
            $organization->twitter_url = $request->twitter_url;
            $organization->instagram_url = $request->instagram_url;
            $organization->parent_organization = $request->parent_organization;
            $organization->funding = $request->funding;

            $organization->organization_year_incorporated = $request->organization_year_incorporated;


            if ($request->has('logo_type')) {
                $link = '';
                if ($request->logo_type == 'file' && $request->hasFile('logo')) {
                    $link = $request->file('logo')->store('uploads', 'public');
                    $link = Storage::url($link);
                } else if ($request->logo_type == 'url' && $request->logo) {
                    $link = $request->logo;
                } else {
                    $link = $organization->logo;
                }
                $organization->logo = $link;
            }

            $oldOrganizationContact = Contact::where('contact_organizations', '=', $id)->get();
            foreach ($oldOrganizationContact as $contact) {
                $contact->contact_organizations = '';
                $contact->save();
            }


            $this->saveOrganizationProgram($request, $organization);


            $phone_recordids = [];

            if ($request->organization_phones) {
                $organization_phone_number_list = $request->organization_phones;
                $organization_phone_extension_list = $request->phone_extension;
                $organization_phone_type_list = $request->phone_type;
                $organization_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $organization_phone_description_list = $request->phone_description;
                $organization_main_priority_list = $request->main_priority;
                for ($i = 0; $i < count($organization_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $organization_phone_number_list[$i])->first();
                    if ($phone_info) {
                        // $organization->organization_phones = $organization->organization_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $organization_phone_number_list[$i];
                        $phone_info->phone_extension = $organization_phone_extension_list[$i];
                        $phone_info->phone_type = $organization_phone_type_list ? $organization_phone_type_list[$i] : '';
                        $phone_info->phone_language = $organization_phone_language_list && isset($organization_phone_language_list[$i]) && is_array($organization_phone_language_list[$i]) ? implode(',', $organization_phone_language_list[$i]) : '';
                        // $phone_info->phone_type = $organization_phone_type_list ? $organization_phone_type_list[$i] : '';
                        // $phone_info->phone_language = $organization_phone_language_list && isset($organization_phone_language_list[$i]) ? implode(',', $organization_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $organization_phone_description_list[$i];
                        if (isset($organization_main_priority_list[0]) && $organization_main_priority_list[0] == $i) {
                            $phone_info->main_priority = '1';
                        } else {
                            $phone_info->main_priority = '0';
                        }
                        $phone_info->save();
                        array_push($phone_recordids, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $organization_phone_number_list[$i];
                        $new_phone->phone_extension = $organization_phone_extension_list[$i];
                        $new_phone->phone_type = $organization_phone_type_list ? $organization_phone_type_list[$i] : '';
                        $new_phone->phone_language = $organization_phone_language_list && isset($organization_phone_language_list[$i]) && is_array($organization_phone_language_list[$i]) ? implode(',', $organization_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $organization_phone_description_list[$i];
                        if (isset($organization_main_priority_list[0]) && $organization_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        // $organization->organization_phones = $organization->organization_phones . $new_phone_recordid . ',';
                        array_push($phone_recordids, $new_phone_recordid);
                    }
                }
            }
            $phone_recordids = is_array($phone_recordids) ? array_values(array_filter($phone_recordids)) : [];
            $organization->phones()->sync($phone_recordids);
            // dd($request);
            // contact section
            if ($request->contact_name && $request->contact_name[0] != null) {
                $contact_department = $request->contact_department && count($request->contact_department) > 0 ? json_decode($request->contact_department[0]) : [];
                $contact_visibility = $request->contact_visibility && count($request->contact_visibility) > 0 ? $request->contact_visibility : [];
                $contact_service = $request->contact_service && count($request->contact_service) > 0 ? json_decode($request->contact_service[0]) : [];
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
                        $contact->contact_organizations = $id;
                        // $contact->contact_phones = $request->contact_phone[$i];
                        if ($contact_service) {
                            $contact->contact_services = join(',', $contact_service[$i]);
                        } else {
                            $contact->contact_services = '';
                        }
                        $contact_service[$i] = is_array($contact_service[$i]) ? array_values(array_filter($contact_service[$i])) : [];
                        $contact->service()->sync($contact_service[$i]);

                        // this is contact phone section
                        $contact->contact_phones = '';
                        for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                            $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                            if ($phone_info) {
                                $contact->contact_phones = $contact->contact_phones . $phone_info->phone_recordid . ',';
                                $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                $phone_info->phone_type = $contact_phone_types[$i][$p];
                                $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
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
                                $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                $new_phone->save();
                                $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                array_push($contact_phone_recordid_list, $new_phone_recordid);
                            }
                        }
                        $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
                        $contact->phone()->sync($contact_phone_recordid_list);
                        $contact->save();
                    } else {
                        $updating_contact = Contact::where('contact_recordid', '=', $request->contact_recordid[$i])->first();
                        if ($updating_contact) {
                            $updating_contact->contact_name = $request->contact_name[$i];
                            $updating_contact->contact_title = $request->contact_title[$i];
                            $updating_contact->contact_email = $request->contact_email[$i];
                            $updating_contact->contact_department = $contact_department[$i];
                            $updating_contact->visibility = $contact_visibility[$i];
                            $updating_contact->contact_organizations = $id;
                            // $contact->contact_phones = $request->contact_phone[$i];
                            if ($contact_service) {
                                $updating_contact->contact_services = join(',', $contact_service[$i]);
                            } else {
                                $updating_contact->contact_services = '';
                            }
                            $contact_service[$i] = is_array($contact_service[$i]) ? array_values(array_filter($contact_service[$i])) : [];
                            $updating_contact->service()->sync($contact_service[$i]);
                            // this is contact phone section
                            $updating_contact->contact_phones = '';
                            if (isset($contact_phone_numbers[$i])) {
                                for ($p = 0; $p < count($contact_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $contact_phone_numbers[$i][$p])->first();
                                    // $contact_phones = explode(',',$updating_contact->contact_phones);
                                    if ($phone_info) {
                                        $updating_contact->contact_phones = $updating_contact->contact_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $contact_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $contact_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $contact_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
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
                                        $new_phone->phone_language = isset($contact_phone_languages[$i][$p]) && is_array($contact_phone_languages[$i][$p]) && count($contact_phone_languages[$i][$p]) > 0 ? implode(',', $contact_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $contact_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $contact->contact_phones = $contact->contact_phones . $new_phone_recordid . ',';
                                        array_push($contact_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            $contact_phone_recordid_list = is_array($contact_phone_recordid_list) ? array_values(array_filter($contact_phone_recordid_list)) : [];
                            $updating_contact->phone()->sync($contact_phone_recordid_list);
                            $updating_contact->save();
                        }
                    }
                }
            }
            // location section
            Location::where('location_organization', $id)->update(['location_organization' => '']);
            $organization->organization_locations = '';
            if ($request->location_name && $request->location_name[0] != null) {
                $location_alternate_name = $request->location_alternate_name && count($request->location_alternate_name) > 0 ? json_decode($request->location_alternate_name[0], true) : [];
                $location_transporation = $request->location_transporation && count($request->location_transporation) > 0 ? json_decode($request->location_transporation[0], true) : [];
                $location_service = $request->location_service && count($request->location_service) > 0 ? json_decode($request->location_service[0], true) : [];
                $location_schedules = $request->location_schedules && count($request->location_schedules) > 0 ? json_decode($request->location_schedules[0], true) : [];
                $location_description = $request->location_description && count($request->location_description) > 0 ? json_decode($request->location_description[0], true) : [];
                $location_details = $request->location_details && count($request->location_details) > 0 ? json_decode($request->location_details[0], true) : [];
                // accessibility
                $location_accessibility = $request->location_accessibility && count($request->location_accessibility) > 0 ? json_decode($request->location_accessibility[0], true) : [];
                $location_accessibility_details = $request->location_accessibility_details && count($request->location_accessibility_details) > 0 ? json_decode($request->location_accessibility_details[0], true) : [];

                $external_identifier = $request->external_identifier && count($request->external_identifier) > 0 ? json_decode($request->external_identifier[0]) : [];
                $external_identifier_type = $request->external_identifier_type && count($request->external_identifier_type) > 0 ? json_decode($request->external_identifier_type[0]) : [];
                $accessesibility_url = $request->accessesibility_url && count($request->accessesibility_url) > 0 ? json_decode($request->accessesibility_url[0]) : [];

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

                    // holiday section
                    $location_holiday_start_dates = $request->location_holiday_start_dates ? json_decode($request->location_holiday_start_dates, true) : [];
                    $location_holiday_end_dates = $request->location_holiday_end_dates ? json_decode($request->location_holiday_end_dates, true) : [];
                    $location_holiday_open_ats = $request->location_holiday_open_ats ? json_decode($request->location_holiday_open_ats, true) : [];
                    $location_holiday_close_ats = $request->location_holiday_close_ats ? json_decode($request->location_holiday_close_ats, true) : [];
                    $location_holiday_closeds = $request->location_holiday_closeds ? json_decode($request->location_holiday_closeds, true) : [];

                    if ($request->locationRadio[$i] == 'new_data') {
                        $location = new Location();
                        $location_recordid = Location::max('location_recordid') + 1;
                        $newLocationId = Location::max('location_recordid') + 1;
                        $location->location_recordid = $newLocationId;
                        $location->location_name = $request->location_name[$i];
                        $location->location_organization = $id;
                        $organization->organization_locations = $organization->organization_locations . ',' . $newLocationId;

                        $location->location_alternate_name = $location_alternate_name[$i] ?? null;
                        $location->location_transportation = $location_transporation[$i] ?? null;
                        $location->location_description = $location_description[$i] ?? null;
                        $location->location_details = $location_details[$i] ?? null;
                        $location->external_identifier = isset($external_identifier[$i]) ? $external_identifier[$i] : null;
                        $location->external_identifier_type = isset($external_identifier_type[$i]) ? $external_identifier_type[$i] : null;
                        $location->accessesibility_url = isset($accessesibility_url[$i]) ? $accessesibility_url[$i] : null;

                        // accessesibility
                        if (!empty($location_accessibility[$i]) && !empty($location_accessibility_details[$i])) {
                            // Accessibility::create([
                            //     'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                            //     'accessibility' => $location_accessibility[$i],
                            //     'accessibility_details' => $location_accessibility_details[$i],
                            //     'accessibility_location' => $newLocationId
                            // ]);
                            $location->accessibility_recordid = $location_accessibility[$i];
                            $location->accessibility_details = $location_accessibility_details[$i];
                        }
                        if (isset($location_regions[$i])) {
                            $location->regions()->sync($location_regions[$i]);
                        }

                        if ($location_service) {
                            $location->location_services = join(',', $location_service[$i]);
                        } else {
                            $location->location_services = '';
                        }
                        $location_service[$i] = is_array($location_service[$i]) ? array_values(array_filter($location_service[$i])) : [];
                        $location->services()->sync($location_service[$i]);

                        // if ($location_schedules[$i]) {
                        //     $location->location_schedule = join(',', $location_schedules[$i]);
                        // } else {
                        //     $location->location_schedule = '';
                        // }
                        // $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
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
                            $new_address = new Address;
                            $new_address_recordid = Address::max('address_recordid') + 1;
                            $new_address->address_recordid = $new_address_recordid;
                            $new_address->address_1 = $request->location_address[$i];
                            $new_address->address_city = $request->location_city[$i];
                            $new_address->address_state_province = $request->location_state[$i];
                            $new_address->address_postal_code = $request->location_zipcode[$i];
                            $new_address->save();
                            $location->location_address = $new_address_recordid;
                            // $location->location_address = $location->location_addresss . $new_address_recordid . ',';
                            array_push($location_address_recordid_list, $new_address_recordid);
                        }

                        // location phone
                        // this is contact phone section
                        if ($location_phone_numbers && count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {

                            for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                $phone_info = Phone::where('phone_number', '=', $request->location_phone[$i])->first();
                                if ($phone_info) {
                                    // $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                    $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                    $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                    $phone_info->phone_type = $location_phone_types[$i][$p];
                                    $phone_info->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
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
                                    $new_phone->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
                                    $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                    $new_phone->save();
                                    // $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                    array_push($location_phone_recordid_list, $new_phone_recordid);
                                }
                            }
                        }

                        // schedule section
                        $schedule_locations = $this->saveLocationSchedule($request, $i, $location_recordid);

                        if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                            Schedule::where('locations', $location_recordid)->where('schedule_holiday', '1')->delete();
                            for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {

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
                            }
                        }
                        $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                        $location->location_schedule = join(',', $schedule_locations);
                        $location->schedules()->sync($schedule_locations);
                        $location->phones()->sync($location_phone_recordid_list);

                        $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];
                        $location->address()->sync($location_address_recordid_list);
                        $location->save();
                    } else {
                        $location = location::where('location_recordid', $request->location_recordid[$i])->first();
                        if ($location) {
                            $location->location_name = $request->location_name[$i];
                            $location->location_organization = $id;
                            // location address
                            $organization->organization_locations = $organization->organization_locations . ',' . $request->location_recordid[$i];

                            $location->location_alternate_name = $location_alternate_name[$i];
                            $location->location_transportation = $location_transporation[$i];
                            $location->location_description = $location_description[$i];
                            $location->location_details = $location_details[$i];
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
                                $location->regions()->sync($location_regions[$i]);
                            }

                            if ($location_service) {
                                $location->location_services = join(',', $location_service[$i]);
                            } else {
                                $location->location_services = '';
                            }
                            $location_service[$i] = is_array($location_service[$i]) ? array_values(array_filter($location_service[$i])) : [];
                            $location->services()->sync($location_service[$i]);

                            // if ($location_schedules[$i]) {
                            //     $location->location_schedule = join(',', $location_schedules[$i]);
                            // } else {
                            //     $location->location_schedule = '';
                            // }
                            // $location_schedules[$i] = is_array($location_schedules[$i]) ? array_values(array_filter($location_schedules[$i])) : [];
                            // $location->schedules()->sync($location_schedules[$i]);

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

                            if (count($location_phone_numbers) > 0 && isset($location_phone_numbers[$i])) {
                                for ($p = 0; $p < count($location_phone_numbers[$i]); $p++) {
                                    $phone_info = Phone::where('phone_number', '=', $request->location_phone[$i])->first();
                                    if ($phone_info) {
                                        $location->location_phones = $location->location_phones . $phone_info->phone_recordid . ',';
                                        $phone_info->phone_number = $location_phone_numbers[$i][$p];
                                        $phone_info->phone_extension = $location_phone_extensions[$i][$p];
                                        $phone_info->phone_type = $location_phone_types[$i][$p];
                                        $phone_info->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
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
                                        $new_phone->phone_language = isset($location_phone_languages[$i][$p]) && is_array($location_phone_languages[$i][$p]) && count($location_phone_languages[$i][$p]) > 0 ? implode(',', $location_phone_languages[$i][$p]) : '';
                                        $new_phone->phone_description = $location_phone_descriptions[$i][$p];
                                        $new_phone->save();
                                        $location->location_phones = $location->location_phones . $new_phone_recordid . ',';
                                        array_push($location_phone_recordid_list, $new_phone_recordid);
                                    }
                                }
                            }
                            // schedule section
                            Schedule::where('locations', $location->location_recordid)->delete();

                            $schedule_locations = $this->saveLocationSchedule($request, $i, $location->location_recordid);

                            if ($location_holiday_start_dates && isset($location_holiday_start_dates[$i]) && $location_holiday_start_dates[$i] != null) {
                                Schedule::where('locations', $location->location_recordid)->where('schedule_holiday', '1')->delete();
                                if ($location_holiday_start_dates[$i] != null && !empty($location_holiday_start_dates[$i])) {
                                    for ($hs = 0; $hs < count($location_holiday_start_dates[$i]); $hs++) {
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
                                    }
                                }
                            }
                            $location->location_schedule = join(',', $schedule_locations);

                            $location_phone_recordid_list = is_array($location_phone_recordid_list) ? array_values(array_filter($location_phone_recordid_list)) : [];
                            $schedule_locations = is_array($schedule_locations) ? array_values(array_filter($schedule_locations)) : [];
                            $location_address_recordid_list = is_array($location_address_recordid_list) ? array_values(array_filter($location_address_recordid_list)) : [];

                            $location->schedules()->sync($schedule_locations);
                            $location->phones()->sync($location_phone_recordid_list);
                            $location->address()->sync($location_address_recordid_list);

                            $location_phone_recordid_list = array_unique($location_phone_recordid_list);
                            $location->location_phones = '';
                            $location->location_phones = count($location_phone_recordid_list) > 0 ? implode(',', $location_phone_recordid_list) : '';

                            $location->save();
                        }
                    }
                }
            }

            if ($request->removePhoneDataId) {
                $removePhoneDataId = explode(',', $request->removePhoneDataId);
                OrganizationPhone::whereIn('phone_recordid', $removePhoneDataId)->where('organization_recordid', $id)->delete();
            }
            if ($request->deletePhoneDataId) {
                $deletePhoneDataId = explode(',', $request->deletePhoneDataId);
                OrganizationPhone::whereIn('phone_recordid', $deletePhoneDataId)->where('organization_recordid', $id)->delete();
                Phone::whereIn('phone_recordid', $deletePhoneDataId)->delete();
            }

            // organozation interaction
            if ($request->organization_notes == '1') {
                $session = new SessionData;
                $new_recordid = SessionData::max('session_recordid') + 1;
                $session->session_recordid = $new_recordid;
                $user = Auth::user();
                $date_time = date("Y-m-d h:i:sa");
                $session->session_name = 'session' . $new_recordid;
                $session->session_organization = $id;
                $session->session_method = $request->interaction_method;
                $session->session_disposition = $request->interaction_disposition;
                $session->session_notes = $request->interaction_notes;
                $session->organization_services = $request->organization_services ? implode(',', $request->organization_services) : '';
                $session->organization_status = $request->organization_status;
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
                $interaction->organization_services = $request->organization_services ? implode(',', $request->organization_services) : '';
                $interaction->organization_status = $request->organization_status;
                $interaction->interaction_records_edited = $request->interaction_records_edited;
                $date_time = date("Y-m-d h:i:sa");
                $interaction->interaction_timestamp = $date_time;

                $interaction->save();

                $organizationStatus = OrganizationStatus::where('id', $request->organization_status)->first();
                if ($organizationStatus && (($request->reverify == '1' && $organizationStatus->status == 'Verified') || ($organizationStatus->status == 'Verified' && $organization->organization_status_x != $request->organization_status))) {
                    $organization->last_verified_at = Carbon::now();
                    $organization->last_verified_by = Auth::id();
                }
                $organization->organization_status_x = $request->organization_status;
            }


            $organization->latest_updated_date = Carbon::now();
            if ($organization->wasChanged()) {
                $organization->updated_at = date("Y-m-d H:i:s");
            }
            $organization->updated_by = Auth::id();
            $organization->save();
            Session::flash('message', 'Organization updated successfully!');
            Session::flash('status', 'success');
            return redirect('organizations/' . $id);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in update organization : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('organizations');
        }
    }

    public function add_comment(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        try {
            $organization = Organization::find($id);
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
            $comment->comments_organization = $id;
            $comment->comments_datetime = $date_time;
            $comment->save();

            $comment_list = Comment::where('comments_organization', '=', $id)->get();
            Session::flash('message', 'Comment added successfully!');
            Session::flash('status', 'success');
            return redirect('organizations/' . $id);
        } catch (\Throwable $th) {
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

    public function delete_organization(Request $request)
    {
        try {
            $organization_recordid = $request->input('organization_recordid');
            if ($request->detele_type == 'only_organization') {
                $organization = Organization::where('organization_recordid', '=', $organization_recordid)->first();
                if ($organization != null) {
                    $organization->delete();
                }
                Session::flash('message', 'Organization deleted successfully!');
                Session::flash('status', 'success');
                return redirect('organizations');
            } else {
                $organization = Organization::where('organization_recordid', '=', $organization_recordid)->first();

                if ($organization != null) {
                    $organization_contact_info_list = Contact::where('contact_organizations', '=', $organization_recordid)->delete();
                    $location_info_list = explode(',', $organization->organization_locations);
                    $organization_locations_list = Location::whereIn('location_recordid', $location_info_list)->delete();
                    $organization_services_recordid_list = explode(',', $organization->organization_services);
                    $organization_services = Service::whereIn('service_recordid', $organization_services_recordid_list)->delete();
                    $organization->delete();
                }
                Session::flash('message', 'The organization and its services, contacts & facilities have been successfully deleted.');
                Session::flash('status', 'success');
                return redirect('organizations');
            }
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function organization_history($organization_old, $request)
    {
        try {
            // if($request->)
        } catch (\Throwable $th) {
        }
    }

    public function createNewTag(Request $request, $id)
    {
        try {
            if (!$request->tag) {
                Session::flash('message', 'Organization tag can`t be blank!');
                Session::flash('status', 'error');
                return redirect()->back();
            }
            $organizationTag = OrganizationTag::create([
                'tag' => $request->tag,
                'created_by' => Auth::id()
            ]);
            $organization = Organization::whereId($id)->first();
            $orgTag = $organization->organization_tag != null ? explode(',', $organization->organization_tag) : [];
            $orgTag[] = $organizationTag->id;
            if (!empty($orgTag)) {
                $organization->organization_tag = implode(',', $orgTag);
                $organization->save();
            }
            Session::flash('message', 'Organization tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }

    public function createNewOrganizationTag(Request $request)
    {
        try {
            if (!$request->tag) {
                Session::flash('message', 'Organization tag can`t be blank!');
                Session::flash('status', 'error');
                return redirect()->back();
            }
            $organizationTag = OrganizationTag::create([
                'tag' => $request->tag,
                'created_by' => Auth::id()
            ]);
            $organization_recordid = $request->organization_recordid;
            $organization = Organization::where('organization_recordid', $organization_recordid)->first();
            $orgTag = $organization->organization_tag != null ? explode(',', $organization->organization_tag) : [];
            $orgTag[] = $organizationTag->id;
            if (!empty($orgTag)) {
                $organization->organization_tag = implode(',', $orgTag);
                $organization->save();
            }
            Session::flash('message', 'Organization tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }

    public function addOrganizationTag(Request $request)
    {
        try {
            $id = $request->id;
            $organization = Organization::whereId($id)->first();
            $organization->organization_tag = $request->val && is_array($request->val) ? implode(',', $request->val) : '';
            $organization->latest_updated_date = Carbon::now();
            $organization->updated_by = Auth::id();
            $organization->updated_at = Carbon::now();
            $organization->save();
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

    public function saveOrganizationBookmark(Request $request)
    {
        try {
            $id = $request->id;
            $organization = Organization::whereId($id)->first();
            $organization->bookmark = $request->value;
            $organization->latest_updated_date = Carbon::now();
            $organization->updated_by = Auth::id();
            $organization->updated_at = Carbon::now();
            $organization->save();
            // Session::flash('message', 'Organization Bookmarked successfully!');
            // Session::flash('status', 'success');
            return response()->json([
                'success' => true,
                'message' => 'Organization Bookmarked successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function manage_filters()
    {
        $filtersData = OrganizationTableFilter::where('user_id', Auth::id())->get();

        return view('backEnd.tables.tb_manage_org_filter', compact('filtersData'));
    }

    public function delete_manage_filters($id)
    {
        $filtersData = OrganizationTableFilter::where('id', $id)->delete();

        Session::flash('message', 'Filter Deleted successfully!');
        Session::flash('status', 'success');
        return redirect()->back();
    }

    public function saveOrganizationTags(Request $request)
    {
        try {
            $organization_recordid = $request->organization_recordid;
            $organization = Organization::where('organization_recordid', $organization_recordid)->first();
            $organization->organization_tag = $request->organization_tag && is_array($request->organization_tag) ? implode(',', $request->organization_tag) : '';
            $organization->save();
            Session::flash('message', 'Organization tag updated successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect()->back();
        }
    }

    public function saveOrganizationProgram($request, $organization)
    {
        $organizationPrograms = [];
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
                    $organizationPrograms[] = Program::max('program_recordid') + 1;
                } else {
                    $program = Program::where('program_recordid', $program_recordid[$i])->first();
                    $organizationPrograms[] = $program->program_recordid;
                }
                $program->name = $program_name[$i];
                $program->alternate_name = $program_alternate_name[$i];
                $program->description = $program_description[$i];
                // $program->program_service_relationship = $program_service_relationship[$i];
                $program->organizations = $organization->service_recordid;
                $recordids = [];
                $recordids[] = $organization->service_recordid;
                $program->save();
            }
        }
        $organization->program()->sync($organizationPrograms);
    }

    /**
     * @param $request
     * @param $new_recordid
     * @return array
     */
    public function saveOrganizationService($request, $new_recordid): array
    {
        $service_recordids = [];
        if ($request->service_name && $request->service_name[0] != null) {
            $service_alternate_name = $request->service_alternate_name && count($request->service_alternate_name) > 0 ? json_decode($request->service_alternate_name[0]) : [];
            $service_program = $request->service_program && count($request->service_program) > 0 ? json_decode($request->service_program[0]) : [];
            $service_status = $request->service_status && count($request->service_status) > 0 ? json_decode($request->service_status[0]) : [];
            $service_taxonomies = $request->service_taxonomies && count($request->service_taxonomies) > 0 ? json_decode($request->service_taxonomies[0]) : [];
            $service_application_process = $request->service_application_process && count($request->service_application_process) > 0 ? json_decode($request->service_application_process[0]) : [];
            $service_wait_time = $request->service_wait_time && count($request->service_wait_time) > 0 ? json_decode($request->service_wait_time[0]) : [];
            $service_fees = $request->service_fees && count($request->service_fees) > 0 ? json_decode($request->service_fees[0]) : [];
            $service_accreditations = $request->service_accreditations && count($request->service_accreditations) > 0 ? json_decode($request->service_accreditations[0]) : [];
            $service_licenses = $request->service_licenses && count($request->service_licenses) > 0 ? json_decode($request->service_licenses[0]) : [];
            $service_schedules = $request->service_schedules && count($request->service_schedules) > 0 ? json_decode($request->service_schedules[0]) : [];
            $service_details = $request->service_details && count($request->service_details) > 0 ? json_decode($request->service_details[0]) : [];
            $service_address = $request->service_address && count($request->service_address) > 0 ? json_decode($request->service_address[0]) : [];
            $service_metadata = $request->service_metadata && count($request->service_metadata) > 0 ? json_decode($request->service_metadata[0]) : [];
            $service_airs_taxonomy_x = $request->service_airs_taxonomy_x && count($request->service_airs_taxonomy_x) > 0 ? json_decode($request->service_airs_taxonomy_x[0]) : [];

            for ($i = 0; $i < count($request->service_name); $i++) {
                $service_phone_recordid_list = [];
                if ($request->serviceRadio[$i] == 'new_data') {
                    $service = new Service();
                    $service->service_recordid = Service::max('service_recordid') + 1;
                    $service->service_name = $request->service_name[$i];
                    $service->service_description = $request->service_description[$i];
                    $service->service_url = $request->service_url[$i];
                    $service->service_email = $request->service_email[$i];
                    $service->service_organization = $new_recordid;

                    $service->service_alternate_name = $service_alternate_name[$i];
                    $service->service_program = $service_program[$i];

                    if ($service_status[$i] == '1') {
                        $service->service_status = 'Verified';
                    } else {
                        $service->service_status = '';
                    }
                    $service->service_taxonomy = join(',', $service_taxonomies[$i]);
                    $service->service_application_process = $service_application_process[$i];
                    $service->service_wait_time = $service_wait_time[$i];
                    $service->service_fees = $service_fees[$i];
                    $service->service_accreditations = $service_accreditations[$i];
                    $service->service_licenses = $service_licenses[$i];
                    if ($service_schedules[$i]) {
                        $service->service_schedule = join(',', $service_schedules[$i]);
                    } else {
                        $service->service_schedule = '';
                    }
                    // $service->service_details = $service_details[$i];
                    if ($service_details[$i]) {
                        $service->service_details = join(',', $service_details[$i]);
                    } else {
                        $service->service_details = '';
                    }
                    // $service->service_address = $service_address[$i];
                    if ($service_address[$i]) {
                        $service->service_address = join(',', $service_address[$i]);
                    } else {
                        $service->service_address = '';
                    }
                    $service->service_metadata = $service_metadata[$i];
                    $service->service_airs_taxonomy_x = $service_airs_taxonomy_x[$i];
                    // $service->service_phones = $request->service_phone[$i];

                    $phone_info = Phone::where('phone_number', '=', $request->service_phone[$i])->first();
                    if ($phone_info) {
                        $service->service_phones = $service->service_phones . $phone_info->phone_recordid . ',';
                        $phone_info->phone_number = $request->service_phone[$i];
                        $phone_info->save();
                        array_push($service_phone_recordid_list, $phone_info->phone_recordid);
                    } else {
                        $new_phone = new Phone;
                        $new_phone_recordid = Phone::max('phone_recordid') + 1;
                        $new_phone->phone_recordid = $new_phone_recordid;
                        $new_phone->phone_number = $request->service_phone[$i];
                        $new_phone->save();
                        $service->service_phones = $service->service_phones . $new_phone_recordid . ',';
                        array_push($service_phone_recordid_list, $new_phone_recordid);
                    }
                    array_push($service_recordids, Service::max('service_recordid') + 1);

                    $service_address[$i] = is_array($service_address[$i]) ? array_values(array_filter($service_address[$i])) : [];
                    $service_details[$i] = is_array($service_details[$i]) ? array_values(array_filter($service_details[$i])) : [];
                    $service_schedules[$i] = is_array($service_schedules[$i]) ? array_values(array_filter($service_schedules[$i])) : [];
                    $service_taxonomies[$i] = is_array($service_taxonomies[$i]) ? array_values(array_filter($service_taxonomies[$i])) : [];
                    $service_phone_recordid_list = is_array($service_phone_recordid_list) ? array_values(array_filter($service_phone_recordid_list)) : [];

                    $service->address()->sync($service_address[$i]);
                    $service->details()->sync($service_details[$i]);
                    $service->schedules()->sync($service_schedules[$i]);
                    $service->taxonomy()->sync($service_taxonomies[$i]);
                    $service->phone()->sync($service_phone_recordid_list);
                    $service->save();
                } else {
                    $updating_service = Service::where('service_recordid', '=', $request->service_recordid[$i])->first();
                    if ($updating_service) {
                        $updating_service->service_name = $request->service_name[$i];
                        $updating_service->service_description = $request->service_description[$i];
                        $updating_service->service_url = $request->service_url[$i];
                        $updating_service->service_email = $request->service_email[$i];
                        $updating_service->service_organization = $new_recordid;
                        // $service->service_phones = $request->service_phone[$i];

                        $updating_service->service_alternate_name = $service_alternate_name[$i];
                        $updating_service->service_program = $service_program[$i];

                        if ($service_status[$i] == '1') {
                            $updating_service->service_status = 'Verified';
                        } else {
                            $updating_service->service_status = '';
                        }
                        $updating_service->service_taxonomy = join(',', $service_taxonomies[$i]);
                        $updating_service->service_application_process = $service_application_process[$i];
                        $updating_service->service_wait_time = $service_wait_time[$i];
                        $updating_service->service_fees = $service_fees[$i];
                        $updating_service->service_accreditations = $service_accreditations[$i];
                        $updating_service->service_licenses = $service_licenses[$i];
                        if ($service_schedules[$i]) {
                            $updating_service->service_schedule = join(',', $service_schedules[$i]);
                        } else {
                            $updating_service->service_schedule = '';
                        }
                        // $updating_service->service_details = $service_details[$i];
                        if ($service_details[$i]) {
                            $updating_service->service_details = join(',', $service_details[$i]);
                        } else {
                            $updating_service->service_details = '';
                        }
                        // $updating_service->service_address = $service_address[$i];
                        if ($service_address[$i]) {
                            $updating_service->service_address = join(',', $service_address[$i]);
                        } else {
                            $updating_service->service_address = '';
                        }
                        $updating_service->service_metadata = $service_metadata[$i];
                        $updating_service->service_airs_taxonomy_x = $service_airs_taxonomy_x[$i];
                        // $updating_service->service_phones = $request->service_phone[$i];

                        $phone_info = Phone::where('phone_number', '=', $request->service_phone[$i])->first();
                        if ($phone_info) {
                            $updating_service->service_phones = $updating_service->service_phones . $phone_info->phone_recordid . ',';
                            $phone_info->phone_number = $request->service_phone[$i];
                            $phone_info->save();
                            array_push($service_phone_recordid_list, $phone_info->phone_recordid);
                        } else {
                            $new_phone = new Phone;
                            $new_phone_recordid = Phone::max('phone_recordid') + 1;
                            $new_phone->phone_recordid = $new_phone_recordid;
                            $new_phone->phone_number = $request->service_phone[$i];
                            $new_phone->save();
                            $updating_service->service_phones = $updating_service->service_phones . $new_phone_recordid . ',';
                            array_push($service_phone_recordid_list, $new_phone_recordid);
                        }
                        $service_recordids[] = Service::max('service_recordid') + 1;

                        $service_address[$i] = is_array($service_address[$i]) ? array_values(array_filter($service_address[$i])) : [];
                        $service_details[$i] = is_array($service_details[$i]) ? array_values(array_filter($service_details[$i])) : [];
                        $service_schedules[$i] = is_array($service_schedules[$i]) ? array_values(array_filter($service_schedules[$i])) : [];
                        $service_taxonomies[$i] = is_array($service_taxonomies[$i]) ? array_values(array_filter($service_taxonomies[$i])) : [];
                        $service_phone_recordid_list = is_array($service_phone_recordid_list) ? array_values(array_filter($service_phone_recordid_list)) : [];

                        $updating_service->address()->sync($service_address[$i]);
                        $updating_service->details()->sync($service_details[$i]);
                        $updating_service->schedules()->sync($service_schedules[$i]);
                        $updating_service->taxonomy()->sync($service_taxonomies[$i]);
                        $updating_service->phone()->sync($service_phone_recordid_list);
                        $updating_service->save();
                        $service_recordids[] = $updating_service->service_recordid;
                    }
                }
            }
        }
        return $service_recordids;
    }

    /**
     * @param $request
     * @param $i
     * @param $location_recordid
     * @return array
     */
    public function saveLocationSchedule($request, $i, $location_recordid): array
    {
        // location schedule section
        $schedule_locations = [];
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

        // if ($opens_location_monday_datas && isset($opens_location_monday_datas[$i]) && $opens_location_monday_datas[$i] != null) {
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        for ($s = 0; $s < 7; $s++) {
            // dd($request);
            if ((isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) && isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i])) || ((isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)))) {
                if ((isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) && isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i])) && ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] && ${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) {
                    $schedules = Schedule::where('locations', $location_recordid)->where('opens', ${'opens_location_' . $weekdays[$s] . '_datas'}[$i])->where('closes', ${'closes_location_' . $weekdays[$s] . '_datas'}[$i])->first();
                } else {
                    $schedules = Schedule::where('locations', $location_recordid)->whereNotNull('schedule_closed')->first();
                }
                if ($schedules) {
                    $schedules->locations = $location_recordid;
                    $schedules->weekday = $schedules->weekday ? (str_contains($schedules->weekday, $weekdays[$s]) ? $schedules->weekday : ($schedules->weekday . ',' . $weekdays[$s])) : $weekdays[$s];
                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) && ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : $schedules->opens;
                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) && ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : $schedules->closes;
                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {

                        $schedules->schedule_closed = $schedules->schedule_closed ? (str_contains($schedules->schedule_closed, ($s + 1)) ? $schedules->schedule_closed : $schedules->schedule_closed . ',' . ($s + 1)) : ($s + 1);
                    } else {
                        if (str_contains($schedules->schedule_closed, ($s + 1))) {
                            $schedule_closed = explode(',', $schedules->schedule_closed);
                            if (is_array($schedule_closed) && in_array(($s + 1), $schedule_closed)) {
                                array_splice($schedule_closed, array_search(($s + 1), $schedule_closed), 1);
                                $schedules->schedule_closed = implode(',', $schedule_closed);
                            }
                        }
                    }
                    $schedules->save();
                    $schedule_locations[] = $schedules->schedule_recordid;
                } else {
                    $schedule_recordid = Schedule::max('schedule_recordid') + 1;
                    $schedules = new Schedule();
                    $schedules->schedule_recordid = $schedule_recordid;
                    $schedules->locations = $location_recordid;
                    $schedules->weekday = $weekdays[$s];
                    $schedules->opens = isset(${'opens_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'opens_location_' . $weekdays[$s] . '_datas'}[$i] : null;
                    $schedules->closes = isset(${'closes_location_' . $weekdays[$s] . '_datas'}[$i]) ? ${'closes_location_' . $weekdays[$s] . '_datas'}[$i] : null;
                    if (isset(${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i]) && ${'schedule_closed_' . $weekdays[$s] . '_datas'}[$i] == ($s + 1)) {
                        $schedules->schedule_closed = $s + 1;
                    }
                    $schedules->save();
                    $schedule_locations[] = $schedule_recordid;
                }
            }
        }
        // }
        return $schedule_locations;
    }
}
