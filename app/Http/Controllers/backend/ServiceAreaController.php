<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Service;
use App\Model\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('backEnd.service_area.index');
        }
        $service_areas = ServiceArea::select('*');
        return DataTables::of($service_areas)
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("service_areas.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'service_areas';
                    $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                }
                return $links;
            })
            ->editColumn('services', function ($row) {
                $name = '';
                if ($row->service) {
                    $name = $row->service->service_name;
                }
                return $name;
            })
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
        $services = Service::pluck('service_name', 'service_recordid');
        return view('backEnd.service_area.create', compact('services'));
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
            'name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            ServiceArea::create([
                'name' => $request->name,
                'services' => $request->services,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Area Added Successfully!');
            Session::flash('status', 'success');
            return redirect('service_areas');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from create service area : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_areas');
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
        $service_area = ServiceArea::whereId($id)->first();
        $services = Service::pluck('service_name', 'service_recordid');
        return view('backEnd.service_area.edit', compact('services', 'service_area'));
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
            'name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            ServiceArea::whereId($id)->update([
                'name' => $request->name,
                'services' => $request->services,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Service Area Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('service_areas');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from edit service area : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_areas');
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
            ServiceArea::whereId($id)->delete();
            DB::commit();
            Session::flash('message', 'Service Area Deleted Successfully!');
            Session::flash('status', 'success');
            return redirect('service_areas');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from delete service area : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('service_areas');
        }
    }
}
