<?php

namespace App\Http\Controllers\backend;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Organization;
use App\Model\OrganizationProgram;
use App\Model\Program;
use App\Model\Service;
use App\Model\ServiceProgram;
use App\Model\Source_data;
use App\Services\Stringtoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller
{
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

            Program::truncate();
            ServiceProgram::truncate();
            OrganizationProgram::truncate();

            // $airtable = new Airtable(array(
            //     'api_key'   => env('AIRTABLE_API_KEY'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'api_key' => $api_key,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('programs');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {

                    $program = new Program();
                    $strtointclass = new Stringtoint();

                    $program->program_recordid = $strtointclass->string_to_int($record['id']);

                    $program->alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                    $program->name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                    if (isset($record['fields']['organizations'])) {
                        $i = 0;
                        $orgIds = [];
                        foreach ($record['fields']['organizations'] as $value) {

                            $programorganizations = $strtointclass->string_to_int($value);
                            $orgIds[] = $programorganizations;
                            if ($i != 0) {
                                $program->organizations = $program->organizations . ',' . $programorganizations;
                            } else {
                                $program->organizations = $programorganizations;
                            }

                            $i++;
                        }
                        $program->organization()->sync($orgIds);
                    }

                    if (isset($record['fields']['services'])) {
                        $i = 0;
                        $serviceIds = [];
                        foreach ($record['fields']['services'] as $value) {
                            $programservice = $strtointclass->string_to_int($value);
                            $serviceIds[] = $programservice;
                            if ($i != 0) {
                                $program->services = $program->services . ',' . $programservice;
                            } else {
                                $program->services = $programservice;
                            }
                            $i++;
                        }
                        $program->service()->sync($serviceIds);
                    }

                    $program->save();
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Programs')->first();
            $airtable->records = Program::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            \Log::error('Error in Program:'.$th->getMessage());
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
    public function index(Request $request)
    {
        $programs = Program::select('*');
        $source_data = Source_data::find(1);

        if (!$request->ajax()) {
            return view('backEnd.programs.index', compact('programs', 'source_data'));
        }
        return DataTables::of($programs)
            ->editColumn('organizations', function ($row) {
                $organizations = $row->organization;
                $links = '';
                foreach ($organizations as $key => $value) {
                    $links .= '<a href="/organizations/' . $value->organization_recordid . '" target="_blank" style="color:blue;">' . $value->organization_name . '</a>';
                }
                return $links;
            })
            ->editColumn('services', function ($row) {
                $services = $row->service;
                $links = '';
                foreach ($services as $key => $value) {
                    $links .= '<a href="/services/' . $value->service_recordid . '" target="_blank"><span class="badge badge-light">' . $value->service_name . '</span></a>';
                }
                return $links;
            })
            ->editColumn('alternate_name', function ($row) {
                return $row->alternate_name ? Str::limit($row->alternate_name, 50, '...') : '';
            })
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("programs.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'programs';
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
            ->rawColumns(['action', 'organizations', 'services'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        $services = Service::pluck('service_name', 'service_recordid');
        return view('backEnd.programs.create', compact('organizations', 'services'));
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
            'alternate_name' => 'required'
        ]);
        try {
            $program = new Program();
            $program->name = $request->name;
            $program->program_recordid = Program::max('program_recordid') + 1;
            $program->alternate_name = $request->alternate_name;
            if ($request->organizations) {
                $program->organizations = implode(',', $request->organizations);
                $program->organization()->sync($request->organizations);
            }
            if ($request->services) {
                $program->services = implode(',', $request->services);
                $program->service()->sync($request->services);
            }
            $program->save();

            Session::flash('message', 'Program created successfully!');
            Session::flash('status', 'success');
            return redirect('/programs');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
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
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        $services = Service::pluck('service_name', 'service_recordid');
        $program = Program::whereId($id)->first();

        $organization_recordids = $program->organization->pluck('organization_recordid')->toArray();
        $service_recordids = $program->service->pluck('service_recordid')->toArray();

        return view('backEnd.programs.edit', compact('organizations', 'services', 'program', 'organization_recordids', 'service_recordids'));
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
            'alternate_name' => 'required'
        ]);
        try {
            $program = Program::whereId($id)->first();
            if ($program) {
                $program->name = $request->name;
                $program->alternate_name = $request->alternate_name;
                if ($request->organizations) {
                    $program->organizations = implode(',', $request->organizations);
                    $program->organization()->sync($request->organizations);
                }
                if ($request->services) {
                    $program->services = implode(',', $request->services);
                    $program->service()->sync($request->services);
                }
                $program->save();

                Session::flash('message', 'Program created successfully!');
                Session::flash('status', 'success');
                return redirect('/programs');
            } else {
                Session::flash('message', 'Record not found!');
                Session::flash('status', 'warning');
                return redirect('/programs');
            }
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
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
            $program = Program::whereId($id)->first();
            if ($program) {
                ServiceProgram::where('program_recordid', $program->program_recordid)->delete();
                OrganizationProgram::where('program_recordid', $program->program_recordid)->delete();
                Program::whereId($id)->delete();
                Session::flash('message', 'Program deleted successfully!');
                Session::flash('status', 'success');
            }
            return redirect('/programs');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back()->withInput();
        }
    }
}
