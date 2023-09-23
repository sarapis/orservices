<table>
    <thead>
        <th>id</th>
        <th>tag</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($organizationTags as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->tag }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
