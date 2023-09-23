<table>
    <thead>
        <th>id</th>
        <th>service_id</th>
        <th>taxonomy_term_id</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($service_attributes as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->service_recordid }}</td>
                <td>{{ $row->taxonomy_recordid }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
