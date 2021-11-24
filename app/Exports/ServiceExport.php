<?php

namespace App\Exports;

use App\Model\Code;
use App\Model\Service;
use App\Model\Taxonomy;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceExport implements FromView
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
        $services = Service::select('*');
        $extraData = $this->request->get('extraData');
        if ($extraData) {
            $code_ids = [];
            $service_ids = [];
            if (isset($extraData['conditions']) && $extraData['conditions'] != null) {
                $code_ids = array_merge($code_ids, $extraData['conditions']);
            }
            if (isset($extraData['service_with_codes']) && $extraData['service_with_codes'] != null && $extraData['service_with_codes'] == "true") {
                $services->whereNotNull('SDOH_code')->whereNotNull('code_category_ids');
            }
            if (isset($extraData['goals']) && $extraData['goals'] != null) {
                $code_ids = array_merge($code_ids, $extraData['goals']);
            }
            if (isset($extraData['activities']) && $extraData['activities'] != null) {
                $code_ids = array_merge($code_ids, $extraData['activities']);
            }
            if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                $services->whereIn('service_organization', $extraData['organizations']);
            }
            if (isset($extraData['service_category']) && $extraData['service_category'] != null) {
                $service_recordids = [];
                $taxonomies = Taxonomy::whereIn('taxonomy_recordid', $extraData['service_category'])->with('service')->get();
                foreach ($taxonomies as $key => $value) {
                    if ($value->service && count($value->service) > 0) {
                        foreach ($value->service as $key => $service) {
                            $service_recordids[] = $service->service_recordid;
                        }
                    }
                }
                $services->whereIn('service_recordid', $service_recordids);
            }
            if (isset($extraData['service_eligibility']) && $extraData['service_eligibility'] != null) {
                $service_recordids = [];
                $taxonomies = Taxonomy::whereIn('taxonomy_recordid', $extraData['service_eligibility'])->with('service')->get();
                foreach ($taxonomies as $key => $value) {
                    if ($value->service && count($value->service) > 0) {
                        foreach ($value->service as $key => $service) {
                            $service_recordids[] = $service->service_recordid;
                        }
                    }
                }
                $services->whereIn('service_recordid', $service_recordids);
            }
            if (count($code_ids) > 0) {
                $codes = Code::whereIn('id', $code_ids)->get();
                foreach ($codes as $key => $value) {
                    $code_ledger = $value->code_ledger;
                    if ($code_ledger) {
                        foreach ($code_ledger as $key => $value) {
                            $service_ids[] = $value->service_recordid;
                        }
                    }
                    // $service_ids = array_merge($service_ids, $value->services->pluck('id')->toArray());
                }
                $services->whereIn('service_recordid', $service_ids);
            }
        }
        return view('exports.services', [
            'services' => $services->cursor(),
        ]);
    }
}
