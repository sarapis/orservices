<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\FeeOption;
use App\Model\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class FeesOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('backEnd.fees_option.index');
        }
        $fees_options = FeeOption::select('*');
        return DataTables::of($fees_options)
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("fees_options.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'fees_options';
                    $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                }
                return $links;
            })
            ->editColumn('services', function ($row) {
                $name = '';
                if ($row->services_fees && count($row->services_fees) > 0) {
                    foreach ($row->services_fees as $key => $value) {
                        $name .= $value->service_name;
                    }
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
        return view('backEnd.fees_option.create', compact('services'));
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
            'fees' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $fees_option = FeeOption::create([
                'fees' => $request->fees,
                'services' => $request->services,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            if ($request->services)
                $fees_option->services_fees()->sync([$request->services]);
            Session::flash('message', 'Fees Added Successfully!');
            Session::flash('status', 'success');
            return redirect('fees_options');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from create fees : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('fees_options');
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
        $fees_option = FeeOption::whereId($id)->first();
        $services = Service::pluck('service_name', 'service_recordid');
        return view('backEnd.fees_option.edit', compact('services', 'fees_option'));
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
            'fees' => 'required'
        ]);

        try {
            DB::beginTransaction();
            FeeOption::whereId($id)->update([
                'fees' => $request->fees,
                'services' => $request->services,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            $fees_option = FeeOption::whereId($id)->first();
            if ($request->services)
                $fees_option->services_fees()->sync([$request->services]);
            Session::flash('message', 'Fee Updated Successfully!');
            Session::flash('status', 'success');
            return redirect('fees_options');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from edit fees : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('fees_options');
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
            FeeOption::whereId($id)->delete();
            DB::commit();
            Session::flash('message', 'Fees Deleted Successfully!');
            Session::flash('status', 'success');
            return redirect('fees_options');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from delete Fees : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('fees_options');
        }
    }
}
