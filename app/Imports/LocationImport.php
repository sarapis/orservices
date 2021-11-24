<?php

namespace App\Imports;

use App\Model\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'location_recordid' => $row['id'],
            'location_name' => $row['name'],
            'location_organization' => $row['organization_id'],
            'location_alternate_name' => isset($row['alternate_name']) ? $row['alternate_name'] : null,
            'location_description' => isset($row['description']) ? $row['description'] : null,
            'location_latitude' => isset($row['latitude']) ? $row['latitude'] : null,
            'location_longitude' => isset($row['longitude']) ? $row['longitude'] : null,
            'location_transportation' => isset($row['transportation']) ? $row['transportation'] : null,
        ];
        return new Location($array);
    }
}
