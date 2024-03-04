<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\TaxonomyType;
use Illuminate\Support\Facades\Log;

class TaxonomyTypeService
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

            // TaxonomyType::truncate();
            // TaxonomyTerm::truncate();
            // AdditionalTaxonomy::truncate();
            // $airtable = new Airtable(array(
            //     'access_token'   => env('AIRTABLE_access_token'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('taxonomy');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);


                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_taxonomy_type = TaxonomyType::where('taxonomy_type_recordid', $recordId)->where('name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_taxonomy_type == null) {
                        $taxonomy = new TaxonomyType();
                        $strtointclass = new Stringtoint();

                        $taxonomy->taxonomy_type_recordid = $strtointclass->string_to_int($record['id']);
                        $taxonomy->name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        $taxonomy->version = isset($record['fields']['version']) ? $record['fields']['version'] : null;
                        $taxonomy->reference_url = isset($record['fields']['uri']) ? $record['fields']['uri'] : null;
                        $taxonomy->notes = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        $taxonomy_terms = isset($record['fields']['taxonomy_terms']) ? $record['fields']['taxonomy_terms'] : [];
                        $taxonomy_terms_ids = [];
                        foreach ($taxonomy_terms as $key => $taxonomy_term_id) {
                            $taxonomy_terms_ids[] = $strtointclass->string_to_int($taxonomy_term_id);
                        }
                        $taxonomy->taxonomy_term()->sync($taxonomy_terms_ids);

                        $taxonomy->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'x_Taxonomy')->first();
            $airtable->records = TaxonomyType::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in x-taxonomies: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
