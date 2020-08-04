<?php

namespace App\Imports;

use App\Model\Area;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AreaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'area_recordid' => $row['service_area'],
            'area_service' => $row['service_id'],
            'area_description' => $row['description'],
            'area_date_added' => $row['date_added'],
            'area_multiple_counties' => $row['multiple_counties'],
        ];

        return new Area($array);
    }
}
