<table>
    <thead>
        <th>id</th>
        <th>organization_id</th>
        <th>program_id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>url</th>
        <th>email</th>
        <th>status</th>
        <th>interpretation_services</th>
        <th>application_process</th>
        <th>wait_time</th>
        <th>fees</th>
        <th>accreditations</th>
        <th>licenses</th>
        <th>taxonomy_ids</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($services as $value)
        <tr>
            <td>{{ $value->service_recordid }}</td>
            <td>{{ $value->service_organization }}</td>
            <td>{{ $value->service_program }}</td>
            <td>{{ $value->service_name }}</td>
            <td>{{ $value->service_alternate_name }}</td>
            <td>{{ $value->service_description }}</td>
            <td>{{ $value->service_url }}</td>
            <td>{{ $value->service_email }}</td>
            <td>{{ $value->service_status }}</td>
            <td></td>
            <td>{{ $value->service_application_process }}</td>
            <td>{{ $value->service_wait_time }}</td>
            <td>{{ $value->service_fees }}</td>
            <td>{{ $value->service_accreditations }}</td>
            <td>{{ $value->service_licenses }}</td>
            <td>{{ $value->service_taxonomy }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
