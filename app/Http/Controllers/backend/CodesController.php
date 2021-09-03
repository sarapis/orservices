<?php

namespace App\Http\Controllers\backend;

use App\Exports\CodeExport;
use App\Http\Controllers\Controller;
use App\Imports\CodeImport;
use App\Model\Code;
use App\Model\CodeLedger;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class CodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Code::pluck('category', 'category')->unique();
        $resource = Code::pluck('resource', 'resource')->unique();
        $resource_element = Code::pluck('resource_element', 'resource_element')->unique();
        $code_system = Code::pluck('code_system', 'code_system')->unique();
        if (!$request->ajax()) {
            return view('backEnd.codes.index', compact('category', 'resource', 'resource_element', 'code_system'));
        }
        $codes = Code::select('*');
        return DataTables::of($codes)
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("codes.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'codes';
                    $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                }
                return $links;
            })
            ->addColumn('services', function ($row) {
                $name = '';
                if ($row->code_ledger) {
                    $name = ($row->code_ledger->service ? $row->code_ledger->service->service_name : '') . ' - ' . ($row->code_ledger->organization ? $row->code_ledger->organization->organization_name : '') . ' - ' . $row->code_ledger->rating;
                }
                return $name;
            })
            ->filter(function ($query) use ($request) {
                $extraData = $request->get('extraData');

                if ($extraData) {

                    if (isset($extraData['category']) && $extraData['category'] != null) {
                        $query = $query->where('category', $extraData['category']);
                    }
                    if (isset($extraData['resource']) && $extraData['resource'] != null) {
                        $query = $query->where('resource', $extraData['resource']);
                    }
                    if (isset($extraData['resource_element']) && $extraData['resource_element'] != null) {
                        $query = $query->where('resource_element', $extraData['resource_element']);
                    }
                    if (isset($extraData['code_system']) && $extraData['code_system'] != null) {
                        $query = $query->where('code_system', $extraData['code_system']);
                    }
                    if (isset($extraData['code_with_service']) && $extraData['code_with_service'] != null) {
                        if ($extraData['code_with_service'] == "true") {
                            $code_ids = CodeLedger::whereNotNull('service_recordid')->pluck('SDOH_code')->toArray();
                            $query = $query->whereIn('id', $code_ids);
                        }
                    }
                }
                return $query;
            }, true)
            ->rawColumns(['action', 'services'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.codes.create');
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
            'code' => 'required'
        ]);
        try {
            DB::beginTransaction();
            Code::create([
                'code' => $request->code,
                'code_system' => $request->code_system,
                'resource' => $request->resource,
                'resource_element' => $request->resource_element,
                'category' => $request->category,
                'description' => $request->description,
                'is_panel_code' => $request->is_panel_code,
                'is_multiselect' => $request->is_multiselect,
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('message', 'Code created successfully');
            Session::flash('status', 'success');
            return redirect('codes');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('codes');
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
        $code = Code::whereId($id)->first();
        return view('backEnd.codes.edit', compact('code'));
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
            'code' => 'required'
        ]);
        try {
            DB::beginTransaction();
            Code::whereId($id)->update([
                'code' => $request->code,
                'code_system' => $request->code_system,
                'resource' => $request->resource,
                'resource_element' => $request->resource_element,
                'category' => $request->category,
                'description' => $request->description,
                'is_panel_code' => $request->is_panel_code,
                'is_multiselect' => $request->is_multiselect,
                'updated_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('message', 'Code updated successfully');
            Session::flash('status', 'success');
            return redirect('codes');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('codes');
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
            DB::beginTransaction();
            $code = Code::whereId($id)->first();
            if ($code) {
                CodeLedger::where('SDOH_code', $id)->delete();
                Code::whereId($id)->delete();
                DB::commit();
                Session::flash('message', 'Code deleted successfully');
                Session::flash('status', 'success');
                return redirect('codes');
            }
            Session::flash('message', 'Code Not Found!');
            Session::flash('status', 'success');
            return redirect('codes');
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('codes');
        }
    }
    public function codes_import()
    {
        return view('backEnd.codes.import');
    }
    public function ImportCodesExcel(Request $request)
    {
        $this->validate($request, [
            'import_codes' => 'required'
        ]);
        try {
            Code::truncate();
            Excel::import(new CodeImport, $request->file('import_codes'));
            Session::flash('message', 'Codes imported successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function codes_export(Request $request)
    {
        try {
            // return Excel::download(new CodeExport, 'codes.csv');
            Excel::store(new CodeExport($request), 'codes.csv', 'csv');
            return response()->json([
                'path' => url('/csv/codes.csv'),
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in codes : ' . $th);
            return response()->json([
                'path' => '',
                'success' => false
            ], 500);
        }
    }
}
