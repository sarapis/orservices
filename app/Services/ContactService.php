<?php

namespace App\Services;

use App\Functions\Airtable;
use App\Model\Airtable_v2;
use App\Model\Contact;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function import_airtable_v3(string $access_token, string $base_url)
    {
        try {
            $airtable = new Airtable(array(
                'access_token' => $access_token,
                'base' => $base_url,
            ));

            $request = $airtable->getContent('contacts');

            do {

                $response = $request->getResponse();

                $airtable_response = json_decode($response, true);

                foreach ($airtable_response['records'] as $record) {
                    $strtointclass = new Stringtoint();
                    $recordId = $strtointclass->string_to_int($record['id']);
                    $old_contact = Contact::where('contact_recordid', $recordId)->where('contact_name', isset($record['fields']['name']) ? $record['fields']['name'] : null)->first();
                    if (empty($old_contact)) {
                        $contact = new Contact();
                        $strtointclass = new Stringtoint();

                        $contact->contact_recordid = $strtointclass->string_to_int($record['id']);

                        $contact->contact_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;


                        // $contact->contact_organizations = isset($record['fields']['organizations']) ? implode(",", $record['fields']['organizations']) : null;

                        // $contact->contact_organizations = $strtointclass->string_to_int($contact->contact_organizations);

                        // $contact->contact_services = isset($record['fields']['services']) ? implode(",", $record['fields']['services']) : null;

                        // $contact->contact_services = $strtointclass->string_to_int($contact->contact_services);

                        $contact->contact_title = isset($record['fields']['title']) ? $record['fields']['title'] : null;
                        $contact->contact_department = isset($record['fields']['department']) ? $record['fields']['department'] : null;
                        $contact->contact_email = isset($record['fields']['email']) ? $record['fields']['email'] : null;
                        // $contact->contact_phones = isset($record['fields']['phones']) ? implode(",", $record['fields']['phones']) : null;

                        // $contact->contact_phones = $strtointclass->string_to_int($contact->contact_phones);

                        if (isset($record['fields']['organizations'])) {
                            $organizationRecordIds = [];
                            foreach ($record['fields']['organizations'] as $value) {
                                $organizationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $contact->contact_organizations = implode(', ', $organizationRecordIds);
                            $contact->organizations()->sync($organizationRecordIds);
                        }

                        if (isset($record['fields']['locations'])) {
                            $locationRecordIds = [];
                            foreach ($record['fields']['locations'] as $value) {
                                $locationRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $contact->locations = implode(', ', $locationRecordIds);
                        }

                        if (isset($record['fields']['service_at_locations'])) {
                            $serviceAtLocationIds = [];
                            foreach ($record['fields']['service_at_locations'] as $value) {
                                $serviceAtLocationIds[] = $strtointclass->string_to_int($value);
                            }
                            $contact->service_at_locations = implode(', ', $serviceAtLocationIds);
                        }

                        if (isset($record['fields']['services'])) {
                            $serviceRecordIds = [];
                            foreach ($record['fields']['services'] as $value) {
                                $serviceRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $contact->contact_services = implode(', ', $serviceRecordIds);
                        }

                        if (isset($record['fields']['phones'])) {
                            $phoneRecordIds = [];
                            foreach ($record['fields']['phones'] as $value) {
                                $phoneRecordIds[] = $strtointclass->string_to_int($value);
                            }
                            $contact->contact_phones = implode(', ', $phoneRecordIds);
                        }

                        $contact->save();
                    }
                }
            } while ($request = $response->next());

            $date = date("Y/m/d H:i:s");
            $airtable = Airtable_v2::where('name', '=', 'Contacts')->first();
            $airtable->records = Contact::count();
            $airtable->syncdate = $date;
            $airtable->save();
        } catch (\Throwable $th) {
            Log::error('Error in Contact Import V3: ' . $th->getMessage());
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
