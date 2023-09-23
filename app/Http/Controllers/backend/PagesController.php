<?php

namespace App\Http\Controllers\backEnd;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
use App\Model\Accessibility;
use App\Model\Address;
use App\Model\Airtable_v2;
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
use App\Model\OrganizationStatus;
use App\Model\OrganizationTag;
use App\Model\Page;
use App\Model\Phone;
use App\Model\Program;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServiceStatus;
use App\Model\ServiceTag;
use App\Model\ServiceTaxonomy;
use App\Model\Source_data;
use App\Model\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Constraint\FileExists;

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
        $airtables_v2 = Airtable_v2::all();
        $airtablekeyinfo = Airtablekeyinfo::find(1);
        $airtablekeyinfo_v2 = Airtablekeyinfo::find(2);
        $csvs = CSV_Source::all();
        $source_data = Source_data::find(1);
        $autosync = AutoSyncAirtable::find(1);

        return view('backEnd.datasync', compact('airtables', 'csvs', 'source_data', 'autosync', 'airtablekeyinfo', 'airtables_v2', 'airtablekeyinfo_v2'));
    }

    public function export(Request $request)
    {
        try {
            $hsds_api_key = HsdsApiKey::find(1);
            $download_all =  ['download_all' => 'Download All'];
            // $organization_tags = Organization::whereNotNull('organization_tag')->pluck('organization_tag', 'organization_tag')->unique()->toArray();
            $organization_tags_list = Organization::whereNotNull('organization_tag')->select("organization_tag")->distinct()->get();

            $tag_list = ['download_all' => 'Download All'];
            foreach ($organization_tags_list as $key => $value) {
                $tags = explode(",", trim($value->organization_tag));
                $tag_list = array_merge($tag_list, $tags);
            }
            $tag_list = array_unique($tag_list);
            $organization_tags = [];
            foreach ($tag_list as $key => $value) {
                $organization_tags[$value] = $value;
            }
            // $organization_tags = array_merge($download_all, $organization_tags);
            $url_path = url('/datapackages?auth_key=' . $hsds_api_key->hsds_api_key);
            return view('backEnd.pages.export', compact('hsds_api_key', 'url_path', 'organization_tags'));
        } catch (\Throwable $th) {
            Log::error('Error in pagecontroller export : ' . $th);
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

    public function export_hsds_zip_file(Request $request)
    {
        $path_csv_export = public_path('csv_export/datapackage/');
        $public_path = public_path('/');
        $organization_ids = [];
        if ($request->has('organization_tags')) {
            if (in_array('download_all', $request->organization_tags)) {
                $organization_ids = Organization::pluck('organization_recordid')->toArray();
            } else {
                $organization_tags = $request->organization_tags;
                $organization_ids = Organization::where(function ($query) use ($organization_tags) {
                    foreach ($organization_tags as $keyword) {
                        $query = $query->orWhere('organization_tag', 'LIKE', "%$keyword%");
                    }
                    return $query;
                })->pluck('organization_recordid')->toArray();
            }
        }
        if (!empty($organization_ids)) {
            $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organization_ids)->pluck('service_recordid');
            $table_service = Service::whereIn('service_recordid', $organization_service_recordid)->get();
        } else {
            $table_service = Service::all();
        }
        // if (file_exists(public_path('datapackage.zip'))) {
        //     unlink(public_path('datapackage.zip'));
        // }
        $file_service = fopen('services.csv', 'w');
        fputcsv($file_service, array('id', 'organization_id', 'program_id', 'name', 'alternate_name', 'description', 'url', 'email', 'status', 'interpretation_services', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses'));
        // fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
        foreach ($table_service as $row) {

            if ($row->service_organization) {
                $serviceArray = [
                    'id' => $row->id,
                    'organization_id' => $row->service_organization,
                    'program_id' => $row->service_program,
                    'name' => $row->service_name,
                    'alternate_name' => $row->service_alternate_name,
                    'description' => $row->service_description,
                    'url' => $row->service_url && strpos($row->service_url, '://') !== false ? trim($row->service_url) : ($row->service_url ? 'http://' . trim($row->service_url) : ''),
                    'email' => trim($row->service_email),
                    'status' => $row->service_status == 'Verified' ? 'active' : 'inactive',
                    'interpretation_services' => '',
                    'application_process' => $row->service_application_process,
                    'wait_time' => $row->service_wait_time,
                    'fees' => $row->service_fees,
                    'accreditations' => $row->service_accreditations,
                    'licenses' => $row->service_licenses,
                    // 'service_recordid' => $row->service_recordid,
                    // 'taxonomy_ids' => $row->service_taxonomy,
                ];
                fputcsv($file_service, $serviceArray);
            }
        }
        fclose($file_service);
        if (file_exists($path_csv_export . 'services.csv')) {
            unlink($path_csv_export . 'services.csv');
        }
        rename($public_path . 'services.csv', $path_csv_export . 'services.csv');

        if (!empty($organization_ids)) {
            $table_location = Location::whereIn('location_organization', $organization_ids)->get();
        } else {
            $table_location = Location::all();
        }
        $file_location = fopen('locations.csv', 'w');
        fputcsv($file_location, array('id', 'organization_id', 'name', 'alternate_name', 'description', 'transportation', 'latitude', 'longitude'));

        // fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));

        foreach ($table_location as $row) {

            $locationArray = [
                'id' => $row->location_recordid,
                'organization_id' => $row->location_organization,
                'name' => $row->location_name,
                'alternate_name' => $row->location_alternate_name,
                'description' => $row->location_description,
                'transportation' => $row->location_transportation,
                'latitude' => $row->location_latitude,
                'longitude' => $row->location_longitude,
                // 'location_recordid' => $row->location_recordid,
            ];
            // fputcsv($file_location, $row->toArray());
            fputcsv($file_location, $locationArray);
        }
        fclose($file_location);
        if (file_exists($path_csv_export . 'locations.csv')) {
            unlink($path_csv_export . 'locations.csv');
        }
        rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');

        if (!empty($organization_ids)) {
            $table_organization = Organization::whereIn('organization_recordid', $organization_ids)->get();
        } else {
            $table_organization = Organization::all();
        }
        $file_organization = fopen('organizations.csv', 'w');

        fputcsv($file_organization, array('id', 'name', 'alternate_name', 'description', 'email', 'url', 'tax_status', 'tax_id', 'year_incorporated', 'legal_status'));

        // fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
        foreach ($table_organization as $row) {
            if ($row->organization_description) {
                $organizationArray = [
                    'id' => strval($row->organization_recordid),
                    'name' => $row->organization_name,
                    'alternate_name' => $row->organization_alternate_name,
                    'description' => $row->organization_description,
                    'email' => trim($row->organization_email),
                    'url' => $row->organization_url && strpos($row->organization_url, '://') !== false ? $row->organization_url : ($row->organization_url ? 'http://' . $row->organization_url : ''),
                    'tax_status' => $row->organization_tax_status,
                    'tax_id' => $row->organization_tax_id,
                    'year_incorporated' => $row->organization_year_incorporated,
                    'legal_status' => $row->organization_legal_status,
                    // 'organization_recordid' => $row->organization_recordid,
                ];
                fputcsv($file_organization, $organizationArray);
            }
            // fputcsv($file_organization, $row->toArray());
        }
        fclose($file_organization);
        if (file_exists($path_csv_export . 'organizations.csv')) {
            unlink($path_csv_export . 'organizations.csv');
        }
        rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');

        if (!empty($organization_ids)) {
            $table_contact = Contact::whereIn('contact_organizations', $organization_ids)->get();
        } else {
            $table_contact = Contact::all();
        }
        $file_contact = fopen('contacts.csv', 'w');
        fputcsv($file_contact, array('id', 'organization_id', 'service_id', 'service_at_location_id', 'name', 'title', 'department', 'email'));
        // fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
        foreach ($table_contact as $row) {

            $locationArray = [
                'id' => $row->id,
                'organization_id' => $row->contact_organizations,
                'service_id' => $row->contact_services,
                'service_at_location_id' => '',
                'name' => $row->contact_name,
                'title' => $row->contact_title,
                'department' => $row->contact_department,
                'email' => ($row->contact_email && $row->contact_email[-1] == '.') ? substr_replace($row->contact_email, "", -1) :  trim($row->contact_email),
                // 'contact_recordid' => $row->contact_recordid,
            ];
            fputcsv($file_contact, $locationArray);
            // fputcsv($file_contact, $row->toArray());
        }
        fclose($file_contact);
        if (file_exists($path_csv_export . 'contacts.csv')) {
            unlink($path_csv_export . 'contacts.csv');
        }
        rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');
        if (!empty($organization_ids)) {
            $table_phone = Phone::whereIn('phone_organizations', $organization_ids)->get();
        } else {
            $table_phone = Phone::all();
        }
        $file_phone = fopen('phones.csv', 'w');
        fputcsv($file_phone, array('id', 'location_id', 'service_id', 'organization_id', 'contact_id', 'service_at_location_id', 'number', 'extension', 'type', 'language', 'description', 'department'));
        // fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
        foreach ($table_phone as $row) {
            if ($row->phone_number) {
                $phoneArray = [
                    'id' => $row->id,
                    'location_id' => $row->phone_locations,
                    'service_id' => $row->phone_services,
                    'organization_id' => $row->phone_organizations,
                    'contact_id' => $row->phone_contacts,
                    'service_at_location_id' => '',
                    'number' => $row->phone_number,
                    'extension' => is_numeric($row->phone_extension) ? $row->phone_extension : '',
                    'type' => $row->phone_type,
                    'language' => $row->phone_language,
                    'description' => $row->phone_description,
                    'department' => '',
                    // 'phone_recordid' => $row->phone_recordid,
                ];
                fputcsv($file_phone, $phoneArray);
            }
            // fputcsv($file_phone, $row->toArray());
        }
        fclose($file_phone);
        if (file_exists($path_csv_export . 'phones.csv')) {
            unlink($path_csv_export . 'phones.csv');
        }
        rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');

        $table_address = Address::all();
        $file_address = fopen('physical_addresses.csv', 'w');
        fputcsv($file_address, array('id', 'location_id', 'attention', 'address_1', 'address_2', 'address_3', 'address_4', 'city', 'region', 'state_province', 'postal_code', 'country'));
        // fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
        foreach ($table_address as $row) {
            if ($row->address_1 && $row->address_city && $row->address_state_province && $row->address_postal_code && $row->address_country) {
                $locationIds = explode(',', $row->address_locations);
                if (count($locationIds) > 0) {
                    foreach ($locationIds as $key => $value) {
                        $addressArray = [
                            'id' => $row->id,
                            'location_id' => $value,
                            'attention' => $row->address_attention,
                            'address_1' => $row->address_1,
                            'address_2' => $row->address_2,
                            'address_3' => '',
                            'address_4' => '',
                            'city' => $row->address_city,
                            'region' => $row->address_region,
                            'state_province' => $row->address_state_province,
                            'postal_code' => $row->address_postal_code,
                            'country' => $row->address_country,
                        ];
                        fputcsv($file_address, $addressArray);
                    }
                } else {
                    $addressArray = [
                        'id' => $row->id,
                        'location_id' => $row->address_locations,
                        'attention' => $row->address_attention,
                        'address_1' => $row->address_1,
                        'address_2' => $row->address_2,
                        'address_3' => '',
                        'address_4' => '',
                        'city' => $row->address_city,
                        'region' => $row->address_region,
                        'state_province' => $row->address_state_province,
                        'postal_code' => $row->address_postal_code,
                        'country' => $row->address_country,
                    ];
                    fputcsv($file_address, $addressArray);
                }
            }
        }
        fclose($file_address);
        if (file_exists($path_csv_export . 'physical_addresses.csv')) {
            unlink($path_csv_export . 'physical_addresses.csv');
        }
        rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');

        $table_language = Language::all();
        $file_language = fopen('languages.csv', 'w');
        fputcsv($file_language, array('id', 'service_id', 'location_id', 'language'));
        // fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
        foreach ($table_language as $row) {

            $langaugeArray = [
                'id' => $row->id,
                'service_id' => $row->language_service,
                'location_id' => $row->language_location,
                'language' => $row->language,
                // 'language_recordid' => $row->language_recordid,
            ];
            fputcsv($file_language, $langaugeArray);
            // fputcsv($file_language, $row->toArray());
        }
        fclose($file_language);
        if (file_exists($path_csv_export . 'languages.csv')) {
            unlink($path_csv_export . 'languages.csv');
        }
        rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');

        $table_taxonomy = Taxonomy::all();
        $file_taxonomy = fopen('taxonomy_terms.csv', 'w');
        // fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
        fputcsv($file_taxonomy, array('id', 'term', 'description', 'parent_id', 'taxonomy', 'language'));
        foreach ($table_taxonomy as $row) {
            // if ($row->taxonomy_name && $row->taxonomy_x_description) {
            $taxonomyArray = [
                'id' => $row->taxonomy_recordid,
                'term' => $row->taxonomy_name,
                'description' => $row->taxonomy_x_description,
                'parent_id' => $row->taxonomy_parent_name,
                'taxonomy' => $row->taxonomy,
                'language' => '',
                // 'x_taxonomies' => $row->x_taxonomies,
                // 'taxonomy_x_notes' => $row->taxonomy_x_notes,
                // 'badge_color' => $row->badge_color,
                // 'taxonomy_recordid' => $row->taxonomy_recordid,
                // 'vocabulary' => $row->badge_color,
            ];
            fputcsv($file_taxonomy, $taxonomyArray);
            // fputcsv($file_taxonomy, $row->toArray());
            // }
        }
        fclose($file_taxonomy);
        if (file_exists($path_csv_export . 'taxonomy_terms.csv')) {
            unlink($path_csv_export . 'taxonomy_terms.csv');
        }
        rename($public_path . 'taxonomy_terms.csv', $path_csv_export . 'taxonomy_terms.csv');

        $table_servicetaxonomy = ServiceTaxonomy::all();
        $file_servicetaxonomy = fopen('service_attributes.csv', 'w');
        fputcsv($file_servicetaxonomy, array('id', 'service_id', 'taxonomy_term_id'));
        // fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
        foreach ($table_servicetaxonomy as $row) {
            if ($row->service_recordid) {
                $ServiceTaxonomyArray = [
                    'id' => $row->id,
                    'service_id' => $row->service_recordid,
                    'taxonomy_term_id' => $row->taxonomy_recordid,
                    // 'taxonomy_detail' => $row->taxonomy_detail,
                ];
                fputcsv($file_servicetaxonomy, $ServiceTaxonomyArray);
                // fputcsv($file_servicetaxonomy, $row->toArray());
            }
        }
        fclose($file_servicetaxonomy);
        if (file_exists($path_csv_export . 'service_attributes.csv')) {
            unlink($path_csv_export . 'service_attributes.csv');
        }
        rename($public_path . 'service_attributes.csv', $path_csv_export . 'service_attributes.csv');

        $table_servicelocation = ServiceLocation::all();
        $file_servicelocation = fopen('services_at_location.csv', 'w');
        fputcsv($file_servicelocation, array('id', 'service_id', 'location_id', 'description'));
        // fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
        foreach ($table_servicelocation as $row) {

            $ServiceLocationArray = [
                'id' => $row->id,
                'service_id' => $row->service_recordid,
                'location_id' => $row->location_recordid,
                'description' => '',
            ];
            // fputcsv($file_servicelocation, $row->toArray());
            fputcsv($file_servicelocation, $ServiceLocationArray);
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
        $file_schedule = fopen('schedules.csv', 'w');
        // fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
        fputcsv($file_schedule, array('id', 'service_id', 'location_id', 'service_at_location_id', 'valid_from', 'valid_to', 'dtstart', 'until', 'wkst', 'freq', 'interval', 'byday', 'byweekno', 'bymonthday', 'byyearday', 'description'));
        foreach ($table_schedule as $row) {
            // if ($row->weekday) {
            $scheduleArray = [
                'id' => $row->id,
                'service_id' => $row->services,
                'location_id' => $row->locations,
                'service_at_location_id' => $row->service_at_location,
                'valid_from' => $row->valid_from,
                'valid_to' => $row->valid_to,
                'dtstart' => $row->opens,
                'until' => $row->closes,
                'wkst' => $row->wkst,
                'freq' => $row->freq,
                'interval' => $row->interval,
                'byday' => $row->byday,
                'byweekno' => $row->byweekno,
                'bymonthday' => $row->bymonthday,
                'byyearday' => $row->byyearday,
                'description' => $row->description,
                // 'schedule_recordid' => $row->schedule_recordid,
            ];

            fputcsv($file_schedule, $scheduleArray);
            // }
            // fputcsv($file_schedule, $row->toArray());
        }
        fclose($file_schedule);
        if (file_exists($path_csv_export . 'schedules.csv')) {
            unlink($path_csv_export . 'schedules.csv');
        }
        rename($public_path . 'schedules.csv', $path_csv_export . 'schedules.csv');

        // program

        $table_program = Program::all();
        $file_program = fopen('programs.csv', 'w');
        fputcsv($file_program, array('id', 'organization_id', 'name', 'alternate_name'));

        // fputcsv($file_program, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'program_services', 'program_phones', 'program_details', 'program_schedule', 'program_address', 'flag'));

        foreach ($table_program as $row) {

            $programArray = [
                'id' => $row->program_recordid,
                'organization_id' => $row->organizations,
                'name' => $row->name,
                'alternate_name' => $row->alternate_name,
            ];
            // fputcsv($file_program, $row->toArray());
            fputcsv($file_program, $programArray);
        }
        fclose($file_program);
        if (file_exists($path_csv_export . 'programs.csv')) {
            unlink($path_csv_export . 'programs.csv');
        }
        rename($public_path . 'programs.csv', $path_csv_export . 'programs.csv');

        $zip_file = 'datapackage.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = $path_csv_export;
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file) {

            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
                $relativePath = substr($filePath, strlen($path));

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
            $path_csv_export = public_path('csv_export/datapackage/');
            $public_path = public_path('/');

            $table_service = Service::all();
            $file_service = fopen('services.csv', 'w');
            fputcsv($file_service, array('id', 'service_recordid', 'organization_id', 'program_id', 'name', 'alternate_name', 'description', 'url', 'email', 'status', 'interpretation_services', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses'));
            // fputcsv($file_service, array('ID', 'id', 'name', 'alternate_name', 'organization_id', 'description', 'service_locations', 'url', 'program_id', 'email', 'status', 'service_taxonomy', 'application_process', 'wait_time', 'fees', 'accreditations', 'licenses', 'service_phones'));
            foreach ($table_service as $row) {

                if ($row->service_organization) {
                    $serviceArray = [
                        'id' => $row->id,
                        'service_recordid' => $row->service_recordid,
                        'organization_id' => $row->service_organization,
                        'program_id' => $row->service_program,
                        'name' => $row->service_name,
                        'alternate_name' => $row->service_alternate_name,
                        'description' => $row->service_description,
                        'url' => $row->service_url,
                        'email' => $row->service_email,
                        'status' => $row->service_status == 'Verified' ? 'active' : 'inactive',
                        'interpretation_services' => '',
                        'application_process' => $row->service_application_process,
                        'wait_time' => $row->service_wait_time,
                        'fees' => $row->service_fees,
                        'accreditations' => $row->service_accreditations,
                        'licenses' => $row->service_licenses,
                        // 'taxonomy_ids' => $row->service_taxonomy,
                    ];
                    fputcsv($file_service, $serviceArray);
                }
            }
            fclose($file_service);
            if (file_exists($path_csv_export . 'services.csv')) {
                unlink($path_csv_export . 'services.csv');
            }
            rename($public_path . 'services.csv', $path_csv_export . 'services.csv');


            $table_location = Location::all();
            $file_location = fopen('locations.csv', 'w');
            fputcsv($file_location, array('id', 'organization_id', 'name', 'alternate_name', 'description', 'transportation', 'latitude', 'longitude'));

            // fputcsv($file_location, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'location_services', 'location_phones', 'location_details', 'location_schedule', 'location_address', 'flag'));

            foreach ($table_location as $row) {

                $locationArray = [
                    'id' => $row->location_recordid,
                    'organization_id' => $row->location_organization,
                    'name' => $row->location_name,
                    'alternate_name' => $row->location_alternate_name,
                    'description' => $row->location_description,
                    'transportation' => $row->location_transportation,
                    'latitude' => $row->location_latitude,
                    'longitude' => $row->location_longitude,
                ];
                // fputcsv($file_location, $row->toArray());
                fputcsv($file_location, $locationArray);
            }
            fclose($file_location);
            if (file_exists($path_csv_export . 'locations.csv')) {
                unlink($path_csv_export . 'locations.csv');
            }
            rename($public_path . 'locations.csv', $path_csv_export . 'locations.csv');

            $table_organization = Organization::all();
            $file_organization = fopen('organizations.csv', 'w');

            fputcsv($file_organization, array('id', 'name', 'alternate_name', 'description', 'email', 'url', 'tax_status', 'tax_id', 'year_incorporated', 'legal_status'));

            // fputcsv($file_organization, array('ID', 'id', 'name', 'alternate_name', 'organization_logo_x', 'organization_x_uid', 'description', 'email', 'organization_forms_x_filename', 'organization_forms_x_url', 'url', 'organization_status_x', 'organization_status_sort', 'legal_status', 'tax_status', 'tax_id', 'year_incorporated', 'organization_services', 'organization_phones', 'organization_locations', 'organization_contact', 'organization_details', 'organization_airs_taxonomy_x', 'flag'));
            foreach ($table_organization as $row) {
                // if ($row->organization_description) {
                $organizationArray = [
                    'id' => strval($row->organization_recordid),
                    'name' => $row->organization_name,
                    'alternate_name' => $row->organization_alternate_name,
                    'description' => $row->organization_description,
                    'email' => $row->organization_email,
                    'url' => $row->organization_url,
                    'tax_status' => $row->organization_tax_status,
                    'tax_id' => $row->organization_tax_id,
                    'year_incorporated' => $row->organization_year_incorporated,
                    'legal_status' => $row->organization_legal_status,
                ];
                fputcsv($file_organization, $organizationArray);
                // }
                // fputcsv($file_organization, $row->toArray());
            }
            fclose($file_organization);
            if (file_exists($path_csv_export . 'organizations.csv')) {
                unlink($path_csv_export . 'organizations.csv');
            }
            rename($public_path . 'organizations.csv', $path_csv_export . 'organizations.csv');

            $table_contact = Contact::all();
            $file_contact = fopen('contacts.csv', 'w');
            fputcsv($file_contact, array('id', 'organization_id', 'service_id', 'service_at_location_id', 'name', 'title', 'department', 'email'));
            // fputcsv($file_contact, array('ID', 'id', 'name', 'organization_id', 'service_id', 'title', 'department', 'email', 'phone_number', 'phone_areacode', 'phone_extension', 'flag'));
            foreach ($table_contact as $row) {

                $locationArray = [
                    'id' => $row->id,
                    'organization_id' => $row->contact_organizations,
                    'service_id' => $row->contact_services,
                    'service_at_location_id' => '',
                    'name' => $row->contact_name,
                    'title' => $row->contact_title,
                    'department' => $row->contact_department,
                    'email' => trim($row->contact_email),
                ];
                fputcsv($file_contact, $locationArray);
                // fputcsv($file_contact, $row->toArray());
            }
            fclose($file_contact);
            if (file_exists($path_csv_export . 'contacts.csv')) {
                unlink($path_csv_export . 'contacts.csv');
            }
            rename($public_path . 'contacts.csv', $path_csv_export . 'contacts.csv');

            $table_phone = Phone::all();
            $file_phone = fopen('phones.csv', 'w');
            fputcsv($file_phone, array('id', 'location_id', 'service_id', 'organization_id', 'contact_id', 'service_at_location_id', 'number', 'extension', 'type', 'language', 'description', 'department'));
            // fputcsv($file_phone, array('ID', 'id', 'number', 'location_id', 'service_id', 'organization_id', 'contact_id', 'extension', 'type', 'language', 'description', 'phone_schedule', 'flag'));
            foreach ($table_phone as $row) {
                if ($row->phone_number) {
                    $phoneArray = [
                        'id' => $row->id,
                        'location_id' => $row->phone_locations,
                        'service_id' => $row->phone_services,
                        'organization_id' => $row->phone_organizations,
                        'contact_id' => $row->phone_contacts,
                        'service_at_location_id' => '',
                        'number' => $row->phone_number,
                        'extension' => $row->phone_extension,
                        'type' => $row->phone_type,
                        'language' => $row->phone_language,
                        'description' => $row->phone_description,
                        'department' => '',
                    ];
                    fputcsv($file_phone, $phoneArray);
                }
                // fputcsv($file_phone, $row->toArray());
            }
            fclose($file_phone);
            if (file_exists($path_csv_export . 'phones.csv')) {
                unlink($path_csv_export . 'phones.csv');
            }
            rename($public_path . 'phones.csv', $path_csv_export . 'phones.csv');

            $table_address = Address::all();
            $file_address = fopen('physical_addresses.csv', 'w');
            fputcsv($file_address, array('id', 'location_id', 'attention', 'address_1', 'address_2', 'address_3', 'address_4', 'city', 'region', 'state_province', 'postal_code', 'country'));
            // fputcsv($file_address, array('ID', 'id', 'address_1', 'address_2', 'city', 'state_province', 'postal_code', 'region', 'country', 'attention', 'address_type', 'location_id', 'address_services', 'organization_id', 'flag'));
            foreach ($table_address as $row) {
                if ($row->address_1 && $row->address_city && $row->address_state_province && $row->address_postal_code && $row->address_country) {
                    $address_locations = '';
                    $locationIds = explode(',', $row->address_locations);
                    // if (count($locationIds) > 0 && isset($locationIds[0])) {
                    //     $address_locations = $locationIds[0];
                    // }
                    if (count($locationIds) > 0) {
                        foreach ($locationIds as $key => $value) {
                            $addressArray = [
                                'id' => $row->id,
                                'location_id' => $value,
                                'attention' => $row->address_attention,
                                'address_1' => $row->address_1,
                                'address_2' => $row->address_2,
                                'address_3' => '',
                                'address_4' => '',
                                'city' => $row->address_city,
                                'region' => $row->address_region,
                                'state_province' => $row->address_state_province,
                                'postal_code' => $row->address_postal_code,
                                'country' => $row->address_country,
                            ];
                            fputcsv($file_address, $addressArray);
                        }
                    } else {
                        $addressArray = [
                            'id' => $row->id,
                            'location_id' => $row->address_locations,
                            'attention' => $row->address_attention,
                            'address_1' => $row->address_1,
                            'address_2' => $row->address_2,
                            'address_3' => '',
                            'address_4' => '',
                            'city' => $row->address_city,
                            'region' => $row->address_region,
                            'state_province' => $row->address_state_province,
                            'postal_code' => $row->address_postal_code,
                            'country' => $row->address_country,
                        ];
                        fputcsv($file_address, $addressArray);
                    }
                }
            }
            fclose($file_address);
            if (file_exists($path_csv_export . 'physical_addresses.csv')) {
                unlink($path_csv_export . 'physical_addresses.csv');
            }
            rename($public_path . 'physical_addresses.csv', $path_csv_export . 'physical_addresses.csv');

            $table_language = Language::all();
            $file_language = fopen('languages.csv', 'w');
            fputcsv($file_language, array('id', 'service_id', 'location_id', 'language'));
            // fputcsv($file_language, array('ID', 'id', 'location_id', 'service_id', 'language', 'flag'));
            foreach ($table_language as $row) {

                $langaugeArray = [
                    'id' => $row->id,
                    'service_id' => $row->language_service,
                    'location_id' => $row->language_location,
                    'language' => $row->language,
                ];
                fputcsv($file_language, $langaugeArray);
                // fputcsv($file_language, $row->toArray());
            }
            fclose($file_language);
            if (file_exists($path_csv_export . 'languages.csv')) {
                unlink($path_csv_export . 'languages.csv');
            }
            rename($public_path . 'languages.csv', $path_csv_export . 'languages.csv');

            $table_taxonomy = Taxonomy::all();
            $file_taxonomy = fopen('taxonomy_terms.csv', 'w');
            // fputcsv($file_taxonomy, array('ID', 'id', 'name', 'parent_name', 'taxonomy_grandparent_name', 'vocabulary', 'taxonomy_x_description', 'taxonomy_x_notes', 'taxonomy_services', 'parent_id', 'taxonomy_facet', 'category_id', 'taxonomy_id', 'flag'));
            fputcsv($file_taxonomy, array('id', 'term', 'description', 'parent_id', 'taxonomy', 'language'));
            foreach ($table_taxonomy as $row) {
                // if ($row->taxonomy_name && $row->taxonomy_x_description) {
                $taxonomyArray = [
                    'id' => $row->taxonomy_recordid,
                    'term' => $row->taxonomy_name,
                    'description' => $row->taxonomy_x_description,
                    'parent_id' => $row->taxonomy_parent_recordid,
                    'taxonomy' => '',
                    'language' => '',
                    // 'vocabulary' => $row->taxonomy_vocabulary,
                ];
                fputcsv($file_taxonomy, $taxonomyArray);
                // fputcsv($file_taxonomy, $row->toArray());
                // }
            }
            fclose($file_taxonomy);
            if (file_exists($path_csv_export . 'taxonomy_terms.csv')) {
                unlink($path_csv_export . 'taxonomy_terms.csv');
            }
            rename($public_path . 'taxonomy_terms.csv', $path_csv_export . 'taxonomy_terms.csv');

            $table_servicetaxonomy = Servicetaxonomy::all();
            $file_servicetaxonomy = fopen('service_attributes.csv', 'w');
            fputcsv($file_servicetaxonomy, array('id', 'service_id', 'taxonomy_term_id'));
            // fputcsv($file_servicetaxonomy, array('ID', 'service_id', 'taxonomy_recordid', 'taxonomy_id', 'taxonomy_detail'));
            foreach ($table_servicetaxonomy as $row) {
                if ($row->service_recordid) {
                    $ServiceTaxonomyArray = [
                        'id' => $row->id,
                        'service_id' => $row->service_recordid,
                        'taxonomy_term_id' => $row->taxonomy_recordid,
                        // 'taxonomy_detail' => $row->taxonomy_detail,
                    ];
                    fputcsv($file_servicetaxonomy, $ServiceTaxonomyArray);
                    // fputcsv($file_servicetaxonomy, $row->toArray());
                }
            }
            fclose($file_servicetaxonomy);
            if (file_exists($path_csv_export . 'service_attributes.csv')) {
                unlink($path_csv_export . 'service_attributes.csv');
            }
            rename($public_path . 'service_attributes.csv', $path_csv_export . 'service_attributes.csv');

            $table_servicelocation = Servicelocation::all();
            $file_servicelocation = fopen('services_at_location.csv', 'w');
            fputcsv($file_servicelocation, array('id', 'service_id', 'location_id', 'description'));
            // fputcsv($file_servicelocation, array('ID', 'location_id', 'service_id'));
            foreach ($table_servicelocation as $row) {

                $ServiceLocationArray = [
                    'id' => $row->id,
                    'service_id' => $row->service_recordid,
                    'location_id' => $row->location_recordid,
                    'description' => '',
                ];
                // fputcsv($file_servicelocation, $row->toArray());
                fputcsv($file_servicelocation, $ServiceLocationArray);
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
            $file_schedule = fopen('schedules.csv', 'w');
            // fputcsv($file_schedule, array('ID', 'schedule_recordid', 'schedule_id', 'service_id', 'location_id', 'schedule_x_phones', 'weekday', 'opens_at', 'closes_at', 'holiday', 'start_date', 'end_date', 'original_text', 'Schedule_closed', 'flag'));
            fputcsv($file_schedule, array('id', 'service_id', 'location_id', 'service_at_location_id', 'valid_from', 'valid_to', 'dtstart', 'until', 'wkst', 'freq', 'interval', 'byday', 'byweekno', 'bymonthday', 'byyearday', 'description'));
            foreach ($table_schedule as $row) {
                // if ($row->weekday) {
                $scheduleArray = [
                    'id' => $row->id,
                    'service_id' => $row->schedule_services,
                    'location_id' => $row->schedule_locations,
                    'service_at_location_id' => $row->service_at_location,
                    'valid_from' => $row->valid_from,
                    'valid_to' => $row->valid_to,
                    'dtstart' => $row->opens,
                    'until' => $row->closes,
                    'wkst' => $row->wkst,
                    'freq' => $row->freq,
                    'interval' => $row->interval,
                    'byday' => $row->byday,
                    'byweekno' => $row->byweekno,
                    'bymonthday' => $row->bymonthday,
                    'byyearday' => $row->byyearday,
                    'description' => $row->description,
                ];

                fputcsv($file_schedule, $scheduleArray);
                // }
                // fputcsv($file_schedule, $row->toArray());
            }
            fclose($file_schedule);
            if (file_exists($path_csv_export . 'schedules.csv')) {
                unlink($path_csv_export . 'schedules.csv');
            }
            rename($public_path . 'schedules.csv', $path_csv_export . 'schedules.csv');

            // program

            $table_program = Program::all();
            $file_program = fopen('programs.csv', 'w');
            fputcsv($file_program, array('id', 'organization_id', 'name', 'alternate_name'));

            // fputcsv($file_program, array('ID', 'id', 'name', 'organization_id', 'alternate_name', 'transportation', 'latitude', 'longitude', 'description', 'program_services', 'program_phones', 'program_details', 'program_schedule', 'program_address', 'flag'));

            foreach ($table_program as $row) {

                $programArray = [
                    'id' => $row->program_recordid,
                    'organization_id' => $row->organizations,
                    'name' => $row->name,
                    'alternate_name' => $row->alternate_name,
                ];
                // fputcsv($file_program, $row->toArray());
                fputcsv($file_program, $programArray);
            }
            fclose($file_program);
            if (file_exists($path_csv_export . 'programs.csv')) {
                unlink($path_csv_export . 'programs.csv');
            }
            rename($public_path . 'programs.csv', $path_csv_export . 'programs.csv');

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
                    // $relativePath = substr($filePath, strlen($path));
                    // dd($relativePath, $filePath, $path);
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
        $service_count = Service::count();
        $filter_count = 0;
        $address_serviceids = [];
        $taxonomy_serviceids = [];
        $organizations = Organization::select('*');
        $organizations_ids = [];
        $filterServiceRecordId = [];
        foreach ($metafilters as $key => $meta_filter) {
            $values = explode(',', $meta_filter->values);

            $values = array_filter($values);

            $filter_count += count($values);

            if ($meta_filter->facet == 'Postal_code') {
                $address_serviceids = [];
                if ($meta_filter->operations == 'Include') {
                    $serviceids = ServiceAddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                }

                if ($meta_filter->operations == 'Exclude') {
                    $serviceids = ServiceAddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                }

                $address_serviceids = array_merge($serviceids, $address_serviceids);
            }
            if ($meta_filter->facet == 'Taxonomy') {

                if ($meta_filter->operations == 'Include') {
                    $serviceids = ServiceTaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                }

                if ($meta_filter->operations == 'Exclude') {
                    $serviceids = ServiceTaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                }

                $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
            }
            if ($meta_filter->facet == 'Service_status') {

                if ($meta_filter->operations == 'Include') {
                    $serviceids = Service::whereIn('service_status', $values)->pluck('service_recordid')->toArray();
                    // $serviceids = Service::whereIn('service_recordid', $values)->pluck('service_recordid')->toArray();
                }

                if ($meta_filter->operations == 'Exclude') {
                    $serviceids = Service::whereNotIn('service_status', $values)->pluck('service_recordid')->toArray();
                }
                $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
            }
            if ($meta_filter->facet == 'service_tag') {
                $serviceids = Service::where(function ($query) use ($values, $meta_filter) {
                    foreach ($values as $keyword) {
                        if ($keyword && $meta_filter->operations == 'Include') {
                            $query = $query->orWhereRaw('find_in_set(' . $keyword . ', service_tag)');
                        }
                        if ($keyword && $meta_filter->operations == 'Exclude') {
                            $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', service_tag)');
                        }
                    }
                    return $query;
                })->pluck('service_recordid')->toArray();
                $taxonomy_serviceids = array_merge($serviceids, $taxonomy_serviceids);
            }
            if ($meta_filter->facet == 'organization_status') {

                $organization_service_recordid = [];
                if ($values && count($values) > 0) {
                    $organizations_status_ids = [];
                    $operations = $meta_filter->operations;
                    if ($values) {
                        $organizations_status_ids = Organization::where(function ($query) use ($values, $operations) {
                            foreach ($values as $keyword) {
                                // $organization_status = OrganizationStatus::whereId($keyword)->first();
                                if ($operations == 'Include') {
                                    $query = $query->orWhere('organization_status_x', 'LIKE', "%$keyword%");
                                }
                                if ($operations == 'Exclude') {
                                    $query = $query->orWhere('organization_status_x', 'NOT LIKE', "%$keyword%");
                                }
                            }
                            return $query;
                        })->pluck('organization_recordid')->toArray();
                    }
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_status_ids)->pluck('service_recordid')->toArray();
                }
                $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
            }
            if ($meta_filter->facet == 'organization_tag') {

                $organization_service_recordid = [];
                if ($values && count($values) > 0) {
                    $organizations_tags_ids = [];
                    $operations = $meta_filter->operations;
                    if ($values) {
                        $organizations_tags_ids = Organization::where(function ($query) use ($values, $operations) {
                            foreach ($values as $keyword) {
                                // $organization_status = OrganizationStatus::whereId($keyword)->first();
                                if ($keyword && $operations == 'Include') {
                                    $query = $query->orWhereRaw('find_in_set(' . $keyword . ', organization_tag)');
                                }
                                if ($keyword && $operations == 'Exclude') {
                                    $query = $query->orWhereRaw('NOT find_in_set(' . $keyword . ', organization_tag)');
                                }
                            }
                            return $query;
                        })->pluck('organization_recordid')->toArray();
                    }
                    $organization_service_recordid = ServiceOrganization::whereIn('organization_recordid', $organizations_tags_ids)->pluck('service_recordid')->toArray();
                }
                $taxonomy_serviceids = array_merge($organization_service_recordid, $taxonomy_serviceids);
            }
            // org count
            if (count($values) > 0) {
                if ($meta_filter->facet == 'organization_status') {
                    $organizations_status_ids = Organization::getOrganizationStatusMeta($values, $meta_filter->operations);
                    $organizations = $organizations->whereIn('organization_recordid', array_unique($organizations_status_ids));
                }
                if ($meta_filter->facet == 'organization_tag') {
                    $organizations_tags_ids = Organization::getOrganizationTagMeta($values, $meta_filter->operations);
                    $organizations = $organizations->whereIn('organization_recordid', array_unique($organizations_tags_ids));
                }
                if ($meta_filter->facet == 'Service_status') {
                    $serviceStatusIds = Service::getServiceStatusMeta($values, $meta_filter->operations);
                    $filterServiceRecordId = array_merge($filterServiceRecordId, $serviceStatusIds);
                }
                if ($meta_filter->facet == 'service_tag') {
                    $serviceTagIds = Service::getServiceTagMeta($values, $meta_filter->operations);
                    $filterServiceRecordId = array_merge($filterServiceRecordId, $serviceTagIds);
                }
            }
        }
        if ($meta && $meta->hide_organizations_with_no_filtered_services == 1 && count($filterServiceRecordId) > 0) {
            $organizationIds = ServiceOrganization::whereIn('service_recordid', $filterServiceRecordId)->pluck('organization_recordid')->toArray();
            $service_organizations = Service::whereIn('service_recordid', $filterServiceRecordId)->pluck('service_organization')->toArray();

            $organizationIds = array_values(array_unique(array_merge($organizationIds, $service_organizations)));

            $organizations = $organizations->orWhereIn('organization_recordid', array_unique($organizationIds));
        }
        $service_count = (count($address_serviceids) > 0 || count($taxonomy_serviceids) > 0) ?  count(array_unique(array_merge($address_serviceids, $taxonomy_serviceids))) : $service_count;
        $organizations_count = $organizations->count();

        return view('backEnd.pages.metafilter', compact('meta', 'source_data', 'metafilters', 'service_count', 'filter_count', 'organizations_count'));
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
            if ($request->input('user_metafilter_option') == 'checked') {
                $meta->user_metafilter_option = 1;
            } else {
                $meta->user_metafilter_option = 0;
            }
            if ($request->default_label) {
                $meta->default_label = $request->default_label;
            }
            if ($request->meta_filter_on_label) {
                $meta->meta_filter_on_label = $request->meta_filter_on_label;
            }
            if ($request->meta_filter_off_label) {
                $meta->meta_filter_off_label = $request->meta_filter_off_label;
            }
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
    public function meta_additional_setting($id, Request $request)
    {
        try {
            $meta = Layout::find($id);

            if ($request->input('hide_organizations_with_no_filtered_services') == 'checked') {
                $meta->hide_organizations_with_no_filtered_services = 1;
            } else {
                $meta->hide_organizations_with_no_filtered_services = 0;
            }
            if ($request->input('hide_service_category_with_no_filter_service') == 'checked') {
                $meta->hide_service_category_with_no_filter_service = 1;
            } else {
                $meta->hide_service_category_with_no_filter_service = 0;
            }
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
    public function organization_status_filter()
    {
        $organization_statuses = OrganizationStatus::all();
        $source_data = Source_data::find(1);
        $checked_status = [];

        return view('backEnd.pages.organization_status_filter', compact('organization_statuses', 'source_data', 'checked_status'))->render();
    }
    public function service_status_filter()
    {
        $service_statuses = ServiceStatus::all();
        $source_data = Source_data::find(1);
        $checked_status = [];

        return view('backEnd.pages.service_status_filter', compact('service_statuses', 'source_data', 'checked_status'))->render();
    }
    public function service_tag_filter()
    {
        $service_tags = ServiceTag::all();
        $source_data = Source_data::find(1);
        $checked_tags = [];

        return view('backEnd.pages.service_tag_meta_filter', compact('service_tags', 'source_data', 'checked_tags'))->render();
    }
    public function organization_tag_filter()
    {
        $organization_tags = OrganizationTag::all();
        $source_data = Source_data::find(1);
        $checked_tags = [];

        return view('backEnd.pages.organization_tag_meta_filter', compact('organization_tags', 'source_data', 'checked_tags'))->render();
    }

    public function metafilter_edit($id, Request $request)
    {
        $source_data = Source_data::find(1);
        $metafilter = MetaFilter::find($id);
        if ($metafilter->facet == 'Taxonomy') {

            $taxonomies = Taxonomy::all();
            $checked_taxonomies = explode(",", $metafilter->values);

            return view('backEnd.pages.metafilter_taxonomy', compact('taxonomies', 'source_data', 'checked_taxonomies'))->render();
        } else if ($metafilter->facet == 'organization_status') {

            $organization_statuses = OrganizationStatus::all();
            $source_data = Source_data::find(1);
            $checked_status = explode(",", $metafilter->values);

            return view('backEnd.pages.organization_status_filter', compact('organization_statuses', 'source_data', 'checked_status'))->render();
        } else if ($metafilter->facet == 'Service_status') {

            $service_statuses = ServiceStatus::all();
            $source_data = Source_data::find(1);
            $checked_status = explode(",", $metafilter->values);

            return view('backEnd.pages.service_status_filter', compact('service_statuses', 'source_data', 'checked_status'))->render();
        } else if ($metafilter->facet === 'service_tag') {

            $service_tags = ServiceTag::all();
            $source_data = Source_data::find(1);
            $checked_tags = explode(",", $metafilter->values);

            return view('backEnd.pages.service_tag_meta_filter', compact('service_tags', 'source_data', 'checked_tags'))->render();
        } else if ($metafilter->facet === 'organization_tag') {

            $organization_tags = OrganizationTag::all();
            $source_data = Source_data::find(1);
            $checked_tags = explode(",", $metafilter->values);

            return view('backEnd.pages.organization_tag_meta_filter', compact('organization_tags', 'source_data', 'checked_tags'))->render();
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
