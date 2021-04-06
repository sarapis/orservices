@extends('layouts.app')
@section('title')
Contact
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">



@section('content')
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('error') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="content" class="container">
        <!-- Example Striped Rows -->
        <div class="row">
            <div class="col-md-8">
                <div class="card detail_services">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="">@if($contact->contact_name!='?'){{$contact->contact_name}}@endif </a>
                            @if ((Auth::user() && Auth::user()->user_organization && $contact->organization && str_contains(Auth::user()->user_organization,$contact->organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles->name == 'System Admin' || Auth::user() && Auth::user()->roles->name != 'Organization Admin')
                            <a href="{{ route('contacts.edit',$contact->contact_recordid) }}" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                        </h4>
                        <h4>
                            <span class="subtitle"><b>Title: </b></span>
                            {{$contact->contact_title}}
                        </h4>
                        <h4>
                            <span class="subtitle"><b>Organization: </b></span>

                            @if (Auth::user() && $contact->organization)
                                <a class="panel-link" href="/organizations/{{$contact->organization->organization_recordid}}">{{ $contact->organization->organization_name }}</a>
                            @endif
                        </h4>
                        <h4>
                            <span class="subtitle"><b>Department: </b></span>
                            {{$contact->contact_department}}
                        </h4>
                        <h4>
                            <span class="subtitle"><b>Email: </b></span>
                            {{$contact->contact_email}}
                        </h4>
                        @if($contact->phone)
                        <h4 style="line-height: inherit;">
                            <span class="subtitle"><b>Phone Number: </b></span>
                            @foreach($contact->phone as $key => $phone)
                                {{$phone->phone_number}} {{ $phone->phone_number && count($contact->phone) > $key+1 ? ',' : '' }}
                            @endforeach
                        </h4>
                        @endif
                        {{-- <h4>
                            <span class="subtitle"><b>Phone Area Code: </b></span>
                            {{$contact->contact_phone_areacode}}
                        </h4> --}}
                        <h4>
                            <span class="subtitle"><b>Phone Extension: </b></span>
                            @foreach($contact->phone as $key => $phone)
                                {{$phone->phone_extension}} {{ $phone->phone_extension && count($contact->phone) > $key+1 ? ',' : '' }}
                            @endforeach
                        </h4>
                    </div>
                </div>

                @if(isset($contact->service))
                <div class="card">
                    <div class="card-block ">
                        <h4 class="card_services_title">Services
                            (@if(isset($contact->service)){{$contact->service->count()}}@else 0 @endif)
                        </h4>
                        @foreach($contact->service as $service)
                        <div class="organization_services">
                            <h4 class="card-title">
                                <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                @if ((Auth::user() && Auth::user()->user_organization && $contact->organization && str_contains(Auth::user()->user_organization,$contact->organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles->name == 'System Admin' || Auth::user() && Auth::user()->roles->name != 'Organization Admin')
                                    <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                @endif
                            </h4>
                            <h4 style="line-height: inherit;">{!! Str::limit($service->service_description, 200) !!}</h4>
                            <h4 style="line-height: inherit;">
                                <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span>
                            </h4>
                            <h4>
                                <span> <i class="icon md-pin font-size-18 vertical-align-top pr-10  m-0"></i>
                                @if(isset($service->address))
                                    @foreach($service->address as $address)
                                        {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
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
                                    for($i = 0; $i < count($show_details); $i ++){
                                        if($show_details[$i]['detail_type'] == $detail->detail_type)
                                            break;
                                    }
                                    if($i == count($show_details)){
                                        $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=> $detail->detail_value);
                                    }
                                    else{
                                        $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].', '.$detail->detail_value;
                                    }
                                @endphp
                            @endforeach
                            @foreach($show_details as $detail)
                                <h4><span class="subtitle"><b>{{ $detail['detail_type'] }}: </b></span> {!! $detail['detail_value'] !!}</h4>
                            @endforeach
                                  @endif
                            <h4>
                                {{-- <span class="pl-0 category_badge subtitle"><b>Types of Services:</b>
                                @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                    @php
                                        $names = [];
                                    @endphp
                                    @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                        @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                            @if($taxonomy->taxonomy_grandparent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                                @php
                                                $names[] = $taxonomy->taxonomy_grandparent_name;
                                                @endphp
                                            @endif
                                        @endif
                                        @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                            @if($taxonomy->taxonomy_parent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                @if($taxonomy->taxonomy_grandparent_name)
                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_parent_name}}</a>
                                                @endif
                                                @php
                                                $names[] = $taxonomy->taxonomy_parent_name;
                                                @endphp
                                            @endif
                                        @endif
                                        @if(!in_array($taxonomy->taxonomy_name, $names))
                                            @if($taxonomy->taxonomy_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}" at="{{$taxonomy->taxonomy_recordid}}" style="background-color: {{ $taxonomy->badge_color ? '#'.$taxonomy->badge_color : '#000' }} !important; color:#fff !important;">{{$taxonomy->taxonomy_name}}</a>
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
                </div>
                @endif
            </div>
            @auth
    <div class="col-md-4">
        {{-- <h4 class="card-title title_edit mb-30"></h4> --}}
        <div class="card all_form_field ">
            <div class="card-block">
                <h4 class="card_services_title mb-20">Change Log</h4>
                @foreach ($contactAudits as $item)
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
                    @endphp
                    <ul style="padding-left: 0px;font-size: 16px;">
                    @if ($v)
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Changed <b
                            style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                        from <span style="color: #FF5044">{{ $v }}</span> to <span
                            style="color: #35AD8B">{{ $item->new_values[$key] }}</span>
                    </li>
                    @elseif($item->new_values[$key])
                    <li style="color: #000;list-style: disc;list-style-position: inside;">Added <b
                        style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> <span
                        style="color: #35AD8B">{{ $item->new_values[$key] }}</span>
                    </li>
                    @endif
                    </ul>
                    @endforeach

                    <span><a href="/viewChanges/{{ $item->id }}/{{ $contact->contact_recordid }}"
                            style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">View
                            Changes</a></span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endauth
        </div>
    </div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>
</script>
@endsection
