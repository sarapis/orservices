<table>
    <thead>
        <th>taxonomy_recordid</th>
        <th>taxonomy_name</th>
        <th>taxonomy_parent_name</th>
        <th>exclude_vocabulary</th>
        <th>taxonomy</th>
        <th>category_logo</th>
        <th>category_logo_white</th>
        <th>taxonomy_grandparent_name</th>
        <th>taxonomy_vocabulary</th>
        <th>taxonomy_x_description</th>
        <th>taxonomy_x_notes</th>
        <th>language</th>
        <th>taxonomy_services</th>
        <th>taxonomy_parent_recordid</th>
        <th>taxonomy_facet</th>
        <th>category_id</th>
        <th>taxonomy_id</th>
        <th>order</th>
        <th>badge_color</th>
        <th>flag</th>
        <th>status</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($taxonomy_terms as $row)
            <tr>
                <td>{{ $row->taxonomy_recordid }}</td>
                <td>{{ $row->taxonomy_name }}</td>
                <td>{{ $row->taxonomy_parent_name }}</td>
                <td>{{ $row->exclude_vocabulary }}</td>
                <td>{{ $row->taxonomy }}</td>
                <td>{{ $row->category_logo }}</td>
                <td>{{ $row->category_logo_white }}</td>
                <td>{{ $row->taxonomy_grandparent_name }}</td>
                <td>{{ $row->taxonomy_vocabulary }}</td>
                <td>{{ $row->taxonomy_x_description }}</td>
                <td>{{ $row->taxonomy_x_notes }}</td>
                <td>{{ $row->language }}</td>
                <td>{{ $row->taxonomy_services }}</td>
                <td>{{ $row->taxonomy_parent_recordid }}</td>
                <td>{{ $row->taxonomy_facet }}</td>
                <td>{{ $row->category_id }}</td>
                <td>{{ $row->taxonomy_id }}</td>
                <td>{{ $row->order }}</td>
                <td>{{ $row->badge_color }}</td>
                <td>{{ $row->flag }}</td>
                <td>{{ $row->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
