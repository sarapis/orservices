<?php

namespace App\Imports;

use App\Model\Contact;
use App\Model\ServiceContact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactImport implements ToModel, WithHeadingRow
{
    // Columns model() reads without a fallback; CsvImport::assertColumns() checks them before importing.
    public const REQUIRED_COLUMNS = ['id', 'organization_id', 'service_id', 'email', 'name', 'title', 'department'];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        $array = [
            'contact_recordid' => $row['id'],
            'contact_organizations' => $row['organization_id'],
            'contact_services' => $row['service_id'],
            'contact_email' => $row['email'],
            'contact_name' => $row['name'],
            // 'contact_phones' => $row['phone_number'],
            // 'contact_phone_areacode' => $row['phone_areacode'],
            // 'contact_phone_extension' => $row['phone_extension'],
            'contact_title' => $row['title'],
            'contact_department' => $row['department'],
        ];

        if ($row['id'] && $row['service_id']) {

            $service_contact = new ServiceContact();
            $service_contact->service_recordid = $row['service_id'] != 'NULL' ? $row['service_id'] : null;
            $service_contact->contact_recordid = $row['id'];
            $service_contact->save();
        }

        return new Contact($array);
    }
}
