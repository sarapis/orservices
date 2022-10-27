<table>
    <thead>
        <th>Location Name</th>
        <th>Organization</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Contacts</th>
        <th>Services</th>

    </thead>
    <tbody>
        <tr></tr>
        @foreach ($locations as $item)
        <tr>
            <td>{{ $item->location_name }}</td>
            <td>{{ $item->organization ? $item->organization->organization_name : ''   }}</td>
            <td>{{ $item->location_latitude }}</td>
            <td>{{ $item->location_longitude }}</td>
            <td>{{ isset($item->address[0]) ? $item->address[0]['address_1'] : '' }}</td>
            <td>{{ isset($item->address[0]) ? $item->address[0]['address_city'] : '' }}</td>
            <td>{{ isset($item->address[0]) ? $item->address[0]['address_state_province'] : '' }}</td>
            <td>
                @php

                    $link = '';
                    if ($item->organization && $item->organization->contact) {
                        foreach ($item->organization->contact as $key => $value) {
                            if($key == 0){
                                $link .= $value->contact_name;
                            }else{
                                $link .=  ' ; '.$value->contact_name;
                            }
                        }
                    }
                @endphp
                {{ $link }}
            </td>
            <td>
                @php
                    $link = '';
                    if ($item->services ) {
                        foreach ($item->services as $key => $value) {
                            if($key == 0){
                                $link .= $value->service_name;
                            }else{
                                $link .= ' ; '. $value->service_name ;
                            }
                        }
                    }
                @endphp
                {{ $link }}
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
