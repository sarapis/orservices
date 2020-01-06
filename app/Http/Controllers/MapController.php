<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Map;
use Geolocation;
use Geocode;
use Spatie\Geocoder\Geocoder;
use App\Location;
use App\Address;
use Image;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class MapController extends Controller
{
    /**
     * Post Repository
     *
     * @var Post
    //  */
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
        $map = Map::find(1);
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $invalid_location_info_count = Location::whereNull('location_name')->count();

        $geocode_status_text = '';
        $enrich_status_text = '';
        if ($ungeocoded_location_numbers == $invalid_location_info_count) {
            $geocode_status_text = 'All valid locations have already been geocoded.';
        }

        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();
        if ($unenriched_location_count == 0) {
            $enrich_status_text = 'All valid locations have already been enriched before.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'location_count', 'unenriched_location_count', 'geocode_status_text', 'enrich_status_text'));
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
        $map=Map::find(1);
        $map->active=$request->active;
        $map->api_key=$request->api_key;
        $map->state=$request->state;
        $map->lat=$request->lat;
        $map->long=$request->long;
        $map->zoom=$request->zoom;

        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('apis');
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

        if (is_null($post))
        {
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
    	$map=Map::find(1);
        // var_dump($request->input('active'));
        // exit();
        if ($request->input('active') == 'checked')
        {
            $map->active = 1;
            $map->api_key=$request->input('api_key');
            $map->state=$request->input('state');
            $map->lat=$request->input('lat');
            $map->long=$request->input('long');
            $map->zoom=$request->input('zoom');
            $map->zoom_profile=$request->input('profile_zoom');
        }
        else {
            $map->active = 0;
        }
        
        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('map');
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

    public function upload()
    {
        $file = Input::file('file');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = Validator::make($input, $rules);
        if ( $validator->fails()) {
            return Response::json(array('success' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        $fileName = time() . '-' . $file->getClientOriginalName();
        $destination = public_path() . '/uploads/';
        $file->move($destination, $fileName);

        echo url('/uploads/'. $fileName);
    }

    public function scan_ungeocoded_location(Request $request) 
    {
        $map = Map::find(1);
        $geocode_status_text = 'Not Started';
        $enrich_status_text = '';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $invalid_location_info_count = Location::whereNull('location_name')->count();
        if ($ungeocoded_location_numbers == $invalid_location_info_count) {
            $geocode_status_text = 'All valid locations have already been geocoded.';
        }
        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();
        if ($unenriched_location_count == 0) {
            $enrich_status_text = 'All valid locations have already been enriched before.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'geocode_status_text', 'location_count', 'unenriched_location_count', 'enrich_status_text'));
    }


    public function scan_enrichable_location(Request $request) 
    {
        $map = Map::find(1);
        $geocode_status_text = '';
        $enrich_status_text = 'Not Started';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $invalid_location_info_count = Location::whereNull('location_name')->count();
        if ($ungeocoded_location_numbers == $invalid_location_info_count) {
            $geocode_status_text = 'All valid locations have already been geocoded.';
        }
        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();
        if ($unenriched_location_count == 0) {
            $enrich_status_text = 'All valid locations have already been enriched before.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'geocode_status_text', 'location_count', 'unenriched_location_count', 'enrich_status_text'));
    }

    public function apply_geocode(Request $request) 
    {
        $ungeocoded_location_info_list = Location::whereNull('location_latitude')->get();
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
        $geocoder->setApiKey($geocode_api_key);

        foreach ($ungeocoded_location_info_list as $key => $location_info) {
            $location_name = $location_info->location_name;
            if (!is_null($location_name)) {
                $response = $geocoder->getCoordinatesForAddress($location_name);
                $latitude = $response['lat'];
                $longitude = $response['lng'];
                $location_info->location_latitude = $latitude;
                $location_info->location_longitude = $longitude;
                $location_info->save();
            }
        }
        return redirect('map');
    }

    public function apply_enrich(Request $request) 
    {
        $valid_location_list = Location::whereNotNull('location_address')->get();
        $unenriched_location_list = Location::whereNotNull('location_name')->whereNull('enrich_flag')->get();
        set_time_limit(0);
        foreach ($unenriched_location_list as $key => $unenriched_location) {
            $address = Address::where('address_recordid', '=', $unenriched_location->location_address)->first();
            $borough = $address->address_city;
            $house_address_info = explode(' ', $address->address_1);
            $house_number = '';
            if (isset($house_address_info[0])) {
                $house_number = $house_address_info[0];
            }
            $street = '';
            if (isset($house_address_info[1])) {
                $street = $house_address_info[1];
            }
            $app_id = 'b985eb41';
            $app_key = '9e7522143dca2c6347306d73882b6e3f';

            $response = \Curl::to('https://api.cityofnewyork.us/geoclient/v1/address.json')
                ->withData(['houseNumber' => $house_number, 'street' => $street, 'borough' => $borough, 'app_id' => $app_id, 'app_key' => $app_key])
                ->get();
            $data = json_decode($response);
            if ($data) {
                if (isset($data->address)) {
                    $address_info = $data->address;
                    if (isset($address_info->cityCouncilDistrict)) {
                        $address->address_city_council_district = $address_info->cityCouncilDistrict;
                        $unenriched_location->location_city_council_district = $address_info->cityCouncilDistrict;
                    }
                    if (isset($address_info->communityDistrict)) {
                        $address->address_community_district = $address_info->communityDistrict;
                        $unenriched_location->location_community_district = $address_info->communityDistrict;                
                    }
                }
            }            
            $unenriched_location->enrich_flag = 'modified';
            $address->save();
            $unenriched_location->save();
        }
        return redirect('map');
    }

}
