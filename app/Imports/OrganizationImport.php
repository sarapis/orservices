<?php

namespace App\Imports;

use App\Model\Organization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganizationImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'organization_recordid' => $row['id'],
            'organization_name' => $row['name'],
            'organization_alternate_name' => $row['alternate_name'],
            'organization_description' => $row['description'],
            'organization_url' => $row['url'],
            'organization_email' => $row['email'],
            'organization_tax_status' => $row['tax_status'],
            'organization_tax_id' => $row['tax_id'],
            'organization_year_incorporated' => $row['year_incorporated'],
            'organization_legal_status' => $row['legal_status'],
        ];

        return new Organization($array);
    }
}
