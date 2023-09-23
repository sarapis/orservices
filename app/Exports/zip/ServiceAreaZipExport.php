<?php

namespace App\Exports\zip;

use App\Model\ServiceArea;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ServiceAreaZipExport implements FromView, WithCustomCsvSettings
{
    public $enclosure;
    public function __construct($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    /**
     * @return View
     */
    public function view(): View
    {
        $service_areas = ServiceArea::select('*');

        return view('exports.zip.service_areas', [
            'service_areas' => $service_areas->cursor(),
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
