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
            'location_services' => isset($row['location_services']) ? $row['location_services'] : null,
            'location_phones' => isset($row['location_phones']) ? $row['location_phones'] : null,
            'location_details' => isset($row['location_details']) ? $row['location_details'] : null,
            'location_schedule' => isset($row['location_schedule']) ? $row['location_schedule'] : null,
            'location_address' => isset($row['location_address']) ? $row['location_address'] : null,
            'location_tag' => isset($row['location_tag']) ? $row['location_tag'] : null,
            'enrich_flag' => isset($row['enrich_flag']) ? $row['enrich_flag'] : null,
            'flag' => isset($row['flag']) ? $row['flag'] : null,
        ];
        return new Location($array);
    }
}
