@extends('layouts.app')
@section('title')
Location
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card detail_services">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="">{{$facility->location_name}} </a>
                            @if ((Auth::user() && Auth::user()->user_organization && $facility->organization &&
                            str_contains(Auth::user()->user_organization,$facility->organization->organization_recordid)
                            && ((Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin'))) || Auth::user() &&
                            Auth::user()->roles->name == 'System Admin' || Auth::user() && ((Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin') || Auth::user()->roles->name != 'Section Admin'))
                            <a href="/facilities/{{$facility->location_recordid}}/edit" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                            @if (count($inactiveOrganizationIds) > 0)
                            <span class="badge badge-danger float-right m-2" style="color:#fff;">Inactive</span>
                            @endif
                        </h4>
                        @if (isset($facility_organizations) && count($facility_organizations) > 0)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Organization: </b></span>
                            @foreach($facility_organizations as $organization)
                            <a class="panel-link"
                                href="/organizations/{{$organization->organization_recordid}}">{{$organization->organization_name}}</a>
                            <br>
                            @endforeach
                        </div>
                        @endif
                        {{-- @if(isset($facility->address) && count($facility->address) > 0)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Address: </b></span>
                            @if(isset($facility->address))
                            @foreach($facility->address as $address)
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }} {{ $address->address_region }} {{ $address->address_country }}"
                                target="_blank">{{ $address->address_1 }} {{ $address->address_2 }}
                                {{ $address->address_city }} {{ $address->address_state_province }}
                                {{ $address->address_postal_code }} {{ $address->address_region }}
                                {{ $address->address_country }}</a>

                            @endforeach
                            @endif
                        </div>
                        @endif --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="">
                                        <table class="display dataTable table-striped jambo_table table-bordered table-responsive">
                                            <thead>
                                                <th>Full Address</th>
                                                <th>Address Type</th>
                                                <th>Primary </th>
                                            </thead>
                                            <tbody>
                                                @foreach ($locationAddresses as $key => $value)
                                                <tr>
                                                    <td>
                                                        {{ $value->full_address }}
                                                    </td>
                                                    <td>{{ $value->address_type_data }}</td>
                                                    <td>
                                                        {{ $value->is_main == 1 ? 'Primary' : '' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($facility->regions) && count($facility->regions) > 0)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Region: </b></span>
                            @foreach($facility->regions as $key => $region)
                            {{ $key > 0 ? ',' : '' }}
                            {{ $region->region }}
                            @endforeach
                        </div>
                        @endif
                        @if(isset($facility->phones) && count($facility->phones) > 0)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Phones: </b></span>
                            @foreach($facility->phones as $key => $phone)
                            <a href="tel:{{$phone->phone_number}}">{{$phone->phone_number}}</a>
                            {{ count($facility->phones) > $key+1 ? ',' : '' }}
                            @endforeach
                        </div>
                        @endif
                        <div class="tagp_class">
                            <span class="subtitle"><b>Accessibility: </b></span>
                            {{ $facility->get_accessibility->accessibility ?? '' }}
                        </div>
                        <div class="tagp_class">
                            <span class="subtitle"><b>Accessibility Details: </b></span>
                            {{$facility->accessibility_details}}
                        </div>

                        @if($facility->location_alternate_name)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Alternative Name: </b></span>
                            {{$facility->location_alternate_name}}
                        </div>
                        @endif
                        @if($facility->location_description)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Description: </b></span>
                            {{$facility->location_description}}
                        </div>
                        @endif
                        @if($facility->location_transportation)
                        <div class="tagp_class">
                            <span class="subtitle"><b>Transportation: </b></span>
                            {{$facility->location_transportation}}
                        </div>
                        @endif

                        @if($locationDetails)
                        @php
                        $show_details = [];
                        @endphp
                            @foreach($locationDetails as $detail)
                                @php
                                    for($i = 0; $i < count($show_details); $i ++){
                                        if($show_details[$i]['detail_type']==$detail->detail_type)
                                        break;
                                    }
                                    if($i == count($show_details)){
                                        $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=>
                                        $detail->detail_value);
                                    }else{
                                        $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].',
                                        '.$detail->detail_value;
                                    }
                                @endphp
                            @endforeach
                            @foreach($show_details as $detail)
                                <div class="tagp_class">
                                    <span class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span> {!!
                                    $detail['detail_value'] !!}
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>

                <!-- Services area design -->
                @if(isset($facility->services))
                @if($facility->services->count() > 0)
                <div class="card">
                    <div class="card-block ">
                        <h4 class="card_services_title">Services
                            (@if(isset($facility->services)){{$facility->services->count()}}@else 0 @endif)
                        </h4>
                        @foreach($facility->services as $service)
                        <div class="organization_services">
                            <h4 class="card-title">
                                <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                            </h4>
                            <div class="tagp_class">{!! Str::limit($service->service_description, 200) !!}</div>
                            @if ($service->phone && count($service->phone) > 0)
                            <div class="tagp_class">
                                <span>
                                    <i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                    @foreach($service->phone as $key => $phone)
                                    <p>
                                        {{-- <a href="tel:{{$phone->phone_number}}">{{ $phone->phone_number}}</a> --}}
                                        @php
                                            $otherData = '<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number.'</a>';
                                            $otherData .= $phone->phone_extension ? '&nbsp;&nbsp;ext. '. $phone->phone_extension : '';
                                            $otherData .=  $phone->type ? '&nbsp;('.$phone->type->type.')' : '';
                                            $otherData .=  $phone->phone_language ? ' '.$phone->phone_language : '';
                                            $otherData .=  $loop->last ? '' : ';';
                                        @endphp
                                        {!! $otherData !!}
                                        {{-- &nbsp;&nbsp;{{ $phone->phone_extension ? 'ext. '. $phone->phone_extension : '' }}&nbsp;{{ $phone->type ? '('.$phone->type->type.')' : '' }}
                                        @if ($phone->phone_language)
                                        {{ $phone->phone_language }}
                                        @endif
                                        {{ $phone->phone_description ? '- '.$phone->phone_description : '' }}
                                        @if(count($service->phone) > ($key + 1))
                                        ;
                                        @endif --}}
                                    </p>
                                        @endforeach
                                    </span>
                            </div>
                            @endif
                            <div class="tagp_class">
                                <span><i class="icon md-pin font-size-18 vertical-align-top pr-10  m-0"></i>
                                    @if(isset($service->locations) && $service->locations != null)
                                    @foreach($service->locations as $location)
                                    @if(isset($location->address))
                                    @if($location->address != null)
                                    @foreach($location->address as $address)
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $address->address_1 .' '. $address->address_2 .' '. $address->address_city .' '. $address->address_state_province .' '. $address->address_postal_code }}"
                                        target="_blank">
                                        {{ $address->address_1 .' '. $address->address_2 .' '. $address->address_city .' '. $address->address_state_province .' '. $address->address_postal_code }}
                                    </a>
                                    @endforeach
                                    @endif
                                    @endif
                                    {{-- <a href="https://www.google.com/maps/dir/?api=1&destination={{ $address->location_name  }}"
                                    target="_blank">
                                    {{ $address->location_name }}
                                    </a> --}}
                                    {{-- <a href="https://www.google.com/maps/dir/?api=1&destination={{ $address->address_1 .' '. $address->address_2 .' '. $address->address_city .' '. $address->address_state_province .' '. $address->address_postal_code }}"
                                    target="_blank">
                                    {{ $address->address_1 .' '. $address->address_2 .' '. $address->address_city .' '. $address->address_state_province .' '. $address->address_postal_code }}
                                    </a> --}}

                                    @endforeach
                                    @endif
                                </span>
                            </div>
                            @if($service->service_details != NULL)
                            @php
                            $show_details = [];
                            @endphp
                            @foreach($service->details->sortBy('detail_type') as $detail)
                            @php
                            for($i = 0; $i < count($show_details); $i ++){
                                if($show_details[$i]['detail_type']==$detail->detail_type)
                                break;
                                }
                                if($i == count($show_details)){
                                $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=>
                                $detail->detail_value);
                                }
                                else{
                                $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].',
                                '.$detail->detail_value;
                                }
                                @endphp
                                @endforeach
                                @foreach($show_details as $detail)
                                <div class="tagp_class">
                                    <span class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span> {!!
                                    $detail['detail_value'] !!}
                                </div>
                                @endforeach
                                @endif
                                @isset($service->taxonomy)
                                @if (count($service->taxonomy) > 0)
                                @php
                                    $i = 0;
                                    $j = 0;
                                @endphp
                                <div class="tagp_class">
                                    <span class="pl-0 category_badge subtitle">
                                        @foreach ($service->taxonomy as $service_taxonomy_info)
                                        @if (isset($service_taxonomy_info->taxonomy_type) &&
                                        count($service_taxonomy_info->taxonomy_type) > 0 &&
                                        $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category')
                                        @if($service->service_taxonomy != null)
                                        @if ($i == 0)
                                        <b>Service Category:</b>
                                        @php
                                            $i ++;
                                        @endphp
                                        @endif
                                        <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                            at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                            style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                        @endif
                                        @endif
                                        @endforeach
                                    </span>
                                </div>
                                <div class="tagp_class">
                                    <span class="pl-0 category_badge subtitle">
                                        @foreach ($service->taxonomy as $service_taxonomy_info)
                                        @if (isset($service_taxonomy_info->taxonomy_type) &&
                                        count($service_taxonomy_info->taxonomy_type) > 0 &&
                                        $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility')
                                        @if($service->service_taxonomy != null)
                                        @if ($j == 0)
                                        <b>Service Eligibility:</b>
                                        @php
                                            $j ++;
                                        @endphp
                                        @endif
                                        <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                            at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                            style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                        @endif
                                        @endif
                                        @endforeach
                                    </span>
                                </div>
                                @endif
                                @endisset
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
                <!-- Services area design -->

                <!-- commnet area design-->
                {{-- @if (Auth::user())
                <div class="card">
                    <div class="card-block">
                        <h4 class="card_services_title">Comments</h4>
                        <div class="comment-body media-body pt-30">
                            @foreach($comment_list as $key => $comment)
                                <div class="main_commnetbox">
                                    <div class="comment_inner">
                                        <div class="commnet_letter">
                                            {{ $comment->comments_user_firstname[0]  . (isset($comment->comments_user_lastname[0]) ? $comment->comments_user_lastname[0] : $comment->comments_user_firstname[1]) }}
            </div>
            <div class="comment_author">
                <h5>
                    <a class="comment-author" href="javascript:void(0)">
                        {{$comment->comments_user_firstname}} {{$comment->comments_user_lastname}}
                    </a>
                </h5>
                <p class="date">{{$comment->comments_datetime}}</p>
            </div>
        </div>
        <div class="commnet_content">
            <p>{{$comment->comments_content}}</p>
        </div>
    </div>
    @endforeach

    <a class="active comment_add" id="reply-btn" href="javascript:void(0)" role="button">Add a comment</a>


    {!! Form::open(['route' => ['location_comment',$facility->location_recordid],'class' => 'comment-reply']) !!}
    {{ csrf_field() }}
    <div class="form-group">
        <label class="d-none">comments </label>
        <textarea class="form-control" id="reply_content" name="reply_content" rows="3">
                                    </textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic"
            style="padding:22.5px 54px">Post</button>
        <button type="button" id="close-reply-window-btn"
            class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic">Close</button>
    </div>
    {!! Form::close() !!}
</div>
</div>
</div>
@endif --}}
<!-- commnet area design -->
</div>

<div class="col-md-4 property">
    @if ((Auth::user() && Auth::user()->user_organization && $facility->organization &&
    str_contains(Auth::user()->user_organization,$facility->organization->organization_recordid) &&
    ((Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin'))) || Auth::user() && Auth::user()->roles->name == 'System Admin' ||
    Auth::user() && ((Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin')))
    <div style="display: flex;" class="mb-20">
        <div class="dropdown add_new_btn" style="width: 100%; float: right;">
            <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-plus"></i> Add New
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                <a href="/service_create/{{$facility->location_recordid}}/facility" id="add-new-services">Add New
                    Service</a>
                <a href="/contact_create/{{$facility->location_recordid}}/facility" id="add-new-services">Add New
                    Contact</a>
            </div>
        </div>
    </div>
    @endif
    {{-- <div class="pt-10 pb-10 pl-0 btn-download">
                    {!! Form::open(['route' => ['location_tag',$facility->location_recordid]]) !!}
                    <div class="row" id="tagging-div">
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="tokenfield" name="tokenfield" value="{{$facility->location_tag}}"
    />
</div>
<div class="col-md-3">
    <button type="submit" class="btn btn_darkblack" style="float: right;">
        <i class="fas fa-save"></i>
    </button>
</div>
</div>
{!! Form::close() !!}
</div> --}}


<!-- Locations area design -->
<div class="card">
    <div class="card-block p-0">
        <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
        </div>
    </div>
</div>
<!-- Locations area design -->

{{-- <div class="card">
                    <div class="card-block">
                        <div class="comment-body media-body">
                            @if(isset($facility->address))
                                <h4>
                                    <span class="subtitle"><b>Address: </b></span>
                                    @if(isset($facility->address))
                                        @foreach($facility->address as $address)
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $address->address_1 }}
{{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }}
{{ $address->address_postal_code }} {{ $address->address_region }} {{ $address->address_country }}"
target="_blank">{{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
{{ $address->address_state_province }} {{ $address->address_postal_code }} {{ $address->address_region }}
{{ $address->address_country }}</a>

@endforeach
@endif
</h4>
@endif
@if(isset($facility->phones))
<h4>
    <span class="subtitle"><b>Phones: </b></span>
    @foreach($facility->phones as $key => $phone)
    <a href="tel:{{$phone->phone_number}}">{{$phone->phone_number}}</a>
    {{ count($facility->phones) > $key+1 ? ',' : '' }}
    @endforeach
</h4>
@endif
</div>
</div>
</div> --}}

<!-- Contact area design -->
@if($facility->organization)
@if($facility->organization->contact->count() > 0)
<div class="card page-project">
    <div class="card-block">
        <h4 class="card_services_title"> Contacts
            {{-- (@if(isset($facility->organization->contact)){{$facility->organization->contact->count()}}@else 0
            @endif) --}}
        </h4>
        @foreach($facility->organization->contact as $contact_info)
        <div class="location_border p-0">
            <table class="table ">
                <tbody>
                    @if($contact_info->contact_name)
                    <tr>
                        <td>
                            <h4 class="m-0"><span><b>Name:</b></span> </h4>
                        </td>
                        <td>
                            <h4 class="m-0"><a
                                    href="/contacts/{{$contact_info->contact_recordid}}">{{$contact_info->contact_name}}</a>
                            </h4>
                        </td>
                    </tr>
                    @endif
                    @if($contact_info->contact_title)
                    <tr>
                        <td>
                            <h4 class="m-0"><span><b>Title:</b></span> </h4>
                        </td>
                        <td>
                            <h4 class="m-0"><span>{{$contact_info->contact_title}}</span></h4>
                        </td>
                    </tr>
                    @endif
                    @if($contact_info->contact_department)
                    <tr>
                        <td>
                            <h4 class="m-0"><span><b>Department:</b></span> </h4>
                        </td>
                        <td>
                            <h4 class="m-0"><span>{{$contact_info->contact_department}}</span></h4>
                        </td>
                    </tr>
                    @endif
                    @if($contact_info->contact_email)
                    <tr>
                        <td>
                            <h4 class="m-0"><span><b>Email:</b></span> </h4>
                        </td>
                        <td>
                            <h4 class="m-0"><span>{{$contact_info->contact_email}}</span></h4>
                        </td>
                    </tr>
                    @endif
                    @if($contact_info->contact_phones)
                    @if(isset($contact_info->phone->phone_number))
                    <tr>
                        <td>
                            <h4 class="m-0"><span><b>Phones:</b></span> </h4>
                        </td>
                        <td>
                            <h4 class="m-0"><span>{{$contact_info->phone->phone_number}}</span></h4>
                        </td>
                    </tr>
                    @endif
                    @endif
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div>
@endif
@endif
<!-- Contact area design -->
<div class="card all_form_field ">
    <div class="card-block">
        <h4 class="card_services_title mb-20">Change Log</h4>
        @foreach ($facilityAudits as $item)
        @if (count($item->new_values) != 0)
        <div class="py-10" style="float: left; width:100%;border-bottom: 1px solid #dadada;">
            <p class="mb-5" style="color: #000;font-size: 16px;">On
                <b
                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">{{ $item->created_at }}</b>
                ,
                <b
                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name.' '.$item->user->last_name : '' }}</b>
            </p>
            @foreach ($item->old_values as $key => $v)
                @php
                $fieldNameArray = explode('_',$key);
                $fieldName = implode(' ',$fieldNameArray);
                $new_values = explode('| ',$item->new_values[$key]);
                $old_values = explode('| ',$v);
                $old_values = array_values(array_filter($old_values));
                $new_values = array_values(array_filter($new_values));
                @endphp
                <ul style="padding-left: 0px;font-size: 16px;">
                    @if($v && count($old_values) > count($new_values))

                    @php
                        $diffData = array_diff($old_values,$new_values);
                    @endphp
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Removed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> <span style="color: #FF5044">{{ implode(',',$diffData) }}</span>
                    </li>
                    @elseif($v && count($old_values) < count($new_values))
                    @php
                        $diffData = array_diff($new_values,$old_values);
                    @endphp
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> <span style="color: #35AD8B">{{ implode(',',$diffData) }}</span>
                    </li>
                    @elseif($v && count($new_values) == count($old_values))
                    @php
                        $diffData = array_diff($new_values,$old_values);
                    @endphp
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> <span style="color: #35AD8B">{{ implode(',',$diffData) }}</span>
                    </li>
                    {{-- <li style="color: #000;list-style: disc;list-style-position: inside;">Changed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> from <span style="color: #FF5044">{{ $v }}</span> to <span style="color: #35AD8B">{{ $new_values ? $new_values : 'none' }}</span>
                    </li> --}}
                    @elseif($item->new_values[$key])
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Added <b
                            style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                        <span style="color: #35AD8B">{{ $item->new_values[$key] }}</span>
                    </li>
                    @endif
                </ul>
                @endforeach
            <span><a href="/viewChanges/{{ $item->id }}/{{ $facility->location_recordid }}"
                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">View
                    Changes</a></span>
        </div>
        @endif
        @endforeach
    </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>
    var tag_source = <?php print_r(json_encode($existing_tags)) ?>;

    $(document).ready(function() {
        $('#tokenfield').tokenfield({
            autocomplete: {
                source: tag_source,
                delay: 100
            },
            showAutocompleteOnFocus: true
        });
    });

    $(document).ready(function() {
        $('.comment-reply').hide();
        $('#reply_content').val('');
    });
    $("#reply-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').show();
    });
    $("#close-reply-window-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').hide();
    });

    $(document).ready(function(){
        setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;
        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom_profile;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 12;
        }

        latitude = locations[0].location_latitude;
        longitude = locations[0].location_longitude;

        if(latitude == null){
            latitude = avglat;
            longitude = avglng;
        }


        var mymap = new GMaps({
            el: '#map',
            lat: latitude,
            lng: longitude,
            zoom: zoom
        });

        $.each( locations, function(index, value ){

                var content = '<div id="iw-container">';
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<div class="iw-title"> <a href="/services/'+value.services[i].service_recordid+'">'+value.services[i].service_name+'</a></div>';
                }
                if(value.organization){

                content += '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + value.organization.organization_recordid + '">' + value.organization.organization_name +'</a>';
                }
                for(i = 0; i < value.address.length; i ++){
                    content +=  '<div class="iw-subTitle">Address</div>'  + value.address[i].address_1+ ', '+value.address[i].address_city+','+value.address[i].address_state_province+','+value.address[i].address_postal_code;
                    content += '<div><a href="https://www.google.com/maps/dir/?api=1&destination=' + value.address[i].address_1+ ', '+value.address[i].address_city+','+value.address[i].address_state_province+','+value.address[i].address_postal_code + '" target="_blank">View on Google Maps</a></div>'
                }
                content += '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
                        '</div>';

                if(value.location_latitude){
                    mymap.addMarker({

                        lat: value.location_latitude,
                        lng: value.location_longitude,
                        title: value.city,

                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        }
                    });
                }
            });
        }, 2000)
    });

</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap" async
    defer>
</script> -->

@endsection
