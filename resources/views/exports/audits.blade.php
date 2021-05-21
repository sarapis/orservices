
<table>
    <thead>
        <th>id</th>
        <th>Timestamp</th>
        <th>Organization</th>
        <th>User</th>
        <th>Event</th>
        <th>Data Type</th>
        <th>Field Type</th>
        <th>Change From</th>
        <th>Change To</th>
        <th>Record ID</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($audits as $value)
        <tr>
            <td>{{ $value['id'] }}</td>
            <td>{{ $value['created_at'] }}</td>
            <td>{{ $value['organization']  }}</td>
            <td>{{ $value['user'] }}</td>
            <td>{{ $value['event'] }}</td>
            <td>{{ $value['auditable_type'] }}</td>
            <td>{{ $value['field_type']  }}</td>
            <td>{{ $value['change_from'] }}</td>
            <td>{{ $value['change_to'] }}</td>
            <td>{{ $value['auditable_id'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
