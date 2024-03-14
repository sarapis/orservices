<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\InterpretationService;
use App\Model\Service;
use App\Model\ServiceAddress;
use App\Model\ServiceContact;
use App\Model\ServiceDetail;
use App\Model\ServiceLocation;
use App\Model\ServiceOrganization;
use App\Model\ServicePhone;
use App\Model\ServiceSchedule;
use App\Model\ServiceTaxonomy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ServiceDataService
{
    public function service_airtable_v3($access_token, $base_url)
    {
        try {
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));
            $request = $airtable->getContent('services');
            $size = '';
            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_service = Service::where('service_recordid', $recordId)->where('service_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if ($old_service == null) {

                        $service = new Service();
                        $service->service_recordid = $strtointclass->string_to_int($record['id']);

                        $service->service_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                        $service->minimum_age = isset($record['fields']['minimum_age']) ? $record['fields']['minimum_age'] : null;
                        $service->eligibility_description = isset($record['fields']['eligibility_description']) ? $record['fields']['eligibility_description'] : null;
                        $service->service_alert = isset($record['fields']['alert']) ? $record['fields']['alert'] : null;
                        $service->assured_email = isset($record['fields']['assured_email']) ? $record['fields']['assured_email'] : null;
                        $service->assured_date = isset($record['fields']['assured_date']) ? date('Y-m-d', strtotime($record['fields']['assured_date'])) : null;

                        if (isset($record['fields']['organizations'])) {
                            foreach ($record['fields']['organizations'] as $key => $value) {
                                if ($key == 0) {
                                    $service_organization = new ServiceOrganization();
                                    $service_organization->service_recordid = $service->service_recordid;
                                    $service_organization->organization_recordid = $strtointclass->string_to_int($value);
                                    $service_organization->save();
                                    $serviceorganization = $strtointclass->string_to_int($value);

                                    $service->service_organization = $serviceorganization;
                                }
                            }
                        }

                        $service->service_alternate_name = isset($record['fields']['alternative_name']) ? $record['fields']['alternative_name'] : null;
                        $service->service_description = isset($record['fields']['description']) ? $record['fields']['description'] : null;

                        if (isset($record['fields']['x-locations'])) {
                            $i = 0;
                            foreach ($record['fields']['x-locations'] as $value) {
                                $service_location = new ServiceLocation();
                                $service_location->service_recordid = $service->service_recordid;
                                $service_location->location_recordid = $strtointclass->string_to_int($value);
                                $service_location->save();
                                $servicelocation = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $service->service_locations = $service->service_locations . ',' . $servicelocation;
                                } else {
                                    $service->service_locations = $servicelocation;
                                }

                                $i++;
                            }
                        }

                        $service->service_url = isset($record['fields']['url']) ? $record['fields']['url'] : null;
                        $service->service_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;


                        $service->service_status = isset($record['fields']['status']) ? $record['fields']['status'] : null;

                         if (isset($record['fields']['taxonomy_terms'])) {
                             $i = 0;
                             $servicetaxonomy = [];
                             foreach ($record['fields']['taxonomy_terms'] as $value) {
//                                 $service_taxonomy = new ServiceTaxonomy();
//                                 $service_taxonomy->service_recordid = $service->service_recordid;
//                                 $service_taxonomy->taxonomy_recordid = $strtointclass->string_to_int($value);
//                                 $service_taxonomy->save();
                                 $servicetaxonomy[] = $strtointclass->string_to_int($value);

//                                 if ($i != 0) {
//                                     $service->service_taxonomy = $service->service_taxonomy . ',' . $servicetaxonomy;
//                                 } else {
//                                     $service->service_taxonomy = $servicetaxonomy;
//                                 }

//                                 $i++;
                             }
                             $service->service_taxonomy = count($servicetaxonomy) > 0 ? implode(',', $servicetaxonomy) : null ;
                         }

                        $service->service_application_process = isset($record['fields']['application_process']) ? $record['fields']['application_process'] : null;
                        // $service->service_wait_time = isset($record['fields']['wait_time']) ? $record['fields']['wait_time'] : null;
                        $service->service_fees = isset($record['fields']['fees_description']) ? $record['fields']['fees_description'] : null;
                        $service->service_accreditations = isset($record['fields']['accreditations']) ? $record['fields']['accreditations'] : null;
                        $service->service_licenses = isset($record['fields']['licenses']) ? $record['fields']['licenses'] : null;

                        if (isset($record['fields']['phones'])) {
                            $i = 0;
                            foreach ($record['fields']['phones'] as $value) {
                                $service_phone = new ServicePhone();
                                $service_phone->service_recordid = $service->service_recordid;
                                $service_phone->phone_recordid = $strtointclass->string_to_int($value);
                                $service_phone->save();
                                $servicephone = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $service->service_phones = $service->service_phones . ',' . $servicephone;
                                } else {
                                    $service->service_phones = $servicephone;
                                }

                                $i++;
                            }
                        }

                        if (isset($record['fields']['schedules'])) {
                            $i = 0;
                            foreach ($record['fields']['schedules'] as $value) {
                                $service_schedule = new ServiceSchedule();
                                $service_schedule->service_recordid = $service->service_recordid;
                                $service_schedule->schedule_recordid = $strtointclass->string_to_int($value);
                                $service_schedule->save();
                                $serviceschedule = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $service->service_schedule = $service->service_schedule . ',' . $serviceschedule;
                                } else {
                                    $service->service_schedule = $serviceschedule;
                                }

                                $i++;
                            }
                        }

                        if (isset($record['fields']['contacts'])) {
                            $i = 0;
                            foreach ($record['fields']['contacts'] as $value) {
                                $service_contact = new ServiceContact();
                                $service_contact->service_recordid = $service->service_recordid;
                                $service_contact->contact_recordid = $strtointclass->string_to_int($value);
                                $service_contact->save();
                                $servicecontact = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $service->service_contacts = $service->service_contacts . ',' . $servicecontact;
                                } else {
                                    $service->service_contacts = $servicecontact;
                                }

                                $i++;
                            }
                        }

                        // if (isset($record['fields']['x-details'])) {
                        //     $i = 0;
                        //     foreach ($record['fields']['x-details'] as $value) {
                        //         $service_detail = new ServiceDetail();
                        //         $service_detail->service_recordid = $service->service_recordid;
                        //         $service_detail->detail_recordid = $strtointclass->string_to_int($value);
                        //         $service_detail->save();
                        //         $servicedetail = $strtointclass->string_to_int($value);

                        //         if ($i != 0) {
                        //             $service->service_details = $service->service_details . ',' . $servicedetail;
                        //         } else {
                        //             $service->service_details = $servicedetail;
                        //         }

                        //         $i++;
                        //     }
                        // }

                        if (isset($record['fields']['x-address'])) {
                            $i = 0;
                            foreach ($record['fields']['x-address'] as $value) {
                                $service_addresses = new ServiceAddress();
                                $service_addresses->service_recordid = $service->service_recordid;
                                $service_addresses->address_recordid = $strtointclass->string_to_int($value);
                                $service_addresses->save();
                                $serviceaddress = $strtointclass->string_to_int($value);

                                if ($i != 0) {
                                    $service->service_address = $service->service_address . ',' . $serviceaddress;
                                } else {
                                    $service->service_address = $serviceaddress;
                                }

                                $i++;
                            }
                        }

                        $programsIds = [];
                        if (isset($record['fields']['programs'])) {
                            foreach ($record['fields']['programs'] as $value) {
                                $programsIds[] = $strtointclass->string_to_int($value);
                            }
                        }
                        $service->program()->sync($programsIds);

                        $attributeIds = [];
                        if (isset($record['fields']['attribute'])) {
                            foreach ($record['fields']['attribute'] as $value) {
                                $attributeIds[] = $strtointclass->string_to_int($value);
                            }
                            $service->attribute = implode(', ', $programsIds);
                        }

                        $serviceInterpretationArray = [];
                        if (isset($record['fields']['interpretation_services'])) {
                            $i = 0;
                            foreach ($record['fields']['interpretation_services'] as $value) {
                                $serviceInterpretation = InterpretationService::firstOrCreate(
                                    [
                                        'name' => $value
                                    ],
                                    [
                                        'created_by' => Auth::id()
                                    ]
                                );
                                $serviceInterpretationArray[] = $serviceInterpretation->id;
                            }
                            $service->service_interpretation = implode(',', $serviceInterpretationArray);
                        }

                        $fundingRecordid = [];

                        if (isset($record['fields']['funding'])) {
                            foreach ($record['fields']['funding'] as $value) {
                                $fundingRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $service->funding = implode(',', $fundingRecordid);
                            $service->fundings()->sync($fundingRecordid);
                        }

                        $costOptionRecordid = [];

                        if (isset($record['fields']['cost_option'])) {
                            foreach ($record['fields']['cost_option'] as $value) {
                                $costOptionRecordid[] = $strtointclass->string_to_int($value);
                            }
                            $service->costOptions()->sync($costOptionRecordid);
                        }

                        // $service->service_metadata = isset($record['fields']['metadata']) ? $record['fields']['metadata'] : null;

                        // $service->service_airs_taxonomy_x = isset($record['fields']['AIRS Taxonomy-x']) ? implode(",", $record['fields']['AIRS Taxonomy-x']) : null;
                        $service->created_at = Carbon::now();
                        $service->updated_at = Carbon::now();

                        $service->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Services')->first();
            $airtable->records = Service::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Service: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
