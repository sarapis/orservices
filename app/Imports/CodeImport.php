<?php

namespace App\Imports;

use App\Model\Code;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CodeImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $data = [
            'code' => $row['code'],
            'code_system' => $row['code_system'],
            'resource' => $row['resource'],
            'resource_element' => $row['resource_element'],
            'category' => $row['sdoh_category'],
            'description' => $row['description'],
            'is_panel_code' => $row['is_panel_code'],
            'is_multiselect' => $row['is_multiselect'],
        ];
        return new Code($data);
    }
}
