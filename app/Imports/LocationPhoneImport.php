<?php

namespace App\Imports;

use App\Model\LocationPhone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationPhoneImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new LocationPhone([
            'location_recordid' => $row['location_recordid'],
            'phone_recordid' => $row['phone_recordid'],
        ]);
    }
}
