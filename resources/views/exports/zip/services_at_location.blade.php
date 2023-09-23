<table>
    <thead>
        <th>id</th>
        <th>service_id</th>
        <th>location_id</th>
        <th>description</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($services_at_location as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->service_recordid }}</td>
                <td>{{ $row->location_recordid }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
