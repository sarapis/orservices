<?php

namespace App\Imports;

use App\Model\ServicePhone;
use Maatwebsite\Excel\Concerns\ToModel;

class ServicePhoneImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ServicePhone([
            'service_recordid' => $row['service_recordid'],
            'phone_recordid' => $row['phone_recordid'],
        ]);
    }
}
