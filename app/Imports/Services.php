<?php

namespace App\Imports;

use App\Model\CSV_Source;
use App\Model\Service;
use App\Model\ServiceOrganization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Services implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $array = [
            'service_recordid' => $row['id'],
            'service_name' => $row['name'],
            'service_organization' => $row['organization_id'],
            'service_alternate_name' => $row['alternate_name'],
            'service_description' => $row['description'],
            'service_application_process' => $row['application_process'],
            'service_url' => $row['url'],
            'service_program' => $row['program_id'],
            'service_email' => $row['email'],
            'service_status' => $row['status'],
            'service_wait_time' => $row['wait_time'],
            'service_fees' => $row['fees'],
            'service_accreditations' => $row['accreditations'],
            'service_licenses' => $row['licenses'],
        ];
        if ($row['organization_id']) {
            $organization_recordids = explode(',', $row['organization_id']);
            foreach ($organization_recordids as $key => $value) {
                $service_organization = new ServiceOrganization();
                $service_organization->service_recordid = $row['id'];
                $service_organization->organization_recordid = $value;
                $service_organization->save();
            }
        }

        return new Service($array);
    }
}
