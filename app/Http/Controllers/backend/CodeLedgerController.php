<?php

namespace App\Http\Controllers\backend;

use App\Exports\CodeLeadgerExport;
use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\CodeLedger;
use App\Model\Organization;
use App\Model\Service;
use App\Model\ServiceCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class CodeLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Service::pluck('service_name', 'service_recordid')->unique();
        $organizations = Organization::pluck('organization_name', 'organization_recordid')->unique();
        $resources = Code::pluck('resource', 'resource')->unique();
        $category = Code::pluck('category', 'category')->unique();

        if (!$request->ajax()) {
            return view('backEnd.tables.code_ledger', compact('services', 'organizations', 'resources', 'category'));
        }
        $code_ledgers = CodeLedger::select('*');
        return DataTables::of($code_ledgers)
            ->addColumn('service', function ($row) {
                return $row->service ? $row->service->service_name : '';
            })
            ->addColumn('organization', function ($row) {
                return $row->organization ?  $row->organization->organization_name : '';
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

                    if (isset($extraData['services']) && $extraData['services'] != null) {
                        $query = $query->where('service_recordid', $extraData['services']);
                    }
                    if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                        $query = $query->where('organization_recordid', $extraData['organizations']);
                    }
                    if (isset($extraData['resources']) && $extraData['resources'] != null) {
                        $query = $query->where('resource', $extraData['resources']);
                    }
                    if (isset($extraData['category']) && $extraData['category'] != null) {
                        $code_ids = Code::where('category', $extraData['category'])->pluck('id')->toArray();
                        $query = $query->whereIn('SDOH_code', $code_ids);
                    }
                }
                return $query;
            })
            // ->rawColumns(['action', 'services'])
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
    public function code_leaders_export(Request $request)
    {
        try {
            // return Excel::download(new CodeExport, 'codes.csv');
            Excel::store(new CodeLeadgerExport($request), 'code_ledgers.csv', 'csv');
            return response()->json([
                'path' => url('/csv/code_ledgers.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in code ledger export : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
}
