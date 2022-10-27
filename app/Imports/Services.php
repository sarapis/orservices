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
            'service_recordid' => isset($row['service_recordid']) ? $row['service_recordid'] : null,
            'service_name' => isset($row['service_name']) ? $row['service_name'] : null,
            'service_alternate_name' => isset($row['service_alternate_name']) ? $row['service_alternate_name'] : null,
            'service_organization' => isset($row['service_organization']) ? $row['service_organization'] : null,
            'service_description' => isset($row['service_description']) ? $row['service_description'] : null,
            'service_locations' => isset($row['service_locations']) ? $row['service_locations'] : null,
            'service_url' => isset($row['service_url']) ? $row['service_url'] : null,
            'service_email' => isset($row['service_email']) ? $row['service_email'] : null,
            'service_status' => isset($row['service_status']) ? $row['service_status'] : null,
            'access_requirement' => isset($row['access_requirement']) ? $row['access_requirement'] : null,
            'service_taxonomy' => isset($row['service_taxonomy']) ? $row['service_taxonomy'] : null,
            'service_application_process' => isset($row['service_application_process']) ? $row['service_application_process'] : null,
            'service_wait_time' => isset($row['service_wait_time']) ? $row['service_wait_time'] : null,
            'service_fees' => isset($row['service_fees']) ? $row['service_fees'] : null,
            'service_accreditations' => isset($row['service_accreditations']) ? $row['service_accreditations'] : null,
            'service_licenses' => isset($row['service_licenses']) ? $row['service_licenses'] : null,
            'service_phones' => isset($row['service_phones']) ? $row['service_phones'] : null,
            'service_schedule' => isset($row['service_schedule']) ? $row['service_schedule'] : null,
            'service_contacts' => isset($row['service_contacts']) ? $row['service_contacts'] : null,
            'service_details' => isset($row['service_details']) ? $row['service_details'] : null,
            'service_address' => isset($row['service_address']) ? $row['service_address'] : null,
            'service_metadata' => isset($row['service_metadata']) ? $row['service_metadata'] : null,
            'service_program' => isset($row['service_program']) ? $row['service_program'] : null,
            'service_code' => isset($row['service_code']) ? $row['service_code'] : null,
            'SDOH_code' => isset($row['SDOH_code']) ? $row['SDOH_code'] : null,
            'code_category_ids' => isset($row['code_category_ids']) ? $row['code_category_ids'] : null,
            'procedure_grouping' => isset($row['procedure_grouping']) ? $row['procedure_grouping'] : null,
            'service_tag' => isset($row['service_tag']) ? $row['service_tag'] : null,
            'service_airs_taxonomy_x' => isset($row['service_airs_taxonomy_x']) ? $row['service_airs_taxonomy_x'] : null,
            'flag' => isset($row['flag']) ? $row['flag'] : null,
            'bookmark' => isset($row['bookmark']) ? $row['bookmark'] : null,
        ];
        if ($row['service_organization']) {
            $organization_recordids = explode(',', $row['service_organization']);
            foreach ($organization_recordids as $key => $value) {
                $service_organization = new ServiceOrganization();
                $service_organization->service_recordid = $row['service_recordid'];
                $service_organization->organization_recordid = $value;
                $service_organization->save();
            }
        }

        return new Service($array);
    }
}
