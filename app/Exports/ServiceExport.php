<?php

namespace App\Exports;

use App\Model\Code;
use App\Model\Service;
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
            if (isset($extraData['goals']) && $extraData['goals'] != null) {
                $code_ids = array_merge($code_ids, $extraData['goals']);
            }
            if (isset($extraData['activities']) && $extraData['activities'] != null) {
                $code_ids = array_merge($code_ids, $extraData['activities']);
            }
            if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                $services->whereIn('service_organization', $extraData['organizations']);
            }
            if (count($code_ids) > 0) {
                $codes = Code::whereIn('id', $code_ids)->get();
                foreach ($codes as $key => $value) {
                    $code_ledger = $value->code_ledger;
                    if ($code_ledger) {
                        $service_ids[] = $code_ledger->service_recordid;
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
