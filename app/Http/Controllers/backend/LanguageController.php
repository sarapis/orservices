<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Imports\LanguageImport;
use App\Model\AllLanguage;
use App\Model\CSV_Source;
use App\Model\Language;
use App\Model\Layout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
    public function index()
    {
        $languages = AllLanguage::get();
        $layout = Layout::first();

        return view('backEnd.languages.index', compact('languages', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.languages.create');
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
            'language_name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            AllLanguage::create([
                'language_name' => $request->get('language_name'),
                'note' => $request->get('note'),
                'created_by' => Auth::id(),
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
        $language = AllLanguage::whereId($id)->first();

        return view('backEnd.languages.edit', compact('language'));
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
            'language_name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            AllLanguage::whereId($id)->update([
                'language_name' => $request->get('language_name'),
                'note' => $request->get('note'),
                'updated_by' => Auth::id(),
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
            AllLanguage::whereId($id)->update([
                'deleted_by' => Auth::id(),
            ]);
            AllLanguage::whereId($id)->delete();
            DB::commit();
            return redirect()->to('languages')->with('success', 'Language deleted successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->to('languages')->with('error', $th->getMessage());
        }
    }
}
