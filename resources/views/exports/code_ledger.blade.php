<table>
    <thead>
        <th class="text-center">SDOH Code Resource</th>
        <th class="text-center">SDOH Code Description</th>
        <th class="text-center">SDOH Code Type</th>
        <th class="text-center">SDOH Code ID</th>
        <th class="text-center">Rating</th>
        <th class="text-center">Service</th>
        <th class="text-center">Organization</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($code_ledgers as $value)
        <tr>
            <td>{{ $value->resource }}</td>
            <td>{{ $value->description }}</td>
            <td>{{ $value->code_type }}</td>
            <td>{{ $value->SDOH_code }}</td>
            <td>{{ $value->rating }}</td>
            <td>{{ $value->service ? $value->service->service_name : '' }}</td>
            <td>{{ $value->organization ? $value->organization->organization_name : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
