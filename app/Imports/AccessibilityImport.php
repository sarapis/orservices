<?php

namespace App\Imports;

use App\Model\Accessibility;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccessibilityImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['id', 'location_id', 'accessibility', 'details'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        $array = [
            'accessibility_recordid' => $row['id'],
            'accessibility_location' => $row['location_id'],
            'accessibility' => $row['accessibility'],
            'accessibility_details' => $row['details'],
        ];
        return new Accessibility($array);
    }
}
