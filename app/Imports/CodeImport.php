<?php

namespace App\Imports;

use App\Model\Code;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
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
            'resource' => trim($row['resource']),
            'resource_element' => trim($row['resource_element']),
            'category' => trim($row['sdoh_category']),
            'description' => $row['description'],
            'grouping' => isset($row['grouping']) ? $row['grouping'] : '',
            'definition' => isset($row['definition']) ? $row['definition'] : '',
            'is_panel_code' => $row['is_panel_code'],
            'is_multiselect' => $row['is_multiselect'],
            'code_id' => isset($row['id']) ? $row['id'] : '',
            'notes' => isset($row['notes']) ? $row['notes'] : '',
            'uid' => isset($row['uid']) ? $row['uid'] : '',
        ];
        return new Code($data);
    }
}

// class CodeImport implements ToCollection, WithHeadingRow
// {
//     public function collection(collection $rows)
//     {
//         foreach ($rows as $row) {
//             $sdoh_categories = explode(',', $row['sdoh_categories']);
//             foreach ($sdoh_categories as $key => $value) {
//                 $data = [
//                     'code' => $row['code'],
//                     'code_system' => $row['code_system'],
//                     'resource' => trim($row['resource']),
//                     'resource_element' => trim($row['resource_element']),
//                     'category' => trim($value),
//                     'description' => $row['description'],
//                     'grouping' => isset($row['grouping']) ? $row['grouping'] : '',
//                     'definition' => isset($row['definition']) ? $row['definition'] : '',
//                     'is_panel_code' => $row['is_panel_code'],
//                     'is_multiselect' => $row['is_multiselect'],
//                     'code_id' => isset($row['id']) ? $row['id'] : '',
//                     'notes' => isset($row['notes']) ? $row['notes'] : '',
//                     'uid' => isset($row['uid']) ? $row['uid'] : '',
//                 ];
//                 Code::create($data);
//             }
//         }
//     }
// }
