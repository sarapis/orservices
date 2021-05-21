<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\OrganizationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrganizationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $layout = Layout::first();
            $organization_status = OrganizationStatus::get();

            if (!$request->ajax()) {
                return view('backEnd.organization_status.index', compact('organization_status', 'layout'));
            }
            $organization_status = OrganizationStatus::select('*');
            return DataTables::of($organization_status)
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("organization_status.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'organization_status';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in OrgStatus : ' . $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.organization_status.create');
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
            'status' => 'required',
            'order' => 'required|unique:organization_statuses,order',
        ]);
        try {
            DB::beginTransaction();
            OrganizationStatus::create([
                'status' => $request->status,
                'order' => $request->order,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Status added successfully!');
            Session::flash('status', 'success');
            return redirect('organization_status');
        } catch (\Throwable $th) {
            DB::rollback();
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
        $organization_status = OrganizationStatus::whereId($id)->first();
        return view('backEnd.organization_status.edit', compact('organization_status'));
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
            'status' => 'required',
            'order' => 'required',
        ]);
        try {
            DB::beginTransaction();
            OrganizationStatus::whereId($id)->update([
                'status' => $request->status,
                'order' => $request->order,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Status updated successfully!');
            Session::flash('status', 'success');
            return redirect('organization_status');
        } catch (\Throwable $th) {
            DB::rollback();
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
            DB::beginTransaction();
            OrganizationStatus::whereId($id)->delete();
            DB::commit();
            return redirect()->to('organization_status')->with('success', 'Status deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->to('organization_status')->with('error', $th->getMessage());
        }
    }
}
