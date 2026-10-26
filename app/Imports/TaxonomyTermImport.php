<?php

namespace App\Imports;

use App\Model\TaxonomyTerm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaxonomyTermImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['taxonomy_recordid', 'taxonomy_type_recordid'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        $array = [
            'taxonomy_recordid' => $row['taxonomy_recordid'],
            'taxonomy_type_recordid' => $row['taxonomy_type_recordid'],
        ];
        return new TaxonomyTerm($array);
    }
}
