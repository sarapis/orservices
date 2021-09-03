
<table>
    <thead>
        <th>SDOH Category</th>
        <th>Resource</th>
        <th>Resource Element</th>
        <th>Code System</th>
        <th>Code</th>
        <th>Description</th>
        <th>Is Panel Code</th>
        <th>Is Multiselect</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($codes as $value)
        <tr>
            <td>{{ $value->category }}</td>
            <td>{{ $value->resource  }}</td>
            <td>{{ $value->resource_element }}</td>
            <td>{{ $value->code_system }}</td>
            <td>{{ $value->code }}</td>
            <td>{{ $value->description }}</td>
            <td>{{ $value->is_panel_code }}</td>
            <td>{{ $value->is_multiselect }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
