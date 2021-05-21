<?php

namespace App\Exports;

use App\Http\Controllers\backend\AuditsController;
use App\Model\Contact;
use App\Model\Location;
use App\Model\Organization;
use App\Model\Service;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use OwenIt\Auditing\Models\Audit;

class EditExport implements FromView
{
    public function __construct($request)
    {
        $this->request = $request;
        // $this->auditsController = $auditsController;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $extraData = $this->request->get('extraData');
        $audits = Audit::orderBy('created_at', 'desc');
        $auditableIds = [];
        if (isset($extraData['organization']) && $extraData['organization'] != null) {
            foreach ($audits->get() as $key => $value) {
                $row = $value;
                $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                if ($modal == 'Organization') {
                    if ($row->auditable_id == $extraData['organization']) {
                        $organization = Organization::where('organization_recordid', $extraData['organization'])->first();
                        $auditableIds[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Location') {
                    if ($row->event == 'created') {
                        $location = Location::whereId($row->auditable_id)->first();
                    } else {
                        $location = Location::where('location_recordid', $row->auditable_id)->first();
                    }
                    $organization = $location->organization;
                    if ($organization && $organization->organization_recordid == $extraData['organization']) {
                        $auditableIds[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Contact') {
                    if ($row->event == 'created') {
                        $contact = Contact::whereId($row->auditable_id)->first();
                    } else {
                        $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                    }
                    $organization = $contact->organization;
                    if ($organization && $organization->organization_recordid == $extraData['organization']) {
                        $auditableIds[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Service') {
                    if ($row->event == 'created') {
                        $service = Service::whereId($row->auditable_id)->first();
                    } else {
                        $service = Service::where('service_recordid', $row->auditable_id)->first();
                    }
                    $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    if ($organization && $organization->organization_recordid == $extraData['organization']) {
                        $auditableIds[] = $row->auditable_id;
                    }
                }
                $auditableIds = array_unique($auditableIds);
            }
            $audits = $audits->whereIn('auditable_id', $auditableIds);
        }
        if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
            $organization_ids = Organization::where('organization_tag', 'LIKE', '%' . $extraData['organization_tag'] . '%')->pluck('organization_recordid')->toArray();
            //
            $organization_tagsId = [];
            foreach ($audits->get() as $key => $value) {
                $row = $value;
                $modal = str_replace(trim('App\Model\ '), '', $row->auditable_type);
                if ($modal == 'Organization') {
                    if (in_array($row->auditable_id, $organization_ids)) {
                        $organization_tagsId[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Location') {
                    if ($row->event == 'created') {
                        $location = Location::whereId($row->auditable_id)->first();
                    } else {
                        $location = Location::where('location_recordid', $row->auditable_id)->first();
                    }
                    $organization = $location->organization;
                    if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                        $organization_tagsId[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Contact') {
                    if ($row->event == 'created') {
                        $contact = Contact::whereId($row->auditable_id)->first();
                    } else {
                        $contact = Contact::where('contact_recordid', $row->auditable_id)->first();
                    }
                    $organization = $contact->organization;
                    if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                        $organization_tagsId[] = $row->auditable_id;
                    }
                } elseif ($modal == 'Service') {
                    if ($row->event == 'created') {
                        $service = Service::whereId($row->auditable_id)->first();
                    } else {
                        $service = Service::where('service_recordid', $row->auditable_id)->first();
                    }
                    $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    if ($organization && in_array($organization->organization_recordid, $organization_ids)) {
                        $organization_tagsId[] = $row->auditable_id;
                    }
                }
                $organization_tagsId = array_unique($organization_tagsId);
            }
            $audits = $audits->whereIn('auditable_id', $organization_tagsId);
        }
        if (isset($extraData['dataType']) && $extraData['dataType'] != null) {
            $audits = $audits->where('auditable_type', 'LIKE', '%' . $extraData['dataType'] . '%');
            // $audits = $audits->filter(function ($value, $key)  use ($extraData) {
            //     return $value->auditable_type == $extraData['dataType'];
            // });
        }
        if (isset($extraData['user']) && $extraData['user'] != null) {
            $audits = $audits->where('user_id', $extraData['user']);
        }
        if (isset($extraData['start_date']) && $extraData['start_date'] != null) {
            $audits = $audits->whereDate('created_at', '>=', $extraData['start_date']);
        }

        if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
            $audits = $audits->whereDate('created_at', '<=', $extraData['end_date']);
        }
        $fieldTypesData = '';
        if (isset($extraData['fieldType']) && $extraData['fieldType'] != null) {
            $fieldTypesData = $extraData['fieldType'];
        }
        $data = [];
        $i = 0;
        // dd($audits);
        $fieldTypes = [];
        //  $this->auditsController->filterAudits($audits);
        $audits = app(\App\Http\Controllers\backend\AuditsController::class)->filterAudits($audits->get());
        foreach ($audits as $key => $audit) {
            foreach ($audit->old_values as $key1 => $value1) {
                if ($fieldTypesData && $fieldTypesData == $key1) {
                    $fieldTypes[] = $key1;
                    $data[$i]['id'] = $audit->id;
                    $data[$i]['created_at'] = date('d-m-Y H:i:s', strtotime($audit->created_at));
                    $data[$i]['auditable_id'] = $audit->auditable_id;
                    $data[$i]['auditable_type'] = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                    $data[$i]['event'] = $audit->event;
                    $data[$i]['user'] = $audit->user ? $audit->user->first_name . ' ' . $audit->user->last_name : '';
                    $data[$i]['user_type'] = $audit->user_type;
                    $data[$i]['field_type'] = $key1;
                    $data[$i]['change_from'] = $value1;
                    $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                    if ($modal == 'Organization') {
                        $organization = Organization::where('organization_recordid', $audit->auditable_id)->first();
                    } elseif ($modal == 'Location') {
                        if ($audit->event == 'created') {
                            $location = Location::whereId($audit->auditable_id)->first();
                        } else {
                            $location = Location::where('location_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $location->organization;
                    } elseif ($modal == 'Contact') {
                        if ($audit->event == 'created') {
                            $contact = Contact::whereId($audit->auditable_id)->first();
                        } else {
                            $contact = Contact::where('contact_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $contact->organization;
                    } elseif ($modal == 'Service') {
                        if ($audit->event == 'created') {
                            $service = Service::whereId($audit->auditable_id)->first();
                        } else {
                            $service = Service::where('service_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    }
                    $data[$i]['organization'] = $organization ? $organization->organization_name : '';
                    $data[$i]['change_to'] = $audit->new_values[$key1] ?? '';
                    $i++;
                }
                if ($fieldTypesData == '') {
                    $fieldTypes[] = $key1;
                    $data[$i]['id'] = $audit->id;
                    $data[$i]['created_at'] = date('d-m-Y H:i:s', strtotime($audit->created_at));
                    $data[$i]['auditable_id'] = $audit->auditable_id;
                    $data[$i]['auditable_type'] = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                    $data[$i]['event'] = $audit->event;
                    $data[$i]['user'] = $audit->user ? $audit->user->first_name . ' ' . $audit->user->last_name : '';
                    $data[$i]['user_type'] = $audit->user_type;
                    $data[$i]['field_type'] = $key1;
                    $data[$i]['change_from'] = $value1;
                    $modal = str_replace(trim('App\Model\ '), '', $audit->auditable_type);
                    if ($modal == 'Organization') {
                        $organization = Organization::where('organization_recordid', $audit->auditable_id)->first();
                    } elseif ($modal == 'Location') {
                        if ($audit->event == 'created') {
                            $location = Location::whereId($audit->auditable_id)->first();
                        } else {
                            $location = Location::where('location_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $location->organization;
                    } elseif ($modal == 'Contact') {
                        if ($audit->event == 'created') {
                            $contact = Contact::whereId($audit->auditable_id)->first();
                        } else {
                            $contact = Contact::where('contact_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $contact->organization;
                    } elseif ($modal == 'Service') {
                        if ($audit->event == 'created') {
                            $service = Service::whereId($audit->auditable_id)->first();
                        } else {
                            $service = Service::where('service_recordid', $audit->auditable_id)->first();
                        }
                        $organization = $service && $service->getOrganizations && count($service->getOrganizations) > 0 ? $service->getOrganizations[0] : null;
                    }
                    $data[$i]['organization'] = $organization ? $organization->organization_name : '';
                    $data[$i]['change_to'] = $audit->new_values[$key1] ?? '';
                    $i++;
                }
            }
        }
        return view('exports.audits', [
            'audits' => $data,
        ]);
    }
}
