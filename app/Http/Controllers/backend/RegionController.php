<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('backEnd.regions.index');
        }
        $regions = Region::select('*');
        return DataTables::of($regions)
            ->addColumn('action', function ($row) {
                $links = '';
                if ($row) {
                    $links .= '<a href="' . route("regions.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                    $id = $row->id;
                    $route = 'regions';
                    $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                }
                return $links;
            })
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
        return view('backEnd.regions.create');
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
            'region' => 'required'
        ]);

        try {
            DB::beginTransaction();
            Region::create([
                'region' => $request->region,
                'created_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Region Added Successfully!');
            Session::flash('status', 'success');
            return redirect('regions');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from create Region : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('regions');
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
        $region = Region::whereId($id)->first();
        return view('backEnd.regions.edit', compact('region'));
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
            'region' => 'required'
        ]);

        try {
            DB::beginTransaction();
            Region::whereId($id)->update([
                'region' => $request->region,
                'updated_by' => Auth::id()
            ]);
            DB::commit();
            Session::flash('message', 'Region updated Successfully!');
            Session::flash('status', 'success');
            return redirect('regions');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from update Region : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('regions');
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
            Region::whereId($id)->delete();
            DB::commit();
            Session::flash('message', 'Region Deleted Successfully!');
            Session::flash('status', 'success');
            return redirect('regions');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("Error from delete Region : " . $th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('regions');
        }
    }
}
