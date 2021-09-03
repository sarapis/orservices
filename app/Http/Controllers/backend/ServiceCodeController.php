<?php

namespace App\Http\Controllers\backend;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\Organization;
use App\Model\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ServiceCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = Code::where('resource', 'Condition')->pluck('description', 'id');
        $goals = Code::where('resource', 'Goal')->pluck('description', 'id');
        $activities = Code::where('resource', 'Procedure')->pluck('description', 'id');
        $organizations = Organization::pluck('organization_name', 'organization_recordid');
        if (!$request->ajax()) {
            return view('backEnd.tables.tb_services', compact('conditions', 'goals', 'activities', 'organizations'));
        }
        $services = Service::with('organizations', 'codes')->orderBy('service_recordid', 'asc');
        return DataTables::of($services)
            ->addColumn('codes', function ($row) {
                $links = '';
                if ($row->codes && count($row->codes) > 0) {
                    $codes = $row->codes;
                    foreach ($codes as $key => $value) {
                        $links .= $value->resource . ' - ' . $value->description . ' - ' . $value->rating . '<br>';
                    }
                }
                return $links;
            })
            ->editColumn('service_organization', function ($row) {

                $organizations = $row->organizations;
                return $organizations ? $organizations->organization_name : '';
                // if($organizations)
            })
            // ->addColumn('services', function ($row) {
            //     $name = '';
            //     if (count($row->services) > 0) {
            //         $services = $row->services()->with('organizations')->get();
            //         $name .= '<ul>';
            //         foreach ($services as $key => $value) {
            //             $name .= '<li>' . $value->service_name . ($value->organizations ? '- ' . $value->organizations->organization_name . ($value->organizations->organization_website_rating ? ' - ' . $value->organizations->organization_website_rating : '') : '') . '</li>';
            //         }
            //         return $name;
            //     }
            // })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {
                    $code_ids = [];
                    $service_ids = [];
                    if (isset($extraData['conditions']) && $extraData['conditions'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['conditions']);
                    }
                    if (isset($extraData['goals']) && $extraData['goals'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['goals']);
                    }
                    if (isset($extraData['activities']) && $extraData['activities'] != null) {
                        $code_ids = array_merge($code_ids, $extraData['activities']);
                    }
                    if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                        $query->whereIn('service_organization', $extraData['organizations']);
                    }
                    if (count($code_ids) > 0) {
                        $codes = Code::whereIn('id', $code_ids)->get();
                        foreach ($codes as $key => $value) {
                            $code_ledger = $value->code_ledger;
                            if ($code_ledger) {
                                $service_ids[] = $code_ledger->service_recordid;
                            }
                            // $service_ids = array_merge($service_ids, $value->services->pluck('id')->toArray());
                        }
                        $query->whereIn('service_recordid', $service_ids);
                    }
                }
                return $query;
            }, true)
            ->rawColumns(['codes'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
    public function tb_services_export(Request $request)
    {
        try {
            // return Excel::download(new CodeExport, 'codes.csv');
            Excel::store(new ServiceExport($request), 'services.csv', 'csv');
            return response()->json([
                'path' => url('/csv/services.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in service code export : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
}
