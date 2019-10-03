<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Location;
use App\Locationaddress;
use App\Locationphone;
use App\Locationschedule;
use App\Accessibility;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        Location::truncate();
        Locationaddress::truncate();
        Locationphone::truncate();
        Locationschedule::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
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

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='locations.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }


        Location::truncate();

        foreach ($csv_data as $row) {
            
            $location = new Location();

            $location->location_recordid= $row['id'];
            $location->location_name = $row['name']!='NULL'?$row['name']:null;

            $location->location_organization = $row['organization_id'];

            $location->location_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;
            $location->location_description = $row['description']!='NULL'?$row['description']:null;
            $location->location_latitude = $row['latitude']!='NULL'?$row['latitude']:null;
            $location->location_longitude = $row['longitude']!='NULL'?$row['longitude']:null;
            $location->location_transportation = $row['transportation']!='NULL'?$row['transportation']:null;
           
                                     
            $location ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Locations')->first();
        $csv_source->records = Location::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function csv_accessibility(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='accessibility_for_disabilities.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        if ($csv_header_fields[0]!='id' || $csv_header_fields[1]!='location_id' || $csv_header_fields[2]!='accessibility' || $csv_header_fields[3]!='details') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Accessibility::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $accessibility = new Accessibility();

            $accessibility->accessibility_recordid =$row[$csv_header_fields[0]]!='NULL'?$row[$csv_header_fields[0]]:null;
            $accessibility->location_recordid = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;
            $accessibility->accessibility =$row[$csv_header_fields[2]]!='NULL'?$row[$csv_header_fields[2]]:null;
            ;   
            $accessibility->details =$row[$csv_header_fields[3]]!='NULL'?$row[$csv_header_fields[3]]:null;
            ;       
            $accessibility->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
        $csv_source->records = Accessibility::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $locations = Location::with('organization')->orderBy('location_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_location', compact('locations', 'source_data'));
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
