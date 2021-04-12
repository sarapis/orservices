<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Contact;
use App\Model\Detail;
use App\Model\Location;
use App\Model\Organization;
use App\Model\OrganizationTag;
use App\Model\Phone;
use App\Model\Schedule;
use App\Model\Service;
use App\Model\Taxonomy;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function serviceSection($service)
    {
        $serviceAudits = $service->audits()->with('user')->orderBy('id', 'desc')->get();
        $serviceAudits->filter(function ($value) {

            $new_values = $value->new_values;
            $old_values = $value->old_values;
            foreach ($value->new_values as $key => $item) {
                // service phone section
                if ($key == 'service_phones') {
                    $servicePhoneIds = explode(',', $value->new_values[$key]);
                    $servicePhoneOldIds = explode(',', $value->old_values[$key]);
                    $servicePhoneIds = array_filter($servicePhoneIds);
                    $servicePhoneOldIds = array_filter($servicePhoneOldIds);
                    $phoneNumbers = '';
                    $oldPhoneNumbers = '';
                    foreach ($servicePhoneIds as $keyP => $valueP) {
                        $phoneData = Phone::where('phone_recordid', $valueP)->first();
                        if ($phoneData) {
                            if ($keyP == 0)
                                $phoneNumbers = $phoneData->phone_number;
                            else
                                $phoneNumbers = $phoneNumbers . ', ' . $phoneData->phone_number;
                        }
                    }
                    $new_values[$key] = $phoneNumbers;

                    foreach ($servicePhoneOldIds as $keyO => $valueO) {
                        $oldPhoneData = Phone::where('phone_recordid', $valueO)->first();
                        if ($oldPhoneData) {
                            if ($keyO == 0)
                                $oldPhoneNumbers = $oldPhoneData->phone_number;
                            else
                                $oldPhoneNumbers = $oldPhoneNumbers . ', ' . $oldPhoneData->phone_number;
                        }
                    }
                    $old_values[$key] = $oldPhoneNumbers;
                } else if ($key == 'service_taxonomy') {
                    $serviceTaxonomyIds = explode(',', $value->new_values[$key]);
                    $serviceTaxonomyOldIds = explode(',', $value->old_values[$key]);
                    $serviceTaxonomyIds = array_filter($serviceTaxonomyIds);
                    $serviceTaxonomyOldIds = array_filter($serviceTaxonomyOldIds);
                    $taxonomyName = '';
                    $oldTaxonomyName = '';
                    foreach ($serviceTaxonomyIds as $keyP => $valueP) {
                        $taxonomyData = Taxonomy::where('taxonomy_recordid', $valueP)->first();
                        if ($taxonomyData) {
                            if ($keyP == 0)
                                $taxonomyName = $taxonomyData->taxonomy_name;
                            else
                                $taxonomyName = $taxonomyName . ', ' . $taxonomyData->taxonomy_name;
                        }
                    }
                    $new_values[$key] = $taxonomyName;

                    foreach ($serviceTaxonomyOldIds as $keyO => $valueO) {
                        $oldTaxonomyData = Taxonomy::where('taxonomy_recordid', $valueO)->first();
                        if ($oldTaxonomyData) {
                            if ($keyO == 0)
                                $oldTaxonomyName = $oldTaxonomyData->taxonomy_name;
                            else
                                $oldTaxonomyName = $oldTaxonomyName . ', ' . $oldTaxonomyData->phone_number;
                        }
                    }
                    $old_values[$key] = $oldTaxonomyName;
                } else if ($key == 'service_contacts') {
                    $serviceContactIds = explode(',', $value->new_values[$key]);
                    $serviceContactOldIds = explode(',', $value->old_values[$key]);
                    $serviceContactIds = array_filter($serviceContactIds);
                    $serviceContactOldIds = array_filter($serviceContactOldIds);
                    $contactName = '';
                    $oldContactName = '';
                    foreach ($serviceContactIds as $keyP => $valueP) {
                        $contactData = Contact::where('contact_recordid', $valueP)->first();
                        if ($contactData) {
                            if ($keyP == 0)
                                $contactName = $contactData->contact_name;
                            else
                                $contactName = $contactName . ', ' . $contactData->contact_name;
                        }
                    }
                    $new_values[$key] = $contactName;

                    foreach ($serviceContactOldIds as $keyO => $valueO) {
                        $oldContactData = Contact::where('contact_recordid', $valueO)->first();
                        if ($oldContactData) {
                            if ($keyO == 0)
                                $oldContactName = $oldContactData->contact_name;
                            else
                                $oldContactName = $oldContactName . ', ' . $oldContactData->contact_name;
                        }
                    }
                    $old_values[$key] = $oldContactName;
                } else if ($key == 'service_details') {
                    $serviceDetailIds = explode(',', $value->new_values[$key]);
                    $serviceDetailOldIds = explode(',', $value->old_values[$key]);
                    $serviceDetailIds = array_filter($serviceDetailIds);
                    $serviceDetailOldIds = array_filter($serviceDetailOldIds);
                    $detailName = '';
                    $oldDetailName = '';
                    foreach ($serviceDetailIds as $keyP => $valueP) {
                        $detailData = Detail::where('detail_recordid', $valueP)->first();
                        if ($detailData) {
                            if ($keyP == 0)
                                $detailName = $detailData->detail_value;
                            else
                                $detailName = $detailName . ', ' . $detailData->detail_value;
                        }
                    }
                    $new_values[$key] = $detailName;

                    foreach ($serviceDetailOldIds as $keyO => $valueO) {
                        $oldDetailData = Detail::where('detail_recordid', $valueO)->first();
                        if ($oldDetailData) {
                            if ($keyO == 0)
                                $oldDetailName = $oldDetailData->detail_value;
                            else
                                $oldDetailName = $oldDetailName . ', ' . $oldDetailData->detail_value;
                        }
                    }
                    $old_values[$key] = $oldDetailName;
                } else if ($key == 'service_locations') {
                    $serviceLocationIds = explode(',', $value->new_values[$key]);
                    $serviceLocationOldIds = explode(',', $value->old_values[$key]);
                    $serviceLocationIds = array_filter($serviceLocationIds);
                    $serviceLocationOldIds = array_filter($serviceLocationOldIds);
                    $locationName = '';
                    $oldLocationName = '';
                    foreach ($serviceLocationIds as $keyP => $valueP) {
                        $locationData = Location::where('location_recordid', $valueP)->first();
                        if ($locationData) {
                            if ($keyP == 0)
                                $locationName = $locationData->location_name;
                            else
                                $locationName = $locationName . ', ' . $locationData->location_name;
                        }
                    }
                    $new_values[$key] = $locationName;

                    foreach ($serviceLocationOldIds as $keyO => $valueO) {
                        $oldLocationData = Location::where('location_recordid', $valueO)->first();
                        if ($oldLocationData) {
                            if ($keyO == 0)
                                $oldLocationName = $oldLocationData->location_name;
                            else
                                $oldLocationName = $oldLocationName . ', ' . $oldLocationData->location_name;
                        }
                    }
                    $old_values[$key] = $oldLocationName;
                }
            }
            $value->new_values = $new_values;
            $value->old_values = $old_values;
            return true;
        });
        return $serviceAudits;
    }
    public function organizationSection($organization)
    {
        $organizationAudits = $organization->audits()->with('user')->orderBy('id', 'desc')->get();
        $organizationAudits->filter(function ($value) {
            $new_values = $value->new_values;
            $old_values = $value->old_values;
            foreach ($value->new_values as $key => $item) {
                // service phone section
                if ($key == 'organization_locations') {
                    $organizationLocationIds = explode(',', $value->new_values[$key]);
                    $organizationLocationOldIds = explode(',', $value->old_values[$key]);
                    $organizationLocationIds = array_filter($organizationLocationIds);
                    $organizationLocationOldIds = array_filter($organizationLocationOldIds);
                    $locationName = '';
                    $oldLocationName = '';
                    foreach ($organizationLocationIds as $keyP => $valueP) {
                        $locationData = Location::where('location_recordid', $valueP)->first();
                        if ($locationData) {
                            if ($keyP == 0)
                                $locationName = $locationData->location_name;
                            else
                                $locationName = $locationName . ', ' . $locationData->location_name;
                        }
                    }
                    $new_values[$key] = $locationName;

                    foreach ($organizationLocationOldIds as $keyO => $valueO) {
                        $oldLocationData = Location::where('location_recordid', $valueO)->first();
                        if ($oldLocationData) {
                            if ($keyO == 0)
                                $oldLocationName = $oldLocationData->location_name;
                            else
                                $oldLocationName = $oldLocationName . ', ' . $oldLocationData->location_name;
                        }
                    }
                    $old_values[$key] = $oldLocationName;
                    // $this->commonController->serviceSection($old_values, $new_values, $value, $key);
                } else if ($key == 'organization_tag') {
                    $organizationTagIds = explode(',', $value->new_values[$key]);
                    $organizationTagOldIds = explode(',', $value->old_values[$key]);
                    $organizationTagIds = array_filter($organizationTagIds);
                    $organizationTagOldIds = array_filter($organizationTagOldIds);
                    $tagName = '';
                    $oldTagName = '';
                    foreach ($organizationTagIds as $keyP => $valueP) {
                        $tagData = OrganizationTag::whereId($valueP)->first();
                        if ($tagData) {
                            if ($keyP == 0)
                                $tagName = $tagData->tag;
                            else
                                $tagName = $tagName . ', ' . $tagData->tag;
                        }
                    }
                    $new_values[$key] = $tagName;

                    foreach ($organizationTagOldIds as $keyO => $valueO) {
                        $oldTagData = OrganizationTag::whereId($valueO)->first();
                        if ($oldTagData) {
                            if ($keyO == 0)
                                $oldTagName = $oldTagData->tag;
                            else
                                $oldTagName = $oldTagName . ', ' . $oldTagData->tag;
                        }
                    }
                    $old_values[$key] = $oldTagName;
                    // $this->commonController->serviceSection($old_values, $new_values, $value, $key);
                }
            }
            $value->new_values = $new_values;
            $value->old_values = $old_values;
            return true;
        });
        return $organizationAudits;
    }
    public function contactSection($contact)
    {
        $contactAudits = $contact->audits()->with('user')->orderBy('id', 'desc')->get();
        $contactAudits->filter(function ($value) {
            $new_values = $value->new_values;
            $old_values = $value->old_values;
            foreach ($value->new_values as $key => $item) {
                // contact phone section
                if ($key == 'contact_phones') {
                    $contactPhoneIds = explode(',', $value->new_values[$key]);
                    $contactPhoneOldIds = explode(',', $value->old_values[$key]);
                    $contactPhoneIds = array_filter($contactPhoneIds);
                    $contactPhoneOldIds = array_filter($contactPhoneOldIds);
                    $phoneNumbers = '';
                    $oldPhoneNumbers = '';
                    foreach ($contactPhoneIds as $keyP => $valueP) {
                        $phoneData = Phone::where('phone_recordid', $valueP)->first();
                        if ($phoneData) {
                            if ($keyP == 0)
                                $phoneNumbers = $phoneData->phone_number;
                            else
                                $phoneNumbers = $phoneNumbers . ', ' . $phoneData->phone_number;
                        }
                    }
                    $new_values[$key] = $phoneNumbers;

                    foreach ($contactPhoneOldIds as $keyO => $valueO) {
                        $oldPhoneData = Phone::where('phone_recordid', $valueO)->first();
                        if ($oldPhoneData) {
                            if ($keyO == 0)
                                $oldPhoneNumbers = $oldPhoneData->phone_number;
                            else
                                $oldPhoneNumbers = $oldPhoneNumbers . ', ' . $oldPhoneData->phone_number;
                        }
                    }
                    $old_values[$key] = $oldPhoneNumbers;
                } elseif ($key == 'contact_organizations') {
                    $contactOrganizationIds = explode(',', $value->new_values[$key]);
                    $contactOrganizationOldIds = explode(',', $value->old_values[$key]);
                    $contactOrganizationIds = array_filter($contactOrganizationIds);
                    $contactOrganizationOldIds = array_filter($contactOrganizationOldIds);
                    $organizationNames = '';
                    $oldOrganizationNames = '';
                    foreach ($contactOrganizationIds as $keyP => $valueP) {
                        $organizationData = Organization::where('organization_recordid', $valueP)->first();
                        if ($organizationData) {
                            if ($keyP == 0)
                                $organizationNames = $organizationData->organization_name;
                            else
                                $organizationNames = $organizationNames . ', ' . $organizationData->organization_name;
                        }
                    }
                    $new_values[$key] = $organizationNames;
                    foreach ($contactOrganizationOldIds as $keyO => $valueO) {
                        $oldOrganizationData = Organization::where('organization_recordid', $valueO)->first();
                        if ($oldOrganizationData) {
                            if ($keyO == 0)
                                $oldOrganizationNames = $oldOrganizationData->organization_name;
                            else
                                $oldOrganizationNames = $oldOrganizationNames . ', ' . $oldOrganizationData->organization_name;
                        }
                    }
                    $old_values[$key] = $oldOrganizationNames;
                } else if ($key == 'contact_services') {
                    $contactServiceIds = explode(',', $value->new_values[$key]);
                    $contactServiceOldIds = explode(',', $value->old_values[$key]);
                    $contactServiceIds = array_filter($contactServiceIds);
                    $contactServiceOldIds = array_filter($contactServiceOldIds);
                    $serviceNames = '';
                    $oldServiceNames = '';
                    foreach ($contactServiceIds as $keyP => $valueP) {
                        $serviceData = Service::where('service_recordid', $valueP)->first();
                        if ($serviceData) {
                            if ($keyP == 0)
                                $serviceNames = $serviceData->service_name;
                            else
                                $serviceNames = $serviceNames . ', ' . $serviceData->service_name;
                        }
                    }
                    $new_values[$key] = $serviceNames;
                    foreach ($contactServiceOldIds as $keyO => $valueO) {
                        $oldServiceData = Service::where('service_recordid', $valueO)->first();
                        if ($oldServiceData) {
                            if ($keyO == 0)
                                $oldServiceNames = $oldServiceData->service_name;
                            else
                                $oldServiceNames = $oldServiceNames . ', ' . $oldServiceData->service_name;
                        }
                    }
                    $old_values[$key] = $oldServiceNames;
                }
            }
            $value->new_values = $new_values;
            $value->old_values = $old_values;
            return true;
        });
        return $contactAudits;
    }
    public function locationSection($facility)
    {
        $facilityAudits = $facility->audits()->with('user')->orderBy('id', 'desc')->get();
        $facilityAudits->filter(function ($value) {
            $new_values = $value->new_values;
            $old_values = $value->old_values;
            foreach ($value->new_values as $key => $item) {
                // facility phone section
                if ($key == 'location_phones') {
                    $facilityPhoneIds = explode(',', $value->new_values[$key]);
                    $facilityPhoneOldIds = explode(',', $value->old_values[$key]);
                    $facilityPhoneIds = array_filter($facilityPhoneIds);
                    $facilityPhoneOldIds = array_filter($facilityPhoneOldIds);
                    $phoneNumbers = '';
                    $oldPhoneNumbers = '';
                    foreach ($facilityPhoneIds as $keyP => $valueP) {
                        $phoneData = Phone::where('phone_recordid', $valueP)->first();
                        if ($phoneData) {
                            if ($keyP == 0)
                                $phoneNumbers = $phoneData->phone_number;
                            else
                                $phoneNumbers = $phoneNumbers . ', ' . $phoneData->phone_number;
                        }
                    }
                    $new_values[$key] = $phoneNumbers;

                    foreach ($facilityPhoneOldIds as $keyO => $valueO) {
                        $oldPhoneData = Phone::where('phone_recordid', $valueO)->first();
                        if ($oldPhoneData) {
                            if ($keyO == 0)
                                $oldPhoneNumbers = $oldPhoneData->phone_number;
                            else
                                $oldPhoneNumbers = $oldPhoneNumbers . ', ' . $oldPhoneData->phone_number;
                        }
                    }
                    $old_values[$key] = $oldPhoneNumbers;
                } else if ($key == 'location_organization') {
                    $locationOrganizationIds = explode(',', $value->new_values[$key]);
                    $locationOrganizationOldIds = explode(',', $value->old_values[$key]);
                    $locationOrganizationIds = array_filter($locationOrganizationIds);
                    $locationOrganizationOldIds = array_filter($locationOrganizationOldIds);
                    $organizationNames = '';
                    $oldOrganizationNames = '';
                    foreach ($locationOrganizationIds as $keyP => $valueP) {
                        $organizationData = Organization::where('organization_recordid', $valueP)->first();
                        if ($organizationData) {
                            if ($keyP == 0)
                                $organizationNames = $organizationData->organization_name;
                            else
                                $organizationNames = $organizationNames . ', ' . $organizationData->organization_name;
                        }
                    }
                    $new_values[$key] = $organizationNames;
                    foreach ($locationOrganizationOldIds as $keyO => $valueO) {
                        $oldOrganizationData = Organization::where('organization_recordid', $valueO)->first();
                        if ($oldOrganizationData) {
                            if ($keyO == 0)
                                $oldOrganizationNames = $oldOrganizationData->organization_name;
                            else
                                $oldOrganizationNames = $oldOrganizationNames . ', ' . $oldOrganizationData->organization_name;
                        }
                    }
                    $old_values[$key] = $oldOrganizationNames;
                } else if ($key == 'location_services') {
                    $locationServiceIds = explode(',', $value->new_values[$key]);
                    $locationServiceOldIds = explode(',', $value->old_values[$key]);
                    $locationServiceIds = array_filter($locationServiceIds);
                    $locationServiceOldIds = array_filter($locationServiceOldIds);
                    $serviceNames = '';
                    $oldServiceNames = '';
                    foreach ($locationServiceIds as $keyP => $valueP) {
                        $serviceData = Service::where('service_recordid', $valueP)->first();
                        if ($serviceData) {
                            if ($keyP == 0)
                                $serviceNames = $serviceData->service_name;
                            else
                                $serviceNames = $serviceNames . ', ' . $serviceData->service_name;
                        }
                    }
                    $new_values[$key] = $serviceNames;
                    foreach ($locationServiceOldIds as $keyO => $valueO) {
                        $oldServiceData = Service::where('service_recordid', $valueO)->first();
                        if ($oldServiceData) {
                            if ($keyO == 0)
                                $oldServiceNames = $oldServiceData->service_name;
                            else
                                $oldServiceNames = $oldServiceNames . ', ' . $oldServiceData->service_name;
                        }
                    }
                    $old_values[$key] = $oldServiceNames;
                } else if ($key == 'location_details') {
                    $locationDetailIds = explode(',', $value->new_values[$key]);
                    $locationDetailOldIds = explode(',', $value->old_values[$key]);
                    $locationDetailIds = array_filter($locationDetailIds);
                    $locationDetailOldIds = array_filter($locationDetailOldIds);
                    $detailName = '';
                    $oldDetailName = '';
                    foreach ($locationDetailIds as $keyP => $valueP) {
                        $detailData = Detail::where('detail_recordid', $valueP)->first();
                        if ($detailData) {
                            if ($keyP == 0)
                                $detailName = $detailData->detail_value;
                            else
                                $detailName = $detailName . ', ' . $detailData->detail_value;
                        }
                    }
                    $new_values[$key] = $detailName;

                    foreach ($locationDetailOldIds as $keyO => $valueO) {
                        $oldDetailData = Detail::where('detail_recordid', $valueO)->first();
                        if ($oldDetailData) {
                            if ($keyO == 0)
                                $oldDetailName = $oldDetailData->detail_value;
                            else
                                $oldDetailName = $oldDetailName . ', ' . $oldDetailData->detail_value;
                        }
                    }
                    $old_values[$key] = $oldDetailName;
                } else if ($key == 'location_address') {
                    $locationAddressIds = explode(',', $value->new_values[$key]);
                    $locationAddressOldIds = explode(',', $value->old_values[$key]);
                    $locationAddressIds = array_filter($locationAddressIds);
                    $locationAddressOldIds = array_filter($locationAddressOldIds);
                    $AddressName = '';
                    $oldAddressName = '';
                    foreach ($locationAddressIds as $keyP => $valueP) {
                        $AddressData = Address::where('address_recordid', $valueP)->first();
                        if ($AddressData) {
                            if ($keyP == 0)
                                $AddressName = $AddressData->address_1;
                            else
                                $AddressName = $AddressName . ', ' . $AddressData->address_1;
                        }
                    }
                    $new_values[$key] = $AddressName;

                    foreach ($locationAddressOldIds as $keyO => $valueO) {
                        $oldAddressData = Address::where('address_recordid', $valueO)->first();
                        if ($oldAddressData) {
                            if ($keyO == 0)
                                $oldAddressName = $oldAddressData->address_1;
                            else
                                $oldAddressName = $oldAddressName . ', ' . $oldAddressData->address_1;
                        }
                    }
                    $old_values[$key] = $oldAddressName;
                } else if ($key == 'location_schedule') {
                    $locationScheduleIds = explode(',', $value->new_values[$key]);
                    $locationScheduleOldIds = explode(',', $value->old_values[$key]);
                    $locationScheduleIds = array_filter($locationScheduleIds);
                    $locationScheduleOldIds = array_filter($locationScheduleOldIds);
                    $scheduleName = '';
                    $oldScheduleName = '';
                    foreach ($locationScheduleIds as $keyP => $valueP) {
                        $scheduleData = Schedule::where('schedule_recordid', $valueP)->first();
                        if ($scheduleData) {
                            if ($keyP == 0)
                                $scheduleName = $scheduleData->name;
                            else
                                $scheduleName = $scheduleName . ', ' . $scheduleData->name;
                        }
                    }
                    $new_values[$key] = $scheduleName;

                    foreach ($locationScheduleOldIds as $keyO => $valueO) {
                        $oldScheduleData = Schedule::where('schedule_recordid', $valueO)->first();
                        if ($oldScheduleData) {
                            if ($keyO == 0)
                                $oldScheduleName = $oldScheduleData->name;
                            else
                                $oldScheduleName = $oldScheduleName . ', ' . $oldScheduleData->name;
                        }
                    }
                    $old_values[$key] = $oldScheduleName;
                }
            }
            $value->new_values = $new_values;
            $value->old_values = $old_values;
            return true;
        });
        return $facilityAudits;
    }
    public function getSingleData($datas, $modal)
    {
        try {
            if (isset($datas[strtolower($modal) . '_phones'])) {
                $currentPhoneIds = explode(',', $datas[strtolower($modal) . '_phones']);
                $currentPhoneIds = array_filter($currentPhoneIds);
                $currentPhoneNumbers = '';
                foreach ($currentPhoneIds as $keyO => $valueO) {
                    $oldPhoneData = Phone::where('phone_recordid', $valueO)->first();
                    if ($oldPhoneData) {
                        if ($keyO == 0)
                            $currentPhoneNumbers = $oldPhoneData->phone_number;
                        else
                            $currentPhoneNumbers = $currentPhoneNumbers . ', ' . $oldPhoneData->phone_number;
                    }
                }
                $datas[strtolower($modal) . '_phones'] = $currentPhoneNumbers;
            }
            if (isset($datas[strtolower($modal) . '_schedule'])) {
                $currentScheduleIds = explode(',', $datas[strtolower($modal) . '_schedule']);
                $currentScheduleIds = array_filter($currentScheduleIds);
                $currentScheduleNames = '';
                foreach ($currentScheduleIds as $keyO => $valueO) {
                    $oldScheduleData = Schedule::where('schedule_recordid', $valueO)->first();
                    if ($oldScheduleData) {
                        if ($keyO == 0)
                            $currentScheduleNames = $oldScheduleData->name;
                        else
                            $currentScheduleNames = $currentScheduleNames . ', ' . $oldScheduleData->name;
                    }
                }
                $datas[strtolower($modal) . '_schedule'] = $currentScheduleNames;
            }
            if (isset($datas[strtolower($modal) . '_address'])) {
                $currentAddressIds = explode(',', $datas[strtolower($modal) . '_address']);
                $currentAddressIds = array_filter($currentAddressIds);
                $currentAddressName = '';
                foreach ($currentAddressIds as $keyO => $valueO) {
                    $oldAddressData = Address::where('address_recordid', $valueO)->first();
                    if ($oldAddressData) {
                        if ($keyO == 0)
                            $currentAddressName = $oldAddressData->address_1;
                        else
                            $currentAddressName = $currentAddressName . ', ' . $oldAddressData->address_1;
                    }
                }
                $datas[strtolower($modal) . '_address'] = $currentAddressName;
            }
            if (isset($datas[strtolower($modal) . '_details'])) {
                $currentDetailIds = explode(',', $datas[strtolower($modal) . '_details']);
                $currentDetailIds = array_filter($currentDetailIds);
                $currentDetailName = '';
                foreach ($currentDetailIds as $keyO => $valueO) {
                    $oldDetailData = Detail::where('detail_recordid', $valueO)->first();
                    if ($oldDetailData) {
                        if ($keyO == 0)
                            $currentDetailName = $oldDetailData->detail_value;
                        else
                            $currentDetailName = $currentDetailName . ', ' . $oldDetailData->detail_value;
                    }
                }
                $datas[strtolower($modal) . '_details'] = $currentDetailName;
            }
            if (isset($datas[strtolower($modal) . '_services'])) {
                $currentServiceIds = explode(',', $datas[strtolower($modal) . '_services']);
                $currentServiceIds = array_filter($currentServiceIds);
                $currentServiceName = '';
                foreach ($currentServiceIds as $keyO => $valueO) {
                    $oldServiceData = Service::where('service_recordid', $valueO)->first();
                    if ($oldServiceData) {
                        if ($keyO == 0)
                            $currentServiceName = $oldServiceData->service_name;
                        else
                            $currentServiceName = $currentServiceName . ', ' . $oldServiceData->service_name;
                    }
                }
                $datas[strtolower($modal) . '_services'] = $currentServiceName;
            }
            if (isset($datas[strtolower($modal) . '_organization'])) {
                $currentOrganizationIds = explode(',', $datas[strtolower($modal) . '_organization']);
                $currentOrganizationIds = array_filter($currentOrganizationIds);
                $currentOrganizationName = '';
                foreach ($currentOrganizationIds as $keyO => $valueO) {
                    $oldOrganizationData = Organization::where('organization_recordid', $valueO)->first();
                    if ($oldOrganizationData) {
                        if ($keyO == 0)
                            $currentOrganizationName = $oldOrganizationData->organization_name;
                        else
                            $currentOrganizationName = $currentOrganizationName . ', ' . $oldOrganizationData->organization_name;
                    }
                }
                $datas[strtolower($modal) . '_organization'] = $currentOrganizationName;
            }
            if (isset($datas[strtolower($modal) . '_organizations'])) {
                $currentOrganizationIds = explode(',', $datas[strtolower($modal) . '_organizations']);
                $currentOrganizationIds = array_filter($currentOrganizationIds);
                $currentOrganizationName = '';
                foreach ($currentOrganizationIds as $keyO => $valueO) {
                    $oldOrganizationData = Organization::where('organization_recordid', $valueO)->first();
                    if ($oldOrganizationData) {
                        if ($keyO == 0)
                            $currentOrganizationName = $oldOrganizationData->organization_name;
                        else
                            $currentOrganizationName = $currentOrganizationName . ', ' . $oldOrganizationData->organization_name;
                    }
                }
                $datas[strtolower($modal) . '_organizations'] = $currentOrganizationName;
            }
            if (isset($datas[strtolower($modal) . '_locations'])) {
                $currentLocationIds = explode(',', $datas[strtolower($modal) . '_locations']);
                $currentLocationIds = array_filter($currentLocationIds);
                $currentLocationName = '';
                foreach ($currentLocationIds as $keyO => $valueO) {
                    $oldLocationData = Location::where('location_recordid', $valueO)->first();
                    if ($oldLocationData) {
                        if ($keyO == 0)
                            $currentLocationName = $oldLocationData->location_name;
                        else
                            $currentLocationName = $currentLocationName . ', ' . $oldLocationData->location_name;
                    }
                }
                $datas[strtolower($modal) . '_locations'] = $currentLocationName;
            }
            if (isset($datas[strtolower($modal) . '_contacts'])) {
                $currentContactIds = explode(',', $datas[strtolower($modal) . '_contacts']);
                $currentContactIds = array_filter($currentContactIds);
                $currentContactName = '';
                foreach ($currentContactIds as $keyO => $valueO) {
                    $oldContactData = Contact::where('contact_recordid', $valueO)->first();
                    if ($oldContactData) {
                        if ($keyO == 0)
                            $currentContactName = $oldContactData->contact_name;
                        else
                            $currentContactName = $currentContactName . ', ' . $oldContactData->contact_name;
                    }
                }
                $datas[strtolower($modal) . '_contacts'] = $currentContactName;
            }
            if (isset($datas[strtolower($modal) . '_taxonomy'])) {
                $currentTaxonomyIds = explode(',', $datas[strtolower($modal) . '_taxonomy']);
                $currentTaxonomyIds = array_filter($currentTaxonomyIds);
                $currentTaxonomyName = '';
                foreach ($currentTaxonomyIds as $keyO => $valueO) {
                    $oldTaxonomyData = Taxonomy::where('taxonomy_recordid', $valueO)->first();
                    if ($oldTaxonomyData) {
                        if ($keyO == 0)
                            $currentTaxonomyName = $oldTaxonomyData->taxonomy_name;
                        else
                            $currentTaxonomyName = $currentTaxonomyName . ', ' . $oldTaxonomyData->taxonomy_name;
                    }
                }
                $datas[strtolower($modal) . '_taxonomy'] = $currentTaxonomyName;
            }
            if (isset($datas[strtolower($modal) . '_tag'])) {
                $currentTagIds = explode(',', $datas[strtolower($modal) . '_tag']);
                $currentTagIds = array_filter($currentTagIds);
                $currentTagName = '';
                foreach ($currentTagIds as $keyO => $valueO) {
                    $oldTagData = OrganizationTag::whereId($valueO)->first();
                    if ($oldTagData) {
                        if ($keyO == 0)
                            $currentTagName = $oldTagData->tag;
                        else
                            $currentTagName = $currentTagName . ', ' . $oldTagData->tag;
                    }
                }
                $datas[strtolower($modal) . '_tag'] = $currentTagName;
            }

            return $datas;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
