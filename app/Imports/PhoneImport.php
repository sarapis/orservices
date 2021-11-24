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
            'phone_locations' => $row['location_id'],
            'phone_services' => $row['service_id'],
            'phone_organizations' => $row['organization_id'],
            'phone_contacts' => $row['contact_id'],
            'phone_number' => $row['number'],
            'phone_extension' => $row['extension'],
            'phone_type' => $row['type'],
            'phone_language' => $row['language'],
            'phone_description' => $row['description'],
            'mail_priority' => $row['priority'],
        ];

        if ($row['id'] && $row['service_id']) {
            $service_recordids = explode(',', $row['service_id']);
            foreach ($service_recordids as $key => $value1) {
                # code...
            }
            $service_phone = new ServicePhone();
            $service_phone->service_recordid = $value1;
            $service_phone->phone_recordid = $row['id'] != null ? $row['id'] : null;
            $service_phone->save();
        }
        if ($row['id'] && $row['location_id']) {
            $location_recordids = explode(',', $row['location_id']);
            foreach ($location_recordids as $key => $value) {
                $location_phone = new LocationPhone();
                $location_phone->location_recordid = $value;
                $location_phone->phone_recordid = $row['id'] != null ? $row['id'] : null;
                $location_phone->save();
            }
        }

        return new Phone($array);
    }
}
