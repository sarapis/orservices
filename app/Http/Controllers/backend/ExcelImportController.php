<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Imports\NycImport;
use App\Imports\NypImport;
use App\Imports\NysImport;
use App\Model\Address;
use App\Model\Contact;
use App\Model\ContactFormat;
use App\Model\Layout;
use App\Model\Location;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function importContact()
    {
        $layout = Layout::find(1);

        return view('backEnd.import.importContact', compact('layout'));
    }
    public function ImportContactExcel(Request $request)
    {
        $this->validate($request, [
            'import_contact' => 'required',
        ]);
        try {
            Contact::truncate();
            Address::truncate();
            Location::truncate();
            ContactFormat::truncate();
            set_time_limit(0);
            ini_set('memory_limit', '8192M');
            if ($request->importType == 'nyc') {
                Excel::import(new NycImport, $request->file('import_contact'));
            } elseif ($request->importType == 'nys') {
                Excel::import(new NysImport, $request->file('import_contact'));
            } else {
                Excel::import(new NypImport, $request->file('import_contact'));
            }

            $ContactFormat = new ContactFormat();
            $ContactFormat->contact_format = $request->importType;
            $ContactFormat->save();

            Session::flash('message', 'Contacts imported successfully!');
            Session::flash('status', 'success');
            return redirect()->back();
        } catch (\Throwable $th) {

            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect()->back();

        }
    }
    public function importOrganization()
    {
        return view('backEnd.import.importOrganization');
    }
    public function importFacility()
    {
        return view('backEnd.import.importFacility');
    }
    // public function ImportContactExcel(Request $request)
    // {
    //     $this->validate($request, [
    //         'import_contact' => 'required',
    //     ]);
    //     try {
    //         if ($request->file('import_contact')) {
    //             $path = $request->file('import_contact')->getRealPath();
    //             $data = \Excel::load($path)->get();

    //             if (!empty($data) && count($data) > 0) {
    //                 $data = $data->toArray();
    //                 Contact::truncate();

    //                 set_time_limit(36000);
    //                 foreach ($data as $key => $contactData) {

    //                     // var_dump($contactData);
    //                     // exit;

    //                     $contact = new Contact;

    //                     $new_recordid = Contact::max('contact_recordid') + 1;
    //                     $contact->contact_recordid = $new_recordid;

    //                     $contact->contact_first_name = $contactData['first_name'];
    //                     $contact->contact_middle_name = $contactData['middle_name'];
    //                     $contact->contact_last_name = $contactData['last_name'];
    //                     $contact->contact_type = $contactData['type'];
    //                     $contact->contact_religious_title = $contactData['religious_title'];
    //                     $contact->contact_pronouns = $contactData['pronouns'];
    //                     $contact->contact_personal_email = $contactData['personal_email'];
    //                     $contact->contact_email = $contactData['work_email'];
    //                     $contact->contact_title = $contactData['position_title'];

    //                     $languageId = [];
    //                     $languageIdList = '';
    //                     if ($contactData['languages_spoken'] != null) {
    //                         // import language id
    //                         $languageSpoken = explode(',', $contactData['languages_spoken']);
    //                         if ($languageSpoken) {
    //                             foreach ($languageSpoken as $key => $languageName) {
    //                                 $getLanguage = AllLanguage::where('language_name', 'LIKE', '%' . $languageName . '%')->first();
    //                                 if ($getLanguage) {
    //                                     array_push($languageId, $getLanguage->id);
    //                                 } else {
    //                                     if ($languageName != null) {
    //                                         $newLanguage = AllLanguage::create([
    //                                             'language' => $languageName,
    //                                         ]);
    //                                         DB::commit();
    //                                         array_push($languageId, $newLanguage->id);
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                         $languageIdList = implode(',', $languageId);
    //                     }

    //                     $contact->contact_languages_spoken = $languageIdList;

    //                     $contact->contact_other_languages = $contactData['other_languages'];
    //                     $contact->flag = 'modified';

    //                     $organization_name = $contactData['organization'];
    //                     $organization_alt_id = $contactData['organization_id'];
    //                     $contact_organization = Organization::where('organization_name', '=', $organization_name)->first();

    //                     $contact_organization_id = $contact_organization ? $contact_organization["organization_recordid"] : '';
    //                     $contact->contact_organizations = $contact_organization_id;
    //                     $contact->contact_organization_id = $organization_alt_id;

    //                     $contact_mailing_address = $contactData['mailing_address'];
    //                     $address_reference_list = explode(", ", $contact_mailing_address);
    //                     if (count($address_reference_list) > 3) {
    //                         $address_1 = $address_reference_list[0];
    //                         $address_zip_code = $address_reference_list[2];
    //                         $address_city = $address_reference_list[3];
    //                     }
    //                     if (count($address_reference_list) == 3) {
    //                         $address_1 = $address_reference_list[0];
    //                         $address_zip_code = $address_reference_list[1];
    //                         $address_city = $address_reference_list[2];
    //                     }
    //                     if (count($address_reference_list) == 2) {
    //                         $address_1 = $address_reference_list[0];
    //                         $address_zip_code = $address_reference_list[1];
    //                     }
    //                     if (count($address_reference_list) == 1) {
    //                         $address_1 = $address_reference_list[0];
    //                     }

    //                     if ($contact_mailing_address) {
    //                         $address = Address::where('address_1', '=', $address_1)->first();
    //                         if ($address) {
    //                             $address_id = $address["address_recordid"];
    //                             $contact->contact_mailing_address = $address_id;
    //                             $location_address_info = $address_id;
    //                         } else {
    //                             $contact->contact_mailing_address = '';
    //                             $location_address_info = '';
    //                         }
    //                     }

    //                     // this section create or find location for organization.
    //                     $location = Location::where('location_address', '=', $location_address_info)->first();

    //                     if ($location != null) {
    //                         $location_recordid = $location->location_recordid;
    //                         $location->location_contact = $contact->contact_recordid;
    //                         $location->location_name = $organization_name;
    //                         $location->save();
    //                     }

    //                     // $locationaddressData = Locationaddress::where('address_recordid', '=', $address->address_recordid)->first();
    //                     // if (!$locationaddressData) {
    //                     //     $locationaddress = new Locationaddress();
    //                     //     $locationaddress->location_recordid = $location->location_recordid;
    //                     //     $locationaddress->address_recordid = $address->address_recordid;
    //                     //     $locationaddress->save();
    //                     // }

    //                     $contact_cell_phones = $contactData['cell_phone'];
    //                     if ($contact_cell_phones) {
    //                         $cell_phone = Phone::where('phone_number', '=', $contact_cell_phones)->first();
    //                         if ($cell_phone != null) {
    //                             $cell_phone_id = $cell_phone["phone_recordid"];
    //                             $contact->contact_cell_phones = $cell_phone_id;
    //                         } else {
    //                             $phone = new Phone;
    //                             $new_recordid = Phone::max('phone_recordid') + 1;

    //                             $phone->phone_recordid = $new_recordid;
    //                             $phone->phone_number = $contact_cell_phones;
    //                             $phone->phone_type = "cell phone";
    //                             $contact->contact_cell_phones = $phone->phone_recordid;
    //                             $phone->save();
    //                         }
    //                     }

    //                     $contact_office_phones = $contactData['office_phone'];
    //                     if ($contact_office_phones) {
    //                         $office_phone = Phone::where('phone_number', '=', $contact_office_phones)->first();
    //                         if ($office_phone != null) {
    //                             $office_phone_id = $office_phone["phone_recordid"];
    //                             $contact->contact_office_phones = $office_phone_id;
    //                         } else {
    //                             $phone = new Phone;
    //                             $new_recordid = Phone::max('phone_recordid') + 1;
    //                             $phone->phone_recordid = $new_recordid;
    //                             $phone->phone_number = $contact_office_phones;
    //                             $phone->phone_type = "office phone";
    //                             $contact->contact_office_phones = $phone->phone_recordid;
    //                             $phone->save();
    //                         }
    //                     }

    //                     $contact_emergency_phones = $contactData['emergency_phone'];
    //                     if ($contact_emergency_phones) {
    //                         $emergency_phone = Phone::where('phone_number', '=', $contact_emergency_phones)->first();
    //                         if ($emergency_phone != null) {
    //                             $emergency_phone_id = $emergency_phone["phone_recordid"];
    //                             $contact->contact_emergency_phones = $emergency_phone_id;
    //                         } else {
    //                             $phone = new Phone;
    //                             $new_recordid = Phone::max('phone_recordid') + 1;
    //                             $phone->phone_recordid = $new_recordid;
    //                             $phone->phone_number = $contact_emergency_phones;
    //                             $phone->phone_type = "emergency phone";
    //                             $contact->contact_emergency_phones = $phone->phone_recordid;
    //                             $phone->save();
    //                         }
    //                     }

    //                     $contact_office_fax_phones = $request->contact_office_fax_phones;
    //                     if ($contact_office_fax_phones) {
    //                         $office_fax_phone = Phone::where('phone_number', '=', $contact_office_fax_phones)->first();
    //                         if ($office_fax_phone != null) {
    //                             $office_fax_phone_id = $office_fax_phone["phone_recordid"];
    //                             $contact->contact_office_fax_phones = $office_fax_phone_id;
    //                         } else {
    //                             $phone = new Phone;
    //                             $new_recordid = Phone::max('phone_recordid') + 1;
    //                             $phone->phone_recordid = $new_recordid;
    //                             $phone->phone_number = $contact_office_fax_phones;
    //                             // $phone->phone_type = "office fax";
    //                             $contact->contact_office_fax_phones = $phone->phone_recordid;
    //                             $phone->save();
    //                         }
    //                     }

    //                     $contact->save();
    //                 }

    //             }
    //             return redirect()->back()->with('success', 'Imported successfully');
    //         }
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', $th->getMessage());
    //     }
    // }
    public function ImportOrganizationExcel(Request $request)
    {
        $this->validate($request, [
            'import_organization' => 'required',
        ]);
        try {
            if ($request->file('import_organization')) {
                $path = $request->file('import_organization')->getRealPath();
                $data = Excel::load($path)->get();
                if (!empty($data) && count($data) > 0) {
                    $data = $data->toArray();
                    Organization::truncate();
                    set_time_limit(36000);
                    foreach ($data as $key => $organizationData) {
                        // var_dump($organizationData);
                        // exit;
                        $organization = new Organization();

                        $new_recordid = Organization::max("organization_recordid") + 1;
                        $organization_recordid = $new_recordid;

                        $organization->organization_name = $organizationData['name'];

                        $location = Location::where('location_name', $organizationData['name'])->first();
                        if ($location) {
                            $location->location_organization = $organization_recordid;
                            $location->save();
                            $organization->organization_locations = $location->location_recordid;
                        }

                        $organization->organization_alt_id = $organizationData['id'];

                        $organization->organization_borough = $organizationData['borough'];
                        $organization->organization_zipcode = $organizationData['zipcode'];

                        // this section for religion

                        $religion = Religion::where('name', $organizationData['religion'])->where('type', 'religion')->first();
                        $faith_tradition = Religion::where('name', $organizationData['faith_tradition'])->where('type', 'faith_tradition')->first();
                        $denomination = Religion::where('name', $organizationData['denomination'])->where('type', 'denominations')->first();
                        $judicatory_body = Religion::where('name', $organizationData['judicatory_body'])->where('type', 'judicatory_body')->first();

                        $organization_religion = '';
                        if ($religion) {
                            $organization_religion = $religion->id;
                        } else {
                            if ($organizationData['religion'] != null) {
                                $newReligion = Religion::create([
                                    'name' => $organizationData['religion'],
                                    'type' => 'religion',
                                ]);
                                DB::commit();
                                $organization_religion = $newReligion->id;
                            }
                        }

                        $organization_faith_tradition = '';
                        if ($faith_tradition) {
                            $organization_faith_tradition = $faith_tradition->id;
                        } else {
                            if ($organizationData['faith_tradition'] != null) {
                                $newFaithTradition = Religion::create([
                                    'name' => $organizationData['faith_tradition'],
                                    'type' => 'faith_tradition',
                                ]);
                                DB::commit();
                                $organization_faith_tradition = $newFaithTradition->id;
                            }
                        }

                        $organization_denomination = '';
                        if ($denomination) {
                            $organization_denomination = $denomination->id;
                        } else {
                            if ($organizationData['denomination'] != null) {
                                $newDenomination = Religion::create([
                                    'name' => $organizationData['denomination'],
                                    'type' => 'denominations',
                                ]);
                                DB::commit();
                                $organization_denomination = $newDenomination->id;
                            }
                        }

                        $organization_judicatory_body = '';
                        if ($judicatory_body) {
                            $organization_judicatory_body = $judicatory_body->id;
                        } else {
                            if ($organizationData['judicatory_body'] != null) {
                                $newJudycatoryBody = Religion::create([
                                    'name' => $organizationData['judicatory_body'],
                                    'type' => 'judicatory_body',
                                ]);
                                DB::commit();
                                $organization_judicatory_body = $newJudycatoryBody->id;
                            }
                        }

                        $organization->organization_recordid = $organization_recordid;
                        $organization->organization_religion = $organization_religion;
                        $organization->organization_faith_tradition = $organization_faith_tradition;
                        $organization->organization_denomination = $organization_denomination;
                        $organization->organization_judicatory_body = $organization_judicatory_body;

                        $organizationType = OrganizationType::where('organization_type', 'LIKE', '%' . $organizationData['organization_type'] . '%')->first();

                        $organization_type = '';
                        if ($organizationType) {
                            $organization_type = $organizationType->id;
                        } else {
                            if ($organizationData['organization_type'] != null) {
                                $newOrganizationType = OrganizationType::create([
                                    'organization_type' => $organizationData['organization_type'],
                                ]);
                                DB::commit();
                                $organization_type = $newOrganizationType->id;
                            }
                        }
                        $organization->organization_type = $organization_type;
                        $organization->organization_url = $organizationData['website'];
                        $organization->organization_facebook = $organizationData['facebook'];
                        $organization->organization_c_board = $organizationData['c._board'];
                        $organization->organization_internet_access = $organizationData['internet_access'];
                        $organization->organization_description = $organizationData['comments'];
                        $organization->flag = 'modified';
                        $organization->save();
                    }
                }
                return redirect()->back()->with('success', 'Organization Added successfully!');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());

        }
    }
    public function ImportFacilityExcel(Request $request)
    {
        $this->validate($request, [
            'import_facility' => 'required',
        ]);
        try {
            if ($request->file('import_facility')) {
                $path = $request->file('import_facility')->getRealPath();
                $data = \Excel::load($path)->get();
                if (!empty($data) && count($data) > 0) {
                    $data = $data->toArray();
                    Address::truncate();
                    Location::truncate();
                    Locationaddress::truncate();
                    // Locationphone::truncate();
                    // Locationschedule::truncate();

                    set_time_limit(36000);
                    foreach ($data as $key => $locationData) {
                        $getLocation = Location::where('location_name', 'LIKE', '%' . $locationData['name'] . '%')->first();
                        if ($getLocation == null) {
                            $location = new Location;

                            $location->location_name = $locationData['name'];

                            $address_recordids = Address::select("address_recordid")->distinct()->get();
                            $address_recordid_list = array();
                            foreach ($address_recordids as $key => $value) {
                                $address_recordid = $value->address_recordid;
                                array_push($address_recordid_list, $address_recordid);
                            }
                            $address_recordid_list = array_unique($address_recordid_list);

                            $organization_name = $locationData['organization'];
                            $facility_organization = Organization::where('organization_name', '=', $organization_name)->first();
                            if ($facility_organization) {
                                $facility_organization_id = $facility_organization["organization_recordid"];
                            } else {
                                $facility_organization_id = '';
                            }
                            $location->location_organization = $facility_organization_id;

                            $facilityType = facilityType::where('facility_type', 'LIKE', '%' . $locationData['facility_type'] . '%')->first();
                            $location_type = '';
                            if ($facilityType) {
                                $location_type = $facilityType->id;
                            } else {
                                if ($locationData['facility_type'] != null) {
                                    $facilityType = facilityType::create([
                                        'facility_type' => $locationData['facility_type'],
                                    ]);
                                    DB::commit();
                                    $location_type = $facilityType->id;
                                }
                            }
                            $location->location_type = $location_type;
                            // if ($request->facility_facility_type == '0') {
                            //     $location->location_type = 'Faith-Based-Service Provider';
                            // }
                            // if ($request->facility_facility_type == '1') {
                            //     $location->location_type = 'House of Worship';
                            // }
                            // if ($request->facility_facility_type == '2') {
                            //     $location->location_type = 'Religious Shool';
                            // }
                            // if ($request->facility_facility_type == '3') {
                            //     $location->location_type = 'Other';
                            // }

                            $location->location_congregation = $locationData['of_congregations_at_this_facility'];

                            $location->location_building_status = $locationData['building_status'];

                            $location->location_call = $locationData['call'];

                            $location->location_description = $locationData['comments'];

                            $facility_address_city = $locationData['borough'];
                            $facility_street_address = $locationData['street_address'];
                            $facility_address_state = 'NY';
                            $facility_address_zip_code = $locationData['zipcode'];
                            $facility_address_info = $facility_street_address . ', ' . $facility_address_city . ', ' . $facility_address_state . ', ' . $facility_address_zip_code;

                            $address = Address::where('address', '=', $facility_address_info)->first();
                            if ($address != null) {
                                $address_id = $address["address_recordid"];
                                $location->location_address = $address_id;
                            } else {
                                $address = new Address;
                                $new_recordid = Address::max('address_recordid') + 1;

                                $address->address_recordid = $new_recordid;
                                $address->address = $facility_address_info;
                                $address->address_1 = $facility_street_address;
                                $address->address_city = $facility_address_city;
                                $address->address_state = $facility_address_state;
                                $address->address_zip_code = $facility_address_zip_code;
                                $address->address_type = "Mailing Address";
                                $location->location_address = $new_recordid;
                                $address->save();
                            }

                            $location->flag = 'modified';

                            $location->location_recordid = Location::max("location_recordid") + 1;
                            $location->save();

                            $locationaddress = new Locationaddress();
                            $locationaddress->location_recordid = $location->location_recordid;
                            $locationaddress->address_recordid = $address->address_recordid;
                            $locationaddress->save();

                        }
                    }
                }
                return redirect()->back()->with('success', 'Facility Added successfully!');

            }

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());

        }
    }
}
