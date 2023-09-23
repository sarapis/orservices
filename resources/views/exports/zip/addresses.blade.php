<table>
    <thead>
        <th>id</th>
        <th>location_id</th>
        <th>attention</th>
        <th>address_1</th>
        <th>address_2</th>
        <th>address_3</th>
        <th>address_4</th>
        <th>city</th>
        <th>region</th>
        <th>state_province</th>
        <th>postal_code</th>
        <th>country</th>
        <th>address_services</th>
        <th>address_organization</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($addresses as $row)
            <tr>
                <td>{{ $row->address_recordid }}</td>
                <td>{{ $row->address_locations }}</td>
                <td>{{ $row->address_attention }}</td>
                <td>{{ $row->address_1 }}</td>
                <td>{{ $row->address_2 }}</td>
                <td></td>
                <td></td>
                <td>{{ $row->address_city }}</td>
                <td>{{ $row->address_region }}</td>
                <td>{{ $row->address_state_province }}</td>
                <td>{{ $row->address_postal_code }}</td>
                <td>{{ $row->address_country }}</td>
                <td>{{ $row->address_services }}</td>
                <td>{{ $row->address_organization }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
