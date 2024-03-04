@extends('layouts.app')
@section('title')
    Location Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="location_organization"],
    button[data-id="facility_service"],
    button[data-id="facility_schedules"],
    button[data-id="facility_details"],
    button[data-id="facility_service"],
    button[data-id="facility_address_city"],
    button[data-id="facility_address_state"],
    button[data-id="phone_type"],
    button[data-id="phone_language"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')
    <div class="top_header_blank"></div>
    <div class="inner_services">
        <div id="contacts-content" class="container">
            <div class="row">
                <!-- <div class="col-md-12">
                            <input type="hidden" id="checked_terms" name="checked_terms">
                        </div> -->
                <div class="col-md-8">
                    <h4 class="card-title title_edit mb-30">
                        Edit Location
                        {{-- <p class=" mb-30 ">A services that will improve your productivity</p> --}}
                    </h4>
                    {{-- <form action="/facility/{{$facility->location_recordid}}/update" method="GET"> --}}
                    {!! Form::model($facility, ['route' => ['facilities.update', $facility->location_recordid]]) !!}

                    <div class="card all_form_field ">
                        <div class="card-block">
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Name: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The name of the location (e.g., Northeast Center, Engine 18)</p>
                                            </div>
                                        </div>
                                        {!! Form::text('location_name', null, ['class' => 'form-control', 'id' => 'location_name']) !!}
                                        @error('location_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        {!! Form::select('location_organization', $organization_names, null, [
                                            'class' => 'form-control selectpicker',
                                            'id' => 'location_organization',
                                            'placeholder' => 'Select organization',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                        ]) !!}
                                        @error('location_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Alternate Name: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>An alternative name for the location</p>
                                            </div>
                                        </div>
                                        {!! Form::text('location_alternate_name', null, ['class' => 'form-control', 'id' => 'location_alternate_name']) !!}
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Transportation: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A description of the access to public or private transportation to and
                                                    from the location.</p>
                                            </div>
                                        </div>
                                        {!! Form::text('location_transportation',null,['class' => 'form-control','id' => 'location_transportation']) !!}
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Location Description: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A description of this location.</p>
                                            </div>
                                        </div>
                                        {!! Form::textarea('location_description', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Type: </label>
                                        {!! Form::text('location_type', null, ['class' => 'form-control', 'id' => 'location_type']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location URL: </label>
                                        {!! Form::text('location_url', null, ['class' => 'form-control', 'id' => 'location_url']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Languages: </label>
                                        {!! Form::select('location_languages[]', $languages, $location_languages_list, [
                                            'class' => 'form-control selectpicker',
                                            'id' => 'location_languages',
                                            'multiple' => 'true',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Transportation: </label>
                                        {!! Form::textarea('location_transportation', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Service: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Link to any services provided at the facility.</p>
                                            </div>
                                        </div>
                                        {!! Form::select('facility_service[]', $service_info_list, $facility_service_list, [
                                            'class' => 'form-control selectpicker',
                                            'id' => 'facility_service',
                                            'multiple' => 'true',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>External Identifier: </label>
                                        {!! Form::text('external_identifier', null, ['class' => 'form-control', 'id' => 'external_identifier']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>External Identifier Type: </label>
                                        {!! Form::text('external_identifier_type', null, ['class' => 'form-control', 'id' => 'external_identifier_type']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Accessibility Description: </label>
                                        {!! Form::textarea('location_description', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Accessibility URL: </label>
                                        {!! Form::text('accessesibility_url', null, ['class' => 'form-control', 'id' => 'accessesibility_url']) !!}
                                    </div>
                                </div>

                                @php
                                    $facilitySchedule = [];
                                    if (isset($facility->schedules)) {
                                        foreach ($facility->schedules as $value) {
                                            $facilitySchedule[] = $value->schedule_recordid;
                                        }
                                    }
                                @endphp

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ADA Compliant: </label>
                                        {!! Form::select(
                                            'accessibility_recordid',
                                            $accessibilities,
                                            null,
                                            [
                                                'class' => 'form-control selectpicker',
                                                'data-live-search' => 'true',
                                                'data-size' => '5',
                                                'id' => 'accessibility',
                                                'placeholder' => 'select accessibility',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Accessibility Details: </label>
                                        {!! Form::textarea('accessibility_details', null, [
                                            'class' => 'form-control',
                                            'id' => 'accessibility_details',
                                            'placeholder' => 'Accessibility Details',
                                        ]) !!}
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Details: </label>
                                    <input class="form-control selectpicker" type="text" id="location_details"
                                        name="location_details" value="{{$facility->location_details}}">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label>
                                    <ol id="phones-ul" class="row p-0 m-0" style="list-style: none;">
                                    @foreach ($facility->phones as $phone)
                                        <li class="facility-phones-li mb-2 col-md-4">
                                            <input class="form-control selectpicker facility_phones"  type="text" name="facility_phones[]" value="{{$phone->phone_number}}">
                                        </li>
                                        @endforeach
                                        </ol>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Schedule: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" id="facility_schedules"
                                        name="facility_schedules[]" data-size="5" >
                                        @foreach ($schedule_info_list as $key => $schedule_info)
                                        <option value="{{$schedule_info->schedule_recordid}}"
                                        {{in_array($schedule_info->schedule_recordid,$facilitySchedule) ? 'selected' : '' }}
                                        >{{$schedule_info->opens_at}} ~ {{$schedule_info->closes_at}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div> --}}


                                {{-- <!-- <div class="form-group">
                                                <label>Phone Number: </label>
                                                <input class="form-control selectpicker" type="text" id="facility_phones"
                                                    name="facility_phones" value="{{ $facility_phone_number }}">
                                                <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                            </div>
                                        </div> --> --}}

                                {{-- <!-- <div class="form-group">
                                                <label>Facility Address: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true" id="facility_address"
                                                    name="facility_address[]" data-size="5" >
                                                    @foreach ($address_info_list as $key => $address_info)
                                                    @if ($address_info->address_1)
                                                    <option value="{{ $address_info->address_recordid }}">{{ $address_info->address_1 }}, {{ $address_info->address_city }}, {{ $address_info->address_state_province }}, {{ $address_info->address_postal_code }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --> --}}
                                {{-- <div class="text-right col-md-12 mb-20">
                                <button type="button" class="btn btn_additional bg-primary-color" data-toggle="collapse" data-target="#demo">Additional Info
                                    <img src="/frontend/assets/images/white_arrow.png" alt="" title="" />
                                </button>
                            </div>
                            <div id="demo" class="collapse row m-0" style="width:100%">

                            </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card all_form_field">
                        <div class="card-block">
                            <h4 class="title_edit text-left mb-25 mt-10">
                                Addresses
                                <div class="d-inline float-right">
                                    <a href="javascript:void(0)" class="plus_delteicon bg-primary-color open_address_modal">
                                        <img src="/frontend/assets/images/plus.png" alt="" title="">
                                    </a>
                                </div>
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="display dataTable table-striped jambo_table table-bordered table-responsive" id="addressesTable">
                                                <thead>
                                                    <th>Full Address</th>
                                                    <th>Address Type</th>
                                                    <th>Primary </th>
                                                    <th style="width:60px">&nbsp;</th>
                                                </thead>
                                                <tbody id="addressTable">
                                                    @foreach ($locationAddresses as $key => $value)
                                                    <tr id="addressTr_{{ $key }}">
                                                        <td>
                                                            <a href="javascript:void(0)" class="edit_address_modal" data-id="{{ $key }}" >{{ $value->full_address }}</a>
                                                        </td>
                                                        <td>{{ $value->address_type_data }}</td>
                                                        <td>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input mainAddress" type="radio" name="mainAddress" id="mainAddress2_{{ $key }}" value="{{ $key }}" {{ ($value->is_main == 1 || count($locationAddresses) === 1 ) ? 'checked' : '' }} >
                                                                <label class="form-check-label" for="mainAddress2_{{ $key }}">
                                                                    {{-- <b style="color: #000">Primary</b> --}}
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td style="vertical-align: middle">
                                                            <a href="javascript:void(0)" class="plus_delteicon btn-button removeAddressData" data-id="{{ $key }}">
                                                                <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card all_form_field">
                        <div class="card-block">
                            <h4 class="title_edit text-left mb-25 mt-10">
                                Details
                                <div class="d-inline float-right" id="addDetailTr">
                                    <a href="javascript:void(0)" id="addDetailData" class="plus_delteicon bg-primary-color">
                                        <img src="/frontend/assets/images/plus.png" alt="" title="">
                                    </a>
                                </div>
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{-- <label>Location Details: </label>
                                    <div class="help-tip">
                                        <div><p>Description of assistance or infrastructure that facilitate access to clients with disabilities.</div>
                                        </p>
                                    </div> --}}
                                        <div class="">
                                            <table class="table table_border_none" id="DetailTable">
                                                <thead>
                                                    <th>Detail Type</th>
                                                    <th>Detail Term</th>
                                                    <th style="width:60px">&nbsp;</th>
                                                </thead>
                                                <tbody>
                                                    @if (count($locationDetails) > 0)
                                                        @foreach ($locationDetails as $key => $value)
                                                            <tr>
                                                                <td>
                                                                    {!! Form::select('detail_type[]', $detail_types, $value->detail_type, [
                                                                        'class' => 'form-control selectpicker detail_type',
                                                                        'placeholder' => 'select detail type',
                                                                        'id' => 'detail_type_' . $key,
                                                                    ]) !!}

                                                                </td>
                                                                <td class="create_btn">
                                                                    @php
                                                                        // $create_new = ['create_new' => 'Create New'];
                                                                        $detail_terms = \App\Model\Detail::where('detail_type', $value->detail_type)
                                                                            ->pluck('detail_value', 'detail_recordid')
                                                                            ->put( 'create_new', 'Create New');
                                                                        // $detail_terms = array_merge($create_new,$detail_terms);
                                                                        // dd($detail_terms);
                                                                    @endphp
                                                                    {!! Form::select('detail_term[]', $detail_terms, $value->detail_recordid, [
                                                                        'class' => 'form-control selectpicker detail_term',
                                                                        'placeholder' => 'select detail term',
                                                                        'id' => 'detail_term_' . $key,
                                                                    ]) !!}
                                                                    <input type="hidden" name="term_type[]"id="term_type_{{ $key }}" value="old">
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <a href="javascript:void(0)"
                                                                        class="plus_delteicon btn-button removePhoneData">
                                                                        <img src="/frontend/assets/images/delete.png"
                                                                            alt="" title="">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td>
                                                                {!! Form::select('detail_type[]', $detail_types, null, [
                                                                    'class' => 'form-control selectpicker detail_type',
                                                                    'placeholder' => 'select detail type',
                                                                    'id' => 'detail_type_0',
                                                                ]) !!}

                                                            </td>
                                                            <td class="create_btn">
                                                                {!! Form::select('detail_term[]', [], null, [
                                                                    'class' => 'form-control selectpicker detail_term',
                                                                    'placeholder' => 'select detail term',
                                                                    'id' => 'detail_term_0',
                                                                ]) !!}
                                                                <input type="hidden" name="term_type[]" id="term_type_0"
                                                                    value="old">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <a href="#" class="plus_delteicon btn-button">
                                                                    <img src="/frontend/assets/images/delete.png"
                                                                        alt="" title="">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr></tr>
                                                    @endif

                                                    {{-- <tr id="addDetailTr">
                                                    <td colspan="6" class="text-center">
                                                        <a href="javascript:void(0)" id="addDetailData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                                    </td>
                                                </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#phones-tab">
                                <h4 class="card_services_title">Phones
                                </h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#contacts-tab">
                                <h4 class="card_services_title">Contacts
                                </h4>
                            </a>
                        </li>
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="phones-tab">
                                    <div class="organization_services p-0">
                                        <h4 class="title_edit text-left mb-25 mt-10">
                                            Phones
                                            <div class="d-inline float-right">
                                                <a href="javascript:void(0)"
                                                    class="phoneModalOpenButton plus_delteicon bg-primary-color">
                                                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                </a>
                                            </div>
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="">
                                                        <table
                                                            class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                            cellspacing="0" width="100%" style="display: table;" id="PhoneTable">
                                                            <thead>
                                                                <th>Number</th>
                                                                <th>Extension</th>
                                                                <th style="width:200px;position:relative;">Type
                                                                    <div class="help-tip" style="top:8px;">
                                                                        <div>
                                                                            <p>Select “Main” if this is the organization's primary phone
                                                                                number (or
                                                                                leave blank)
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <th style="width:200px;">Language(s)</th>
                                                                <th style="width:200px;position:relative;">Description
                                                                    <div class="help-tip" style="top:8px;">
                                                                        <div>
                                                                            <p>A description providing extra information about the phone
                                                                                service (e.g.
                                                                                any special arrangements for accessing, or details of
                                                                                availability at
                                                                                particular times).
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <th>Main</th>
                                                                <th style="width:140px">&nbsp;</th>
                                                            </thead>
                                                            <tbody id="phonesTable">
                                                                @if (count($facility->phones) > 0)
                                                                    @foreach ($facility->phones as $key => $value)
                                                                        <tr id="phoneTr_{{ $key }}">
                                                                            <td>
                                                                                <input type="hidden" class="form-control"
                                                                                    name="facility_phones[]"
                                                                                    id="phone_number_{{ $key }}"
                                                                                    value="{{ $value->phone_number }}">
                                                                                {{ $value->phone_number }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" class="form-control"
                                                                                    name="phone_extension[]"
                                                                                    id="phone_extension_{{ $key }}"
                                                                                    value="{{ $value->phone_extension }}">
                                                                                {{ $value->phone_extension }}
                                                                            </td>
                                                                            <td>
                                                                                {{-- {!! Form::select('phone_type[]',$phone_type,$value->phone_type ?
                                                    explode(',',$value->phone_type) : [],['class' => 'form-control
                                                    selectpicker','data-live-search' => 'true','id' => 'phone_type','data-size' =>
                                                    5,'placeholder' => 'select phone type'])!!} --}}
                                                                                <input type="hidden" class="form-control"
                                                                                    name="phone_type[]"
                                                                                    id="phone_type_{{ $key }}"
                                                                                    value="{{ $value->phone_type }}">
                                                                                {{ $value->type ? $value->type->type : '' }}
                                                                            </td>
                                                                            <td>
                                                                                {{-- {!! Form::select('phone_language[]',$phone_languages,$value->phone_language ?
                                                    explode(',',$value->phone_language) : [],['class' => 'form-control selectpicker
                                                    phone_language','data-size' => 5,'data-live-search' => 'true', 'id' =>
                                                    'phone_language_'.$key,'multiple' => true]) !!} --}}
                                                                                <input type="hidden" class="form-control"
                                                                                    name="phone_language[]"
                                                                                    id="phone_language_{{ $key }}"
                                                                                    value="{{ $value->phone_language }}">
                                                                                {{ isset($phone_language_name[$key]) ? $phone_language_name[$key] : '' }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" class="form-control"
                                                                                    name="phone_description[]"
                                                                                    id="phone_description_{{ $key }}"
                                                                                    value="{{ $value->phone_description }}">
                                                                                {{ $value->phone_description }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-check form-check-inline"
                                                                                    style="margin-top: -10px;">
                                                                                    <input class="form-check-input " type="radio"
                                                                                        name="main_priority[]"
                                                                                        id="main_priority{{ $key }}"
                                                                                        value="{{ $key }}"
                                                                                        {{ $value->main_priority == 1 ? 'checked' : '' }}>
                                                                                    <label class="form-check-label"
                                                                                        for="main_priority{{ $key }}"></label>
                                                                                </div>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <a href="javascript:void(0)"
                                                                                    class="phoneEditButton plus_delteicon bg-primary-color">
                                                                                    <img src="/frontend/assets/images/edit_pencil.png"
                                                                                        alt="" title="">
                                                                                </a>
                                                                                <a href="javascript:void(0)"
                                                                                    class="plus_delteicon btn-button removeLocationPhoneData">
                                                                                    <img src="/frontend/assets/images/delete.png"
                                                                                        alt="" title="">
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="removePhoneDataId" id="removePhoneDataId">
                                            <input type="hidden" name="deletePhoneDataId" id="deletePhoneDataId">
                                            <input type="hidden" name="phone_language_data" id="phone_language_data" value="{{ $phone_language_data }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="contacts-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                {{-- contact table --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Contacts
                                                    {{-- <a class="contactModalOpenButton float-right plus_delteicon bg-primary-color">
                                                        <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                    </a> --}}
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                                    cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <th>Name</th>
                                                                        <th>Title</th>
                                                                        <th>Email</th>
                                                                        <th>Visibility</th>
                                                                        <th>Phone</th>
                                                                        {{-- <th style="width:100px">&nbsp;</th> --}}
                                                                    </thead>
                                                                    <tbody id="contactsTable">
                                                                        <tr>
                                                                            <td>amit solanki</td>
                                                                            <td>contact_title</td>
                                                                            <td>amit.d9ithub@gmail.com</td>
                                                                            <td>contact_visibility</td>
                                                                            <td>22111 22222</td>
                                                                            {{-- <td style="vertical-align:middle;">
                                                                                <a href="javascript:void(0)"
                                                                                    class="contactEditButton plus_delteicon bg-primary-color">
                                                                                    <img src="/frontend/assets/images/edit_pencil.png"
                                                                                        alt="" title="">
                                                                                </a>
                                                                                <a href="javascript:void(0)"
                                                                                    class="removeContactData plus_delteicon btn-button">
                                                                                    <img src="/frontend/assets/images/delete.png"
                                                                                        alt="" title="">
                                                                                </a>
                                                                            </td> --}}
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- end here --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button"
                            class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"
                            id="back-facility-btn"> Back</button>
                        <button type="button"
                            class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn"
                            id="delete-facility-btn" value="{{ $facility->location_recordid }}" data-toggle="modal"
                            data-target=".bs-delete-modal-lg"> Delete</button>
                        <button type="submit"
                            class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
                            id="save-facility-btn"> Save</button>

                    </div>
                    <input type="hidden" name="address_1[]" value="{{ $location_address_1_list }}" id="address_1">
                    <input type="hidden" name="address_2[]" value="{{ $location_address_2_list }}" id="address_2">
                    <input type="hidden" name="address_attention[]" value="{{ $location_address_attention_list }}" id="address_attention">
                    <input type="hidden" name="address_city[]" value="{{ $location_address_city_list }}" id="address_city">
                    <input type="hidden" name="address_regions[]" value="{{ $location_address_regions_list }}" id="address_regions">
                    <input type="hidden" name="address_state[]" value="{{ $location_address_state_list }}" id="address_state">
                    <input type="hidden" name="zip_codes[]" value="{{ $location_zip_codes_list }}" id="zip_codes">
                    <input type="hidden" name="address_country[]" value="{{ $location_address_country_list }}" id="address_country">
                    <input type="hidden" name="address_type[]" value="{{ $location_address_type_list }}" id="address_type">
                    {!! Form::close() !!}
                    {{-- </form> --}}
                </div>
                {{-- phone modal --}}
                @include('frontEnd.locations.locationPhone')
                {{-- phone modala close --}}
                <div class="col-md-4">
                    <h4 class="card-title title_edit mb-30"></h4>
                    <div class="card all_form_field mt-40">
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
                                                style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '' }}</b>
                                        </p>
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
                                                        Removed <b
                                                            style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                        <span style="color: #FF5044">{{ implode(',', $diffData) }}</span>
                                                    </li>
                                                @elseif($v && count($old_values) < count($new_values))
                                                    @php
                                                        $diffData = array_diff($new_values, $old_values);
                                                    @endphp
                                                    <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                        Added <b
                                                            style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                        <span style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                    </li>
                                                @elseif($v && count($new_values) == count($old_values))
                                                    @php
                                                        $diffData = array_diff($new_values, $old_values);
                                                    @endphp
                                                    <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                        Added <b
                                                            style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                        <span style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                    </li>
                                                    {{-- <li style="color: #000;list-style: disc;list-style-position: inside;">Changed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> from <span style="color: #FF5044">{{ $v }}</span> to <span style="color: #35AD8B">{{ $new_values ? $new_values : 'none' }}</span>
                    </li> --}}
                                                @elseif($item->new_values[$key])
                                                    <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                        Added <b
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
    <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/facility_delete_filter" method="POST" id="facility_delete_filter">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Delete Facility</h4>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" id="facility_recordid" name="facility_recordid">
                        <h4>Are you sure to delete this Facility?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"
                            class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                        <button type="button"
                            class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic"
                            data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- detail term modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="create_new_term">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close detailTermCloseButton"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Add Detail Term</h4>
                    </div>
                    <div class="modal-body all_form_field">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label
                                        style="margin-bottom:5px;font-weight:600;color: #000;letter-spacing: 0.5px;">Detail
                                        Term</label>
                                    <input type="text" class="form-control" placeholder="Detail Term"
                                        id="detail_term_p">
                                    <input type="hidden" name="detail_term_index_p" value=""
                                        id="detail_term_index_p">
                                    <span id="detail_term_error" style="display: none;color:red">Detail Term is
                                        required!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-danger btn-lg btn_delete red_btn detailTermCloseButton">Close</button>
                        <button type="button" id="detailTermSubmit"
                            class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End here --}}

    {{-- Address modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="address_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <button type="button" class="close closeAddressModal" id="">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Address</h4>
                    </div>
                    <div class="modal-body all_form_field">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address 1: </label>
                                    {!! Form::text('address_1_p', null, ['class' => 'form-control', 'id' => 'address_1_p']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address 2: </label>
                                    {!! Form::text('address_2_p', null, ['class' => 'form-control', 'id' => 'address_2_p']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Attention: </label>
                                    {!! Form::text('address_attention_p', null, ['class' => 'form-control', 'id' => 'address_attention_p']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City: </label>
                                    {!! Form::select('address_city_p', $address_city_list, null, [
                                        'class' => 'form-control selectpicker',
                                        'data-live-search' => 'true',
                                        'data-size' => '5',
                                        'id' => 'address_city_p',
                                        'placeholder' => 'select city',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State: </label>
                                    {!! Form::select('address_state_p', $address_states_list, null, [
                                        'class' => 'form-control selectpicker',
                                        'data-live-search' => 'true',
                                        'data-size' => '5',
                                        'id' => 'address_state_p',
                                        'placeholder' => 'select state',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Postal Code: </label>
                                    {!! Form::text('zip_code_p', null, ['class' => 'form-control', 'id' => 'zip_code_p']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Region: </label>
                                    {!! Form::select('region_p', $regions, null, [
                                        'class' => 'form-control selectpicker',
                                        'data-live-search' => 'true',
                                        'data-size' => '5',
                                        'id' => 'region_p',
                                        'multiple' => true,
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Country: </label>
                                    {!! Form::text('address_country_p', null, ['class' => 'form-control', 'id' => 'address_country_p']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address Type: </label>
                                    {{-- {!! Form::text('address_type_p', null, ['class' => 'form-control', 'id' => 'address_type_p']) !!} --}}
                                    {!! Form::select('address_type_p', $addressTypes, null, [
                                        'class' => 'form-control selectpicker',
                                        'data-live-search' => 'true',
                                        'data-size' => '5',
                                        'id' => 'address_type_p',
                                        'multiple' => true,
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-danger btn-lg btn_delete red_btn closeAddressModal" >Close</button>
                        <button type="button" id="addressModalSubmit"
                            class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Edit Address here --}}

    <script>
        let removePhoneDataId = []
        let deletePhoneDataId = []
        $('[data-toggle="tooltip"]').tooltip();
        $('#back-facility-btn').click(function() {
            history.go(-1);
            return false;
        });
        $('button.delete-td').on('click', function() {
            var value = $(this).val();
            $('input#facility_recordid').val(value);
        });
        $(document).on("change", 'div > .detail_type', function() {
            let value = $(this).val()
            let id = $(this).attr('id')
            let idsArray = id ? id.split('_') : []
            let index = idsArray.length > 0 ? idsArray[2] : ''
            console.log(value, index)
            if (value == '') {
                $('#detail_term_' + index).empty()
                $('#detail_term_' + index).val('')
                $('#detail_term_' + index).selectpicker('refresh')
                return false
            }
            $.ajax({
                url: '{{ route('getDetailTerm') }}',
                method: 'get',
                data: {
                    value
                },
                success: function(response) {
                    let data = response.data
                    $('#detail_term_' + index).empty()
                    $.each(data, function(i, v) {
                        $('#detail_term_' + index).append('<option value="' + i + '">' + v + '</option>');
                    })
                    $('#detail_term_' + index).append( '<option value="create_new">+ Create New</option>');
                    $('#detail_term_' + index).val('')
                    $('#detail_term_' + index).selectpicker('refresh')
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })
        $(document).on("change", 'div >.detail_term', function() {
            let value = $(this).val()
            let id = $(this).attr('id')
            let text = $("#" + id + " option:selected").text();
            let idsArray = id ? id.split('_') : []
            let index = idsArray.length > 0 ? idsArray[2] : ''

            if (value == 'create_new') {
                $('#detail_term_index_p').val(index)
                $('#create_new_term').modal('show')
            } else if (text == value) {
                $('#term_type_' + index).val('new')
            } else {
                $('#term_type_' + index).val('old')
            }
        })
        $('#detailTermSubmit').click(function() {
            if ($('#detail_term_p').val() == '') {
                $('#detail_term_error').show()
                setTimeout(() => {
                    $('#detail_term_error').hide()
                }, 5000);
                return false
            }
            let detail_term = $('#detail_term_p').val()
            let index = $('#detail_term_index_p').val()
            $('#term_type_' + index).val('new')
            $('#detail_term_' + index).append('<option value="' + detail_term + '">' + detail_term + '</option>');
            $('#detail_term_' + index).val(detail_term)
            $('#detail_term_' + index).selectpicker('refresh')
            $('#create_new_term').modal('hide')
            $('#detail_term_p').val('')
            $('#detail_term_index_p').val('')
        })
        $('.detailTermCloseButton').click(function() {

            let detail_term = $('#detail_term_p').val()
            let index = $('#detail_term_index_p').val()
            $('#term_type_' + index).val('old')
            $('#detail_term_' + index).val('');
            $('#detail_term_' + index).selectpicker('refresh')
            $('#create_new_term').modal('hide')
            $('#detail_term_p').val('')
            $('#detail_term_index_p').val('')
        })

        let d = {{ count($locationDetails) > 0 ? count($locationDetails) : 1 }}
        $('#addDetailTr').click(function() {
            $('#DetailTable').append('<tr><td><select name="detail_type[]" id="detail_type_' + d + '" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="detail_term[]" id="detail_term_' + d + '" class="form-control selectpicker detail_term"><option value="">Select Detail term</option> </select><input type="hidden" name="term_type[]" id="term_type_' + d + '" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            d++;
        })
        // $(document).ready(function(){
        //     $('#error_cell_phone').hide();
        //     $("#facilities-edit-content").submit(function(event){
        //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
        //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
        //         var facility_phones = $("#facility_phones").val();
        //         if (facility_phones != ''){
        //             if(mob.test(facility_phones) == false && facility_phones != 10){
        //                 $('#error_cell_phone').show();
        //                 event.preventDefault();
        //             }
        //         }

        //     });
        // });
        $(document).on('click', '.removePhoneData', function() {
            $(this).closest('tr').remove()
            let id = $(this).data('id')
            removePhoneDataId.push(id)
            $('#removePhoneDataId').val(removePhoneDataId)
            // $('#ConfirmMessage').empty();
            // $('#ConfirmMessage').append('<h4 style="">Remove this data from service.</h4>')
            // $('.bs-confirm-lg').modal('show');

        });
        $(document).on('click', '.deletePhoneData', function() {
            $(this).closest('tr').remove()
            let id = $(this).data('id')
            deletePhoneDataId.push(id)
            $('#deletePhoneDataId').val(deletePhoneDataId)
            // $('#ConfirmMessage').empty();
            // $('#ConfirmMessage').append('<h4 style="">Delete data permanently</h4>')
            // $('.bs-confirm-lg').modal('show');

        });
        // end

        $("#add-phone-input").click(function() {
            $("ol#phones-ul").append("<li class='facility-phones-li mb-2 col-md-4'>" +"<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>" +"</li>");
        });

        // address section
        let address_1 = $('#address_1').val() ? JSON.parse($('#address_1').val()) : [];
        let address_2 = $('#address_2').val() ? JSON.parse($('#address_2').val()) : [];
        let address_attention = $('#address_attention').val() ? JSON.parse($('#address_attention').val()) : [];
        let address_city = $('#address_city').val() ? JSON.parse($('#address_city').val()) : [];
        let regions = $('#regions').val() ? JSON.parse($('#regions').val()) : [];
        let address_state = $('#address_state').val() ? JSON.parse($('#address_state').val()) : [];
        let zip_codes = $('#zip_codes').val() ? JSON.parse($('#zip_codes').val()) : [];
        let address_country = $('#address_country').val() ? JSON.parse($('#address_country').val()) : [];
        let address_type = $('#address_type').val() ? JSON.parse($('#address_type').val()) : [];
        let index = address_1.length > 0 ? address_1.length : 0 ;
        let selectedIndex = ''
        let editAddressData = false
        let addressTypes = <?php echo(json_encode($addressTypes)) ?>;

        $('.open_address_modal').click(function() {
            $('#address_1_p').val('');
            $('#address_2_p').val('');
            $('#address_attention_p').val('');
            $('#address_city_p').val('');
            $('#region_p').val('');
            $('#address_state_p').val('');
            $('#zip_code_p').val('');
            $('#address_country_p').val('');
            $('#address_type_p').val('');
            $('#address_state_p').selectpicker('refresh')
            $('#address_city_p').selectpicker('refresh')
            $('#address_type_p').selectpicker('refresh')
            $('#region_p').selectpicker('refresh')
            $('#address_modal').modal('show')
        })

        $('.closeAddressModal').click(function() {
            editAddressData = false

            $('#address_1_p').val('');
            $('#address_2_p').val('');
            $('#address_attention_p').val('');
            $('#address_city_p').val('');
            $('#region_p').val('');
            $('#address_state_p').val('');
            $('#zip_code_p').val('');
            $('#address_country_p').val('');
            $('#address_type_p').val('');
            $('#address_modal').modal('hide')
            $('#address_city_p').selectpicker('refresh')
            $('#address_state_p').selectpicker('refresh')
            $('#address_type_p').selectpicker('refresh')
            $('#region_p').selectpicker('refresh')
        })

        $(document).on('click','.edit_address_modal', function() {
            selectedIndex = $(this).data('id')
            editAddressData = true
            // let address_type_array = address_type[selectedIndex] ? address_type[selectedIndex].split(',') : []

            $('#address_1_p').val(address_1[selectedIndex]);
            $('#address_2_p').val(address_2[selectedIndex]);
            $('#address_attention_p').val(address_attention[selectedIndex]);
            $('#address_city_p').val(address_city[selectedIndex]);
            $('#region_p').val(regions[selectedIndex]);
            $('#address_state_p').val(address_state[selectedIndex]);
            $('#zip_code_p').val(zip_codes[selectedIndex]);
            $('#address_country_p').val(address_country[selectedIndex]);
            $('#address_type_p').val(address_type[selectedIndex]);
            $('#address_city_p').selectpicker('refresh')
            $('#address_state_p').selectpicker('refresh')
            $('#address_type_p').selectpicker('refresh')
            $('#region_p').selectpicker('refresh')
            $('#address_modal').modal('show')
        })

        $('#addressModalSubmit').click(function() {
            let address_1_p = $('#address_1_p').val();
            let address_2_p = $('#address_2_p').val();
            let address_attention_p = $('#address_attention_p').val();
            let address_city_p = $('#address_city_p').val();
            let region_p = $('#region_p').val();
            let address_state_p = $('#address_state_p').val();
            let zip_code_p = $('#zip_code_p').val();
            let address_country_p = $('#address_country_p').val();
            let address_type_p = $('#address_type_p').val();
            let address_type_text = '';

            let address_type_ids = address_type_p ? address_type_p : []
            address_type_ids.forEach(function(v,i){
                if(v in addressTypes){
                    if(i == 0){
                        address_type_text = addressTypes[v]
                    }else{
                        address_type_text = address_type_text +' ,'+ addressTypes[v]
                    }
                }
            })
            // phone_type_text = phone_types[phone_type_p] ? phone_types[phone_type_p] : ''

            if(editAddressData) {
                $('#addressTr_' + selectedIndex).empty()
                $('#addressTr_' + selectedIndex).append('<td><a href="javascript:void(0)" class="edit_address_modal" data-id="'+ index +'" >'+ address_1_p + ' '+ address_2_p + ' '+ address_city_p +', '+ address_state_p + ' '+ zip_code_p +'</a><td>'+ address_type_text +'<td><div class="form-check form-check-inline"><input class="form-check-input mainAddress" id="mainAddress2_'+ index +'" name="mainAddress" type="radio" value="'+ index +'"> <label class="form-check-label" for="mainAddress2_'+ index +'"><b style="color:#000">Primary</b></label></div><td style="vertical-align:middle"><a href="javascript:void(0)" class="btn-button plus_delteicon removeAddressData" data-id="'+ index +'"><img alt="" src="/frontend/assets/images/delete.png"></a></td>');

                address_1[selectedIndex] = address_1_p;
                address_2[selectedIndex] = address_2_p;
                address_attention[selectedIndex] = address_attention_p;
                address_city[selectedIndex] = address_city_p;
                regions[selectedIndex] = region_p;
                address_state[selectedIndex] = address_state_p;
                zip_codes[selectedIndex] = zip_code_p;
                address_country[selectedIndex] = address_country_p;
                address_type[selectedIndex] = address_type_p;

            }else{
                $('#addressTable').append('<tr id="addressTr_'+ index +'" ><td><a href="javascript:void(0)" class="edit_address_modal" data-id="'+ index +'" >'+ address_1_p + ' '+ address_2_p + ' '+ address_city_p +', '+ address_state_p + ' '+ zip_code_p +'</a><td>'+ address_type_text +'<td><div class="form-check form-check-inline"><input class="form-check-input mainAddress" id="mainAddress2_'+ index +'" name="mainAddress" type="radio" value="'+ index +'"> <label class="form-check-label" for="mainAddress2_'+ index +'"><b style="color:#000">Primary</b></label></div><td style="vertical-align:middle"><a href="javascript:void(0)" class="btn-button plus_delteicon removeAddressData" data-id="'+ index +'"><img alt="" src="/frontend/assets/images/delete.png"></a></td></tr>');

                index++;

                address_1.push(address_1_p);
                address_2.push(address_2_p);
                address_attention.push(address_attention_p);
                address_city.push(address_city_p);
                regions.push(region_p);
                address_state.push(address_state_p);
                zip_codes.push(zip_code_p);
                address_country.push(address_country_p);
                address_type.push(address_type_p);
            }

            $('#address_1').val(JSON.stringify(address_1))
            $('#address_2').val(JSON.stringify(address_2))
            $('#address_attention').val(JSON.stringify(address_attention))
            $('#address_city').val(JSON.stringify(address_city))
            $('#regions').val(JSON.stringify(regions))
            $('#address_state').val(JSON.stringify(address_state))
            $('#zip_codes').val(JSON.stringify(zip_codes))
            $('#address_country').val(JSON.stringify(address_country))
            $('#address_type').val(JSON.stringify(address_type))

            selectedIndex = '';
            editAddressData = false;
            $('#address_modal').modal('hide');
        })

        $(document).on('click','.removeAddressData', function(){
            // let tr = $(this).closest('tr');

            let removeIndex = $(this).data('id');
            address_1.splice(removeIndex, 1);
            address_2.splice(removeIndex, 1);
            address_attention.splice(removeIndex, 1);
            address_city.splice(removeIndex, 1);
            regions.splice(removeIndex, 1);
            address_state.splice(removeIndex, 1);
            zip_codes.splice(removeIndex, 1);
            address_country.splice(removeIndex, 1);
            address_type.splice(removeIndex, 1);

            $('#address_1').val(JSON.stringify(address_1))
            $('#address_2').val(JSON.stringify(address_2))
            $('#address_attention').val(JSON.stringify(address_attention))
            $('#address_city').val(JSON.stringify(address_city))
            $('#regions').val(JSON.stringify(regions))
            $('#address_state').val(JSON.stringify(address_state))
            $('#zip_codes').val(JSON.stringify(zip_codes))
            $('#address_country').val(JSON.stringify(address_country))
            $('#address_type').val(JSON.stringify(address_type))


            $(this).closest('tr').remove()

        })

    </script>
@endsection
