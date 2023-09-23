<table>
    <thead>
        <th>id</th>
        <th>name</th>
        <th>service_id</th>
        <th>location_id</th>
        <th>service_at_location_id</th>
        <th>weekday</th>
        <th>valid_from</th>
        <th>valid_to</th>
        <th>opens</th>
        <th>closes</th>
        <th>dtstart</th>
        <th>until</th>
        <th>wkst</th>
        <th>freq</th>
        <th>interval</th>
        <th>byday</th>
        <th>byweekno</th>
        <th>bymonthday</th>
        <th>byyearday</th>
        <th>description</th>
        <th>opens_at</th>
        <th>closes_at</th>
        <th>schedule_holiday</th>
        <th>schedule_closed</th>
        <th>open_24_hours</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($schedules as $row)
            <tr>
                <td>{{ $row->schedule_recordid }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->services }}</td>
                <td>{{ $row->locations }}</td>
                <td>{{ $row->service_at_location }}</td>
                <td>{{ $row->weekday }}</td>
                <td>{{ $row->valid_from }}</td>
                <td>{{ $row->valid_to }}</td>
                <td>{{ $row->schedule_closed ? 'Closed' : ($row->schedule_holiday ? 'Holiday' : ($row->open_24_hours ? '24 Hours' : $row->opens)) }}</td>
                <td>{{ $row->closes }}</td>
                <td>{{ $row->dtstart }}</td>
                <td>{{ $row->until }}</td>
                <td>{{ $row->wkst }}</td>
                <td>{{ $row->freq }}</td>
                <td>{{ $row->interval }}</td>
                <td>{{ $row->byday }}</td>
                <td>{{ $row->byweekno }}</td>
                <td>{{ $row->bymonthday }}</td>
                <td>{{ $row->byyearday }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->opens_at }}</td>
                <td>{{ $row->closes_at }}</td>
                <td>{{ $row->schedule_holiday }}</td>
                <td>{{ $row->schedule_closed }}</td>
                <td>{{ $row->open_24_hours }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
