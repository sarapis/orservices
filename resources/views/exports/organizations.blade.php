<table>
    <thead>
        <th>id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>url</th>
        <th>email</th>
        <th>tax_status</th>
        <th>tax_id</th>
        <th>year_incorporated</th>
        <th>legal_status</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($organizations as $value)
        <tr>
            <td>{{ $value->organization_recordid }}</td>
            <td>{{ $value->organization_name }}</td>
            <td>{{ $value->organization_alternate_name }}</td>
            <td>{{ $value->organization_description  }}</td>
            <td>{{ $value->organization_url }}</td>
            <td>{{ $value->organization_email }}</td>
            <td>{{ $value->organization_tax_status }}</td>
            <td>{{ $value->organization_tax_id }}</td>
            <td>{{ $value->organization_year_incorporated }}</td>
            <td>{{ $value->organization_legal_status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
