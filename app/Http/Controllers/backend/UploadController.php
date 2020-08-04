<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Imports\AccessibilityImport;
use App\Imports\AddressImport;
use App\Imports\ContactImport;
use App\Imports\LanguageImport;
use App\Imports\LocationImport;
use App\Imports\OrganizationImport;
use App\Imports\PhoneImport;
use App\Imports\ScheduleImport;
use App\Imports\ServiceLocationImport;
use App\Imports\Services;
use App\Imports\ServiceTaxonomyImport;
use App\Imports\TaxonomyImport;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Contact;
use App\Model\CSV_Source;
use App\Model\Language;
use App\Model\Location;
use App\Model\LocationAddress;
use App\Model\LocationPhone;
use App\Model\LocationSchedule;
use App\Model\Organization;
use App\Model\OrganizationDetail;
use App\Model\Phone;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceContact;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServicePhone;
use App\Model\ServiceSchedule;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use ZanySoft\Zip\Zip;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function zip(Request $request)
    {
        try {
            $extension = $request->file('file_zip')->getClientOriginalExtension();

            if ($extension != 'zip') {
                $response = array(
                    'status' => 'error',
                    'result' => 'This File is not zip file.',
                );
                return $response;
            }

            $path = $request->file('file_zip')->getRealPath();
            $zip = Zip::open($request->file('file_zip'));

            // Zip::make($path)->extractTo('HSDS');
            $zip->extract(public_path('HSDS'));

            $filename =  $request->file('file_zip')->getClientOriginalName();
            $request->file('file_zip')->move(public_path('/zip/'), $filename);
            
            $path = public_path('/HSDS/data/services.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'services.csv does not exist.',
                );
                return $response;
            }


            Service::truncate();
            ServiceOrganization::truncate();

            Excel::import(new Services, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services')->first();
            $csv_source->records = Service::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //locations.csv
            $path = public_path('/HSDS/data/locations.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'locations.csv does not exist.',
                );
                return $response;
            }

            // $data = Excel::load($path)->get();
            Location::truncate();

            Excel::import(new LocationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Locations')->first();
            $csv_source->records = Location::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //organizations.csv
            $path = public_path('/HSDS/data/organizations.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'organizations.csv does not exist.',
                );
                return $response;
            }

            Organization::truncate();
            OrganizationDetail::truncate();

            Excel::import(new OrganizationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Organizations')->first();
            $csv_source->records = Organization::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //contacts.csv
            $path = public_path('/HSDS/data/contacts.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'contacts.csv does not exist.',
                );
                return $response;
            }
            Contact::truncate();
            ServiceContact::truncate();

            Excel::import(new ContactImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Contacts')->first();
            $csv_source->records = Contact::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //phones.csv
            $path = public_path('/HSDS/data/phones.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'phones.csv does not exist.',
                );
                return $response;
            }
            Phone::truncate();
            ServicePhone::truncate();
            LocationPhone::truncate();

            Excel::import(new PhoneImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Phones')->first();
            $csv_source->records = Phone::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //physical_addresses.csv
            $path = public_path('/HSDS/data/physical_addresses.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'physical_addresses.csv does not exist.',
                );
                return $response;
            }
            Address::truncate();
            LocationAddress::truncate();
            ServiceAddress::truncate();

            Excel::import(new AddressImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Address')->first();
            $csv_source->records = Address::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //languages.csv
            $path = public_path('/HSDS/data/languages.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'languages.csv does not exist.',
                );
                return $response;
            }
            Language::truncate();

            Excel::import(new LanguageImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Languages')->first();
            $csv_source->records = Language::count();
            $csv_source->syncdate = $date;
            $csv_source->save();


            //taxonomy.csv
            $path = public_path('/HSDS/data/taxonomy.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'taxonomy.csv does not exist.',
                );
                return $response;
            }

            Excel::import(new TaxonomyImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
            $csv_source->records = Taxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //services_taxonomy.csv
            $path = public_path('/HSDS/data/services_taxonomy.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'services_taxonomy.csv does not exist.',
                );
                return $response;
            }

            ServiceTaxonomy::truncate();

            Excel::import(new ServiceTaxonomyImport, $path);

            $date = date("Y/m/d H:i:s");
            $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
            $csv_source->records = ServiceTaxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //services_at_location.csv
            $path = public_path('/HSDS/data/services_at_location.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'services_at_location.csv does not exist.',
                );
                return $response;
            }

            ServiceLocation::truncate();

            Excel::import(new ServiceLocationImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
            $csv_source->records = ServiceLocation::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //accessibility_for_disabilities.csv
            $path = public_path('/HSDS/data/accessibility_for_disabilities.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'accessibility_for_disabilities.csv does not exist.',
                );
                return $response;
            }
            Accessibility::truncate();

            Excel::import(new AccessibilityImport, $path);

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Accessibility_for_disabilites')->first();
            $csv_source->records = Accessibility::count();
            $csv_source->syncdate = $date;
            $csv_source->save();

            //regular_schedules.csv
            $path = public_path('/HSDS/data/regular_schedules.csv');

            if (!file_exists($path)) {
                $response = array(
                    'status' => 'error',
                    'result' => 'regular_schedules.csv does not exist.',
                );
                return $response;
            }

            Schedule::truncate();
            ServiceSchedule::truncate();
            LocationSchedule::truncate();

            Excel::import(new ScheduleImport, $path);

            $date = Carbon::now();
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
            return "import completed";
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
