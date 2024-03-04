<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Schedule;
use Illuminate\Support\Facades\Log;

class ScheduleService
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

            // Schedule::truncate();
            // $airtable = new Airtable(array(
            //     'access_token'   => env('AIRTABLE_access_token'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('schedules');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_schedule = Schedule::where('schedule_recordid', $recordId)->where('name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_schedule == null) {
                        $schedule = new Schedule();
                        $strtointclass = new Stringtoint();

                        $schedule->schedule_recordid = $strtointclass->string_to_int($record['id']);
                        $schedule->name = isset($record['fields']['name']) ? $record['fields']['name'] : null;

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            // for save service
                            $servicesId_list = $serviceRecordIds;
                            $current_services = $schedule->get_services()->allRelatedIds()->toArray();
                            $res = array_unique(array_merge($current_services, $servicesId_list));
                            $schedule->get_services()->sync($res);
                            $schedule->services = implode(',', $res);
                        }

                        if (isset($record['fields']['locations'])) {
                            $locationRecordIds = [];
                            foreach ($record['fields']['locations'] as $value) {
                                $locationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            // for save location
                            $locationsId_list = $locationRecordIds;
                            $current_locations = $schedule->get_locations()->allRelatedIds()->toArray();
                            $res = array_unique(array_merge($current_locations, $locationsId_list));
                            $schedule->get_locations()->sync($res);
                            $schedule->locations = implode(',', $res);
                        }

                        $schedule->description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                        $schedule->phones = isset($record['fields']['x-phones']) ? implode(",", $record['fields']['x-phones']) : null;

                        $schedule->byday = isset($record['fields']['byday']) ? (is_array($record['fields']['byday']) ? implode(',', $record['fields']['byday']) : $record['fields']['byday']) : null;
                        $schedule->opens_at = isset($record['fields']['opens_at']) ? $record['fields']['opens_at'] : null;
                        $schedule->closes_at = isset($record['fields']['closes_at']) ? $record['fields']['closes_at'] : null;
                        $schedule->dtstart = isset($record['fields']['dtstart']) ? $record['fields']['dtstart'] : null;
                        $schedule->until = isset($record['fields']['until']) ? $record['fields']['until'] : null;
                        $schedule->special = isset($record['fields']['x-special']) ? $record['fields']['x-special'] : null;
                        $schedule->closed = isset($record['fields']['x-closed']) ? $record['fields']['x-closed'] : null;
                        $schedule->service_at_location = isset($record['fields']['service_at_location']) ? $record['fields']['service_at_location'] : null;
                        $schedule->freq = isset($record['fields']['freq']) ? $record['fields']['freq'] : null;
                        $schedule->valid_from = isset($record['fields']['valid_from']) ? $record['fields']['valid_from'] : null;
                        $schedule->valid_to = isset($record['fields']['valid_to']) ? $record['fields']['valid_to'] : null;
                        $schedule->wkst = isset($record['fields']['wkst']) ? $record['fields']['wkst'] : null;
                        $schedule->interval = isset($record['fields']['interval']) ? $record['fields']['interval'] : null;
                        $schedule->count = isset($record['fields']['count']) ? $record['fields']['count'] : null;
                        $schedule->byweekno = isset($record['fields']['byweekno']) ? $record['fields']['byweekno'] : null;
                        $schedule->bymonthday = isset($record['fields']['bymonthday']) ? $record['fields']['bymonthday'] : null;
                        $schedule->byyearday = isset($record['fields']['byyearday']) ? $record['fields']['byyearday'] : null;
                        $schedule->timezone = isset($record['fields']['timezone']) ? $record['fields']['timezone'] : null;
                        $schedule->notes = isset($record['fields']['notes']) ? $record['fields']['notes'] : null;
                        $schedule->attending_type = isset($record['fields']['attending_type']) ? $record['fields']['attending_type'] : null;
                        $schedule->schedule_link = isset($record['fields']['schedule_link']) ? $record['fields']['schedule_link'] : null;
                        $schedule->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Schedule')->first();
            $airtable->records = Schedule::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Schedule: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
