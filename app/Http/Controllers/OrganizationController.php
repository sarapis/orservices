<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Organization;
use App\Location;
use App\Layout;
use App\Map;
use App\Airtables;
use App\Services\Stringtoint;
use PDF;

class OrganizationController extends Controller
{

    public function airtable()
    {

        Organization::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'organizations' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $organization = new Organization();
                $strtointclass = new Stringtoint();
                $organization->organization_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                $organization->organization_name = isset($record['fields']['name'])?$record['fields']['name']:null;
                $organization->organization_alternate_name = isset($record['fields']['alternate_name'])?$record['fields']['alternate_name']:null;
                $organization->organization_x_uid = isset($record['fields']['x-uid'])?$record['fields']['x-uid']:null;
                $organization->organization_description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $organization->organization_description =  mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                $organization->organization_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $organization->organization_url = isset($record['fields']['url'])?$record['fields']['url']:null;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_tax_id = isset($record['fields']['tax_id'])?$record['fields']['tax_id']:null;
                $organization->organization_year_incorporated = isset($record['fields']['year_incorporated'])?$record['fields']['year_incoporated']:null;

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $organizationservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_services = $organization->organization_services. ','. $organizationservice;
                        else
                            $organization->organization_services = $organizationservice;
                        $i ++;
                    }
                }

                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {

                        $organizationphone=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_phones = $organization->organization_phones. ','. $organizationphone;
                        else
                            $organization->organization_phones = $organizationphone;
                        $i ++;
                    }
                }
                

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $organizationlocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_locations = $organization->organization_locations. ','. $organizationlocation;
                        else
                            $organization->organization_locations = $organizationlocation;
                        $i ++;
                    }
                }
                $organization->organization_contact = isset($record['fields']['contact']) ?implode(",", $record['fields']['contact']):null;

                $organization->organization_contact = $strtointclass->string_to_int($organization->organization_contact);

                $organization->organization_details = isset($record['fields']['details']) ?implode(",", $record['fields']['details']):null;

                $organization->organization_details = $strtointclass->string_to_int($organization->organization_details);

                $organization ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Organizations')->first();
        $airtable->records = Organization::count();
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
        $organizations = Organization::orderBy('organization_name')->paginate(20);

        return view('backEnd.tables.tb_organization', compact('organizations'));
    }

    public function organizations()
    {
        $organizations = Organization::orderBy('organization_name')->paginate(10);
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];

        return view('frontEnd.organizations', compact('organizations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances'));
    }

    public function organization($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $locations = Location::with('services', 'address')->where('location_organization', '=', $id)->get();
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];

        return view('frontEnd.organization', compact('organization', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances'));
    }

    public function download($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $organization_name = $organization->organization_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.organization_download', compact('organization', 'layout'));

        return $pdf->download($organization_name.'.pdf');
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
        $organization= Organization::find($id);
        return response()->json($organization);
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
        $organization = Organization::find($id);
        $organization->organization_name = $request->organization_name;
        $organization->organization_alternate_name = $request->organization_alternate_name;
        $organization->organization_x_uid = $request->organization_x_uid;
        $organization->organization_description = $request->organization_description;
        $organization->organization_email = $request->organization_email;
        $organization->organization_url = $request->organization_url;
        $organization->organization_legal_status = $request->legal_status;
        $organization->organization_tax_status = $request->organization_tax_status;
        $organization->organization_tax_id = $request->organization_tax_id;
        $organization->organization_year_incorporated = $request->organization_year_incorporated;
        $organization->flag = 'modified';
        $organization->save();

        return response()->json($organization);
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
