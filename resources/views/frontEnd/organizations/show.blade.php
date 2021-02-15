@extends('layouts.app')
@section('title')
{{$organization->organization_name}}
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style type="text/css">
    .pagination_right .pagination{
        text-align: right;
        float: right;
        margin-top: 15px;
        margin-bottom: 0px;
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
                            </a>
                            @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
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
                        <h4>
                            <span class="subtitle"><b>Status:</b></span>
                            {{$organization->organization_status_x}}
                        </h4>
                        {{-- <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}
                        </h4> --}}
                        <h4 style="line-height:inherit"> {!! nl2br($organization->organization_description) !!}</h4>
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-globe font-size-18 vertical-align-top pr-10 m-0"></i>
                                <a href="{{$organization->organization_url}}"> {{$organization->organization_url}}</a>
                            </span>
                        </h4>
                        @if($organization->phones)
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                @foreach($organization->phones as $key => $phone)
                                @if ($phone->phone_number)
                                @if($key == 0)
                                <a href="tel:{{$phone->phone_number}}">{{$phone->phone_number}}
                                </a>
                                @else
                                <a href="tel:{{$phone->phone_number}}">, {{$phone->phone_number}}
                                </a>
                                @endif
                                @endif
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
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Referral Forms:</b></span>
                            <a href="{{$organization->organization_forms_x_url}}" class="panel-link">
                                {{$organization->organization_forms_x_filename}}
                            </a>
                        </h4>
                        @endif
                    </div>
                </div>
                <!-- Services area design -->
                @if(isset($organization_services))
                <div class="card">
                    <div class="card-block ">
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
                        @foreach($organization_services as $service)

                            <div class="organization_services">
                                <h4 class="card-title">
                                    <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                    $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                    <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                    @endif
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                    <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                    @endif
                                </h4>
                                <h4 style="line-height: inherit;">{!! Str::limit($service->service_description, 200) !!}</h4>
                                <h4 style="line-height: inherit;">
                                    <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                        @foreach($service->phone as $phone)
                                        <a href="tel:{{$phone->phone_number}}">
                                            {!! $phone->phone_number !!}
                                        </a>
                                        @endforeach
                                    </span>
                                </h4>
                                <h4>
                                    <span>
                                        <i class="icon md-pin font-size-18 vertical-align-top pr-10  m-0"></i>
                                        @if(isset($service->address))
                                        @foreach($service->address as $key => $address)
                                        @if($key == 0)
                                        {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                        {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                        @else
                                        ,  {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                        {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                        @endif
                                        @endforeach
                                        @endif
                                    </span>
                                </h4>

                                @if($service->service_details!=NULL)
                                @php
                                $show_details = [];
                                @endphp
                                @foreach($service->details->sortBy('detail_type') as $detail)
                                @php
                                for($i = 0; $i < count($show_details); $i ++){ if($show_details[$i]['detail_type']==$detail->
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
                                <h4>
                                    {{-- <span class="pl-0 category_badge subtitle"><b>Types of Services:</b>
                                        @if($service->service_taxonomy != 0 || $service->service_taxonomy==null)
                                        @php
                                        $names = [];
                                        @endphp
                                        @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                        @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                        @if($taxonomy->taxonomy_grandparent_name && $taxonomy->taxonomy_parent_name !=
                                        'Target Populations')

                                        <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}"
                                            at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                        @php
                                        $names[] = $taxonomy->taxonomy_grandparent_name;
                                        @endphp
                                        @endif
                                        @endif
                                        @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                        @if($taxonomy->taxonomy_parent_name && $taxonomy->taxonomy_parent_name != 'Target
                                        Populations')

                                        @if($taxonomy->taxonomy_grandparent_name)
                                        <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}"
                                            at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_parent_name}}</a>
                                        @endif
                                        @php
                                        $names[] = $taxonomy->taxonomy_parent_name;
                                        @endphp
                                        @endif
                                        @endif
                                        @if(!in_array($taxonomy->taxonomy_name, $names))
                                        @if($taxonomy->taxonomy_name && $taxonomy->taxonomy_parent_name != 'Target
                                        Populations')
                                        <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}"
                                            at="{{$taxonomy->taxonomy_recordid}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_name}}</a>
                                        @php
                                        $names[] = $taxonomy->taxonomy_name;
                                        @endphp
                                        @endif
                                        @endif

                                        @endforeach
                                        @endif
                                    </span> --}}
                                    <span class="pl-0 category_badge subtitle"><b>Service Category:</b>
                                        @foreach ($service->taxonomy as $service_taxonomy_info)
                                        @if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 &&  $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category')
                                        @if($service->service_taxonomy != null)
                                        <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                            at="child_{{$service_taxonomy_info->taxonomy_recordid}}" style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                        @endif
                                        @endif
                                        @endforeach
                                    </span>
                                </h4>
                                <h4>
                                    <span class="pl-0 category_badge subtitle"><b>Service Eligibility:</b>
                                    @foreach ($service->taxonomy as $service_taxonomy_info)
                                    @if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 &&  $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility')
                                    @if($service->service_taxonomy != null)
                                    <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                        at="child_{{$service_taxonomy_info->taxonomy_recordid}}" style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                    @endif
                                    @endif
                                    @endforeach
                                    </span>
                                </h4>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination_right text-right">
                        {{ $organization_services->appends(\Request::except('page'))->render() }}
                    </div>
                </div>
                @endif
                <!-- Services area design -->

                <!-- comment area design -->
                @if (Auth::user() && Auth::user()->roles)
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

                                {!! Form::open(['route' => ['organization_comment',$organization->organization_recordid]]) !!}
                                    <div class="form-group">
                                        <textarea class="form-control" id="reply_content" name="comment" rows="3">
                                          </textarea>
                                        @error('comment')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic" style="padding:22.5px 54px" >Post</button>
                                        {{-- <button type="button" id="close-reply-window-btn" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic">Close</button> --}}
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endif
                <!-- comment area design -->

            </div>

            <div class="col-md-4 property">
                @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,$organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                <div style="display: flex;" class="mb-20">
                    <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                        <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus"></i> Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                            <a href="/service_create/{{$organization->organization_recordid}}" id="add-new-services">Add New Service</a>
                            <a href="/contact_create/{{$organization->organization_recordid}}" id="add-new-services">Add New Contact</a>
                            <a href="/facility_create/{{$organization->organization_recordid}}" id="add-new-services">Add New Location</a>
                        </div>
                    </div>
                </div>
                @endif
                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin')
                <div class="pt-10 pb-10 pl-0 btn-download">
                    {{-- <form method="GET" action="/organizations/{{$organization->organization_recordid}}/tagging"
                        id="organization_tagging"> --}}
                        {!! Form::open(['route' => ['organization_tag',$organization->organization_recordid]]) !!}
                        <div class="row" id="tagging-div">
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="tokenfield" name="tokenfield" value="{{$organization->organization_tag}}" />
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn_darkblack" style="float: right;">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    {{-- </form> --}}
                    {!! Form::close() !!}
                </div>
                @endif


                <!-- Locations area design -->
                <div class="card">
                    <div class="card-block p-0">
                        <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
                        </div>
                        <div class="p-25">
                            <h4 class="card_services_title">
                                <b>Locations</b>
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin' && isset($service))
                                <a href="/facilities/{{$service->service_locations}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                            </h4>
                            <div>
                                @if($location_info_list)
                                @foreach($location_info_list as $location)

                                <div class="location_border">
                                    <h4>
                                        @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                        <a href="/facilities/{{$location->location_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                        @endif
                                        <span><i class="icon fas fa-building font-size-18 vertical-align-top"></i>
                                            <a href="/facilities/{{$location->location_recordid}}">{{$location->location_name}}</a>
                                        </span>
                                    </h4>
                                    <h4>
                                        <span><i class="icon md-pin font-size-18 vertical-align-top"></i>
                                            @if(isset($location->address))
                                            @foreach($location->address as $address)
                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                            {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endforeach
                                            @endif
                                        </span>
                                    </h4>
                                    <h4>
                                        <span><i class="icon md-phone font-size-18 vertical-align-top  "></i>
                                            @php
                                                $phones = '';
                                            @endphp
                                            @foreach($location->phones as $k => $phone)
                                            @php
                                            if($k == 0){
                                                $phoneNo = '<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number.'</a>';
                                            }else{
                                                $phoneNo = ', '.'<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number .'</a>';
                                            }
                                            $phones .= $phoneNo;

                                            @endphp
                                            @endforeach
                                            @if(isset($phones))
                                            {!! rtrim($phones, ',') !!}
                                            @endif
                                        </span>
                                    </h4>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Locations area design -->


                <!-- Websitye rating area design -->
                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                    {{-- @if ($organization->organization_website_rating) --}}
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card_services_title">Website Rating:  {{$organization->organization_website_rating ? $organization->organization_website_rating : '-'}}</h4>
                                {{-- <div class="rating-body media-body" style="text-align: center;">
                                    <h1><b>{{$organization->organization_website_rating}}</b></h1>
                                </div> --}}
                            </div>
                        </div>
                    {{-- @endif --}}
                @endif
                <!-- Websitye rating area design -->

                <!-- Contact area design -->
                @if(isset($organization->contact))
                    @if ($organization->contact->count() > 0 && ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                    $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')))
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card_services_title"> Contacts
                                {{-- (@if(isset($organization->contact)){{$organization->contact->count()}}@else 0 @endif) --}}
                            </h4>
                            @foreach($organization->contact as $contact_info)
                                <div class="location_border">
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
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
                                                        <h4 class="m-0"><a href="/contacts/{{$contact_info->contact_recordid}}">{{$contact_info->contact_name}}</a></h4>
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
                        </div>
                    </div>
                    @endif
                @endif
                <!-- Contact area design -->

                <!-- Session area design -->
                @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,$organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card_services_title mb-20">Session
                                <a class="float-right comment_add" href="/session_create/{{$organization->organization_recordid}}" >Add Session</a>
                                <a href="{{route('session_download',$organization->organization_recordid)}}">
                                    <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10">
                                </a>
                            </h4>
                            <div class="session-body media-body col-md-12 p-0">
                                <table class="table jambo_table bulk_action nowrap" id="tbl-session">
                                    <thead>
                                        <tr>
                                            <th class="default-active">Date</th>
                                            <th class="default-active">Status</th>
                                            <th class="default-active">Edits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($session_list as $key => $session)
                                        <tr>
                                            <td>
                                                <h4 class="m-0">
                                                    <a href="/session/{{$session->session_recordid}}" target="_blank" style="color: #1b1b1b; text-decoration:none">
                                                        {{$session->session_performed_at}}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td><h4 class="m-0"><span>{{$session->session_verification_status}}</span></h4></td>
                                            <td><h4 class="m-0"><span>{{$session->session_edits}}</span></h4></td>
                                        </tr>
                                        @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Session area design -->



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
                            '<a href="/organizations/' + location.organization_recordid + '">' + location.organization_name +'</a>'+
                            '<div class="iw-subTitle">Address</div>' +
                            '<a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address_name + '" target="_blank">' + location.address_name +'</a>'+
                        '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
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
