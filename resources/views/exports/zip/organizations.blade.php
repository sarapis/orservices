<table>
    <thead>
        <th>id</th>
        <th>name</th>
        <th>alternate_name</th>
        <th>description</th>
        <th>email</th>
        <th>url</th>
        <th>tax_status</th>
        <th>tax_id</th>
        <th>year_incorporated</th>
        <th>legal_status</th>
        <th>organization_tag</th>
        <th>parent_organization</th>
        <th>organization_services</th>
        <th>organization_phones</th>
        <th>organization_locations</th>
        <th>organization_contact</th>
        <th>organization_details</th>
        <th>organization_code</th>
        <th>organization_website_rating</th>
        <th>facebook_url</th>
        <th>twitter_url</th>
        <th>instagram_url</th>
        <th>logo</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($organizations as $row)
            <tr>
                <td>{{ $row->organization_recordid }}</td>
                <td>{{ $row->organization_name }}</td>
                <td>{{ $row->organization_alternate_name }}</td>
                <td>{{ $row->organization_description }}</td>
                <td>{{ $row->organization_email }}</td>
                <td>{{ $row->organization_url }}</td>
                <td>{{ $row->organization_tax_status }}</td>
                <td>{{ $row->organization_tax_id }}</td>
                <td>{{ $row->organization_year_incorporated }}</td>
                <td>{{ $row->organization_legal_status }}</td>
                <td>{{ $row->organization_tag }}</td>
                <td>{{ $row->parent_organization }}</td>
                <td>{{ $row->organization_services }}</td>
                <td>{{ $row->organization_phones }}</td>
                <td>{{ $row->organization_locations }}</td>
                <td>{{ $row->organization_contact }}</td>
                <td>{{ $row->organization_details }}</td>
                <td>{{ $row->organization_code }}</td>
                <td>{{ $row->organization_website_rating }}</td>
                <td>{{ $row->facebook_url }}</td>
                <td>{{ $row->twitter_url }}</td>
                <td>{{ $row->instagram_url }}</td>
                <td>{{ $row->logo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
