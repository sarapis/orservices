<?php

namespace App\Exports;

use App\Model\Code;
use App\Model\CodeLedger;
use App\Model\Organization;
use App\Model\ServiceCode;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CodeLeadgerExport implements FromView
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
        $code_ledgers = CodeLedger::select('*');
        $extraData = $this->request->get('extraData');
        if ($extraData) {

            if (isset($extraData['services']) && $extraData['services'] != null) {
                $code_ledgers = $code_ledgers->where('service_recordid', $extraData['services']);
            }
            if (isset($extraData['organizations']) && $extraData['organizations'] != null) {
                $code_ledgers = $code_ledgers->where('organization_recordid', $extraData['organizations']);
            }
            if (isset($extraData['resources']) && $extraData['resources'] != null) {
                $code_ledgers = $code_ledgers->where('resource', $extraData['resources']);
            }
            if (isset($extraData['category']) && $extraData['category'] != null) {
                $code_ids = Code::where('category', $extraData['category'])->pluck('id')->toArray();
                $code_ledgers = $code_ledgers->whereIn('SDOH_code', $code_ids);
            }
        }
        return view('exports.code_ledger', [
            'code_ledgers' => $code_ledgers->cursor(),
        ]);
    }
}
