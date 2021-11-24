<?php

namespace App\Http\Controllers\backend;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\AccessibilityImport;
use App\Imports\AddressImport;
use App\Imports\ContactImport;
use App\Imports\LanguageImport;
use App\Imports\LocationImport;
use App\Imports\OrganizationImport;
use App\Imports\PhoneImport;
use App\Imports\ScheduleImport;
use App\Imports\ServiceLocationImport;
use App\Imports\Services;
use App\Imports\ServiceTaxonomyImport;
use App\Imports\TaxonomyImport;
use App\Model\Accessibility;
use App\Model\AdditionalTaxonomy;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use App\Model\CodeLedger;
use App\Model\Contact;
use App\Model\ContactPhone;
use App\Model\CSV_Source;
use App\Model\Detail;
use App\Model\ImportDataSource;
use App\Model\ImportHistory;
use App\Model\Language;
use App\Model\Location;
use App\Model\LocationAddress;
use App\Model\LocationPhone;
use App\Model\LocationSchedule;
use App\Model\Organization;
use App\Model\OrganizationDetail;
use App\Model\OrganizationPhone;
use App\Model\OrganizationProgram;
use App\Model\Phone;
use App\Model\Program;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceContact;
use App\Model\ServiceDetail;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServicePhone;
use App\Model\ServiceProgram;
use App\Model\ServiceSchedule;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use Spatie\Geocoder\Geocoder;
use ZanySoft\Zip\Zip;
use ZipArchive;

