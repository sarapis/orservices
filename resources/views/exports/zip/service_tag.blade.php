<table>
    <thead>
        <th>id</th>
        <th>tag</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($serviceTags as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->tag }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
