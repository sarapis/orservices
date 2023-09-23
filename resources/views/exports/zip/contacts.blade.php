<table>
    <thead>
        <th>id</th>
        <th>organization_id</th>
        <th>service_id</th>
        <th>service_at_location_id</th>
        <th>name</th>
        <th>title</th>
        <th>department</th>
        <th>email</th>
        <th>contact_phones</th>
        <th>visibility</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($contacts as $row)
            <tr>
                <td>{{ $row->contact_recordid }}</td>
                <td>{{ $row->contact_organizations }}</td>
                <td>{{ $row->contact_services }}</td>
                <td></td>
                <td>{{ $row->contact_name }}</td>
                <td>{{ $row->contact_title }}</td>
                <td>{{ $row->contact_department }}</td>
                <td>{{ $row->contact_email }}</td>
                <td>{{ $row->contact_phones }}</td>
                <td>{{ $row->visibility }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
