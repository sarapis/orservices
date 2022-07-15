<?php

namespace App\Http\Controllers\frontEnd;

use App\Exports\LocationExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Functions\Airtable;
use App\Imports\LocationImport;
use App\Model\Location;
use App\Model\Service;
use App\Model\Schedule;
use App\Model\Phone;
use App\Model\Address;
use App\Model\Organization;
use App\Model\Detail;
use App\Model\Comment;
use App\Model\Airtablekeyinfo;
use App\Model\LocationAddress;
use App\Model\LocationPhone;
use App\Model\LocationSchedule;
use App\Model\Accessibility;
use App\Model\Airtables;
use App\Model\Airtable_v2;
use App\Model\City;
use App\Model\Code;
use App\Model\Contact;
use App\Model\Map;
use App\Model\CSV_Source;
use App\Model\DetailType;
use App\Model\Helptext;
use App\Model\Language;
use App\Model\Layout;
use App\Model\PhoneType;
use App\Model\Region;
use App\Model\Source_data;
use App\Model\State;
use Spatie\Geocoder\Geocoder;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Yajra\DataTables\Facades\DataTables;
use OwenIt\Auditing\Models\Audit;

// use DataTables;
class LocationController extends Controller
{
    public function __construct(CommonController $commonController)
    {
        $this->commonController = $commonController;
    }

    public function airtable($api_key, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info) {
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Location::truncate();
        LocationAddress::truncate();
        LocationPhone::truncate();
        LocationSchedule::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent('locations');

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode($response, TRUE);

            foreach ($airtable_response['records'] as $record) {

                $location = new Location();
                $strtointclass = new Stringtoint();
                $location->location_recordid = $strtointclass->string_to_int($record['id']);
                $location->location_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                $location->location_organization = isset($record['fields']['organization']) ? implode(",", $record['fields']['organization']) : null;

                $location->location_organization = $strtointclass->string_to_int($location->location_organization);

                $location->location_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                $location->location_transportation = isset($record['fields']['transportation']) ? $record['fields']['transportation'] : null;
                $location->location_latitude = isset($record['fields']['latitude']) ? $record['fields']['latitude'] : null;
                $location->location_longitude = isset($record['fields']['longitude']) ? $record['fields']['longitude'] : null;
                $location->location_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                $location->location_services = isset($record['fields']['services']) ? implode(",", $record['fields']['services']) : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $locationservice = $strtointclass->string_to_int($value);

                        if ($i != 0)
                            $location->location_services = $location->location_services . ',' . $locationservice;
                        else
                            $location->location_services = $locationservice;
                        $i++;
                    }
                }

                if (isset($record['fields']['phones'])) {
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {

                        $location_phone = new Locationphone();
                        $location_phone->location_recordid = $location->location_recordid;
                        $location_phone->phone_recordid = $strtointclass->string_to_int($value);
                        $location_phone->save();
                        if ($i != 0)
                            $location->location_phones = $location->location_phones . ',' . $location_phone->phone_recordid;
                        else
                            $location->location_phones = $location_phone->phone_recordid;
                        $i++;
                    }
                }

                $location->location_details = isset($record['fields']['details']) ? implode(",", $record['fields']['details']) : null;


                if (isset($record['fields']['schedule'])) {
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {
                        $locationschedule = new Locationschedule();
                        $locationschedule->location_recordid = $location->location_recordid;
                        $locationschedule->schedule_recordid = $strtointclass->string_to_int($value);
                        $locationschedule->save();
                        if ($i != 0)
                            $location->location_schedule = $location->location_schedule . ',' . $locationschedule->schedule_recordid;
                        else
                            $location->location_schedule = $locationschedule->schedule_recordid;
                        $i++;
                    }
                }

                $location->location_address = isset($record['fields']['address']) ? implode(",", $record['fields']['address']) : null;

                if (isset($record['fields']['address'])) {
                    $i = 0;
                    foreach ($record['fields']['address']  as  $value) {
                        $locationaddress = new Locationaddress();
                        $locationaddress->location_recordid = $location->location_recordid;
                        $locationaddress->address_recordid = $strtointclass->string_to_int($value);
                        $locationaddress->save();
                        if ($i != 0)
                            $location->location_address = $location->location_address . ',' . $locationaddress->address_recordid;
                        else
                            $location->location_address = $locationaddress->address_recordid;
                        $i++;
                    }
                }

                $location->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Locations')->first();
        $airtable->records = Location::count();
        $airtable->syncdate = $date;
        $airtable->save();
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

