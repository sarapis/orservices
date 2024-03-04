<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Airtablekeyinfo;
use App\Model\Organization;
use App\Model\OrganizationDetail;
use App\Model\OrganizationStatus;
use App\Model\OrganizationTag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrganizationService
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

            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('organizations');

            do {


                $response = $request->getResponse();

                $airtable_response = json_decode($response, TRUE);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_organization = Organization::where('organization_recordid', $recordId)->where('organization_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if (empty($old_organization)) {
                        $organization = new Organization();
                        $strtointclass = new Stringtoint();
                        $organization->organization_recordid = $strtointclass->string_to_int($record['id']);
                        $organization->organization_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        if (isset($record['fields']['logo'])) {
                            $organization->organization_logo_x = $record['fields']['logo'];
                        }

                        $organization->organization_alternate_name = isset($record['fields']['alternate_name']) ? $record['fields']['alternate_name'] : null;
                        $organization->organization_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        $organization->organization_description = mb_convert_encoding($organization->organization_description, "HTML-ENTITIES", "UTF-8");

                        $organization->organization_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                        $organization->organization_url = isset($record['fields']['website']) ? $record['fields']['website'] : null;
                        $organization->organization_status_x = isset($record['fields']['x-status']) ? $record['fields']['x-status'] : null;

                        $organization->organization_legal_status = isset($record['fields']['legal_status']) ? $record['fields']['legal_status'] : null;

                        $organization->organization_year_incorporated = isset($record['fields']['year_incorporated']) ? $record['fields']['year_incorporated'] : null;
                        $tag = isset($record['fields']['x-tags']) ? $record['fields']['x-tags'] : null;
                        $organization_tag = OrganizationTag::where('tag', 'LIKE', '%' . $tag . '%')->first();
                        if ($organization_tag) {
                            $orgTag = $organization_tag->id;
                        } else {
                            $organization_tag = OrganizationTag::create([
                                'tag' => $tag
                            ]);
                            $orgTag = $organization_tag->id;
                        }
                        $organization->organization_tag = $orgTag;

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $organization->organization_services = implode(', ', $serviceRecordIds);
                            $organization->getServices()->sync($serviceRecordIds);
                        }

                        if (isset($record['fields']['phones'])) {
                            $i = 0;
                            foreach ($record['fields']['phones'] as $value) {

                                $organizationphone = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_phones = $organization->organization_phones . ',' . $organizationphone;
                                else
                                    $organization->organization_phones = $organizationphone;
                                $i++;
                            }
                        }


                        // if (isset($record['fields']['locations'])) {
                        //     $i = 0;
                        //     foreach ($record['fields']['locations'] as $value) {

                        //         $organizationlocation = $strtointclass->string_to_int($value);

                        //         if ($i != 0)
                        //             $organization->organization_locations = $organization->organization_locations . ',' . $organizationlocation;
                        //         else
                        //             $organization->organization_locations = $organizationlocation;
                        //         $i++;
                        //     }
                        // }
                        // $organization->organization_contact = isset($record['fields']['contacts']) ? implode(",", $record['fields']['contacts']) : null;
                        // $organization->organization_contact = $strtointclass->string_to_int($organization->organization_contact);

                        if (isset($record['fields']['x-details'])) {
                            $i = 0;
                            foreach ($record['fields']['x-details'] as $value) {
                                $organization_detail = new OrganizationDetail();
                                $organization_detail->organization_recordid = $organization->organization_recordid;
                                $organization_detail->detail_recordid = $strtointclass->string_to_int($value);
                                $organization_detail->save();
                                $organizationdetail = $strtointclass->string_to_int($value);

                                if ($i != 0)
                                    $organization->organization_details = $organization->organization_details . ',' . $organizationdetail;
                                else
                                    $organization->organization_details = $organizationdetail;
                                $i++;
                            }
                        }

                        if (isset($record['fields']['fAIRS Taxonomy-x'])) {
                            $i = 0;
                            foreach ($record['fields']['AIRS Taxonomy-x'] as $value) {

                                if ($i != 0)
                                    $organization->organization_airs_taxonomy_x = $organization->organization_airs_taxonomy_x . ',' . $value;
                                else
                                    $organization->organization_airs_taxonomy_x = $value;
                                $i++;
                            }
                        }

                        $fundingRecordid = [];

                        if (isset($record['fields']['funding'])) {
                            foreach ($record['fields']['funding'] as $value) {
                                $fundingRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $organization->funding = implode(',', $fundingRecordid);
                            $organization->fundings()->sync($fundingRecordid);
                        }

                        $identifierRecordid = [];

                        if (isset($record['fields']['organization_identifier'])) {
                            foreach ($record['fields']['organization_identifier'] as $value) {
                                $identifierRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $organization->identifiers()->sync($identifierRecordid);
                        }

                        $phonesRecordid = [];

                        if (isset($record['fields']['phones'])) {
                            foreach ($record['fields']['phones'] as $value) {
                                $phonesRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $organization->phones()->sync($phonesRecordid);
                        }

                        $programsRecordid = [];

                        if (isset($record['fields']['programs'])) {
                            foreach ($record['fields']['programs'] as $value) {
                                $programsRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $organization->program()->sync($programsRecordid);
                        }

                        $locationsRecordid = [];

                        if (isset($record['fields']['locations'])) {
                            foreach ($record['fields']['locations'] as $value) {
                                $locationsRecordid[] = $strtointclass->string_to_int($value);
                            }
                            // $organization->locations()->sync($locationsRecordid);
                            $organization->organization_locations = implode(', ', $locationsRecordid);
                        }

                        $contactRecordid = [];

                        if (isset($record['fields']['contacts'])) {
                            foreach ($record['fields']['contacts'] as $value) {
                                $contactRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $organization->organization_contact = implode(',', $contactRecordid);
                            $organization->contacts()->sync($contactRecordid);
                        }

                        if (isset($record['fields']['parent_organization'])) {
                            foreach ($record['fields']['parent_organization'] as $value) {
                                $organization->parent_organization = $strtointclass->string_to_int($value);
                            }
                        }

                        $organization->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Organizations')->first();
            $airtable->records = Organization::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Organization sync : ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
