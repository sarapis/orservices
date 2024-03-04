<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Location;
use Illuminate\Support\Facades\Log;

class LocationService
{
    function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable_key_info = Airtablekeyinfo::find(1);
            if (!$airtable_key_info) {
                $airtable_key_info = new Airtablekeyinfo;
            }
            $airtable_key_info->access_token = $access_token;
            $airtable_key_info->base_url = $base_url;
            $airtable_key_info->save();

            $airtable = new Airtable(array(
                'access_token'   => $access_token,
                'base'      => $base_url,
            ));

            $request = $airtable->getContent('locations');

            do {


                $response = $request->getResponse();

                $airtable_response = json_decode($response, TRUE);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_location = Location::where('location_recordid', $recordId)->where('location_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if (empty($old_location)) {
                        $location = new Location();
                        $strtointclass = new Stringtoint();
                        $location->location_recordid = $strtointclass->string_to_int($record['id']);
                        $location->location_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        $location->location_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                        $location->external_identifier = isset($record['fields']['external_identifier']) ? $record['fields']['external_identifier'] : null;
                        $location->external_identifier_type = isset($record['fields']['external_identifier_type']) ? $record['fields']['external_identifier_type'] : null;

                        if (isset($record['fields']['organization'])) {
                            $organizationRecordIds = [];
                            foreach ($record['fields']['organization'] as $key => $value) {
                                $organizationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->location_organization = implode(', ', $organizationRecordIds);
                        }

                        $location->location_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                        $location->location_transportation = isset($record['fields']['transportation']) ? $record['fields']['transportation'] : null;
                        $location->location_latitude = isset($record['fields']['latitude']) ? $record['fields']['latitude'] : null;
                        $location->location_longitude = isset($record['fields']['longitude']) ? $record['fields']['longitude'] : null;
                        $location->location_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services']  as  $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->location_services = implode(', ', $serviceRecordIds);
                        }

                        if (isset($record['fields']['phones'])) {
                            $phoneRecordIds = [];
                            foreach ($record['fields']['phones']  as  $value) {
                                $phoneRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->phones()->sync($phoneRecordIds);
                        }

                        if (isset($record['fields']['schedules'])) {
                            $scheduleRecordIds = [];
                            foreach ($record['fields']['schedules']  as  $value) {
                                $scheduleRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->schedules()->sync($scheduleRecordIds);
                        }


                        if (isset($record['fields']['addresses'])) {
                            $addressRecordIds = [];
                            foreach ($record['fields']['addresses']  as  $value) {
                                $addressRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->address()->sync($addressRecordIds);
                        }

                        if (isset($record['fields']['languages'])) {
                            $languageRecordIds = [];
                            foreach ($record['fields']['languages']  as  $value) {
                                $languageRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->languages()->sync($languageRecordIds);
                        }

                        if (isset($record['fields']['accessibility'])) {
                            $accessebilityRecordIds = [];
                            foreach ($record['fields']['accessibility']  as  $value) {
                                $accessebilityRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $location->accessibilities()->sync($accessebilityRecordIds);
                        }

                        if (isset($record['fields']['location_type'])) {
                            $locationTypes = [];
                            foreach ($record['fields']['location_type']  as  $value) {
                                $locationTypes[] = $strtointclass->string_to_int($value);
                            }
                            $location->location_type = implode(', ', $locationTypes);
                        }

                        $location->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Locations')->first();
            $airtable->records = Location::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            dd($th);
            Log::error('Error in location: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
