<table>
    <thead>
        <th>id</th>
        <th>location_id</th>
        <th>service_id</th>
        <th>organization_id</th>
        <th>contact_id</th>
        <th>service_at_location_id</th>
        <th>number</th>
        <th>extension</th>
        <th>type</th>
        <th>language</th>
        <th>description</th>
        <th>department</th>
        <th>priority</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($phones as $row)
            <tr>
                <td>{{ $row->phone_recordid }}</td>
                <td>{{ $row->phone_locations }}</td>
                <td>{{ $row->phone_services }}</td>
                <td>{{ $row->phone_organizations }}</td>
                <td>{{ $row->phone_contacts }}</td>
                <td></td>
                <td>{{ $row->phone_number }}</td>
                <td>{{ $row->phone_extension }}</td>
                <td>{{ $row->phone_type }}</td>
                <td>{{ $row->phone_language }}</td>
                <td>{{ $row->phone_description }}</td>
                <td></td>
                <td>{{ $row->main_priority }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
