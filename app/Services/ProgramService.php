<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Program;
use Illuminate\Support\Facades\Log;

class ProgramService
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

            // Program::truncate();
            // ServiceProgram::truncate();
            // OrganizationProgram::truncate();

            // $airtable = new Airtable(array(
            //     'access_token'   => env('AIRTABLE_access_token'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('programs');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_program = Program::where('program_recordid', $recordId)->where('name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_program == null) {
                        $program = new Program();
                        $strtointclass = new Stringtoint();

                        $program->program_recordid = $strtointclass->string_to_int($record['id']);

                        $program->alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                        $program->name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        $program->description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        if (isset($record['fields']['organizations'])) {
                            $orgIds = [];
                            foreach ($record['fields']['organizations'] as $value) {
                                $orgIds[] = $strtointclass->string_to_int($value);
                            }
                            $program->organizations = implode(',', $orgIds);

                            $program->organization()->sync($orgIds);
                        }

                        if (isset($record['fields']['services'])) {
                            $serviceIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceIds[] = $strtointclass->string_to_int($value);
                            }
                            $program->services = implode(',', $serviceIds);
                            $program->service()->sync($serviceIds);
                        }

                        $program->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Programs')->first();
            $airtable->records = Program::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Program:' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