            // Location::truncate();
            // LocationAddress::truncate();
            // LocationPhone::truncate();
            // LocationSchedule::truncate();

            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'api_key'   => $api_key,
                'base'      => $base_url,
            ));

            $request = $airtable->getContent('locations');

            do {


                $response = $request->getResponse();

                $airtable_response = json_decode($response, TRUE);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_location = Location::where('location_recordid', $recordId)->where('location_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_location == null) {
                        $location = new Location();
                        $strtointclass = new Stringtoint();
                        $location->location_recordid = $strtointclass->string_to_int($record['id']);
                        $location->location_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                        // $location->location_organization = isset($record['fields']['organizations']) ? implode(",", $record['fields']['organizations']) : null;
                        if (isset($record['fields']['organizations'])) {
                            foreach ($record['fields']['organizations'] as $key => $value) {
                                $location->location_organization = $strtointclass->string_to_int($value);
                            }
                        }
                        // $location->location_organization = $strtointclass->string_to_int($location->location_organization);

                        $location->location_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                        $location->location_transportation = isset($record['fields']['transportation']) ? $record['fields']['transportation'] : null;
                        $location->location_latitude = isset($record['fields']['latitude']) ? $record['fields']['latitude'] : null;
                        $location->location_longitude = isset($record['fields']['longitude']) ? $record['fields']['longitude'] : null;
                        $location->location_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                        $location->location_services = isset($record['fields']['services']) ? implode(",", $record['fields']['services']) : null;

                        if (isset($record['fields']['services'])) {
                            $i = 0;
                            foreach ($record['fields']['services']  as  $value) {

                                $locationservice = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $location->location_services = $location->location_services . ',' . $locationservice;
                                else
                                    $location->location_services = $locationservice;
                                $i++;
                            }
                        }

                        if (isset($record['fields']['phones'])) {
                            $i = 0;
                            foreach ($record['fields']['phones']  as  $value) {

                                $location_phone = new Locationphone();
                                $location_phone->location_recordid = $location->location_recordid;
                                $location_phone->phone_recordid = $strtointclass->string_to_int($value);
                                $location_phone->save();
                                if ($i != 0)
                                    $location->location_phones = $location->location_phones . ',' . $location_phone->phone_recordid;
                                else
                                    $location->location_phones = $location_phone->phone_recordid;
                                $i++;
                            }
                        }

                        // $location->location_details = isset($record['fields']['x-details']) ? implode(",", $record['fields']['x-details']) : null;

                        if (isset($record['fields']['x-details'])) {
                            $i = 0;
                            foreach ($record['fields']['x-details']  as  $value) {

                                $locationDetail = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $location->location_details = $location->location_details . ',' . $locationDetail;
                                else
                                    $location->location_details = $locationDetail;
                                $i++;
                            }
                        }


                        if (isset($record['fields']['schedule'])) {
                            $i = 0;
                            foreach ($record['fields']['schedule']  as  $value) {
                                $locationschedule = new Locationschedule();
                                $locationschedule->location_recordid = $location->location_recordid;
                                $locationschedule->schedule_recordid = $strtointclass->string_to_int($value);
                                $locationschedule->save();
                                if ($i != 0)
                                    $location->location_schedule = $location->location_schedule . ',' . $locationschedule->schedule_recordid;
                                else
                                    $location->location_schedule = $locationschedule->schedule_recordid;
                                $i++;
                            }
                        }

                        $location->location_address = isset($record['fields']['address']) ? implode(",", $record['fields']['address']) : null;

                        if (isset($record['fields']['address'])) {
                            $i = 0;
                            foreach ($record['fields']['address']  as  $value) {
                                $locationaddress = new Locationaddress();
                                $locationaddress->location_recordid = $location->location_recordid;
                                $locationaddress->address_recordid = $strtointclass->string_to_int($value);
                                $locationaddress->save();
                                if ($i != 0)
                                    $location->location_address = $location->location_address . ',' . $locationaddress->address_recordid;
                                else
                                    $location->location_address = $locationaddress->address_recordid;
                                $i++;
                            }
                        }

                        $location->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Locations')->first();
            $airtable->records = Location::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in location: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function csv(Request $request)
    {
        try {
            Location::truncate();

            Excel::import(new LocationImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Locations')->first();
            $csv_source->records = Location::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            $response = array(
                'status' => 'success',
                'result' => 'Location imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            Log::error('Error in location csv : ' . $th);
        }
    }

    public function csv_accessibility(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename != 'accessibility_for_disabilities.csv') {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        if ($csv_header_fields[0] != 'id' || $csv_header_fields[1] != 'location_id' || $csv_header_fields[2] != 'accessibility' || $csv_header_fields[3] != 'details') {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Accessibility::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $accessibility = new Accessibility();

            $accessibility->accessibility_recordid = $row[$csv_header_fields[0]] != 'NULL' ? $row[$csv_header_fields[0]] : null;
            $accessibility->location_recordid = $row[$csv_header_fields[1]] != 'NULL' ? $row[$csv_header_fields[1]] : null;
            $accessibility->accessibility = $row[$csv_header_fields[2]] != 'NULL' ? $row[$csv_header_fields[2]] : null;;
            $accessibility->details = $row[$csv_header_fields[3]] != 'NULL' ? $row[$csv_header_fields[3]] : null;;
            $accessibility->save();
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
        $csv_source->records = Accessibility::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index(Request $request)
    {
        try {
            $facilities = Location::whereNotNull('location_name');
            $map = Map::find(1);

            if (!$request->ajax()) {
                return view('frontEnd.locations.index', compact('map', 'facilities'));
            }

            return DataTables::of($facilities->orderBy('id', 'desc'))
                // ->addColumn('action', function ($row) {
                //     $link = '<a class=" open-td" href="/facilities/' . $row->location_recordid . '" style="color: #007bff;"><i class="fa fa-fw fa-eye"></a>';
                //     return $link;
                // })
                ->editColumn('location_name', function ($row) {
                    $link = '<a class=" open-td" href="/facilities/' . $row->location_recordid . '" style="color: #007bff;">' . $row->location_name . '</a>';
                    return $link;
                })
                ->addColumn('location_contact', function ($row) {
                    $link = '';
                    if ($row->organization && $row->organization->contact) {
                        foreach ($row->organization->contact as $key => $value) {
                            $link .= '<a class=" open-td" href="/contacts/' . $value->contact_recordid . '" style="color: #007bff;">' . $value->contact_name . '</a>';
                        }
                    }
                    return $link;
                })
                ->addColumn('location_service', function ($row) {
                    $link = '';
                    if ($row->services) {
                        foreach ($row->services as $key => $value) {
                            $link .= '<a class=" open-td" href="/services/' . $value->service_recordid . '" style="color: #007bff;">' . $value->service_name . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('location_organization', function ($row) {
                    $link = '';
                    if ($row->organization) {
                        $link = '<a class=" open-td" href="/organizations/' . $row->organization->organization_recordid . '" style="color: #007bff;">' . $row->organization->organization_name . '</a>';
                    }
                    return $link;
                })
                ->editColumn('location_address', function ($row) {

                    return isset($row->address[0]) ? $row->address[0]['address_1'] : '';
                })
                ->addColumn('location_city', function ($row) {

                    return isset($row->address[0]) ? $row->address[0]['address_city'] : '';
                })
                ->addColumn('location_state', function ($row) {

                    return isset($row->address[0]) ? $row->address[0]['address_state_province'] : '';
                })
                ->rawColumns(['location_name', 'location_contact', 'location_service', 'location_organization'])
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in Location index : ' . $th);
        }
    }
    public function tb_location()
    {
        try {
            $locations = Location::paginate(20);
            $map = Map::find(1);
            return view('backEnd.tables.tb_location', compact('locations', 'map'));
        } catch (\Throwable $th) {
            Log::error('Error in tb_location : ' . $th);
        }
    }

    public function get_all_facilities(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $search_term = $request->search_term;


        $facilities = Location::orderBy('location_recordid', 'DESC');

        if ($search_term) {
            $facilities = $facilities
                ->where('location_name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('location_description', 'LIKE', '%' . $search_term . '%')
                ->whereHas('organization', function (Builder $query) use ($search_term) {
                    $query->where('organization_name', 'LIKE', '%' . $search_term . '%');
                });
        }

        $filtered_count = $facilities->count();

        $facilities = $facilities->offset($start)->limit($length)->get();
        $total_count = Location::count();
        $result = [];
        $facility_info = [];
        foreach ($facilities as $facility) {

            $facility_info[0] = '';
            $facility_info[1] = $facility->location_recordid;
            $facility_info[2] = Str::limit($facility->location_name, 30, '...');
            if ($facility->organization) {
                $facility_info[3] = Str::limit($facility->organization['organization_name'], 25, '...');
            } else {
                $facility_info[3] = '';
            }


            $facility_full_address_info = '';
            if (isset($facility->address[0])) {
                $facility_full_address_info = $facility_full_address_info . $facility->address[0]['address_1'];
                if ($facility->address[0]['address_city']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_city'];
                }
                if ($facility->address[0]['address_state']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_state'];
                }
                if ($facility->address[0]['address_zip_code']) {
                    $facility_full_address_info = $facility_full_address_info . ', ' . $facility->address[0]['address_zip_code'];
                }
            }

            $facility_info[4] = $facility_full_address_info;
            $facility_info[5] = Str::limit($facility->location_description, 15, '...');

            array_push($result, $facility_info);
        }
        return response()->json(array('data' => $result, 'recordsTotal' => $total_count, 'recordsFiltered' => $filtered_count));
    }


    public function facility($id)
    {
        $facility = Location::where('location_recordid', '=', $id)->first();
        $locations = Location::with('services', 'address', 'phones')->where('location_recordid', '=', $id)->get();
        $map = Map::find(1);

        $facility_organization_recordid_list = explode(',', $facility->location_organization);
        $facility_organizations = Organization::whereIn('organization_recordid', $facility_organization_recordid_list)->orderBy('organization_name')->paginate(10);

        $comment_list = Comment::where('comments_location', '=', $id)->get();

        $existing_tag_element_list = Location::whereNotNull('location_tag')->get()->pluck('location_tag');
        $existing_tags = [];
        foreach ($existing_tag_element_list as $key => $existing_tag_element) {
            $existing_tag_list = explode(", ", $existing_tag_element);
            foreach ($existing_tag_list as $key => $existing_tag) {
                if (!in_array($existing_tag, $existing_tags, true)) {
                    array_push($existing_tags, $existing_tag);
                }
            }
        }

        return view('frontEnd.location', compact('facility', 'map', 'locations', 'facility_organizations', 'comment_list', 'existing_tags'));
    }


    public function location_tag(Request $request, $id)
    {
        try {
            $facility = Location::where('location_recordid', $id)->first();
            $facility->location_tag = $request->tokenfield;
            $facility->save();
            Session::flash('message', 'Tag added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function location_comment(Request $request, $id)
    {

        try {
            $facility = Location::find($id);
            $comment_content = $request->reply_content;
            $user = Auth::user();
            $date_time = date("Y-m-d h:i:sa");
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
            $comment->comments_location = $id;
            $comment->comments_datetime = $date_time;
            $comment->save();

            $comment_list = Comment::where('comments_location', '=', $id)->get();

            Session::flash('message', 'Comment added successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }

    public function create_in_organization($id)
    {
        $map = Map::find(1);
        $organization = Organization::where('organization_recordid', '=', $id)->select('organization_recordid', 'organization_name')->first();
        $service_info_list = $organization->services()->select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        // $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();
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

        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');

        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);
        $regions = Region::pluck('region', 'id');
        return view('frontEnd.locations.facility-create-in-organization', compact('map', 'organization', 'service_info_list', 'schedule_info_list', 'address_info_list', 'detail_info_list', 'address_states_list', 'address_city_list', 'phone_languages', 'phone_type', 'detail_types', 'all_phones', 'phone_language_data', 'regions'));
    }

    public function create()
    {
        $map = Map::find(1);
        if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
            $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
            $organization_name_list = Organization::orderBy('organization_name')->whereIn('organization_recordid', $organization_recordid)->pluck('organization_name', 'organization_recordid');
        } else {
            $organization_name_list = Organization::orderBy('organization_name')->pluck('organization_name', 'organization_recordid');
        }
        // $organization_name_list = [];
        // foreach ($organization_names as $key => $value) {
        //     $org_names = explode(", ", trim($value->organization_name));
        //     $organization_name_list = array_merge($organization_name_list, $org_names);
        // }
        // $organization_name_list = array_unique($organization_name_list);

        $service_info_list = Service::orderBy('service_name')->pluck('service_name', 'service_recordid');
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();
        $address_info_list = Address::select('address_recordid', 'address_1', 'address_city', 'address_state_province', 'address_postal_code')->orderBy('address_1')->distinct()->get();
        $detail_info_list = Detail::select('detail_recordid', 'detail_value')->orderBy('detail_value')->distinct()->get();

        // $address_city_list = Address::pluck('address_city', 'address_city')->unique();
        // $address_states_list = Address::pluck('address_state_province', 'address_state_province')->unique();
        $address_city_list = City::orderBy('city')->pluck('city', 'city');
        $address_states_list = State::orderBy('state')->pluck('state', 'state');

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
        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');

        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);

        $regions = Region::pluck('region', 'id');

        return view('frontEnd.locations.create', compact('map', 'organization_name_list', 'service_info_list', 'schedule_info_list', 'address_info_list', 'detail_info_list', 'address_states_list', 'address_city_list', 'phone_languages', 'phone_type', 'detail_types', 'all_phones', 'phone_language_data', 'regions'));
    }
    public function add_new_facility_in_organization(Request $request)
    {
        try {
            $facility = new Location;

            $facility_recordids = Location::select("location_recordid")->distinct()->get();
            $facility_recordid_list = array();
            foreach ($facility_recordids as $key => $value) {
                $facility_recordid = $value->location_recordid;
                array_push($facility_recordid_list, $facility_recordid);
            }
            $facility_recordid_list = array_unique($facility_recordid_list);

            $new_recordid = Location::max('location_recordid') + 1;
            if (in_array($new_recordid, $facility_recordid_list)) {
                $new_recordid = Contact::max('location_recordid') + 1;
            }
            $facility->location_recordid = $new_recordid;

            $facility->location_name = $request->location_name;
            $facility->location_alternate_name = $request->location_alternate_name;
            $facility->location_transportation = $request->location_transportation;
            $facility->location_description = $request->location_description;

            // accessesibility
            if ($request->accessibility) {
                Accessibility::create([
                    'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                    'accessibility' => $request->accessibility,
                    'accessibility_details' => $request->accessibility_details,
                    'accessibility_location' => $new_recordid
                ]);
            }
            if ($request->regions) {
                $facility->regions()->sync($request->regions);
            }


            // $facility->location_details = $request->location_details;

            // $organization_name = $request->facility_organization;
            // $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
            // $facility_organization_id = $facility_organization["organization_recordid"];
            $facility->location_organization = $request->facility_organization;

            if ($request->facility_service) {
                $facility->location_services = join(',', $request->facility_service);
            } else {
                $facility->location_services = '';
            }
            $facility->services()->sync($request->facility_service);

            if ($request->facility_schedules) {
                $facility->location_schedule = join(',', $request->facility_schedules);
            } else {
                $facility->location_schedule = '';
            }
            $facility->schedules()->sync($request->facility_schedules);

            // detail section

            if ($request->detail_type) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                $detail_ids = [];
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail_locations = $detail->detail_locations ? explode(',', $detail->detail_locations) : [];
                            if (!empty($detail_locations)) {
                                $detail_locations = array_filter($detail_locations);
                            }
                            if (!in_array($new_recordid, $detail_locations)) {
                                array_push($detail_locations, $new_recordid);
                            }
                            $detail->detail_locations = join(',', $detail_locations);
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_update = Detail::where('detail_recordid', $detail_term[$key])->first();
                            if ($detail_update) {
                                $detail_locations = $detail_update->detail_locations ? explode(',', $detail_update->detail_locations) : [];
                                if (!empty($detail_locations)) {
                                    $detail_locations = array_filter($detail_locations);
                                }
                                if (!in_array($new_recordid, $detail_locations)) {
                                    array_push($detail_locations, $new_recordid);
                                }
                                $detail_update->detail_locations = join(',', $detail_locations);
                                $detail_update->save();
                            }
                            $detail_ids[] = $detail_term[$key];
                        }
                    }
                }
            }
            $facility->location_details = join(',', $detail_ids);

            // if ($request->facility_address) {
            //     $facility->location_address = join(',', $request->facility_address);
            // } else {
            //     $facility->location_address = '';
            // }
            // $facility->address()->sync($request->facility_address);

            $facility_address_city = $request->facility_address_city;
            $facility_street_address = $request->facility_street_address;
            $facility_address_state = '';
            if ($request->facility_address_state) {
                $facility_address_state = $request->facility_address_state;
            }
            $facility_address_zip_code = $request->facility_zip_code;
            $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

            $address = Address::where('address_1', '=', $facility_street_address)->first();
            $address_recordid_list = [];
            $address_id = '';
            if ($address) {
                $address_id = $address["address_recordid"];
                $facility->location_address = $address_id;
            } else {
                $address = new Address;
                $new_recordid = Address::max('address_recordid') + 1;
                $address->address_recordid = $new_recordid;
                $address->address_1 = $facility_street_address;
                $address->address_city = $facility_address_city;
                $address->address_state_province = $facility_address_state;
                $address->address_postal_code = $facility_address_zip_code;
                $facility->location_address = $new_recordid;
                $address_id = $new_recordid;
                $address->save();
            }
            array_push($address_recordid_list, $address_id);
            $facility->address()->sync($address_recordid_list);

            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($facility->location_address) {
                $address_recordid = $facility->location_address;
                $address_info = Address::where('address_recordid', '=', $address_recordid)->select('address_1', 'address_city', 'address_state_province', 'address_postal_code')->first();
                $full_address_info = $address_info->address_1 . ', ' . $address_info->address_city . ', ' . $address_info->address_state_province . ', ' . $address_info->address_postal_code;

                $response = $geocoder->getCoordinatesForAddress($full_address_info);

                if ($response['lat']) {
                    $latitude = $response['lat'];
                    $longitude = $response['lng'];
                    $facility->location_latitude = $latitude;
                    $facility->location_longitude = $longitude;
                } else {
                    Session::flash('message', 'Address info is not valid. We can not get longitude and latitude for this address');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
            }

            // $facility->location_phones = '';
            // $phone_recordid_list = [];
            // if ($request->facility_phones) {
            //     $facility_phone_number_list = $request->facility_phones;
            //     foreach ($facility_phone_number_list as $key => $facility_phone_number) {
            //         $phone_info = Phone::where('phone_number', '=', $facility_phone_number)->select('phone_recordid')->first();
            //         if ($phone_info) {
            //             $facility->location_phones = $facility->location_phones . $phone_info->phone_recordid . ',';
            //             array_push($phone_recordid_list, $phone_info->phone_recordid);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $facility_phone_number;
            //             $new_phone->save();
            //             $facility->location_phones = $facility->location_phones . $new_phone_recordid . ',';
            //             array_push($phone_recordid_list, $new_phone_recordid);
            //         }
            //     }
            // }
            // $facility->phones()->sync($phone_recordid_list);

            $facility->location_phones = '';
            $phone_recordid_list = [];
            if ($request->facility_phones) {
                $facility_phone_number_list = $request->facility_phones;
                $facility_phone_extension_list = $request->phone_extension;
                $facility_phone_type_list =  $request->phone_type;
                $facility_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $facility_phone_description_list = $request->phone_description;
                $facility_main_priority_list = $request->main_priority;
                // foreach ($facility_phone_number_list as $key => $facility_phone_number) {

                //     if ($phone_info) {
                //         $facility->facility_phones = $facility->facility_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($facility_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $facility_phone_number_list[$i])->first();
                    $existing_location_phones = $facility->location_phones ? explode(',', $facility->location_phones) : [];

                    if ($phone_info) {
                        $existing_location_phones[] = $phone_info->phone_recordid;
                        array_unique($existing_location_phones);
                        $facility->location_phones = count($existing_location_phones) > 0 ? implode(',', $existing_location_phones) : '';
                        $phone_info->phone_number = $facility_phone_number_list[$i];
                        $phone_info->phone_extension = $facility_phone_extension_list[$i];
                        $phone_info->phone_type = $facility_phone_type_list[$i];
                        $phone_info->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
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
                        $new_phone->phone_number = $facility_phone_number_list[$i];
                        $new_phone->phone_extension = $facility_phone_extension_list[$i];
                        $new_phone->phone_type = $facility_phone_type_list[$i];
                        $new_phone->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        $existing_location_phones[] = $new_phone_recordid;
                        array_unique($existing_location_phones);
                        $facility->location_phones = count($existing_location_phones) > 0 ? implode(',', $existing_location_phones) : '';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }

            $facility->phones()->sync($phone_recordid_list);

            $facility->save();
            Session::flash('message', 'Facility added successfully!');
            Session::flash('status', 'success');
            return redirect('organizations/' . $request->facility_organization);
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('organizations');
        }
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
            'location_name' => 'required',
            'location_organization' => 'required',
        ]);
        try {
            $facility = new Location;
            $facility_recordids = Location::select("location_recordid")->distinct()->get();
            $facility_recordid_list = array();
            foreach ($facility_recordids as $key => $value) {
                $facility_recordid = $value->location_recordid;
                array_push($facility_recordid_list, $facility_recordid);
            }
            $facility_recordid_list = array_unique($facility_recordid_list);
            $new_recordid = Location::max('location_recordid') + 1;
            if (in_array($new_recordid, $facility_recordid_list)) {
                $new_recordid = Contact::max('location_recordid') + 1;
            }
            $location_recordid = $new_recordid;
            $facility->location_recordid = $new_recordid;

            $facility->location_name = $request->location_name;
            $facility->location_alternate_name = $request->location_alternate_name;
            $facility->location_transportation = $request->location_transportation;
            $facility->location_description = $request->location_description;
            // $facility->location_details = $request->location_details;

            // accessesibility
            if ($request->accessibility) {
                Accessibility::create([
                    'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                    'accessibility' => $request->accessibility,
                    'accessibility_details' => $request->accessibility_details,
                    'accessibility_location' => $new_recordid
                ]);
            }
            if ($request->regions) {
                $facility->regions()->sync($request->regions);
            }

            // detail section

            if ($request->detail_type) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                $detail_ids = [];
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail_locations = $detail->detail_locations ? explode(',', $detail->detail_locations) : [];
                            if (!empty($detail_locations)) {
                                $detail_locations = array_filter($detail_locations);
                            }
                            if (!in_array($new_recordid, $detail_locations)) {
                                array_push($detail_locations, $new_recordid);
                            }
                            $detail->detail_locations = join(',', $detail_locations);
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_update = Detail::where('detail_recordid', $detail_term[$key])->first();
                            if ($detail_update) {
                                $detail_locations = $detail_update->detail_locations ? explode(',', $detail_update->detail_locations) : [];
                                if (!empty($detail_locations)) {
                                    $detail_locations = array_filter($detail_locations);
                                }
                                if (!in_array($new_recordid, $detail_locations)) {
                                    array_push($detail_locations, $new_recordid);
                                }
                                $detail_update->detail_locations = join(',', $detail_locations);
                                $detail_update->save();
                            }
                            $detail_ids[] = $detail_term[$key];
                        }
                    }
                }
            }
            $facility->location_details = join(',', $detail_ids);

            // $organization_name = $request->facility_organization;
            // $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
            // $facility_organization_id = $facility_organization["organization_recordid"];
            $facility->location_organization = $request->location_organization;

            if ($request->location_services) {
                $facility->location_services = join(',', $request->location_services);
            } else {
                $facility->location_services = '';
            }
            $facility->services()->sync($request->location_services);

            if ($request->facility_schedules) {
                $facility->location_schedule = join(',', $request->facility_schedules);
            } else {
                $facility->location_schedule = '';
            }
            $facility->schedules()->sync($request->facility_schedules);

            // if ($request->facility_address) {
            //     $facility->location_address = join(',', $request->facility_address);
            // } else {
            //     $facility->location_address = '';
            // }
            // $facility->address()->sync($request->facility_address);

            $facility_address_city = $request->facility_address_city;
            $facility_street_address = $request->facility_street_address;
            $facility_address_state = '';
            if ($request->facility_address_state) {
                $facility_address_state = $request->facility_address_state;
            }
            $facility_address_zip_code = $request->facility_zip_code;
            $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

            $address = Address::where('address_1', '=', $facility_street_address)->first();
            $address_recordid_list = [];
            $address_id = '';
            if ($address) {
                $address_id = $address["address_recordid"];
                $facility->location_address = $address_id;
            } else {
                $address = new Address;
                $new_recordid = Address::max('address_recordid') + 1;
                $address->address_recordid = $new_recordid;
                $address->address_1 = $facility_street_address;
                $address->address_city = $facility_address_city;
                $address->address_state_province = $facility_address_state;
                $address->address_postal_code = $facility_address_zip_code;
                $facility->location_address = $new_recordid;
                $address_id = $new_recordid;
                $address->save();
            }
            array_push($address_recordid_list, $address_id);
            $facility->address()->sync($address_recordid_list);

            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($facility->location_address) {
                $address_recordid = $facility->location_address;
                $address_info = Address::where('address_recordid', '=', $address_recordid)->select('address_1', 'address_city', 'address_state_province', 'address_postal_code')->first();
                $full_address_info = $address_info->address_1 . ', ' . $address_info->address_city . ', ' . $address_info->address_state_province . ', ' . $address_info->address_postal_code;

                $response = $geocoder->getCoordinatesForAddress($full_address_info);

                if ($response['lat']) {
                    $latitude = $response['lat'];
                    $longitude = $response['lng'];
                    $facility->location_latitude = $latitude;
                    $facility->location_longitude = $longitude;
                } else {
                    Session::flash('message', 'Address info is not valid. We can not get longitude and latitude for this address');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
            }


            // if ($request->facility_phones) {
            //     $facility_phone_number_list = $request->facility_phones;
            //     foreach ($facility_phone_number_list as $key => $facility_phone_number) {
            //         $phone_info = Phone::where('phone_number', '=', $facility_phone_number)->select('phone_recordid')->first();
            //         if ($phone_info) {
            //             $facility->location_phones = $facility->location_phones . $phone_info->phone_recordid . ',';
            //             array_push($phone_recordid_list, $phone_info->phone_recordid);
            //         } else {
            //             $new_phone = new Phone;
            //             $new_phone_recordid = Phone::max('phone_recordid') + 1;
            //             $new_phone->phone_recordid = $new_phone_recordid;
            //             $new_phone->phone_number = $facility_phone_number;
            //             $new_phone->save();
            //             $facility->location_phones = $facility->location_phones . $new_phone_recordid . ',';
            //             array_push($phone_recordid_list, $new_phone_recordid);
            //         }
            //     }
            // }
            $facility->location_phones = '';
            $phone_recordid_list = [];
            if ($request->facility_phones) {
                $facility_phone_number_list = $request->facility_phones;
                $facility_phone_extension_list = $request->phone_extension;
                $facility_phone_type_list =  $request->phone_type;
                $facility_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $facility_phone_description_list = $request->phone_description;
                $facility_main_priority_list = $request->main_priority;

                // foreach ($facility_phone_number_list as $key => $facility_phone_number) {

                //     if ($phone_info) {
                //         $facility->facility_phones = $facility->facility_phones . $phone_info->phone_recordid . ',';
                //         array_push($phone_recordid_list, $phone_info->phone_recordid);
                //     } else {
                for ($i = 0; $i < count($facility_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $facility_phone_number_list[$i])->first();
                    if ($phone_info) {
                        // $facility->location_phones = $facility->location_phones .  ',' . $phone_info->phone_recordid;
                        $phone_info->phone_number = $facility_phone_number_list[$i];
                        $phone_info->phone_extension = $facility_phone_extension_list[$i];
                        $phone_info->phone_type = $facility_phone_type_list[$i];
                        $phone_info->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
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
                        $new_phone->phone_number = $facility_phone_number_list[$i];
                        $new_phone->phone_extension = $facility_phone_extension_list[$i];
                        $new_phone->phone_type = $facility_phone_type_list[$i];
                        $new_phone->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        // $facility->location_phones = $facility->location_phones . $new_phone_recordid . ',';
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                    //     }
                    // }
                }
            }
            $phone_recordid_list = array_unique($phone_recordid_list);
            $facility->location_phones = '';
            $facility->location_phones = count($phone_recordid_list) > 0 ? implode(',', $phone_recordid_list) : '';

            $facility->phones()->sync($phone_recordid_list);

            $facility->save();

            $audit = Audit::where('auditable_id', $facility->location_recordid)->update([
                'auditable_id' => $location_recordid
            ]);

            // if ($audit) {
            //     $audit->auditable_id = $location_recordid;
            //     $audit->save();
            //     dd($audit);
            // }
            Session::flash('message', 'Facility added successfully!');
            Session::flash('status', 'success');
            return redirect('facilities');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('facilities');
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
        // $process = Location::find($id);
        // return response()->json($process);

        $facility = Location::where('location_recordid', '=', $id)->first();

        $locations = Location::where('location_recordid', '=', $id)->get();
        if ($facility) {
            $locations = $locations->filter(function ($value) {
                $value->organization_name = $value->organization ? $value->organization->organization_name : '';
                $value->organization_recordid = $value->organization ? $value->organization->organization_recordid : '';
                $value->address_name = $value->address && count($value->address) > 0 ? $value->address[0]->address_1 : '';
                $value->services = $value->services;
                return true;
            });

            $map = Map::find(1);

            $facility_organization_recordid_list = explode(',', $facility->location_organization);
            $facility_organizations = Organization::whereIn('organization_recordid', $facility_organization_recordid_list)->orderBy('organization_name')->get();

            $comment_list = Comment::where('comments_location', '=', $id)->get();

            $existing_tag_element_list = Location::whereNotNull('location_tag')->get()->pluck('location_tag');
            $existing_tags = [];
            foreach ($existing_tag_element_list as $key => $existing_tag_element) {
                $existing_tag_list = explode(", ", $existing_tag_element);
                foreach ($existing_tag_list as $key => $existing_tag) {
                    if (!in_array($existing_tag, $existing_tags, true)) {
                        array_push($existing_tags, $existing_tag);
                    }
                }
            }
            $locationDetails = [];
            if ($facility->location_details) {
                $detail_recordids = explode(',', $facility->location_details);

                $locationDetails = Detail::whereIn('detail_recordid', $detail_recordids)->get();
            }

            $facilityAudits = $this->commonController->locationSection($facility);

            return view('frontEnd.locations.show', compact('facility', 'map', 'locations', 'facility_organizations', 'comment_list', 'existing_tags', 'locationDetails', 'facilityAudits'));
        } else {
            Session::flash('message', 'This record has been deleted.');
            Session::flash('status', 'warning');
            return redirect('facilities');
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
        $facility = Location::where('location_recordid', '=', $id)->first();
        if ($facility) {

            if ($facility->accessibilities && isset($facility->accessibilities[0])) {
                $facility->accessibility = $facility->accessibilities[0]->accessibility;
                $facility->accessibility_details = $facility->accessibilities[0]->accessibility_details;
            } else {
                $facility->accessibility_details = 'Visitors with concerns about the level of access for specific physical conditions, are always recommended to contact the organization directly to obtain the best possible information about physical access';
            }

            $facility_organization_recordid_list = explode(',', $facility->location_organization);
            $facility_organizations = Organization::whereIn('organization_recordid', $facility_organization_recordid_list)->orderBy('organization_name')->get();

            if ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->user_organization && $facility_organizations &&
                str_contains(Auth::user()->user_organization, $facility_organizations[0]->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')) {

                // $organization_names = Organization::pluck("organization_name", "organization_recordid");
                if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
                    $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
                    $organization_names = Organization::orderBy('organization_name')->whereIn('organization_recordid', $organization_recordid)->pluck("organization_name", "organization_recordid");
                } else {
                    $organization_names = Organization::orderBy('organization_name')->pluck("organization_name", "organization_recordid");
                }
                // $organization_name_list = [];
                // foreach ($organization_names as $key => $value) {
                //     $org_names = explode(", ", trim($value->organization_name));
                //     $organization_name_list = array_merge($organization_name_list, $org_names);
                // }
                // $organization_name_list = array_unique($organization_name_list);

                $organization_id = $facility->location_organization;
                $organization_name_info = Organization::where('organization_recordid', '=', $organization_id)->select('organization_name')->first();
                $facility_organization_name = '';
                if ($organization_name_info) {
                    $facility_organization_name = $organization_name_info["organization_name"];
                }

                $facility_organization_list = explode(',', $facility->location_organization);
                $service_recordid_list = [];
                foreach ($facility_organizations as $key => $value) {
                    $service_recordid_list = array_merge($value->services()->pluck('service_recordid')->toArray(), $service_recordid_list);
                }

                $service_info_list = Service::whereIn('service_recordid', $service_recordid_list)->orderBy('service_name')->pluck('service_name', 'service_recordid')->toArray();
                // $service_info_list = Service::orderBy('service_name')->pluck('service_name', 'service_recordid')->toArray();

                $facility_service_list = explode(',', $facility->location_services);

                $serviceData = $facility->services ? $facility->services->pluck('service_recordid')->toArray() : [];
                if (!empty($serviceData)) {
                    $facility_service_list = array_merge($facility_service_list, $serviceData);
                    $facility_service_list = array_unique($facility_service_list);
                }

                $facility_phone_number = '';
                if (sizeof($facility->phones)) {
                    $facility_phone_number = $facility->phones[0]['phone_number'];
                }

                $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();

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
                // $address_city_list = Address::pluck('address_city', 'address_city')->unique();
                // $address_states_list = Address::pluck('address_state_province', 'address_state_province')->unique();
                $address_city_list = City::orderBy('city')->pluck('city', 'city');
                $address_states_list = State::orderBy('state')->pluck('state', 'state');


                $facility_location_address = Location::where('location_recordid', '=', $id)->select('location_address')->first();

                $facility_location_address_id = $facility_location_address['location_address'];
                $address = Address::where('address_recordid', '=', $facility_location_address_id)->first();

                // $address_city_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_city')->first();

                $location_address_city = '';
                if ($address) {
                    // $location_address_city = $address->address_city;
                    $facility->facility_address_city = $address->address_city;
                }

                // $address_street_address_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_1')->first();
                $location_street_address = '';
                if ($address) {
                    $facility->facility_street_address = $address->address_1;
                }

                $address_zip_code_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_postal_code')->first();
                $location_zip_code = '';
                if ($address_zip_code_info) {
                    $facility->facility_zip_code = $address->address_postal_code;
                    // $location_zip_code = $address_zip_code_info['address_postal_code'];
                }

                $address_state_info = Address::where('address_recordid', '=', $facility_location_address_id)->select('address_state_province')->first();
                $location_state = '';
                if ($address_state_info) {
                    $facility->facility_address_state = $address->address_state_province;
                    // $location_state = $address_state_info['address_state_province'];
                }
                $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
                $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');

                $locationDetails = [];
                if ($facility->location_details) {
                    $detail_recordids = explode(',', $facility->location_details);
                    $locationDetails = Detail::whereIn('detail_recordid', $detail_recordids)->get();
                }

                $detail_types = DetailType::orderBy('order')->pluck('type', 'type');

                $phone_language_data = [];
                $phone_language_name = [];
                if ($facility->phones) {
                    foreach ($facility->phones as $key => $value) {
                        $phone_language_data[$key] = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languageId = $value->phone_language ? explode(',', $value->phone_language) : [];
                        $languages = Language::whereIn('language_recordid', $languageId)->pluck('language')->toArray();
                        $phone_language_name[$key] = implode(', ', $languages);
                    }
                }
                $phone_language_data = json_encode($phone_language_data);

                $facilityAudits = $this->commonController->locationSection($facility);
                $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
                $regions = Region::pluck('region', 'id');

                return view('frontEnd.locations.edit', compact('facility', 'map', 'facility_organization_name', 'service_info_list', 'facility_service_list', 'organization_names', 'facility_organization_list', 'facility_phone_number', 'schedule_info_list', 'address_info_list', 'detail_info_list', 'address_states_list', 'address_city_list', 'location_address_city', 'location_street_address', 'location_zip_code', 'location_state', 'phone_languages', 'phone_type', 'detail_types', 'locationDetails', 'phone_language_data', 'facilityAudits', 'phone_language_name', 'all_phones', 'regions'));
            } else {
                Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
                Session::flash('status', 'warning');
                return redirect('/');
            }
        } else {
            return redirect('facilities');
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
            'location_name' => 'required',
            'location_organization' => 'required',
        ]);
        try {
            $facility = Location::find($id);

            $facility->location_name = $request->location_name;
            $facility->location_organization = $request->location_organization;

            $facility->location_description = $request->location_description;
            $facility->location_alternate_name = $request->location_alternate_name;
            $facility->location_transportation = $request->location_transportation;
            // $facility->location_details = $request->location_details;

            // accessesibility
            if ($request->accessibility) {
                Accessibility::updateOrCreate([
                    'accessibility_location' => $id
                ], [
                    'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                    'accessibility' => $request->accessibility,
                    'accessibility_details' => $request->accessibility_details,
                ]);
            }
            if ($request->regions) {
                $facility->regions()->sync($request->regions);
            }

            // detail section
            $detail_ids = [];

            if ($request->detail_type) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                $allDetails = Detail::where('detail_locations', 'LIKE', '%' . $id . '%')->get();

                foreach ($allDetails as $detailItem) {
                    $changeDetail = Detail::whereId($detailItem->id)->first();
                    if ($changeDetail) {
                        $detail_locations = $changeDetail->detail_locations ? explode(',', $changeDetail->detail_locations) : [];
                        $detail_locations = array_filter($detail_locations);
                        if (in_array($id, $detail_locations)) {
                            $index = array_search($id, $detail_locations);
                            $detail_locations = array_splice($detail_locations, $index, 1);
                        }
                        $changeDetail->detail_locations = join(',', $detail_locations);
                        $changeDetail->save();
                    }
                }
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail_locations = $detail->detail_locations ? explode(',', $detail->detail_locations) : [];
                            if (!empty($detail_locations)) {
                                $detail_locations = array_filter($detail_locations);
                            }
                            if (!in_array($id, $detail_locations)) {
                                array_push($detail_locations, $id);
                            }
                            $detail->detail_locations = join(',', $detail_locations);
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            if (isset($detail_term[$key])) {
                                $detail_update = Detail::where('detail_recordid', $detail_term[$key])->first();
                                if ($detail_update) {
                                    $detail_locations = $detail_update->detail_locations ? explode(',', $detail_update->detail_locations) : [];
                                    if (!empty($detail_locations)) {
                                        $detail_locations = array_filter($detail_locations);
                                    }
                                    if (!in_array($id, $detail_locations)) {
                                        array_push($detail_locations, $id);
                                    }
                                    $detail_update->detail_locations = join(',', $detail_locations);
                                    $detail_update->save();
                                }
                                $detail_ids[] = $detail_term[$key];
                            }
                        }
                    }
                }
            }
            $facility->location_details = join(',', $detail_ids);

            if ($request->facility_service) {
                $facility->location_services = join(',', $request->facility_service);
            } else {
                $facility->location_services = '';
            }
            $facility->services()->sync($request->facility_service);

            if ($request->facility_schedules) {
                $facility->location_schedule = join(',', $request->facility_schedules);
            } else {
                $facility->location_schedule = '';
            }
            $facility->schedules()->sync($request->facility_schedules);

            $facility_address_city = $request->facility_address_city;
            $facility_street_address = $request->facility_street_address;
            $facility_address_state = '';
            if ($request->facility_address_state) {
                $facility_address_state = $request->facility_address_state;
            }
            $facility_address_zip_code = $request->facility_zip_code;
            $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

            $address = Address::where('address_1', '=', $facility_street_address)->first();
            $address_recordid_list = [];
            $address_id = '';
            if ($address) {
                $address_id = $address["address_recordid"];
                $facility->location_address = $address_id;
                $address->address_1 = $facility_street_address;
                $address->address_city = $facility_address_city;
                $address->address_state_province = $facility_address_state;
                $address->address_postal_code = $facility_address_zip_code;
                $address->save();
            } else {
                $address = new Address;
                $new_recordid = Address::max('address_recordid') + 1;
                $address->address_recordid = $new_recordid;
                $address->address_1 = $facility_street_address;
                $address->address_city = $facility_address_city;
                $address->address_state_province = $facility_address_state;
                $address->address_postal_code = $facility_address_zip_code;
                $facility->location_address = $new_recordid;
                $address_id = $new_recordid;
                $address->save();
            }
            array_push($address_recordid_list, $address_id);
            $facility->address()->sync($address_recordid_list);

            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($facility->location_address) {
                $address_recordid = $facility->location_address;
                $address_info = Address::where('address_recordid', '=', $address_recordid)->select('address_1', 'address_city', 'address_state_province', 'address_postal_code')->first();
                $full_address_info = $address_info->address_1 . ', ' . $address_info->address_city . ', ' . $address_info->address_state_province . ', ' . $address_info->address_postal_code;

                $response = $geocoder->getCoordinatesForAddress($full_address_info);

                if ($response['lat']) {
                    $latitude = $response['lat'];
                    $longitude = $response['lng'];
                    $facility->location_latitude = $latitude;
                    $facility->location_longitude = $longitude;
                } else {
                    return redirect()->back()->with('error', 'Address info is not valid. We can not get longitude and latitude for this address')->withInput();
                }
            }


            $facility->location_phones = '';
            $phone_recordid_list = [];


            if ($request->facility_phones) {
                $facility_phone_number_list = $request->facility_phones;
                $facility_phone_extension_list = $request->phone_extension;
                $facility_phone_type_list =  $request->phone_type;
                $facility_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $facility_phone_description_list = $request->phone_description;
                $facility_main_priority_list = $request->main_priority;


                for ($i = 0; $i < count($facility_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $facility_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $phone_info->phone_number = $facility_phone_number_list[$i];
                        $phone_info->phone_extension = $facility_phone_extension_list[$i];
                        $phone_info->phone_type = $facility_phone_type_list[$i];
                        $phone_info->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
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
                        $new_phone->phone_number = $facility_phone_number_list[$i];
                        $new_phone->phone_extension = $facility_phone_extension_list[$i];
                        $new_phone->phone_type = $facility_phone_type_list[$i];
                        $new_phone->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $phone_recordid_list = array_unique($phone_recordid_list);
            $facility->location_phones = '';
            $facility->location_phones = count($phone_recordid_list) > 0 ? implode(',', $phone_recordid_list) : '';

            $facility->phones()->sync($phone_recordid_list);

            if ($request->removePhoneDataId) {
                $removePhoneDataId = explode(',', $request->removePhoneDataId);
                LocationPhone::whereIn('phone_recordid', $removePhoneDataId)->where('location_recordid', $facility->location_recordid)->delete();
            }
            if ($request->deletePhoneDataId) {
                $deletePhoneDataId = explode(',', $request->deletePhoneDataId);
                LocationPhone::whereIn('phone_recordid', $deletePhoneDataId)->where('location_recordid', $facility->location_recordid)->delete();
                Phone::whereIn('phone_recordid', $deletePhoneDataId)->delete();
            }

            $facility->save();

            $location_organization = $request->facility_organization_name;
            $organization = Organization::where('organization_recordid', '=', $location_organization)->select('organization_recordid', 'updated_at')->first();
            if ($organization) {
                $organization->updated_at = date("Y-m-d H:i:s");
                $organization->save();
            }


            Session::flash('message', 'facility updated successfully!');
            Session::flash('status', 'success');
            return redirect('facilities/' . $id);
        } catch (\Throwable $th) {
            Log::error('Error in location update : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'success');
            return redirect('facilities');
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
    public function delete_facility(Request $request)
    {
        try {
            // Location::where('location_recordid', $request->facility_recordid)->delete();
            $location = Location::find($request->facility_recordid);
            if ($location) {
                $location->delete();
            }
            Session::flash('message', 'Facility deleted successfully!');
            Session::flash('status', 'success');
            return redirect('facilities');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function create_in_service($id)
    {
        $service_recordid = $id;
        $map = Map::find(1);
        $layout  = Layout::find(1);
        $service = Service::where('service_recordid', $service_recordid)->first();
        $organization_recordid = $service->organizations ? $service->organizations->organization_recordid : '';

        // $organization_names = Organization::select("organization_name")->distinct()->get();
        // $organization_name_list = [];
        // foreach ($organization_names as $key => $value) {
        //     $org_names = explode(", ", trim($value->organization_name));
        //     $organization_name_list = array_merge($organization_name_list, $org_names);
        // }
        // $organization_name_list = array_unique($organization_name_list);
        if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
            $organizations = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_name', 'organization_recordid') : [];
        } else {
            $organizations = Organization::pluck("organization_name", 'organization_recordid')->unique();
        }
        $regions = Region::pluck('region', 'id');

        $service_info_list = Service::select('service_recordid', 'service_name')->orderBy('service_name')->distinct()->get();
        $schedule_info_list = Schedule::select('schedule_recordid', 'opens_at', 'closes_at')->whereNotNull('opens_at')->where('opens_at', '!=', '')->orderBy('opens_at')->distinct()->get();
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

        $phone_languages = Language::orderBy('order')->whereNotNull('language_recordid')->pluck('language', 'language_recordid');
        $phone_type = PhoneType::orderBy('order')->pluck('type', 'id');
        $detail_types = DetailType::orderBy('order')->pluck('type', 'type');
        $all_phones = Phone::whereNotNull('phone_number')->distinct()->get();
        $phone_language_data = json_encode([]);

        return view('frontEnd.locations.facility-create-in-service', compact('service_recordid', 'map', 'service_info_list', 'schedule_info_list', 'address_info_list', 'detail_info_list', 'address_states_list', 'address_city_list', 'organization_recordid', 'organizations', 'phone_languages', 'phone_type', 'detail_types', 'all_phones', 'phone_language_data', 'regions'));
    }
    public function add_new_facility_in_service(Request $request)
    {
        $this->validate($request, [
            'location_name' => 'required',
            'facility_organization' => 'required',
        ]);
        try {
            $facility = new Location;

            $facility_recordids = Location::select("location_recordid")->distinct()->get();
            $facility_recordid_list = array();
            foreach ($facility_recordids as $key => $value) {
                $facility_recordid = $value->location_recordid;
                array_push($facility_recordid_list, $facility_recordid);
            }
            $facility_recordid_list = array_unique($facility_recordid_list);

            $new_recordid = Location::max('location_recordid') + 1;
            if (in_array($new_recordid, $facility_recordid_list)) {
                $new_recordid = Contact::max('location_recordid') + 1;
            }
            $facility->location_recordid = $new_recordid;

            $facility->location_name = $request->location_name;
            $facility->location_alternate_name = $request->location_alternate_name;
            $facility->location_transportation = $request->location_transportation;
            $facility->location_description = $request->location_description;

            $organization_recordid = $request->facility_organization;
            $facility_organization = Organization::where('organization_recordid', '=', $organization_recordid)->first();
            $facility_organization_id = $facility_organization["organization_recordid"];
            $facility->location_organization = $facility_organization_id;

            $facility->services()->sync($request->facility_service);

            if ($request->facility_schedules) {
                $facility->location_schedule = join(',', $request->facility_schedules);
            } else {
                $facility->location_schedule = '';
            }
            $facility->schedules()->sync($request->facility_schedules);

            // accessesibility
            if ($request->accessibility) {
                Accessibility::create([
                    'accessibility_recordid' => Accessibility::max('accessibility_recordid') + 1,
                    'accessibility' => $request->accessibility,
                    'accessibility_details' => $request->accessibility_details,
                    'accessibility_location' => $new_recordid
                ]);
            }

            if ($request->regions) {
                $facility->regions()->sync($request->regions);
            }
            // detail section
            if ($request->detail_type) {
                $detail_type = $request->detail_type;
                $detail_term = $request->detail_term;
                $term_type = $request->term_type;
                $detail_ids = [];
                foreach ($detail_type as $key => $value) {
                    if (isset($detail_type[$key]) && isset($detail_term[$key]) && isset($term_type[$key])) {
                        if ($term_type[$key] == 'new') {
                            $detail = new Detail();
                            $detail_recordid = Detail::max('detail_recordid') + 1;
                            $detail->detail_recordid = $detail_recordid;
                            $detail->detail_type = $value;
                            $detail_locations = $detail->detail_locations ? explode(',', $detail->detail_locations) : [];
                            if (!empty($detail_locations)) {
                                $detail_locations = array_filter($detail_locations);
                            }
                            if (!in_array($new_recordid, $detail_locations)) {
                                array_push($detail_locations, $new_recordid);
                            }
                            $detail->detail_locations = join(',', $detail_locations);
                            $detail->detail_value = $detail_term[$key];
                            $detail->save();

                            $detail_ids[] = $detail_recordid;
                        } else {
                            $detail_update = Detail::where('detail_recordid', $detail_term[$key])->first();
                            if ($detail_update) {
                                $detail_locations = $detail_update->detail_locations ? explode(',', $detail_update->detail_locations) : [];
                                if (!empty($detail_locations)) {
                                    $detail_locations = array_filter($detail_locations);
                                }
                                if (!in_array($new_recordid, $detail_locations)) {
                                    array_push($detail_locations, $new_recordid);
                                }
                                $detail_update->detail_locations = join(',', $detail_locations);
                                $detail_update->save();
                            }
                            $detail_ids[] = $detail_term[$key];
                        }
                    }
                }
            }
            $facility->location_details = join(',', $detail_ids);


            $facility_address_city = $request->facility_address_city;
            $facility_street_address = $request->facility_street_address;
            $facility_address_state = '';
            if ($request->facility_address_state) {
                $facility_address_state = $request->facility_address_state;
            }
            $facility_address_zip_code = $request->facility_zip_code;
            $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

            $address = Address::where('address_1', '=', $facility_street_address)->first();
            $address_recordid_list = [];
            $address_id = '';
            if ($address) {
                $address_id = $address["address_recordid"];
                $facility->location_address = $address_id;
            } else {
                $address = new Address;
                $new_recordid = Address::max('address_recordid') + 1;
                $address->address_recordid = $new_recordid;
                $address->address_1 = $facility_street_address;
                $address->address_city = $facility_address_city;
                $address->address_state_province = $facility_address_state;
                $address->address_postal_code = $facility_address_zip_code;
                $facility->location_address = $new_recordid;
                $address_id = $new_recordid;
                $address->save();
            }
            array_push($address_recordid_list, $address_id);
            $facility->address()->sync($address_recordid_list);

            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($facility->location_address) {
                $address_recordid = $facility->location_address;
                $address_info = Address::where('address_recordid', '=', $address_recordid)->select('address_1', 'address_city', 'address_state_province', 'address_postal_code')->first();
                $full_address_info = $address_info->address_1 . ', ' . $address_info->address_city . ', ' . $address_info->address_state_province . ', ' . $address_info->address_postal_code;

                $response = $geocoder->getCoordinatesForAddress($full_address_info);

                if ($response['lat']) {
                    $latitude = $response['lat'];
                    $longitude = $response['lng'];
                    $facility->location_latitude = $latitude;
                    $facility->location_longitude = $longitude;
                } else {
                    Session::flash('message', 'Address info is not valid. We can not get longitude and latitude for this address');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
            }
            $facility->location_phones = '';
            $phone_recordid_list = [];
            if ($request->facility_phones) {
                $facility_phone_number_list = $request->facility_phones;
                $facility_phone_extension_list = $request->phone_extension;
                $facility_phone_type_list =  $request->phone_type;
                $facility_phone_language_list = $request->phone_language_data ? json_decode($request->phone_language_data) : [];
                $facility_phone_description_list = $request->phone_description;
                $facility_main_priority_list = $request->main_priority;

                for ($i = 0; $i < count($facility_phone_number_list); $i++) {
                    $phone_info = Phone::where('phone_number', '=', $facility_phone_number_list[$i])->first();
                    if ($phone_info) {
                        $phone_info->phone_number = $facility_phone_number_list[$i];
                        $phone_info->phone_extension = $facility_phone_extension_list[$i];
                        $phone_info->phone_type = $facility_phone_type_list[$i];
                        $phone_info->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $phone_info->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
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
                        $new_phone->phone_number = $facility_phone_number_list[$i];
                        $new_phone->phone_extension = $facility_phone_extension_list[$i];
                        $new_phone->phone_type = $facility_phone_type_list[$i];
                        $new_phone->phone_language = $facility_phone_language_list && isset($facility_phone_language_list[$i]) && is_array($facility_phone_language_list[$i]) ? implode(',', $facility_phone_language_list[$i]) : '';
                        $new_phone->phone_description = $facility_phone_description_list[$i];
                        if (isset($facility_main_priority_list[0]) && $facility_main_priority_list[0] == $i) {
                            $new_phone->main_priority = '1';
                        } else {
                            $new_phone->main_priority = '0';
                        }
                        $new_phone->save();
                        array_push($phone_recordid_list, $new_phone_recordid);
                    }
                }
            }
            $phone_recordid_list = array_unique($phone_recordid_list);
            $facility->location_phones = '';
            $facility->location_phones = count($phone_recordid_list) > 0 ? implode(',', $phone_recordid_list) : '';
            $facility->phones()->sync($phone_recordid_list);

            $facility->save();
            Session::flash('message', 'Facility added successfully!');
            Session::flash('status', 'success');
            return redirect('facilities');
        } catch (\Throwable $th) {
            Log::error('Error in add location in service : ' . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('facilities');
        }
    }
    public function export_location()
    {
        return Excel::download(new LocationExport, 'Locations.csv');
    }
}
