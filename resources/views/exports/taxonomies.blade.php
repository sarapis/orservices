
<table>
    <thead>
        <th>id</th>
        <th>term</th>
        <th>description</th>
        <th>parent_id</th>
        <th>taxonomy</th>
        <th>language</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($taxonomies as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->taxonomy_name  }}</td>
            <td>{{ $value->taxonomy_x_description }}</td>
            <td>{{ $value->parent->taxonomy_name ?? '' }}</td>
            <td>{{ $value->taxonomy_type && count($value->taxonomy_type) > 0 ? $value->taxonomy_type[0]->name : ''  }}</td>
            <td>{{ $value->language }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
