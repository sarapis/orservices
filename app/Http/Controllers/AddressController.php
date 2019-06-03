<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Address;
use App\Airtables;
use App\Services\Stringtoint;

class AddressController extends Controller
{

    public function airtable()
    {

        Address::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'address' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $address = new Address();
                $strtointclass = new Stringtoint();

                $address->address_recordid = $strtointclass->string_to_int($record[ 'id' ]);

                $address->address_1 = isset($record['fields']['address_1'])?$record['fields']['address_1']:null;
                $address->address_2 = isset($record['fields']['address_2'])?$record['fields']['address_2']:null;
                $address->address_city = isset($record['fields']['city'])?$record['fields']['city']:null;
                $address->address_state_province = isset($record['fields']['State'])?$record['fields']['State']:null;
                $address->address_postal_code = isset($record['fields']['Zip Code'])?$record['fields']['Zip Code']:null;
                $address->address_region = isset($record['fields']['region'])?$record['fields']['region']:null;
                $address->address_country = isset($record['fields']['Country'])?$record['fields']['Country']:null;
                $address->address_attention = isset($record['fields']['y-Attention'])?$record['fields']['y-Attention']:null;
                $address->address_type = isset($record['fields']['y-address_type'])? implode(",", $record['fields']['y-address_type']):null;

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $addresslocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $address->address_locations = $address->address_locations. ','. $addresslocation;
                        else
                            $address->address_locations = $addresslocation;
                        $i ++;
                    }
                }

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $addressservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $address->address_services = $address->address_services. ','. $addressservice;
                        else
                            $address->address_services = $addressservice;
                        $i ++;
                    }
                }
                $address ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Address')->first();
        $airtable->records = Address::count();
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
        $address = Address::orderBy('address_1')->get();

        return view('backEnd.tables.tb_address', compact('address'));
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
        $address= Address::find($id);
        return response()->json($address);
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
        $address = Address::find($id);
        $address->address_1 = $request->address_1;
        $address->address_2 = $request->address_2;
        $address->address_city = $request->address_city;
        $address->address_state_province = $request->address_state_province;
        $address->address_postal_code = $request->address_postal_code;
        $address->address_region = $request->address_region;
        $address->address_country = $request->address_country;
        $address->address_attention = $request->address_attention;
        $address->address_type = $request->address_type;
        $address->flag = 'modified';
        $address->save();

        return response()->json($address);
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
