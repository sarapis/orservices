<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Contact;
use App\Model\ExportConfiguration;
use App\Model\ExportHistory;
use App\Model\HsdsApiKey;
use App\Model\Language;
use App\Model\Location;
use App\Model\LocationAddress;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Phone;
use App\Model\Program;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceArea;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServiceSchedule;
use App\Model\ServiceTag;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backEnd.export.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $organization_tags = OrganizationTag::pluck('tag', 'id');
        $service_tags = ServiceTag::pluck('tag', 'id');
        $key = time() . '_' . Str::random(5);

        return view('backEnd.export.create', compact('organization_tags', 'key', 'service_tags'));
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
            'name' => 'required',
            // 'filter' => 'required',
            'type' => 'required',
        ]);
        try {
            $file_path = 'export_csv/';
            $file_name = $request->key . '.zip';
            $full_path_name = $file_path . $file_name;

            DB::beginTransaction();
            ExportConfiguration::create([
                'name' => $request->name,
                'endpoint' => $request->type == 'api_feed' ?  $request->endpoint : '',
                'filter' => $request->filter ? implode(',', $request->filter) : null,
                'type' => $request->type,
                'organization_tags' => $request->filter && is_array($request->filter) && in_array('organization_tags', $request->filter) ? ($request->organization_tags ? implode(',', $request->organization_tags) : '') : null,
                'service_tags' => $request->filter && is_array($request->filter) && in_array('service_tags', $request->filter) ? ($request->service_tags ? implode(',', $request->service_tags) : '') : null,
                'key' => $request->key,
                // 'auto_sync' => $request->auto_sync,
                // 'hours' => $request->auto_sync == 1 ? $request->hours : '',
                'file_path' => $file_path,
                'file_name' => $file_name,
                'full_path_name' => $full_path_name,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Export Configuration Added successfully!');
            Session::flash('status', 'success');
            return redirect('export');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('export');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization_tags = OrganizationTag::pluck('tag', 'id');
        $export_configuration = ExportConfiguration::whereId($id)->first();
        $service_tags = ServiceTag::pluck('tag', 'id');

        return view('backEnd.export.edit', compact('organization_tags', 'export_configuration', 'service_tags'));
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
            'name' => 'required',
            // 'filter' => 'required',
            'type' => 'required',
        ]);
        try {
            $file_path = 'export_csv/';
            $file_name = $request->key . '.zip';
            $full_path_name = $file_path .  $file_name;
            DB::beginTransaction();
            ExportConfiguration::whereId($id)->update([
                'name' => $request->name,
                'endpoint' => $file_path .  $request->key,
                'filter' => $request->filter ? implode(',', $request->filter) : null,
                'type' => $request->type,
                'organization_tags' => $request->filter && is_array($request->filter) && in_array('organization_tags', $request->filter) ? ($request->organization_tags ? implode(',', $request->organization_tags) : '') : null,
                'service_tags' => $request->filter && is_array($request->filter) && in_array('service_tags', $request->filter) ? ($request->service_tags ? implode(',', $request->service_tags) : '') : null,
                'key' => $request->key,
                'file_path' => $file_path,
                'file_name' => $file_name,
                'full_path_name' => $full_path_name,
                // 'auto_sync' => $request->auto_sync,
                // 'hours' => $request->auto_sync == 1 ? $request->hours : '',
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Export Configuration updated successfully!');
            Session::flash('status', 'success');
            return redirect('export');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('export');
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
        try {
            ExportConfiguration::whereId($id)->delete();
            Session::flash('message', 'Export Configuration deleted successfully!');
            Session::flash('status', 'success');
            return redirect('export');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('export');
        }
    }
    public function getExportConfiguration(Request $request)
    {
        try {
            $exportConfiguration = ExportConfiguration::select('*');

            if (!$request->ajax()) {
                return view('backEnd.export.index', compact('exportConfiguration'));
            }
            return DataTables::of($exportConfiguration)
                // ->editColumn('auto_sync', function ($row) {
                //     $link = $row->auto_sync == 1 ? "On" : "Off";
                //     return $link;
                // })
                ->editColumn('type', function ($row) {
                    $link = $row->type == 'api_feed' ? "API Feed" : "Download";
                    return $link;
                })
                ->editColumn('key', function ($row) {
                    $link = $row->type == 'api_feed' ? $row->key : "";
                    return $link;
                })
                ->editColumn('endpoint', function ($row) {
                    $link = $row->type == 'api_feed' && $row->endpoint ? url($row->endpoint) : '';
                    return $link;
                })
                ->editColumn('organization_tags', function ($row) {
                    // $link = 'Off&nbsp;&nbsp;<input type="checkbox" class="switch" value="1" name="auto_sync" data-id="' . $row->id . '" id="auto_sync" ' . ($row->auto_sync == 1 ? "checked" : "") . ' />&nbsp;&nbsp;On';
                    // return $link;
                    $link = '';
                    $tagName = [];
                    if ($row->organization_tags) {
                        $tags = explode(',', $row->organization_tags);
                        foreach ($tags as $key => $value) {
                            $orgTag = OrganizationTag::whereId($value)->first();
                            $tagName[] = $orgTag->tag;
                        }
                        $tagName = array_filter($tagName);
                    }
                    if (count($tagName) > 0) {
                        $link = 'Tags : ' . implode(',', $tagName);
                    }
                    if ($row->filter == 'none') {
                        $link = 'none';
                    }
                    return $link;
                })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("export.exportData", $row->id) . '" class="btn btn-info btn-sm " style="margin-right:10px;">Export Now</a>';
                        $links .= '<a href="' . route("export.edit", $row->id) . '" style="margin-right:10px;"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'export';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                // ->filter(function ($query) use ($request) {
                //     $extraData = $request->get('extraData');

                //     if ($extraData) {
                //         if (isset($extraData['detail_type']) && $extraData['detail_type'] != null) {
                //             $query = $query->whereIn('detail_type', $extraData['detail_type']);
                //         }
                //     }
                //     return $query;
                // })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error("Error in getExportConfiguration : " . $th);
        }
    }
    public function getExportHistory(Request $request)
    {
        try {
            $exportHistory = ExportHistory::select('*');

            if (!$request->ajax()) {
                return view('backEnd.import.import', compact('exportHistory'));
            }
            return DataTables::of($exportHistory)
                ->editColumn('created_at', function ($row) {

                    return date('d-m-Y H:i:s A', strtotime($row->created_at));
                })
                ->editColumn('auto_sync', function ($row) {
                    $link = $row->auto_sync == 1 ? "Auto" : "Manual";
                    return $link;
                })
                ->editColumn('configuration', function ($row) {
                    $link = $row->configuration ? $row->name : "";
                    return $link;
                })
                // ->editColumn('import_type', function ($row) {
                //     $link = $row->import_type == 'airtable' ? "Airtable 2.2" : "Zipfile";
                //     return $link;
                // })
                // ->filter(function ($query) use ($request) {
                //     $extraData = $request->get('extraData');

                //     if ($extraData) {
                //         if (isset($extraData['detail_type']) && $extraData['detail_type'] != null) {
                //             $query = $query->whereIn('detail_type', $extraData['detail_type']);
                //         }
                //     }
                //     return $query;
                // })
                ->rawColumns(['auto_sync'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function exportData($id)
    {
        try {

            $export_configuration = ExportConfiguration::whereId($id)->first();

            if ($export_configuration) {
                $path_csv_export = public_path($export_configuration->file_path);
                $public_path = public_path('/');
                $organization_ids = [];
                if ($export_configuration->organization_tags) {
                    $export_configuration->organization_tags = explode(',', $export_configuration->organization_tags);
                    if (in_array('download_all', $export_configuration->organization_tags)) {
                        $organization_ids = Organization::pluck('organization_recordid')->toArray();
                    } else {
                        $organization_tags = $export_configuration->organization_tags;
                        $organization_ids = Organization::where(function ($query) use ($organization_tags) {
                            foreach ($organization_tags as $keyword) {
                                $query = $query->orWhere('organization_tag', 'LIKE', "%$keyword%");
                            }
                            return $query;
                        })->pluck('organization_recordid')->toArray();
                    }
                }
                if (!empty($organization_ids)) {
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid');
                    $table_service = Service::whereIn('service_recordid', $organization_service_recordid)->select('*');
                } else {
                    $table_service = Service::select('*');
                }
                $service_ids = [];
                if ($export_configuration->service_tags) {
                    $export_configuration->service_tags = explode(',', $export_configuration->service_tags);
                    if (in_array('download_all', $export_configuration->service_tags)) {
                        $service_ids = Service::pluck('service_recordid')->toArray();
                    } else {
                        $service_tags = $export_configuration->service_tags;
                        $service_ids = Service::where(function ($query) use ($service_tags) {
                            foreach ($service_tags as $keyword) {
                                $query = $query->orWhere('service_tag', 'LIKE', "%$keyword%");
                            }
                            return $query;
                        })->pluck('service_recordid')->toArray();
                    }
                }
                if (!empty($service_ids)) {
                    $table_service = $table_service->whereIn('service_recordid', $service_ids)->get();
                } else {
                    $table_service = $table_service->get();
                }
                // if (file_exists(public_path('datapackage.zip'))) {
                //     unlink(public_path('datapackage.zip'));
                // }
                $file_service = fopen('services.csv', 'w');
                fputcsv($file_service, array('id', 'organization_id', 'program_id', 'name', 'alternate_name', 'description', 'url', 'email', 'status', 'interpretation_services', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses'));
                // fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
                $service_recordids_temp = [];
                foreach ($table_service as $row) {
                    $programs = $row->program->pluck('program_recordid')->toArray();
                    if ($row->service_organization) {
                        $service_recordids_temp[] = $row->service_recordid;
                        $serviceArray = [
                            'id' => $row->service_recordid,
                            'organization_id' => $row->service_organization,
                            // 'program_id' => $row->service_program,
                            'program_id' => count($programs) > 0 ? implode(',', $programs) : '',
                            'name' => $row->service_name,
                            'alternate_name' => $row->service_alternate_name,
                            'description' => $row->service_description,
                            'url' => $row->service_url && strpos($row->service_url, '://') !== false ? trim($row->service_url) : ($row->service_url ? 'http://' . trim($row->service_url) : ''),
                            'email' => trim($row->service_email),
                            'status' => $row->service_status == 'Verified' ? 'active' : 'inactive',
                            'interpretation_services' => '',
                            'application_process' => $row->service_application_process,
                            'wait_time' => $row->service_wait_time,
                            'fees' => $row->service_fees,
                            'accreditations' => $row->service_accreditations,
                            'licenses' => $row->service_licenses,
                            // 'service_recordid' => $row->service_recordid,
                            // 'taxonomy_ids' => $row->service_taxonomy,
                        ];
                        fputcsv($file_service, $serviceArray);
                    }
                }
                fclose($file_service);
                if (file_exists($path_csv_export . 'services.csv')) {
                    unlink($path_csv_export . 'services.csv');
                }
                rename($public_path . 'services.csv', $path_csv_export . 'services.csv');

                if (!empty($organization_ids)) {
                    $table_location = Location::whereIn('location_organization', $organization_ids)->get();
                } else {
                    $table_location = Location::all();
                }
                $file_location = fopen('locations.csv', 'w');
                fputcsv($file_location, array('id', 'organization_id', 'name', 'alternate_name', 'description', 'transportation', 'latitude', 'longitude'));

                // fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));
                $location_recordids_temp = [];
                foreach ($table_location as $row) {
                    $location_recordids_temp[] = $row->location_recordid;
                    $locationArray = [
                        'id' => $row->location_recordid,
                        'organization_id' => $row->location_organization,
                        'name' => $row->location_name,
                        'alternate_name' => $row->location_alternate_name,
                        'description' => $row->location_description,
                        'transportation' => $row->location_transportation,
                        'latitude' => $row->location_latitude,
                        'longitude' => $row->location_longitude,
                        // 'location_recordid' => $row->location_recordid,
                    ];
                    // fputcsv($file_location, $row->toArray());
                    fputcsv($file_location, $locationArray);
                }
                fclose($file_location);
                if (file_exists($path_csv_export . 'locations.csv')) {
                    unlink($path_csv_export . 'locations.csv');
                }
                rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');

                if (!empty($organization_ids)) {
                    $table_organization = Organization::whereIn('organization_recordid', $organization_ids)->get();
                } else {
                    $table_organization = Organization::all();
                }
                $file_organization = fopen('organizations.csv', 'w');

                fputcsv($file_organization, array('id', 'name', 'alternate_name', 'description', 'email', 'url', 'tax_status', 'tax_id', 'year_incorporated', 'legal_status'));

                // fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
                foreach ($table_organization as $row) {
                    if ($row->organization_description) {
                        $organizationArray = [
                            'id' => strval($row->organization_recordid),
                            'name' => $row->organization_name,
                            'alternate_name' => $row->organization_alternate_name,
                            'description' => $row->organization_description,
                            'email' => trim($row->organization_email),
                            'url' => $row->organization_url && strpos($row->organization_url, '://') !== false ? $row->organization_url : ($row->organization_url ? 'http://' . $row->organization_url : ''),
                            'tax_status' => $row->organization_tax_status,
                            'tax_id' => $row->organization_tax_id,
                            'year_incorporated' => $row->organization_year_incorporated,
                            'legal_status' => $row->organization_legal_status,
                            // 'organization_recordid' => $row->organization_recordid,
                        ];
                        fputcsv($file_organization, $organizationArray);
                    }
                    // fputcsv($file_organization, $row->toArray());
                }
                fclose($file_organization);
                if (file_exists($path_csv_export . 'organizations.csv')) {
                    unlink($path_csv_export . 'organizations.csv');
                }
                rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');

                if (!empty($organization_ids)) {
                    $table_contact = Contact::whereIn('contact_organizations', $organization_ids)->get();
                } else {
                    $table_contact = Contact::all();
                }
                $file_contact = fopen('contacts.csv', 'w');
                fputcsv($file_contact, array('id', 'organization_id', 'service_id', 'service_at_location_id', 'name', 'title', 'department', 'email'));
                // fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
                foreach ($table_contact as $row) {

                    $locationArray = [
                        'id' => $row->contact_recordid,
                        'organization_id' => $row->contact_organizations,
                        'service_id' => $row->contact_services,
                        'service_at_location_id' => '',
                        'name' => $row->contact_name,
                        'title' => $row->contact_title,
                        'department' => $row->contact_department,
                        'email' => ($row->contact_email && $row->contact_email[-1] == '.') ? substr_replace($row->contact_email, "", -1) :  trim($row->contact_email),
                        // 'contact_recordid' => $row->contact_recordid,
                    ];
                    fputcsv($file_contact, $locationArray);
                    // fputcsv($file_contact, $row->toArray());
                }
                fclose($file_contact);
                if (file_exists($path_csv_export . 'contacts.csv')) {
                    unlink($path_csv_export . 'contacts.csv');
                }
                rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');
                // if (!empty($organization_ids)) {
                //     $table_phone = Phone::whereIn('phone_organizations', $organization_ids)->get();
                // } else {
                //     $table_phone = Phone::all();
                // }
                // if (isset($location_recordids_temp) && count($location_recordids_temp) > 0) {
                //     $table_phone = Phone::whereIn('phone_locations', $location_recordids_temp)->get();
                // } else {
                $table_phone = Phone::all();
                // }
                $file_phone = fopen('phones.csv', 'w');
                fputcsv($file_phone, array('id', 'location_id', 'service_id', 'organization_id', 'contact_id', 'service_at_location_id', 'number', 'extension', 'type', 'language', 'description', 'department', 'Priority'));
                // fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
                foreach ($table_phone as $row) {
                    if ($row->phone_number && isset($row->locations) && $row->locations()->count() > 0) {
                        // if ($row->phone_number && ($row->phone_locations || $row->main_priority == '1')) {
                        foreach ($row->locations as $key => $value) {
                            if (in_array($value->location_recordid, $location_recordids_temp)) {
                                $phoneArray = [
                                    'id' => $row->phone_recordid,
                                    // 'location_id' => $row->phone_locations,
                                    'location_id' => $value->location_recordid,
                                    'service_id' => $row->phone_services,
                                    'organization_id' => $row->phone_organizations,
                                    'contact_id' => $row->phone_contacts,
                                    'service_at_location_id' => '',
                                    'number' => $row->phone_number,
                                    'extension' => is_numeric($row->phone_extension) ? $row->phone_extension : '',
                                    'type' => $row->phone_type,
                                    'language' => $row->phone_language,
                                    'description' => $row->phone_description,
                                    'department' => '',
                                    'priority' => $row->main_priority == '1' ? 'main' : '',
                                    // 'phone_recordid' => $row->phone_recordid,
                                ];
                                fputcsv($file_phone, $phoneArray);
                            }
                        }
                    }
                    // fputcsv($file_phone, $row->toArray());
                }
                fclose($file_phone);
                if (file_exists($path_csv_export . 'phones.csv')) {
                    unlink($path_csv_export . 'phones.csv');
                }
                rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');
                if (isset($location_recordids_temp) && count($location_recordids_temp) > 0) {
                    $address_record_ids = LocationAddress::whereIn('location_recordid', $location_recordids_temp)->pluck('address_recordid')->toArray();
                    $table_address = Address::whereIn('address_recordid', $address_record_ids)->get();
                } else {
                    $table_address = Address::all();
                }
                $file_address = fopen('physical_addresses.csv', 'w');
                fputcsv($file_address, array('id', 'location_id', 'attention', 'address_1', 'address_2', 'address_3', 'address_4', 'city', 'region', 'state_province', 'postal_code', 'country'));
                // fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
                foreach ($table_address as $row) {
                    // if ($row->address_1 && $row->address_city && $row->address_state_province && $row->address_postal_code && $row->address_country) {
                    // $locationIds = explode(',', $row->address_locations);
                    // if (count($locationIds) > 0) {
                    //     foreach ($locationIds as $key => $value) {
                    //         if ($value == null || $value == '') {
                    //             $location_address_ids = LocationAddress::where('address_recordid', $row->address_recordid)->get();
                    //             if (count($location_address_ids) > 0) {
                    //                 foreach ($location_address_ids as $key => $location_data) {
                    //                     $addressArray = [
                    //                         'id' => $row->address_recordid,
                    //                         'location_id' => $location_data->location_recordid,
                    //                         'attention' => $row->address_attention,
                    //                         'address_1' => $row->address_1,
                    //                         'address_2' => $row->address_2,
                    //                         'address_3' => '',
                    //                         'address_4' => '',
                    //                         'city' => $row->address_city,
                    //                         'region' => $row->address_region,
                    //                         'state_province' => $row->address_state_province,
                    //                         'postal_code' => $row->address_postal_code,
                    //                         'country' => $row->address_country,
                    //                     ];
                    //                     fputcsv($file_address, $addressArray);
                    //                 }
                    //             }
                    //         } else {
                    //             $addressArray = [
                    //                 'id' => $row->address_recordid,
                    //                 'location_id' => $value,
                    //                 'attention' => $row->address_attention,
                    //                 'address_1' => $row->address_1,
                    //                 'address_2' => $row->address_2,
                    //                 'address_3' => '',
                    //                 'address_4' => '',
                    //                 'city' => $row->address_city,
                    //                 'region' => $row->address_region,
                    //                 'state_province' => $row->address_state_province,
                    //                 'postal_code' => $row->address_postal_code,
                    //                 'country' => $row->address_country,
                    //             ];
                    //             fputcsv($file_address, $addressArray);
                    //         }
                    //     }
                    // } else {
                    //     $location_address_id = LocationAddress::where('address_recordid', $row->address_recordid)->first();
                    //     $addressArray = [
                    //         'id' => $row->id,
                    //         // 'location_id' => $row->address_locations,
                    //         'location_id' => $location_address_id ? $location_address_id->location_recordid : '',
                    //         'attention' => $row->address_attention,
                    //         'address_1' => $row->address_1,
                    //         'address_2' => $row->address_2,
                    //         'address_3' => '',
                    //         'address_4' => '',
                    //         'city' => $row->address_city,
                    //         'region' => $row->address_region,
                    //         'state_province' => $row->address_state_province,
                    //         'postal_code' => $row->address_postal_code,
                    //         'country' => $row->address_country,
                    //     ];
                    //     fputcsv($file_address, $addressArray);
                    // }
                    // }
                    $location_address_ids = LocationAddress::where('address_recordid', $row->address_recordid)->get();
                    if (count($location_address_ids) > 0) {
                        foreach ($location_address_ids as $key => $location_data) {
                            $addressArray = [
                                'id' => $row->address_recordid,
                                'location_id' => $location_data->location_recordid,
                                'attention' => $row->address_attention,
                                'address_1' => $row->address_1,
                                'address_2' => $row->address_2,
                                'address_3' => '',
                                'address_4' => '',
                                'city' => $row->address_city,
                                'region' => $row->address_region,
                                'state_province' => $row->address_state_province,
                                'postal_code' => $row->address_postal_code,
                                'country' => $row->address_country,
                            ];
                            fputcsv($file_address, $addressArray);
                        }
                    }
                }
                fclose($file_address);
                if (file_exists($path_csv_export . 'physical_addresses.csv')) {
                    unlink($path_csv_export . 'physical_addresses.csv');
                }
                rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');

                $table_language = Language::all();
                $file_language = fopen('languages.csv', 'w');
                fputcsv($file_language, array('id', 'service_id', 'location_id', 'language', 'language_recordid'));
                // fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
                foreach ($table_language as $row) {

                    $langaugeArray = [
                        'id' => $row->id,
                        'service_id' => $row->language_service,
                        'location_id' => $row->language_location,
                        'language' => $row->language,
                        'language_recordid' => $row->language_recordid,
                    ];
                    fputcsv($file_language, $langaugeArray);
                    // fputcsv($file_language, $row->toArray());
                }
                fclose($file_language);
                if (file_exists($path_csv_export . 'languages.csv')) {
                    unlink($path_csv_export . 'languages.csv');
                }
                rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');

                $table_taxonomy = Taxonomy::all();
                $file_taxonomy = fopen('taxonomy_terms.csv', 'w');
                // fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
                fputcsv($file_taxonomy, array('id', 'term', 'description', 'parent_id', 'taxonomy', 'language', 'x_taxonomies', 'taxonomy_x_notes', 'badge_color'));
                foreach ($table_taxonomy as $row) {
                    // if ($row->taxonomy_name && $row->taxonomy_x_description) {
                    $taxonomyArray = [
                        'id' => $row->taxonomy_recordid,
                        'term' => $row->taxonomy_name,
                        'description' => $row->taxonomy_x_description,
                        'parent_id' => $row->taxonomy_parent_name,
                        'taxonomy' => $row->taxonomy_type && count($row->taxonomy_type) > 0 ? $row->taxonomy_type[0]->name : '',
                        'language' => '',
                        'x_taxonomies' => $row->x_taxonomies,
                        'taxonomy_x_notes' => $row->taxonomy_x_notes,
                        'badge_color' => $row->badge_color,
                        // 'taxonomy_recordid' => $row->taxonomy_recordid,
                        // 'vocabulary' => $row->badge_color,
                    ];
                    fputcsv($file_taxonomy, $taxonomyArray);
                    // fputcsv($file_taxonomy, $row->toArray());
                    // }
                }
                fclose($file_taxonomy);
                if (file_exists($path_csv_export . 'taxonomy_terms.csv')) {
                    unlink($path_csv_export . 'taxonomy_terms.csv');
                }
                rename($public_path . 'taxonomy_terms.csv', $path_csv_export . 'taxonomy_terms.csv');
                if (count($service_recordids_temp) > 0) {
                    $table_servicetaxonomy = ServiceTaxonomy::whereIn('service_recordid', $service_recordids_temp)->get();
                } else {
                    $table_servicetaxonomy = ServiceTaxonomy::all();
                }
                $file_servicetaxonomy = fopen('service_attributes.csv', 'w');
                fputcsv($file_servicetaxonomy, array('id', 'service_id', 'taxonomy_term_id'));
                // fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
                foreach ($table_servicetaxonomy as $row) {
                    if ($row->service_recordid) {
                        $ServiceTaxonomyArray = [
                            'id' => $row->id,
                            'service_id' => $row->service_recordid,
                            'taxonomy_term_id' => $row->taxonomy_recordid,
                            // 'taxonomy_detail' => $row->taxonomy_detail,
                        ];
                        fputcsv($file_servicetaxonomy, $ServiceTaxonomyArray);
                        // fputcsv($file_servicetaxonomy, $row->toArray());
                    }
                }
                fclose($file_servicetaxonomy);
                if (file_exists($path_csv_export . 'service_attributes.csv')) {
                    unlink($path_csv_export . 'service_attributes.csv');
                }
                rename($public_path . 'service_attributes.csv', $path_csv_export . 'service_attributes.csv');

                if (isset($service_recordids_temp) && count($service_recordids_temp) > 0) {
                    $table_servicelocation = ServiceLocation::whereIn('service_recordid', $service_recordids_temp)->get();
                } else {
                    $table_servicelocation = ServiceLocation::all();
                }
                $file_servicelocation = fopen('services_at_location.csv', 'w');
                fputcsv($file_servicelocation, array('id', 'service_id', 'location_id', 'description'));
                // fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
                foreach ($table_servicelocation as $row) {
                    if (isset($location_recordids_temp) && count($location_recordids_temp) > 0 && in_array($row->location_recordid, $location_recordids_temp)) {
                        $ServiceLocationArray = [
                            'id' => $row->id,
                            'service_id' => $row->service_recordid,
                            'location_id' => $row->location_recordid,
                            'description' => '',
                        ];
                        // fputcsv($file_servicelocation, $row->toArray());
                        fputcsv($file_servicelocation, $ServiceLocationArray);
                    }
                }
                fclose($file_servicelocation);
                if (file_exists($path_csv_export . 'services_at_location.csv')) {
                    unlink($path_csv_export . 'services_at_location.csv');
                }
                rename($public_path . 'services_at_location.csv', $path_csv_export . 'services_at_location.csv');

                $table_accessibility = Accessibility::all();
                $file_accessibility = fopen('accessibility_for_disabilities.csv', 'w');
                fputcsv($file_accessibility, array('id', 'location_id', 'accessibility', 'details'));
                foreach ($table_accessibility as $row) {
                    $AccessibilityArray = [
                        'id' => $row->accessibility_recordid,
                        'location_id' => $row->accessibility_location,
                        'accessibility' => $row->accessibility,
                        'details' => $row->accessibility_details,
                    ];

                    fputcsv($file_accessibility, $AccessibilityArray);
                }
                fclose($file_accessibility);
                if (file_exists($path_csv_export . 'accessibility_for_disabilities.csv')) {
                    unlink($path_csv_export . 'accessibility_for_disabilities.csv');
                }
                rename($public_path . 'accessibility_for_disabilities.csv', $path_csv_export . 'accessibility_for_disabilities.csv');

                $table_schedule = Schedule::all();
                $file_schedule = fopen('schedules.csv', 'w');
                // fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
                fputcsv($file_schedule, array('id', 'name', 'service_id', 'location_id', 'service_at_location_id', 'weekday', 'valid_from', 'valid_to', 'dtstart', 'until', 'wkst', 'freq', 'interval', 'byday', 'byweekno', 'bymonthday', 'byyearday', 'description'));

                foreach ($table_schedule as $row) {
                    if (!empty($organization_ids)) {
                        $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid');
                        $service_recordids = Service::whereIn('service_recordid', $organization_service_recordid)->pluck('service_recordid')->toArray();
                        $ServiceSchedule = ServiceSchedule::where('schedule_recordid', $row->schedule_recordid)->whereIn('service_recordid', $service_recordids)->get();
                    } else {
                        $ServiceSchedule = ServiceSchedule::where('schedule_recordid', $row->schedule_recordid)->get();
                    }
                    if (count($ServiceSchedule) > 0) {
                        foreach ($ServiceSchedule as $key => $data) {
                            $scheduleArray = [
                                'id' => $row->schedule_recordid,
                                'name' => $row->name,
                                'service_id' => $data->service_recordid,
                                'location_id' => $row->locations,
                                'service_at_location_id' => $row->service_at_location,
                                'weekday' => $row->weekday,
                                'valid_from' => $row->valid_from,
                                'valid_to' => $row->valid_to,
                                'dtstart' => $row->opens,
                                'until' => $row->closes,
                                'wkst' => $row->wkst,
                                'freq' => $row->freq,
                                'interval' => $row->interval,
                                'byday' => $row->byday,
                                'byweekno' => $row->byweekno,
                                'bymonthday' => $row->bymonthday,
                                'byyearday' => $row->byyearday,
                                'description' => $row->description,
                                // 'schedule_recordid' => $row->schedule_recordid,
                            ];
                            fputcsv($file_schedule, $scheduleArray);
                        }
                        // } else {
                        //     $scheduleArray = [
                        //         'id' => $row->id,
                        //         'service_id' => $row->schedule_services,
                        //         'location_id' => $row->locations,
                        //         'service_at_location_id' => $row->service_at_location,
                        //         'valid_from' => $row->valid_from,
                        //         'valid_to' => $row->valid_to,
                        //         'dtstart' => $row->opens,
                        //         'until' => $row->closes,
                        //         'wkst' => $row->wkst,
                        //         'freq' => $row->freq,
                        //         'interval' => $row->interval,
                        //         'byday' => $row->byday,
                        //         'byweekno' => $row->byweekno,
                        //         'bymonthday' => $row->bymonthday,
                        //         'byyearday' => $row->byyearday,
                        //         'description' => $row->description,
                        //         // 'schedule_recordid' => $row->schedule_recordid,
                        //     ];
                        //     fputcsv($file_schedule, $scheduleArray);
                    }
                    // }
                    // fputcsv($file_schedule, $row->toArray());
                }
                fclose($file_schedule);
                if (file_exists($path_csv_export . 'schedules.csv')) {
                    unlink($path_csv_export . 'schedules.csv');
                }
                rename($public_path . 'schedules.csv', $path_csv_export . 'schedules.csv');

                // program

                $table_program = Program::all();
                $file_program = fopen('programs.csv', 'w');
                fputcsv($file_program, array('id', 'organization_id', 'name', 'alternate_name'));

                // fputcsv($file_program, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'program_services', 'program_phones', 'program_details', 'program_schedule', 'program_address', 'flag'));

                foreach ($table_program as $row) {

                    $programArray = [
                        'id' => $row->program_recordid,
                        'organization_id' => $row->organizations,
                        'name' => $row->name,
                        'alternate_name' => $row->alternate_name,
                    ];
                    // fputcsv($file_program, $row->toArray());
                    fputcsv($file_program, $programArray);
                }
                fclose($file_program);
                if (file_exists($path_csv_export . 'programs.csv')) {
                    unlink($path_csv_export . 'programs.csv');
                }
                rename($public_path . 'programs.csv', $path_csv_export . 'programs.csv');

                // service area

                $table_service_area = ServiceArea::all();
                $file_service_area = fopen('service_areas.csv', 'w');
                fputcsv($file_service_area, array('id', 'service_id', 'service_area', 'description'));
                foreach ($table_service_area as $row) {

                    $programArray = [
                        'id' => $row->id,
                        'service_id' => $row->services,
                        'service_area' => $row->name,
                        'description' => $row->description,
                    ];
                    // fputcsv($file_service_area, $row->toArray());
                    fputcsv($file_service_area, $programArray);
                }
                fclose($file_service_area);
                if (file_exists($path_csv_export . 'service_areas.csv')) {
                    unlink($path_csv_export . 'service_areas.csv');
                }
                rename($public_path . 'service_areas.csv', $path_csv_export . 'service_areas.csv');

                ExportHistory::create([
                    'name' => $export_configuration->name,
                    'auto_sync' => $export_configuration->auto_sync,
                    'configuration_id' => $export_configuration->id,
                    'status' => 'completed',
                ]);

                $zip_file = 'zip/' . $export_configuration->file_name;
                // $zip_file = 'datapackage.zip';
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $path = $path_csv_export;
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                foreach ($files as $name => $file) {
                    // We're skipping all subfolders
                    if (!$file->isDir()) {
                        $filePath     = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($path));
                        // $relativePath = substr($filePath, strlen($path));
                        // dd($relativePath, $filePath, $path);
                        $zip->addFile($filePath, $relativePath);
                    }
                }
                $zip->close();
                if ($export_configuration->type == 'download') {
                    return response()->download($zip_file);
                } else {
                    Session::flash('status', 'success');
                    Session::flash('message', 'Export file updated successfully!');
                    return redirect('export');
                }
            } else {
                return response()->json('Failed', 500);
            }
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('status', 'error');
            Session::flash('message', $th->getMessage());
            return redirect('export');
        }
    }
    public function export_csv(Request $request, $id)
    {
        $export_configuration = ExportConfiguration::where('key', $id)->first();
        if ($export_configuration) {
            if ($export_configuration && $export_configuration->auto_sync == 1 && file_exists(public_path('zip/' . $export_configuration->file_name))) {
                return response()->download('zip/' . $export_configuration->file_name);
            }
            $path_csv_export = public_path($export_configuration->file_path);
            $public_path = public_path('/');
            $organization_ids = [];
            if ($export_configuration->organization_tags) {
                $export_configuration->organization_tags = explode(',', $export_configuration->organization_tags);
                if (in_array('download_all', $export_configuration->organization_tags)) {
                    $organization_ids = Organization::pluck('organization_recordid')->toArray();
                } else {
                    $organization_tags = $export_configuration->organization_tags;
                    $organization_ids = Organization::where(function ($query) use ($organization_tags) {
                        foreach ($organization_tags as $keyword) {
                            $query = $query->orWhere('organization_tag', 'LIKE', "%$keyword%");
                        }
                        return $query;
                    })->pluck('organization_recordid')->toArray();
                }
            }

            /* services */
            if (!empty($organization_ids)) {
                $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid');
                $table_service = Service::whereIn('service_recordid', $organization_service_recordid)->select('*');
            } else {
                $table_service = Service::select('*');
            }
            $service_ids = [];
            if ($export_configuration->service_tags) {
                $export_configuration->service_tags = explode(',', $export_configuration->service_tags);
                if (in_array('download_all', $export_configuration->service_tags)) {
                    $service_ids = Service::pluck('service_recordid')->toArray();
                } else {
                    $service_tags = $export_configuration->service_tags;
                    $service_ids = Service::where(function ($query) use ($service_tags) {
                        foreach ($service_tags as $keyword) {
                            $query = $query->orWhere('service_tag', 'LIKE', "%$keyword%");
                        }
                        return $query;
                    })->pluck('service_recordid')->toArray();
                }
            }
            if (!empty($service_ids)) {
                $table_service = $table_service->whereIn('service_recordid', $service_ids)->get();
            } else {
                $table_service = $table_service->get();
            }
            // if (file_exists(public_path('datapackage.zip'))) {
            //     unlink(public_path('datapackage.zip'));
            // }
            $file_service = fopen('services.csv', 'w');
            fputcsv($file_service, array('id', 'organization_id', 'program_id', 'name', 'alternate_name', 'description', 'url', 'email', 'status', 'interpretation_services', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses'));
            // fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
            $service_recordids_temp = [];
            foreach ($table_service as $row) {
                $programs = $row->program->pluck('program_recordid')->toArray();


                if ($row->service_organization) {
                    $service_recordids_temp[] = $row->service_recordid;
                    $serviceArray = [
                        'id' => $row->service_recordid,
                        'organization_id' => $row->service_organization,
                        // 'program_id' => $row->service_program,
                        'program_id' => count($programs) > 0 ? implode(',', $programs) : '',
                        'name' => $row->service_name,
                        'alternate_name' => $row->service_alternate_name,
                        'description' => $row->service_description,
                        'url' => $row->service_url && strpos($row->service_url, '://') !== false ? trim($row->service_url) : ($row->service_url ? 'http://' . trim($row->service_url) : ''),
                        'email' => trim($row->service_email),
                        'status' => $row->service_status == 'Verified' ? 'active' : 'inactive',
                        'interpretation_services' => '',
                        'application_process' => $row->service_application_process,
                        'wait_time' => $row->service_wait_time,
                        'fees' => $row->service_fees,
                        'accreditations' => $row->service_accreditations,
                        'licenses' => $row->service_licenses,
                        // 'service_recordid' => $row->service_recordid,
                        // 'taxonomy_ids' => $row->service_taxonomy,
                    ];
                    fputcsv($file_service, $serviceArray);
                }
            }
            fclose($file_service);
            if (file_exists($path_csv_export . 'services.csv')) {
                unlink($path_csv_export . 'services.csv');
            }
            rename($public_path . 'services.csv', $path_csv_export . 'services.csv');
            /* /services */


            /* service_at_location */
            if (isset($service_recordids_temp) && count($service_recordids_temp) > 0) {
                $table_servicelocation = ServiceLocation::whereIn('service_recordid', $service_recordids_temp)->get();
            } else {
                $table_servicelocation = ServiceLocation::all();
            }

            $file_servicelocation = fopen('services_at_location.csv', 'w');
            fputcsv($file_servicelocation, array('id', 'service_id', 'location_id', 'description'));
            // fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
            $location_recordids_temp = [];
            foreach ($table_servicelocation as $row) {
                //if (isset($location_recordids_temp) && count($location_recordids_temp) > 0 && in_array($row->location_recordid, $location_recordids_temp)) {
                if (($service_recordids_temp ?? null) && in_array($row->service_recordid, $service_recordids_temp)) {
                    $location_recordids_temp[] = $row->location_recordid;
                    $ServiceLocationArray = [
                        'id' => $row->id,
                        'service_id' => $row->service_recordid,
                        'location_id' => $row->location_recordid,
                        'description' => '',
                    ];
                    // fputcsv($file_servicelocation, $row->toArray());
                    fputcsv($file_servicelocation, $ServiceLocationArray);
                }
            }
            fclose($file_servicelocation);
            if (file_exists($path_csv_export . 'services_at_location.csv')) {
                unlink($path_csv_export . 'services_at_location.csv');
            }
            rename($public_path . 'services_at_location.csv', $path_csv_export . 'services_at_location.csv');
            /* /service_at_location */


            /* locations */
            if (!empty($organization_ids)) {
                $table_location = Location::whereIn('location_organization', $organization_ids)->orWhereIn('location_recordid', $location_recordids_temp)->get();
            } else {
                $table_location = Location::all();
            }
            $file_location = fopen('locations.csv', 'w');
            fputcsv($file_location, array('id', 'organization_id', 'name', 'alternate_name', 'description', 'transportation', 'latitude', 'longitude'));

            // fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));
            // $location_recordids_temp = [];		// !! $location_recordids_temp is not empty by this moment
            foreach ($table_location as $row) {
                $location_recordids_temp[] = $row->location_recordid;
                $locationArray = [
                    'id' => $row->location_recordid,
                    'organization_id' => $row->location_organization,
                    'name' => $row->location_name,
                    'alternate_name' => $row->location_alternate_name,
                    'description' => $row->location_description,
                    'transportation' => $row->location_transportation,
                    'latitude' => $row->location_latitude,
                    'longitude' => $row->location_longitude,
                    // 'location_recordid' => $row->location_recordid,
                ];
                // fputcsv($file_location, $row->toArray());
                fputcsv($file_location, $locationArray);
            }
            array_unique($location_recordids_temp);
            fclose($file_location);
            if (file_exists($path_csv_export . 'locations.csv')) {
                unlink($path_csv_export . 'locations.csv');
            }
            rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');
            /* /locations */


            /* organizations */
            if (!empty($organization_ids)) {
                $table_organization = Organization::whereIn('organization_recordid', $organization_ids)->get();
            } else {
                $table_organization = Organization::all();
            }
            $file_organization = fopen('organizations.csv', 'w');

            fputcsv($file_organization, array('id', 'name', 'alternate_name', 'description', 'email', 'url', 'tax_status', 'tax_id', 'year_incorporated', 'legal_status'));

            // fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
            foreach ($table_organization as $row) {
                if ($row->organization_description) {
                    $organizationArray = [
                        'id' => strval($row->organization_recordid),
                        'name' => $row->organization_name,
                        'alternate_name' => $row->organization_alternate_name,
                        'description' => $row->organization_description,
                        'email' => trim($row->organization_email),
                        'url' => $row->organization_url && strpos($row->organization_url, '://') !== false ? $row->organization_url : ($row->organization_url ? 'http://' . $row->organization_url : ''),
                        'tax_status' => $row->organization_tax_status,
                        'tax_id' => $row->organization_tax_id,
                        'year_incorporated' => $row->organization_year_incorporated,
                        'legal_status' => $row->organization_legal_status,
                        // 'organization_recordid' => $row->organization_recordid,
                    ];
                    fputcsv($file_organization, $organizationArray);
                }
                // fputcsv($file_organization, $row->toArray());
            }
            fclose($file_organization);
            if (file_exists($path_csv_export . 'organizations.csv')) {
                unlink($path_csv_export . 'organizations.csv');
            }
            rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');
            /* /organizations */


            /* contacts */
            if (!empty($organization_ids)) {
                $table_contact = Contact::whereIn('contact_organizations', $organization_ids)->orWhereIn('contact_services', $service_recordids_temp)->get();
            } else {
                $table_contact = Contact::all();
            }
            $file_contact = fopen('contacts.csv', 'w');
            fputcsv($file_contact, array('id', 'organization_id', 'service_id', 'service_at_location_id', 'name', 'title', 'department', 'email'));
            // fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
            foreach ($table_contact as $row) {

                $locationArray = [
                    'id' => $row->contact_recordid,
                    'organization_id' => $row->contact_organizations,
                    'service_id' => $row->contact_services,
                    'service_at_location_id' => '',
                    'name' => $row->contact_name,
                    'title' => $row->contact_title,
                    'department' => $row->contact_department,
                    'email' => ($row->contact_email && $row->contact_email[-1] == '.') ? substr_replace($row->contact_email, "", -1) :  trim($row->contact_email),
                    // 'contact_recordid' => $row->contact_recordid,
                ];
                fputcsv($file_contact, $locationArray);
                // fputcsv($file_contact, $row->toArray());
            }
            fclose($file_contact);
            if (file_exists($path_csv_export . 'contacts.csv')) {
                unlink($path_csv_export . 'contacts.csv');
            }
            rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');
            /* /contacts */


            /* phones */
            // if (!empty($organization_ids)) {
            //     $table_phone = Phone::whereIn('phone_organizations', $organization_ids)->get();
            // } else {
            //     $table_phone = Phone::all();
            // }
            // if (isset($location_recordids_temp) && count($location_recordids_temp) > 0) {
            //     $table_phone = Phone::whereIn('phone_locations', $location_recordids_temp)->get();
            // } else {
            $table_phone = Phone::all();
            // }
            $file_phone = fopen('phones.csv', 'w');
            fputcsv($file_phone, array('id', 'location_id', 'service_id', 'organization_id', 'contact_id', 'service_at_location_id', 'number', 'extension', 'type', 'language', 'description', 'department', 'Priority'));
            // fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
            foreach ($table_phone as $row) {
                if ($row->phone_number && isset($row->locations) && $row->locations()->count() > 0) {
                    // if ($row->phone_number && ($row->phone_locations || $row->main_priority == '1')) {
                    foreach ($row->locations as $key => $value) {
                        if (in_array($value->location_recordid, $location_recordids_temp)) {
                            $phoneArray = [
                                'id' => $row->phone_recordid,
                                // 'location_id' => $row->phone_locations,
                                'location_id' => $value->location_recordid,
                                'service_id' => $row->phone_services,
                                'organization_id' => $row->phone_organizations,
                                'contact_id' => $row->phone_contacts,
                                'service_at_location_id' => '',
                                'number' => $row->phone_number,
                                'extension' => is_numeric($row->phone_extension) ? $row->phone_extension : '',
                                'type' => $row->phone_type,
                                'language' => $row->phone_language,
                                'description' => $row->phone_description,
                                'department' => '',
                                'priority' => $row->main_priority == '1' ? 'main' : '',
                                // 'phone_recordid' => $row->phone_recordid,
                            ];
                            fputcsv($file_phone, $phoneArray);
                        }
                    }
                }
                // fputcsv($file_phone, $row->toArray());
            }
            fclose($file_phone);
            if (file_exists($path_csv_export . 'phones.csv')) {
                unlink($path_csv_export . 'phones.csv');
            }
            rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');
            /* /phones */


            /* physical_addresses */
            if (isset($location_recordids_temp) && count($location_recordids_temp) > 0) {
                $address_record_ids = LocationAddress::whereIn('location_recordid', $location_recordids_temp)->pluck('address_recordid')->toArray();
                $table_address = Address::whereIn('address_recordid', $address_record_ids)->get();
                // dd($table_address);
            } else {
                $table_address = Address::all();
            }

            $file_address = fopen('physical_addresses.csv', 'w');
            fputcsv($file_address, array('id', 'location_id', 'attention', 'address_1', 'address_2', 'address_3', 'address_4', 'city', 'region', 'state_province', 'postal_code', 'country'));
            // fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
            foreach ($table_address as $row) {
                // if ($row->address_1 && $row->address_city && $row->address_state_province && $row->address_postal_code && $row->address_country) {
                // $locationIds = explode(',', $row->address_locations);
                // if (count($locationIds) > 0) {
                //     foreach ($locationIds as $key => $value) {
                //         if ($value == null || $value == '') {
                //             $location_address_ids = LocationAddress::where('address_recordid', $row->address_recordid)->get();
                //             if (count($location_address_ids) > 0) {
                //                 foreach ($location_address_ids as $key => $location_data) {
                //                     $addressArray = [
                //                         'id' => $row->address_recordid,
                //                         'location_id' => $location_data->location_recordid,
                //                         'attention' => $row->address_attention,
                //                         'address_1' => $row->address_1,
                //                         'address_2' => $row->address_2,
                //                         'address_3' => '',
                //                         'address_4' => '',
                //                         'city' => $row->address_city,
                //                         'region' => $row->address_region,
                //                         'state_province' => $row->address_state_province,
                //                         'postal_code' => $row->address_postal_code,
                //                         'country' => $row->address_country,
                //                     ];
                //                     fputcsv($file_address, $addressArray);
                //                 }
                //             }
                //         } else {
                //             $addressArray = [
                //                 'id' => $row->address_recordid,
                //                 'location_id' => $value,
                //                 'attention' => $row->address_attention,
                //                 'address_1' => $row->address_1,
                //                 'address_2' => $row->address_2,
                //                 'address_3' => '',
                //                 'address_4' => '',
                //                 'city' => $row->address_city,
                //                 'region' => $row->address_region,
                //                 'state_province' => $row->address_state_province,
                //                 'postal_code' => $row->address_postal_code,
                //                 'country' => $row->address_country,
                //             ];
                //             fputcsv($file_address, $addressArray);
                //         }
                //     }
                // } else {
                //     $location_address_id = LocationAddress::where('address_recordid', $row->address_recordid)->first();
                //     $addressArray = [
                //         'id' => $row->id,
                //         // 'location_id' => $row->address_locations,
                //         'location_id' => $location_address_id ? $location_address_id->location_recordid : '',
                //         'attention' => $row->address_attention,
                //         'address_1' => $row->address_1,
                //         'address_2' => $row->address_2,
                //         'address_3' => '',
                //         'address_4' => '',
                //         'city' => $row->address_city,
                //         'region' => $row->address_region,
                //         'state_province' => $row->address_state_province,
                //         'postal_code' => $row->address_postal_code,
                //         'country' => $row->address_country,
                //     ];
                //     fputcsv($file_address, $addressArray);
                // }
                // }
                $location_address_ids = LocationAddress::where('address_recordid', $row->address_recordid)->get();
                if (count($location_address_ids) > 0) {
                    foreach ($location_address_ids as $key => $location_data) {
                        if (!($location_recordids_temp ?? null) || in_array($location_data->location_recordid, $location_recordids_temp)) {
                            $addressArray = [
                                'id' => $row->address_recordid,
                                'location_id' => $location_data->location_recordid,
                                'attention' => $row->address_attention,
                                'address_1' => $row->address_1,
                                'address_2' => $row->address_2,
                                'address_3' => '',
                                'address_4' => '',
                                'city' => $row->address_city,
                                'region' => $row->address_region,
                                'state_province' => $row->address_state_province,
                                'postal_code' => $row->address_postal_code,
                                'country' => $row->address_country,
                            ];
                            fputcsv($file_address, $addressArray);
                        }
                    }
                }
            }
            fclose($file_address);
            if (file_exists($path_csv_export . 'physical_addresses.csv')) {
                unlink($path_csv_export . 'physical_addresses.csv');
            }
            rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');
            /* /physical_addresses */


            /* languages */
            $table_language = Language::all();
            $file_language = fopen('languages.csv', 'w');
            fputcsv($file_language, array('id', 'service_id', 'location_id', 'language', 'language_recordid'));
            // fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
            foreach ($table_language as $row) {

                $langaugeArray = [
                    'id' => $row->id,
                    'service_id' => $row->language_service,
                    'location_id' => $row->language_location,
                    'language' => $row->language,
                    'language_recordid' => $row->language_recordid,
                ];
                fputcsv($file_language, $langaugeArray);
                // fputcsv($file_language, $row->toArray());
            }
            fclose($file_language);
            if (file_exists($path_csv_export . 'languages.csv')) {
                unlink($path_csv_export . 'languages.csv');
            }
            rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');
            /* /languages */


            /* taxonomy */
            $table_taxonomy = Taxonomy::all();
            $file_taxonomy = fopen('taxonomy_terms.csv', 'w');
            // fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
            fputcsv($file_taxonomy, array('id', 'term', 'description', 'parent_id', 'taxonomy', 'language', 'x_taxonomies', 'taxonomy_x_notes', 'badge_color'));
            foreach ($table_taxonomy as $row) {
                // if ($row->taxonomy_name && $row->taxonomy_x_description) {
                $taxonomyArray = [
                    'id' => $row->taxonomy_recordid,
                    'term' => $row->taxonomy_name,
                    'description' => $row->taxonomy_x_description,
                    'parent_id' => $row->taxonomy_parent_name,
                    'taxonomy' => $row->taxonomy_type && count($row->taxonomy_type) > 0 ? $row->taxonomy_type[0]->name : '',
                    'language' => '',
                    'x_taxonomies' => $row->x_taxonomies,
                    'taxonomy_x_notes' => $row->taxonomy_x_notes,
                    'badge_color' => $row->badge_color,
                    // 'taxonomy_recordid' => $row->taxonomy_recordid,
                    // 'vocabulary' => $row->badge_color,
                ];
                fputcsv($file_taxonomy, $taxonomyArray);
                // fputcsv($file_taxonomy, $row->toArray());
                // }
            }
            fclose($file_taxonomy);
            if (file_exists($path_csv_export . 'taxonomy_terms.csv')) {
                unlink($path_csv_export . 'taxonomy_terms.csv');
            }
            rename($public_path . 'taxonomy_terms.csv', $path_csv_export . 'taxonomy_terms.csv');
            /* /taxonomy */


            /* service_attributes */
            if (count($service_recordids_temp) > 0) {
                $table_servicetaxonomy = ServiceTaxonomy::whereIn('service_recordid', $service_recordids_temp)->get();
            } else {
                $table_servicetaxonomy = ServiceTaxonomy::all();
            }
            $file_servicetaxonomy = fopen('service_attributes.csv', 'w');
            fputcsv($file_servicetaxonomy, array('id', 'service_id', 'taxonomy_term_id'));
            // fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
            foreach ($table_servicetaxonomy as $row) {
                if ($row->service_recordid) {
                    $ServiceTaxonomyArray = [
                        'id' => $row->id,
                        'service_id' => $row->service_recordid,
                        'taxonomy_term_id' => $row->taxonomy_recordid,
                        // 'taxonomy_detail' => $row->taxonomy_detail,
                    ];
                    fputcsv($file_servicetaxonomy, $ServiceTaxonomyArray);
                    // fputcsv($file_servicetaxonomy, $row->toArray());
                }
            }
            fclose($file_servicetaxonomy);
            if (file_exists($path_csv_export . 'service_attributes.csv')) {
                unlink($path_csv_export . 'service_attributes.csv');
            }
            rename($public_path . 'service_attributes.csv', $path_csv_export . 'service_attributes.csv');
            /* /service_attributes */


            /* accessibility_for_disabilities */
            $table_accessibility = Accessibility::all();
            $file_accessibility = fopen('accessibility_for_disabilities.csv', 'w');
            fputcsv($file_accessibility, array('id', 'location_id', 'accessibility', 'details'));
            foreach ($table_accessibility as $row) {

                $AccessibilityArray = [
                    'id' => $row->accessibility_recordid,
                    'location_id' => $row->accessibility_location,
                    'accessibility' => $row->accessibility,
                    'details' => $row->accessibility_details,
                ];

                fputcsv($file_accessibility, $AccessibilityArray);
            }
            fclose($file_accessibility);
            if (file_exists($path_csv_export . 'accessibility_for_disabilities.csv')) {
                unlink($path_csv_export . 'accessibility_for_disabilities.csv');
            }
            rename($public_path . 'accessibility_for_disabilities.csv', $path_csv_export . 'accessibility_for_disabilities.csv');
            /* /accessibility_for_disabilities */


            $table_schedule = Schedule::all();
            $file_schedule = fopen('schedules.csv', 'w');
            // fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
            fputcsv($file_schedule, array('id', 'name', 'service_id', 'location_id', 'service_at_location_id', 'weekday', 'valid_from', 'valid_to', 'dtstart', 'until', 'wkst', 'freq', 'interval', 'byday', 'byweekno', 'bymonthday', 'byyearday', 'description'));
            foreach ($table_schedule as $row) {
                // if ($row->weekday) {
                if (!empty($organization_ids)) {
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid');
                    $service_recordids = Service::whereIn('service_recordid', $organization_service_recordid)->pluck('service_recordid')->toArray();
                    $ServiceSchedule = ServiceSchedule::where('schedule_recordid', $row->schedule_recordid)->whereIn('service_recordid', $service_recordids)->get();
                } else {
                    $ServiceSchedule = ServiceSchedule::where('schedule_recordid', $row->schedule_recordid)->get();
                }
                if (count($ServiceSchedule) > 0) {
                    foreach ($ServiceSchedule as $key => $data) {
                        $scheduleArray = [
                            'id' => $row->schedule_recordid,
                            'name' => $row->name,
                            'service_id' => $data->service_recordid,
                            'location_id' => $row->locations,
                            'service_at_location_id' => $row->service_at_location,
                            'weekday' => $row->weekday,
                            'valid_from' => $row->valid_from,
                            'valid_to' => $row->valid_to,
                            'dtstart' => $row->opens,
                            'until' => $row->closes,
                            'wkst' => $row->wkst,
                            'freq' => $row->freq,
                            'interval' => $row->interval,
                            'byday' => $row->byday,
                            'byweekno' => $row->byweekno,
                            'bymonthday' => $row->bymonthday,
                            'byyearday' => $row->byyearday,
                            'description' => $row->description,
                            // 'schedule_recordid' => $row->schedule_recordid,
                        ];
                        fputcsv($file_schedule, $scheduleArray);
                    }
                    // } else {
                    //     $scheduleArray = [
                    //         'id' => $row->id,
                    //         'service_id' => $row->schedule_services,
                    //         'location_id' => $row->locations,
                    //         'service_at_location_id' => $row->service_at_location,
                    //         'valid_from' => $row->valid_from,
                    //         'valid_to' => $row->valid_to,
                    //         'dtstart' => $row->opens,
                    //         'until' => $row->closes,
                    //         'wkst' => $row->wkst,
                    //         'freq' => $row->freq,
                    //         'interval' => $row->interval,
                    //         'byday' => $row->byday,
                    //         'byweekno' => $row->byweekno,
                    //         'bymonthday' => $row->bymonthday,
                    //         'byyearday' => $row->byyearday,
                    //         'description' => $row->description,
                    //         // 'schedule_recordid' => $row->schedule_recordid,
                    //     ];
                    //     fputcsv($file_schedule, $scheduleArray);
                }
                // }
                // fputcsv($file_schedule, $row->toArray());
            }
            fclose($file_schedule);
            if (file_exists($path_csv_export . 'schedules.csv')) {
                unlink($path_csv_export . 'schedules.csv');
            }
            rename($public_path . 'schedules.csv', $path_csv_export . 'schedules.csv');

            // program

            $table_program = Program::all();
            $file_program = fopen('programs.csv', 'w');
            fputcsv($file_program, array('id', 'organization_id', 'name', 'alternate_name'));

            // fputcsv($file_program, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'program_services', 'program_phones', 'program_details', 'program_schedule', 'program_address', 'flag'));

            foreach ($table_program as $row) {

                $programArray = [
                    'id' => $row->program_recordid,
                    'organization_id' => $row->organizations,
                    'name' => $row->name,
                    'alternate_name' => $row->alternate_name,
                ];
                // fputcsv($file_program, $row->toArray());
                fputcsv($file_program, $programArray);
            }
            fclose($file_program);
            if (file_exists($path_csv_export . 'programs.csv')) {
                unlink($path_csv_export . 'programs.csv');
            }
            rename($public_path . 'programs.csv', $path_csv_export . 'programs.csv');

            // service area

            $table_service_area = ServiceArea::all();
            $file_service_area = fopen('service_areas.csv', 'w');
            fputcsv($file_service_area, array('id', 'service_id', 'service_area', 'description'));
            foreach ($table_service_area as $row) {

                $programArray = [
                    'id' => $row->id,
                    'service_id' => $row->services,
                    'service_area' => $row->name,
                    'description' => $row->description,
                ];
                // fputcsv($file_service_area, $row->toArray());
                fputcsv($file_service_area, $programArray);
            }
            fclose($file_service_area);
            if (file_exists($path_csv_export . 'service_areas.csv')) {
                unlink($path_csv_export . 'service_areas.csv');
            }
            rename($public_path . 'service_areas.csv', $path_csv_export . 'service_areas.csv');

            ExportHistory::create([
                'name' => $export_configuration->name,
                'auto_sync' => $export_configuration->auto_sync,
                'configuration_id' => $export_configuration->id,
                'status' => 'completed',
            ]);

            $zip_file = 'zip/' . $export_configuration->file_name;
            // $zip_file = 'datapackage.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $path = $path_csv_export;
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    // $relativePath = 'datapackage/' . substr($filePath, strlen($path));
                    $relativePath = substr($filePath, strlen($path));
                    // $relativePath = substr($filePath, strlen($path));
                    // dd($relativePath, $filePath, $path);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return response()->download($zip_file);
        } else {
            return response()->json('Failed', 500);
        }
    }
}