class ImportController extends Controller
{
    public function __construct(MapController $mapController)
    {
        $this->mapController = $mapController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backEnd.import.import');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.import.create');
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
            // 'format' => 'required',
            // 'airtable_api_key' => 'required',
            // 'airtable_base_id' => 'required',
            'auto_sync' => 'required',
            'mode' => 'required',
        ]);
        if ($request->import_type == 'zipfile') {
            $this->validate($request, [
                'zipfile' => 'required'
            ]);
        } elseif ($request->import_type == 'zipfile_api') {
            $this->validate($request, [
                'endpoint' => 'required',
                'key' => 'required',
            ]);
        } elseif ($request->import_type == 'airtable') {
            $this->validate($request, [
                'airtable_api_key' => 'required',
                'airtable_base_id' => 'required',
            ]);
        }
        try {
            $airtable_api_key = '';
            $airtable_base_id = '';
            $zipfile_path = '';
            if ($request->has('airtable_api_key') && $request->has('airtable_base_id') && $request->import_type == 'airtable') {
                $airtable = new Airtable(array(
                    'api_key' => $request->airtable_api_key,
                    'base' => $request->airtable_base_id,
                ));

                $response = Http::get('https://api.airtable.com/v0/' . $request->airtable_base_id . '/organizations?api_key=' . $request->airtable_api_key);
                if ($response->status() != 200) {
                    Session::flash('message', 'Airtable key or base id is invalid. Please enter valid information.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }


                $Airtablekeyinfo = Airtablekeyinfo::create([
                    'api_key' => $request->airtable_api_key,
                    'base_url' => $request->airtable_base_id
                ]);
                $airtable_api_key = $Airtablekeyinfo->id;
                $airtable_base_id = $Airtablekeyinfo->id;
            }
            if ($request->import_type == 'zipfile' && $request->hasFile('zipfile')) {
                $file = $request->file('zipfile');
                $name = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $path = public_path('import_source_file');
                $file->move($path, $name);
                $zipfile_path = $path . $name;
            } else if ($request->import_type == 'zipfile_api') {
                $path = public_path('import_source_file/' . $request->key . '.zip');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, $request->endpoint);
                curl_setopt($ch, CURLOPT_REFERER, $request->endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($ch);
                curl_close($ch);
                if (json_decode($result) == "Failed") {
                    Session::flash('message', 'Error! : api response error please check api endpoint!');
                    Session::flash('status', 'error');
                    return redirect('import');
                }
                // if($result == )
                $ifp = fopen($path, "wb");
                // if ($decode) {
                //     fwrite($ifp, base64_decode($result));
                // } else {
                fwrite($ifp, $result);
                // }

                fclose($ifp);
                // $zipfile_path = $path . $name;
                $zipfile_path = '/import_source_file/' . $request->key . '.zip';
            }
            if ($request->has('auto_sync') && $request->auto_sync == 1) {
                $syncData = ImportDataSource::where('auto_sync', '1')->get();
                if (count($syncData) > 0) {
                    Session::flash('message', 'You can only have one auto-synced Airtable and you already have one.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
            }
            ImportDataSource::create([
                'name' => $request->name,
                'format' => $request->format,
                'airtable_api_key' => $airtable_api_key,
                'airtable_base_id' => $airtable_base_id,
                'auto_sync' => $request->auto_sync,
                'endpoint' => $request->endpoint,
                'key' => $request->key,
                'mode' => $request->mode,
                'source_file' => $zipfile_path,
                'import_type' => $request->import_type,
                'organization_tags' => $request->organization_tags,
                'created_by' => Auth::id(),
            ]);
            Session::flash('message', 'Success! Data Source added successfully.');
            Session::flash('status', 'success');
            return redirect('import');
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! :' . $th->getMessage());
            Session::flash('status', 'error');
            return redirect('import');
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
        $dataSource = ImportDataSource::whereId($id)->first();
        return view('backEnd.import.edit', compact('dataSource'));
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
            // 'format' => 'required',
            // 'airtable_api_key' => 'required',
            // 'airtable_base_id' => 'required',
            'auto_sync' => 'required',
            'mode' => 'required',
        ]);
        if ($request->import_type == 'zipfile') {
            $this->validate($request, [
                'zipfile' => 'required'
            ]);
        } elseif ($request->import_type == 'zipfile_api') {
            $this->validate($request, [
                'endpoint' => 'required',
                'key' => 'required',
            ]);
        } elseif ($request->import_type == 'airtable') {
            $this->validate($request, [
                'airtable_api_key' => 'required',
                'airtable_base_id' => 'required',
            ]);
        }
        try {
            $dataSource = ImportDataSource::whereId($id)->first();
            $airtable_api_key = $dataSource->airtable_api_key;
            $airtable_base_id = $dataSource->airtable_base_id;
            $zipfile_path = $dataSource->source_file;
            if ($request->has('airtable_api_key') && $request->has('airtable_base_id') && $request->import_type == 'airtable') {
                $Airtablekeyinfo = Airtablekeyinfo::where('api_key', $request->airtable_api_key)->first();
                if ($Airtablekeyinfo) {
                    Airtablekeyinfo::where('api_key', $request->airtable_api_key)->update([
                        'api_key' => $request->airtable_api_key,
                        'base_url' => $request->airtable_base_id
                    ]);
                } else {
                    $Airtablekeyinfo = Airtablekeyinfo::create([
                        'api_key' => $request->airtable_api_key,
                        'base_url' => $request->airtable_base_id
                    ]);
                }

                $response = Http::get('https://api.airtable.com/v0/' . $request->airtable_base_id . '/organizations?api_key=' . $request->airtable_api_key);
                if ($response->status() != 200) {
                    Session::flash('message', 'Airtable key or base id is invalid. Please enter valid information.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
                $airtable_api_key = $Airtablekeyinfo->id;
                $airtable_base_id = $Airtablekeyinfo->id;
            }
            if ($request->import_type == 'zipfile' && $request->hasFile('zipfile')) {
                $file = $request->file('zipfile');
                $name = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $path = public_path('import_source_file');
                $file->move($path, $name);
                $zipfile_path = '/import_source_file/' . $name;
            } else if ($request->import_type == 'zipfile_api') {
                $path = public_path('import_source_file/' . $request->key . '.zip');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, $request->endpoint);
                curl_setopt($ch, CURLOPT_REFERER, $request->endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($ch);
                curl_close($ch);
                if (json_decode($result) == "Failed") {
                    Session::flash('message', 'Error! : api response error please check api endpoint!');
                    Session::flash('status', 'error');
                    return redirect('import');
                }
                // if($result == )
                $ifp = fopen($path, "wb");
                // if ($decode) {
                //     fwrite($ifp, base64_decode($result));
                // } else {
                fwrite($ifp, $result);
                // }

                fclose($ifp);
                // $zipfile_path = $path . $name;
                $zipfile_path = '/import_source_file/' . $request->key . '.zip';
            }
            if ($request->has('auto_sync') && $request->auto_sync == 1) {
                $syncData = ImportDataSource::where('auto_sync', '1')->first();

                if ($syncData && $syncData->id != $id) {
                    Session::flash('message', 'You can only have one auto-synced Airtable and you already have one.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
            }
            ImportDataSource::whereId($id)->update([
                'name' => $request->name,
                'format' => $request->format,
                'airtable_api_key' => $airtable_api_key,
                'airtable_base_id' => $airtable_base_id,
                'auto_sync' => $request->auto_sync,
                'endpoint' => $request->endpoint,
                'key' => $request->key,
                'mode' => $request->mode,
                'source_file' => $zipfile_path,
                'import_type' => $request->import_type,
                'sync_hours' => $request->sync_hours ? $request->sync_hours : 1,
                'organization_tags' => $request->organization_tags,
                'created_by' => Auth::id(),
            ]);
            Session::flash('message', 'Success! Data Source updated successfully.');
            Session::flash('status', 'success');
            return redirect('import');
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! :' . $th->getMessage());
            Session::flash('status', 'error');
            return redirect('import');
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
            ImportDataSource::whereId($id)->delete();
            Session::flash('message', 'Success! Data Source deleted successfully.');
            Session::flash('status', 'success');
            return redirect('import');
        } catch (\Throwable $th) {
            Session::flash('message', 'Error! :' . $th->getMessage());
            Session::flash('status', 'error');
            return redirect('import');
        }
    }
    public function getDataSource(Request $request)
    {
        try {
            $ImportDataSource = ImportDataSource::select('*');

            if (!$request->ajax()) {
                return view('backEnd.import.import', compact('ImportDataSource'));
            }
            return DataTables::of($ImportDataSource)
                ->editColumn('auto_sync', function ($row) {
                    // $link = 'Off&nbsp;&nbsp;<input type="checkbox" class="switch" value="1" name="auto_sync" data-id="' . $row->id . '" id="auto_sync" ' . ($row->auto_sync == 1 ? "checked" : "") . ' />&nbsp;&nbsp;On';
                    // return $link;
                    $link = $row->auto_sync == 1 ? "On" : "Off";
                    return $link;
                })

                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("import.importData", $row->id) . '" class="btn btn-info btn-sm " style="margin-right:10px;">Import Now</a>';
                        $links .= '<a href="' . route("import.edit", $row->id) . '" style="margin-right:10px;"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'import';
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
                ->rawColumns(['action', 'auto_sync'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function getImportHistory(Request $request)
    {
        try {
            $importHistory = ImportHistory::select('*');

            if (!$request->ajax()) {
                return view('backEnd.import.import', compact('importHistory'));
            }
            return DataTables::of($importHistory)
                ->editColumn('created_at', function ($row) {

                    return date('d-m-Y H:i:s A', strtotime($row->created_at));
                })
                ->editColumn('auto_sync', function ($row) {
                    $link = $row->auto_sync == 1 ? "Auto" : "Manual";
                    return $link;
                })
                ->editColumn('import_type', function ($row) {
                    $link = $row->import_type == 'airtable' ? "Airtable 2.2" : "Zipfile";
                    return $link;
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
                ->rawColumns(['auto_sync'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function importData($id)
    {
        try {
            $importData = ImportDataSource::whereId($id)->first();
            if ($importData && $importData->mode == 'replace') {
                Program::truncate();
                ServiceProgram::truncate();
                OrganizationProgram::truncate();
                TaxonomyType::truncate();
                TaxonomyTerm::truncate();
                AdditionalTaxonomy::truncate();
                Address::truncate();
                Contact::truncate();
                Detail::truncate();
                Location::truncate();
                LocationAddress::truncate();
                LocationPhone::truncate();
                LocationSchedule::truncate();
                Organization::truncate();
                OrganizationDetail::truncate();
                Phone::truncate();
                OrganizationPhone::truncate();
                ContactPhone::truncate();
                ServicePhone::truncate();
                Schedule::truncate();
                Service::truncate();
                ServiceLocation::truncate();
                ServiceAddress::truncate();
                ServiceDetail::truncate();
                ServiceOrganization::truncate();
                ServiceContact::truncate();
                ServiceTaxonomy::truncate();
                ServiceSchedule::truncate();
                Taxonomy::truncate();
                Audit::truncate();
                CodeLedger::truncate();
            }
            if ($importData && $importData->import_type == 'airtable') {
                $organization_tag = $importData->organization_tags;
                $airtableKeyInfo = Airtablekeyinfo::whereId($importData->airtable_api_key)->first();
                $response = Http::get('https://api.airtable.com/v0/' . $airtableKeyInfo->base_url . '/organizations?api_key=' . $airtableKeyInfo->api_key);
                if ($response->status() != 200) {
                    Session::flash('message', 'Airtable key or base id is invalid. Please enter valid information and try again.');
                    Session::flash('status', 'error');
                    return redirect()->back()->withInput();
                }
                app(\App\Http\Controllers\frontEnd\ServiceController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\AddressController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\ContactController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\DetailController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\LocationController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\OrganizationController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url, $organization_tag);
                app(\App\Http\Controllers\frontEnd\PhoneController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\ScheduleController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\frontEnd\TaxonomyController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\backend\ProgramController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                app(\App\Http\Controllers\backend\TaxonomyTypeController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                $importData->last_imports = Carbon::now();
                $importData->save();

                $importHistory = new ImportHistory();
                $importHistory->source_name = $importData->format;
                $importHistory->import_type = $importData->import_type;
                $importHistory->auto_sync = $importData->auto_sync;
                $importHistory->status = 'Completed';
                $importHistory->sync_by = Auth::id();
                $importHistory->save();
            } else if ($importData && $importData->import_type == 'zipfile' || $importData && $importData->import_type == 'zipfile_api') {
                $this->zip($importData);

                $importData->last_imports = Carbon::now();
                $importData->save();
                $importHistory = new ImportHistory();
                $importHistory->source_name = $importData->format;
                $importHistory->import_type = $importData->import_type;
                $importHistory->auto_sync = $importData->auto_sync;
                $importHistory->status = 'Completed';
                $importHistory->sync_by = Auth::id();
                $importHistory->save();
            }
            $this->apply_geocode();
            Session::flash('message', 'Success! Data Source Imported successfully.');
            Session::flash('status', 'success');
            return redirect('import');
        } catch (\Throwable $th) {
            Log::error('Error from importData : ' . $th);
            $importHistory = new ImportHistory();
            $importHistory->status = 'Error';
            $importHistory->error_message = $th->getMessage();
            $importHistory->sync_by = Auth::id();
            $importHistory->save();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('import');
        }
    }
    public function changeAutoImport(Request $request)
    {
        try {
            $id = $request->id;
            $syncData = ImportDataSource::where('auto_sync', '1')->get();

            if ($request->checked == 'true') {
                // ImportDataSource::where('auto_sync', '1')->update([
                //     'auto_sync' => '0'
                // ]);
                // DB::commit();
                if (count($syncData) > 0) {
                    return response()->json([
                        'message' => 'You can only have one auto-synced Airtable and you already have one.',
                        'success' => false
                    ], 500);
                }
                $importData = ImportDataSource::whereId($id)->first();
                $importData->auto_sync = '1';
                $importData->save();
                return response()->json([
                    'message' => 'Auto sync changed successfully!',
                    'success' => true
                ], 200);
            } else {
                $importData = ImportDataSource::whereId($id)->first();
                $importData->auto_sync = '0';
                $importData->save();
                return response()->json([
                    'message' => 'Auto sync changed successfully!',
                    'success' => true
                ], 200);
            }
            // } else {
            //     return response()->json([
            //         'message' => 'You can only have one auto-synced Airtable and you already have one.',
            //         'success' => false
            //     ], 500);
            // }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
    public function zip($importData)
    {
        try {
            // $extension = $request->file('file_zip')->getClientOriginalExtension();

            // if ($extension != 'zip') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This File is not zip file.',
            //     );
            //     return $response;
            // }
            // $path = $request->file('file_zip')->getRealPath();
            $zip = new ZipArchive();
            $zip->open(public_path($importData->source_file));
            $zip->extractTo(public_path('HSDS/data/'));
            $zip->close();
            // $file = file_get_contents(public_path($importData->source_file));
            // $zip = Zip::open($file);

            // Zip::make($path)->extractTo('HSDS');
            // $zip->extract(public_path('HSDS'));

            // $filename =  $request->file('file_zip')->getClientOriginalName();
            // $request->file('file_zip')->move(public_path('/zip/'), $filename);

            $path = public_path('/HSDS/data/services.csv');


            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'services.csv does not exist.',
                );
                return $response;
            }


            //
            Excel::import(new Services, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services')->first();
            $csv_source->records = Service::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //locations.csv
            $path = public_path('/HSDS/data/locations.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'locations.csv does not exist.',
                );
                return $response;
            }

            // $data = Excel::load($path)->get();
            //
            Excel::import(new LocationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Locations')->first();
            $csv_source->records = Location::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //organizations.csv
            $path = public_path('/HSDS/data/organizations.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'organizations.csv does not exist.',
                );
                return $response;
            }

            //
            Excel::import(new OrganizationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Organizations')->first();
            $csv_source->records = Organization::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //contacts.csv
            $path = public_path('/HSDS/data/contacts.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'contacts.csv does not exist.',
                );
                return $response;
            }
            //
            Excel::import(new ContactImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Contacts')->first();
            $csv_source->records = Contact::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //phones.csv
            $path = public_path('/HSDS/data/phones.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'phones.csv does not exist.',
                );
                return $response;
            }
            //
            Excel::import(new PhoneImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Phones')->first();
            $csv_source->records = Phone::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //physical_addresses.csv
            $path = public_path('/HSDS/data/physical_addresses.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'physical_addresses.csv does not exist.',
                );
                return $response;
            }
            //
            Excel::import(new AddressImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Address')->first();
            $csv_source->records = Address::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //languages.csv
            $path = public_path('/HSDS/data/languages.csv');
            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'languages.csv does not exist.',
                );
                return $response;
            }
            //
            Excel::import(new LanguageImport, $path);
            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Languages')->first();
            $csv_source->records = Language::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //

            //taxonomy.csv
            $path = public_path('/HSDS/data/taxonomy_terms.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'taxonomy_terms.csv does not exist.',
                );
                return $response;
            }
            //
            Excel::import(new TaxonomyImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
            $csv_source->records = Taxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //services_taxonomy.csv
            $path = public_path('/HSDS/data/service_attributes.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'service_attributes.csv does not exist.',
                );
                return $response;
            }

            // ServiceTaxonomy::truncate();
            //
            Excel::import(new ServiceTaxonomyImport, $path);

            $date = date("Y/m/d H:i:s");
            $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
            $csv_source->records = ServiceTaxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //services_at_location.csv
            $path = public_path('/HSDS/data/services_at_location.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'services_at_location.csv does not exist.',
                );
                return $response;
            }

            // ServiceLocation::truncate();
            //
            Excel::import(new ServiceLocationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
            $csv_source->records = ServiceLocation::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //accessibility_for_disabilities.csv
            $path = public_path('/HSDS/data/accessibility_for_disabilities.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'accessibility_for_disabilities.csv does not exist.',
                );
                return $response;
            }
            // Accessibility::truncate();
            //
            Excel::import(new AccessibilityImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
            $csv_source->records = Accessibility::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            //
            //regular_schedules.csv
            $path = public_path('/HSDS/data/schedules.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'schedules.csv does not exist.',
                );
                return $response;
            }

            Excel::import(new ScheduleImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Regular_schedules')->first();
            $csv_source->records = Schedule::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            rename(public_path('/HSDS/data/services.csv'), public_path('/csv/services.csv'));
            rename(public_path('/HSDS/data/locations.csv'), public_path('/csv/locations.csv'));
            rename(public_path('/HSDS/data/organizations.csv'), public_path('/csv/organizations.csv'));
            rename(public_path('/HSDS/data/contacts.csv'), public_path('/csv/contacts.csv'));
            rename(public_path('/HSDS/data/phones.csv'), public_path('/csv/phones.csv'));
            rename(public_path('/HSDS/data/physical_addresses.csv'), public_path('/csv/physical_addresses.csv'));
            rename(public_path('/HSDS/data/languages.csv'), public_path('/csv/languages.csv'));
            rename(public_path('/HSDS/data/taxonomy_terms.csv'), public_path('/csv/taxonomy_terms.csv'));
            rename(public_path('/HSDS/data/service_attributes.csv'), public_path('/csv/service_attributes.csv'));
            rename(public_path('/HSDS/data/services_at_location.csv'), public_path('/csv/services_at_location.csv'));
            rename(public_path('/HSDS/data/accessibility_for_disabilities.csv'), public_path('/csv/accessibility_for_disabilities.csv'));
            rename(public_path('/HSDS/data/schedules.csv'), public_path('/csv/schedules.csv'));
            return "import completed";
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in import controller import : ' . $th);
        }
    }
    public function apply_geocode()
    {
        try {
            $ungeocoded_location_info_list = Location::whereNull('location_latitude')->get();
            $badgeocoded_location_info_list = Location::where('location_latitude', '=', '')->get();
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($ungeocoded_location_info_list) {
                foreach ($ungeocoded_location_info_list as $key => $location_info) {

                    if ($location_info->location_name) {
                        $address_info = $location_info->location_name;
                        // $response = $geocoder->getCoordinatesForAddress('30-61 87th Street, Queens, NY, 11369');
                        $response = $geocoder->getCoordinatesForAddress($address_info);
                        // if (($response['lat'] > 40.5) && ($response['lat'] < 42.0)) {
                        //     $latitude = $response['lat'];
                        //     $longitude = $response['lng'];
                        // } else {
                        //     $latitude = '';
                        //     $longitude = '';
                        // }

                        $latitude = $response['lat'];
                        $longitude = $response['lng'];

                        $location_info->location_latitude = $latitude;
                        $location_info->location_longitude = $longitude;
                        $location_info->save();
                    }
                }
            }

            if ($badgeocoded_location_info_list) {
                foreach ($badgeocoded_location_info_list as $key => $location_info) {
                    if ($location_info->location_name) {
                        $address_info = $location_info->location_name;
                        // $response = $geocoder->getCoordinatesForAddress('30-61 87th Street, Queens, NY, 11369');
                        $response = $geocoder->getCoordinatesForAddress($address_info);
                        $latitude = $response['lat'];
                        $longitude = $response['lng'];
                        $location_info->location_latitude = $latitude;
                        $location_info->location_longitude = $longitude;
                        $location_info->save();
                    }
                }
            }

            return;
        } catch (\Throwable $th) {
            Log::error('Error in applying geocode in import : ' . $th);
        }
    }
}
