@extends('layouts.app')
@section('title')
    {{ $organization->organization_name }}
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
    .org_logo img{
        max-width: 100%; max-height: 200px;
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
                                @if ($organization->organization_name != '')
                                    {{ $organization->organization_name }} {{$organization->organization_alternate_name ? '( '.$organization->organization_alternate_name.' )' : ''}}
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
                                <a href="">
                                    @if ($organization->organization_logo_x)
                                        <img src="{{ $organization->organization_logo_x }}" height="80">
                                    @endif
                                    {{ $organization->organization_name }}
                                    @if ($organization->organization_alternate_name != '')
                                        ({{ $organization->organization_alternate_name }})
                                    @endif
                                    @if ($organization->organization_url)
                                        <a href="{{ $organization->organization_url }}" class="m-2"
                                            target="_blank" style="font-size: 18px;"><i class="fab fa-globe"></i></a>
                                    @endif
                                    @if ($organization->facebook_url)
                                        <a href="{{ $organization->facebook_url }}" class="m-2" target="_blank"
                                            style="font-size: 18px;"><i class="fab fa-facebook"></i></a>
                                    @endif
                                    @if ($organization->twitter_url)
                                        <a href="{{ $organization->twitter_url }}" class="m-2" target="_blank"
                                            style="font-size: 18px;"><i class="fab fa-twitter"></i></a>
                                    @endif
                                    @if ($organization->instagram_url)
                                        <a href="{{ $organization->instagram_url }}" class="m-2"
                                            target="_blank" style="font-size: 18px;"><i class="fab fa-instagram"></i></a>
                                    @endif
                                </a>
                                {{-- $organizationStatus, ($organization->organization_status_x  --}}
                                @if ($organization->organization_status_x && isset($organizationStatus[$organization->organization_status_x]) && ($organizationStatus[$organization->organization_status_x] == 'Out of Business' || $organizationStatus[$organization->organization_status_x] == 'Inactive'))
                                <span class="badge badge-danger float-right m-2" style="color:#fff;">Inactive</span>
                                @endif
                                @if (Auth::user() && Auth::user()->roles && ((Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && ((Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin'))) ||  (Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles->name == 'Network Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0) || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0)))
                                    <a href="/organizations/{{ $organization->organization_recordid }}/edit"
                                        class="float-right">
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                @endif
                            </h4>

                            @if ($organization->parent_organization)
                            <h4 class="tagp_class"><span class="">Parent Organization:</span>
                                <a href="/organizations/{{ $organization->parent_organization }}" target="_blank">{{$organization->parent->organization_name}}</a>
                            </h4>
                            @endif
                            @if ($organization->organization_description)
                            <div class="tagp_class">{!! nl2br($organization->organization_description) !!}</div>
                            @endif
                            @if ($organization->organization_url)
                            <div class="tagp_class">
                                <span>
                                    <i class="icon md-globe font-size-18 vertical-align-top mr-10 pt-2" style="float:none"></i>
                                    <a href="{{ $organization->organization_url }}" target="_blank"> {{ $organization->organization_url }}</a>
                                </span>
                            </div>
                            @endif
                            @if ($organization->organization_phones_data)
                                <div class="tagp_class">
                                    <span><i class="icon md-phone font-size-18 vertical-align-top  mr-10 pt-2" style="float:none"></i>
                                        {!! $organization->organization_phones_data !!}
                                    </span>
                                </div>
                            @endif
                            @if ($organization->organization_email)
                                <div class="tagp_class">
                                    <span><i class="icon md-email font-size-18 vertical-align-top  mr-10 pt-2" style="float:none"></i>
                                        <a href="mailto:{{ $organization->organization_email }}">{{ $organization->organization_email }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                            @if ($organization->organization_year_incorporated)
                            <div class="tagp_class">
                                <span class="subtitle"><b>Year Incorporated:</b></span>
                                {{ $organization->organization_year_incorporated }}
                            </div>
                            @endif
                            @if ($organization->organization_tax_id)
                            <div class="tagp_class">
                                <span class="subtitle"><b>URI:</b></span>
                                {{ $organization->organization_tax_id }}
                            </div>
                            @endif
                            {{-- related program --}}

                            @if (isset($organization->organization_forms_x_filename))
                                <h4 class="py-10" style="line-height: inherit;"><span
                                        class="mb-10"><b>Referral
                                            Forms:</b></span>
                                    <a href="{{ $organization->organization_forms_x_url }}" class="panel-link">
                                        {{ $organization->organization_forms_x_filename }}
                                    </a>
                                </h4>
                            @endif
                        </div>
                    </div>
                    <ul class="nav nav-tabs tabpanel_above">
                        @if ((isset($organization->services) && count($organization->services) != 0) || (count($organization->services) == 0 && $organization->getServices->count() > 0))
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#services">
                                <h4 class="card_services_title">Services
                                    ({{ $organization->organization_service_count }})
                                </h4>
                            </a>
                        </li>
                        @endif
                        @if ($location_info_list && count($location_info_list) > 0)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#locations">
                                <h4 class="card_services_title">Locations
                                    ({{ $location_info_list ? count($location_info_list) : 0 }})
                                </h4>
                            </a>
                        </li>
                        @endif
                        @if ($organization->contact->count() > 0 && ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles->name == 'Network Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0) || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0)))
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#contacts">
                                <h4 class="card_services_title">Contacts
                                    ({{ isset($organization->contact) ? $organization->contact->count() : 0 }})
                                </h4>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#program_tabs">
                                <h4 class="card_services_title">Program
                                    ({{ $organization->program ? count($organization->program) : 0 }})
                                </h4>
                            </a>
                        </li>
                        @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles->name == 'Network Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0) || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0))
                            <div style="margin-left: auto;">
                                <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                                    <button class="btn btn-primary dropdown-toggle btn-block" type="button"
                                        id="dropdownMenuButton-group" data-toggle="dropdown" ria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-plus"></i> Add New
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                                        <a href="/service_create/{{ $organization->organization_recordid }}"
                                            id="add-new-services">Service</a>
                                        <a href="/contact_create/{{ $organization->organization_recordid }}"
                                            id="add-new-services">Contact</a>
                                        <a href="/facility_create/{{ $organization->organization_recordid }}"
                                            id="add-new-services">Location</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane {{ ((isset($organization->services) && count($organization->services) != 0) || (count($organization->services) == 0 && $organization->getServices->count() > 0)) ? 'active' : 'fade' }}" id="services">
                                    <!-- Services area design -->
                                    @if (isset($organization_services))
                                        @foreach ($organization_services as $service)
                                            @if ((!Auth::user() && $service->access_requirement == 'none') || Auth::user())
                                                <div class="organization_services">
                                                    <h4 class="card-title">
                                                        <a href="/services/{{ $service->service_recordid }}">{{ $service->service_name }}</a>
                                                        @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles->name == 'Network Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0) || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0))
                                                            <a href="/services/{{ $service->service_recordid }}/edit"
                                                                class="float-right">
                                                                @if ($service->access_requirement == 'yes')
                                                                    <img src="/images/noun_Lock and Key_1043619.png"
                                                                        width="30px" alt="noun_Lock and Key_1043619"
                                                                        style="margin-right: 6px">
                                                                @endif
                                                                <i class="icon md-edit mr-0"></i>
                                                            </a>
                                                        @endif
                                                    </h4>
                                                    @if ($service->service_description)
                                                        <div class="tagp_class">{!! Str::limit($service->service_description, 200) !!}</div>
                                                    @endif
                                                    @if ($service->phone && count($service->phone) > 0)
                                                        <div class="tagp_class">
                                                            <span><i class="icon md-phone font-size-18 vertical-align-top mr-10" ></i>
                                                                @foreach ($service->phone as $key => $phone)
                                                                    {{-- <a href="tel:{{ $phone->phone_number }}">{{ $phone->phone_number }}</a> --}}

                                                                    @php
                                                                        $otherData = '<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number.'</a>';
                                                                        $otherData .= $phone->phone_extension ? '&nbsp;&nbsp;ext. '. $phone->phone_extension : '';
                                                                        $otherData .=  $phone->type ? '&nbsp;('.$phone->type->type.')' : '';
                                                                        $otherData .=  $phone->phone_language ? ' '.$phone->get_phone_language($phone->id) : '';
                                                                        $otherData .=  $loop->last ? '' : ';';
                                                                    @endphp
                                                                    {!! $otherData !!}

                                                                    {{-- &nbsp;&nbsp;{{ $phone->phone_extension ? 'ext. ' . $phone->phone_extension : '' }}&nbsp;{{ $phone->type ? '(' . $phone->type->type . ')' : '' }}
                                                                    @if ($phone->phone_language)
                                                                        {{ $phone->get_phone_language($phone->id) }}
                                                                    @endif
                                                                    {{ $phone->phone_description ? '- ' . $phone->phone_description : '' }}
                                                                    @if(count($service->phone) > ($key + 1))
                                                                    ;
                                                                    @endif --}}
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    @endif
                                                    {{-- @if (isset($service->address) && count($service->address) > 0)
                                                    <div class="tagp_class">
                                                        <span>
                                                            <i class="icon md-pin font-size-18 vertical-align-top mr-10" ></i>
                                                            @if (isset($service->address))
                                                            @foreach ($service->address as $key => $address)
                                                            @if ($key == 0)
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
                                                    </div>
                                                    @endif --}}

                                                    @if (isset($service->locations) && count($service->locations) > 0)
                                                    <div class="tagp_class">
                                                        <span>
                                                            <i class="icon md-pin font-size-18 vertical-align-top mr-10" style="margin-top:5px;"></i>
                                                            <ul class="p-0" style="margin-left: 25px;">
                                                            {{-- @if (isset($service->locations))
                                                                @foreach ($service->locations as $key => $locationData)
                                                                    @if (isset($locationData->address))
                                                                        @if ($locationData->address != null)
                                                                            @foreach ($locationData->address as $address)
                                                                                <li>
                                                                                    {{ $address->address_1 }}
                                                                                    {{ $address->address_2 }}
                                                                                    {{ $address->address_city }}
                                                                                    {{ $address->address_state_province }}
                                                                                    {{ $address->address_postal_code }}
                                                                                </li>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif --}}
                                                            @foreach ($service->getAddresses() as $address)
                                                            <li> {{ $address }} </li>
                                                            @endforeach
                                                            </ul>
                                                        </span>
                                                    </div>
                                                    @endif


                                                    @if ($service->service_details != null)
                                                        @php
                                                            $show_details = [];
                                                        @endphp
                                                        @foreach ($service->details->sortBy('detail_type') as $detail)
                                                            @php
                                                                for ($i = 0; $i < count($show_details); $i++) {
                                                                    if ($show_details[$i]['detail_type'] == $detail->detail_type) {
                                                                        break;
                                                                    }
                                                                }
                                                                if ($i == count($show_details)) {
                                                                    $show_details[$i] = ['detail_type' => $detail->detail_type, 'detail_value' => $detail->detail_value];
                                                                } else {
                                                                    $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'] . ',' .$detail->detail_value;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($show_details as $detail)
                                                            <div class="tagp_class">
                                                                <span
                                                                    class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span>
                                                                {!! $detail['detail_value'] !!}</div>
                                                        @endforeach
                                                    @endif
                                                    @if (isset($service->taxonomy) && count($service->taxonomy) > 0)
                                                        @php
                                                            $i = 0;
                                                            $j = 0;
                                                            $service_category_data = 0;
                                                            $service_eligibility_data = 0;
                                                            foreach ($service->taxonomy as $service_taxonomy_info) {
                                                                $taxonomyTypeCategory = $service_taxonomy_info?->taxonomy_type->where('name','Service Category')->first();

                                                                if (!empty($taxonomyTypeCategory)) {
                                                                    $service_category_data += 1;
                                                                }
                                                                $taxonomyTypeEligibility = $service_taxonomy_info?->taxonomy_type->where('name','Service Eligibility')->first();
                                                                if (!empty($taxonomyTypeEligibility)) {
                                                                    $service_eligibility_data += 1;
                                                                }
                                                            }
                                                        @endphp
                                                        <div class="tagp_class">
                                                            <span class="pl-0 category_badge subtitle">
                                                                @foreach ($service->taxonomy as $service_taxonomy_info)
                                                                @php
                                                                    $taxonomyTypeCategory = $service_taxonomy_info?->taxonomy_type->where('name','Service Category')->first();
                                                                @endphp
                                                                    @if (!empty($taxonomyTypeCategory))
                                                                        {{-- @if ($service->service_taxonomy != null) --}}
                                                                            @if ($i == 0)
                                                                                <b>Service Category:</b>
                                                                                @php
                                                                                    $i++;
                                                                                @endphp
                                                                            @endif
                                                                            <a class="panel-link {{ str_replace(' ', '_', $service_taxonomy_info->taxonomy_name) }}"
                                                                                at="child_{{ $service_taxonomy_info->taxonomy_recordid }}"
                                                                                style="background-color: {{ $service_taxonomy_info->badge_color ? '#' . $service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{ $service_taxonomy_info->taxonomy_name }}</a>
                                                                        {{-- @endif --}}
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                        <div class="tagp_class">
                                                            <span class="pl-0 category_badge subtitle">
                                                                @foreach ($service->taxonomy as $service_taxonomy_info)
                                                                @php
                                                                    $taxonomyTypeEligibility = $service_taxonomy_info?->taxonomy_type->where('name','Service Eligibility')->first();
                                                                @endphp
                                                                @if (!empty($taxonomyTypeEligibility))
                                                                        {{-- @if ($service->service_taxonomy != null) --}}
                                                                            @if ($j == 0)
                                                                                <b>Service Eligibility:</b>
                                                                                @php
                                                                                    $j++;
                                                                                @endphp
                                                                            @endif
                                                                            <a class="panel-link {{ str_replace(' ', '_', $service_taxonomy_info->taxonomy_name) }}"
                                                                                at="child_{{ $service_taxonomy_info->taxonomy_recordid }}"
                                                                                style="background-color: {{ $service_taxonomy_info->badge_color ? '#' . $service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{ $service_taxonomy_info->taxonomy_name }}</a>
                                                                        {{-- @endif --}}
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="pagination_right text-right">
                                        {{ $organization_services->appends(\Request::except('page'))->render() }}
                                    </div>
                                </div>
                                <div class="tab-pane {{ ((isset($organization->services) && count($organization->services) == 0) || (count($organization->services) == 0 && $organization->getServices->count() == 0) && ($location_info_list && count($location_info_list) > 0) && ($organization->contact->count() == 0)) ? 'active' : '' }}" id="locations">
                                    <div>
                                        @if ($location_info_list)
                                            @foreach ($location_info_list as $location)
                                                <div class="location_border">
                                                    <div class="tagp_class">
                                                        @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                            <a href="/facilities/{{ $location->location_recordid }}/edit" class="float-right">
                                                                <i class="icon md-edit mr-0"></i>
                                                            </a>
                                                        @endif
                                                        <span><i class="icon fas fa-building font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                            @if (Auth::check())
                                                            <a href="/facilities/{{ $location->location_recordid }}">{{ $location->location_name }}</a>
                                                            @else
                                                                {{ $location->location_name }}

                                                            @endif
                                                        </span>
                                                    </div>
                                                    @if (isset($location->address) && count($location->address) > 0)
                                                    <div class="tagp_class">
                                                        <span><i class="icon md-pin font-size-18 vertical-align-top mr-10" style="margin-top:5px;"></i>
                                                            {{-- @if (isset($location->address))
                                                                @foreach ($location->address as $address)
                                                                    {{ $address->address_1 }}
                                                                    {{ $address->address_2 }}
                                                                    {{ $address->address_city }}
                                                                    {{ $address->address_state_province }}
                                                                    {{ $address->address_postal_code }}
                                                                @endforeach
                                                            @endif --}}
                                                            <ul class="p-0" style="margin-left: 25px;">
                                                                @foreach ($location->getAddresses() as $address)
                                                                   <li> {{ $address }} </li>
                                                                @endforeach
                                                            </ul>
                                                        </span>
                                                    </div>
                                                    @endif
                                                    @if (isset($location->phones) && count($location->phones) > 0)
                                                    <div class="tagp_class">
                                                        <span>
                                                            @php
                                                                $phones = '';
                                                            @endphp
                                                            @foreach ($location->phones as $k => $phone)
                                                                @php
                                                                    if ($phone && $phone->phone_number) {
                                                                        if ($k == 0) {
                                                                            $phoneNo = '<a href="tel:' . $phone->phone_number . '">' . $phone->phone_number . '</a>';
                                                                        } else {
                                                                            $phoneNo = ', ' . '<a href="tel:' . $phone->phone_number . '">' . $phone->phone_number . '</a>';
                                                                        }
                                                                        $phones .= $phoneNo;
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                            @if (isset($phones) && $phones != '')
                                                                <i class="icon md-phone font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                {!! rtrim($phones, ',') !!}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @endif
                                                    @if (isset($location->get_accessibility))
                                                    <div class="tagp_class">
                                                        <span><i class="fa fa-wheelchair-alt icon font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                            {{ $location->get_accessibility->accessibility }}
                                                        </span>
                                                        <br />
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane {{ ((isset($organization->services) && count($organization->services) == 0) || (count($organization->services) == 0 && $organization->getServices->count() == 0) && ($location_info_list && count($location_info_list) == 0) && ($organization->contact->count() > 0)) ? 'active' : '' }}" id="contacts">
                                    @if (isset($organization->contact))
                                        @if ($organization->contact->count() > 0 && ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles->name == 'Network Admin' && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0) || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $organization->organization_tag))) > 0)))

                                            @foreach ($organization->contact as $contact_info)
                                                <div class="location_border p-0">
                                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                        <a href="/contacts/{{ $contact_info->contact_recordid }}/edit"
                                                            class="float-right">
                                                            <i class="icon md-edit mr-0"></i>
                                                        </a>
                                                    @endif
                                                    <table class="table ">
                                                        <tbody>
                                                            @if ($contact_info->contact_name)
                                                                <tr>
                                                                    <td>
                                                                        <h4 class="m-0"><span><b>Name:</b></span>
                                                                        </h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="m-0"><a
                                                                                href="/contacts/{{ $contact_info->contact_recordid }}">{{ $contact_info->contact_name }}</a>
                                                                        </h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($contact_info->contact_title)
                                                                <tr>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span><b>Title:</b></span>
                                                                        </h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span>{{ $contact_info->contact_title }}</span>
                                                                        </h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($contact_info->contact_department)
                                                                <tr>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span><b>Department:</b></span>
                                                                        </h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span>{{ $contact_info->contact_department }}</span>
                                                                        </h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($contact_info->contact_email)
                                                                <tr>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span><b>Email:</b></span>
                                                                        </h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span>{{ $contact_info->contact_email }}</span>
                                                                        </h4>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($contact_info->phone->count())
                                                                <tr>
                                                                    <td>
                                                                        <h4 class="m-0">
                                                                            <span><b>Phones:</b></span>
                                                                        </h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4 class="m-0"><span>
                                                                                @foreach ($contact_info->phone as $phone_info)
                                                                                    <a
                                                                                        href="tel:{{ $phone_info->phone_number }}">
                                                                                        {{ $phone_info->phone_number }},
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
                                <div class="tab-pane fade" id="program_tabs">
                                    @include('frontEnd.organizations.show_programs')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Services area design -->

                    <!-- comment area design -->
                    @auth
                        <ul class="nav nav-tabs tabpanel_above">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#interactions">
                                    <h4 class="card_services_title">Notes</h4>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#commnets">
                                    <h4 class="card_services_title">Comments</h4>
                                </a>
                            </li>
                            {{-- @if (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin'))
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#customdata">
                                        <h4 class="card_services_title">Custom Data</h4>
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                        <div class="card">
                            <div class="card-block" style="border-radius: 0 0 12px 12px">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="interactions">
                                        @foreach ($organization->SessionData as $sessionValue)
                                            <table class="table table-bordered mb-20 notes_table">
                                                <tbody>
                                                    <tr>
                                                        <td><span class="subtitle">Timestamp:
                                                            </span>{{ $sessionValue->session_performed_at }}</td>
                                                        <td><span class="subtitle">User:</span>
                                                            {{ $sessionValue->user ? $sessionValue->user->first_name . ' ' . $sessionValue->user->last_name : '' }}
                                                        </td>
                                                        <td><span class="subtitle">Method:</span>
                                                            {{ $sessionValue->get_interaction_method_data ? $sessionValue->get_interaction_method_data->name : '' }}
                                                        </td>
                                                        <td><span class="subtitle">Disposition:</span>
                                                            {{ $sessionValue->getInteraction && $sessionValue->getInteraction->disposition ? $sessionValue->getInteraction->disposition->name : '' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td ><span class="subtitle">Notes:</span>
                                                            {{ $sessionValue->getInteraction ? $sessionValue->getInteraction->interaction_notes : '' }}
                                                        </td>
                                                        <td ><span class="subtitle">Status:</span>
                                                            {{ $sessionValue->getOrganizationStatus ? $sessionValue->getOrganizationStatus->status : '' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endforeach
                                    </div>
                                    <div class="tab-pane fade" id="commnets">
                                        @if (Auth::user() && Auth::user()->roles)
                                            <div class="comment-body media-body">
                                                @foreach ($comment_list as $key => $comment)
                                                    <div class="main_commnetbox">
                                                        <div class="comment_inner">
                                                            <div class="commnet_letter">

                                                                {{ $comment->comments_user_firstname[0] .(isset($comment->comments_user_lastname[0])? $comment->comments_user_lastname[0]: $comment->comments_user_firstname[1]) }}
                                                            </div>
                                                            <div class="comment_author">
                                                                <h5>
                                                                    <a class="comment-author" href="javascript:void(0)">
                                                                        {{ $comment->comments_user_firstname }}
                                                                        {{ $comment->comments_user_lastname }}
                                                                    </a>
                                                                </h5>
                                                                <p class="date">{{ $comment->comments_datetime }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="commnet_content">
                                                            <p>{{ $comment->comments_content }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                {{-- <a class="active comment_add" id="reply-btn" href="javascript:void(0)" role="button">Add a comment</a> --}}

                                                {!! Form::open(['route' => ['organization_comment', $organization->organization_recordid]]) !!}
                                                <div class="form-group">
                                                    <textarea class="form-control" id="reply_content" name="comment" rows="3"></textarea>
                                                    @error('comment')
                                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-lg waves-effect waves-classic"
                                                        style="padding:22.5px 54px">Post</button>
                                                    {{-- <button type="button" id="close-reply-window-btn" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic">Close</button> --}}
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
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
                                                        <td>{{ $organization->organization_website_rating ? $organization->organization_website_rating : '-' }}
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
                    @if (Auth::check() || $organization->logo)
                        <div class="card detail_services">
                            <div class="card-block">
                                <div class="text-center org_logo">
                                    <img src="{{ $organization->logo }}" alt="" title=" ">
                                </div>
                                @auth
                                @if ($organization->organization_status_x)
                                    <h4>
                                        <span class="subtitle"><b>Status:</b></span>
                                        {{ $organization->status_data ? $organization->status_data->status : '' }}
                                    </h4>
                                @endif
                                @if ($organization->get_latest_updated($organization, 'updated_at'))
                                    <h4>
                                        <span class="subtitle"><b>Last Updated:</b></span>
                                        {{ $organization->get_latest_updated($organization, 'updated_at') }}
                                    </h4>
                                @endif
                                @if ($organization->get_latest_updated($organization, 'updated_by'))
                                    <h4>
                                        <span class="subtitle"><b>Last Updated By:</b></span>
                                        {{ $organization->get_latest_updated($organization, 'updated_by') }}
                                    </h4>
                                @endif
                                @if ($organization->get_last_verified_by)
                                    <h4>
                                        <span class="subtitle"><b>Last Verified By:</b></span>
                                        {{ $organization->get_last_verified_by->first_name . ' ' . $organization->get_last_verified_by->last_name }}
                                    </h4>
                                @endif
                                @if (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin'))
                                    <div class="btn-download">
                                        {!! Form::open(['route' => ['organization_tag', $organization->organization_recordid], 'class' => 'm-0']) !!}
                                        <div class="row" id="tagging-div">
                                            <div class="col-md-10">
                                                {!! Form::select('organization_tag[]', $allTags, explode(',', $organization->organization_tag), ['class' => 'form-control selectpicker drop_down_create', 'id' => 'organization_tag', 'data-live-search' => 'true', 'data-size' => '5', 'multiple' => 'true']) !!}
                                            </div>
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
                                @endif
                                @endauth
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
                                        <div class="py-10"
                                            style="float: left; width:100%;border-bottom: 1px solid #dadada;">
                                            <p style="color: #000;font-size: 16px; font-weight: 500 !Important;">For Organization :
                                                {{ $organization->organization_name }}</p>
                                            <p class="mb-5" style="color: #000;font-size: 16px;">On
                                                <a href="/viewChanges/{{ $item->id }}/{{ $organization->organization_recordid }}">
                                                    <b style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">{{ $item->created_at }}</b>
                                                </a>
                                                ,
                                                @if ($item->user)
                                                    <a href="/userEdits/{{ $item->user ? $item->user->id : '' }}/0" style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">
                                                        <b style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '' }}</b>
                                                    </a>
                                                @endif
                                            </p>
                                            {{-- <p><b style="font-family: Neue Haas Grotesk Display Medium; color:#000">User Name</b> - </p> --}}
                                            @foreach ($item->old_values as $key => $v)
                                                @php
                                                    $fieldNameArray = explode('_', $key);
                                                    $fieldName = implode(' ', $fieldNameArray);
                                                    $new_values = explode('| ', $item->new_values[$key]);
                                                    $old_values = explode('| ', $v);
                                                    $old_values = array_values(array_filter($old_values));
                                                    $new_values = array_values(array_filter($new_values));
                                                @endphp
                                                <ul style="padding-left: 0px;font-size: 16px;">
                                                    @if ($v && count($old_values) > count($new_values))
                                                        @php
                                                            $diffData = array_diff($old_values, $new_values);
                                                        @endphp
                                                        <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Removed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span style="color: #FF5044">{{ implode(',', $diffData) }}</span>
                                                        </li>
                                                    @elseif($v && count($old_values) < count($new_values))
                                                        @php
                                                            $diffData = array_diff($new_values, $old_values);
                                                        @endphp
                                                        @if (count($diffData) > 0)
                                                        <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                        </li>
                                                        @endif
                                                    @elseif($v && count($new_values) == count($old_values))
                                                        @php
                                                            $diffData = array_diff($new_values, $old_values);
                                                        @endphp
                                                        @if (count($diffData) > 0)
                                                        <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                        </li>
                                                        @endif
                                                    @elseif($item->new_values[$key])
                                                        <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
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
                                        <label>Method: </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="interaction_method" name="interaction_method" data-size="5">
                                            @foreach ($method_list as $key => $method)
                                                <option value="{{ $key }}">{{ $method }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="organization_recordid"
                                        value="{{ $organization->organization_recordid }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Disposition: </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="interaction_disposition" name="interaction_disposition" data-size="5">
                                            @foreach ($disposition_list as $key => $disposition)
                                                <option value="{{ $key }}">{{ $disposition }}</option>
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
                                        <label>Notes: </label>
                                        <input class="form-control selectpicker" type="text" id="interaction_notes"
                                            name="interaction_notes" value="">
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Services: </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="organization_services" name="organization_services[]" data-size="5"
                                            multiple>
                                            <option value="" selected>No Specific Service</option>
                                            @foreach ($service_interations as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Update Status: </label>
                                        {!! Form::select('organization_status', $organizationStatus, ($organization->organization_status_x ? $organization->organization_status_x : ($layout->default_organization_status ?? null)), ['class' => 'form-control selectpicker', 'id' => 'organization_status', 'placeholder' => 'Select Status']) !!}
                                    </div>
                                </div>
                                @if (isset($organizationStatus[$organization->organization_status_x]) && $organizationStatus[$organization->organization_status_x] == 'Verified')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input reverify" type="checkbox" name="reverify" id="reverify1" value="1">
                                            <label class="form-check-label" for="reverify1"><b style="color: #000">Reverify</b></label>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
                                    <input type="text" class="form-control" required name="tag" placeholder="tag" id="tag">
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


    <script type="text/javascript" src="https://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
    </script>
    <script type="text/javascript"
        src="https://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

    <script>
        var tag_source = <?php print_r(json_encode($existing_tags)); ?>;

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

            $('#organization_tag').change(function() {
                let val = $(this).val()
                console.log($.inArray('create_new', val))
                if ($.inArray('create_new', val) != -1) {
                    $('#create_new_organization_tag').modal('show')
                } else {
                    let id = "{{ $organization->id }}"
                    $.ajax({
                        url: '{{ route('addOrganizationTag') }}',
                        method: 'get',
                        data: {
                            val,
                            id
                        },
                        success: function(response) {
                            // alert('Tag save successfully!')
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    })
                }
            })
            $('.organizationTagCloseButton').click(function() {
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

        $(document).ready(function() {

            var locations = <?php print_r(json_encode($locations)); ?>;
            console.log(locations,'locations')
            var organization = <?php print_r(json_encode($organization->organization_name)); ?>;
            var maplocation = <?php print_r(json_encode($map)); ?>;

            if (maplocation.active == 1) {
                avglat = maplocation.lat;
                avglng = maplocation.long;
                zoom = maplocation.zoom_profile;
            } else {
                avglat = 40.730981;
                avglng = -73.998107;
                zoom = 12;
            }
            if (locations.length > 0) {
                latitude = locations[0].location_latitude;
                longitude = locations[0].location_longitude;
            } else {
                latitude = null;
                longitude = null;
            }

            if (latitude == null) {
                latitude = avglat;
                longitude = avglng;
            }

            async function initMap() {
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
                    "marker",
                );

                const map = new Map(document.getElementById("map"), {
                    center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                    zoom: zoom,
                    mapId: "4504f8b37365c3d0",
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
                    for (i = 0; i < location.services.length; i++) {
                        content += '<div class="iw-title"> <a href="/services/' + location.services[i].service_recordid + '">' + location.services[i].service_name + '</a></div>';
                    }
                    // '<div class="iw-title"> <a href="/services/'+ location.service_recordid +'">' + location.service_name + '</a> </div>' +
                    content += '<div class="iw-content">' +
                        '<div class="iw-subTitle">Organization Name</div>' +
                        '<a href="/organizations/' + location.organization_recordid + '">' + location
                        .organization_name + '</a>';
                    // '<div class="iw-subTitle">Address</div>' +
                    // location.address_name ;
                    // '<br> <a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address_name + '" target="_blank">View on Google Maps</a>'+

                    if (location.address) {
                        for (i = 0; i < location.address.length; i++) {
                            content += '<div class="iw-subTitle">Address</div>' + location.address[i].address_1 + (location.address[i].address_city ? ', ' + location.address[i].address_city : '') +  (location.address[i].address_state_province ? ',' + location.address[i].address_state_province : '') + (location.address[i].address_postal_code ? ',' + location.address[i].address_postal_code : '');
                            content +='<div><a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address[i].address_1 + (location.address[i].address_city ? ', ' + location.address[i].address_city : '') + (location.address[i].address_state_province ? ',' + location.address[i].address_state_province : '') + (location.address[i].address_postal_code ? ',' + location.address[i].address_postal_code : '') + '" target="_blank">View on Google Maps</a></div>';
                        }
                    }
                    content += '</div><div class="iw-bottom-gradient"></div>' +
                        '</div>';

                    const infowindow = new google.maps.InfoWindow({
                        content: content,
                        ariaLabel: "Uluru",
                    });
                    const marker = new AdvancedMarkerElement({
                        map,
                        position: position,
                        title: location.location_name,
                    });
                    marker.addListener("click", () => {
                        infowindow.open({
                            anchor: marker,
                            map,
                        });
                    });
                });

                if (locations.length > 1) {
                    map.fitBounds(latlongbounds);
                }
            }
            initMap()

        });

        $(document).ready(function() {
            var showChar = 250;
            var ellipsestext = "...";
            var moretext = "More";
            var lesstext = "Less";
            $('.more').each(function() {
                var content = $(this).html();

                if (content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreelipses">' + ellipsestext +
                        '</span><span class="morecontent"><span>' + h +
                        '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function() {
                if ($(this).hasClass("less")) {
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

            $('.panel-link').on('click', function(e) {
                if ($(this).hasClass('target-population-link') || $(this).hasClass(
                        'target-population-child'))
                    return;
                var id = $(this).attr('at');
                //   console.log(id);
                $("#category_" + id).prop("checked", true);
                $("#checked_" + id).prop("checked", true);
                $("#filter").submit();
            });

            $('.panel-link.target-population-link').on('click', function(e) {
                $("#target_all").val("all");
                $("#filter").submit();
            });

            $('.panel-link.target-population-child').on('click', function(e) {
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
