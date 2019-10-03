<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Phone;
use App\Servicephone;
use App\Locationphone;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class PhoneController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        Phone::truncate();
        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'phones' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $phone = new Phone();
                $strtointclass = new Stringtoint();
                $phone->phone_recordid = $record[ 'id' ];
                $phone->phone_recordid = $strtointclass->string_to_int($record[ 'id' ]);
                $phone->phone_number = isset($record['fields']['number'])?$record['fields']['number']:null;


                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $phonelocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_locations = $phone->phone_locations. ','. $phonelocation;
                        else
                            $phone->phone_locations = $phonelocation;
                        $i ++;
                    }
                }


                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $phoneservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_services = $phone->phone_services. ','. $phoneservice;
                        else
                            $phone->phone_services = $phoneservice;
                        $i ++;
                    }
                }

                if(isset($record['fields']['organizations'])){
                    $i = 0;
                    foreach ($record['fields']['organizations']  as  $value) {

                        $phoneorganization=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $phone->phone_organizations = $phone->phone_organizations. ','. $phoneorganization;
                        else
                            $phone->phone_organizations = $phoneorganization;
                        $i ++;
                    }
                }

                $phone->phone_contacts = isset($record['fields']['contacts'])? implode(",", $record['fields']['contacts']):null;
                $phone->phone_extension = isset($record['fields']['extension'])?$record['fields']['extension']:null;
                $phone->phone_type = isset($record['fields']['type'])?$record['fields']['type']:null;
                $phone->phone_language = isset($record['fields']['language'])? implode(",", $record['fields']['language']):null;
                $phone->phone_description = isset($record['fields']['description'])?$record['fields']['description']:null;
                $phone->phone_schedule = isset($record['fields']['schedule'])? implode(",", $record['fields']['schedule']):null;
                $phone ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Phones')->first();
        $airtable->records = Phone::count();
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

        if ($filename!='phones.csv') 
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

        Phone::truncate();
        Servicephone::truncate();
        Locationphone::truncate();

        foreach ($csv_data as $row) {

            $phone = new Phone();

            $phone->phone_recordid = $row['id'];
            $phone->phone_services = $row['service_id'];

            if($row['id'] && $row['service_id']){
                $service_phone = new Servicephone();
                $service_phone->service_recordid = $row['service_id']!='NULL'?$row['service_id']:null;
                $service_phone->phone_recordid = $row['id']!='NULL'?$row['id']:null;
                $service_phone->save();

            }

            $phone->phone_locations = $row['location_id'];

            if($row['id'] && $row['location_id']){
                $location_phone = new Locationphone();
                $location_phone->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
                $location_phone->phone_recordid = $row['id']!='NULL'?$row['id']:null;
                $location_phone->save();

            }

            $phone->phone_number = $row['number']!='NULL'?$row['number']:null;
            $phone->phone_organizations = $row['organization_id']!='NULL'?$row['organization_id']:null;
            $phone->phone_contacts = $row['contact_id']!='NULL'?$row['contact_id']:null;
            $phone->phone_extension = $row['extension']!='NULL'?$row['extension']:null;
            $phone->phone_type = $row['type']!='NULL'?$row['type']:null;
            $phone->phone_language = $row['language']!='NULL'?$row['language']:null;
            $phone->phone_description = $row['description']!='NULL'?$row['description']:null;                                              
            $phone ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Phones')->first();
        $csv_source->records = Phone::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $phones = Phone::orderBy('phone_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_phones', compact('phones', 'source_data'));
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
        $phone= Phone::find($id);
        return response()->json($phone);
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
        $phone = Phone::find($id);
        $phone->phone_number = $request->phone_number;
        $phone->phone_extension = $request->phone_extension;
        $phone->phone_type = $request->phone_type;
        $phone->phone_language = $request->phone_language;
        $phone->phone_description = $request->phone_description;
        $phone->flag = 'modified';
        $phone->save();

        return response()->json($phone);
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
