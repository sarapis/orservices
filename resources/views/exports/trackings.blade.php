<table>
    <thead>
        <th>id</th>
        <th>name</th>
        <th>Organization Tags</th>
        <th>Organization Status</th>
        <th>Service Tags</th>
        <th>Date Last Verified</th>
        <th>User Verified</th>
        <th>Last Note Date</th>
        <th>Last Updated Date</th>
    </thead>
    <tbody>
        <tr></tr>
        @foreach ($organizations as $row)
        <tr>
            <td>{{ $row->organization_recordid }}</td>
            <td>{{ $row->organization_name }}</td>
            <td>
                @php
                    $organization_tag = [];
                    if ($row->organization_tag) {
                        $organization_tags = explode(',', $row->organization_tag);
                        foreach ($organization_tags as $key => $value) {
                            $tag = \App\Model\OrganizationTag::whereId($value)->first();
                            if ($tag) {
                                $organization_tag[] = $tag->tag;
                            }
                        }
                    }
                @endphp
                {{ implode(',',$organization_tag) }}
            </td>
            <td>
                @php
                    $organization_status = [];
                    if ($row->organization_status_x) {
                        $organization_statuses = explode(',', $row->organization_status_x);
                        foreach ($organization_statuses as $key => $value) {
                            $status = \App\Model\OrganizationStatus::whereId($value)->first();
                            if ($status) {
                                $organization_status[] = $status->status;
                            }
                        }
                    }
                @endphp
                {{ implode(',', $organization_status)  }}
            </td>
            <td>
                @php
                    $tags = [];
                    if (isset($row->services)) {
                        $services = $row->services;
                        foreach ($services as $key => $value) {
                            if ($value->service_tag) {
                                $tagsArray = explode(',', $value->service_tag);
                                foreach ($tagsArray as $key => $value1) {
                                    $service_tag = \App\Model\ServiceTag::whereId($value1)->first();
                                    if ($service_tag) {
                                        $tags[] = $service_tag->tag;
                                    }
                                }
                            }
                        }
                    }
                @endphp
                {{ count($tags) > 0 ? implode(',', $tags) : '' }}
            </td>
            <td>{{ $row->last_verified_at }}</td>
            <td>{{ $row->get_last_verified_by ? $row->get_last_verified_by->first_name . ' ' . $row->get_last_verified_by->last_name : '' }}</td>
            <td>
                @php
                    $audit = \OwenIt\Auditing\Models\Audit::where('auditable_id', $row->organization_recordid)->orderBy('id', 'desc')->first();

                    $last_note_date = $audit ?  $audit->created_at : "";
                @endphp
                {{ $last_note_date }}</td>
            <td>{{ $row->updated_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
