<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Service;
use App\Servicelocation;
use App\Servicephone;
use App\Servicedetail;
use App\Serviceaddress;
use App\Serviceorganization;
use App\Servicecontact;
use App\Servicetaxonomy;
use App\Serviceschedule;
use App\Location;
use App\Locationphone;
use App\Locationaddress;
use App\Locationschedule;
use App\Organization;
use App\Organizationdetail;
use App\Contact;
use App\Phone;
use App\Address;
use App\Language;
use App\Taxonomy;
use App\Accessibility;
use App\Schedule;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;

use App\Map;
use App\Layout;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function zip(Request $request)
    {
        

        $extension = $request->file('file_zip')->getClientOriginalExtension();

        if ($extension != 'zip') {
            $response = array(
                'status' => 'error',
                'result' => 'This File is not zip file.',
            );
            return $response;
        }

        $path = $request->file('file_zip')->getRealPath();
        \Zipper::make($path)->extractTo('HSDS');

        $filename =  $request->file('file_zip')->getClientOriginalName();
        $request->file('file_zip')->move(public_path('/zip/'), $filename);

        $path = public_path('/HSDS/data/services.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'services.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Service::truncate();
        Serviceorganization::truncate();

    
        foreach ($csv_data as $row) {
            
            $service = new Service();

            $service->service_recordid= $row['id'];
            $service->service_name = $row['name']!='NULL'?$row['name']:null;

            if($row['organization_id']){

                    $service_organization = new Serviceorganization();
                    $service_organization->service_recordid=$service->service_recordid;
                    $service_organization->organization_recordid=$row['organization_id'];
                    $service_organization->save();

                    $service->service_organization = $row['organization_id'];

            }

            $service->service_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;
            $service->service_description = $row['description']!='NULL'?$row['description']:null;
            $service->service_application_process = $row['application_process']!='NULL'?$row['application_process']:null;
            $service->service_url = $row['url']!='NULL'?$row['url']:null;
            $service->service_program = $row['program_id']!='NULL'?$row['program_id']:null;

            $service->service_email = $row['email']!='NULL'?$row['email']:null;
            $service->service_status = $row['status']!='NULL'?$row['status']:null;

            $service->service_wait_time = $row['wait_time']!='NULL'?$row['wait_time']:null;
            $service->service_fees = $row['fees']!='NULL'?$row['fees']:null;
            $service->service_accreditations = $row['accreditations']!='NULL'?$row['accreditations']:null;
            $service->service_licenses = $row['licenses']!='NULL'?$row['licenses']:null;
        
            
            $service ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services')->first();
        $csv_source->records = Service::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //locations.csv
        $path = public_path('/HSDS/data/locations.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'locations.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

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

        //organizations.csv
        $path = public_path('/HSDS/data/organizations.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'organizations.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

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

        //contacts.csv
        $path = public_path('/HSDS/data/contacts.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'contacts.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

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

        //phones.csv
        $path = public_path('/HSDS/data/phones.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'phones.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

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

        //physical_addresses.csv
        $path = public_path('/HSDS/data/physical_addresses.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'physical_addresses.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Address::truncate();
        Locationaddress::truncate();
        Serviceaddress::truncate();

        foreach ($csv_data as $key => $row) {

            $address = new Address();

            $address->address_recordid = $row['id'];
            $address->address_locations = $row['location_id'];
            
            if($row['location_id']){
                $location_address = new Locationaddress();
                $location_address->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
                $location_address->address_recordid = $address->address_recordid;
                $location_address->save();

            }

            if($row['location_id']){
                $service_address = new Serviceaddress();
                $service_address->service_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
                $service_address->address_recordid = $address->address_recordid;
                $service_address->save();

            }

            $address->address_1 = $row['address_1']!='NULL'?$row['address_1']:null;
            $address->address_2 = $row['address_2']!='NULL'?$row['address_2']:null;
            $address->address_city= $row['city']!='NULL'?$row['city']:null;
            $address->address_postal_code = $row['postal_code']!='NULL'?$row['postal_code']:null;
            $address->address_state_province = $row['state_province']!='NULL'?$row['state_province']:null;
            $address->address_country = $row['country']!='NULL'?$row['country']:null;
            $address->address_organization = $row['organization_id']!='NULL'?$row['organization_id']:null;
            $address->address_attention = $row['attention']!='NULL'?$row['attention']:null;
            $address->address_region = $row['region']!='NULL'?$row['region']:null;

            $address ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Address')->first();
        $csv_source->records = Address::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //languages.csv
        $path = public_path('/HSDS/data/languages.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'languages.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Language::truncate();

        foreach ($csv_data as $row) {

            $language = new Language();

            $language->language_recordid =$row['id']!='NULL'?$row['id']:null;
            
            $language->language_location = $row['location_id']!='NULL'?$row['location_id']:null;
            $language->language_service = $row['service_id']!='NULL'?$row['service_id']:null;
            $language->language= $row['language']!='NULL'?$row['language']:null;
            
            $language->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Languages')->first();
        $csv_source->records = Language::count();
        $csv_source->syncdate = $date;
        $csv_source->save();


        //taxonomy.csv
        $path = public_path('/HSDS/data/taxonomy.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'taxonomy.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        foreach ($csv_data as $key => $row) {

            $taxonomy = Taxonomy::where('taxonomy_id', $row['id'])->first();

            if(!isset($taxonomy->taxonomy_id)){
                $taxonomy = new Taxonomy();
            }

            $taxonomy->taxonomy_recordid = $key+1;

            $taxonomy->taxonomy_id =$row['id']!='NULL'?$row['id']:null;
            $taxonomy->category_id =$row['id']!='NULL'?$row['id']:null;
            $taxonomy->taxonomy_name = $row['name']!='NULL'?$row['name']:null;
            $taxonomy->taxonomy_facet = $row['taxonomy_facet']!='NULL'?$row['taxonomy_facet']:null;
            $taxonomy->taxonomy_parent_recordid= $row['parent_id']!='NULL'?$row['parent_id']:null;
            $taxonomy->taxonomy_parent_name= $row['parent_name']!='NULL'?$row['parent_name']:null;
            $taxonomy->taxonomy_vocabulary= $row['vocabulary']!='NULL'?$row['vocabulary']:null;

            $taxonomy->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
        $csv_source->records = Taxonomy::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //services_taxonomy.csv
        $path = public_path('/HSDS/data/services_taxonomy.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'services_taxonomy.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Servicetaxonomy::truncate();

        foreach ($csv_data as $key => $row) {

            $service_taxonomy = new Servicetaxonomy();

            $service_taxonomy->taxonomy_recordid = $key+1;
            $service_taxonomy->service_recordid = $row['service_id']!='NULL'?$row['service_id']:null;
            $service_taxonomy->taxonomy_id =$row['taxonomy_id']!='NULL'?$row['taxonomy_id']:null;
            
            $service_taxonomy->taxonomy_detail = $row['taxonomy_detail']!='NULL'?$row['taxonomy_detail']:null;
           
            $service_taxonomy->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
        $csv_source->records = Servicetaxonomy::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //services_at_location.csv
        $path = public_path('/HSDS/data/services_at_location.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'services_at_location.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Servicelocation::truncate();

        foreach ($csv_data as $key => $row) {

            $service_location = new Servicelocation();

            $service_location->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
            $service_location->service_recordid =$row['service_id']!='NULL'?$row['service_id']:null;
                      
            $service_location->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
        $csv_source->records = Servicelocation::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //accessibility_for_disabilities.csv
        $path = public_path('/HSDS/data/accessibility_for_disabilities.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'accessibility_for_disabilities.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Accessibility::truncate();

        foreach ($csv_data as $key => $row) {

            $accessibility = new Accessibility();

            $accessibility->accessibility_recordid =$row['id']!='NULL'?$row['id']:null;
            $accessibility->accessibility_location = $row['location_id']!='NULL'?$row['location_id']:null;
            $accessibility->accessibility =$row['accessibility']!='NULL'?$row['accessibility']:null;
            ;   
            $accessibility->accessibility_details =$row['details']!='NULL'?$row['details']:null;
            ;       
            $accessibility->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
        $csv_source->records = Accessibility::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        //regular_schedules.csv
        $path = public_path('/HSDS/data/regular_schedules.csv');

        if (!file_exists($path)) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'regular_schedules.csv does not exist.',
            );
            return $response;
        }

        $data = Excel::load($path)->get();

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }

        Schedule::truncate();
        Serviceschedule::truncate();
        Locationschedule::truncate();

        foreach ($csv_data as $key => $row) {

            $schedule = new Schedule();

            $schedule->schedule_recordid= $key+1;
            $schedule->schedule_services = $row['service_id']!='NULL'?$row['service_id']:null;

            if($row['service_id']){
                $service_schedule = new Serviceschedule();
                $service_schedule->service_recordid = $row['service_id']!='NULL'?$row['service_id']:null;
                $service_schedule->schedule_recordid = $schedule->schedule_recordid;
                $service_schedule->save();

            }

            $schedule->schedule_days_of_week = $row['weekday']!='NULL'?$row['weekday']:null;
            $schedule->schedule_opens_at = $row['opens_at']!='NULL'?$row['opens_at']:null;
            $schedule->schedule_closes_at = $row['closes_at']!='NULL'?$row['closes_at']:null;
            $schedule->schedule_description = $row['original_text']!='NULL'?$row['original_text']:null;
            $schedule->schedule_locations = $row['location_id']!='NULL'?$row['location_id']:null;

            if($row['location_id']){
                $location_schedule = new Locationschedule();
                $location_schedule->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
                $location_schedule->schedule_recordid = $schedule->schedule_recordid;
                $location_schedule->save();

            }
                                     
            $schedule ->save();
          
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Regular_schedules')->first();
        $csv_source->records = Schedule::count();
        $csv_source->syncdate = $date;
        $csv_source->save();

        rename(public_path('/HSDS/data/services.csv'), public_path('/csv/services.csv'));
        rename(public_path('/HSDS/data/locations.csv'), public_path('/csv/locations.csv'));
        rename(public_path('/HSDS/data/organizations.csv'), public_path('/csv/organizations.csv'));
        rename(public_path('/HSDS/data/contacts.csv'), public_path('/csv/contacts.csv'));
        rename(public_path('/HSDS/data/phones.csv'), public_path('/csv/phones.csv'));
        rename(public_path('/HSDS/data/physical_addresses.csv'), public_path('/csv/physical_addresses.csv'));
        rename(public_path('/HSDS/data/languages.csv'), public_path('/csv/languages.csv'));
        rename(public_path('/HSDS/data/taxonomy.csv'), public_path('/csv/taxonomy.csv'));
        rename(public_path('/HSDS/data/services_taxonomy.csv'), public_path('/csv/services_taxonomy.csv'));
        rename(public_path('/HSDS/data/services_at_location.csv'), public_path('/csv/services_at_location.csv'));
        rename(public_path('/HSDS/data/accessibility_for_disabilities.csv'), public_path('/csv/accessibility_for_disabilities.csv'));
        rename(public_path('/HSDS/data/regular_schedules.csv'), public_path('/csv/regular_schedules.csv'));
    }

    
}
