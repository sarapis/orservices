<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Identifier;
use Illuminate\Support\Facades\Log;

class IdentifierService
{
    public function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('organization_identifier');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $oldIdentifier = Identifier::where('identifier_recordid', $recordId)->first();
                    if (empty($oldIdentifier)) {
                        $Identifier = new Identifier();
                        $strtointclass = new Stringtoint();

                        $Identifier->identifier_recordid = $strtointclass->string_to_int($record['id']);

                        $Identifier->identifier = isset($record['fields']['identifier']) ? $record['fields']['identifier'] : null;
                        $Identifier->identifier_scheme = isset($record['fields']['identifier_scheme']) ? $record['fields']['identifier_scheme'] : null;
                        $Identifier->identifier_type = isset($record['fields']['identifier_type']) ? $record['fields']['identifier_type'] : null;

                        if (isset($record['fields']['organizations'])) {
                            $organizationRecordIds = [];
                            foreach ($record['fields']['organizations'] as $value) {
                                $organizationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $Identifier->organizations()->sync($organizationRecordIds);
                        }

                        $Identifier->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");

            Airtable_v2::firstOrCreate(
                [
                    'name' => 'Identifiers',
                ],
                [
                    'records' => Identifier::count(),
                    'syncdate' => $date
                ]
            );
        } catch (\Throwable $th) {
            Log::error('Error in Identifiers Import V3: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
