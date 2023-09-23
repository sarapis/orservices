<?php

namespace App\Exports\zip;

use App\Model\Location;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class LocationZipExport implements FromView, WithCustomCsvSettings
{
    public $organization_ids, $enclosure;

    public function __construct($organization_ids, $enclosure)
    {
        $this->organization_ids = $organization_ids;
        $this->enclosure = $enclosure;
    }
    /**
     * @return View
     */
    public function view(): View
    {
        $locations = Location::select('*');
        if ($this->organization_ids) {
            $locations->where('location_organization', $this->organization_ids);
        }

        return view('exports.zip.locations', [
            'locations' => $locations->cursor(),
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
