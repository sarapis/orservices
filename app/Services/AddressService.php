<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Address;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\City;
use App\Model\State;
use Illuminate\Support\Facades\Log;

class AddressService
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

            // Address::truncate();
            
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('addresses');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {

                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_address = Address::where('address_recordid', $recordId)->where('address_1', isset($record['fields']['address_1']) ? $record['fields']['address_1'] : null)->first();
                    if ($old_address == null) {

                        $address = new Address();
                        $strtointclass = new Stringtoint();

                        $address->address_recordid = $strtointclass->string_to_int($record['id']);

                        $address->address_1 = isset($record['fields']['address_1']) ? $record['fields']['address_1'] : null;
                        $address->address_2 = isset($record['fields']['address_2']) ? $record['fields']['address_2'] : null;
                        $address->address_city = isset($record['fields']['city']) ? $record['fields']['city'] : null;
                        if (isset($record['fields']['city']) && $record['fields']['city'])
                            City::firstOrCreate(['city' => $record['fields']['city']]);
                        $address->address_state_province = isset($record['fields']['state_province']) ? $record['fields']['state_province'] : null;
                        if (isset($record['fields']['state_province']) && $record['fields']['state_province'])
                            State::firstOrCreate(['state' => $record['fields']['state_province']]);
                        $address->address_postal_code = isset($record['fields']['postal_code']) ? $record['fields']['postal_code'] : null;
                        $address->address_region = isset($record['fields']['region']) ? $record['fields']['region'] : null;
                        $address->address_country = isset($record['fields']['country']) ? $record['fields']['country'] : null;
                        $address->address_attention = isset($record['fields']['attention']) ? $record['fields']['attention'] : null;
                        // $address->address_type = isset($record['fields']['x-type'])  ? (is_array(is_array($record['fields']['x-type'])) ? implode(',', $record['fields']['x-type']) : $record['fields']['x-type']) : null;

                        if (isset($record['fields']['location'])) {
                            $i = 0;
                            $addressRecordIds = [];
                            foreach ($record['fields']['location'] as $value) {
                                $addressRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $address->address_locations = implode(', ', $addressRecordIds);
                        }

                        $address->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Physical_Address')->first();
            $airtable->records = Address::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Address:' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
