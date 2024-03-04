<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Language;
use App\Model\Phone;
use Illuminate\Support\Facades\Log;

class PhoneService
{
    public function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->access_token = $access_token;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            // Phone::truncate();
            // OrganizationPhone::truncate();
            // ContactPhone::truncate();
            // ServicePhone::truncate();
            // LocationPhone::truncate();
            // $airtable = new Airtable(array(
            //     'access_token'   => env('AIRTABLE_access_token'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('phones');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_phone = Phone::where('phone_recordid', $recordId)->where('phone_number', isset($record['fields']['number']) ? $record['fields']['number'] : null)->first();
                    if ($old_phone == null) {
                        $phone = new Phone();
                        $strtointclass = new Stringtoint();
                        $phone->phone_recordid = $record['id'];
                        $phone->phone_recordid = $strtointclass->string_to_int($record['id']);
                        $phone->phone_number = isset($record['fields']['number']) ? $record['fields']['number'] : null;

                        $locationRecordid = [];
                        if (isset($record['fields']['locations'])) {
                            foreach ($record['fields']['locations'] as $value) {
                                $locationRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $phone->phone_locations = implode(',', $locationRecordid);
                        }
                        $serviceRecordid = [];
                        if (isset($record['fields']['services'])) {
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $phone->phone_services = implode(',', $serviceRecordid);
                        }

                        $organizationRecordid = [];
                        if (isset($record['fields']['organizations'])) {
                            foreach ($record['fields']['organizations'] as $value) {
                                $organizationRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $phone->phone_organizations = implode(',', $organizationRecordid);
                        }
                        $contactRecordid = [];
                        if (isset($record['fields']['contacts'])) {
                            foreach ($record['fields']['contacts'] as $value) {
                                $contactRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $phone->phone_contacts = implode(',', $contactRecordid);
                        }
                        if (isset($record['fields']['language'])) {
                            $languages = $record['fields']['language'];
                            $phone_language = [];
                            foreach ($languages as $key => $value) {
                                $lang = Language::where('language', $value)->first();
                                if ($lang) {
                                    $phone_language[] = $lang->language_recordid;
                                } else {
                                    $lang = new Language();
                                    $lang->language = $value;
                                    $language_recordid = Language::max('language_recordid') + 1;
                                    $lang->language_recordid = $language_recordid;
                                    $lang->save();
                                    $phone_language[] = $language_recordid;
                                }
                            }
                            $phone->phone_language = implode(',', $phone_language);
                        }
                        $phone->phone_extension = isset($record['fields']['extension']) ? $record['fields']['extension'] : null;
                        $phone->phone_type = isset($record['fields']['type']) ? $record['fields']['type'] : null;
                        $phone->phone_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                        $phone->phone_schedule = isset($record['fields']['schedule']) ? implode(",", $record['fields']['schedule']) : null;
                        // for save organization
                        $organizationId_list = $organizationRecordid;
                        $current_organizations = $phone->organization()->allRelatedIds()->toArray();
                        $res = array_unique(array_merge($current_organizations, $organizationId_list));
                        $phone->organization()->sync($res);

                        // for save service
                        $servicesId_list = $serviceRecordid;
                        $current_services = $phone->services()->allRelatedIds()->toArray();
                        $res = array_unique(array_merge($current_services, $servicesId_list));
                        $phone->services()->sync($res);

                        // for save location
                        $locationsId_list = $locationRecordid;
                        $current_locations = $phone->locations()->allRelatedIds()->toArray();
                        $res = array_unique(array_merge($current_locations, $locationsId_list));
                        $phone->locations()->sync($res);

                        // for save contact
                        $contactsId_list = $contactRecordid;
                        $current_contacts = $phone->contact()->allRelatedIds()->toArray();
                        $res = array_unique(array_merge($current_contacts, $contactsId_list));
                        $phone->contact()->sync($res);

                        $phone->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Phones')->first();
            $airtable->records = Phone::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Phone: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
