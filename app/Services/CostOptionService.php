<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\CostOption;
use Illuminate\Support\Facades\Log;

class CostOptionService
{
    public function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('cost_option');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $oldCostOption = CostOption::where('cost_recordid', $recordId)->first();
                    if (empty($oldCostOption)) {
                        $costOption = new CostOption();
                        $strtointclass = new Stringtoint();

                        $costOption->cost_recordid = $strtointclass->string_to_int($record['id']);

                        $costOption->valid_from = isset($record['fields']['valid_from']) ? date('Y-m-d', strtotime($record['fields']['valid_from'])) : null;
                        $costOption->valid_to = isset($record['fields']['valid_to']) ? date('Y-m-d', strtotime($record['fields']['valid_to'])) : null;
                        $costOption->option = isset($record['fields']['option']) ? $record['fields']['option'] : null;
                        $costOption->currency = isset($record['fields']['currency']) ? $record['fields']['currency'] : null;
                        $costOption->amount = isset($record['fields']['amount']) ? $record['fields']['amount'] : null;
                        $costOption->amount_description = isset($record['fields']['amount_description']) ? $record['fields']['amount_description'] : null;

                        if (isset($record['fields']['attributes'])) {
                            $attributesRecordIds = [];
                            foreach ($record['fields']['attributes'] as $value) {
                                $attributesRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $costOption->attributes = implode(',', $attributesRecordIds);
                        }

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $costOption->services()->sync($serviceRecordIds);
                        }

                        $costOption->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");

            Airtable_v2::firstOrCreate(
                [
                    'name' => 'Cost_Options',
                ],
                [
                    'records' => CostOption::count(),
                    'syncdate' => $date
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Error in Cost options Import V3: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
