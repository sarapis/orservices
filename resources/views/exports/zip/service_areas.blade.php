<table>
    <thead>
        <th>id</th>
        <th>service_id</th>
        <th>service_area</th>
        <th>description</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($service_areas as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->services }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
