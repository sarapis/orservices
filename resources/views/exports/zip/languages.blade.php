<table>
    <thead>
        <th>id</th>
        <th>service_id</th>
        <th>location_id</th>
        <th>language</th>
        <th>language_recordid</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($languages as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->language_service }}</td>
                <td>{{ $row->language_location }}</td>
                <td>{{ $row->language }}</td>
                <td>{{ $row->language_recordid }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
