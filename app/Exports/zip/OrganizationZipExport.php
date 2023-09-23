<?php

namespace App\Exports\zip;

use App\Model\Organization;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class OrganizationZipExport implements FromView, WithCustomCsvSettings
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
        $organizations = Organization::select('*');
        if ($this->organization_ids) {
            $organizations->where('organization_recordid', $this->organization_ids);
        }

        return view('exports.zip.organizations', [
            'organizations' => $organizations->cursor(),
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
