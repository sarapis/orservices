<table>
    <thead>
        <th>id</th>
        <th>organization_id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>program_service_relationship</th>
        <th>services</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($programs as $row)
            <tr>
                <td>{{ $row->program_recordid }}</td>
                <td>{{ $row->organizations }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->alternate_name }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->program_service_relationship }}</td>
                <td>{{ $row->services }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
