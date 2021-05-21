<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Imports\LanguageImport;
use App\Model\Language;
use App\Model\CSV_Source;
use App\Model\Layout;
use App\Model\Location;
use App\Model\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends Controller
{

    public function csv(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename =  $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'languages.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            // if (count($data) > 0) {
            //     $csv_header_fields = [];
            //     foreach ($data[0] as $key => $value) {
            //         $csv_header_fields[] = $key;
            //     }
            //     $csv_data = $data;
            // }

            Language::truncate();

            Excel::import(new LanguageImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Languages')->first();
            $csv_source->records = Language::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Language imported successfully',
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $layout = Layout::first();
            $languages = Language::orderBy('order')->get();

            if (!$request->ajax()) {
                return view('backEnd.languages.index', compact('languages', 'layout'));
            }
            $languages = Language::orderBy('order');
            return DataTables::of($languages)
                ->editColumn('language_location', function ($row) {
                    return $row->location ? $row->location->location_name : '';
                })
                ->editColumn('language_service', function ($row) {
                    return $row->service ? $row->service->service_name : '';
                })
                ->addColumn('action', function ($row) {
                    $links = '';
                    if ($row) {
                        $links .= '<a href="' . route("languages.edit", $row->id) . '"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>';
                        $id = $row->id;
                        $route = 'languages';
                        $links .=  view('backEnd.delete', compact('id', 'route'))->render();
                    }
                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            Log::error('Error in language controller index : ' . $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::whereNotNull('service_name')->pluck('service_name', 'service_recordid');
        $locations = Location::whereNotNull('location_name')->pluck('location_name', 'location_recordid');
        return view('backEnd.languages.create', compact('services', 'locations'));
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
            'language' => 'required',
            'order' => 'required|unique:languages,order'
        ]);
        try {
            DB::beginTransaction();
            $language_recordid = Language::max('language_recordid') + 1;
            Language::create([
                'language' => $request->get('language'),
                'language_recordid' => $language_recordid,
                'order' => $request->get('order'),
                'language_service' => $request->get('language_service'),
                'language_location' => $request->get('language_location'),
            ]);
            DB::commit();

            return redirect()->to('languages')->with('success', 'Language added successfully!');
        } catch (\Throwable $th) {
            return redirect()->to('languages')->with('error', $th->getMessage());
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
        $language = Language::whereId($id)->first();
        $services = Service::whereNotNull('service_name')->pluck('service_name', 'service_recordid');
        $locations = Location::whereNotNull('location_name')->pluck('location_name', 'location_recordid');
        return view('backEnd.languages.edit', compact('language', 'services', 'locations'));
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
            'language' => 'required',
            'order' => 'required'
        ]);
        try {
            DB::beginTransaction();
            Language::whereId($id)->update([
                'language' => $request->get('language'),
                'order' => $request->get('order'),
                'language_service' => $request->get('language_service'),
                'language_location' => $request->get('language_location'),
            ]);
            DB::commit();

            return redirect()->to('languages')->with('success', 'Language updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->to('languages')->with('error', $th->getMessage());
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
            Language::whereId($id)->delete();
            DB::commit();
            return redirect()->to('languages')->with('success', 'Language deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->to('languages')->with('error', $th->getMessage());
        }
    }
}
