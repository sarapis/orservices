<table>
    <thead>
        <th>id</th>
        <th>organization_id</th>
        <th>program_id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>url</th>
        <th>email</th>
        <th>status</th>
        <th>interpretation_services</th>
        <th>application_process</th>
        <th>wait_time</th>
        <th>fees</th>
        <th>accreditations</th>
        <th>licenses</th>
        <th>taxonomy_ids</th>
        <th>taxonomies</th>
        <th>SDOH Code</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($services as $value)
        <tr>
            <td>{{ $value->service_recordid }}</td>
            <td>{{ $value->organizations ? $value->organizations->organization_name : ''  }}</td>
            <td>{{ $value->service_program }}</td>
            <td>{{ $value->service_name }}</td>
            <td>{{ $value->service_alternate_name }}</td>
            <td>{{ $value->service_description }}</td>
            <td>{{ $value->service_url }}</td>
            <td>{{ $value->service_email }}</td>
            <td>{{ $value->service_status }}</td>
            <td></td>
            <td>{{ $value->service_application_process }}</td>
            <td>{{ $value->service_wait_time }}</td>
            <td>{{ $value->service_fees }}</td>
            <td>{{ $value->service_accreditations }}</td>
            <td>{{ $value->service_licenses }}</td>
            <td>{{ $value->service_taxonomy }}</td>
            @if (isset($value->taxonomy) && count($value->taxonomy) > 0)
            <td>
                @foreach ($value->taxonomy as $item)
                    {{ $item->taxonomy_name .' , ' }}
                @endforeach
            </td>
            @else
            <td></td>
            @endif
            @if (isset($value->codes) && count($value->codes) > 0)
            <td>
                @foreach ($value->codes as $item)
                    {{ $item->resource . ' - ' . $item->description . ' - ' . $item->rating .' , ' }}
                @endforeach
            </td>
            @else
            <td></td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
