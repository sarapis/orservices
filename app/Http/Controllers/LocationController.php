<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Location;
use App\Locationaddress;
use App\Locationphone;
use App\Locationschedule;
use App\Airtables;
use App\Services\Stringtoint;

class LocationController extends Controller
{

    public function airtable()
    {

        Location::truncate();
        Locationaddress::truncate();
        Locationphone::truncate();
        Locationschedule::truncate();

        $airtable = new Airtable(array(
            'api_key'   => 'keyIvQZcMYmjNbtUO',
            'base'      => 'appqjWvTygtaX9eil',
        ));

        $request = $airtable->getContent( 'locations' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $location = new Location();
                $strtointclass = new Stringtoint();
                $location->location_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                $location->location_name = isset($record['fields']['name'])?$record['fields']['name']:null;

                $location->location_organization = isset($record['fields']['organization'])? implode(",", $record['fields']['organization']):null;

                $location->location_organization = $strtointclass->string_to_int($location->location_organization);

                $location->location_alternate_name = isset($record['fields']['alternate_name'])?$record['fields']['alternate_name']:null;
                $location->location_transportation = isset($record['fields']['transportation'])?$record['fields']['transportation']:null;
                $location->location_latitude = isset($record['fields']['latitude'])?$record['fields']['latitude']:null;
                $location->location_longitude = isset($record['fields']['longitude'])?$record['fields']['longitude']:null;
                $location->location_description = isset($record['fields']['description'])?$record['fields']['description']:null;
                $location->location_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;  

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $locationservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $location->location_services = $location->location_services. ','. $locationservice;
                        else
                            $location->location_services = $locationservice;
                        $i ++;
                    }
                } 
               
                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {

                        $location_phone = new Locationphone();
                        $location_phone->location_recordid=$location->location_recordid;
                        $location_phone->phone_recordid=$strtointclass->string_to_int($value);
                        $location_phone->save();
                        if($i != 0)
                            $location->location_phones = $location->location_phones. ','. $location_phone->phone_recordid;
                        else
                            $location->location_phones = $location_phone->phone_recordid;
                        $i ++;
                    }
                }

                $location->location_details = isset($record['fields']['details'])? implode(",", $record['fields']['details']):null;
            

                if(isset($record['fields']['schedule'])){
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {
                        $locationschedule = new Locationschedule();
                        $locationschedule->location_recordid=$location->location_recordid;
                        $locationschedule->schedule_recordid=$strtointclass->string_to_int($value);
                        $locationschedule->save();
                        if($i != 0)
                            $location->location_schedule = $location->location_schedule. ','. $locationschedule->schedule_recordid;
                        else
                            $location->location_schedule = $locationschedule->schedule_recordid;
                        $i ++;
                    }
                } 

                $location->location_address = isset($record['fields']['address'])? implode(",", $record['fields']['address']):null;  

                if(isset($record['fields']['address'])){
                    $i=0;
                    foreach ($record['fields']['address']  as  $value) {
                        $locationaddress = new Locationaddress();
                        $locationaddress->location_recordid=$location->location_recordid;
                        $locationaddress->address_recordid=$strtointclass->string_to_int($value);
                        $locationaddress->save();
                        if($i != 0)
                            $location->location_address = $location->location_address. ','. $locationaddress->address_recordid;
                        else
                            $location->location_address = $locationaddress->address_recordid;
                        $i ++;
                    }
                }    
                
                $location ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Locations')->first();
        $airtable->records = Location::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('location_name')->paginate(15);

        return view('backEnd.tables.tb_location', compact('locations'));
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
        $process= Location::find($id);
        return response()->json($process);
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
        $location = Location::find($id);
        // $project = Project::where('id', '=', $id)->first();
        $location->location_name = $request->location_name;
        $location->location_alternate_name = $request->location_alternate_name;
        $location->location_transportation = $request->location_transportation;
        $location->location_latitude = $request->location_latitude;
        $location->location_longitude = $request->location_longitude;
        $location->location_description = $request->location_description;
        $location->flag = 'modified';
        $location->save();
        // var_dump($project);
        // exit();
        return response()->json($location);
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
