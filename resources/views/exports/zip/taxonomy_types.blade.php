<table>
    <thead>
        <th>taxonomy_type_recordid</th>
        <th>name</th>
        <th>type</th>
        <th>order</th>
        <th>reference_url</th>
        <th>notes</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($taxonomy_types as $row)
            <tr>
                <td>{{ $row->taxonomy_type_recordid }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->type }}</td>
                <td>{{ $row->order }}</td>
                <td>{{ $row->reference_url }}</td>
                <td>{{ $row->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
