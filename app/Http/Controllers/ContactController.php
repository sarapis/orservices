<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Contact;
use App\Airtablekeyinfo;
use App\Servicecontact;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use App\Servicelocation;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info){
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        Contact::truncate();
        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
        ));

        $request = $airtable->getContent( 'contact' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $contact = new Contact();
                $strtointclass = new Stringtoint();

                $contact->contact_recordid= $strtointclass->string_to_int($record[ 'id' ]);

                $contact->contact_name = isset($record['fields']['name'])?$record['fields']['name']:null;
                $contact->contact_organizations = isset($record['fields']['organizations'])? implode(",", $record['fields']['organizations']):null;

                $contact->contact_organizations = $strtointclass->string_to_int($contact->contact_organizations);

                $contact->contact_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;

                $contact->contact_services = $strtointclass->string_to_int($contact->contact_services);

                $contact->contact_title = isset($record['fields']['title'])?$record['fields']['title']:null;
                $contact->contact_department = isset($record['fields']['department'])?$record['fields']['department']:null;
                $contact->contact_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $contact->contact_phones = isset($record['fields']['phones'])? implode(",", $record['fields']['phones']):null;

                $contact->contact_phones = $strtointclass->string_to_int($contact->contact_phones);

                $contact ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Contact')->first();
        $airtable->records = Contact::count();
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

        if ($filename!='contacts.csv') 
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

        Contact::truncate();
        Servicecontact::truncate();


        foreach ($csv_data as $row) {

            $contact = new Contact();

            $contact->contact_recordid= $row['id'];
            $contact->contact_services = $row['service_id']!='NULL'?$row['service_id']:null;

            if($row['service_id']){

                $service_contact = new Servicecontact();
                $service_contact->service_recordid=$row['service_id']!='NULL'?$row['service_id']:null;
                $service_contact->contact_recordid=$row['id'];
                $service_contact->save();

            }


            $contact->contact_email = $row['email']!='NULL'?$row['email']:null;
            $contact->contact_name = $row['name']!='NULL'?$row['name']:null;
            $contact->contact_phones = $row['phone_number']!='NULL'?$row['phone_number']:null;
            $contact->contact_phone_areacode = $row['phone_areacode']!='NULL'?$row['phone_areacode']:null;
            $contact->contact_phone_extension = $row['phone_extension']!='NULL'?$row['phone_extension']:null;
            $contact->contact_title = $row['title']!='NULL'?$row['title']:null;
            $contact->contact_organizations = $row['organization_id']!='NULL'?$row['organization_id']:null;
            $contact->contact_department = $row['department']!='NULL'?$row['department']:null;          
                                     
            $contact ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Contacts')->first();
        $csv_source->records = Contact::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }


    public function index()
    {
        $contacts = Contact::orderBy('contact_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_contacts', compact('contacts', 'source_data'));
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
        $contact= Contact::find($id);
        return response()->json($contact);
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
        $contact = Contact::find($id);
        $contact->contact_name = $request->contact_name;
        $contact->contact_title = $request->contact_title;
        $contact->contact_department = $request->contact_department;
        $contact->contact_email = $request->contact_email;
        $contact->flag = 'modified';
        $contact->save();

        return response()->json($contact);
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
