<table>
    <thead>
        <th>service_recordid</th>
        <th>service_name</th>
        <th>service_alternate_name</th>
        <th>service_organization</th>
        <th>service_description</th>
        <th>service_locations</th>
        <th>service_url</th>
        <th>service_email</th>
        <th>service_status</th>
        <th>access_requirement</th>
        <th>service_taxonomy</th>
        <th>service_application_process</th>
        <th>service_wait_time</th>
        <th>service_fees</th>
        <th>service_accreditations</th>
        <th>service_licenses</th>
        <th>service_phones</th>
        <th>service_schedule</th>
        <th>service_contacts</th>
        <th>service_details</th>
        <th>service_address</th>
        <th>service_metadata</th>
        <th>service_program</th>
        <th>service_code</th>
        <th>SDOH_code</th>
        <th>code_category_ids</th>
        <th>procedure_grouping</th>
        <th>service_tag</th>
        <th>service_airs_taxonomy_x</th>
        <th>flag</th>
        <th>bookmark</th>
        <th>service_language</th>
        <th>service_interpretation</th>
        <th>eligibility_description</th>
        <th>minimum_age</th>
        <th>maximum_age</th>
        <th>service_alert</th>
    </thead>
    <tbody>
    <tr></tr>
    @foreach ($services as $row)
        <tr>
            <td>{{ $row->service_recordid }}</td>
            <td>{{ $row->service_name }}</td>
            <td>{{ $row->service_alternate_name }}</td>
            <td>{{$row->service_organization }}</td>
            <td>{{ $row->service_description }}</td>
            <td>{{ $row->service_locations }}</td>
            <td>{{ $row->service_url }}</td>
            <td>{{ $row->service_email }}</td>
            <td>{{ $row->service_status }}</td>
            <td>{{ $row->access_requirement }}</td>
            <td>{{ $row->service_taxonomy }}</td>
            <td>{{ $row->service_application_process }}</td>
            <td>{{ $row->service_wait_time }}</td>
            <td>{{ $row->service_fees }}</td>
            <td>{{ $row->service_accreditations }}</td>
            <td>{{ $row->service_licenses }}</td>
            <td>{{ $row->service_phones }}</td>
            <td>{{ $row->service_schedule }}</td>
            <td>{{ $row->service_contacts }}</td>
            <td>{{ $row->service_details }}</td>
            <td>{{ $row->service_address }}</td>
            <td>{{ $row->service_metadata }}</td>
            <td>{{ $row->service_program }}</td>
            <td>{{ $row->service_code }}</td>
            <td>{{ $row->SDOH_code }}</td>
            <td>{{ $row->code_category_ids }}</td>
            <td>{{ $row->procedure_grouping }}</td>
            <td>{{ $row->service_tag }}</td>
            <td>{{ $row->service_airs_taxonomy_x }}</td>
            <td>{{ $row->flag }}</td>
            <td>{{ $row->bookmark }}</td>
            <td>{{ $row->service_language }}</td>
            <td>{{ $row->service_interpretation }}</td>
            <td>{{ $row->eligibility_description }}</td>
            <td>{{ $row->minimum_age }}</td>
            <td>{{ $row->maximum_age }}</td>
            <td>{{ $row->service_alert }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
