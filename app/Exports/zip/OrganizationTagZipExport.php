<?php

namespace App\Exports\zip;

use App\Model\OrganizationTag;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class OrganizationTagZipExport implements FromView, WithCustomCsvSettings
{
    public $enclosure;
    public function __construct($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    /**
     * @return \Illuminate\Support\View
     */
    public function view(): View
    {
        return view('exports.zip.organization_tag', [
            'organizationTags' => OrganizationTag::get(),
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
