<table>
    <thead>
        <th>id</th>
        <th>location_id</th>
        <th>accessibility</th>
        <th>details</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($accessibility_for_disabilities as $row)
            <tr>
                <td>{{ $row->accessibility_recordid }}</td>
                <td>{{ $row->accessibility_location }}</td>
                <td>{{ $row->accessibility }}</td>
                <td>{{ $row->accessibility_details }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
