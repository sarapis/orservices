<?php

namespace App\Imports;

use App\Model\Code;
use App\Model\CodeCategory;
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
        $category = null;
        if (isset($row['sdoh_category'])) {
            $category = $row['sdoh_category'];
        } else if (isset($row['sdoh_domain'])) {
            $category = $row['sdoh_domain'];
        }
        if ($category) {
            $codeCategory = CodeCategory::firstOrCreate(
                [
                    'name' => trim($category)
                ],
                [
                    'name' => trim($category),
                ]
            );
        }
        $data = [
            'code' => isset($row['code']) ? $row['code'] : null,
            'code_system' => isset($row['code_system']) ? $row['code_system'] : null,
            'resource' => isset($row['resource']) ? trim($row['resource']) : null,
            'resource_element' => isset($row['resource_element']) ? trim($row['resource_element']) : null,
            'category' => isset($codeCategory) ? $codeCategory->id : null,
            'description' => isset($row['description']) ? $row['description'] : null,
            'grouping' => isset($row['grouping']) ? $row['grouping'] : null,
            'definition' => isset($row['definition']) ? $row['definition'] : null,
            'is_panel_code' => isset($row['is_panel_code']) ? $row['is_panel_code'] : null,
            'is_multiselect' => isset($row['is_multiselect']) ? $row['is_multiselect'] : null,
            'code_id' => isset($row['id']) ? $row['id'] : null,
            'notes' => isset($row['notes']) ? $row['notes'] : null,
            'uid' => isset($row['uid']) ? $row['uid'] : null,
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
//                     'grouping' => isset($row['grouping']) ? $row['grouping'] : null,
//                     'definition' => isset($row['definition']) ? $row['definition'] : null,
//                     'is_panel_code' => $row['is_panel_code'],
//                     'is_multiselect' => $row['is_multiselect'],
//                     'code_id' => isset($row['id']) ? $row['id'] : null,
//                     'notes' => isset($row['notes']) ? $row['notes'] : null,
//                     'uid' => isset($row['uid']) ? $row['uid'] : null,
//                 ];
//                 Code::create($data);
//             }
//         }
//     }
// }
