<?php

namespace App\Imports;

use App\Model\TaxonomyTerm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaxonomyTermImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'taxonomy_recordid' => $row['taxonomy_recordid'],
            'taxonomy_type_recordid' => $row['taxonomy_type_recordid'],
        ];
        return new TaxonomyTerm($array);
    }
}
