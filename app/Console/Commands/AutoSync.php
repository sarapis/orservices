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
            $importData = ImportDataSource::where('auto_sync', 1)->first();
            if ($importData) {

                $hours = $importData->sync_hours;
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $importData->last_imports);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
                $diff_in_hours = $to->diffInHours($from);
                if ($diff_in_hours == intval($hours)) {
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
                        $airtableKeyInfo = Airtablekeyinfo::whereId(2)->first();
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
                            $importData->auto_sync = '0';
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
                        $importData->auto_sync = '0';
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
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error from auto sync cronjob: ' . $th->getMessage());
        }
    }
}
