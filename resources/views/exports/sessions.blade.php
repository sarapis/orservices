<table>
    <thead>
        <th>Session ID</th>
        <th>User Name</th>
        <th>Organization</th>
        <th>Organization Tags</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Duration</th>
        <th>Status</th>
        <th>Notes</th>
        <th>Method</th>
        <th>Timestamp</th>
        <th>Disposition</th>
        <th>Records Edited</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($sessions as $value)
        <tr>
            <td>{{ $value->session_recordid }}</td>
            <td>{{ $value->user ? $value->user->first_name. ' ' .$value->user->last_name : ''  }}</td>
            <td>{{ $value->organization ? $value->organization->organization_name : '' }}</td>
            <td>{{ $value->organization ? $value->organization->organization_tag : '' }}</td>
            <td>{{ $value->session_start_datetime  }}</td>
            <td>{{ $value->session_end_datetime }}</td>
            <td>{{ $value->session_duration }}</td>
            <td>{{ $value->session_verification_status }}</td>
            <td>{{ $value->session_notes }}</td>
            <td>{{ $value->session_method }}</td>
            <td>{{ $value->created_at }}</td>
            <td>{{ $value->session_disposition }}</td>
            <td>{{ $value->session_edits }}</td>/
        </tr>
        @endforeach
    </tbody>
</table>
