<?php

namespace App\Imports;

use App\Model\Language;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LanguageImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'language_recordid' => $row['language_recordid'],
            'language_location' => $row['location_id'],
            'language_service' => $row['service_id'],
            'language' => $row['language'],
        ];

        return new Language($array);
    }
}
