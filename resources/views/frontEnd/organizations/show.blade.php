@extends('layouts.app')
@section('title')
{{$organization->organization_name}}
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style type="text/css">
    .pagination_right .pagination {
        text-align: right;
        float: right;
        margin-top: 15px;
        margin-bottom: 0px;
    }

    button[data-id="interaction_method"],
    button[data-id="organization_tag"],
    button[data-id="interaction_disposition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    #tagging-div .form-control.bootstrap-select {
        height: auto
    }

    #tagging-div .form-control.bootstrap-select>.dropdown-toggle {
        padding: 13px 12px;
        border-radius: 12px;
    }

    .bootstrap-select .dropdown-menu li.selected,
    .dropdown-item:focus,
    .dropdown-item:hover {
        background: #5051db !important;
    }

    .bootstrap-select .dropdown-menu li.selected a span,
    .dropdown-item:focus span,
    .dropdown-item:hover span {
        color: #fff !important
    }

    .bootstrap-select.show-tick .dropdown-menu .selected span.check-mark {
        top: 12px;
    }
</style>
@section('content')
@include('layouts.filter_organization')
@include('layouts.sidebar_organization')
<div class="breadcume_top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/organizations">Organizations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if($organization->organization_name!='')
                            {{$organization->organization_name}}
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="inner_services">
    <div id="content" class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card detail_services">
                    <div class="card-block">
                        <h4 class="card-title m-0">
                            <a href="">@if($organization->organization_logo_x)
                                <img src="{{$organization->organization_logo_x}}" height="80">@endif
                                {{$organization->organization_name}}
                                @if($organization->organization_alternate_name!='')
                                ({{$organization->organization_alternate_name}})
                                @endif
                                @if ($organization->organization_url)
                                <a href="{{ $organization->organization_url }}" class="m-2" target="_blank" style="font-size: 18px;"><i class="fab fa-globe"></i></a>
                                @endif
                                @if ($organization->facebook_url)
                                <a href="{{ $organization->facebook_url }}" class="m-2" target="_blank" style="font-size: 18px;"><i class="fab fa-facebook"></i></a>
                                @endif
                                @if ($organization->twitter_url)
                                <a href="{{ $organization->twitter_url }}" class="m-2" target="_blank" style="font-size: 18px;"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if ($organization->instagram_url)
                                <a href="{{ $organization->instagram_url }}" class="m-2" target="_blank" style="font-size: 18px;"><i class="fab fa-instagram"></i></a>
                                @endif
                            </a>
                            @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization &&
                            str_contains(Auth::user()->user_organization,
                            $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                            <a href="/organizations/{{$organization->organization_recordid}}/edit" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                            <a href="/organizations/{{$organization->organization_recordid}}/edit" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                        </h4>
                        @if ($organization->organization_status_x)
                        <h4>
                            <span class="subtitle"><b>Status:</b></span>
                            {{$organization->organization_status_x}}
                        </h4>
                        @endif
                        {{-- <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}
                        </h4> --}}
                        @if ($organization->organization_description)
                        <h4 style="line-height:inherit"> {!! nl2br($organization->organization_description) !!}</h4>
                        @endif
                        @if ($organization->organization_url)
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-globe font-size-18 vertical-align-top pr-10 m-0"></i>
                                <a href="{{$organization->organization_url}}"> {{$organization->organization_url}}</a>
                            </span>
                        </h4>
                        @endif
                        @php
                            $phone_number_info = '';
                            $mainPhoneNumber = [];
                            $phone_number_info_array = [];

                            if (isset($organization->phones) && count($organization->phones) > 0) {
                                foreach ($organization->phones as $valueV) {
                                    if ($valueV->main_priority == '1' && count($mainPhoneNumber) == 0) {
                                        $mainPhoneNumber[] = $valueV->phone_number;
                                    }
                                    // else {
                                    //     $phone_number_info_array[] = $valueV->phone_number;
                                    // }
                                }
                                // $servicePhoneData = $service->phone()->where('main_priority', '1')->first();
                            }
                            // $phone_number_info = $mainPhoneNumber . ', ' . implode(',', $phone_number_info_array);
                            $mainPhoneNumber = array_merge($mainPhoneNumber, $phone_number_info_array);
                        @endphp
                        @if($mainPhoneNumber && count($mainPhoneNumber) > 0)
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                {{-- @foreach($organization->phones as $key => $phone)

                                @if($phone->main_priority == '1')
                                <a href="tel:{{$phone->phone_number}}">{{$phone->phone_number}}</a>
                                @endif
                                @endforeach --}}
                                @foreach ($mainPhoneNumber as $key => $item)
                                    <a href="tel:{{$item}}">{{ $key == 0 ? $item : ', '.$item}}</a>
                                @endforeach
                            </span>
                        </h4>
                        @endif
                        @if($organization->organization_email)
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-email font-size-18 vertical-align-top pr-10  m-0"></i>
                                <a href="mailto:{{$organization->organization_email}}">{{$organization->organization_email}}
                                </a>
                            </span>
                        </h4>
                        @endif
                        @if(isset($organization->organization_forms_x_filename))
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Referral
                                    Forms:</b></span>
                            <a href="{{$organization->organization_forms_x_url}}" class="panel-link">
                                {{$organization->organization_forms_x_filename}}
                            </a>
                        </h4>
                        @endif
                    </div>
                </div>
                <ul class="nav nav-tabs tabpanel_above">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#services">
                            <h4 class="card_services_title">Services
                                (
                                {{-- @php
                                    if(count($organization->services) == 0){
                                        $organization_services_count = $organization->getServices->count();
                                        $organization_services = $organization->getServices->count();
                                    }else{
                                        $organization_services = $organization->services;
                                    }
                                @endphp --}}
                                @if(isset($organization->services) && count($organization->services) != 0)
                                {{$organization->services->count()}}
                                @elseif(count($organization->services) == 0)
                                {{ $organization->getServices->count() }}
                                @else
                                0
                                @endif)
                            </h4>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#locations">
                            <h4 class="card_services_title">Locations
                                ({{ $location_info_list ? count($location_info_list) : 0  }})
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization &&
                                str_contains(Auth::user()->user_organization,
                                $organization->organization_recordid) && Auth::user()->roles->name == 'Organization
                                Admin' && isset($service))
                                <a href="/facilities/{{$service->service_locations}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                            </h4>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contacts">
                            <h4 class="card_services_title">Contacts
                                ({{ isset($organization->contact) ? $organization->contact->count() : 0  }})
                            </h4>
                        </a>
                    </li>
                    @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization &&
                    str_contains(Auth::user()->user_organization,$organization->organization_recordid) &&
                    Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles &&
                    Auth::user()->roles->name == 'System Admin')
                    <div style="margin-left: auto;">
                        <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                            <button class="btn btn-primary dropdown-toggle btn-block" type="button"
                                id="dropdownMenuButton-group" data-toggle="dropdown" ria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                                <a href="/service_create/{{$organization->organization_recordid}}"
                                    id="add-new-services">Service</a>
                                <a href="/contact_create/{{$organization->organization_recordid}}"
                                    id="add-new-services">Contact</a>
                                <a href="/facility_create/{{$organization->organization_recordid}}"
                                    id="add-new-services">Location</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </ul>
                <div class="card">
                    <div class="card-block" style="border-radius: 0 0 12px 12px">
                        <div class="tab-content">
                            <div class="tab-pane active" id="services">
                                <!-- Services area design -->
                                @if(isset($organization_services))
                                @foreach($organization_services as $service)
                                @if ((!Auth::user() && $service->access_requirement == 'none') || Auth::user())


                                <div class="organization_services">
                                    <h4 class="card-title">
                                        <a
                                            href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                        @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                        $organization->organization_recordid) && Auth::user()->roles->name =='Organization Admin') || Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                        <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                            @if ($service->access_requirement == 'yes')
                                            <img src="/images/noun_Lock and Key_1043619.png" width="30px" alt="noun_Lock and Key_1043619" style="margin-right: 6px">
                                            @endif
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                        @endif
                                    </h4>
                                    @if ($service->service_description)

                                    <h4 style="line-height: inherit;">{!! Str::limit($service->service_description, 200)
                                        !!}</h4>
                                    @endif
                                    @if ($service->phone && count($service->phone) > 0)
                                    <h4 style="line-height: inherit;">
                                        <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                            @foreach($service->phone as $phone)
                                            <a href="tel:{{$phone->phone_number}}">
                                                {!! $phone->phone_number !!}
                                            </a>
                                            @endforeach
                                        </span>
                                    </h4>
                                    @endif
                                    @if (isset($service->address)&& count($service->address) > 0)
                                    <h4>
                                        <span>
                                            <i class="icon md-pin font-size-18 vertical-align-top pr-10  m-0"></i>
                                            @if(isset($service->address))
                                            @foreach($service->address as $key => $address)
                                            @if($key == 0)
                                            {{ $address->address_1 }} {{ $address->address_2 }}
                                            {{ $address->address_city }}
                                            {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @else
                                            , {{ $address->address_1 }} {{ $address->address_2 }}
                                            {{ $address->address_city }}
                                            {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endif
                                            @endforeach
                                            @endif
                                        </span>
                                    </h4>
                                    @endif

                                    @if($service->service_details!=NULL)
                                    @php
                                    $show_details = [];
                                    @endphp
                                    @foreach($service->details->sortBy('detail_type') as $detail)
                                    @php
                                    for($i = 0; $i < count($show_details); $i ++){
                                        if($show_details[$i]['detail_type']==$detail->
                                        detail_type)
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
                                        <h4><span class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span> {!!
                                            $detail['detail_value'] !!}</h4>
                                        @endforeach
                                        @endif
                                        @if (isset($service->taxonomy) && count($service->taxonomy) > 0)

                                        <h4>
                                            <span class="pl-0 category_badge subtitle"><b>Service Category:</b>
                                                @foreach ($service->taxonomy as $service_taxonomy_info)
                                                @if (isset($service_taxonomy_info->taxonomy_type) &&
                                                count($service_taxonomy_info->taxonomy_type) > 0 &&
                                                $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category')
                                                @if($service->service_taxonomy != null)
                                                <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                                    at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                                    style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                                @endif
                                                @endif
                                                @endforeach
                                            </span>
                                        </h4>
                                        <h4>
                                            <span class="pl-0 category_badge subtitle"><b>Service Eligibility:</b>
                                                @foreach ($service->taxonomy as $service_taxonomy_info)
                                                @if (isset($service_taxonomy_info->taxonomy_type) &&
                                                count($service_taxonomy_info->taxonomy_type) > 0 &&
                                                $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility')
                                                @if($service->service_taxonomy != null)
                                                <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                                    at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                                    style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                                @endif
                                                @endif
                                                @endforeach
                                            </span>
                                        </h4>
                                        @endif
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                            <div class="tab-pane fade" id="locations">
                                <div>
                                    @if($location_info_list)
                                    @foreach($location_info_list as $location)

                                    <div class="location_border">
                                        <h4>
                                            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name ==
                                            'System Admin')
                                            <a href="/facilities/{{$location->location_recordid}}/edit"
                                                class="float-right">
                                                <i class="icon md-edit mr-0"></i>
                                            </a>
                                            @endif
                                            <span><i class="icon fas fa-building font-size-18 vertical-align-top"></i>
                                                <a
                                                    href="/facilities/{{$location->location_recordid}}">{{$location->location_name}}</a>
                                            </span>
                                        </h4>
                                        @if (isset($location->address) && count($location->address) > 0)
                                        <h4>
                                            <span><i class="icon md-pin font-size-18 vertical-align-top"></i>
                                                @if(isset($location->address))
                                                @foreach($location->address as $address)
                                                {{ $address->address_1 }} {{ $address->address_2 }}
                                                {{ $address->address_city }}
                                                {{ $address->address_state_province }}
                                                {{ $address->address_postal_code }}
                                                @endforeach
                                                @endif
                                            </span>
                                        </h4>
                                        @endif
                                        @if (isset($location->phones) && count($location->phones) > 0)

                                        <h4>
                                            <span><i class="icon md-phone font-size-18 vertical-align-top  "></i>
                                                @php
                                                $phones = '';
                                                @endphp
                                                @foreach($location->phones as $k => $phone)
                                                @php
                                                if($k == 0){
                                                $phoneNo = '<a
                                                    href="tel:'.$phone->phone_number.'">'.$phone->phone_number.'</a>';
                                                }else{
                                                $phoneNo = ', '.'<a
                                                    href="tel:'.$phone->phone_number.'">'.$phone->phone_number .'</a>';
                                                }
                                                $phones .= $phoneNo;

                                                @endphp
                                                @endforeach
                                                @if(isset($phones))
                                                {!! rtrim($phones, ',') !!}
                                                @endif
                                            </span>
                                        </h4>
                                        @endif
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contacts">
                                @if(isset($organization->contact))
                                @if ($organization->contact->count() > 0 && ((Auth::user() && Auth::user()->roles &&
                                Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles &&
                                Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                $organization->organization_recordid) && Auth::user()->roles->name == 'Organization
                                Admin')))
                                {{-- <h4 class="card_services_title"> Contacts
                                            (@if(isset($organization->contact)){{$organization->contact->count()}}@else
                                0 @endif)
                                </h4> --}}
                                @foreach($organization->contact as $contact_info)
                                <div class="location_border">
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System
                                    Admin')
                                    <a href="/contacts/{{$contact_info->contact_recordid}}/edit" class="float-right">
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                    @endif
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
                                                    <h4 class="m-0"><span>{{$contact_info->contact_department}}</span>
                                                    </h4>
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
                                            @if($contact_info->phone->count())
                                            <tr>
                                                <td>
                                                    <h4 class="m-0"><span><b>Phones:</b></span> </h4>
                                                </td>
                                                <td>
                                                    <h4 class="m-0"><span>
                                                            @foreach($contact_info->phone as $phone_info)
                                                            <a href="tel:{{ $phone_info->phone_number }}">
                                                                {{$phone_info->phone_number}},
                                                            </a>
                                                            @endforeach
                                                        </span></h4>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="pagination_right text-right">
                        {{ $organization_services->appends(\Request::except('page'))->render() }}
                    </div>
                </div>
                <!-- Services area design -->

                <!-- comment area design -->
                @auth
                <ul class="nav nav-tabs tabpanel_above">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#commnets">
                            <h4 class="card_services_title">Comments</h4>
                        </a>
                    </li>
                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin')
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#interactions">
                                <h4 class="card_services_title">Notes</h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#customdata">
                                <h4 class="card_services_title">Custom Data</h4>
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="card">
                    <div class="card-block" style="border-radius: 0 0 12px 12px">
                        <div class="tab-content">
                            <div class="tab-pane active" id="commnets">
                                @if (Auth::user() && Auth::user()->roles)
                                <div class="comment-body media-body">
                                    @foreach($comment_list as $key => $comment)
                                    <div class="main_commnetbox">
                                        <div class="comment_inner">
                                            <div class="commnet_letter">

                                                {{ $comment->comments_user_firstname[0]  . (isset($comment->comments_user_lastname[0]) ? $comment->comments_user_lastname[0] : $comment->comments_user_firstname[1]) }}
                                            </div>
                                            <div class="comment_author">
                                                <h5>
                                                    <a class="comment-author" href="javascript:void(0)">
                                                        {{$comment->comments_user_firstname}}
                                                        {{$comment->comments_user_lastname}}
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
                                    {{-- <a class="active comment_add" id="reply-btn" href="javascript:void(0)" role="button">Add a comment</a> --}}

                                    {!! Form::open(['route' =>
                                    ['organization_comment',$organization->organization_recordid]]) !!}
                                    <div class="form-group">
                                        <textarea class="form-control" id="reply_content" name="comment" rows="3">
                                              </textarea>
                                        @error('comment')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic"
                                            style="padding:22.5px 54px">Post</button>
                                        {{-- <button type="button" id="close-reply-window-btn" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic">Close</button> --}}
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="interactions">
                                @foreach ($organization->SessionData as $sessionValue)
                                <table class="table table-bordered mb-20 notes_table">
                                    <tbody>
                                        <tr>
                                            <td><span class="subtitle">Timestamp:
                                                </span>{{ $sessionValue->session_performed_at }}</td>
                                            <td><span class="subtitle">User:</span>
                                                {{ $sessionValue->user ? $sessionValue->user->first_name.' '.$sessionValue->user->last_name : '' }}
                                            </td>
                                            <td><span class="subtitle">Method:</span>
                                                {{ $sessionValue->getInteraction ? $sessionValue->getInteraction->interaction_method : '' }}
                                            </td>
                                            <td><span class="subtitle">Disposition:</span>
                                                {{ $sessionValue->getInteraction ? $sessionValue->getInteraction->interaction_disposition : '' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4"><span class="subtitle">Notes:</span>
                                                {{ $sessionValue->getInteraction ? $sessionValue->getInteraction->interaction_notes : '' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @endforeach
                            </div>
                            <div class="tab-pane fade" id="customdata">
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                {{-- @if ($organization->organization_website_rating) --}}
                                <table class="table mb-20 customedata">
                                    <tbody>
                                        <tr>
                                            <th>Key</th>
                                            <th>Value</th>
                                        </tr>
                                        <tr>
                                            <td>Website Rating</td>
                                            <td>{{$organization->organization_website_rating ? $organization->organization_website_rating : '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>

            <div class="col-md-4 property">

                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin')
                <div class="card detail_services">
                    <div class="card-block">
                        <div class="btn-download">
                            {{-- <form method="GET" action="/organizations/{{$organization->organization_recordid}}/tagging"
                            id="organization_tagging"> --}}
                            {!! Form::open(['route' => ['organization_tag',$organization->organization_recordid],
                            'class' => 'm-0']) !!}
                            <div class="row" id="tagging-div">
                                <div class="col-md-10">
                                    {{-- <input type="text" class="form-control" id="tokenfield" name="tokenfield"
                            value="{{$organization->organization_tag}}" autocomplete="off" /> --}}
                                    {{-- <select class="form-control selectpicker" data-live-search="true" id="organization_tag" data-size="5" name="organization_tag" multiple>
                                <option value="">Select Tags</option>
                                @foreach($existing_tags as $key => $tags)
                                    <option value="{{$tags}}"
                                    {{ Str::contains($organization->organization_tag, $tags) ? 'selected' : '' }}>{{$tags}}
                                    </option>
                                    @endforeach
                                    </select> --}}
                                    {{-- {{ dd($organization->organization_tag) }} --}}
                                    {!!
                                    Form::select('organization_tag[]',$allTags,explode(',',$organization->organization_tag),['class'
                                    => 'form-control selectpicker drop_down_create','id' =>
                                    'organization_tag','data-live-search' =>
                                    'true','data-size' => '5','multiple' => 'true']) !!}
                                </div>
                                {{-- <div class="col-md-2 pl-0">
                                    <button type="submit" class="btn btn_darkblack" style="float: right;">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div> --}}
                                <div class="col-md-2 pl-0 btn-download">
                                    <button type="button" class="btn btn_darkblack" data-toggle="modal"
                                        data-target="#interactionModal">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- </form> --}}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Locations area design -->
                <div class="card">
                    <div class="card-block p-0">
                        <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
                        </div>
                    </div>
                </div>
                <!-- Locations area design -->

                <!-- start chage log -->
                @auth
                <div class="card">
                    <div class="card-block">
                        <h4 class="card_services_title mb-20">Change Log</h4>
                        @foreach ($organizationAudits as $item)
                        @if (count($item->new_values) != 0)
                        <div class="py-10" style="float: left; width:100%;border-bottom: 1px solid #dadada;">
                            <p style="color: #000;font-size: 16px; font-weight: 500 !Important;">For Organization :
                                {{ $organization->organization_name }}</p>
                            <p class="mb-5" style="color: #000;font-size: 16px;">On
                                <a href="/viewChanges/{{ $item->id }}/{{ $organization->organization_recordid }}">
                                    <b
                                        style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">{{ $item->created_at }}</b>
                                </a>
                                ,
                                @if ($item->user)
                                <a href="/userEdits/{{ $item->user ? $item->user->id : '' }}"
                                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">
                                    <b
                                        style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name.' '.$item->user->last_name : '' }}</b>
                                </a>
                                @endif
                            </p>
                            {{-- <p><b style="font-family: Neue Haas Grotesk Display Medium; color:#000">User Name</b> - </p> --}}
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
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="interactionModal" tabindex="-1" role="dialog" aria-labelledby="interactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interactionModalLabel">Add Interaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/addInteractionOrganization" method="POST">
                <div class="modal-body all_form_field">
                    {{ csrf_field() }}
                    <div id="facility-create-content" class="sesion_form">
                        <div class="row m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Interaction Method: </label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="interaction_method" name="interaction_method" data-size="5">
                                        @foreach($method_list as $key => $method)
                                        <option value="{{$method}}">{{$method}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="organization_recordid"
                                    value="{{$organization->organization_recordid}}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Interaction Disposition: </label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="interaction_disposition" name="interaction_disposition" data-size="5">
                                        @foreach($disposition_list as $key => $disposition)
                                        <option value="{{$disposition}}">{{$disposition}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label>Records Edited: </label>
                                <input class="form-control selectpicker" type="text" id="interaction_records_edited" name="interaction_records_edited" value="">
                            </div>
                        </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Interaction Notes: </label>
                                    <input class="form-control selectpicker" type="text" id="interaction_notes"
                                        name="interaction_notes" value="">
                                </div>
                            </div>
                            {{-- <div class="col-md-4 mt-25 text-center">
                        </div> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn contactCloseButton waves-effect waves-classic"
                        data-dismiss="modal">Close</button>
                    <button type="submit"
                        class="btn btn-primary btn-lg btn_padding green_btn waves-effect waves-classic"
                        id="save-interaction-btn"> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- organization tag modal --}}
<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"
    id="create_new_organization_tag">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/createNewTag/{{ $organization->id }}" method="post">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close organizationTagCloseButton" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add New Organization Tag</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    style="margin-bottom:5px;font-weight:600;color: #000;letter-spacing: 0.5px;">Tag</label>
                                <input type="text" class="form-control" name="tag" placeholder="tag" id="tag">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn organizationTagCloseButton">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End here --}}


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
    //   showAutocompleteOnFocus: true
      });
    //   $('#tokenfield').on('keypress',function () {
    //     console.log($(this).val)
    //   })

    $('#organization_tag').change(function(){
        let val = $(this).val()
        console.log($.inArray('create_new',val))
        if($.inArray('create_new',val) != -1){
            $('#create_new_organization_tag').modal('show')
        }else{
            let id = "{{ $organization->id }}"
            $.ajax({
            url : '{{ route("addOrganizationTag") }}',
            method : 'get',
            data : {val,id},
            success: function (response) {
                // alert('Tag save successfully!')
            },
            error : function (error) {
                console.log(error)
            }
        })
        }
    })
    $('.organizationTagCloseButton').click(function () {
        let value = $('#organization_tag').val()
        value.pop()
        $('#organization_tag').val(value);
        $('#organization_tag').selectpicker('refresh')
        $('#create_new_organization_tag').modal('hide')
    })
  });

  $(document).ready(function() {
      $('.comment-reply').hide();
      $('#reply_content').val('');
  });

  $(document).ready(function(){

      var locations = <?php print_r(json_encode($locations)) ?>;
      var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
      var maplocation = <?php print_r(json_encode($map)) ?>;
    //   console.log(locations);

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

      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
      });

      var latlongbounds = new google.maps.LatLngBounds();
      var markers = locations.map(function(location, i) {
          var position = {
              lat: location.location_latitude,
              lng: location.location_longitude
          }
          var latlong = new google.maps.LatLng(position.lat, position.lng);
          latlongbounds.extend(latlong);

           var content = '<div id="iw-container">';
                   for(i = 0; i < location.services.length; i ++){
                            content +=  '<div class="iw-title"> <a href="/services/'+location.services[i].service_recordid+'">'+location.services[i].service_name+'</a></div>';
                        }
                        // '<div class="iw-title"> <a href="/services/'+ location.service_recordid +'">' + location.service_name + '</a> </div>' +

                        content += '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + location.organization_recordid + '">' + location.organization_name +'</a>';
                            // '<div class="iw-subTitle">Address</div>' +
                            // location.address_name ;
                            // '<br> <a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address_name + '" target="_blank">View on Google Maps</a>'+

                        if(location.address){
                            for(i = 0; i < location.address.length; i ++){
                                content +=  '<div class="iw-subTitle">Address</div>'+
                                        location.address[i].address_1+ ', '+location.address[i].address_city+','+location.address[i].address_state_province+','+location.address[i].address_postal_code ;
                                content += '<div><a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address[i].address_1+ ', '+location.address[i].address_city+','+location.address[i].address_state_province+','+location.address[i].address_postal_code + '" target="_blank">View on Google Maps</a></div>';
                            }
                        }
                        content += '</div><div class="iw-bottom-gradient"></div>' +
                        '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: content
            });

          var marker = new google.maps.Marker({
              position: position,
              map: map,
              title: location.location_name,
          });
          marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
          return marker;
      });

      if (locations.length > 1) {
          map.fitBounds(latlongbounds);
      }

  });

  $(document).ready(function() {
    var showChar = 250;
    var ellipsestext = "...";
    var moretext = "More";
    var lesstext = "Less";
    $('.more').each(function() {
      var content = $(this).html();

      if(content.length > showChar) {

        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);

        var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

        $(this).html(html);
      }

    });

    $(".morelink").click(function(){
      if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
      } else {
        $(this).addClass("less");
        $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });

    $('.panel-link').on('click', function(e){
          if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
              return;
          var id = $(this).attr('at');
        //   console.log(id);
          $("#category_" +  id).prop( "checked", true );
          $("#checked_" +  id).prop( "checked", true );
          $("#filter").submit();
      });

      $('.panel-link.target-population-link').on('click', function(e){
          $("#target_all").val("all");
          $("#filter").submit();
      });

      $('.panel-link.target-population-child').on('click', function(e){
          var id = $(this).attr('at');
          $("#target_multiple").val(id);
          $("#filter").submit();

      });
  });


  $("#reply-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').show();
  });
  $("#close-reply-window-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').hide();
  });

</script>
@endsection
