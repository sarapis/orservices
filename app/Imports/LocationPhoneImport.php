<?php

namespace App\Imports;

use App\Model\LocationPhone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationPhoneImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['location_recordid', 'phone_recordid'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        return new LocationPhone([
            'location_recordid' => $row['location_recordid'],
            'phone_recordid' => $row['phone_recordid'],
        ]);
    }
}
