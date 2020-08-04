<?php

namespace App\Imports;

use App\Model\ServiceLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceLocationImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'location_recordid' => $row['location_id'],
            'service_recordid' => $row['service_id'],
        ];

        return new ServiceLocation($array);
    }
}
