<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Detail;
use App\Airtables;
use App\Services\Stringtoint;

class DetailController extends Controller
{

    public function airtable()
    {

        Detail::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'details' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $detail = new Detail();
                $strtointclass = new Stringtoint();

                $detail->detail_recordid = $strtointclass->string_to_int($record[ 'id' ]);
                $detail->detail_value = isset($record['fields']['value'])?$record['fields']['value']:null;
                $detail->detail_type = isset($record['fields']['Detail Type'])?$record['fields']['Detail Type']:null;
                $detail->detail_description= isset($record['fields']['description'])?$record['fields']['description']:null;

                if(isset($record['fields']['organizations'])){
                    $i = 0;
                    foreach ($record['fields']['organizations']  as  $value) {

                        $detailorganization=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $detail->detail_organizations = $detail->detail_organizations. ','. $detailorganization;
                        else
                            $detail->detail_organizations = $detailorganization;
                        $i ++;
                    }
                } 

                $detail->detail_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $detailservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $detail->detail_services = $detail->detail_services. ','. $detailservice;
                        else
                            $detail->detail_services = $detailservice;
                        $i ++;
                    }
                } 

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $detaillocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $detail->detail_locations = $detail->detail_locations. ','. $detaillocation;
                        else
                            $detail->detail_locations = $detaillocation;
                        $i ++;
                    }
                }

                $detail ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Details')->first();
        $airtable->records = Detail::count();
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
        $details = Detail::orderBy('detail_value')->get();

        return view('backEnd.tables.tb_details', compact('details'));
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
        $detail= Detail::find($id);
        return response()->json($detail);
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
        $detail = Detail::find($id);
        $detail->detail_value = $request->detail_value;
        $detail->detail_type = $request->detail_type;
        $detail->detail_description = $request->detail_description;
        $detail->flag = 'modified';
        $detail->save();

        return response()->json($detail);
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
