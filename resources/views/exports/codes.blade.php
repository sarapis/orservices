
<table>
    <thead>
        <th>SDOH Domain</th>
        <th>Resource</th>
        <th>Resource Element</th>
        <th>Code System</th>
        <th>Code</th>
        <th>Description</th>
        <th>Grouping</th>
        <th>Definition</th>
        <th>Notes</th>
        <th>id</th>
        {{-- <th>id</th> --}}
        {{-- <th>uid</th>
        <th>Is Panel Code</th>
        <th>Is Multiselect</th>
        <th>Services</th> --}}

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($codes as $value)
        <tr>
            <td>{{ $value->get_category ? $value->get_category->name : '' }}</td>
            <td>{{ $value->resource  }}</td>
            <td>{{ $value->resource_element }}</td>
            <td>{{ $value->code_system }}</td>
            <td>{{ $value->code }}</td>
            <td>{{ $value->description }}</td>
            <td>{{ $value->grouping }}</td>
            <td>{{ $value->definition }}</td>
            <td>{{ $value->notes }}</td>
            <td>{{ $value->code_id }}</td>
            {{-- <td>{{ $value->code_id }}</td>
            <td>{{ $value->id }}</td>
            <td>{{ $value->uid }}</td>
            <td>{{ $value->is_panel_code }}</td>
            <td>{{ $value->is_multiselect }}</td>
            @php
                $name = '';
            @endphp
            @if ($value->code_ledger && count($value->code_ledger) > 0)
                @foreach ($value->code_ledger as $key => $code_ledger)
                    @php
                        $name .= ($key != 0 ? ', ' : '') . ($code_ledger->service ? $code_ledger->service->service_name : '') . ' - ' . ($code_ledger->organization ? $code_ledger->organization->organization_name : '') . ' - ' . $code_ledger->rating;
                    @endphp
                @endforeach
            @endif
            <td>{{ $name }}</td> --}}
        </tr>
        @endforeach
    </tbody>
</table>
