<?php

namespace App\Exports\zip;

use App\Model\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ServiceZipExport implements FromView, WithCustomCsvSettings
{
    public $serviceRecordids, $enclosure;
    public function __construct($serviceRecordids, $enclosure)
    {
        $this->serviceRecordids = $serviceRecordids;
        $this->enclosure = $enclosure;
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $services = Service::select('*');
        if ($this->serviceRecordids) {
            $services->where('service_recordid', $this->serviceRecordids);
        }
        return view('exports.zip.services', [
            'services' => $services->cursor(),
        ]);
    }
    public function getCsvSettings(): array
    {
        if ($this->enclosure) {
            return [
                'delimiter' => ',',
                'enclosure' => '',
            ];
        }
        return [];
    }
}
