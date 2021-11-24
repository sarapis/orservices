<?php

namespace App\Imports;

use App\Model\Address;
use App\Model\LocationAddress;
use App\Model\ServiceAddress;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AddressImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'address_recordid' => $row['id'],
            'address_locations' => $row['location_id'],
            'address_1' => $row['address_1'],
            'address_2' => $row['address_2'],
            'address_city' => $row['city'],
            'address_postal_code' => $row['postal_code'],
            'address_state_province' => $row['state_province'],
            'address_country' => $row['country'],
            // 'address_organization' => $row['organization_id'],
            'address_attention' => $row['attention'],
            'address_region' => $row['region'],
        ];

        if ($row['location_id']) {
            $location_address = new LocationAddress();
            $location_address->location_recordid = $row['location_id'] != 'NULL' ? $row['location_id'] : null;
            $location_address->address_recordid = $row['id'];
            $location_address->save();
        }

        // if ($row['location_id']) {
        //     $service_address = new ServiceAddress();
        //     $service_address->service_recordid = $row['location_id'] != 'NULL' ? $row['location_id'] : null;
        //     $service_address->address_recordid = $row['address_recordid'];
        //     $service_address->save();
        // }

        return new Address();
    }
}
