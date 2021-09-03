<?php

namespace App\Exports;

use App\Model\Code;
use App\Model\CodeLedger;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CodeExport implements FromView
{

    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $query = Code::select('*');
        $extraData = $this->request->extraData;
        if (isset($extraData['category']) && $extraData['category'] != null) {
            $query = $query->where('category', $extraData['category']);
        }
        if (isset($extraData['resource']) && $extraData['resource'] != null) {
            $query = $query->where('resource', $extraData['resource']);
        }
        if (isset($extraData['resource_element']) && $extraData['resource_element'] != null) {
            $query = $query->where('resource_element', $extraData['resource_element']);
        }
        if (isset($extraData['code_system']) && $extraData['code_system'] != null) {
            $query = $query->where('code_system', $extraData['code_system']);
        }
        if (isset($extraData['code_with_service']) && $extraData['code_with_service'] != null) {
            if ($extraData['code_with_service'] == "true") {
                $code_ids = CodeLedger::whereNotNull('service_recordid')->pluck('SDOH_code')->toArray();
                $query = $query->whereIn('id', $code_ids);
            }
        }
        return view('exports.codes', [
            'codes' => $query->cursor(),
        ]);
    }
}
