<?php

namespace App\Imports;

use App\Model\Area;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AreaImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['service_area', 'service_id', 'description', 'date_added', 'multiple_counties'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
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
