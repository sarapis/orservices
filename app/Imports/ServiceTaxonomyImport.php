<?php

namespace App\Imports;

use App\Model\ServiceTaxonomy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceTaxonomyImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['taxonomy_term_id', 'service_id'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        $serviceTaxonomy = ServiceTaxonomy::max('taxonomy_recordid');
        $array = [
            'taxonomy_recordid' => $row['taxonomy_term_id'],
            'service_recordid' => $row['service_id'],
            // 'taxonomy_detail' => $row['taxonomy_detail'],
        ];
        return new ServiceTaxonomy($array);
    }
}
