<?php

namespace App\Http\Controllers\frontEnd;

use App\Services\CsvImport;
use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Model\Area;
use App\Model\CSV_Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AreaController extends Controller
{


    public function csv(Request $request)
    {
        try {
            $file = CsvImport::upload($request, 'csv_file');
            CsvImport::assertColumns(new AreaImport, $file);

            Area::truncate();

            Excel::import(new AreaImport, $file);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Service_areas')->first();
            $csv_source->records = Area::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Area imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'false',
                'result' => $th->getMessage(),
            );
            return $response;
        }
    }

    public function index()
    {
        $areas = Area::orderBy('area_recordid')->paginate(20);
        return view('backEnd.tables.tb_area', compact('areas'));
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
        $area = Area::find($id);
        return response()->json($area);
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
        $area = Area::find($id);
        $area->area_description = $request->area_description;
        $area->save();

        return response()->json($area);
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
}
