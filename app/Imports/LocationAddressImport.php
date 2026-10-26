<?php

namespace App\Imports;

use App\Model\LocationAddress;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationAddressImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['location_recordid', 'address_recordid'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        return new LocationAddress([
            'location_recordid' => $row['location_recordid'],
            'address_recordid' => $row['address_recordid'],
        ]);
    }
}
