<table>
    <thead>
        <th>id</th>
        <th>organization_id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>transportation</th>
        <th>latitude</th>
        <th>longitude</th>
        <th>location_services</th>
        <th>location_phones</th>
        <th>location_details</th>
        <th>location_schedule</th>
        <th>location_address</th>
        <th>location_tag</th>
        <th>enrich_flag</th>
        <th>flag</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($locations as $row)
            <tr>
                <td>{{ $row->location_recordid }}</td>
                <td>{{ $row->location_organization }}</td>
                <td>{{ $row->location_name }}</td>
                <td>{{ $row->location_alternate_name }}</td>
                <td>{{ $row->location_description }}</td>
                <td>{{ $row->location_transportation }}</td>
                <td>{{ $row->location_latitude }}</td>
                <td>{{ $row->location_longitude }}</td>
                <td>{{ $row->location_services }}</td>
                <td>{{ $row->location_phones }}</td>
                <td>{{ $row->location_details }}</td>
                <td>{{ $row->location_schedule }}</td>
                <td>{{ $row->location_address }}</td>
                <td>{{ $row->location_tag }}</td>
                <td>{{ $row->enrich_flag }}</td>
                <td>{{ $row->flag }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
