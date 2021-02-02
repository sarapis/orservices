<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Map;
use App\Model\Source_data;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DataController extends Controller
{
    /**
     * Post Repository
     *
     * @var Post
     */
    // protected $about;

    // public function __construct(About $about)
    // {
    //     $this->about = $about;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $source_data = Source_data::find(1);

        return view('backEnd.pages.data', compact('source_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $map = Map::find(1);
        $map->active = $request->active;
        $map->api_key = $request->api_key;
        $map->state = $request->state;
        $map->lat = $request->lat;
        $map->long = $request->long;

        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->post->find($id);

        if (is_null($post)) {
            return Redirect::route('posts.index');
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $source_data = Source_data::find(1);
            // var_dump($request->input('active'));
            // exit();
            $source_data->active = $request->input('source_data');


            $source_data->save();


            return redirect('import');
        } catch (\Throwable $th) {
            return redirect('import');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->post->find($id)->delete();

        return Redirect::route('admin.posts.index');
    }
    public function add_country()
    {
        $countries = DB::table('countries')->pluck('name', 'sortname');

        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            // date_default_timezone_set($zone);
            // $zones_array[$key]['zone'] = $zone;
            // $zones_array[$key]['offset'] = (int) ((int) date('O', $timestamp)) / 100;
            // $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
            $zones_array[$zone] = ('UTC/GMT ' . date('P', $timestamp)) . ' ' . $zone;
        }
        return view('backEnd.settings.add_country', compact('countries', 'zones_array'));
    }
    public function save_country(Request $request)
    {
        try {
            Address::whereNULL('address_country')->update([
                'address_country' => $request->country
            ]);
            DB::commit();

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $values = [
                "TIME_ZONE" => $request->get('timezone') ? $request->get('timezone') : 'UTC',
            ];

            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {

                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }

            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                return false;
            }
            Artisan::call('config:cache');
            Artisan::call('config:clear');
            Session::flash('message', 'Data saved successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();
        }
    }
    public function save_source_data(Request $request)
    {
        try {
            $source_data = Source_data::find(1);
            $source_data->active = $request->input('source_data');
            $source_data->save();
            $airtable_api_key_input = $request->airtable_api_key_input;
            $airtable_base_url_input = $request->airtable_base_url_input;
            // if ($request->source_data == 1) {
            //     Airtablekeyinfo::whereid(1)->update([
            //         'api_key' => $airtable_api_key_input,
            //         'base_url' => $airtable_base_url_input,
            //     ]);
            // } else if ($request->source_data == 3) {
            //     Airtablekeyinfo::whereid(2)->update([
            //         'api_key' => $airtable_api_key_input,
            //         'base_url' => $airtable_base_url_input,
            //     ]);
            // }
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
