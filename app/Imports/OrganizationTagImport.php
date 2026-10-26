<?php

namespace App\Imports;

use App\Model\OrganizationTag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganizationTagImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['id', 'tag'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        return new OrganizationTag([
            'id' => $row['id'],
            'tag' => $row['tag']
        ]);
    }
}
