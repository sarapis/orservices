<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Organization;
use App\Organizationdetail;
use App\Taxonomy;
use App\Alt_taxonomy;
use App\Servicetaxonomy;
use App\Service;
use App\Location;
use App\Layout;
use App\Map;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OrganizationController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        Organization::truncate();
        Organizationdetail::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key'   => $api_key,
            'base'      => $base_url,
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
                if(isset($record['fields']['logo-x'])){
                    foreach ($record['fields']['logo-x'] as $key => $image) {
                        try {
                            $organization->organization_logo_x .= $image["url"];
                        } catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                        }
                    }
                }
                if(isset($record['fields']['forms-x'])){
                    foreach ($record['fields']['forms-x'] as $key => $form) {
                        try {
                            $organization->organization_forms_x_filename .= $form["filename"];
                            $organization->organization_forms_x_url .= $form["url"];
                        } catch (Exception $e) {
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                        }
                    }
                }
                $organization->organization_alternate_name = isset($record['fields']['alternate_name'])?$record['fields']['alternate_name']:null;
                $organization->organization_x_uid = isset($record['fields']['x-uid'])?$record['fields']['x-uid']:null;
                $organization->organization_description = isset($record['fields']['description'])?$record['fields']['description']:null;

                $organization->organization_description =  mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                $organization->organization_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $organization->organization_url = isset($record['fields']['url'])?$record['fields']['url']:null;
                $organization->organization_status_x = isset($record['fields']['status-x'])?$record['fields']['status-x']:null;
                if($organization->organization_status_x == 'Vetted')
                    $organization->organization_status_sort = 1;
                if($organization->organization_status_x == 'Vetting In Progress')
                    $organization->organization_status_sort = 2;
                if($organization->organization_status_x == 'Not vetted')
                    $organization->organization_status_sort = 3;
                if($organization->organization_status_x == null)
                    $organization->organization_status_sort = 4;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_legal_status = isset($record['fields']['legal_status'])?$record['fields']['legal_status']:null;
                $organization->organization_tax_status = isset($record['fields']['tax_status'])?$record['fields']['tax_status']:null;
                $organization->organization_tax_id = isset($record['fields']['tax_id'])?$record['fields']['tax_id']:null;
                $organization->organization_year_incorporated = isset($record['fields']['year_incorporated'])?$record['fields']['year_incorporated']:null;

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

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {
                        $organization_detail = new Organizationdetail();
                        $organization_detail->organization_recordid=$organization->organization_recordid;
                        $organization_detail->detail_recordid=$strtointclass->string_to_int($value);
                        $organization_detail->save();
                        $organizationdetail=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $organization->organization_details = $organization->organization_details. ','. $organizationdetail;
                        else
                            $organization->organization_details = $organizationdetail;
                        $i ++;
                    }
                }

                if(isset($record['fields']['AIRS Taxonomy-x'])){
                    $i = 0;
                    foreach ($record['fields']['AIRS Taxonomy-x']  as  $value) {

                        if($i != 0)
                            $organization->organization_airs_taxonomy_x = $organization->organization_airs_taxonomy_x . ','. $value;
                        else
                            $organization->organization_airs_taxonomy_x  = $value;
                        $i ++;
                    }
                }    

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

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='organizations.csv') 
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

        Organization::truncate();
        Organizationdetail::truncate();

        foreach ($csv_data as $row) {
            
            $organization = new Organization();

            $organization->organization_recordid = $row['id'];
            $organization->organization_name = $row['name']!='NULL'?$row['name']:null;

            $organization->organization_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;

            $organization->organization_description = $row['description']!='NULL'?$row['description']:null;
            $organization->organization_url = $row['url']!='NULL'?$row['url']:null;
            $organization->organization_email = $row['email']!='NULL'?$row['email']:null;
            $organization->organization_tax_status = $row['tax_status']!='NULL'?$row['tax_status']:null;
            $organization->organization_tax_id = $row['tax_id']!='NULL'?$row['tax_id']:null;
            $organization->organization_year_incorporated = $row['year_incorporated']!='NULL'?$row['year_incorporated']:null;
            $organization->organization_legal_status = $row['legal_status']!='NULL'?$row['legal_status']:null;
           
                                     
            $organization->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Organizations')->first();
        $csv_source->records = Organization::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::orderBy('organization_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_organization', compact('organizations', 'source_data'));
    }

    public function organizations()
    {
        $organizations = Organization::orderBy('organization_status_sort')->orderBy('organization_name')->paginate(10);
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        //=====================updated tree==========================//

        $grandparent_taxonomies = Alt_taxonomy::all();
        $taxonomy_tree = [];
        foreach ($grandparent_taxonomies as $key => $grandparent) {

            $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
            $terms = $grandparent->terms()->get();
            $taxonomy_parent_name_list = [];
            foreach ($terms as $term_key => $term) {
                array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
            }

            $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

            $parent_taxonomy = [];
            $grandparent_service_count = 0;
            foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                if ($parent_count == $term_count) {
                    $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                    $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                    $child_data['child_taxonomies'] = $child_taxonomies;
                    array_push($parent_taxonomy, $child_data);
                } else {
                    foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                        $child_data['parent_taxonomy'] = $child_term;
                        $child_data['child_taxonomies'] = "";
                        array_push($parent_taxonomy, $child_data);
                    }
                }
            }
            $taxonomy_ids = $grandparent->terms()->allRelatedIds();
            $grand_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $taxonomy_ids)->groupBy('service_recordid')->pluck('service_recordid')->toArray();
            $grandparent_service_count = Service::whereIn('service_recordid',$grand_service_ids)->count();

            $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
            $taxonomy_data['service_count'] = $grandparent_service_count;
            array_push($taxonomy_tree, $taxonomy_data);
        }

        return view('frontEnd.organizations', compact('organizations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree'));
    }

    public function organization($id)
    {
        $organization = Organization::where('organization_recordid', '=', $id)->first();
        $locations = Location::with('services', 'address', 'phones')->where('location_organization', '=', $id)->get();
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        //=====================updated tree==========================//

        $grandparent_taxonomies = Alt_taxonomy::all();
        $taxonomy_tree = [];
        foreach ($grandparent_taxonomies as $key => $grandparent) {

            $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
            $terms = $grandparent->terms()->get();
            $taxonomy_parent_name_list = [];
            foreach ($terms as $term_key => $term) {
                array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
            }

            $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

            $parent_taxonomy = [];
            $grandparent_service_count = 0;
            foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                if ($parent_count == $term_count) {
                    $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                    $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                    $child_data['child_taxonomies'] = $child_taxonomies;
                    array_push($parent_taxonomy, $child_data);
                } else {
                    foreach($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                        $child_data['parent_taxonomy'] = $child_term;
                        $child_data['child_taxonomies'] = "";
                        array_push($parent_taxonomy, $child_data);
                    }
                }
            }
            $taxonomy_ids = $grandparent->terms()->allRelatedIds();
            $grand_service_ids = Servicetaxonomy::whereIn('taxonomy_id', $taxonomy_ids)->groupBy('service_recordid')->pluck('service_recordid')->toArray();
            $grandparent_service_count = Service::whereIn('service_recordid',$grand_service_ids)->count();

            $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
            $taxonomy_data['service_count'] = $grandparent_service_count;
            array_push($taxonomy_tree, $taxonomy_data);
        }

        return view('frontEnd.organization', compact('organization', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'taxonomy_tree'));
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
