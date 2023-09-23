<?php

namespace App\Imports;

use App\Model\LocationAddress;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationAddressImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new LocationAddress([
            'location_recordid' => $row['location_recordid'],
            'address_recordid' => $row['address_recordid'],
        ]);
    }
}
