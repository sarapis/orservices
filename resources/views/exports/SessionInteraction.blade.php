<table>
    <thead>
        <th>Method</th>
        <th>Timestamp</th>
        <th>Notes</th>
        <th>Disposition</th>
        <th>Records Edited</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($ineteractions as $value)
        <tr>
            <td>{{ $value->interaction_method }}</td>
            <td>{{ $value->interaction_timestamp }}</td>
            <td>{{ $value->interaction_notes }}</td>
            <td>{{ $value->interaction_disposition }}</td>
            <td>{{ $value->interaction_records_edited }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
