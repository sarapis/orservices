<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Service;
use App\Model\ServiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ServiceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_statuses = ServiceStatus::get();
        $layout = Layout::find(1);
        return view('backEnd.service_status.index', compact('service_statuses', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.service_status.create');
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
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();
            ServiceStatus::create([
                'status' => $request->status,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Status Added Successfully!');
            Session::flash('status', 'success');
            return redirect('service_status');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_status');
            //throw $th;
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
        $service_status = ServiceStatus::whereId($id)->first();

        return view('backEnd.service_status.edit', compact('service_status'));
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
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();
            ServiceStatus::whereId($id)->update([
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Status Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('service_status');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_status');
            //throw $th;
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
        ServiceStatus::whereId($id)->delete();
        Session::flash('message', 'Service Status Deleted Successfully!');
        Session::flash('status', 'success');
        return redirect('service_status');
    }
    public function save_default_service_status(Request $request)
    {
        try {
            DB::beginTransaction();
            Layout::whereId(1)->update([
                'default_service_status' => $request->default_service_status,
            ]);
            DB::commit();
            Service::whereNull('service_status')->update([
                'service_status' => $request->default_service_status
            ]);
            DB::commit();
            Session::flash('message', 'Default Service Status Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('service_status');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_status');
            //throw $th;
        }
    }
}
