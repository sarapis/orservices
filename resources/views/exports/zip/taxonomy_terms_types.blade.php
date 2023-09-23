<table>
    <thead>
        <th>taxonomy_recordid</th>
        <th>taxonomy_type_recordid</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($taxonomy_terms_types as $row)
            <tr>
                <td>{{ $row->taxonomy_recordid }}</td>
                <td>{{ $row->taxonomy_type_recordid }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
