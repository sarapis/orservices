<?php

namespace App\Console\Commands;

use App\Model\Airtablekeyinfo;
use App\Model\AutoSyncAirtable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Model\AdditionalTaxonomy;
use App\Model\Address;
use App\Model\Contact;
use App\Model\ContactPhone;
use App\Model\Detail;
use App\Model\ImportDataSource;
use App\Model\ImportHistory;
use App\Model\Location;
use App\Model\LocationAddress;
use App\Model\LocationPhone;
use App\Model\LocationSchedule;
use App\Model\Organization;
use App\Model\OrganizationDetail;
use App\Model\OrganizationPhone;
use App\Model\OrganizationProgram;
use App\Model\Phone;
use App\Model\Program;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceContact;
use App\Model\ServiceDetail;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServicePhone;
use App\Model\ServiceProgram;
use App\Model\ServiceSchedule;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use App\Model\TaxonomyTerm;
use App\Model\TaxonomyType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Spatie\Geocoder\Geocoder;

class AutoSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoSync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto sync is init';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // $AutoSyncAirtable = AutoSyncAirtable::first();
            // if ($AutoSyncAirtable && $AutoSyncAirtable->option == 'yes') {
            //     $hours = $AutoSyncAirtable->days;
            //     $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $AutoSyncAirtable->updated_at);
            //     $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
            // $AutoSyncAirtable = AutoSyncAirtable::first();
            $importData = ImportDataSource::where('auto_sync', '1')->first();
            if ($importData) {
                $hours = $importData->sync_hours;
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $importData->last_imports);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
                $diff_in_hours = $to->diffInHours($from);
                if ($diff_in_hours >= intval($hours)) {
                    // if (Carbon::parse($importData->last_imports)->DiffInHours(Carbon::now()) == $hours) {
                    $organization_tag = $importData->organization_tags;
                    if ($importData->mode == 'replace') {
                        Program::truncate();
                        ServiceProgram::truncate();
                        OrganizationProgram::truncate();
                        TaxonomyType::truncate();
                        TaxonomyTerm::truncate();
                        AdditionalTaxonomy::truncate();
                        Address::truncate();
                        Contact::truncate();
                        Detail::truncate();
                        Location::truncate();
                        LocationAddress::truncate();
                        LocationPhone::truncate();
                        LocationSchedule::truncate();
                        Organization::truncate();
                        OrganizationDetail::truncate();
                        Phone::truncate();
                        OrganizationPhone::truncate();
                        ContactPhone::truncate();
                        ServicePhone::truncate();
                        Schedule::truncate();
                        Service::truncate();
                        ServiceLocation::truncate();
                        ServiceAddress::truncate();
                        ServiceDetail::truncate();
                        ServiceOrganization::truncate();
                        ServiceContact::truncate();
                        ServiceTaxonomy::truncate();
                        ServiceSchedule::truncate();
                        Taxonomy::truncate();
                    }
                    if ($importData && $importData->import_type == 'airtable') {
                        $airtableKeyInfo = Airtablekeyinfo::whereId($importData->airtable_api_key)->first();
                        $response = Http::get('https://api.airtable.com/v0/' . $airtableKeyInfo->base_url . '/organizations?api_key=' . $airtableKeyInfo->api_key);
                        if ($response->status() != 200) {
                            Log::error("Autosync : Airtable key or base id is invalid. Please enter valid information and try again.");
                            return;
                        }
                        if ($airtableKeyInfo) {
                            app(\App\Http\Controllers\frontEnd\ServiceController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\AddressController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\ContactController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\DetailController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\LocationController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\OrganizationController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url, $organization_tag);
                            app(\App\Http\Controllers\frontEnd\PhoneController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\ScheduleController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\frontEnd\TaxonomyController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\backend\ProgramController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            app(\App\Http\Controllers\backend\TaxonomyTypeController::class)->airtable_v2($airtableKeyInfo->api_key, $airtableKeyInfo->base_url);
                            // $importData->auto_sync = '0';
                            $importData->updated_at = Carbon::now();
                            $importData->last_imports = Carbon::now();
                            $importData->save();
                            $importHistory = new ImportHistory();
                            $importHistory->source_name = $importData->format;
                            $importHistory->import_type = $importData->import_type;
                            $importHistory->auto_sync = '1';
                            $importHistory->status = 'Completed';
                            $importHistory->save();
                            Log::info('Sync via airtable Completed: ' . Carbon::now());
                        }
                    } else if ($importData && $importData->import_type == 'zipfile') {
                        // $this->zip($importData);
                        app(\App\Http\Controllers\backend\ImportController::class)->zip($importData);
                        // $importData->auto_sync = '0';
                        $importData->updated_at = Carbon::now();
                        $importData->last_imports = Carbon::now();
                        $importData->save();
                        $importHistory = new ImportHistory();
                        $importHistory->source_name = $importData->format;
                        $importHistory->import_type = $importData->import_type;
                        $importHistory->auto_sync = '1';
                        $importHistory->status = 'Completed';
                        $importHistory->save();
                        Log::info('Sync via zipfile Completed: ' . Carbon::now());
                    }
                    $this->apply_geocode();
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error from auto sync cronjob: ' . $th->getMessage());
        }
    }
    public function apply_geocode()
    {
        try {
            $ungeocoded_location_info_list = Location::whereNull('location_latitude')->get();
            $badgeocoded_location_info_list = Location::where('location_latitude', '=', '')->get();
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
            $geocoder->setApiKey($geocode_api_key);

            if ($ungeocoded_location_info_list) {
                foreach ($ungeocoded_location_info_list as $key => $location_info) {

                    if ($location_info->location_name) {
                        $address_info = $location_info->location_name;
                        // $response = $geocoder->getCoordinatesForAddress('30-61 87th Street, Queens, NY, 11369');
                        $response = $geocoder->getCoordinatesForAddress($address_info);
                        // if (($response['lat'] > 40.5) && ($response['lat'] < 42.0)) {
                        //     $latitude = $response['lat'];
                        //     $longitude = $response['lng'];
                        // } else {
                        //     $latitude = '';
                        //     $longitude = '';
                        // }

                        $latitude = $response['lat'];
                        $longitude = $response['lng'];

                        $location_info->location_latitude = $latitude;
                        $location_info->location_longitude = $longitude;
                        $location_info->save();
                    }
                }
            }

            if ($badgeocoded_location_info_list) {
                foreach ($badgeocoded_location_info_list as $key => $location_info) {
                    if ($location_info->location_name) {
                        $address_info = $location_info->location_name;
                        // $response = $geocoder->getCoordinatesForAddress('30-61 87th Street, Queens, NY, 11369');
                        $response = $geocoder->getCoordinatesForAddress($address_info);
                        $latitude = $response['lat'];
                        $longitude = $response['lng'];
                        $location_info->location_latitude = $latitude;
                        $location_info->location_longitude = $longitude;
                        $location_info->save();
                    }
                }
            }

            return;
        } catch (\Throwable $th) {
            Log::error('Error in applying geocode in import : ' . $th);
        }
    }
}
