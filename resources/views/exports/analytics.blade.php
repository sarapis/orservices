<table class="table table-striped jambo_table bulk_action" id="tblroles">
    <thead>
        <tr>
            <th>Month</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($additional_analytics as $value)
            <tr>
                <td>{{ $value['name'] }}</td>
                <td>{{ $value['Jan'] }}</td>
                <td>{{ $value['Feb'] }}</td>
                <td>{{ $value['Mar'] }}</td>
                <td>{{ $value['Apr'] }}</td>
                <td>{{ $value['May'] }}</td>
                <td>{{ $value['Jun'] }}</td>
                <td>{{ $value['Jul'] }}</td>
                <td>{{ $value['Aug'] }}</td>
                <td>{{ $value['Sep'] }}</td>
                <td>{{ $value['Oct'] }}</td>
                <td>{{ $value['Nov'] }}</td>
                <td>{{ $value['Dec'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
