<?php

namespace App\Imports;

use App\Model\ServicePhone;
use Maatwebsite\Excel\Concerns\ToModel;

class ServicePhoneImport implements ToModel
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['service_recordid', 'phone_recordid'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        return new ServicePhone([
            'service_recordid' => $row['service_recordid'],
            'phone_recordid' => $row['phone_recordid'],
        ]);
    }
}
