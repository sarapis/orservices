<?php

namespace App\Http\Controllers\backEnd;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\AutoSyncAirtable;
use App\Model\Contact;
use App\Model\CSV_Source;
use App\Model\HsdsApiKey;
use App\Model\Language;
use App\Model\Layout;
use App\Model\Location;
use App\Model\MetaFilter;
use App\Model\Organization;
use App\Model\Page;
use App\Model\Phone;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceLocation;
use App\Model\ServiceTaxonomy;
use App\Model\Source_data;
use App\Model\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PagesController extends Controller
{
    protected function validator(Request $request, $id = '')
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = Page::all();

        return view('backEnd.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backEnd.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($this->validator($request, Auth::id())->fails()) {

            return redirect()->back()
                ->withErrors($this->validator($request))
                ->withInput();
        }

        Page::create($request->all());

        Session::flash('message', 'Page added!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        if ($this->validator($request, Auth::id())->fails()) {

            return redirect()->back()
                ->withErrors($this->validator($request))
                ->withInput();
        }

        $page = Page::findOrFail($id);
        $page->update($request->all());

        Session::flash('message', 'Page updated!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        $page->delete();

        Session::flash('message', 'Page deleted!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    public function import()
    {
        $airtables = Airtables::all();
        $airtablekeyinfo = Airtablekeyinfo::find(1);
        $csvs = CSV_Source::all();
        $source_data = Source_data::find(1);
        $autosync = AutoSyncAirtable::find(1);

        return view('backEnd.datasync', compact('airtables', 'csvs', 'source_data', 'autosync', 'airtablekeyinfo'));
    }

    public function export(Request $request)
    {
        try {
            $hsds_api_key = HsdsApiKey::find(1);
            $url_path = 'http://34.222.120.131/datapackages?auth_key=' . $hsds_api_key->hsds_api_key;
            return view('backEnd.pages.export', compact('hsds_api_key', 'url_path'));
        } catch (\Throwable $th) {
            dd($th);
        }

        // return view('backEnd.pages.export');
    }
    public function update_hsds_api_key(Request $request)
    {
        $hsds_api_key = Hsdsapikey::find(1);
        $new_hsds_api_key = $request->input('import_hsds_api_key');
        $hsds_api_key->hsds_api_key = $new_hsds_api_key;
        $hsds_api_key->save();
        return redirect('export');
    }

    public function export_hsds_zip_file()
    {

        $path_csv_export = public_path('csv_export/datapackage/');
        $public_path = public_path('/');

        $table_service = Service::all();
        $file_service = fopen('services.csv', 'w');
        fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
        foreach ($table_service as $row) {
            fputcsv($file_service, $row->toArray());
        }
        fclose($file_service);
        if (file_exists($path_csv_export . 'services.csv')) {
            unlink($path_csv_export . 'services.csv');
        }
        rename($public_path . 'services.csv', $path_csv_export . 'services.csv');


        $table_location = Location::all();
        $file_location = fopen('locations.csv', 'w');
        fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));
        foreach ($table_location as $row) {
            fputcsv($file_location, $row->toArray());
        }
        fclose($file_location);
        if (file_exists($path_csv_export . 'locations.csv')) {
            unlink($path_csv_export . 'locations.csv');
        }
        rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');

        $table_organization = Organization::all();
        $file_organization = fopen('organizations.csv', 'w');
        fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
        foreach ($table_organization as $row) {
            fputcsv($file_organization, $row->toArray());
        }
        fclose($file_organization);
        if (file_exists($path_csv_export . 'organizations.csv')) {
            unlink($path_csv_export . 'organizations.csv');
        }
        rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');

        $table_contact = Contact::all();
        $file_contact = fopen('contacts.csv', 'w');
        fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
        foreach ($table_contact as $row) {
            fputcsv($file_contact, $row->toArray());
        }
        fclose($file_contact);
        if (file_exists($path_csv_export . 'contacts.csv')) {
            unlink($path_csv_export . 'contacts.csv');
        }
        rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');

        $table_phone = Phone::all();
        $file_phone = fopen('phones.csv', 'w');
        fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
        foreach ($table_phone as $row) {
            fputcsv($file_phone, $row->toArray());
        }
        fclose($file_phone);
        if (file_exists($path_csv_export . 'phones.csv')) {
            unlink($path_csv_export . 'phones.csv');
        }
        rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');

        $table_address = Address::all();
        $file_address = fopen('physical_addresses.csv', 'w');
        fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
        foreach ($table_address as $row) {
            fputcsv($file_address, $row->toArray());
        }
        fclose($file_address);
        if (file_exists($path_csv_export . 'physical_addresses.csv')) {
            unlink($path_csv_export . 'physical_addresses.csv');
        }
        rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');

        $table_language = Language::all();
        $file_language = fopen('languages.csv', 'w');
        fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
        foreach ($table_language as $row) {
            fputcsv($file_language, $row->toArray());
        }
        fclose($file_language);
        if (file_exists($path_csv_export . 'languages.csv')) {
            unlink($path_csv_export . 'languages.csv');
        }
        rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');

        $table_taxonomy = Taxonomy::all();
        $file_taxonomy = fopen('taxonomy.csv', 'w');
        fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
        foreach ($table_taxonomy as $row) {
            fputcsv($file_taxonomy, $row->toArray());
        }
        fclose($file_taxonomy);
        if (file_exists($path_csv_export . 'taxonomy.csv')) {
            unlink($path_csv_export . 'taxonomy.csv');
        }
        rename($public_path . 'taxonomy.csv', $path_csv_export . 'taxonomy.csv');

        $table_servicetaxonomy = ServiceTaxonomy::all();
        $file_servicetaxonomy = fopen('services_taxonomy.csv', 'w');
        fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
        foreach ($table_servicetaxonomy as $row) {
            fputcsv($file_servicetaxonomy, $row->toArray());
        }
        fclose($file_servicetaxonomy);
        if (file_exists($path_csv_export . 'services_taxonomy.csv')) {
            unlink($path_csv_export . 'services_taxonomy.csv');
        }
        rename($public_path . 'services_taxonomy.csv', $path_csv_export . 'services_taxonomy.csv');

        $table_servicelocation = ServiceLocation::all();
        $file_servicelocation = fopen('services_at_location.csv', 'w');
        fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
        foreach ($table_servicelocation as $row) {
            fputcsv($file_servicelocation, $row->toArray());
        }
        fclose($file_servicelocation);
        if (file_exists($path_csv_export . 'services_at_location.csv')) {
            unlink($path_csv_export . 'services_at_location.csv');
        }
        rename($public_path . 'services_at_location.csv', $path_csv_export . 'services_at_location.csv');

        $table_accessibility = Accessibility::all();
        $file_accessibility = fopen('accessibility_for_disabilities.csv', 'w');
        fputcsv($file_accessibility, array('ID', 'id', 'location_id', 'accessibility', 'details'));
        foreach ($table_accessibility as $row) {
            fputcsv($file_accessibility, $row->toArray());
        }
        fclose($file_accessibility);
        if (file_exists($path_csv_export . 'accessibility_for_disabilities.csv')) {
            unlink($path_csv_export . 'accessibility_for_disabilities.csv');
        }
        rename($public_path . 'accessibility_for_disabilities.csv', $path_csv_export . 'accessibility_for_disabilities.csv');

        $table_schedule = Schedule::all();
        $file_schedule = fopen('regular_schedules.csv', 'w');
        fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
        foreach ($table_schedule as $row) {
            fputcsv($file_schedule, $row->toArray());
        }
        fclose($file_schedule);
        if (file_exists($path_csv_export . 'regular_schedules.csv')) {
            unlink($path_csv_export . 'regular_schedules.csv');
        }
        rename($public_path . 'regular_schedules.csv', $path_csv_export . 'regular_schedules.csv');
        $zip_file = 'datapackage.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = $path_csv_export;
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file) {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
                $relativePath = 'datapackage/' . substr($filePath, strlen($path));

                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return response()->download($zip_file);
    }

    public function datapackages(Request $request)
    {
        $hsds_api_key = Hsdsapikey::find(1);
        $auth_key = $hsds_api_key->hsds_api_key;
        if ($request->input('auth_key') == $auth_key) {
            $path_csv_export = public_path('/csv_export/datapackage/');
            $public_path = public_path('/');

            $table_service = Service::all();
            $file_service = fopen('services.csv', 'w');
            fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
            foreach ($table_service as $row) {
                fputcsv($file_service, $row->toArray());
            }
            fclose($file_service);
            if (file_exists($path_csv_export . 'services.csv')) {
                unlink($path_csv_export . 'services.csv');
            }
            rename($public_path . 'services.csv', $path_csv_export . 'services.csv');


            $table_location = Location::all();
            $file_location = fopen('locations.csv', 'w');
            fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));
            foreach ($table_location as $row) {
                fputcsv($file_location, $row->toArray());
            }
            fclose($file_location);
            if (file_exists($path_csv_export . 'locations.csv')) {
                unlink($path_csv_export . 'locations.csv');
            }
            rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');

            $table_organization = Organization::all();
            $file_organization = fopen('organizations.csv', 'w');
            fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
            foreach ($table_organization as $row) {
                fputcsv($file_organization, $row->toArray());
            }
            fclose($file_organization);
            if (file_exists($path_csv_export . 'organizations.csv')) {
                unlink($path_csv_export . 'organizations.csv');
            }
            rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');

            $table_contact = Contact::all();
            $file_contact = fopen('contacts.csv', 'w');
            fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
            foreach ($table_contact as $row) {
                fputcsv($file_contact, $row->toArray());
            }
            fclose($file_contact);
            if (file_exists($path_csv_export . 'contacts.csv')) {
                unlink($path_csv_export . 'contacts.csv');
            }
            rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');

            $table_phone = Phone::all();
            $file_phone = fopen('phones.csv', 'w');
            fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
            foreach ($table_phone as $row) {
                fputcsv($file_phone, $row->toArray());
            }
            fclose($file_phone);
            if (file_exists($path_csv_export . 'phones.csv')) {
                unlink($path_csv_export . 'phones.csv');
            }
            rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');

            $table_address = Address::all();
            $file_address = fopen('physical_addresses.csv', 'w');
            fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
            foreach ($table_address as $row) {
                fputcsv($file_address, $row->toArray());
            }
            fclose($file_address);
            if (file_exists($path_csv_export . 'physical_addresses.csv')) {
                unlink($path_csv_export . 'physical_addresses.csv');
            }
            rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');

            $table_language = Language::all();
            $file_language = fopen('languages.csv', 'w');
            fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
            foreach ($table_language as $row) {
                fputcsv($file_language, $row->toArray());
            }
            fclose($file_language);
            if (file_exists($path_csv_export . 'languages.csv')) {
                unlink($path_csv_export . 'languages.csv');
            }
            rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');

            $table_taxonomy = Taxonomy::all();
            $file_taxonomy = fopen('taxonomy.csv', 'w');
            fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
            foreach ($table_taxonomy as $row) {
                fputcsv($file_taxonomy, $row->toArray());
            }
            fclose($file_taxonomy);
            if (file_exists($path_csv_export . 'taxonomy.csv')) {
                unlink($path_csv_export . 'taxonomy.csv');
            }
            rename($public_path . 'taxonomy.csv', $path_csv_export . 'taxonomy.csv');

            $table_servicetaxonomy = Servicetaxonomy::all();
            $file_servicetaxonomy = fopen('services_taxonomy.csv', 'w');
            fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
            foreach ($table_servicetaxonomy as $row) {
                fputcsv($file_servicetaxonomy, $row->toArray());
            }
            fclose($file_servicetaxonomy);
            if (file_exists($path_csv_export . 'services_taxonomy.csv')) {
                unlink($path_csv_export . 'services_taxonomy.csv');
            }
            rename($public_path . 'services_taxonomy.csv', $path_csv_export . 'services_taxonomy.csv');

            $table_servicelocation = Servicelocation::all();
            $file_servicelocation = fopen('services_at_location.csv', 'w');
            fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
            foreach ($table_servicelocation as $row) {
                fputcsv($file_servicelocation, $row->toArray());
            }
            fclose($file_servicelocation);
            if (file_exists($path_csv_export . 'services_at_location.csv')) {
                unlink($path_csv_export . 'services_at_location.csv');
            }
            rename($public_path . 'services_at_location.csv', $path_csv_export . 'services_at_location.csv');

            $table_accessibility = Accessibility::all();
            $file_accessibility = fopen('accessibility_for_disabilities.csv', 'w');
            fputcsv($file_accessibility, array('ID', 'id', 'location_id', 'accessibility', 'details'));
            foreach ($table_accessibility as $row) {
                fputcsv($file_accessibility, $row->toArray());
            }
            fclose($file_accessibility);
            if (file_exists($path_csv_export . 'accessibility_for_disabilities.csv')) {
                unlink($path_csv_export . 'accessibility_for_disabilities.csv');
            }
            rename($public_path . 'accessibility_for_disabilities.csv', $path_csv_export . 'accessibility_for_disabilities.csv');

            $table_schedule = Schedule::all();
            $file_schedule = fopen('regular_schedules.csv', 'w');
            fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
            foreach ($table_schedule as $row) {
                fputcsv($file_schedule, $row->toArray());
            }
            fclose($file_schedule);
            if (file_exists($path_csv_export . 'regular_schedules.csv')) {
                unlink($path_csv_export . 'regular_schedules.csv');
            }
            rename($public_path . 'regular_schedules.csv', $path_csv_export . 'regular_schedules.csv');

            $zip_file = 'datapackage.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $path = $path_csv_export;
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $name => $file) {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = 'datapackage/' . substr($filePath, strlen($path));
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return response()->download($zip_file);
        } else {
            return response()->json('Failed', 500);
        }
    }

    public function metafilter()
    {
        $meta = Layout::find(1);
        $source_data = Source_data::find(1);
        $metafilters = MetaFilter::all();
        $service_count = 0;
        foreach ($metafilters as $key => $metafilter) {
            $values = explode(',', $metafilter->values);

            $values = array_filter($values);

            $service_count += count($values);
        }

        return view('backEnd.pages.metafilter', compact('meta', 'source_data', 'metafilters', 'service_count'));
    }

    public function metafilter_save($id, Request $request)
    {
        try {
            $meta = Layout::find($id);

            if ($request->input('meta_filter_activate') == 'checked') {
                $meta->meta_filter_activate = 1;
            } else {
                $meta->meta_filter_activate = 0;
            }

            $meta->meta_filter_on_label = $request->meta_filter_on_label;
            $meta->meta_filter_off_label = $request->meta_filter_off_label;
            $meta->save();
            Session::flash('message', 'Saved successfully!');
            Session::flash('status', 'success');
            return redirect('meta_filter');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('meta_filter');
        }
    }

    public function taxonomy_filter()
    {
        $taxonomies = Taxonomy::all();
        $source_data = Source_data::find(1);
        $checked_taxonomies = [];

        return view('backEnd.pages.metafilter_taxonomy', compact('taxonomies', 'source_data', 'checked_taxonomies'))->render();
    }

    public function metafilter_edit($id, Request $request)
    {
        $source_data = Source_data::find(1);
        $metafilter = MetaFilter::find($id);
        if ($metafilter->facet = 'Taxonomy') {

            $taxonomies = Taxonomy::all();
            $checked_taxonomies = explode(",", $metafilter->values);

            return view('backEnd.pages.metafilter_taxonomy', compact('taxonomies', 'source_data', 'checked_taxonomies'))->render();
        } else {

            $addresses = Address::orderBy('id')->get();
            $checked_addresses = explode(",", $metafilter->values);

            return view('backEnd.pages.metafilter_address', compact('addresses', 'source_data', 'checked_addresses'))->render();
        }
    }

    public function postal_filter()
    {
        $addresses = Address::orderBy('id')->get();
        $source_data = Source_data::find(1);

        return view('backEnd.pages.metafilter_address', compact('addresses', 'source_data'))->render();
    }

    public function operation(Request $request)
    {
        $id = $request->input('status');
        if ($id == 0) {
            $metafilter = new Metafilter;
            $metafilter->operations = $request->input('operation');
            $metafilter->facet = $request->input('facet');
            $metafilter->method = $request->input('method');
            if ($request->hasFile('csv_import')) {
                $path = $request->file('csv_import')->getRealPath();

                // $data = Excel::load($path)->get();

                $filename = $request->file('csv_import')->getClientOriginalName();
                $request->file('csv_import')->move(public_path('/csv/'), $filename);
            }
            if ($request->input('table_records') != null) {
                $metafilter->values = implode(",", $request->input('table_records'));
            }

            $metafilter->save();
        } else {
            $metafilter = MetaFilter::where('id', '=', $id)->first();
            $metafilter->operations = $request->input('operation');
            $metafilter->facet = $request->input('facet');
            $metafilter->method = $request->input('method');
            if ($request->input('table_records') != null) {
                $metafilter->values = implode(",", $request->input('table_records'));
            }

            $metafilter->save();
        }

        return redirect('meta_filter');
    }

    public function delete_operation(Request $request)
    {
        $id = $request->input('id');

        $metafilter = MetaFilter::find($id);
        $metafilter->delete();

        return redirect('meta_filter');
    }
}
