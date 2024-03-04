<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Funding;
use Illuminate\Support\Facades\Log;

class FundingService
{
    public function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('funding');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_funding = Funding::where('funding_recordid', $recordId)->first();
                    if (empty($old_funding)) {
                        $funding = new Funding();
                        $strtointclass = new Stringtoint();

                        $funding->funding_recordid = $strtointclass->string_to_int($record['id']);

                        $funding->source = isset($record['fields']['source']) ? $record['fields']['source'] : null;

                        if (isset($record['fields']['organizations'])) {
                            $organizationRecordIds = [];
                            foreach ($record['fields']['organizations'] as $value) {
                                $organizationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $funding->organizationsData()->sync($organizationRecordIds);
                        }

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $funding->services()->sync($serviceRecordIds);
                        }

                        $funding->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");

            Airtable_v2::firstOrCreate(
                [
                    'name' => 'Fundings',
                ],
                [
                    'records' => Funding::count(),
                    'syncdate' => $date
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Error in Funding Import V3: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
