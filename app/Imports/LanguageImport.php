<?php

namespace App\Imports;

use App\Model\Language;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LanguageImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['language_recordid', 'location_id', 'service_id', 'language'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
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
