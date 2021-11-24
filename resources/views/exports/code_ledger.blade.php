<table>
    <thead>
        <th class="text-center">Action</th>
        <th class="text-center">Ledger ID</th>
        <th class="text-center">SDOH Code</th>
        <th class="text-center">SDOH Code Category</th>
        <th class="text-center">SDOH Code Resource</th>
        <th class="text-center">SDOH Code Description</th>
        <th class="text-center">SDOH Code Type</th>
        {{-- <th class="text-center">SDOH Code ID</th> --}}
        <th class="text-center">Rating</th>
        <th class="text-center">Service</th>
        <th class="text-center">Organization</th>
        <th class="text-center">Timestamp</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($code_ledgers as $value)
        <tr>
            <td>{{ $value->deleted_at ? 'Removed' : ($value->updated_at->gt($value->created_at) ?  'Updated' : ($value->updated_at->eq($value->created_at) ? 'Added' : '')) }}</td>
            <td>{{ $value->id }}</td>
            <td>{{ $value->code }}</td>
            <td>{{ $value->code_data ?  $value->code_data->category : '' }}</td>
            <td>{{ $value->resource }}</td>
            <td>{{ $value->description }}</td>
            <td>{{ $value->code_type }}</td>
            {{-- <td>{{ $value->SDOH_code }}</td> --}}
            <td>{{ $value->rating }}</td>
            <td>{{ $value->service ? $value->service->service_name : '' }}</td>
            <td>{{ $value->organization ? $value->organization->organization_name : '' }}</td>
            <td>{{ $value->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
