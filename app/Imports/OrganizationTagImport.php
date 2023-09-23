<?php

namespace App\Imports;

use App\Model\OrganizationTag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganizationTagImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new OrganizationTag([
            'id' => $row['id'],
            'tag' => $row['tag']
        ]);
    }
}
