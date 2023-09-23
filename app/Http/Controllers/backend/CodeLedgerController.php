<?php

namespace App\Http\Controllers\backend;

use App\Exports\CodeLeadgerExport;
use App\Http\Controllers\Controller;
use App\Model\Code;
use App\Model\CodeCategory;
use App\Model\CodeLedger;
use App\Model\Organization;
use App\Model\Service;
use App\Model\ServiceCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
        $category = CodeCategory::pluck('name', 'id')->unique();

        if (!$request->ajax()) {
            return view('backEnd.tables.code_ledger', compact('services', 'organizations', 'resources', 'category'));
        }
        $code_ledgers = CodeLedger::withTrashed();
        return DataTables::of($code_ledgers)
            ->addColumn('action', function ($row) {
                // $links = '';
                // if ($row) {
                //     // $links .= '<a href="' . route("languages.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                //     $id = $row->id;
                //     $route = 'code_ledgers';
                //     $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                // }
                // return $links;
                if ($row->deleted_at) {
                    return '<a class="panel-link" style="background-color: red !important; color:#fff !important;">Removed</a>';
                } else if ($row->updated_at->gt($row->created_at)) {
                    return '<a class="panel-link" style="background-color: #0014ff !important; color:#fff !important;">Updated</a>';
                } elseif ($row->updated_at->eq($row->created_at)) {
                    return '<a class="panel-link" style="background-color: green !important; color:#fff !important;">Added</a>';
                } else {
                    return '';
                }
            })
            ->addColumn('service', function ($row) {
                return $row->service ? $row->service->service_name : '';
            })
            ->addColumn('code_id', function ($row) {
                return $row->code_data ? $row->code_data->code_id : '';
            })
            ->addColumn('resource_element', function ($row) {
                return $row->code_data ? $row->code_data->resource_element : '';
            })
            ->addColumn('procedure_grouping', function ($row) {
                // $service_grouping = $row->service ? unserialize($row->service->procedure_grouping) : [];
                // $procedure_grouping = '';
                // if (is_array($service_grouping) && count($service_grouping) > 0) {
                //     $category = $row->code_data ?  $row->code_data->category : '';
                //     $category = str_replace(' ', '_', str_replace('/', '_', str_replace(',', '_', $category)));
                //     $results = array_filter($service_grouping, function ($value) use ($category) {
                //         return strpos($value, $category) !== false;
                //     });
                //     $results = array_values($results);
                //     foreach ($results as $key => $value) {
                //         if (strpos($value, $category) !== false) {
                //             $value = str_replace('_', ' ', str_replace($category . '|', '', $value));
                //             $procedure_grouping .= ($key == 0 ? $value : ', ' . $value);
                //         }
                //     }
                // }
                // return $procedure_grouping;
                return $row->code_data ? $row->code_data->grouping : '';
            })
            ->editColumn('created_at', function ($row) {
                return date('d-m-Y H:i:s', strtotime($row->created_at));
            })
            ->addColumn('organization', function ($row) {
                return $row->organization ?  $row->organization->organization_name : '';
            })
            ->addColumn('category', function ($row) {
                return $row->code_data && $row->code_data->get_category ?  $row->code_data->get_category->name : '';
            })
            ->addColumn('code_type', function ($row) {
                return $row->get_code_system ?  $row->get_code_system->name : '';
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
            }, true)
            ->rawColumns(['action'])
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
        try {
            $code_ledger = CodeLedger::whereId($id)->first();
            $code_id = $code_ledger->SDOH_code;
            $service = Service::where('service_recordid', $code_ledger->service_recordid)->first();
            if ($service) {
                $service_codes = $service->SDOH_code ? explode(',', $service->SDOH_code) : [];
                if (in_array($code_id, $service_codes)) {
                    $key = array_search($code_id, $service_codes);
                    array_splice($service_codes, $key, 1);
                }
                $service->SDOH_code = implode(',', $service_codes);
                $service->save();
            }
            $code_ledger->delete();
            Session::flash('message', 'Code Ledger deleted successfully');
            Session::flash('status', 'success');
            return redirect('code_ledgers');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('code_ledgers');
        }
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
            Log::error('Error in code ledger export : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
}
