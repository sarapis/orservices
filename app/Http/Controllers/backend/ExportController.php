<?php

namespace App\Http\Controllers\backend;

use App\Exports\OrganizationTagExport;
use App\Exports\zip\AccessibilityZipExport;
use App\Exports\zip\AddressZipExport;
use App\Exports\zip\ContactZipExport;
use App\Exports\zip\LanguageZipExport;
use App\Exports\zip\LocationAddressZipExport;
use App\Exports\zip\LocationPhoneZipExport;
use App\Exports\zip\OrganizationZipExport;
use App\Exports\zip\PhoneZipExport;
use App\Exports\zip\LocationZipExport;
use App\Exports\zip\OrganizationTagZipExport;
use App\Exports\zip\ProgramZipExport;
use App\Exports\zip\ScheduleZipExport;
use App\Exports\zip\ServiceAreaZipExport;
use App\Exports\zip\ServiceLocationZipExport;
use App\Exports\zip\ServicePhoneZipExport;
use App\Exports\zip\ServiceTagZipExport;
use App\Exports\zip\ServiceTaxonomyZipExport;
use App\Exports\zip\ServiceZipExport;
use App\Exports\zip\TaxonomyTermZipExport;
use App\Exports\zip\TaxonomyTypeZipExport;
use App\Exports\zip\TaxonomyZipExport;
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
use App\Model\OrganizationProgram;
use App\Model\OrganizationTag;
use App\Model\Phone;
use App\Model\Program;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceArea;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServiceProgram;
use App\Model\ServiceSchedule;
use App\Model\ServiceTag;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * @param \Illuminate\Http\Request $request
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
            $endpoint = '';
            if ($request->type == 'api_feed') {
                $endpoint = url('/export_csv/' . $request->key);
                $file_path = 'export_csv/';
            } elseif ($request->type == 'data_for_api' || $request->type == 'data_for_api_v2' || $request->type == 'data_for_api_v3') {
                $endpoint = url('/' . $request->type . '/' . $request->key);
                $file_path = $request->type . '/';
            }

            DB::beginTransaction();
            ExportConfiguration::create([
                'name' => $request->name,
                'endpoint' => $endpoint,
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
            $endpoint = '';
            if ($request->type == 'api_feed') {
                $endpoint = url('/export_csv/' . $request->key);
                $file_path = 'export_csv/';
            } elseif ($request->type == 'data_for_api' || $request->type == 'data_for_api_v2' || $request->type == 'data_for_api_v3') {
                $endpoint = url('/' . $request->type . '/' . $request->key);
                $file_path = $request->type . '/';
            }
            $file_name = $request->key . '.zip';
            $full_path_name = $file_path . $file_name;
            DB::beginTransaction();
            ExportConfiguration::whereId($id)->update([
                'name' => $request->name,
                'endpoint' => $endpoint,
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
     * @param int $id
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
                    $link = $row->type == 'api_feed' ? "API Feed" : ($row->type == 'data_for_api' ? 'Data for API' : ($row->type == 'data_for_api_v2' || $row->type == 'data_for_api_v3' ? 'Data for API V2' : "Download"));
                    return $link;
                })
                ->editColumn('key', function ($row) {
                    $link = ($row->type == 'api_feed' || $row->type == 'data_for_api' || $row->type == 'data_for_api_v2' || $row->type == 'data_for_api_v3') ? $row->key : "";
                    return $link;
                })
                ->editColumn('endpoint', function ($row) {
                    $link = ($row->type == 'api_feed' || $row->type == 'data_for_api' || $row->type == 'data_for_api_v2' || $row->type == 'data_for_api_v3') && $row->endpoint ? url($row->endpoint) : '';
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
                        $links .= view('backEnd.delete', compact('id', 'route'))->render();
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
                $zip_file = $this->export($export_configuration);
                if ($export_configuration->type == 'download') {
                    return response()->download($zip_file);
                } else {
                    Session::flash('status', 'success');
                    Session::flash('message', 'Export file updated successfully!');
                    return redirect('export');
                }
            } else {
                Session::flash('status', 'error');
                Session::flash('message', 'Data not Found');
                return redirect('export');
            }
        } catch (\Throwable $th) {
            Log::error('Error from Export: ' . $th->getMessage());
            Session::flash('status', 'error');
            Session::flash('message', $th->getMessage());
            return redirect('export');
        }
    }
    public function data_for_api($id)
    {
        try {
            $export_configuration = ExportConfiguration::where('key', $id)->first();
            if ($export_configuration) {
                $zip_file = $this->export($export_configuration);
                return response()->download($zip_file);
            } else {
                Session::flash('status', 'error');
                Session::flash('message', 'Data not Found');
                return redirect('export');
            }
        } catch (\Throwable $th) {
            Log::error('Error from Export: ' . $th->getMessage());
            Session::flash('status', 'error');
            Session::flash('message', $th->getMessage());
            return redirect('export');
        }
    }
    public function data_for_api_v2($id)
    {
        try {
            $export_configuration = ExportConfiguration::where('key', $id)->first();
            if ($export_configuration) {
                $zip_file = $this->export($export_configuration);
                return response()->download($zip_file);
            } else {
                Session::flash('status', 'error');
                Session::flash('message', 'Data not Found');
                return redirect('export');
            }
        } catch (\Throwable $th) {
            Log::error('Error from Export: ' . $th->getMessage());
            Session::flash('status', 'error');
            Session::flash('message', $th->getMessage());
            return redirect('export');
        }
    }
    public function data_for_api_v3($id)
    {
        try {
            $export_configuration = ExportConfiguration::where('key', $id)->first();
            if ($export_configuration) {
                $zip_file = $this->export($export_configuration, true);
                return response()->download($zip_file);
            } else {
                Session::flash('status', 'error');
                Session::flash('message', 'Data not Found');
                return redirect('export');
            }
        } catch (\Throwable $th) {
            Log::error('Error from Export: ' . $th->getMessage());
            Session::flash('status', 'error');
            Session::flash('message', $th->getMessage());
            return redirect('export');
        }
    }

    public function export($export_configuration, $enclosure = false)
    {
        try {
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
            $organization_service_recordid = [];
            if (!empty($organization_ids)) {
                $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid')->toArray();
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
            $serviceRecordids = array_merge($organization_service_recordid, $service_ids);
            // service CSV
            Excel::store(new ServiceZipExport($serviceRecordids, $enclosure), 'services.csv', 'newExport');

            $location_recordids_temp = [];
            if (!empty($organization_ids)) {
                $table_location = Location::whereIn('location_organization', $organization_ids)->get();
                foreach ($table_location as $row) {
                    $location_recordids_temp[] = $row->location_recordid;
                }
            } else {
                $table_location = Location::all();
            }
            // Location CSV
            Excel::store(new LocationZipExport($organization_ids, $enclosure), 'locations.csv', 'newExport');
            // Organization CSV
            Excel::store(new ServiceZipExport($organization_ids, $enclosure), 'services.csv', 'newExport');
            // Organization CSV
            Excel::store(new OrganizationZipExport($organization_ids, $enclosure), 'organizations.csv', 'newExport');
            // Contacts CSV
            Excel::store(new ContactZipExport($organization_ids, $enclosure), 'contacts.csv', 'newExport');
            // phones CSV
            Excel::store(new PhoneZipExport($enclosure), 'phones.csv', 'newExport');
            // address CSV
            Excel::store(new AddressZipExport($location_recordids_temp, $enclosure), 'physical_addresses.csv', 'newExport');
            // languages CSV
            Excel::store(new LanguageZipExport($enclosure), 'languages.csv', 'newExport');
            // taxonomy_types CSV
            Excel::store(new TaxonomyTypeZipExport($enclosure), 'taxonomy_types.csv', 'newExport');
            // taxonomy_terms_types CSV
            Excel::store(new TaxonomyTermZipExport($enclosure), 'taxonomy_terms_types.csv', 'newExport');
            // taxonomy_terms CSV
            Excel::store(new TaxonomyZipExport($enclosure), 'taxonomy_terms.csv', 'newExport');
            // service_attributes CSV
            Excel::store(new ServiceTaxonomyZipExport($enclosure), 'service_attributes.csv', 'newExport');
            // services_at_location CSV
            Excel::store(new ServiceLocationZipExport($enclosure), 'services_at_location.csv', 'newExport');
            // accessibility_for_disabilities CSV
            Excel::store(new AccessibilityZipExport($enclosure), 'accessibility_for_disabilities.csv', 'newExport');
            // schedules CSV
            Excel::store(new ScheduleZipExport($enclosure), 'schedules.csv', 'newExport');
            // programs CSV
            Excel::store(new ProgramZipExport($enclosure), 'programs.csv', 'newExport');
            // service_areas CSV
            Excel::store(new ServiceAreaZipExport($enclosure), 'service_areas.csv', 'newExport');
            // organizationTag CSV
            Excel::store(new OrganizationTagZipExport($enclosure), 'organization_tags.csv', 'newExport');
            // serviceTag CSV
            Excel::store(new ServiceTagZipExport($enclosure), 'service_tags.csv', 'newExport');
            // locationAddress CSV
            Excel::store(new LocationAddressZipExport($enclosure), 'location_addresses.csv', 'newExport');
            // locationPhone CSV
            Excel::store(new LocationPhoneZipExport($enclosure), 'location_phones.csv', 'newExport');
            // servicePhone CSV
            Excel::store(new ServicePhoneZipExport($enclosure), 'service_phones.csv', 'newExport');

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

            $path = public_path('newExport/');
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            foreach ($files as $name => $file) {

                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path));
                    // $relativePath = substr($filePath, strlen($path));
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return $zip_file;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
