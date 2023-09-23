<?php

namespace App\Exports\zip;

use App\Model\LocationAddress;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationAddressZipExport implements FromCollection, WithCustomCsvSettings, WithHeadings
{
    public $enclosure;
    public function __construct($enclosure)
    {
        $this->enclosure = $enclosure;
    }
    public function headings(): array
    {
        return [
            'id',
            'location_recordid',
            'address_recordid',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return LocationAddress::all();
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
