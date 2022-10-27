<?php

namespace App\Imports;

use App\Model\TaxonomyType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaxonomyTypeImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'taxonomy_type_recordid' => $row['taxonomy_type_recordid'],
            'name' => $row['name'],
            'type' => $row['type'],
            'order' => $row['order'],
            'reference_url' => $row['reference_url'],
            'notes' => $row['notes'],
        ];
        return new TaxonomyType($array);
    }
}
