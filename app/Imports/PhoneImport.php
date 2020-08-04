<?php

namespace App\Imports;

use App\Model\LocationPhone;
use App\Model\Phone;
use App\Model\ServicePhone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PhoneImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'phone_recordid' => $row['id'],
            'phone_services' => $row['service_id'],
            'phone_locations' => $row['location_id'],
            'phone_number' => $row['number'],
            'phone_organizations' => $row['organization_id'],
            'phone_contacts' => $row['contact_id'],
            'phone_extension' => $row['extension'],
            'phone_type' => $row['type'],
            'phone_language' => $row['language'],
            'phone_description' => $row['description'],
        ];

        if ($row['id'] && $row['service_id']) {
            $service_phone = new ServicePhone();
            $service_phone->service_recordid = $row['service_id'] != 'NULL' ? $row['service_id'] : null;
            $service_phone->phone_recordid = $row['id'] != 'NULL' ? $row['id'] : null;
        $service_phone->save();
        }
        if ($row['id'] && $row['location_id']) {
            $location_phone = new LocationPhone();
            $location_phone->location_recordid = $row['location_id'] != 'NULL' ? $row['location_id'] : null;
            $location_phone->phone_recordid = $row['id'] != 'NULL' ? $row['id'] : null;
            $location_phone->save();
        }

        return new Phone($array);
    }
}
