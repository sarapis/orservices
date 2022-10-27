@extends('layouts.app')
@section('title')
    {{ $service->service_name }}
@stop
<style>
    .text_tooltips {
        position: relative;
        line-height: 20px;
        margin-top: 6px;
    }

    .text_tooltips .help-tip {
        top: 0;
        left: 0;
        width: 100%;
        border: none;
        background: none;
    }
    button[data-id="interaction_method"],
    button[data-id="service_tag"],
    button[data-id="service_status"],
    button[data-id="interaction_disposition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .text_tooltips .help-tip::before {
        display: none
    }

    .text_tooltips:hover .help-tip div {
        display: block;
        left: 0;
    }

</style>
@section('content')
    @include('layouts.filter')
    <div>

        <!-- Page Content Holder -->
        <div class="top_services_filter" style="display: inline-block;width: 100%;">
            <div class="container">
                @include('layouts.sidebar')
                <!-- Types Of Services -->
                {{-- <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                        Types Of Services
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu">
                        <a class="dropdown-item drop-sort">Service Name</a>
                    </div>
                </div> --}}
                <!--end  Types Of Services -->

                <!-- download -->
                @if ($layout->display_download_menu == 1)
                    <div class="dropdown btn_download float-right">
                        <button type="button" class="float-right btn_share_download dropdown-toggle" id=""
                            data-toggle="dropdown" aria-expanded="false">
                            <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                        </button>
                        <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                            @if ($layout->display_download_csv == 1)
                                <a class="dropdown-item" href="/download_service_csv/{{ $service->service_recordid }}"
                                    role="menuitem">Download CSV</a>
                            @endif
                            @if ($layout->display_download_pdf == 1)
                                <a class="dropdown-item " href="/download_service/{{ $service->service_recordid }}"
                                    role="menuitem">Download PDF</a>
                            @endif
                        </div>
                    </div>
                @endif
                <!--end download -->

                <!-- share btn -->
                <button type="button" class="float-right btn_share_download" data-toggle="modal"
                    data-target="#shareThisModal">
                    <img src="/frontend/assets/images/share.png" alt="" title="" class="mr-10 share_image">
                    Share
                </button>
                <!--end share btn -->
            </div>
        </div>
        <div class="inner_services">
            <div id="content" class="container">
                <!-- Example Striped Rows -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <a href="#">{{ $service->service_name }}</a>

                                    @if ((Auth::user() && Auth::user()->roles && $organization && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $service->organizations()->first()->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')  || (Auth::user() && Auth::user()->roles && Auth::user()->service_tags && $service->service_tag && count(array_intersect(explode(',', Auth::user()->service_tags),explode(',', $service->service_tag))) > 0) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'Section Admin' && Auth::user()->organization_tags && $service->organizations()->first()->organization_tag && count(array_intersect(explode(',', Auth::user()->organization_tags),explode(',', $service->organizations()->first()->organization_tag))) > 0))
                                        <a href="/services/{{ $service->service_recordid }}/edit" class="float-right">
                                            @if ($service->access_requirement == 'yes')
                                                <img src="/images/noun_Lock and Key_1043619.png" width="30px"
                                                    alt="noun_Lock and Key_1043619" style="margin-right: 6px" />
                                            @endif
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                    @endif
                                </h4>
                                @if (isset($service->service_alternate_name))
                                    <h4>
                                        <span class="subtitle"><b>Alternate Name: </b></span>
                                        {{ $service->service_alternate_name }}
                                    </h4>
                                @endif
                                @if ($service->service_organization)
                                    <p class="org_title"><span class="subtitle"><b>Organization:</b></span>
                                        @if ($service->service_organization != 0)
                                            @if (isset($service->organizations))
                                                <a class="panel-link" class="notranslate"
                                                    href="/organizations/{{ $service->organizations()->first()->organization_recordid }}">
                                                    {{ $service->organizations()->first()->organization_name }}</a>
                                            @endif
                                        @endif
                                    </p>
                                @endif
                                @if ($service->service_description)
                                <div class="service-description" style="line-height: inherit;">
                                    {!! nl2br($service->service_description) !!}
                                </div>
                                @endif
                                @if (isset($mainPhoneNumber) && count($mainPhoneNumber) > 0)
                                    <div class="tagp_class">
                                        <span><i class="icon md-phone font-size-18 vertical-align-top mr-0 pr-10"></i>
                                            @foreach ($mainPhoneNumber as $key => $item)
                                                {{-- <p> --}}
                                                    @if ($key != 0)
                                                        ,&nbsp;
                                                    @endif
                                                    <a href="tel:{{ $item->phone_number }}">{{ $item->phone_number }}</a>{!! $item->phone_extension ? '&nbsp;&nbsp; ext. ' . $item->phone_extension : '' !!}{!! $item->type ? '&nbsp;(' . $item->type->type . ')' : '' !!}
                                                    @if ($item->phone_language)
                                                        <br>
                                                        {{ $item->phone_language }}
                                                    @endif
                                                    {{ $item->phone_description ? '- ' . $item->phone_description : '' }}
                                                {{-- </p> --}}
                                            @endforeach
                                        </span>
                                    </div>
                                @endif
                                @if ($service->service_url)
                                    <div class="tagp_class">
                                        <span>
                                            <i class="icon md-globe font-size-18 vertical-align-top mr-0 pr-10"></i>
                                            @if ($service->service_url != null)
                                                <a href="{!! $service->service_url !!}">{!! $service->service_url !!}</a>
                                            @endif
                                        </span>
                                    </div>
                                @endif


                                @if ($service->service_email != null)
                                    <div class="tagp_class">
                                        <span>
                                            <i class="icon md-email font-size-18 vertical-align-top mr-0 pr-10"></i>
                                            {{ $service->service_email }}
                                        </span>
                                    </div>
                                @endif

                                @if (isset($service->languages) && count($service->languages) > 0)
                                    <div class="tagp_class">
                                        <span>
                                            <i class="icon fa-language  font-size-18 vertical-align-top mr-0 pr-10"></i>
                                            @foreach ($service->languages as $language)
                                                @if ($loop->last)
                                                    {{ $language->language }}
                                                @else
                                                    {{ $language->language }},
                                                @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endif

                                @if ($service->service_application_process)
                                    <div class="tagp_class">
                                        <span class="subtitle"><b>Application:</b></span> {!! $service->service_application_process !!}
                                    </div>
                                @endif
                                @if ($service->areas && count($service->areas) > 0)
                                    <div class="tagp_class">
                                        <span class="subtitle"><b>Service Area: </b></span>
                                        @foreach ($service->areas as $k => $item)
                                            @if ($k > 0)
                                                ,
                                            @endif
                                            {{ $item->name }}
                                        @endforeach
                                    </div>
                                @endif

                                @if ($service->service_wait_time)
                                    <div class="tagp_class"><span class="subtitle"><b>Wait Time:</b></span>
                                        {{ $service->service_wait_time }}</div>
                                @endif
                                @if ($service->fees && count($service->fees) > 0)
                                    <div class="tagp_class">
                                        <span class="subtitle"><b>Fee Options: </b></span>
                                        @foreach ($service->fees as $k => $item)
                                            @if ($k > 0)
                                                ,
                                            @endif
                                            {{ $item->fees }}
                                        @endforeach
                                    </div>
                                @endif

                                @if ($service->service_fees)
                                    <div class="tagp_class"><span class="subtitle"><b>Fees:</b></span> {{ $service->service_fees }}</div>
                                @endif

                                @if ($service->service_accreditations)
                                    <div class="tagp_class"><span class="subtitle"><b>Accreditations</b></span>
                                        {{ $service->service_accreditations }}
                                    </div>
                                @endif

                                @if ($service->service_licenses)
                                    <div class="tagp_class"><span class="subtitle"><b>Licenses</b></span>
                                        {{ $service->service_licenses }}</div>
                                @endif


                                @if (isset($service->schedules()->first()->byday))
                                    <div class="tagp_class">
                                        <span class="subtitle"><b>Schedules</b></span><br />
                                        {{-- @foreach ($service->schedules as $schedule)
                                        @if ($loop->last)
                                        {{$schedule->byday}} {{$schedule->opens_at}}
                                        {{$schedule->closes_at}}
                                        @else
                                        {{$schedule->byday}} {{$schedule->opens_at}}
                                        {{$schedule->closes_at}},
                                        @endif
                                        @endforeach --}}

                                    </div>
                                    @foreach ($service->schedules as $key => $schedule)
                                        @if ($schedule->schedule_holiday)
                                            @php
                                                $holidayScheduleData[] = 1;
                                            @endphp
                                        @endif
                                        @if (($schedule->byday && ($schedule->schedule_closed == null && $schedule->opens_at)) || ($schedule->schedule_closed && $schedule->schedule_holiday == null))
                                            <div >
                                                {{-- style="color:{{ strtolower(\Carbon\Carbon::now()->format('l')) == $schedule->byday ? 'blue' : '' }}" --}}
                                                <b style="font-weight: 600;color: #000; letter-spacing: 0.5px;">{{ ucfirst($schedule->byday) }}
                                                    :</b>
                                                @if ($schedule->schedule_closed == null)
                                                    {{ $schedule->opens_at }} - {{ $schedule->closes_at }}
                                                @else
                                                    Closed
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @if (isset($holidayScheduleData))
                                        <span style="margin-bottom: 20px;display: inline-block;font-weight: 600;text-decoration: underline; color: #5051db;cursor: pointer;" id="showHolidays"><a>Show holidays</a></span>
                                    @endif
                                    <div style="display: none;" id="holidays">
                                        <span class="subtitle"><b>Holidays</b></span><br />
                                        @foreach ($service->schedules as $schedule)
                                            @if ($schedule->schedule_holiday)
                                                <h4 style="color: #000;">
                                                    {{ $schedule->dtstart }} to {{ $schedule->dtstart }} :
                                                    @if ($schedule->schedule_closed == null)
                                                        {{ $schedule->opens_at }} - {{ $schedule->closes_at }}
                                                    @else
                                                        Closed
                                                    @endif
                                                </h4>
                                            @endif
                                        @endforeach
                                        <span style="margin-bottom: 20px;display: inline-block;font-weight: 600;text-decoration: underline; color: #5051db;cursor: pointer;" id="hideHolidays"><a>Hide holidays</a></span> <br>
                                    </div>
                                @endif

                                @isset($service->taxonomy)
                                    @if (count($service->taxonomy) > 0)
                                        @php
                                            $i = 0;
                                            $j = 0;
                                            $service_category_data = 0;
                                            $service_eligibility_data = 0;
                                            foreach ($service->taxonomy as $service_taxonomy_info) {
                                                // dd($service_taxonomy_info->taxonomy_type[0]->name);
                                                if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category') {
                                                    $service_category_data += 1;
                                                }
                                                if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility') {
                                                    $service_eligibility_data += 1;
                                                }
                                            }
                                        @endphp
                                        @if ($service_category_data != 0)
                                            <div class="tagp_class">
                                                <span class="pl-0 category_badge subtitle">
                                                    @foreach ($service->taxonomy as $service_taxonomy_info)
                                                        @if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Category')
                                                            @if ($service->service_taxonomy != null)
                                                                @if ($i == 0)
                                                                    <b>Service Category:</b>
                                                                    @php
                                                                        $i++;
                                                                    @endphp
                                                                @endif
                                                                <a class="panel-link {{ str_replace(' ', '_', $service_taxonomy_info->taxonomy_name) }}"
                                                                    at="child_{{ $service_taxonomy_info->taxonomy_recordid }}"
                                                                    style="background-color: {{ $service_taxonomy_info->badge_color ? '#' . $service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{ $service_taxonomy_info->taxonomy_name }}</a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>
                                        @endif
                                        @if ($service_eligibility_data != 0)
                                            <div class="tagp_class">
                                                <span class="pl-0 category_badge subtitle">
                                                    @foreach ($service->taxonomy as $service_taxonomy_info)
                                                        @if (isset($service_taxonomy_info->taxonomy_type) && count($service_taxonomy_info->taxonomy_type) > 0 && $service_taxonomy_info->taxonomy_type[0]->name == 'Service Eligibility')
                                                            @if ($service->service_taxonomy != null)
                                                                @if ($j == 0)
                                                                    <b>Service Eligibility:</b>
                                                                    @php
                                                                        $j++;
                                                                    @endphp
                                                                @endif
                                                                <a class="panel-link {{ str_replace(' ', '_', $service_taxonomy_info->taxonomy_name) }}"
                                                                    at="child_{{ $service_taxonomy_info->taxonomy_recordid }}"
                                                                    style="background-color: {{ $service_taxonomy_info->badge_color ? '#' . $service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{ $service_taxonomy_info->taxonomy_name }}</a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                @endisset
                                @if ($service->program && count($service->program) > 0)
                                    <div class="tagp_class">
                                        <span class="pl-0 category_badge subtitle"><b>Related Program:</b></span>
                                        <ul>
                                            @if (count($service->program) == 1 && isset($service->program[0]))
                                            <li class="text_tooltips">
                                                <u>{{ $service->program[0]->name }}</u>
                                                @if ($service->program[0]->alternate_name)
                                                    <div class="help-tip">
                                                        <div style="width: 300px;">
                                                            <p>{{ $service->program[0]->alternate_name }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($service->program[0]->program_service_relationship)
                                                    participation is
                                                    {{ $service->program[0]->program_service_relationship == 'not_required'? 'Not Required': Str::ucfirst($service->program[0]->program_service_relationship) }}.
                                                @endif
                                            </li>
                                        </ul>
                                        @else
                                        <ul>
                                        @foreach ($service->program as $key => $program)
                                            <li class="text_tooltips">
                                                {{ $program->name }}
                                                @if ($program->program_service_relationship)
                                                    @if ($program->alternate_name)
                                                        <div class="help-tip">
                                                            <div style="width: 300px;">
                                                                <p>{{ $program->alternate_name }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    participation is
                                                    {{ $program->program_service_relationship == 'not_required'? 'Not Required': Str::ucfirst($program->program_service_relationship) }}.
                                                @endif
                                            </li>
                                        @endforeach
                                        </ul>
                                        @endif
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
                                        <span class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span>
                                            {!! $detail['detail_value'] !!}
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <ul class="nav nav-tabs tabpanel_above">
                            @if (isset($service->locations) && count($service->locations) > 0)
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#locations">
                                    <h4 class="card_services_title">Locations
                                        ({{ isset($service->locations) ? count($service->locations) : 0 }})
                                    </h4>
                                </a>
                            </li>
                            @endif
                            @if (isset($contact_info_list) && count($contact_info_list) > 0)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#contacts">
                                    <h4 class="card_services_title">Contacts
                                        @if ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin'))  || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->service_tags),explode(',', $service->service_tag))) > 0))
                                            ({{ isset($contact_info_list) ? count($contact_info_list) : 0 }})
                                        @endif
                                    </h4>
                                </a>
                            </li>
                            @endif
                            @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && Auth::user()->user_organization == ($service->organizations ? $service->organizations()->first()->organization_recordid : '') && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')  || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->service_tags),explode(',', $service->service_tag))) > 0))
                                <div style="margin-left: auto;">
                                    <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                                        <button class="btn btn-primary dropdown-toggle btn-block" type="button"
                                            id="dropdownMenuButton-group" data-toggle="dropdown" ria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-plus"></i> Add New
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                                            <a href="/contact_create/{{ $service->service_recordid }}/service" id="add-new-services">Add New Contact</a>
                                        <a href="/facility_create/{{ $service->service_recordid }}/service" id="add-new-services">Add New Location</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </ul>
                        @if (isset($service->locations) && count($service->locations) > 0 || isset($contact_info_list) && count($contact_info_list) > 0 )
                        <div class="card">
                            <div class="card-block" style="border-radius: 0 0 12px 12px">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="locations">
                                        <div>
                                            <!-- @if (isset($service->locations))
                                                @if ($service->locations != null && count($service->locations) > 0)
                                                    <h4 class="card_services_title">
                                                        <b>Locations</b>
                                                        @if (Auth::user() && Auth::user()->roles && $organization && Auth::user()->user_organization && str_contains(Auth::user()->user_organization, $organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')   || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->service_tags),explode(',', $service->service_tag))) > 0))
                                                            <a href="/facilities/{{ $service->service_locations }}/edit"
                                                                class="float-right">
                                                                <i class="icon md-edit mr-0"></i>
                                                            </a>
                                                        @endif
                                                    </h4>
                                                @endif
                                            @endif -->
                                            <div>
                                                @if (isset($service->locations))
                                                    @if ($service->locations != null)
                                                        @foreach ($service->locations as $location)
                                                        <div class="location_border">
                                                            <p class="m-0">
                                                                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                                    <a href="/facilities/{{ $location->location_recordid }}/edit"
                                                                        class="float-right">
                                                                        <i class="icon md-edit mr-0"></i>
                                                                    </a>
                                                                @endif
                                                            </p>
                                                            @if ($location->location_name)
                                                            <div class="tagp_class">
                                                                <span><i class="icon fas fa-building font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                    {{ $location->location_name }}
                                                                    {{ $location->location_alternate_name ? '(' . $location->location_alternate_name . ')' : '' }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                            <div class="tagp_class">
                                                                <span>
                                                                    <i class="icon md-pin font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                    @if (isset($location->address))
                                                                        @if ($location->address != null)
                                                                            @foreach ($location->address as $address)
                                                                                {{ $address->address_1 }}
                                                                                {{ $address->address_2 }}
                                                                                {{ $address->address_city }}
                                                                                {{ $address->address_state_province }}
                                                                                {{ $address->address_postal_code }}
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            @if ($location->location_hours)
                                                                <div class="tagp_class">
                                                                    <span>
                                                                        <i class="icon fa-clock-o font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                        {{ $location->location_hours }}
                                                                    </span>
                                                                </div>
                                                                @endif
                                                                @if ($location->location_transportation)
                                                                <div class="tagp_class">
                                                                    <span><i class="icon fa-truck font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                        {{ $location->location_transportation }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if (isset($location->phones))
                                                                @if ($location->phones != null)
                                                                    @if (count($location->phones) > 0)
                                                                        @php
                                                                            $phones = '';
                                                                        @endphp
                                                                        @foreach ($location->phones as $k => $phone)
                                                                            @php
                                                                                if ($phone->phone_number) {
                                                                                    if ($k == 0) {
                                                                                        $phoneNo =
                                                                                            '<a href="tel:' .
                                                                                            $phone->phone_number .
                                                                                            '">' .
                                                                                            $phone->phone_number .
                                                                                            '</a>';
                                                                                    } else {
                                                                                        $phoneNo =
                                                                                            ', ' .
                                                                                            '<a href="tel:' .
                                                                                            $phone->phone_number .
                                                                                            '">' .
                                                                                            $phone->phone_number .
                                                                                            '</a>';
                                                                                    }
                                                                                    $phones .= $phoneNo;
                                                                                }
                                                                            @endphp
                                                                        @endforeach
                                                                        @if ($phones != '')
                                                                            <p>
                                                                                <span>
                                                                                    <i class="icon md-phone font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                                    {!! rtrim($phones, ',') !!}
                                                                                </span>
                                                                            </p>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            @if ($location->location_description)
                                                                <div class="tagp_class">
                                                                    <span>
                                                                        {{ $location->location_description }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if (isset($location->accessibilities()->first()->accessibility))
                                                                <div class="tagp_class">
                                                                    <span><i class="fa fa-wheelchair-alt icon font-size-18 vertical-align-top mr-10" style="float:none"></i>
                                                                        {{ $location->accessibilities()->first()->accessibility }}
                                                                    </span>
                                                                    <br />
                                                                </div>
                                                            @endif
                                                            @if (isset($location->schedules()->first()->byday))
                                                                <p class="panel-text">
                                                                    <span class="badge bg-red"><b>Schedules:</b></span>
                                                                    @if ($location->schedules != null)
                                                                        @foreach ($location->schedules as $schedule)
                                                                            @if ($loop->last)
                                                                                {{ $schedule->byday }}
                                                                                {{ $schedule->opens_at }}
                                                                                {{ $schedule->closes_at }}
                                                                            @else
                                                                                {{ $schedule->byday }}
                                                                                {{ $schedule->opens_at }}
                                                                                {{ $schedule->closes_at }},
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </p>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="contacts">
                                        <!-- contact area design -->
                                            @if ($contact_info_list && count($contact_info_list) > 0 && $contactCount > 0)
                                            <!-- <h4 class="card_services_title"> Contacts </h4> -->
                                            @foreach ($contact_info_list as $contact_info)
                                                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                    <a href="/contacts/{{ $contact_info->contact_recordid }}/edit"
                                                        class="float-right">
                                                        <i class="icon md-edit mr-0"></i>
                                                    </a>
                                                @endif
                                                @if ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && $contact_info->organization && str_contains(Auth::user()->user_organization, $contact_info->organization->organization_recordid) && (Auth::user()->roles->name == 'Organization Admin' || Auth::user()->roles->name == 'Section Admin')) || (!Auth::check() && $contact_info->visibility != 'private')   || (Auth::user() && Auth::user()->roles && count(array_intersect(explode(',', Auth::user()->service_tags),explode(',', $service->service_tag))) > 0))
                                                    <div class="location_border p-0">
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
                                                                            <h4 class="m-0"><span><b>Title:</b></span>
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
                                                                            <h4 class="m-0"><span><b>Email:</b></span>
                                                                            </h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4 class="m-0">
                                                                                <span>{{ $contact_info->contact_email }}</span>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if ($contact_info->contact_phones)
                                                                    @if (isset($contact_info->phone->phone_number))
                                                                        <tr>
                                                                            <td>
                                                                                <h4 class="m-0">
                                                                                    <span><b>Phones:</b></span>
                                                                                </h4>
                                                                            </td>
                                                                            <td>
                                                                                <h4 class="m-0"><span>
                                                                                        {{ $contact_info->phone->phone_number }}</span>
                                                                                </h4>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @endif
                                        <!-- contact area design -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                            </ul>
                            <div class="card">
                                <div class="card-block" style="border-radius: 0 0 12px 12px">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="interactions">
                                            @foreach ($service->SessionData as $sessionValue)
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
                                                            <td><span class="subtitle">Notes:</span>
                                                                {{ $sessionValue->getInteraction ? $sessionValue->getInteraction->interaction_notes : '' }}
                                                            </td>
                                                            <td colspan="4"><span class="subtitle">Service Status:</span>
                                                                {{ $sessionValue->getServiceStatus && $sessionValue->getServiceStatus->status ? $sessionValue->getServiceStatus->status : '' }}
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
                                                    {!! Form::open(['route' => ['service_comment', $service->service_recordid]]) !!}
                                                    <div class="form-group">
                                                        <textarea class="form-control" id="reply_content" name="comment" rows="3"></textarea>
                                                        @error('comment')
                                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic" style="padding:22.5px 54px">Post</button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div class="col-md-4 property">
                        @auth
                        <div class="card detail_services">
                            <div class="card-block">
                                @if ($service->get_status)
                                    <p>
                                        <span class="pl-0 category_badge subtitle"><b>Service Status:</b>
                                            <span class="">{!! $service->get_status->status !!}</span>
                                        </span>
                                    </p>
                                @endif
                                @if ($service->updated_at)
                                    <h4>
                                        <span class="subtitle"><b>Last Updated:</b></span>
                                        {{ $service->updated_at }}
                                    </h4>
                                @endif
                                @if (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin'))
                                    <div class="btn-download">
                                        {!! Form::open(['route' => ['service_tag', $service->service_recordid], 'class' => 'm-0']) !!}
                                        <div class="row" id="tagging-div">
                                            <div class="col-md-10">
                                                {!! Form::select('service_tag[]', $allTags, explode(',', $service->service_tag), ['class' => 'form-control selectpicker drop_down_create', 'id' => 'service_tag', 'data-live-search' => 'true', 'data-size' => '5', 'multiple' => 'true']) !!}
                                            </div>
                                            <div class="col-md-2 pl-0 btn-download">
                                                <button type="button" class="btn btn_darkblack" data-toggle="modal" data-target="#interactionModal">
                                                    <i class="fas fa-book"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endauth
                        <!-- Locations area design -->
                        @if (count($locations) > 0)
                        <div class="card">
                            <div class="card-block p-0">
                                <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
                                </div>
                            </div>
                        </div>
                        @endif
                        @auth
                            <div class="card ">
                                <div class="card-block">
                                    <h4 class="card_services_title ">Change Log</h4>
                                    @foreach ($serviceAudits as $item)
                                        @if (count($item->new_values) != 0)
                                            <div class="py-10" style="float: left; width:100%;border-bottom: 1px solid #dadada;">

                                                @foreach ($item->old_values as $key => $v)
                                                    @php
                                                        $fieldNameArray = explode('_', $key);
                                                        $fieldName = implode(' ', $fieldNameArray);
                                                        $new_values = explode('| ', $item->new_values[$key]);
                                                        $old_values = explode('| ', $v);
                                                        $old_values = array_values(array_filter($old_values));
                                                        $new_values = array_values(array_filter($new_values));
                                                        // dd($old_values,$new_values);
                                                    @endphp
                                                    @if ((count($old_values) > 0 && count($new_values) > 0) || $item->new_values[$key] || ($v && count($old_values) > count($new_values)) || ($v && count($new_values) == count($old_values)))
                                                        @if($key == 0)
                                                        <p class="mb-5" style="color: #000;font-size: 16px;">On
                                                            <a href="/viewChanges/{{ $item->id }}/{{ $service->service_recordid }}"  style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;"><b style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">{{ $item->created_at }}</b></a>,
                                                            @if ($item->user)
                                                                <a href="/userEdits/{{ $item->user ? $item->user->id : '' }}/0" style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">
                                                                    <b style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '' }}</b>
                                                                </a>
                                                            @endif
                                                        </p>
                                                        @endif
                                                    <ul style="padding-left: 0px;font-size: 16px;">
                                                        @if ($v && count($old_values) > count($new_values))
                                                            @php
                                                                $diffData = array_diff($old_values, $new_values);
                                                            @endphp
                                                            <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                                Removed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                                <span style="color: #FF5044">{{ implode(' | ', $diffData) }}</span>
                                                            </li>
                                                        @elseif($v && count($old_values) < count($new_values))
                                                         @php
                                                            $diffData = array_diff($new_values, $old_values);
                                                        @endphp
                                                        @if (count($diffData) > 0)
                                                        <li style="color: #000;list-style: disc;list-style-position: inside;"> Added
                                                            <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span style="color: #35AD8B">{{ implode(' | ', $diffData) }}</span>
                                                        </li>
                                                        @endif
                                                        @elseif($v && count($new_values) == count($old_values))
                                                            @php
                                                                $diffData = array_diff($new_values, $old_values);
                                                            @endphp
                                                            @if (count($diffData) > 0)
                                                            <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                                Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                                <span style="color: #35AD8B">{{ implode(' | ', $diffData) }}</span>
                                                            </li>
                                                            @endif
                                                        @elseif($item->new_values[$key])
                                                            <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                                Added <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                                <span style="color: #35AD8B">{{ $item->new_values[$key] }}</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    @endif
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
        {{-- service tag modal --}}
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"
        id="create_new_service_tag">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/createNewServiceTag/{{ $service->id }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <button type="button" class="close serviceTagCloseButton" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Add New Service Tag</h4>
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
                                class="btn btn-danger btn-lg btn_delete red_btn serviceTagCloseButton">Close</button>
                            <button type="submit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End here --}}
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
                    <form action="/addInteractionService" method="POST">
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
                                        <input type="hidden" name="service_recordid"
                                            value="{{ $service->service_recordid }}">
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes: </label>
                                            <input class="form-control selectpicker" type="text" id="interaction_notes" name="interaction_notes" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Update Status: </label>
                                            {!! Form::select('service_status', $serviceStatus, $service->service_status ? $service->service_status :( $layout->default_service_status ?? null), ['class' => 'form-control selectpicker', 'id' => 'service_status', 'placeholder' => 'Select Status']) !!}
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
        <script>
            $(document).ready(function() {
                // navigator.geolocation.getCurrentPosition(showPosition)

                $('#service_tag').change(function() {
                let val = $(this).val()
                // console.log($.inArray('create_new', val))
                if ($.inArray('create_new', val) != -1) {
                    $('#create_new_service_tag').modal('show')
                } else {
                    let id = "{{ $service->id }}"
                    let _token = "{{ csrf_token() }}"
                    $.ajax({
                        url: '{{ route('addServiceTag') }}',
                        method: 'post',
                        data: {
                            val, id,_token
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
            $('.serviceTagCloseButton').click(function() {
                let value = $('#service_tag').val()
                value.pop()
                $('#service_tag').val(value);
                $('#service_tag').selectpicker('refresh')
                $('#create_new_service_tag').modal('hide')
            })

                setTimeout(function() {
                    var locations = <?php print_r(json_encode($locations)); ?>;
                    var maplocation = <?php print_r(json_encode($map)); ?>;


                    var show = 1;
                    if (locations.length == 0) {
                        show = 0;
                    }

                    if (maplocation.active == 1) {
                        avglat = maplocation.lat;
                        avglng = maplocation.long;
                        zoom = maplocation.zoom_profile;
                    } else {
                        avglat = 40.730981;
                        avglng = -73.998107;
                        zoom = 10
                    }

                    latitude = null;
                    longitude = null;

                    if (locations[0]) {
                        latitude = locations[0].location_latitude;
                        longitude = locations[0].location_longitude;
                    }
                    if (latitude == null) {
                        latitude = avglat;
                        longitude = avglng;
                    }

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: zoom,
                        center: {
                            lat: parseFloat(latitude),
                            lng: parseFloat(longitude)
                        }
                    });

                    var latlongbounds = new google.maps.LatLngBounds();
                    var markers = locations.map(function(location, i) {

                        var position = {
                            lat: location.location_latitude,
                            lng: location.location_longitude
                        }
                        var latlong = new google.maps.LatLng(position.lat, position.lng);
                        latlongbounds.extend(latlong);

                        var content = '<div id="iw-container">' +
                            '<div class="iw-title"> <a href="#">' + location.service + '</a> </div>' +
                            '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + location.organization_recordid + '">' +
                            location.organization_name + '</a>';
                        // '<a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address_name + '" target="_blank">' + location.address_name +'</a>'+
                        if (location.address) {
                            for (i = 0; i < location.address.length; i++) {
                                content += '<div class="iw-subTitle">Address</div>' +
                                    location.address[i].address_1 + (location.address[i]
                                    .address_city ? ', ' + location.address[i]
                                    .address_city : '') +  (location.address[i].address_state_province ? ', ' + location.address[i].address_state_province : '') +
                                    (location.address[i].address_postal_code ? ', ' + location.address[i].address_postal_code : '');
                                content +=
                                    '<div><a href="https://www.google.com/maps/dir/?api=1&destination=' +
                                    location.address[i].address_1 + (location.address[i]
                                    .address_city ? ', ' + location.address[i]
                                    .address_city : '') + (location.address[i].address_state_province ? ', ' + location.address[i].address_state_province : '') +
                                    (location.address[i].address_postal_code ? ', ' + location.address[i].address_postal_code : '') +
                                    '" target="_blank">View on Google Maps</a></div>';
                            }
                        }
                        content += '</div>' +
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

                    // map.fitBounds(latlongbounds);
                    if (locations.length > 1) {
                        map.fitBounds(latlongbounds);
                    }

                }, 2000);


                $('.panel-link').on('click', function(e) {
                    if ($(this).hasClass('target-population-link') || $(this).hasClass(
                            'target-population-child'))
                        return;
                    var id = $(this).attr('at');
                    // console.log(id);
                    // $("#category_" +  id).prop( "checked", true );
                    // $("#checked_" +  id).prop( "checked", true );
                    selected_taxonomy_ids = id.toString();
                    $("#selected_taxonomies").val(selected_taxonomy_ids);
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
            $('#showHolidays').click(function() {
                $('#holidays').show();
                $('#hideHolidays').show();
                $(this).hide()
            })
            $('#hideHolidays').click(function() {
                $('#holidays').hide();
                $('#showHolidays').show();
                $(this).hide()
            })
        </script>
    @endsection
