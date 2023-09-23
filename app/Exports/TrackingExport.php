<?php

namespace App\Exports;

use App\Model\Organization;
use App\Model\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrackingExport implements FromView
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
        $organizations = Organization::select('*');
        $extraData = $this->request->get('extraData');

        if ($extraData) {

            if (isset($extraData['organization_tag']) && $extraData['organization_tag'] != null) {
                $organization_tags = count($extraData['organization_tag']) > 0 ?  array_filter($extraData['organization_tag']) : [];
                $organizations = $organizations->where(function ($q) use ($organization_tags) {
                    foreach ($organization_tags as $key => $value) {
                        $q->orWhere('organization_tag', 'LIKE', '%' . $value . '%');
                    }
                });
            }
            if (isset($extraData['organization_bookmark_only']) && $extraData['organization_bookmark_only'] != null && $extraData['organization_bookmark_only'] == "true") {
                $organizations = $organizations->where('bookmark', 1);
            }
            if (isset($extraData['service_tag']) && $extraData['service_tag'] != null) {
                $service_tags = count($extraData['service_tag']) > 0 ?  array_filter($extraData['service_tag']) : [];

                $organization_recordids = Service::where(function ($q) use ($service_tags) {
                    foreach ($service_tags as $key => $value) {
                        $q->orWhere('service_tag', 'LIKE', '%' . $value . '%');
                    }
                })->pluck('service_organization')->toArray();
                $organizations->whereIn('organization_recordid', $organization_recordids);
            }
            // last updated
            if (isset($extraData['start_updated']) && $extraData['start_updated'] != null && $extraData['end_updated'] == null) {
                $organizations = $organizations->whereDate('updated_at', '>=', $extraData['start_updated']);
            }
            if (isset($extraData['start_updated']) && $extraData['start_updated'] != null && isset($extraData['end_updated']) && $extraData['end_updated'] != null) {
                $organizations = $organizations->whereDate('updated_at', '<=', $extraData['end_updated'])->whereDate('updated_at', '>=', $extraData['start_updated']);
            }
            // end

            // last verified
            if (isset($extraData['start_verified']) && $extraData['start_verified'] != null && $extraData['end_verified'] == null) {
                $organizations = $organizations->whereDate('last_verified_at', '>=', $extraData['start_verified']);
            }
            if (isset($extraData['start_verified']) && $extraData['start_verified'] != null && isset($extraData['end_verified']) && $extraData['end_verified'] != null) {
                $organizations = $organizations->whereDate('last_verified_at', '<=', $extraData['end_verified'])->whereDate('last_verified_at', '>=', $extraData['start_verified']);
            }
            // end
            // if (isset($extraData['end_date']) && $extraData['end_date'] != null) {
            //     $organizations = $organizations->whereDate('updated_at', '<=', $extraData['end_date']);
            // }
            if (isset($extraData['status']) && $extraData['status'] != null) {
                // $statuses = count($extraData['status']) > 0 ?  array_filter($extraData['status']) : [];
                // $organizations = $organizations->where(function ($q) use ($statuses) {
                //     foreach ($statuses as $key => $value) {
                //         $q->orWhere('organization_status_x', 'LIKE', '%' . $value . '%');
                //     }
                // });
                $organizations->whereIn('organization_status_x', $extraData['status']);
            }
            if (isset($extraData['last_updated_by']) && $extraData['last_updated_by'] != null) {
                $organizations->where('updated_by', $extraData['last_updated_by']);
            }
            if (isset($extraData['last_verified_by']) && $extraData['last_verified_by'] != null) {
                $organizations->where('last_verified_by', $extraData['last_verified_by']);
            }
        }
        return view('exports.trackings', [
            'organizations' => $organizations->cursor(),
        ]);
    }
}
