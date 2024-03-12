<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Taxonomy;
use App\Model\TaxonomyType;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

class TaxonomyService
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

            // Taxonomy::truncate();
            // $airtable = new Airtable(array(
            //     'access_token'   => env('AIRTABLE_access_token'),
            //     'base'      => env('AIRTABLE_BASE_URL'),
            // ));
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('taxonomy_terms');

            do {
                $response = $request->getResponse();
                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_taxonomy = Taxonomy::where('taxonomy_recordid', $recordId)->where('taxonomy_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();

                    if ($old_taxonomy == null) {
                        $taxonomy = new Taxonomy();
                        $strtointclass = new Stringtoint();

                        $taxonomy->taxonomy_recordid = $strtointclass->string_to_int($record['id']);
                        $taxonomy->taxonomy_id = $record['id'];
                        // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                        $taxonomy->taxonomy_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        $taxonomy->term_uri = isset($record['fields']['term_uri']) ? $record['fields']['term_uri'] : null;
                        $taxonomy->code = isset($record['fields']['code']) ? $record['fields']['code'] : null;

                        $parent_names = isset($record['fields']['parent']) ? $record['fields']['parent'] : [];
                        $parent_name_ids = [];
                        foreach ($parent_names as $key => $parent_name) {
                            $parent_name_ids[] = $strtointclass->string_to_int($parent_name);
                        }
                        $taxonomy->taxonomy_parent_name = count($parent_name_ids) > 0 ? implode(',', $parent_name_ids) : null;

                        // $taxonomy->taxonomy_vocabulary = isset($record['fields']['vocabulary']) ? $record['fields']['vocabulary'] : null;
                        // $taxonomyName = isset($record['fields']['taxonomy']) ? $record['fields']['taxonomy'] : '[]';
                        // $taxonomy_type = TaxonomyType::firstOrCreate(
                        //     ['name' => $taxonomyName],
                        //     [
                        //         'name' => $taxonomyName,
                        //         'taxonomy_type_recordid' => TaxonomyType::max('taxonomy_type_recordid') + 1
                        //     ]
                        // );
                        // $taxonomyIds = isset($record['fields']['taxonomy']) ? $record['fields']['taxonomy'] : [];

                        // $taxonomy_ids = null;
                        // foreach ($taxonomyIds as $key => $taxonomyid) {
                        //     if ($key == 0) {
                        //         $taxonomy_ids = $strtointclass->string_to_int($taxonomyid);
                        //     } else {

                        //         $taxonomy_ids = $taxonomy_ids . ',' . $strtointclass->string_to_int($taxonomyid);
                        //     }
                        // }

                        if (isset($record['fields']['x-icon_dark']) && is_array($record['fields']['x-icon_dark'])) {
                            $icon_dark = $record['fields']['x-icon_dark'];
                            foreach ($icon_dark as $key => $value) {
                                $path = $value['url'];
                                $filename = $value['filename'];
                                Image::make($path)->save(public_path('/uploads/images/' . $filename));
                                $taxonomy->category_logo = '/uploads/images/' . $filename;
                            }
                        }
                        if (isset($record['fields']['x-icon_light']) && is_array($record['fields']['x-icon_light'])) {
                            $icon_light = $record['fields']['x-icon_light'];
                            foreach ($icon_light as $key => $value) {
                                $path = $value['url'];
                                $filename = $value['filename'];
                                Image::make($path)->save(public_path('/uploads/images/' . $filename));
                                $taxonomy->category_logo_white = '/uploads/images/' . $filename;
                            }
                        }
                        // if ($taxonomy_type) {

                        //     $taxonomy->taxonomy = $taxonomy_type->taxonomy_type_recordid;
                        // }
                        // $taxonomy->taxonomy = $taxonomy_ids;

                        $taxonomies = isset($record['fields']['taxonomy']) ? $record['fields']['taxonomy'] : [];
                        $taxonomyIds = [];
                        foreach ($taxonomies as $key => $xtaxonomy) {
                            $taxonomyIds[] = $strtointclass->string_to_int($xtaxonomy);
                        }
                        $taxonomy->taxonomy = implode(',', $taxonomyIds);
                        $taxonomy->taxonomy_type()->sync($taxonomyIds);

                        $taxonomy->taxonomy_x_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;
                        $taxonomy->taxonomy_x_notes = isset($record['fields']['x-notes']) ? $record['fields']['x-notes'] : null;

                        $color = substr(str_shuffle('AABBCCDDEEFF00112233445566778899AABBCCDDEEFF00112233445566778899AABBCCDDEEFF00112233445566778899'), 0, 6);
                        $taxonomy->badge_color = $color;


                        if (isset($record['fields']['services'])) {
                            $serviceIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceIds[] = $strtointclass->string_to_int($value);
                            }
                            $taxonomy->taxonomy_services = implode(',', $serviceIds);
                            $taxonomy->service()->sync($serviceIds);
                        }

                        $taxonomy->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Taxonomy_Term')->first();
            $airtable->records = Taxonomy::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Taxonomy: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
