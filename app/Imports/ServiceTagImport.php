<?php

namespace App\Imports;

use App\Model\ServiceTag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ServiceTagImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ServiceTag([
            'id' => $row['id'],
            'tag' => $row['tag']
        ]);
    }
}
