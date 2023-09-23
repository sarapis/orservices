@extends('layouts.app')
@section('title')
    Organization Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="organization_services"],
    button[data-id="organization_contacts"],
    button[data-id="organization_phones"],
    button[data-id="organization_locations"],
    button[data-id="organization_website_rating"],
    button[data-id="phone_type"],
    button[data-id="phone_language"],
    button[data-id="service_organization"],
    button[data-id="service_status"],
    button[data-id="service_taxonomies"],
    button[data-id="service_schedules"],
    button[data-id="service_details"],
    button[data-id="service_address"],
    button[data-id="facility_organization"],
    button[data-id="facility_schedules"],
    button[data-id="facility_details"],
    button[data-id="facility_service"],
    button[data-id="facility_address_city"],
    button[data-id="facility_address_state"],
    button[data-id="contact_organization_name"],
    button[data-id="contact_service"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    .choose_file{
        background: #5051db;
    color: #fff;
    position: relative;
    height: 55px;
    border-radius: 12px;
    text-align: center;
    line-height: 55px;
    font-size: 15px;
    letter-spacing: 1px;
    font-weight: 600;
    }
    .choose_file .form-control{
        position: absolute;
        top: 0px;
        width: 100%;
        height: 100%;
        opacity: 0;
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
                        Edit Organization
                        {{-- <p class=" mb-30 ">A services that will improve your productivity</p> --}}
                    </h4>
                    {!! Form::model($organization, [ 'route' => ['organizations.update', $organization->organization_recordid], 'method' => 'PUT','enctype' => 'multipart/form-data']) !!}
                    <div class="card all_form_field">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Name </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The official or public name of the organization/agency.</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_name', null, ['class' => 'form-control', 'id' => 'organization_name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Parent Organization </label>
                                        {!! Form::select('parent_organization', $parent_organizations, null, [ 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-size' => '5', 'id' => 'parent_organization', 'placeholder' => 'Select parent organization', ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A brief summary about the organization.</p>
                                            </div>
                                        </div>
                                        {!! Form::textarea('organization_description', null, [ 'id' => 'organization_description', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none', ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The contact email address for the organization.</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_email', null, ['class' => 'form-control', 'id' => 'organization_email']) !!}
                                        @error('organization_email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>URL (website) </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The URL (website address) of the organization.</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_url', null, ['class' => 'form-control', 'id' => 'organization_url']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facebook URL </label>
                                        {!! Form::text('facebook_url', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Twitter URL </label>
                                        {!! Form::text('twitter_url', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Instagram URL </label>
                                        {!! Form::text('instagram_url', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>URI </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A government issued identifier used for tax administration (i.e., EIN, TIN).</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_tax_id', null, [ 'class' => 'form-control', 'id' => 'organization_tax_id', 'placeholder' => 'Ex. 12-3456789', ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year Incorporated </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The year in which the organization was legally formed</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_year_incorporated', null, [ 'class' => 'form-control', 'id' => 'organization_year_incorporated', ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Legal Status </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Type of organization</p>
                                            </div>
                                        </div>
                                        {!! Form::select( 'organization_legal_status', [ 'non-profit' => 'non-profit', 'private-corporation' => 'private corporation', 'government' => 'government', 'other' => 'other', ],null,['class' => 'form-control selectpicker','id' => 'organization_legal_status','placeholder' => 'Select Legal Status',]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Alternate Name </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Alternative or commonly used name for the organization.</p>
                                            </div>
                                        </div>
                                        {!! Form::text('organization_alternate_name', null, ['class' => 'form-control','id' => 'organization_alternate_name',]) !!}
                                    </div>
                                </div>

                                {{-- @if (Auth::user() && Auth::user()->roles->name == 'System Admin')
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Website Rating </label>
                                            {!! Form::select('organization_website_rating', $rating_info_list, null, [
                                                'class' => 'form-control selectpicker',
                                                'data-live-search' => 'true',
                                                'data-size' => '5',
                                                'id' => 'organization_website_rating',
                                                'placeholder' => 'Select website rating',
                                            ]) !!}
                                        </div>
                                    </div>
                                @endif --}}

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Logo </label>
                                        <div class="row" style="display:{{ $organization->logo ? 'block' : 'none'  }};" id="displayLogoDiv">
                                            <div class="col-md-12 col-sm-12">
                                                <img src="{{ $organization->logo }}" alt="" style="max-width: 100%; max-height: 80px;">
                                                <button type="button" class="btn choose_file" id="changeLogoButton" style="    border-radius: 12px;margin-left: 20px;">Change Logo</button>
                                            </div>
                                        </div>
                                        <div class="row" style="display:{{ $organization->logo ? 'none' : 'block'  }};" id="logoFormDiv">
                                            <div class="col-md-4 col-sm-12">
                                                <div class="mb-5 mt-5">
                                                    <input class="form-check-input logo_radio" type="radio" name="logo_type" id="inlineRadio1" value="file" checked>
                                                    <label class="form-check-label" for="inlineRadio1">Choose File</label>
                                                </div>
                                                <div class="mb-5">
                                                    <input class="form-check-input logo_radio" type="radio" name="logo_type" id="inlineRadio2" value="url">
                                                    <label class="form-check-label" for="inlineRadio2">Enter URL</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-7 col-sm-12">
                                                        <div class="choose_file logo_file">
                                                            {!! Form::file('logo', ['class' => 'form-control' ,'id' => 'chooseFile']) !!}
                                                            <p>Choose Logo</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-sm-12">
                                                        <img src="" alt="" style="max-width: 100%; max-height: 60px;display:none;" id="previewUplodedLogo">
                                                    </div>
                                                </div>
                                                <input class="form-control logo_url" type="text" placeholder="Enter URL" style="display:none;" name="logo" >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="text-right col-md-12 mb-20">
                                    <button type="button" class="btn btn_additional bg-primary-color"
                                        data-toggle="collapse" data-target="#demo">Additional Info
                                        <img src="/frontend/assets/images/white_arrow.png" alt="" title="" />
                                    </button>
                                </div> --}}
                                {{-- <div id="demo" class="collapse row m-0" style="width:100%"> --}}
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tax Status </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>Government assigned tax designation for tax-exempt organizations.</p>
                                                </div>
                                            </div>
                                            {!! Form::text('organization_tax_status', null, [
                                                'class' => 'form-control',
                                                'id' => 'organization_tax_status',
                                                'placeholder' => 'Ex. 501(c)(3)',
                                            ]) !!}
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Code </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>Internal code. If you don't know what it is please don't change it.
                                                    </p>
                                                </div>
                                            </div>
                                            {!! Form::text('organization_code', null, ['class' => 'form-control', 'id' => 'organization_code']) !!}
                                        </div>
                                    </div> --}}
                                    {{-- @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Organization's activity and/or verification status.</p>
                                            </div>
                                        </div>
                                        {!! Form::select('organization_status_x',$organizationStatus,null,['class' => 'form-control selectpicker','id' => 'organization_status_x','placeholder' => 'Select Status']) !!}
                                    </div>
                                </div>
                                @endif --}}
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#locations-tab">
                                <h4 class="card_services_title">Locations
                                </h4>
                            </a>
                        </li>
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
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#programs-tab">
                                <h4 class="card_services_title">Programs
                                </h4>
                            </a>
                        </li>
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="locations-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Locations
                                                    <a href="javascript:void(0)"
                                                        class="locationModalOpenButton plus_delteicon bg-primary-color float-right">
                                                        <img src="/frontend/assets/images/plus.png" alt=""
                                                            title="">
                                                    </a>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{-- <label>Locations: <a class="locationModalOpenButton"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                                            <div class="table-responsive">
                                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <th>Name</th>
                                                                        <th>Address</th>
                                                                        <th>City</th>
                                                                        <th>State</th>
                                                                        <th>Zipcode</th>
                                                                        <th>Phone</th>
                                                                        <th style="width:100px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody id="locationsTable">
                                                                        @foreach ($organization_locations_data as $key => $location)
                                                                            <tr id="locationTr_{{ $key }}">
                                                                                <td>{{ $location->location_name }}<input type="hidden" name="location_name[]" value="{{ $location->location_name }}" id="location_name_{{ $key }}">
                                                                                </td>
                                                                                <td>{{ $location->location_address }}<input type="hidden" name="location_address[]" value="{{ $location->location_address }}" id="location_address_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_city }}<input
                                                                                        type="hidden"
                                                                                        name="location_city[]"
                                                                                        value="{{ $location->location_city }}"
                                                                                        id="location_city_{{ $key }}">
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    {{ $location->location_state }}<input
                                                                                        type="hidden"
                                                                                        name="location_state[]"
                                                                                        value="{{ $location->location_state }}"
                                                                                        id="location_state_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_zipcode }}<input
                                                                                        type="hidden"
                                                                                        name="location_zipcode[]"
                                                                                        value="{{ $location->location_zipcode }}"
                                                                                        id="location_zipcode_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_phone }}<input
                                                                                        type="hidden"
                                                                                        name="location_phone[]"
                                                                                        value="{{ $location->location_phone }}"
                                                                                        id="location_phone_{{ $key }}">
                                                                                </td>
                                                                                <td style="vertical-align:middle;">
                                                                                    <a href="javascript:void(0)"
                                                                                        class="locationEditButton plus_delteicon bg-primary-color">
                                                                                        <img src="/frontend/assets/images/edit_pencil.png"
                                                                                            alt="" title="">
                                                                                    </a>
                                                                                    <a href="javascript:void(0)"
                                                                                        class="removeLocationData plus_delteicon btn-button">
                                                                                        <img src="/frontend/assets/images/delete.png"
                                                                                            alt="" title="">
                                                                                    </a>
                                                                                    <input type="hidden"
                                                                                        name="locationRadio[]"
                                                                                        value="existing"
                                                                                        id="selectedLocationRadio_{{ $key }}">
                                                                                    <input type="hidden" name="location_recordid[]"
                                                                                        value="{{ $location->location_recordid }}"
                                                                                        id="existingLocationIds_{{ $key }}">
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- location table end here --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="phones-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Phones
                                                    <div class="d-inline float-right">
                                                        <a href="javascript:void(0)"
                                                            class="phoneModalOpenButton plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt=""
                                                                title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    {{-- phone table --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{-- <label>Phones: </label> --}}
                                                            <div class="">
                                                                <table
                                                                    class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                                    cellspacing="0" width="100%" id="PhoneTable">
                                                                    {{-- <table class="table table_border_none"> --}}
                                                                    <thead>
                                                                        <th>Number</th>
                                                                        <th>Extension</th>
                                                                        <th style="width:200px;position:relative;">Type
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div>
                                                                                    <p>Select “Main” if this is the
                                                                                        organization's primary phone number
                                                                                        (or leave blank)
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                        <th style="width:200px;">Language(s)</th>
                                                                        <th style="width:200px;position:relative;">
                                                                            Description
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div>
                                                                                    <p>A description providing extra
                                                                                        information about the phone service
                                                                                        (e.g. any special arrangements for
                                                                                        accessing, or details of
                                                                                        availability at particular times).
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                        <th>Main</th>
                                                                        <th style="width:140px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody id="phonesTable">
                                                                        @if (count($organization->phones) > 0)
                                                                            @foreach ($organization->phones as $key => $value)
                                                                                <tr id="phoneTr_{{ $key }}">
                                                                                    <td>
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            name="organization_phones[]"
                                                                                            id="phone_number_{{ $key }}"
                                                                                            value="{{ $value->phone_number }}">
                                                                                        {{ $value->phone_number }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            name="phone_extension[]"
                                                                                            id="phone_extension_{{ $key }}"
                                                                                            value="{{ $value->phone_extension }}">
                                                                                        {{ $value->phone_extension }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            name="phone_type[]"
                                                                                            id="phone_type_{{ $key }}"
                                                                                            value="{{ $value->phone_type }}">
                                                                                        {{ $value->type ? $value->type->type : '' }}
                                                                                        {{-- {!! Form::select('phone_type[]',$phone_type,$value->phone_type ?
                                                                            explode(',',$value->phone_type) : [],['class' => 'form-control
                                                                            selectpicker','data-live-search' => 'true','id' => 'phone_type','data-size' =>
                                                                            5,'placeholder' => 'select phone type'])!!} --}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{-- {!! Form::select('phone_language[]',$phone_languages,$value->phone_language ?
                                                                            explode(',',$value->phone_language) : [],['class' => 'form-control selectpicker
                                                                            phone_language','data-size' => 5,'data-live-search' => 'true','id' =>
                                                                            'phone_language_'.$key,'multiple' => true]) !!} --}}
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            name="phone_language[]"
                                                                                            id="phone_language_{{ $key }}"
                                                                                            value="{{ $value->phone_language }}">
                                                                                        {{ isset($phone_language_name[$key]) ? $phone_language_name[$key] : '' }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden"
                                                                                            class="form-control"
                                                                                            name="phone_description[]"
                                                                                            id="phone_description_{{ $key }}"
                                                                                            value="{{ $value->phone_description }}"
                                                                                            readonly>
                                                                                        {{ $value->phone_description }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="form-check form-check-inline"
                                                                                            style="margin-top: -10px;">
                                                                                            <input
                                                                                                class="form-check-input "
                                                                                                type="radio"
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
                                                                                                alt=""
                                                                                                title="">
                                                                                        </a>
                                                                                        <a href="javascript:void(0)"
                                                                                            class="plus_delteicon btn-button removeOrganizationPhoneData">
                                                                                            <img src="/frontend/assets/images/delete.png"
                                                                                                alt=""
                                                                                                title="">
                                                                                        </a>
                                                                                        {{-- <a href="javascript:void(0)" class="plus_delteicon btn-button deletePhoneData" data-id="{{ $value->phone_recordid }}">
                                                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                            </a> --}}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                    <a
                                                        class="contactModalOpenButton float-right plus_delteicon bg-primary-color"><img
                                                            src="/frontend/assets/images/plus.png" alt=""
                                                            title=""></a>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{-- <label>Contacts:<a class="contactModalOpenButton"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label> --}}
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
                                                                        <th style="width:100px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody id="contactsTable">
                                                                        @foreach ($organizationContacts as $key => $contact)
                                                                            <tr id="contactTr_{{ $key }}">
                                                                                <td>{{ $contact->contact_name }}
                                                                                    <input type="hidden"
                                                                                        name="contact_name[]"
                                                                                        value="{{ $contact->contact_name }}"
                                                                                        id="contact_name_{{ $key }}">
                                                                                </td>

                                                                                <td>{{ $contact->contact_title }}
                                                                                    <input type="hidden"
                                                                                        name="contact_title[]"
                                                                                        value="{{ $contact->contact_title }}"
                                                                                        id="contact_title_{{ $key }}">
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    {{ $contact->contact_email }}
                                                                                    <input type="hidden"
                                                                                        name="contact_email[]"
                                                                                        value="{{ $contact->contact_email }}"
                                                                                        id="contact_email_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $contact->visibility }}
                                                                                    <input type="hidden"
                                                                                        name="contact_visibility[]"
                                                                                        value="{{ $contact->visibility }}"
                                                                                        id="contact_visibility_{{ $key }}">
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    {{ $contact->phone && count($contact->phone) > 0 ? $contact->phone[0]->phone_number : '' }}
                                                                                    <input type="hidden"
                                                                                        name="contact_phone[]"
                                                                                        value="{{ $contact->phone && count($contact->phone) > 0 ? $contact->phone[0]->phone_number : '' }}"
                                                                                        id="contact_phone_{{ $key }}">
                                                                                </td>
                                                                                <td style="vertical-align:middle;">
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
                                                                                    <input type="hidden"
                                                                                        name="contactRadio[]"
                                                                                        value="existing"
                                                                                        id="selectedContactRadio_{{ $key }}"><input
                                                                                        type="hidden"
                                                                                        name="contact_recordid[]"
                                                                                        value="{{ $contact->contact_recordid }}"
                                                                                        id="existingContactIds_{{ $key }}">
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
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
                                <div class="tab-pane" id="programs-tab">
                                    @include('frontEnd.organizations.organization_program')
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="removePhoneDataId" id="removePhoneDataId">
                    <input type="hidden" name="deletePhoneDataId" id="deletePhoneDataId">
                    <input type="hidden" name="phone_language_data" id="phone_language_data" value="{{ $phone_language_data }}">

                    <input type="hidden" name="service_alternate_name[]" id="service_alternate_name" value="{{ $service_alternate_name }}">
                    <input type="hidden" name="service_program[]" id="service_program" value="{{ $service_program }}">
                    <input type="hidden" name="service_status[]" id="service_status" value="{{ $service_status }}">
                    <input type="hidden" name="service_taxonomies[]" id="service_taxonomies" value="{{ $service_taxonomies }}">
                    <input type="hidden" name="service_application_process[]" id="service_application_process" value="{{ $service_application_process }}">
                    <input type="hidden" name="service_wait_time[]" id="service_wait_time" value="{{ $service_wait_time }}">
                    <input type="hidden" name="service_fees[]" id="service_fees" value="{{ $service_fees }}">
                    <input type="hidden" name="service_accreditations[]" id="service_accreditations" value="{{ $service_accreditations }}">
                    <input type="hidden" name="service_licenses[]" id="service_licenses" value="{{ $service_licenses }}">
                    <input type="hidden" name="service_schedules[]" id="service_schedules" value="{{ $service_schedules }}">
                    <input type="hidden" name="service_details[]" id="service_details" value="{{ $service_details }}">
                    <input type="hidden" name="service_address[]" id="service_address" value="{{ $service_address }}">
                    <input type="hidden" name="service_metadata[]" id="service_metadata" value="{{ $service_metadata }}">
                    <input type="hidden" name="service_airs_taxonomy_x[]" id="service_airs_taxonomy_x" value="{{ $service_airs_taxonomy_x }}">

                    <input type="hidden" name="location_alternate_name[]" id="location_alternate_name" value="{{ $location_alternate_name }}">
                    <input type="hidden" name="location_transporation[]" id="location_transporation" value="{{ $location_transporation }}">
                    <input type="hidden" name="location_service[]" id="location_service" value="{{ $location_service }}">
                    <input type="hidden" name="location_schedules[]" id="location_schedules" value="{{ $location_schedules }}">
                    <input type="hidden" name="location_description[]" id="location_description" value="{{ $location_description }}">
                    <input type="hidden" name="location_details[]" id="location_details" value="{{ $location_details }}">
                    <input type="hidden" name="location_accessibility[]" id="location_accessibility" value="{{ $location_accessibility }}">
                    <input type="hidden" name="location_accessibility_details[]" id="location_accessibility_details" value="{{ $location_accessibility_details }}">
                    <input type="hidden" name="location_regions[]" id="location_regions" value="{{ $location_regions }}">

                    <input type="hidden" name="contact_service[]" id="contact_service" value="{{ $contact_service }}">
                    <input type="hidden" name="contact_department[]" id="contact_department" value="{{ $contact_department }}">
                    {{-- <input type="hidden" name="contact_visibility[]" id="contact_visibility" value="{{ $contact_visibility }}"> --}}

                    {{-- contact phone --}}
                    <input type="hidden" name="contact_phone_numbers[]" id="contact_phone_numbers" value="{{ $contact_phone_numbers }}">
                    <input type="hidden" name="contact_phone_extensions[]" id="contact_phone_extensions" value="{{ $contact_phone_extensions }}">
                    <input type="hidden" name="contact_phone_types[]" id="contact_phone_types" value="{{ $contact_phone_types }}">
                    <input type="hidden" name="contact_phone_languages[]" id="contact_phone_languages" value="{{ $contact_phone_languages }}">
                    <input type="hidden" name="contact_phone_descriptions[]" id="contact_phone_descriptions" value="{{ $contact_phone_descriptions }}">
                    {{-- location phone --}}
                    <input type="hidden" name="location_phone_numbers[]" id="location_phone_numbers" value="{{ $location_phone_numbers }}">
                    <input type="hidden" name="location_phone_extensions[]" id="location_phone_extensions" value="{{ $location_phone_extensions }}">
                    <input type="hidden" name="location_phone_types[]" id="location_phone_types" value="{{ $location_phone_types }}">
                    <input type="hidden" name="location_phone_languages[]" id="location_phone_languages" value="{{ $location_phone_languages }}">
                    <input type="hidden" name="location_phone_descriptions[]" id="location_phone_descriptions" value="{{ $location_phone_descriptions }}">

                    {{-- schedule section --}}
                    <input type="hidden" name="opens_location_monday_datas" id="opens_location_monday_datas" value="{{ $opens_location_monday_datas }}">
                    <input type="hidden" name="closes_location_monday_datas" id="closes_location_monday_datas" value="{{ $closes_location_monday_datas }}">
                    <input type="hidden" name="schedule_closed_monday_datas" id="schedule_closed_monday_datas" value="{{ $schedule_closed_monday_datas }}">
                    <input type="hidden" name="opens_location_tuesday_datas" id="opens_location_tuesday_datas" value="{{ $opens_location_tuesday_datas }}">
                    <input type="hidden" name="closes_location_tuesday_datas" id="closes_location_tuesday_datas" value="{{ $closes_location_tuesday_datas }}">
                    <input type="hidden" name="schedule_closed_tuesday_datas" id="schedule_closed_tuesday_datas" value="{{ $schedule_closed_tuesday_datas }}">
                    <input type="hidden" name="opens_location_wednesday_datas" id="opens_location_wednesday_datas" value="{{ $opens_location_wednesday_datas }}">
                    <input type="hidden" name="closes_location_wednesday_datas" id="closes_location_wednesday_datas" value="{{ $closes_location_wednesday_datas }}">
                    <input type="hidden" name="schedule_closed_wednesday_datas" id="schedule_closed_wednesday_datas" value="{{ $schedule_closed_wednesday_datas }}">
                    <input type="hidden" name="opens_location_thursday_datas" id="opens_location_thursday_datas" value="{{ $opens_location_thursday_datas }}">
                    <input type="hidden" name="closes_location_thursday_datas" id="closes_location_thursday_datas" value="{{ $closes_location_thursday_datas }}">
                    <input type="hidden" name="schedule_closed_thursday_datas" id="schedule_closed_thursday_datas" value="{{ $schedule_closed_thursday_datas }}">
                    <input type="hidden" name="opens_location_friday_datas" id="opens_location_friday_datas" value="{{ $opens_location_friday_datas }}">
                    <input type="hidden" name="closes_location_friday_datas" id="closes_location_friday_datas" value="{{ $closes_location_friday_datas }}">
                    <input type="hidden" name="schedule_closed_friday_datas" id="schedule_closed_friday_datas" value="{{ $schedule_closed_friday_datas }}">
                    <input type="hidden" name="opens_location_saturday_datas" id="opens_location_saturday_datas" value="{{ $opens_location_saturday_datas }}">
                    <input type="hidden" name="closes_location_saturday_datas" id="closes_location_saturday_datas" value="{{ $closes_location_saturday_datas }}">
                    <input type="hidden" name="schedule_closed_saturday_datas" id="schedule_closed_saturday_datas" value="{{ $schedule_closed_saturday_datas }}">
                    <input type="hidden" name="opens_location_sunday_datas" id="opens_location_sunday_datas" value="{{ $opens_location_sunday_datas }}">
                    <input type="hidden" name="closes_location_sunday_datas" id="closes_location_sunday_datas" value="{{ $closes_location_sunday_datas }}">
                    <input type="hidden" name="schedule_closed_sunday_datas" id="schedule_closed_sunday_datas" value="{{ $schedule_closed_sunday_datas }}">

                    <input type="hidden" name="location_holiday_start_dates" id="location_holiday_start_dates" value="{{ $location_holiday_start_dates }}">
                    <input type="hidden" name="location_holiday_end_dates" id="location_holiday_end_dates" value="{{ $location_holiday_end_dates }}">
                    <input type="hidden" name="location_holiday_open_ats" id="location_holiday_open_ats" value="{{ $location_holiday_open_ats }}">
                    <input type="hidden" name="location_holiday_close_ats" id="location_holiday_close_ats" value="{{ $location_holiday_close_ats }}">
                    <input type="hidden" name="location_holiday_closeds" id="location_holiday_closeds" value="{{ $location_holiday_closeds }}">
                    <input type="hidden" name="interaction_method" id="interaction_method" value="">
                    <input type="hidden" name="interaction_disposition" id="interaction_disposition" value="">
                    <input type="hidden" name="interaction_notes" id="interaction_notes" value="">
                    <input type="hidden" name="organization_status" id="organization_status" value="">
                    <input type="hidden" name="reverify" id="reverify" value="">
                    <input type="hidden" name="organization_notes" id="organization_notes" value="">

                    <div class="col-md-12 text-center">
                        <a href="/organizations/{{ $organization->organization_recordid }}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-organization-btn "> Close</a>
                        <button type="button" class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn" id="delete-organization-btn " value="{{ $organization->organization_recordid }}" data-toggle="modal" data-target=".bs-delete-modal-lg"> Delete</button>
                        <button type="button" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
                            id="save-organization-btn" data-toggle="modal" data-target="#interactionModal"> Save</button>
                        <button type="submit" style="display: none;" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-organization-btn-submit"> Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-4">
                    <h4 class="card-title title_edit mb-30"></h4>
                    <div class="card all_form_field mt-40">
                        <div class="card-block">
                            <h4 class="card_services_title mb-20">Change Log</h4>
                            @foreach ($organizationAudits as $item)
                                @if (count($item->new_values) != 0)
                                    <div class="py-10" style="float: left; width:100%;border-bottom: 1px solid #dadada;">
                                        <p class="mb-5" style="color: #000;font-size: 16px;">On
                                            <a href="/viewChanges/{{ $item->id }}/{{ $organization->organization_recordid }}"
                                                style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">
                                                <b
                                                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">{{ $item->created_at }}</b>
                                            </a>
                                            ,
                                            @if ($item->user)
                                                <a href="/userEdits/{{ $item->user ? $item->user->id : '' }}/0"
                                                    style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">
                                                    <b
                                                        style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '' }}</b>
                                                </a>
                                            @endif
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
                                                    @if (count($diffData) > 0)
                                                        <li
                                                            style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added <b
                                                                style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span
                                                                style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                        </li>
                                                    @endif
                                                @elseif($v && count($new_values) == count($old_values))
                                                    @php
                                                        $diffData = array_diff($new_values, $old_values);
                                                    @endphp
                                                    @if (count($diffData) > 0)
                                                        <li
                                                            style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added <b
                                                                style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span
                                                                style="color: #35AD8B">{{ implode(',', $diffData) }}</span>
                                                        </li>
                                                    @endif
                                                @elseif($item->new_values[$key])
                                                    <li style="color: #000;list-style: disc;list-style-position: inside;">
                                                        Added <b
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
                </div>
            </div>

            {{-- phone modal --}}
            @include('frontEnd.organizations.organizationPhone')
            {{-- phone modala close --}}
            {{-- services Modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="servicemodal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close serviceCloseButton"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add services</h4>
                            </div>
                            <div class="modal-body all_form_field ">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input serviceRadio" type="radio" name="serviceRadio"
                                            id="serviceRadio2" value="new_data" checked>
                                        <label class="form-check-label" for="serviceRadio2"><b style="color: #000">Create
                                                New Service</b></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input serviceRadio" type="radio" name="serviceRadio"
                                            id="serviceRadio1" value="existing">
                                        <label class="form-check-label" for="serviceRadio1"><b style="color: #000">Use
                                                Existing Service</b></label>
                                    </div>
                                </div>
                                <div class="" id="existingServiceData" style="display: none;">
                                    <select name="services" id="serviceSelectData" class="form-control">
                                        <option value="">Select Services</option>
                                        @foreach ($all_services as $service)
                                            <option value="{{ $service }}"
                                                data-id="{{ $service->service_recordid }}">
                                                {{ $service->service_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="newServiceData">
                                    {{--
                                <div class="form-group mb-10">
                                    <label>Description</label>
                                    <input type="text" class="form-control" placeholder="Description" id="service_description_p">
                                </div>
                                <div class="form-group mb-10">
                                    <label>URL</label>
                                    <input type="text" class="form-control" placeholder="URL" id="service_url_p">
                                </div>
                                <div class="form-group mb-10">
                                    <label>Email</label>
                                    <input type="text" class="form-control" placeholder="Email" id="service_email_p">
                                </div>
                                 --}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Name: </label>
                                                <input class="form-control " type="text" id="service_name_p"
                                                    name="service_name" value="">
                                                <span id="service_name_error" style="display: none;color:red">Service Name
                                                    is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Alternate Name: </label>
                                                <input class="form-control" type="text" id="service_alternate_name_p"
                                                    name="service_alternate_name" value="">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Organization Name: </label>
                                            <select class="form-control" data-live-search="true" id="service_organization" name="service_organization" data-size="5" >
                                                <option value="1">Academy of Hope</option>
                                                <option value="1">CENTER FOR SOCIAL JUSTICE: D.C. READS</option>
                                                <option value="1">Nativity Shelter</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Description: </label>
                                                <input class="form-control" type="text" id="service_description_p"
                                                    name="service_description" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service URL: </label>
                                                <input class="form-control" type="text" id="service_url_p"
                                                    name="service_url" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Program: </label>
                                                <input class="form-control" type="text" id="service_program_p"
                                                    name="service_program" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Email: </label>
                                                <input class="form-control" type="text" id="service_email_p"
                                                    name="service_email" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Status(Verified): </label>
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="service_status_p" name="service_status" data-size="5">
                                                    <option value="">Select status</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Taxonomies: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true"
                                                    id="service_taxonomies_p" name="service_taxonomies[]" data-size="5">
                                                    @foreach ($taxonomy_info_list as $key => $taxonomy_info)
                                                        <option value="{{ $taxonomy_info->taxonomy_recordid }}">
                                                            {{ $taxonomy_info->taxonomy_name }}</option>
                                                        @foreach ($taxonomy_info->taxonomyArray as $item)
                                                            <option value="{{ $item->taxonomy_recordid }}">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-
                                                                {{ $taxonomy_info->taxonomy_name . ' : ' . $item->taxonomy_name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Application Process: </label>
                                                <input class="form-control" type="text"
                                                    id="service_application_process_p" name="service_application_process"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Wait Time: </label>
                                                <input class="form-control" type="text" id="service_wait_time_p"
                                                    name="service_wait_time" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Fees: </label>
                                                <input class="form-control" type="text" id="service_fees_p"
                                                    name="service_fees" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Accrediations: </label>
                                                <input class="form-control" type="text" id="service_accreditations_p"
                                                    name="service_accreditations" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Licenses: </label>
                                                <input class="form-control" type="text" id="service_licenses_p"
                                                    name="service_licenses" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Schedule: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true"
                                                    id="service_schedules_p" name="service_schedules[]" data-size="5">
                                                    @foreach ($schedule_info_list as $key => $schedule_info)
                                                        <option value="{{ $schedule_info->schedule_recordid }}">
                                                            {{ $schedule_info->opens }} ~
                                                            {{ $schedule_info->closes }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Details: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true"
                                                    id="service_details_p" name="service_details[]" data-size="5">
                                                    @foreach ($detail_info_list as $key => $detail_info)
                                                        <option value="{{ $detail_info->detail_recordid }}">
                                                            {{ $detail_info->detail_value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service Address: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true"
                                                    id="service_address_p" name="service_address[]" data-size="5">
                                                    @foreach ($address_info_list as $key => $address_info)
                                                        @if ($address_info->address_1)
                                                            <option value="{{ $address_info->address_recordid }}">
                                                                {{ $address_info->address_1 }},
                                                                {{ $address_info->address_city }},
                                                                {{ $address_info->address_state_province }},
                                                                {{ $address_info->address_postal_code }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Meta Data: </label>
                                                <input class="form-control" type="text" id="service_metadata_p"
                                                    name="service_metadata" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Airs Taxonomy X: </label>
                                                <input class="form-control" type="text" id="service_airs_taxonomy_x_p"
                                                    name="service_airs_taxonomy_x" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" class="form-control" placeholder="Phone"
                                                    id="service_phone_p">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-danger btn-lg btn_delete red_btn serviceCloseButton">Close</button>
                                <button type="button" id="serviceSubmit"
                                    class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- location Modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="locationmodal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close locationCloseButton"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Locations</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input locationRadio" type="radio" name="locationRadio"
                                            id="locationRadio2" value="new_data" checked>
                                        <label class="form-check-label" for="locationRadio2"><b
                                                style="color: #000">Create New Location</b></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input locationRadio" type="radio" name="locationRadio"
                                            id="locationRadio1" value="existing">
                                        <label class="form-check-label" for="locationRadio1"><b style="color: #000">Use
                                                Existing Location</b></label>
                                    </div>
                                </div>
                                <div class="" id="existingLocationData" style="display: none;">
                                    <select name="locations" id="locationSelectData" class="form-control">
                                        <option value="">Select Locations</option>
                                        @foreach ($all_locations as $location)
                                            <option value="{{ $location }}"
                                                data-id="{{ $location->location_recordid }}">
                                                {{ $location->location_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="newLocationData">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Location Name:</label>
                                                <input class="form-control selectpicker" type="text"
                                                    id="location_name_p" name="location_name" value="">
                                                <span id="location_name_error" style="display: none;color:red">Location
                                                    Name is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Location Alternate Name: </label>
                                                <input class="form-control selectpicker" type="text"
                                                    id="location_alternate_name_p" name="location_alternate_name"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Location Transportation: </label>
                                                <input class="form-control selectpicker" type="text"
                                                    id="location_transporation_p" name="location_transporation"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Location Description: </label>
                                                <textarea id="location_description_p" name="location_description" class="form-control selectpicker" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Location Service: </label>
                                                <select class="form-control selectpicker" multiple data-live-search="true"
                                                    id="location_service_p" name="location_service[]" data-size="8">
                                                    @foreach ($service_info_list as $key => $service_info)
                                                        <option value="{{ $service_info->service_recordid }}">
                                                            {{ $service_info->service_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Address"
                                                    id="location_address_p">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City: </label>
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="location_city_p" name="location_city" , data-size="5">
                                                    <option value="">Select city</option>
                                                    @foreach ($address_city_list as $key => $address_city)
                                                        <option value="{{ $address_city }}">{{ $address_city }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State: </label>
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="location_state_p" name="location_state" , data-size="5">
                                                    <option value="">Select State</option>
                                                    @foreach ($address_states_list as $key => $address_state)
                                                        <option value="{{ $address_state }}">{{ $address_state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div v class="col-md-4">
                                            <div class="form-group">
                                                <label>Zip Code: </label>
                                                <input type="text" class="form-control" placeholder="Zipcode"
                                                    id="location_zipcode_p">
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Details: </label>
                                    <input class="form-control selectpicker" type="text" id="location_details_p"
                                        name="location_details" value="">
                                </div>
                            </div> --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Regions: </label>
                                                {!! Form::select('location_region_p', $regions, null, ['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'location_region_p','multiple' => true,]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>ADA Compliant: </label>
                                                {!! Form::select('location_accessibility_p',$accessibilities,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'location_accessibility_p','placeholder' => 'select accessibility',],) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Accessibility Details: </label>
                                                {!! Form::textarea(
                                                    'location_accessibility_details_p',
                                                    'Visitors with concerns about the level of access for specific physical conditions, are always recommended to contact the organization directly to obtain the best possible information about physical access',
                                                    ['class' => 'form-control', 'id' => 'location_accessibility_details_p', 'placeholder' => 'Accessibility Details'],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{-- <label>Phones: <a id="addDataLocation"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                            <h4 class="title_edit text-left mb-25 mt-10 px-20">Phones
                                                <a id="addDataLocation"
                                                    class="plus_delteicon bg-primary-color float-right"><img
                                                        src="/frontend/assets/images/plus.png" alt=""
                                                        title=""></a>
                                            </h4>
                                            <div class="col-md-12">
                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                cellspacing="0" width="100%" style="display: table" id="PhoneTableLocation">
                                                    <thead>
                                                        <th>Number</th>
                                                        <th>Extension</th>
                                                        <th style="width:200px;position:relative;">Type
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>Select “Main” if this is the organization's primary
                                                                        phone number (or leave blank) </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th style="width:200px;">Language(s)</th>
                                                        <th style="width:200px;position:relative;">Description
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>A description providing extra information about the
                                                                        phone service (e.g. any special arrangements for
                                                                        accessing, or details of availability at particular
                                                                        times). </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody id="addPhoneTrLocation">
                                                        <tr>
                                                            <td id="location_0">
                                                                <input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0">
                                                            </td>
                                                            <td>
                                                                {!! Form::select('phone_type[]',$phone_type,[],['class' => 'form-control selectpicker','data-live-search' => 'true','id' => 'phone_type_location_0','data-size' => 5,'placeholder' => 'select phone type',],) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('phone_language[]',$phone_languages,[],['class' => 'form-control selectpicker phone_language','data-size' => 5,'data-live-search' =>'true','multiple' => true,'id' => 'phone_language_location_0',],) !!}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="phone_description[]"
                                                                    id="phone_description_location_0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- schedule section --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{-- <label>Regular Schedule: </label> --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Regular Schedule
                                                </h4>
                                                <div class="table-responsive">
                                                    <table
                                                        class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                        cellspacing="0" width="100%">
                                                        {{-- <thead>
                                                        <th colspan="4" class="text-center">Regular Schedule</th>
                                                    </thead> --}}
                                                        <thead>
                                                            <th>Weekday</th>
                                                            <th>Opens</th>
                                                            <th>Closes</th>
                                                            <th>Closed All Day</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    Monday
                                                                    <input type="hidden" name="weekday" value="monday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_monday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_monday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_monday" value="1" id="schedule_closed_location_monday">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td> Tuesday
                                                                    <input type="hidden" name="weekday" value="tuesday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_tuesday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_tuesday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_tuesday" value="2" id="schedule_closed_location_tuesday">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Wednesday
                                                                    <input type="hidden" name="weekday"
                                                                        value="wednesday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_wednesday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, [ 'class' => 'form-control timePicker', 'id' => 'closes_location_wednesday', ]) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_wednesday" value="3" id="schedule_closed_location_wednesday">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thursday
                                                                    <input type="hidden" name="weekday"
                                                                        value="thursday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_thursday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_thursday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_thursday" value="4" id="schedule_closed_location_thursday">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Friday
                                                                    <input type="hidden" name="weekday" value="friday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_friday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_friday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_friday" id="schedule_closed_location_friday" value="5">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Saturday
                                                                    <input type="hidden" name="weekday" value="saturday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_saturday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_saturday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_saturday" id="schedule_closed_location_saturday" value="6">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sunday
                                                                    <input type="hidden" name="weekday" value="sunday">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('opens', null, ['class' => 'form-control timePicker', 'id' => 'opens_location_sunday']) !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('closes', null, ['class' => 'form-control timePicker', 'id' => 'closes_location_sunday']) !!}
                                                                </td>
                                                                <td style="vertical-align: middle">
                                                                    <input type="checkbox" name="schedule_closed_location_sunday" id="schedule_closed_location_sunday" value="7">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{-- <label class="p-0">Holiday Schedule: </label> --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Holiday Schedule
                                                </h4>
                                                {{-- <label>Holiday Schedule: <a id="addScheduleHolidayLocation"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label> --}}
                                                <div class="table-responsive">
                                                    <table
                                                        class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                        cellspacing="0" width="100%" id="">
                                                        <thead>
                                                            <th>Start</th>
                                                            <th>End</th>
                                                            <th>Opens</th>
                                                            <th>Closes</th>
                                                            <th>Closed All Day</th>
                                                            <th><a href="javascript:void(0)" id="addScheduleHolidayLocation" class="plus_delteicon bg-primary-color"><img src="/frontend/assets/images/plus.png" alt="" title=""></a></th>
                                                        </thead>
                                                        <tbody id="scheduleHolidayLocation">
                                                            <tr>
                                                                <td>
                                                                    <input type="date" name="holiday_start_date" id="holiday_start_date_location_0" class="form-control">
                                                                </td>
                                                                <td>
                                                                    <input type="date" name="holiday_end_date" id="holiday_end_date_location_0" class="form-control">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="holiday_open_at" id="holiday_open_at_location_0" class="form-control timePicker">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="holiday_close_at" id="holiday_close_at_location_0" class="form-control timePicker">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            {{-- <tr id="addTr">
                                                            <td colspan="6" class="text-center">
                                                                <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                                            </td>
                                                        </tr> --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end here --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-danger btn-lg btn_delete red_btn locationCloseButton">Close</button>
                                <button type="button" id="locationSubmit"
                                    class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- contact modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="contactmodal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close contactCloseButton"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Contacts</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input contactRadio" type="radio"
                                            name="contactRadio" id="contactRadio2" value="new_data" checked>
                                        <label class="form-check-label" for="contactRadio2"><b
                                                style="color: #000">Create New Contact</b></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input contactRadio" type="radio"
                                            name="contactRadio" id="contactRadio1" value="existing">
                                        <label class="form-check-label" for="contactRadio1"><b style="color: #000">Use
                                                Existing Contact</b></label>
                                    </div>

                                </div>
                                <div class="" id="existingContactData" style="display: none;">
                                    <select name="contacts" id="contactSelectData" class="form-control">
                                        <option value="">Select Contacts</option>
                                        @foreach ($contactOrganization as $contact)
                                            <option value="{{ $contact }}"
                                                data-id="{{ $contact->contact_recordid }}">
                                                {{ $contact->contact_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="newContactData">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" placeholder="Name"
                                                    id="contact_name_p">
                                                <span id="contact_name_error" style="display: none;color:red">Contact
                                                    Name is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Service: </label>
                                                <select class="form-control selectpicker" multiple
                                                    data-live-search="true" id="contact_service_p"
                                                    name="contact_service_p[]" data-size="8">
                                                    @foreach ($contactServices as $key => $service_info)
                                                        <option value="{{ $key }}">
                                                            {{ $service_info }}</option>
                                                    @endforeach
                                                </select>

                                                <span id="contact_service_error" style="display: none;color:red">Contact
                                                    service is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Title"
                                                    id="contact_title_p">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact Department: </label>
                                                <input class="form-control selectpicker" type="text"
                                                    id="contact_department_p" name="contact_department"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control" placeholder="Email"
                                                    id="contact_email_p">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Visibility: </label>
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="contact_visibility_p" name="contact_visibility_p[]"
                                                    data-size="8">
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h4 class="title_edit text-left mb-25 mt-10 px-20">
                                                Phones
                                                <a href="javascript:void(0)" id="addDataContact"
                                                    class="plus_delteicon bg-primary-color float-right">
                                                    <img src="/frontend/assets/images/plus.png" alt=""
                                                        title="">
                                                </a>
                                            </h4>
                                            {{-- <label>Phones: <a id="addDataContact"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                            <div class="col-md-12">
                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                cellspacing="0" width="100%" style="display: table" id="PhoneTableContact">
                                                    <thead>
                                                        <th>Number</th>
                                                        <th>Extension</th>
                                                        <th style="width:200px;position:relative;">Type
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>Select “Main” if this is the organization's primary
                                                                        phone number (or leave blank)
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th style="width:200px;">Language(s)</th>
                                                        <th style="width:200px;position:relative;">Description
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>A description providing extra information about the
                                                                        phone service (e.g. any special arrangements for
                                                                        accessing, or details of availability at particular
                                                                        times).
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody id="addPhoneTrContact">
                                                        <tr id="contact_0">
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="service_phones[]"
                                                                    id="service_phones_contact_0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="phone_extension[]"
                                                                    id="phone_extension_contact_0">
                                                            </td>
                                                            <td>
                                                                {!! Form::select(
                                                                    'phone_type[]',
                                                                    $phone_type,
                                                                    [],
                                                                    [
                                                                        'class' => 'form-control selectpicker',
                                                                        'data-live-search' => 'true',
                                                                        'id' => 'phone_type_contact_0',
                                                                        'data-size' => 5,
                                                                        'placeholder' => 'select phone type',
                                                                    ],
                                                                ) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select(
                                                                    'phone_language[]',
                                                                    $phone_languages,
                                                                    [],
                                                                    [
                                                                        'class' => 'form-control selectpicker',
                                                                        'data-size' => 5,
                                                                        ' data-live-search' => 'true',
                                                                        'id' => 'phone_language_contact_0',
                                                                        'multiple' => true,
                                                                    ],
                                                                ) !!}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="phone_description[]"
                                                                    id="phone_description_contact_0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-danger btn-lg btn_delete red_btn contactCloseButton">Close</button>
                                <button type="button" id="contactSubmit"
                                    class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- organization delete modal --}}
            <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                            </div>
                            <div class="modal-body text-left">
                                <input type="radio" name="detele_type" value="only_organization"
                                    class="form-check-input" id="only_organization" checked>
                                <label class="form-check-label" for="only_organization">
                                    <b>Delete only this organization</b>
                                </label>
                                <br>
                                <input type="radio" name="detele_type" class="form-check-input" id="delete_all"
                                    value="delete_all">
                                <label class="form-check-label" for="delete_all">
                                    Delete this
                                    organization as well as all of its services, locations and contacts
                                </label>

                                <input type="hidden" id="organization_recordid" name="organization_recordid">
                                <!-- <h4 class="text-center">Are you sure to delete this organization?</h4> -->
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
            {{-- end here --}}

            {{-- interaction modal --}}
            <div class="modal fade" id="interactionModal" tabindex="-1" role="dialog"
                aria-labelledby="interactionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="interactionModalLabel">Add Interaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body all_form_field">
                            {{ csrf_field() }}
                            <div id="facility-create-content" class="sesion_form">
                                <div class="row m-0">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Method: </label>
                                            <select class="form-control selectpicker" data-live-search="true"
                                                id="interaction_method_pop_up" name="interaction_method"
                                                data-size="5">
                                                @foreach ($method_list as $key => $method)
                                                    <option value="{{ $key }}">{{ $method }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Disposition: </label>
                                            <select class="form-control selectpicker" data-live-search="true"
                                                id="interaction_disposition_pop_up" name="interaction_disposition"
                                                data-size="5">
                                                @foreach ($disposition_list as $key => $disposition)
                                                    <option value="{{ $key }}">{{ $disposition }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Notes: </label>
                                            <input class="form-control" type="text" id="interaction_notes_pop_up"
                                                name="interaction_notes" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Update Status: </label>
                                            {!! Form::select(
                                                'organization_status',
                                                $organizationStatus,
                                                $organization->organization_status_x
                                                    ? $organization->organization_status_x
                                                    : $layout->default_organization_status ?? null,
                                                ['class' => 'form-control selectpicker', 'id' => 'organization_status_pop_up', 'placeholder' => 'Select Status'],
                                            ) !!}
                                        </div>
                                    </div>
                                    @if (isset($organizationStatus[$organization->organization_status_x]) &&
                                            $organizationStatus[$organization->organization_status_x] == 'Verified')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input reverify" type="checkbox"
                                                        name="reverify" value="1" id="reverify_pop_up">
                                                    <label class="form-check-label" for="reverify1"><b
                                                            style="color: #000">Reverify</b></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn waves-effect waves-classic" data-dismiss="modal" id="closeInteraction">Skip</button>
                            <button type="submit"
                                class="btn btn-primary btn-lg btn_padding green_btn waves-effect waves-classic"
                                id="submitInteraction"> Add</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end here --}}
        </div>
    </div>

    <script src="/js/jquery.timepicker.min.js"></script>
    <script>
        $('.timePicker').timepicker({
            'scrollDefault': 'now'
        });
        let removePhoneDataId = []
        let deletePhoneDataId = []
        let editContactData = false;
        let editServiceData = false;
        let selectedContactTrId = ''
        let editLocationData = false;
        let selectedLocationTrId = ''
        // array value
        let all_services_taxonomies = JSON.parse($('#service_taxonomies').val())
        let all_services_schedules = JSON.parse($('#service_schedules').val())
        let all_services_details = JSON.parse($('#service_details').val())
        let all_services_address = JSON.parse($('#service_address').val())
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function() {
            $("#organization_services").selectpicker("");
            $("#organization_contacts").selectpicker("");
            $("#organization_locations").selectpicker("");

            $('.logo_radio').change(function(){
                let value = $(this).val()
                if(value === 'file'){
                    $('.logo_file').show()
                    $('.logo_url').hide()
                }else{
                    $('.logo_file').hide()
                    $('.logo_url').show()
                }
                preview = document.getElementById('previewUplodedLogo');
                preview.style.display = 'none';
                preview.src = '';
            })
            $('#changeLogoButton').click(function(){
                $('#displayLogoDiv').remove()
                $('#logoFormDiv').show()
            })
            $('#chooseFile').change(function(e){
                preview = document.getElementById('previewUplodedLogo');
                preview.style.display = 'block';
                const [file] = e.target.files
                if (file) {
                    preview.src = URL.createObjectURL(file)
                }
            });
        });
        $('button.delete-td').on('click', function() {
            var value = $(this).val();
            $('input#organization_recordid').val(value);
        });

        $('#closeInteraction').click(function() {
            $('#save-organization-btn-submit').click();
        });
        $('#submitInteraction').click(function() {
            $('#interaction_method').val($('#interaction_method_pop_up').val());
            $('#interaction_disposition').val($('#interaction_disposition_pop_up').val());
            $('#interaction_notes').val($('#interaction_notes_pop_up').val());
            $('#organization_status').val($('#organization_status_pop_up').val());
            if ($('#reverify_pop_up').is(":checked")) {
                $('#reverify').val('1');
            }
            $('#organization_notes').val(1);
            $('#save-organization-btn-submit').click();
        });

        $("#add-location-input").click(function() {
            $("ol#other-locations-ul").append("<li class='organization-locations-li mb-2'>" + "<div class='col-md-12 col-sm-12 col-xs-12 organization-locations-div'>" + "<input class='form-control selectpicker organization_locations'  type='text' name='organization_locations[]'>" + "</div>" + "</li>");
        });

        // phone table section
        // let phone_language_data = JSON.parse($('#phone_language_data').val())
        // $(document).on('change','div > .phone_language',function () {
        //     let value = $(this).val()
        //     let id = $(this).attr('id')
        //     let idsArray = id ? id.split('_') : []
        //     let index = idsArray.length > 0 ? idsArray[2] : ''
        //     phone_language_data[index] = value
        //     console.log(phone_language_data)
        //     $('#phone_language_data').val(JSON.stringify(phone_language_data))
        // })
        // pt = {{ count($organization->phones) }}
        // $('#addPhoneTr').click(function(){
        //     $('#PhoneTable tr:last').before('<tr><td><input type="text" class="form-control" name="organization_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select('phone_type[]', $phone_type, [], [ 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'id' => 'phone_type', 'data-size' => 5, 'placeholder' => 'select phone type', ], ) !!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td><div class="form-check form-check-inline" style="margin-top: -10px;"><input class="form-check-input " type="radio" name="main_priority[]" id="main_priority'+pt+'" value="'+pt+'" ><label class="form-check-label" for="main_priority'+pt+'"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        //     $('.selectpicker').selectpicker();
        //     pt++;
        // })

        let cp = 1;
        $('#addDataContact').click(function() {
            $('#addPhoneTrContact').append('<tr id="contact_' + cp + '"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_' + cp + '"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_' + cp + '"></td><td><select name="phone_type[]" id="phone_type_contact_' + cp + '" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_' + cp + '" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_' + cp + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            cp++
        })
        let lp = 1;
        $('#addDataLocation').click(function() {
            $('#addPhoneTrLocation').append('<tr id="location_' + lp + '"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_' + lp + '"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_' + lp + '"></td><td><select name="phone_type[]" id="phone_type_location_' + lp + '" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_' + lp + '" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_' + lp + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            lp++
        })
        let ls = 1;
        $('#addScheduleHolidayLocation').click(function() {
            $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_' + ls + '"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_' + ls + '"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_' + ls + '"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_' + ls + '"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_' + ls + '" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            ls++;
            $('.timePicker').timepicker({
                'scrollDefault': 'now'
            });
        });
        $(document).on('click', '.removePhoneData', function() {
            // $(this).closest('tr').remove()
            let id = $(this).data('id')
            removePhoneDataId.push(id)
            $('#removePhoneDataId').val(removePhoneDataId)
            var $row = jQuery(this).closest('tr');
            let TrId = $row.attr('id')
            if (TrId) {
                let id_new = TrId.split('_');
                let id = id_new[1]
                let name = id_new[0]
                let deletedId = parseInt(id)
                if (name == 'contact') {
                    let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val());
                    let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val());
                    let contact_phone_types = JSON.parse($('#contact_phone_types').val());
                    let contact_phone_languages = JSON.parse($('#contact_phone_languages').val());
                    let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val());
                    if (contact_phone_numbers && contact_phone_numbers.length > 0) {
                        if (contact_phone_numbers) {
                            contact_phone_numbers[selectedContactTrId].splice(deletedId, 1)
                        }
                        contact_phone_extensions[selectedContactTrId].splice(deletedId, 1)
                        contact_phone_types[selectedContactTrId].splice(deletedId, 1)
                        contact_phone_languages[selectedContactTrId].splice(deletedId, 1)
                        contact_phone_descriptions[selectedContactTrId].splice(deletedId, 1)


                        $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
                        $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
                        $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
                        $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
                        $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))
                    }

                    $(this).closest('tr').remove()
                    let j = 0;
                    $('#addPhoneTrContact').each(function() {
                        var table = $(this);
                        table.find('tr').each(function(i) {
                            $(this).find('td').each(function(j) {
                                if (j == 0) {
                                    $(this).find('input').attr('id','service_phones_contact_' + i)
                                } else if (j == 1) {
                                    $(this).find('input').attr('id','phone_extension_contact_' + i)
                                } else if (j == 2) {
                                    $(this).find('select').attr('id','phone_type_contact_' + i)
                                    $('#phone_type_contact_' + i).selectpicker('refresh')
                                } else if (j == 3) {
                                    $(this).find('select').attr('id','phone_language_contact_' + i)
                                    $('#phone_language_contact_' + i).selectpicker('refresh')
                                } else if (j == 4) {
                                    $(this).find('input').attr('id','phone_description_contact_' + i)
                                }
                            })
                            $(this).attr("id", "contact_" + i)
                            j++
                        });
                        //Code here
                    });
                    cp = contact_phone_numbers[selectedContactTrId] ? contact_phone_numbers[selectedContactTrId]
                        .length : j
                } else if (name == 'location') {

                    let location_phone_numbers = JSON.parse($('#location_phone_numbers').val());
                    let location_phone_extensions = JSON.parse($('#location_phone_extensions').val());
                    let location_phone_types = JSON.parse($('#location_phone_types').val());
                    let location_phone_languages = JSON.parse($('#location_phone_languages').val());
                    let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val());
                    if (location_phone_numbers && location_phone_numbers.length > 0 && location_phone_numbers[selectedLocationTrId]) {
                        location_phone_numbers[selectedLocationTrId].splice(deletedId, 1)
                        location_phone_extensions[selectedLocationTrId].splice(deletedId, 1)
                        location_phone_types[selectedLocationTrId].splice(deletedId, 1)
                        location_phone_languages[selectedLocationTrId].splice(deletedId, 1)
                        location_phone_descriptions[selectedLocationTrId].splice(deletedId, 1)

                        $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
                        $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
                        $('#location_phone_types').val(JSON.stringify(location_phone_types))
                        $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
                        $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))

                    }
                    $(this).closest('tr').remove()
                    j = 0;
                    $('#addPhoneTrLocation').each(function() {
                        var table = $(this);
                        table.find('tr').each(function(i) {
                            $(this).find('td').each(function(j) {
                                if (j == 0) {
                                    $(this).find('input').attr('id','service_phones_location_' + i)
                                } else if (j == 1) {
                                    $(this).find('input').attr('id','phone_extension_location_' + i)
                                } else if (j == 2) {
                                    $(this).find('select').attr('id','phone_type_location_' + i)
                                    $('#phone_type_location_' + i).selectpicker('refresh')
                                } else if (j == 3) {
                                    $(this).find('select').attr('id','phone_language_location_' + i)
                                    $('#phone_language_location_' + i).selectpicker('refresh')
                                } else if (j == 4) {
                                    $(this).find('input').attr('id','phone_description_location_' + i)
                                }
                            })
                            $(this).attr("id", "location_" + i)
                            j++
                        });
                        //Code here
                    });
                    lp = location_phone_numbers[selectedLocationTrId] ? location_phone_numbers[selectedLocationTrId]
                        .length : j

                }

            } else {
                $(this).closest('tr').remove()
            }

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

        // contact section
        let contactRadioValue = 'new_data'
        $('.contactRadio').on('change', function() {
            let value = $(this).val()
            contactRadioValue = value
            if (value == 'new_data') {
                $('#newContactData').show()
                $('#existingContactData').hide()
            } else {
                $('#newContactData').hide()
                $('#existingContactData').show()
            }
        })
        let c = "{{ count($organizationContacts) }}";
        let contact_service = JSON.parse($('#contact_service').val())
        let contact_department = JSON.parse($('#contact_department').val())
        // let contact_visibility = JSON.parse($('#contact_visibility').val())

        let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val())
        let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val())
        let contact_phone_types = JSON.parse($('#contact_phone_types').val())
        let contact_phone_languages = JSON.parse($('#contact_phone_languages').val())
        let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val())

        $('#contactSubmit').click(function() {
            let contact_name_p = ''
            let contact_service_p = ''
            let contact_title_p = ''
            let contact_department_p = ''
            let contact_visibility_p = ''
            let contact_email_p = ''
            let contact_phone_p = ''
            let contact_recordid_p = ''
            if (contactRadioValue == 'new_data' && $('#contact_name_p').val() == '') {
                $('#contact_name_error').show()
                setTimeout(() => {
                    $('#contact_name_error').hide()
                }, 5000);
                return false
            }
            // if(contactRadioValue == 'new_data' && $('#contact_service_p').val() == ''){
            //         $('#contact_service_error').show()
            //     setTimeout(() => {
            //         $('#contact_service_error').hide()
            //     }, 5000);
            //     return false
            // }
            let phone_number_contact = []
            let phone_extension_contact = []
            let phone_type_contact = []
            let phone_language_contact = []
            let phone_description_contact = []
            if (contactRadioValue == 'new_data') {
                contact_name_p = $('#contact_name_p').val()
                contact_service_p = $('#contact_service_p').val()
                contact_title_p = $('#contact_title_p').val()
                contact_department_p = $('#contact_department_p').val()
                contact_visibility_p = $('#contact_visibility_p').val()
                contact_email_p = $('#contact_email_p').val()
                // contact_phone_p = $('#contact_phone_p').val()
                for (let index = 0; index < cp; index++) {
                    phone_number_contact.push($('#service_phones_contact_' + index).val())
                    phone_extension_contact.push($('#phone_extension_contact_' + index).val())
                    phone_type_contact.push($('#phone_type_contact_' + index).val())
                    phone_language_contact.push($('#phone_language_contact_' + index).val())
                    phone_description_contact.push($('#phone_description_contact_' + index).val())
                }

                // contactsTable
            } else {
                let data = JSON.parse($('#contactSelectData').val())
                contact_name_p = data.contact_name ? data.contact_name : ''
                contact_title_p = data.contact_title ? data.contact_title : ''
                contact_department_p = data.contact_department ? data.contact_department : ''
                contact_visibility_p = data.visibility ? data.visibility : ''
                contact_email_p = data.contact_email ? data.contact_email : ''
                contact_recordid_p = data.contact_recordid ? data.contact_recordid : ''
                let service_val = data.service && data.service.length > 0 ? data.service : []
                let serviceIds = service_val.map((v) => {
                    return v.service_recordid
                }).join(',');
                contact_service_p = serviceIds ? serviceIds.split(',') : []
                // contact_phone_p = data.phone && data.phone.length > 0 && data.phone[0].phone_number ? data.phone[0].phone_number : ''
                let phone = data.phone && data.phone.length > 0 && data.phone ? data.phone : []

                for (let index = 0; index < phone.length; index++) {
                    phone_number_contact.push(phone[index].phone_number)
                    phone_extension_contact.push(phone[index].phone_extension)
                    phone_type_contact.push(phone[index].phone_type)
                    phone_language_contact.push(phone[index].phone_language)
                    phone_description_contact.push(phone[index].phone_description)
                }

            }
            phone_number_contact = phone_number_contact.filter(function(element) {
                return element !== undefined;
            })
            let contact_phone_list = phone_number_contact.join(',')
            if (editContactData == false) {
                contact_service.push(contact_service_p)
                contact_department.push(contact_department_p)
                // contact_visibility.push(contact_visibility_p)

                contact_phone_numbers[c] = phone_number_contact
                contact_phone_extensions[c] = phone_extension_contact
                contact_phone_types[c] = phone_type_contact
                contact_phone_languages[c] = phone_language_contact
                contact_phone_descriptions[c] = phone_description_contact

                $('#contactsTable').append('<tr id="contactTr_' + c + '"><td>' + contact_name_p +'<input type="hidden" name="contact_name[]" value="' + contact_name_p + '" id="contact_name_' + c + '"></td><td>' + contact_title_p + '<input type="hidden" name="contact_title[]" value="' + contact_title_p + '" id="contact_title_' + c + '"></td><td class="text-center">' + contact_email_p + '<input type="hidden" name="contact_email[]" value="' + contact_email_p + '" id="contact_email_' + c + '"></td><td class="text-center">' + contact_visibility_p + '<input type="hidden" name="contact_visibility[]" value="' + contact_visibility_p + '" id="contact_visibility_' + c + '"></td><td class="text-center">' + contact_phone_list + '<input type="hidden" name="contact_phone[]" value="' + contact_phone_p + '" id="contact_phone_' + c + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeContactData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="' + contactRadioValue + '" id="selectedContactRadio_' + c + '"><input type="hidden" name="contact_recordid[]" value="' + contact_recordid_p + '" id="existingContactIds_' + c + '"></td></tr>');
                c++;
            } else {
                if (selectedContactTrId) {
                    contactRadioValue = $('#selectedContactRadio_' + selectedContactTrId).val()
                    contact_recordid_p = $('#existingContactIds_' + selectedContactTrId).val()
                    contact_service[selectedContactTrId] = contact_service_p
                    contact_department[selectedContactTrId] = contact_department_p
                    // contact_visibility[selectedContactTrId] = contact_visibility_p

                    contact_phone_numbers[selectedContactTrId] = phone_number_contact
                    contact_phone_extensions[selectedContactTrId] = phone_extension_contact
                    contact_phone_types[selectedContactTrId] = phone_type_contact
                    contact_phone_languages[selectedContactTrId] = phone_language_contact
                    contact_phone_descriptions[selectedContactTrId] = phone_description_contact

                    $('#contactTr_' + selectedContactTrId).empty()
                    $('#contactTr_' + selectedContactTrId).append('<td>' + contact_name_p + '<input type="hidden" name="contact_name[]" value="' + contact_name_p + '" id="contact_name_' + selectedContactTrId + '"></td><td>' + contact_title_p + '<input type="hidden" name="contact_title[]" value="' + contact_title_p + '" id="contact_title_' + selectedContactTrId + '"></td><td class="text-center">' + contact_email_p + '<input type="hidden" name="contact_email[]" value="' + contact_email_p + '" id="contact_email_' + selectedContactTrId + '"></td><td class="text-center">' + contact_visibility_p + '<input type="hidden" name="contact_visibility[]" value="' + contact_visibility_p + '" id="contact_visibility_' + selectedContactTrId + '"></td><td class="text-center">' + contact_phone_list + '<input type="hidden" name="contact_phone[]" value="' + contact_phone_p + '" id="contact_phone_' + selectedContactTrId + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeContactData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="' + contactRadioValue + '" id="selectedContactRadio_' + selectedContactTrId + '"><input type="hidden" name="contact_recordid[]" value="' + contact_recordid_p + '" id="existingContactIds_' + selectedContactTrId + '"></td>')
                }
            }
            $('#contact_service').val(JSON.stringify(contact_service))
            $('#contact_department').val(JSON.stringify(contact_department))
            // $('#contact_visibility').val(JSON.stringify(contact_visibility))

            $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
            $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
            $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
            $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
            $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))

            $('#contactSelectData').val('')
            $('#contact_name_p').val('')
            $('#contact_title_p').val('')
            $('#contact_email_p').val('')
            $('#contact_phone_p').val('')
            $('#contact_service_p').val('')
            $('#contact_department_p').val('')
            $('#contact_visibility_p').val('')

            $('#contact_service_p').selectpicker('refresh')

            $('#addPhoneTrContact').empty()
            $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');

            $('#contactmodal').modal('hide');

            cp = JSON.parse($('#contact_phone_numbers').val()).length;
            editContactData = false
            selectedContactTrId = ''
        })
        $(document).on('click', '.removeContactData', function() {
            var $row = jQuery(this).closest('tr');
            if (confirm("Are you sure want to remove this contact?")) {
                let contactTrId = $row.attr('id')
                let id_new = contactTrId.split('_');
                let id = id_new[1]
                let deletedId = id

                let contact_service_val = JSON.parse($('#contact_service').val())
                let contact_department_val = JSON.parse($('#contact_department').val())
                // let contact_visibility_val = JSON.parse($('#contact_visibility').val())

                // contact modal phone section
                let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val())
                let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val())
                let contact_phone_types = JSON.parse($('#contact_phone_types').val())
                let contact_phone_languages = JSON.parse($('#contact_phone_languages').val())
                let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val())

                contact_service_val.splice(deletedId, 1)
                contact_department_val.splice(deletedId, 1)
                // contact_visibility_val.splice(deletedId,1)
                contact_phone_numbers.splice(deletedId, 1)
                contact_phone_extensions.splice(deletedId, 1)
                contact_phone_types.splice(deletedId, 1)
                contact_phone_languages.splice(deletedId, 1)
                contact_phone_descriptions.splice(deletedId, 1)

                $('#contact_service').val(JSON.stringify(contact_service_val))
                $('#contact_department').val(JSON.stringify(contact_department_val))
                // $('#contact_visibility').val(JSON.stringify(contact_visibility_val))
                $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
                $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
                $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
                $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
                $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))
                $(this).closest('tr').remove()

                $('#contactsTable').each(function() {
                    var table = $(this);
                    table.find('tr').each(function(i) {
                        $(this).attr("id", "contactTr_" + i)
                    });
                    //Code here
                });
                s = contact_service_val.length
                cp = 1
            }
            return false

        });
        $(document).on('click', '.contactCloseButton', function() {
            editContactData = false
            $('#contactSelectData').val('')
            $('#contact_name_p').val('')
            $('#contact_title_p').val('')
            $('#contact_email_p').val('')
            $('#contact_phone_p').val('')
            $('#contact_service_p').val('')
            $('#contact_department_p').val('')
            $('#contact_visibility_p').val('')

            $('#contact_service_p').selectpicker('refresh')
            $('#contactmodal').modal('hide');
        });
        $(document).on('click', '.contactModalOpenButton', function() {
            $('#contactSelectData').val('')
            $('#contact_name_p').val('')
            $('#contact_title_p').val('')
            $('#contact_email_p').val('')
            $('#contact_phone_p').val('')
            $('#contact_service_p').val('')
            $('#contact_department_p').val('')
            $('#contact_visibility_p').val('public')

            $('#addPhoneTrContact').empty()
            $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');

            $('#contact_service_p').selectpicker('refresh')
            $('#contactmodal').modal('show');
        });
        $(document).on('click', '.contactEditButton', function() {
            editContactData = true
            var $row = jQuery(this).closest('tr');
            // var $columns = $row.find('td');
            // console.log()
            let contactTrId = $row.attr('id')
            let id_new = contactTrId.split('_');
            let id = id_new[1]
            selectedContactTrId = id

            $('#addPhoneTrContact').empty()
            $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');

            // $('.contactRadio').val()
            let radioValue = $("#selectedContactRadio_" + id).val();
            let contact_name_p = $('#contact_name_' + id).val()
            let contact_visibility_p = $('#contact_visibility_' + id).val()
            let contact_title_p = $('#contact_title_' + id).val()
            let contact_email_p = $('#contact_email_' + id).val()
            let contact_phone_p = $('#contact_phone_' + id).val()
            let contact_recordid_p = $('#existingContactIds_' + id).val()
            let contact_service_val = JSON.parse($('#contact_service').val())
            let contact_department_val = JSON.parse($('#contact_department').val())
            // let contact_visibility_val = JSON.parse($('#contact_visibility').val())

            // contact modal phone section
            let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val())
            let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val())
            let contact_phone_types = JSON.parse($('#contact_phone_types').val())
            let contact_phone_languages = JSON.parse($('#contact_phone_languages').val())
            let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val())


            let phone_number_contact = contact_phone_numbers[id]
            let phone_extension_contact = contact_phone_extensions[id]
            let phone_type_contact = contact_phone_types[id]
            let phone_language_contact = contact_phone_languages[id]
            let phone_description_contact = contact_phone_descriptions[id]

            $('#service_phones_contact_0').val(phone_number_contact[0])
            $('#phone_extension_contact_0').val(phone_extension_contact[0])
            $('#phone_type_contact_0').val(phone_type_contact[0])
            $('#phone_language_contact_0').val(phone_language_contact[0])
            $('#phone_description_contact_0').val(phone_description_contact[0])
            // let contact_phone_list = phone_number_contact[0]
            for (let index = 1; index < phone_number_contact.length; index++) {
                $('#addPhoneTrContact').append('<tr id="contact_' + index + '"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_' + index + '" value="' + phone_number_contact[index] + '"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_' + index + '" value="' + (phone_extension_contact[index] != null ? phone_extension_contact[index] : "") + '"></td><td><select name="phone_type" id="phone_type_contact_' + index + '" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language" id="phone_language_contact_' + index + '" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}" >{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_' + index + '" value="' + (phone_description_contact[index] != null ? phone_description_contact[index] : "") + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
                if (phone_type_contact[index] != '') {
                    $("select[id='phone_type_contact_" + index + "'] option[value=" + phone_type_contact[index] +"]").prop('selected', true)
                }
                if (phone_language_contact[index] != '') {
                    for (let m = 0; m < phone_language_contact[index].length; m++) {
                        $("select[id='phone_language_contact_" + index + "'] option[value=" +phone_language_contact[index][m] + "]").prop('selected', true)
                    }
                }
                // contact_phone_list = contact_phone_list + ',' + phone_number_contact[index]
                $('.selectpicker').selectpicker();
            }
            $('.selectpicker').selectpicker('refresh');
            cp = phone_number_contact.length

            let contact_department_p = contact_department_val[id]
            // let contact_visibility_p = contact_visibility_val[id]
            let contact_service_p = contact_service_val[id]
            // contactRadioValue = radioValue
            contactRadioValue = 'new_data'
            // $("input[name=contactRadio][value="+radioValue+"]").prop("checked",true);
            $("input[name=contactRadio][value='new_data']").prop("checked", true);
            $("input[name=contactRadio][value='existing']").prop("disabled", true);
            // if(radioValue == 'new_data'){
            $('#contact_name_p').val(contact_name_p)
            $('#contact_title_p').val(contact_title_p)
            $('#contact_email_p').val(contact_email_p)
            $('#contact_phone_p').val(contact_phone_p)
            $('#contact_department_p').val(contact_department_p)
            $('#contact_visibility_p').val(contact_visibility_p)
            $('#contact_service_p').val(contact_service_p)
            $('#contactSelectData').val('')
            $('#contact_service_p').selectpicker('refresh')
            $('#contact_visibility_p').selectpicker('refresh')
            $('#newContactData').show()
            $('#existingContactData').hide()
            // }else{
            //     let t = $('#contactSelectData option[data-id="'+contact_recordid_p+'"]').val();
            //     $('#contactSelectData').val(t)
            //     $('#newContactData').hide()
            //     $('#existingContactData').show()
            // }

            // $columns.addClass('row-highlight');
            // var values = "";

            // jQuery.each($columns, function(i, item) {
            //     values = values + 'td' + (i + 1) + ':' + item.innerHTML + '<br/>';
            //     console.log(item.innerHTML);
            // });
            // console.log(values);
            $('#contactmodal').modal('show');
        });
        // contact section end here
        // location section
        let locationRadioValue = 'new_data'
        $('.locationRadio').on('change', function() {
            let value = $(this).val()
            locationRadioValue = value
            if (value == 'new_data') {
                $('#newLocationData').show()
                $('#existingLocationData').hide()
            } else {
                $('#newLocationData').hide()
                $('#existingLocationData').show()
            }
        })
        let l = "{{ count($organization_locations_data) }}";
        let location_alternate_name = JSON.parse($('#location_alternate_name').val())
        let location_transporation = JSON.parse($('#location_transporation').val())
        let location_service = JSON.parse($('#location_service').val())
        let location_schedules = JSON.parse($('#location_schedules').val())
        let location_description = JSON.parse($('#location_description').val())
        let location_details = JSON.parse($('#location_details').val())
        let location_regions = JSON.parse($('#location_regions').val())
        let location_accessibility = JSON.parse($('#location_accessibility').val())
        let location_accessibility_details = JSON.parse($('#location_accessibility_details').val())

        let location_phone_numbers = JSON.parse($('#location_phone_numbers').val())
        let location_phone_extensions = JSON.parse($('#location_phone_extensions').val())
        let location_phone_types = JSON.parse($('#location_phone_types').val())
        let location_phone_languages = JSON.parse($('#location_phone_languages').val())
        let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val())

        // schedule variables
        let opens_location_monday_datas = JSON.parse($('#opens_location_monday_datas').val())
        let closes_location_monday_datas = JSON.parse($('#closes_location_monday_datas').val())
        let schedule_closed_monday_datas = JSON.parse($('#schedule_closed_monday_datas').val())
        let opens_location_tuesday_datas = JSON.parse($('#opens_location_tuesday_datas').val())
        let closes_location_tuesday_datas = JSON.parse($('#closes_location_tuesday_datas').val())
        let schedule_closed_tuesday_datas = JSON.parse($('#schedule_closed_tuesday_datas').val())
        let opens_location_wednesday_datas = JSON.parse($('#opens_location_wednesday_datas').val())
        let closes_location_wednesday_datas = JSON.parse($('#closes_location_wednesday_datas').val())
        let schedule_closed_wednesday_datas = JSON.parse($('#schedule_closed_wednesday_datas').val())
        let opens_location_thursday_datas = JSON.parse($('#opens_location_thursday_datas').val())
        let closes_location_thursday_datas = JSON.parse($('#closes_location_thursday_datas').val())
        let schedule_closed_thursday_datas = JSON.parse($('#schedule_closed_thursday_datas').val())
        let opens_location_friday_datas = JSON.parse($('#opens_location_friday_datas').val())
        let closes_location_friday_datas = JSON.parse($('#closes_location_friday_datas').val())
        let schedule_closed_friday_datas = JSON.parse($('#schedule_closed_friday_datas').val())
        let opens_location_saturday_datas = JSON.parse($('#opens_location_saturday_datas').val())
        let closes_location_saturday_datas = JSON.parse($('#closes_location_saturday_datas').val())
        let schedule_closed_saturday_datas = JSON.parse($('#schedule_closed_saturday_datas').val())
        let opens_location_sunday_datas = JSON.parse($('#opens_location_sunday_datas').val())
        let closes_location_sunday_datas = JSON.parse($('#closes_location_sunday_datas').val())
        let schedule_closed_sunday_datas = JSON.parse($('#schedule_closed_sunday_datas').val())


        let location_holiday_start_dates = JSON.parse($('#location_holiday_start_dates').val())
        let location_holiday_end_dates = JSON.parse($('#location_holiday_end_dates').val())
        let location_holiday_open_ats = JSON.parse($('#location_holiday_open_ats').val())
        let location_holiday_close_ats = JSON.parse($('#location_holiday_close_ats').val())
        let location_holiday_closeds = JSON.parse($('#location_holiday_closeds').val())

        $('#locationSubmit').click(function() {
            let location_name_p = ''
            let location_alternate_name_p = ''
            let location_transporation_p = ''
            let location_service_p = ''
            let location_schedules_p = ''
            let location_description_p = ''
            let location_address_p = ''
            let location_city_p = ''
            let location_state_p = ''
            let location_zipcode_p = ''
            let location_details_p = ''
            let location_region_p = ''
            let location_accessibility_p = ''
            let location_accessibility_details_p = ''
            let location_phone_p = ''
            let location_recordid_p = ''

            // schedule section
            let opens_location_monday = ''
            let closes_location_monday = ''
            let schedule_closed_monday = ''

            let opens_location_tuesday = ''
            let closes_location_tuesday = ''
            let schedule_closed_tuesday = ''

            let opens_location_wednesday = ''
            let closes_location_wednesday = ''
            let schedule_closed_wednesday = ''

            let opens_location_thursday = ''
            let closes_location_thursday = ''
            let schedule_closed_thursday = ''

            let opens_location_friday = ''
            let closes_location_friday = ''
            let schedule_closed_friday = ''

            let opens_location_saturday = ''
            let closes_location_saturday = ''
            let schedule_closed_saturday = ''

            let opens_location_sunday = ''
            let closes_location_sunday = ''
            let schedule_closed_sunday = ''

            if (locationRadioValue == 'new_data' && $('#location_name_p').val() == '') {
                $('#location_name_error').show()
                setTimeout(() => {
                    $('#location_name_error').hide()
                }, 5000);
                return false
            }
            let phone_number_location = []
            let phone_extension_location = []
            let phone_type_location = []
            let phone_language_location = []
            let phone_description_location = []

            let holiday_start_date_location = []
            let holiday_end_date_location = []
            let holiday_open_at_location = []
            let holiday_close_at_location = []
            let holiday_closed_location = []

            if (locationRadioValue == 'new_data') {
                // location_name_p = $('#location_name_p').val()
                // location_address_p = $('#location_address_p').val()
                // location_city_p = $('#location_city_p').val()
                // location_state_p = $('#location_state_p').val()
                // location_zipcode_p = $('#location_zipcode_p').val()
                // location_phone_p = $('#location_phone_p').val()
                location_name_p = $('#location_name_p').val()
                location_alternate_name_p = $('#location_alternate_name_p').val()
                location_transporation_p = $('#location_transporation_p').val()
                location_service_p = $('#location_service_p').val()
                location_schedules_p = $('#location_schedules_p').val()
                location_description_p = $('#location_description_p').val()
                location_address_p = $('#location_address_p').val()
                location_city_p = $('#location_city_p').val()
                location_state_p = $('#location_state_p').val()
                location_zipcode_p = $('#location_zipcode_p').val()
                location_details_p = $('#location_details_p').val()
                location_region_p = $('#location_region_p').val()
                location_accessibility_p = $('#location_accessibility_p').val()
                location_accessibility_details_p = $('#location_accessibility_details_p').val()
                // location_phone_p = $('#location_phone_p').val()

                for (let index = 0; index < lp; index++) {
                    phone_number_location.push($('#service_phones_location_' + index).val())
                    phone_extension_location.push($('#phone_extension_location_' + index).val())
                    phone_type_location.push($('#phone_type_location_' + index).val())
                    phone_language_location.push($('#phone_language_location_' + index).val())
                    phone_description_location.push($('#phone_description_location_' + index).val())
                }
                // schedule section
                for (let index = 0; index < ls; index++) {
                    holiday_start_date_location.push($('#holiday_start_date_location_' + index).val())
                    holiday_end_date_location.push($('#holiday_end_date_location_' + index).val())
                    holiday_open_at_location.push($('#holiday_open_at_location_' + index).val())
                    holiday_close_at_location.push($('#holiday_close_at_location_' + index).val())
                    holiday_closed_location.push($('#holiday_closed_location_' + index).is(":checked") ? 1 : '')
                }
                opens_location_monday = $('#opens_location_monday').val()
                closes_location_monday = $('#closes_location_monday').val()
                schedule_closed_monday = $('#schedule_closed_location_monday').is(":checked") ? 1 : ''

                opens_location_tuesday = $('#opens_location_tuesday').val()
                closes_location_tuesday = $('#closes_location_tuesday').val()
                schedule_closed_tuesday = $('#schedule_closed_location_tuesday').is(":checked") ? 2 : ''

                opens_location_wednesday = $('#opens_location_wednesday').val()
                closes_location_wednesday = $('#closes_location_wednesday').val()
                schedule_closed_wednesday = $('#schedule_closed_location_wednesday').is(":checked") ? 3 : ''

                opens_location_thursday = $('#opens_location_thursday').val()
                closes_location_thursday = $('#closes_location_thursday').val()
                schedule_closed_thursday = $('#schedule_closed_location_thursday').is(":checked") ? 4 : ''

                opens_location_friday = $('#opens_location_friday').val()
                closes_location_friday = $('#closes_location_friday').val()
                schedule_closed_friday = $('#schedule_closed_location_friday').is(":checked") ? 5 : ''

                opens_location_saturday = $('#opens_location_saturday').val()
                closes_location_saturday = $('#closes_location_saturday').val()
                schedule_closed_saturday = $('#schedule_closed_location_saturday').is(":checked") ? 6 : ''

                opens_location_sunday = $('#opens_location_sunday').val()
                closes_location_sunday = $('#closes_location_sunday').val()
                schedule_closed_sunday = $('#schedule_closed_location_sunday').is(":checked") ? 7 : ''


                // locationsTable
            } else {
                let data = JSON.parse($('#locationSelectData').val())

                location_name_p = data.location_name ? data.location_name : ''
                location_recordid_p = data.location_recordid ? data.location_recordid : ''

                location_alternate_name_p = data.location_alternate_name ? data.location_alternate_name : ''
                location_transporation_p = data.location_transportation ? data.location_transportation : ''
                location_description_p = data.location_description ? data.location_description : ''
                location_details_p = data.location_details ? data.location_details : ''

                // for location accessibility
                let accessibilities = data.accessibilities && data.accessibilities.length > 0 ? data
                    .accessibilities : []
                location_accessibility_p = accessibilities.map((v) => {
                    return v.accessibility
                }).join(',');

                location_accessibility_details_p = accessibilities.map((v) => {
                    return v.accessibility_details
                }).join(',');

                // for regions
                let regions_data = data.regions && data.regions.length > 0 ? data.regions : []
                let regionsIds = regions_data.map((v) => {
                    return v.id
                }).join(',');
                location_region_p = regionsIds ? regionsIds.split(',') : []

                let services = data.services && data.services.length > 0 ? data.services : []
                let servicesIds = services.map((v) => {
                    return v.service_recordid
                }).join(',');
                location_service_p = servicesIds ? servicesIds.split(',') : []

                let schedules = data.schedules && data.schedules.length > 0 ? data.schedules : []
                let schedulesIds = schedules.map((v) => {
                    return v.schedule_recordid
                }).join(',');
                location_schedules_p = schedulesIds ? schedulesIds.split(',') : []

                let address = data.address && data.address.length > 0 ? data.address[0] : ''

                location_address_p = address ? address.address_1 : ''
                location_city_p = address ? address.address_city : ''
                location_state_p = address ? address.address_state_province : ''
                location_zipcode_p = address ? address.address_postal_code : ''

                // location_phone_p = data.phones && data.phones.length > 0 && data.phones[0].phone_number ? data.phones[0].phone_number : ''
                let phone = data.phones && data.phones.length > 0 ? data.phones : []

                for (let index = 0; index < phone.length; index++) {
                    phone_number_location.push(phone[index].phone_number)
                    phone_extension_location.push(phone[index].phone_extension)
                    phone_type_location.push(phone[index].phone_type)
                    phone_language_location.push(phone[index].phone_language)
                    phone_description_location.push(phone[index].phone_description)
                }

                let schedule = data.schedules && data.schedules.length > 0 ? data.schedules : []

                for (let index = 0; index < schedules.length; index++) {
                    if (schedule[index].schedule_holiday == 1) {
                        holiday_start_date_location.push(schedule[index].dtstart)
                        holiday_end_date_location.push(schedule[index].until)
                        holiday_open_at_location.push(schedule[index].opens)
                        holiday_close_at_location.push(schedule[index].closes)
                        holiday_closed_location.push(schedule[index].schedule_closed)
                    } else {
                        if (schedule[index].weekday == 'monday') {
                            opens_location_monday = schedule[index].opens
                            closes_location_monday = schedule[index].closes
                            schedule_closed_monday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'tuesday') {
                            opens_location_tuesday = schedule[index].opens
                            closes_location_tuesday = schedule[index].closes
                            schedule_closed_tuesday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'wednesday') {
                            opens_location_wednesday = schedule[index].opens
                            closes_location_wednesday = schedule[index].closes
                            schedule_closed_wednesday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'thursday') {
                            opens_location_thursday = schedule[index].opens
                            closes_location_thursday = schedule[index].closes
                            schedule_closed_thursday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'friday') {
                            opens_location_friday = schedule[index].opens
                            closes_location_friday = schedule[index].closes
                            schedule_closed_friday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'saturday') {
                            opens_location_saturday = schedule[index].opens
                            closes_location_saturday = schedule[index].closes
                            schedule_closed_saturday = schedule[index].schedule_closed
                        } else if (schedule[index].weekday == 'sunday') {
                            opens_location_sunday = schedule[index].opens
                            closes_location_sunday = schedule[index].closes
                            schedule_closed_sunday = schedule[index].schedule_closed
                        }
                    }

                }

            }
            phone_number_location = phone_number_location.filter(function(element) {
                return element !== undefined;
            })
            let location_phone_list = phone_number_location.join(',');
            if (editLocationData == false) {
                location_alternate_name.push(location_alternate_name_p)
                location_transporation.push(location_transporation_p)
                location_service.push(location_service_p)
                location_schedules.push(location_schedules_p)
                location_description.push(location_description_p)
                location_details.push(location_details_p)
                location_accessibility.push(location_accessibility_p)
                location_accessibility_details.push(location_accessibility_details_p)
                location_regions.push(location_region_p)

                location_phone_numbers[l] = phone_number_location
                location_phone_extensions[l] = phone_extension_location
                location_phone_types[l] = phone_type_location
                location_phone_languages[l] = phone_language_location
                location_phone_descriptions[l] = phone_description_location

                opens_location_monday_datas[l] = opens_location_monday
                closes_location_monday_datas[l] = closes_location_monday
                schedule_closed_monday_datas[l] = schedule_closed_monday
                opens_location_tuesday_datas[l] = opens_location_tuesday
                closes_location_tuesday_datas[l] = closes_location_tuesday
                schedule_closed_tuesday_datas[l] = schedule_closed_tuesday
                opens_location_wednesday_datas[l] = opens_location_wednesday
                closes_location_wednesday_datas[l] = closes_location_wednesday
                schedule_closed_wednesday_datas[l] = schedule_closed_wednesday
                opens_location_thursday_datas[l] = opens_location_thursday
                closes_location_thursday_datas[l] = closes_location_thursday
                schedule_closed_thursday_datas[l] = schedule_closed_thursday
                opens_location_friday_datas[l] = opens_location_friday
                closes_location_friday_datas[l] = closes_location_friday
                schedule_closed_friday_datas[l] = schedule_closed_friday
                opens_location_saturday_datas[l] = opens_location_saturday
                closes_location_saturday_datas[l] = closes_location_saturday
                schedule_closed_saturday_datas[l] = schedule_closed_saturday
                opens_location_sunday_datas[l] = opens_location_sunday
                closes_location_sunday_datas[l] = closes_location_sunday
                schedule_closed_sunday_datas[l] = schedule_closed_sunday

                location_holiday_start_dates[l] = holiday_start_date_location
                location_holiday_end_dates[l] = holiday_end_date_location
                location_holiday_open_ats[l] = holiday_open_at_location
                location_holiday_close_ats[l] = holiday_close_at_location
                location_holiday_closeds[l] = holiday_closed_location

                $('#locationsTable').append('<tr id="locationTr_' + l + '"><td>' + location_name_p + '<input type="hidden" name="location_name[]" value="' + location_name_p + '" id="location_name_' + l + '"></td><td>' + location_address_p + '<input type="hidden" name="location_address[]" value="' + location_address_p + '" id="location_address_' + l + '"></td><td class="text-center">' + location_city_p + '<input type="hidden" name="location_city[]" value="' + location_city_p + '" id="location_city_' + l + '"></td><td class="text-center">' + location_state_p + '<input type="hidden" name="location_state[]" value="' + location_state_p + '" id="location_state_' + l + '"></td><td class="text-center">' + location_zipcode_p + '<input type="hidden" name="location_zipcode[]" value="' + location_zipcode_p + '" id="location_zipcode_' + l + '"></td><td class="text-center">' + location_phone_list + '<input type="hidden" name="location_phone[]" value="' + location_phone_p + '" id="location_phone_' + l + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="' + locationRadioValue + '" id="selectedLocationRadio_' + l + '"><input type="hidden" name="location_recordid[]" value="' + location_recordid_p + '" id="existingLocationIds_' + l + '"></td></tr>');
                l++;
            } else {
                if (selectedLocationTrId) {
                    locationRadioValue = $('#selectedLocationRadio_' + selectedLocationTrId).val()
                    location_recordid_p = $('#existingLocationIds_' + selectedLocationTrId).val()
                    location_alternate_name[selectedLocationTrId] = location_alternate_name_p
                    location_transporation[selectedLocationTrId] = location_transporation_p
                    location_service[selectedLocationTrId] = location_service_p
                    location_schedules[selectedLocationTrId] = location_schedules_p
                    location_description[selectedLocationTrId] = location_description_p
                    location_details[selectedLocationTrId] = location_details_p
                    location_accessibility[selectedLocationTrId] = location_accessibility_p
                    location_accessibility_details[selectedLocationTrId] = location_accessibility_details_p
                    location_regions[selectedLocationTrId] = location_region_p

                    location_phone_numbers[selectedLocationTrId] = phone_number_location
                    location_phone_extensions[selectedLocationTrId] = phone_extension_location
                    location_phone_types[selectedLocationTrId] = phone_type_location
                    location_phone_languages[selectedLocationTrId] = phone_language_location
                    location_phone_descriptions[selectedLocationTrId] = phone_description_location

                    opens_location_monday_datas[selectedLocationTrId] = opens_location_monday
                    closes_location_monday_datas[selectedLocationTrId] = closes_location_monday
                    schedule_closed_monday_datas[selectedLocationTrId] = schedule_closed_monday
                    opens_location_tuesday_datas[selectedLocationTrId] = opens_location_tuesday
                    closes_location_tuesday_datas[selectedLocationTrId] = closes_location_tuesday
                    schedule_closed_tuesday_datas[selectedLocationTrId] = schedule_closed_tuesday
                    opens_location_wednesday_datas[selectedLocationTrId] = opens_location_wednesday
                    closes_location_wednesday_datas[selectedLocationTrId] = closes_location_wednesday
                    schedule_closed_wednesday_datas[selectedLocationTrId] = schedule_closed_wednesday
                    opens_location_thursday_datas[selectedLocationTrId] = opens_location_thursday
                    closes_location_thursday_datas[selectedLocationTrId] = closes_location_thursday
                    schedule_closed_thursday_datas[selectedLocationTrId] = schedule_closed_thursday
                    opens_location_friday_datas[selectedLocationTrId] = opens_location_friday
                    closes_location_friday_datas[selectedLocationTrId] = closes_location_friday
                    schedule_closed_friday_datas[selectedLocationTrId] = schedule_closed_friday
                    opens_location_saturday_datas[selectedLocationTrId] = opens_location_saturday
                    closes_location_saturday_datas[selectedLocationTrId] = closes_location_saturday
                    schedule_closed_saturday_datas[selectedLocationTrId] = schedule_closed_saturday
                    opens_location_sunday_datas[selectedLocationTrId] = opens_location_sunday
                    closes_location_sunday_datas[selectedLocationTrId] = closes_location_sunday
                    schedule_closed_sunday_datas[selectedLocationTrId] = schedule_closed_sunday

                    location_holiday_start_dates[selectedLocationTrId] = holiday_start_date_location
                    location_holiday_end_dates[selectedLocationTrId] = holiday_end_date_location
                    location_holiday_open_ats[selectedLocationTrId] = holiday_open_at_location
                    location_holiday_close_ats[selectedLocationTrId] = holiday_close_at_location
                    location_holiday_closeds[selectedLocationTrId] = holiday_closed_location

                    $('#locationTr_' + selectedLocationTrId).empty()
                    $('#locationTr_' + selectedLocationTrId).append('<td>' + location_name_p + '<input type="hidden" name="location_name[]" value="' + location_name_p + '" id="location_name_' + selectedLocationTrId + '"></td><td>' + location_address_p + '<input type="hidden" name="location_address[]" value="' + location_address_p + '" id="location_address_' + selectedLocationTrId + '"></td><td class="text-center">' + location_city_p + '<input type="hidden" name="location_city[]" value="' + location_city_p + '" id="location_city_' + selectedLocationTrId + '"></td><td class="text-center">' + location_state_p + '<input type="hidden" name="location_state[]" value="' + location_state_p + '" id="location_state_' + selectedLocationTrId + '"></td><td class="text-center">' + location_zipcode_p + '<input type="hidden" name="location_zipcode[]" value="' + location_zipcode_p + '" id="location_zipcode_' + selectedLocationTrId + '"></td><td class="text-center">' + location_phone_list + '<input type="hidden" name="location_phone[]" value="' + location_phone_p + '" id="location_phone_' + selectedLocationTrId + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="' + locationRadioValue + '" id="selectedLocationRadio_' + selectedLocationTrId + '"><input type="hidden" name="location_recordid[]" value="' + location_recordid_p + '" id="existingLocationIds_' + selectedLocationTrId + '"></td>')
                }
            }
            $('#location_alternate_name').val(JSON.stringify(location_alternate_name))
            $('#location_transporation').val(JSON.stringify(location_transporation))
            $('#location_service').val(JSON.stringify(location_service))
            $('#location_schedules').val(JSON.stringify(location_schedules))
            $('#location_description').val(JSON.stringify(location_description))
            $('#location_details').val(JSON.stringify(location_details))
            $('#location_accessibility').val(JSON.stringify(location_accessibility))
            $('#location_accessibility_details').val(JSON.stringify(location_accessibility_details))
            $('#location_regions').val(JSON.stringify(location_regions))

            $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
            $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
            $('#location_phone_types').val(JSON.stringify(location_phone_types))
            $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
            $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))

            $('#addPhoneTrLocation').empty()
            $('#addPhoneTrLocation').append('<tr id="location_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');

            $('#opens_location_monday_datas').val(JSON.stringify(opens_location_monday_datas))
            $('#closes_location_monday_datas').val(JSON.stringify(closes_location_monday_datas))
            $('#schedule_closed_monday_datas').val(JSON.stringify(schedule_closed_monday_datas))
            $('#opens_location_tuesday_datas').val(JSON.stringify(opens_location_tuesday_datas))
            $('#closes_location_tuesday_datas').val(JSON.stringify(closes_location_tuesday_datas))
            $('#schedule_closed_tuesday_datas').val(JSON.stringify(schedule_closed_tuesday_datas))
            $('#opens_location_wednesday_datas').val(JSON.stringify(opens_location_wednesday_datas))
            $('#closes_location_wednesday_datas').val(JSON.stringify(closes_location_wednesday_datas))
            $('#schedule_closed_wednesday_datas').val(JSON.stringify(schedule_closed_wednesday_datas))
            $('#opens_location_thursday_datas').val(JSON.stringify(opens_location_thursday_datas))
            $('#closes_location_thursday_datas').val(JSON.stringify(closes_location_thursday_datas))
            $('#schedule_closed_thursday_datas').val(JSON.stringify(schedule_closed_thursday_datas))
            $('#opens_location_friday_datas').val(JSON.stringify(opens_location_friday_datas))
            $('#closes_location_friday_datas').val(JSON.stringify(closes_location_friday_datas))
            $('#schedule_closed_friday_datas').val(JSON.stringify(schedule_closed_friday_datas))
            $('#opens_location_saturday_datas').val(JSON.stringify(opens_location_saturday_datas))
            $('#closes_location_saturday_datas').val(JSON.stringify(closes_location_saturday_datas))
            $('#schedule_closed_saturday_datas').val(JSON.stringify(schedule_closed_saturday_datas))
            $('#opens_location_sunday_datas').val(JSON.stringify(opens_location_sunday_datas))
            $('#closes_location_sunday_datas').val(JSON.stringify(closes_location_sunday_datas))
            $('#schedule_closed_sunday_datas').val(JSON.stringify(schedule_closed_sunday_datas))

            $('#location_holiday_start_dates').val(JSON.stringify(location_holiday_start_dates))
            $('#location_holiday_end_dates').val(JSON.stringify(location_holiday_end_dates))
            $('#location_holiday_open_ats').val(JSON.stringify(location_holiday_open_ats))
            $('#location_holiday_close_ats').val(JSON.stringify(location_holiday_close_ats))
            $('#location_holiday_closeds').val(JSON.stringify(location_holiday_closeds))

            $('#scheduleHolidayLocation').empty()
            $('#scheduleHolidayLocation').append(
                '<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_0"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_0"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
            );
            $('.timePicker').timepicker({
                'scrollDefault': 'now'
            });
            $('#opens_location_monday').val('')
            $('#closes_location_monday').val('')
            $('#schedule_closed_location_monday').val(1)
            $('#opens_location_tuesday').val('')
            $('#closes_location_tuesday').val('')
            $('#schedule_closed_location_tuesday').val(2)
            $('#opens_location_wednesday').val('')
            $('#closes_location_wednesday').val('')
            $('#schedule_closed_location_wednesday').val(3)
            $('#opens_location_thursday').val('')
            $('#closes_location_thursday').val('')
            $('#schedule_closed_location_thursday').val(4)
            $('#opens_location_friday').val('')
            $('#closes_location_friday').val('')
            $('#schedule_closed_location_friday').val(5)
            $('#opens_location_saturday').val('')
            $('#closes_location_saturday').val('')
            $('#schedule_closed_location_saturday').val(6)
            $('#opens_location_sunday').val('')
            $('#closes_location_sunday').val('')
            $('#schedule_closed_location_sunday').val(7)

            $('#schedule_closed_location_monday').attr('checked', false)
            $('#schedule_closed_location_tuesday').attr('checked', false)
            $('#schedule_closed_location_wednesday').attr('checked', false)
            $('#schedule_closed_location_thursday').attr('checked', false)
            $('#schedule_closed_location_friday').attr('checked', false)
            $('#schedule_closed_location_saturday').attr('checked', false)
            $('#schedule_closed_location_sunday').attr('checked', false)

            $('#locationSelectData').val('')
            $('#location_name_p').val('')
            $('#location_address_p').val('')
            $('#location_city_p').val('')
            $('#location_state_p').val('')
            $('#location_zipcode_p').val('')
            $('#location_phone_p').val('')
            $('#location_alternate_name_p').val('')
            $('#location_transporation_p').val('')
            $('#location_service_p').val('')
            $('#location_schedules_p').val('')
            $('#location_description_p').val('')
            $('#location_details_p').val('')
            $('#location_accessibility_p').val('')
            $('#location_region_p').val('')
            $('#location_region_p').selectpicker('refresh')
            $('#location_accessibility_p').selectpicker('refresh')
            $('#location_service_p').selectpicker('refresh')
            $('#location_schedules_p').selectpicker('refresh')
            $('#locationmodal').modal('hide');

            // lp = JSON.parse($('#location_phone_numbers').val()).length;
            lp = 1;
            ls = 1
            editLocationData = false
            selectedLocationTrId = ''
        })
        $(document).on('click', '.removeLocationData', function() {
            var $row = jQuery(this).closest('tr');
            if (confirm("Are you sure want to remove this location?")) {
                let contactTrId = $row.attr('id')
                let id_new = contactTrId.split('_');
                let id = id_new[1]
                let deletedId = id

                let location_alternate_name_val = JSON.parse($('#location_alternate_name').val())
                let location_transporation_val = JSON.parse($('#location_transporation').val())
                let location_service = JSON.parse($('#location_service').val())
                let location_schedules_val = JSON.parse($('#location_schedules').val())
                let location_description_val = JSON.parse($('#location_description').val())
                let location_details_val = JSON.parse($('#location_details').val())
                let location_accessibility_val = JSON.parse($('#location_accessibility').val())
                let location_accessibility_details_val = JSON.parse($('#location_accessibility_details').val())
                let location_regions_val = JSON.parse($('#location_regions').val())

                // location modal phone section
                let location_phone_numbers = JSON.parse($('#location_phone_numbers').val())
                let location_phone_extensions = JSON.parse($('#location_phone_extensions').val())
                let location_phone_types = JSON.parse($('#location_phone_types').val())
                let location_phone_languages = JSON.parse($('#location_phone_languages').val())
                let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val())

                let opens_location_monday_datas = JSON.parse($('#opens_location_monday_datas').val())
                let closes_location_monday_datas = JSON.parse($('#closes_location_monday_datas').val())
                let schedule_closed_monday_datas = JSON.parse($('#schedule_closed_monday_datas').val())
                let opens_location_tuesday_datas = JSON.parse($('#opens_location_tuesday_datas').val())
                let closes_location_tuesday_datas = JSON.parse($('#closes_location_tuesday_datas').val())
                let schedule_closed_tuesday_datas = JSON.parse($('#schedule_closed_tuesday_datas').val())
                let opens_location_wednesday_datas = JSON.parse($('#opens_location_wednesday_datas').val())
                let closes_location_wednesday_datas = JSON.parse($('#closes_location_wednesday_datas').val())
                let schedule_closed_wednesday_datas = JSON.parse($('#schedule_closed_wednesday_datas').val())
                let opens_location_thursday_datas = JSON.parse($('#opens_location_thursday_datas').val())
                let closes_location_thursday_datas = JSON.parse($('#closes_location_thursday_datas').val())
                let schedule_closed_thursday_datas = JSON.parse($('#schedule_closed_thursday_datas').val())
                let opens_location_friday_datas = JSON.parse($('#opens_location_friday_datas').val())
                let closes_location_friday_datas = JSON.parse($('#closes_location_friday_datas').val())
                let schedule_closed_friday_datas = JSON.parse($('#schedule_closed_friday_datas').val())
                let opens_location_saturday_datas = JSON.parse($('#opens_location_saturday_datas').val())
                let closes_location_saturday_datas = JSON.parse($('#closes_location_saturday_datas').val())
                let schedule_closed_saturday_datas = JSON.parse($('#schedule_closed_saturday_datas').val())
                let opens_location_sunday_datas = JSON.parse($('#opens_location_sunday_datas').val())
                let closes_location_sunday_datas = JSON.parse($('#closes_location_sunday_datas').val())
                let schedule_closed_sunday_datas = JSON.parse($('#schedule_closed_sunday_datas').val())

                let location_holiday_start_dates_val = JSON.parse($('#location_holiday_start_dates').val())
                let location_holiday_end_dates_val = JSON.parse($('#location_holiday_end_dates').val())
                let location_holiday_open_ats_val = JSON.parse($('#location_holiday_open_ats').val())
                let location_holiday_close_ats_val = JSON.parse($('#location_holiday_close_ats').val())
                let location_holiday_closeds_val = JSON.parse($('#location_holiday_closeds').val())

                location_alternate_name_val.splice(deletedId, 1)
                location_transporation_val.splice(deletedId, 1)
                location_service.splice(deletedId, 1)
                location_schedules_val.splice(deletedId, 1)
                location_description_val.splice(deletedId, 1)
                location_details_val.splice(deletedId, 1)
                location_accessibility_val.splice(deletedId, 1)
                location_accessibility_details_val.splice(deletedId, 1)
                location_regions_val.splice(deletedId, 1)
                location_phone_numbers.splice(deletedId, 1)
                location_phone_extensions.splice(deletedId, 1)
                location_phone_types.splice(deletedId, 1)
                location_phone_languages.splice(deletedId, 1)
                location_phone_descriptions.splice(deletedId, 1)
                opens_location_monday_datas.splice(deletedId, 1)
                closes_location_monday_datas.splice(deletedId, 1)
                schedule_closed_monday_datas.splice(deletedId, 1)
                opens_location_tuesday_datas.splice(deletedId, 1)
                closes_location_tuesday_datas.splice(deletedId, 1)
                schedule_closed_tuesday_datas.splice(deletedId, 1)
                opens_location_wednesday_datas.splice(deletedId, 1)
                closes_location_wednesday_datas.splice(deletedId, 1)
                schedule_closed_wednesday_datas.splice(deletedId, 1)
                opens_location_thursday_datas.splice(deletedId, 1)
                closes_location_thursday_datas.splice(deletedId, 1)
                schedule_closed_thursday_datas.splice(deletedId, 1)
                opens_location_friday_datas.splice(deletedId, 1)
                closes_location_friday_datas.splice(deletedId, 1)
                schedule_closed_friday_datas.splice(deletedId, 1)
                opens_location_saturday_datas.splice(deletedId, 1)
                closes_location_saturday_datas.splice(deletedId, 1)
                schedule_closed_saturday_datas.splice(deletedId, 1)
                opens_location_sunday_datas.splice(deletedId, 1)
                closes_location_sunday_datas.splice(deletedId, 1)
                schedule_closed_sunday_datas.splice(deletedId, 1)
                location_holiday_start_dates_val.splice(deletedId, 1)
                location_holiday_end_dates_val.splice(deletedId, 1)
                location_holiday_open_ats_val.splice(deletedId, 1)
                location_holiday_close_ats_val.splice(deletedId, 1)
                location_holiday_closeds_val.splice(deletedId, 1)

                $('#location_alternate_name').val(JSON.stringify(location_alternate_name_val))
                $('#location_transporation').val(JSON.stringify(location_transporation_val))
                $('#location_service').val(JSON.stringify(location_service))
                $('#location_schedules').val(JSON.stringify(location_schedules_val))
                $('#location_description').val(JSON.stringify(location_description_val))
                $('#location_details').val(JSON.stringify(location_details_val))
                $('#location_accessibility').val(JSON.stringify(location_accessibility_val))
                $('#location_accessibility_details').val(JSON.stringify(location_accessibility_details_val))
                $('#location_regions').val(JSON.stringify(location_regions_val))

                $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
                $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
                $('#location_phone_types').val(JSON.stringify(location_phone_types))
                $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
                $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))
                $('#opens_location_monday_datas').val(JSON.stringify(opens_location_monday_datas))
                $('#closes_location_monday_datas').val(JSON.stringify(closes_location_monday_datas))
                $('#schedule_closed_monday_datas').val(JSON.stringify(schedule_closed_monday_datas))
                $('#opens_location_tuesday_datas').val(JSON.stringify(opens_location_tuesday_datas))
                $('#closes_location_tuesday_datas').val(JSON.stringify(closes_location_tuesday_datas))
                $('#schedule_closed_tuesday_datas').val(JSON.stringify(schedule_closed_tuesday_datas))
                $('#opens_location_wednesday_datas').val(JSON.stringify(opens_location_wednesday_datas))
                $('#closes_location_wednesday_datas').val(JSON.stringify(closes_location_wednesday_datas))
                $('#schedule_closed_wednesday_datas').val(JSON.stringify(schedule_closed_wednesday_datas))
                $('#opens_location_thursday_datas').val(JSON.stringify(opens_location_thursday_datas))
                $('#closes_location_thursday_datas').val(JSON.stringify(closes_location_thursday_datas))
                $('#schedule_closed_thursday_datas').val(JSON.stringify(schedule_closed_thursday_datas))
                $('#opens_location_friday_datas').val(JSON.stringify(opens_location_friday_datas))
                $('#closes_location_friday_datas').val(JSON.stringify(closes_location_friday_datas))
                $('#schedule_closed_friday_datas').val(JSON.stringify(schedule_closed_friday_datas))
                $('#opens_location_saturday_datas').val(JSON.stringify(opens_location_saturday_datas))
                $('#closes_location_saturday_datas').val(JSON.stringify(closes_location_saturday_datas))
                $('#schedule_closed_saturday_datas').val(JSON.stringify(schedule_closed_saturday_datas))
                $('#opens_location_sunday_datas').val(JSON.stringify(opens_location_sunday_datas))
                $('#closes_location_sunday_datas').val(JSON.stringify(closes_location_sunday_datas))
                $('#schedule_closed_sunday_datas').val(JSON.stringify(schedule_closed_sunday_datas))
                $('#location_holiday_start_dates').val(JSON.stringify(location_holiday_start_dates_val))
                $('#location_holiday_end_dates').val(JSON.stringify(location_holiday_end_dates_val))
                $('#location_holiday_open_ats').val(JSON.stringify(location_holiday_open_ats_val))
                $('#location_holiday_close_ats').val(JSON.stringify(location_holiday_close_ats_val))
                $('#location_holiday_closeds').val(JSON.stringify(location_holiday_closeds_val))
                $(this).closest('tr').remove()


                $('#locationsTable').each(function() {
                    var table = $(this);
                    table.find('tr').each(function(i) {
                        $(this).attr("id", "locationTr_" + i)
                        $(this).find('td').find("input[name='location_name[]']").attr("id","location_name_" + i)
                        $(this).find('td').find("input[name='location_address[]']").attr("id","location_address_" + i)
                        $(this).find('td').find("input[name='location_city[]']").attr("id","location_city_" + i)
                        $(this).find('td').find("input[name='location_state[]']").attr("id","location_state_" + i)
                        $(this).find('td').find("input[name='location_zipcode[]']").attr("id","location_zipcode_" + i)
                    });
                    //Code here
                });
                l = location_alternate_name_val.length
                lp = 1
            }
            return false;
        });
        $(document).on('click', '.locationModalOpenButton', function() {
            $('#locationSelectData').val('')
            $('#location_name_p').val('')
            $('#location_address_p').val('')
            $('#location_city_p').val('')
            $('#location_state_p').val('')
            $('#location_zipcode_p').val('')
            $('#location_phone_p').val('')
            $('#location_alternate_name_p').val('')
            $('#location_transporation_p').val('')
            $('#location_service_p').val('')
            $('#location_schedules_p').val('')
            $('#location_description_p').val('')
            $('#location_details_p').val('')
            $('#location_accessibility_p').val('')
            // $('#location_accessibility_details_p').val('')
            $('#location_region_p').val('')

            $('#location_service_p').selectpicker('refresh')
            $('#location_schedules_p').selectpicker('refresh')

            $('#schedule_closed_location_monday').attr('checked', false)
            $('#schedule_closed_location_tuesday').attr('checked', false)
            $('#schedule_closed_location_wednesday').attr('checked', false)
            $('#schedule_closed_location_thursday').attr('checked', false)
            $('#schedule_closed_location_friday').attr('checked', false)
            $('#schedule_closed_location_saturday').attr('checked', false)
            $('#schedule_closed_location_sunday').attr('checked', false)

            $('#locationmodal').modal('show');
        });
        $(document).on('click', '.locationCloseButton', function() {
            editLocationData = false

            $('#schedule_closed_location_monday').attr('checked', false)
            $('#schedule_closed_location_tuesday').attr('checked', false)
            $('#schedule_closed_location_wednesday').attr('checked', false)
            $('#schedule_closed_location_thursday').attr('checked', false)
            $('#schedule_closed_location_friday').attr('checked', false)
            $('#schedule_closed_location_saturday').attr('checked', false)
            $('#schedule_closed_location_sunday').attr('checked', false)

            $("input[name=locationRadio][value='existing']").prop("disabled", false);
            $('#locationmodal').modal('hide');
        });
        $(document).on('click', '.locationEditButton', function() {
            editLocationData = true
            var $row = jQuery(this).closest('tr');
            // var $columns = $row.find('td');
            // console.log()
            let locationTrId = $row.attr('id')
            let id_new = locationTrId.split('_');
            let id = id_new[1]
            selectedLocationTrId = id

            $('#addPhoneTrLocation').empty()
            $('#addPhoneTrLocation').append('<tr id="location_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');

            $('#scheduleHolidayLocation').empty()
            $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_0"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_0"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.timePicker').timepicker({
                'scrollDefault': 'now'
            });
            // $('.locationRadio').val()
            let radioValue = $("#selectedLocationRadio_" + id).val();
            let location_name_p = $('#location_name_' + id).val()
            let location_address_p = $('#location_address_' + id).val()
            let location_city_p = $('#location_city_' + id).val()
            let location_state_p = $('#location_state_' + id).val()
            let location_zipcode_p = $('#location_zipcode_' + id).val()
            let location_phone_p = $('#location_phone_' + id).val()
            let location_recordid_p = $('#existingLocationIds_' + id).val()


            let location_alternate_name_val = JSON.parse($('#location_alternate_name').val())
            let location_transporation_val = JSON.parse($('#location_transporation').val())
            let location_service_val = JSON.parse($('#location_service').val())
            let location_schedules_val = JSON.parse($('#location_schedules').val())
            let location_description_val = JSON.parse($('#location_description').val())
            let location_details_val = JSON.parse($('#location_details').val())
            let location_accessibility_val = JSON.parse($('#location_accessibility').val())
            let location_accessibility_details_val = JSON.parse($('#location_accessibility_details').val())
            let location_regions_val = JSON.parse($('#location_regions').val())

            // location modal phone section
            let location_phone_numbers = JSON.parse($('#location_phone_numbers').val())
            let location_phone_extensions = JSON.parse($('#location_phone_extensions').val())
            let location_phone_types = JSON.parse($('#location_phone_types').val())
            let location_phone_languages = JSON.parse($('#location_phone_languages').val())
            let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val())

            let phone_number_location = location_phone_numbers[id]
            let phone_extension_location = location_phone_extensions[id]
            let phone_type_location = location_phone_types[id]
            let phone_language_location = location_phone_languages[id]
            let phone_description_location = location_phone_descriptions[id]
            $('#service_phones_location_0').val(phone_number_location[0])
            $('#phone_extension_location_0').val(phone_extension_location[0])
            $('#phone_type_location_0').val(phone_type_location[0])
            $('#phone_language_location_0').val(phone_language_location[0])
            $('#phone_description_location_0').val(phone_description_location[0])
            for (let index = 1; index < phone_number_location.length; index++) {
                $('#addPhoneTrLocation').append('<tr id="location_' + index + '"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_' + index + '" value="' + phone_number_location[index] + '"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_' + index + '" value="' + (phone_extension_location[index] != null ? phone_extension_location[index] : "") + '"></td><td><select name="phone_type[]" id="phone_type_location_' + index + '" class="form-control selectpicker" data-live-search="true" data-size="5"><option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_' + index + '" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_' + index + '" value="' + (phone_description_location[index] != null ? phone_description_location[index] : "") + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');

                if (phone_type_location[index] != '') {
                    $("select[id='phone_type_location_" + index + "'] option[value=" + phone_type_location[index] +"]").prop('selected', true)
                }
                if (phone_language_location[index] != '') {
                    for (let m = 0; m < phone_language_location[index].length; m++) {
                        $("select[id='phone_language_location_" + index + "'] option[value=" + phone_language_location[index][m] + "]").prop('selected', true)
                    }
                }
                $('.selectpicker').selectpicker();
            }
            $('.selectpicker').selectpicker('refresh');
            lp = phone_number_location.length

            // location schedule section

            let opens_location_monday_datas = JSON.parse($('#opens_location_monday_datas').val())
            let closes_location_monday_datas = JSON.parse($('#closes_location_monday_datas').val())
            let schedule_closed_monday_datas = JSON.parse($('#schedule_closed_monday_datas').val())
            let opens_location_tuesday_datas = JSON.parse($('#opens_location_tuesday_datas').val())
            let closes_location_tuesday_datas = JSON.parse($('#closes_location_tuesday_datas').val())
            let schedule_closed_tuesday_datas = JSON.parse($('#schedule_closed_tuesday_datas').val())
            let opens_location_wednesday_datas = JSON.parse($('#opens_location_wednesday_datas').val())
            let closes_location_wednesday_datas = JSON.parse($('#closes_location_wednesday_datas').val())
            let schedule_closed_wednesday_datas = JSON.parse($('#schedule_closed_wednesday_datas').val())
            let opens_location_thursday_datas = JSON.parse($('#opens_location_thursday_datas').val())
            let closes_location_thursday_datas = JSON.parse($('#closes_location_thursday_datas').val())
            let schedule_closed_thursday_datas = JSON.parse($('#schedule_closed_thursday_datas').val())
            let opens_location_friday_datas = JSON.parse($('#opens_location_friday_datas').val())
            let closes_location_friday_datas = JSON.parse($('#closes_location_friday_datas').val())
            let schedule_closed_friday_datas = JSON.parse($('#schedule_closed_friday_datas').val())
            let opens_location_saturday_datas = JSON.parse($('#opens_location_saturday_datas').val())
            let closes_location_saturday_datas = JSON.parse($('#closes_location_saturday_datas').val())
            let schedule_closed_saturday_datas = JSON.parse($('#schedule_closed_saturday_datas').val())
            let opens_location_sunday_datas = JSON.parse($('#opens_location_sunday_datas').val())
            let closes_location_sunday_datas = JSON.parse($('#closes_location_sunday_datas').val())
            let schedule_closed_sunday_datas = JSON.parse($('#schedule_closed_sunday_datas').val())

            let location_holiday_start_dates_val = JSON.parse($('#location_holiday_start_dates').val())
            let location_holiday_end_dates_val = JSON.parse($('#location_holiday_end_dates').val())
            let location_holiday_open_ats_val = JSON.parse($('#location_holiday_open_ats').val())
            let location_holiday_close_ats_val = JSON.parse($('#location_holiday_close_ats').val())
            let location_holiday_closeds_val = JSON.parse($('#location_holiday_closeds').val())

            let opens_location_monday = opens_location_monday_datas[id]
            let closes_location_monday = closes_location_monday_datas[id]
            let schedule_closed_monday = schedule_closed_monday_datas[id]
            let opens_location_tuesday = opens_location_tuesday_datas[id]
            let closes_location_tuesday = closes_location_tuesday_datas[id]
            let schedule_closed_tuesday = schedule_closed_tuesday_datas[id]
            let opens_location_wednesday = opens_location_wednesday_datas[id]
            let closes_location_wednesday = closes_location_wednesday_datas[id]
            let schedule_closed_wednesday = schedule_closed_wednesday_datas[id]
            let opens_location_thursday = opens_location_thursday_datas[id]
            let closes_location_thursday = closes_location_thursday_datas[id]
            let schedule_closed_thursday = schedule_closed_thursday_datas[id]
            let opens_location_friday = opens_location_friday_datas[id]
            let closes_location_friday = closes_location_friday_datas[id]
            let schedule_closed_friday = schedule_closed_friday_datas[id]
            let opens_location_saturday = opens_location_saturday_datas[id]
            let closes_location_saturday = closes_location_saturday_datas[id]
            let schedule_closed_saturday = schedule_closed_saturday_datas[id]
            let opens_location_sunday = opens_location_sunday_datas[id]
            let closes_location_sunday = closes_location_sunday_datas[id]
            let schedule_closed_sunday = schedule_closed_sunday_datas[id]

            let location_holiday_start_dates = location_holiday_start_dates_val[id]
            let location_holiday_end_dates = location_holiday_end_dates_val[id]
            let location_holiday_open_ats = location_holiday_open_ats_val[id]
            let location_holiday_close_ats = location_holiday_close_ats_val[id]
            let location_holiday_closeds = location_holiday_closeds_val[id]

            $('#opens_location_monday').val(opens_location_monday)
            $('#closes_location_monday').val(closes_location_monday)
            $('#schedule_closed_location_monday').val(1)
            if (schedule_closed_monday == 1) {
                $('#schedule_closed_location_monday').attr('checked', true)
            } else {
                $('#schedule_closed_location_monday').attr('checked', false)
            }

            $('#opens_location_tuesday').val(opens_location_tuesday)
            $('#closes_location_tuesday').val(closes_location_tuesday)
            $('#schedule_closed_location_tuesday').val(2)
            if (schedule_closed_tuesday == 2) {
                $('#schedule_closed_location_tuesday').attr('checked', true)
            } else {
                $('#schedule_closed_location_tuesday').attr('checked', false)
            }

            $('#opens_location_wednesday').val(opens_location_wednesday)
            $('#closes_location_wednesday').val(closes_location_wednesday)
            $('#schedule_closed_location_wednesday').val(3)
            if (schedule_closed_wednesday == 3) {
                $('#schedule_closed_location_wednesday').attr('checked', true)
            } else {
                $('#schedule_closed_location_wednesday').attr('checked', false)
            }

            $('#opens_location_thursday').val(opens_location_thursday)
            $('#closes_location_thursday').val(closes_location_thursday)
            $('#schedule_closed_location_thursday').val(4)
            if (schedule_closed_thursday == 4) {
                $('#schedule_closed_location_thursday').attr('checked', true)
            } else {
                $('#schedule_closed_location_thursday').attr('checked', false)
            }

            $('#opens_location_friday').val(opens_location_friday)
            $('#closes_location_friday').val(closes_location_friday)
            $('#schedule_closed_location_friday').val(5)
            if (schedule_closed_friday == 5) {
                $('#schedule_closed_location_friday').attr('checked', true)
            } else {
                $('#schedule_closed_location_friday').attr('checked', false)
            }

            $('#opens_location_saturday').val(opens_location_saturday)
            $('#closes_location_saturday').val(closes_location_saturday)
            $('#schedule_closed_location_saturday').val(6)
            if (schedule_closed_saturday == 6) {
                $('#schedule_closed_location_saturday').attr('checked', true)
            } else {
                $('#schedule_closed_location_saturday').attr('checked', false)
            }

            $('#opens_location_sunday').val(opens_location_sunday)
            $('#closes_location_sunday').val(closes_location_sunday)
            $('#schedule_closed_location_sunday').val(7)

            if (schedule_closed_sunday == 7) {
                $('#schedule_closed_location_sunday').attr('checked', true)
            } else {
                $('#schedule_closed_location_sunday').attr('checked', false)
            }
            $('#holiday_start_date_location_0').val(location_holiday_start_dates ? location_holiday_start_dates[0] :
                '')
            $('#holiday_end_date_location_0').val(location_holiday_end_dates ? location_holiday_end_dates[0] : '')
            $('#holiday_open_at_location_0').val(location_holiday_open_ats ? location_holiday_open_ats[0] : '')
            $('#holiday_close_at_location_0').val(location_holiday_close_ats ? location_holiday_close_ats[0] : '')
            $('#holiday_closed_location_0').val(1)
            if ((location_holiday_closeds ? location_holiday_closeds[0] : '') == 1) {
                $('#holiday_closed_location_0').attr('checked', true)
            } else {
                $('#holiday_closed_location_0').attr('checked', false)
            }
            if (location_holiday_start_dates) {
                for (let index = 1; index < location_holiday_start_dates.length; index++) {
                    $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_' + index + '" value="' + location_holiday_start_dates[index] + '"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_' + index + '" value="' + location_holiday_end_dates[index] + '"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_' + index + '" value="' + location_holiday_open_ats[index] + '"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_' + index + '" value="' + location_holiday_close_ats[index] + '"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_' + index + '" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
                    $('.timePicker').timepicker({'scrollDefault': 'now'});
                    if (location_holiday_closeds[index] == 1) {
                        $('#holiday_closed_location_' + index).attr('checked', true)
                    } else {
                        $('#holiday_closed_location_' + index).attr('checked', false)
                    }
                }
            }

            ls = location_holiday_start_dates ? location_holiday_start_dates.length : 1

            let location_alternate_name_p = location_alternate_name_val[id]
            let location_transporation_p = location_transporation_val[id]
            let location_service_p = location_service_val[id]
            let location_schedules_p = location_schedules_val[id]
            let location_description_p = location_description_val[id]
            let location_details_p = location_details_val[id]
            let location_accessibility_p = location_accessibility_val[id]
            let location_accessibility_details_p = location_accessibility_details_val[id]
            let location_region_p = location_regions_val[id]


            // locationRadioValue = radioValue
            locationRadioValue = 'new_data'
            // $("input[name=locationRadio][value="+radioValue+"]").prop("checked",true);
            $("input[name=locationRadio][value='new_data']").prop("checked", true);
            $("input[name=locationRadio][value='existing']").prop("disabled", true);
            // if(radioValue == 'new_data'){
            $('#location_name_p').val(location_name_p)
            $('#location_address_p').val(location_address_p)
            $('#location_city_p').val(location_city_p)
            $('#location_state_p').val(location_state_p)
            $('#location_zipcode_p').val(location_zipcode_p)
            $('#location_phone_p').val(location_phone_p)
            $('#location_alternate_name_p').val(location_alternate_name_p)
            $('#location_transporation_p').val(location_transporation_p)
            $('#location_service_p').val(location_service_p)
            $('#location_schedules_p').val(location_schedules_p)
            $('#location_description_p').val(location_description_p)
            $('#location_details_p').val(location_details_p)
            $('#location_accessibility_p').val(location_accessibility_p)
            $('#location_accessibility_details_p').val(location_accessibility_details_p)
            $('#location_region_p').val(location_region_p)

            $('#locationSelectData').val('')
            $('#newLocationData').show()
            $('#existingLocationData').hide()
            $('#location_accessibility_p').selectpicker('refresh')
            $('#location_region_p').selectpicker('refresh')
            $('#location_service_p').selectpicker('refresh')
            $('#location_schedules_p').selectpicker('refresh')
            $('#location_city_p').selectpicker('refresh')
            $('#location_state_p').selectpicker('refresh')

            // }else{
            //     // let t = $('#locationSelectData option[data-id="'+location_recordid_p+'"]').val();
            //     // $('#locationSelectData').val(t)
            //     // $('#newLocationData').hide()
            //     // $('#existingLocationData').show()
            // }

            // $columns.addClass('row-highlight');
            // var values = "";

            // jQuery.each($columns, function(i, item) {
            //     values = values + 'td' + (i + 1) + ':' + item.innerHTML + '<br/>';
            //     console.log(item.innerHTML);
            // });
            // console.log(values);
            $('.selectpicker').selectpicker('refresh');
            $('#locationmodal').modal('show');
        });

        // service section
        let serviceRadioValue = 'new_data'
        $('.serviceRadio').on('change', function() {
            let value = $(this).val()
            serviceRadioValue = value
            if (value == 'new_data') {
                $('#newServiceData').show()
                $('#existingServiceData').hide()
            } else {
                $('#newServiceData').hide()
                $('#existingServiceData').show()
            }
        })
        let s = "{{ count($organization_service_list) }}";
        let service_alternate_name = JSON.parse($('#service_alternate_name').val())
        let service_program = JSON.parse($('#service_program').val())
        let service_status = JSON.parse($('#service_status').val())
        let service_application_process = JSON.parse($('#service_application_process').val())
        let service_wait_time = JSON.parse($('#service_wait_time').val())
        let service_fees = JSON.parse($('#service_fees').val())
        let service_accreditations = JSON.parse($('#service_accreditations').val())
        let service_licenses = JSON.parse($('#service_licenses').val())
        let service_metadata = JSON.parse($('#service_metadata').val())
        let service_airs_taxonomy_x = JSON.parse($('#service_airs_taxonomy_x').val())
        $('#serviceSubmit').click(function() {
            let service_name_p = ''
            let service_alternate_name_p = ''
            let service_description_p = ''
            let service_url_p = ''
            let service_program_p = ''
            let service_email_p = ''
            let service_status_p = ''
            let service_taxonomies_p = ''
            let service_application_process_p = ''
            let service_wait_time_p = ''
            let service_fees_p = ''
            let service_accreditations_p = ''
            let service_licenses_p = ''
            let service_schedules_p = ''
            let service_details_p = ''
            let service_address_p = ''
            let service_metadata_p = ''
            let service_airs_taxonomy_x_p = ''
            let service_phone_p = ''
            let service_recordid_p = ''
            if (serviceRadioValue == 'new_data' && $('#service_name_p').val() == '') {
                $('#service_name_error').show()
                setTimeout(() => {
                    $('#service_name_error').hide()
                }, 5000);
                return false
            }
            if (serviceRadioValue == 'new_data') {
                service_name_p = $('#service_name_p').val()
                service_alternate_name_p = $('#service_alternate_name_p').val()
                service_description_p = $('#service_description_p').val()
                service_url_p = $('#service_url_p').val()
                service_program_p = $('#service_program_p').val()
                service_email_p = $('#service_email_p').val()
                service_status_p = $('#service_status_p').val()
                service_taxonomies_p = $('#service_taxonomies_p').val()
                service_application_process_p = $('#service_application_process_p').val()
                service_wait_time_p = $('#service_wait_time_p').val()
                service_fees_p = $('#service_fees_p').val()
                service_accreditations_p = $('#service_accreditations_p').val()
                service_licenses_p = $('#service_licenses_p').val()
                service_schedules_p = $('#service_schedules_p').val()
                service_details_p = $('#service_details_p').val()
                service_address_p = $('#service_address_p').val()
                service_metadata_p = $('#service_metadata_p').val()
                service_airs_taxonomy_x_p = $('#service_airs_taxonomy_x_p').val()
                service_phone_p = $('#service_phone_p').val()
                // servicesTable
            } else {
                let data = JSON.parse($('#serviceSelectData').val())

                service_recordid_p = data.service_recordid ? data.service_recordid : ''
                service_name_p = data.service_name ? data.service_name : ''
                service_alternate_name_p = data.service_alternate_name ? data.service_alternate_name : ''
                service_description_p = data.service_description ? data.service_description : ''
                service_url_p = data.service_url ? data.service_url : ''
                service_program_p = data.service_program ? data.service_program : ''
                service_email_p = data.service_email ? data.service_email : ''
                service_status_p = data.service_status ? data.service_status : ''
                service_taxonomies_p = data.service_taxonomy ? data.service_taxonomy.split(',') : []
                service_application_process_p = data.service_application_process ? data.service_application_process : ''
                service_wait_time_p = data.service_wait_time ? data.service_wait_time : ''
                service_fees_p = data.service_fees ? data.service_fees : ''
                service_accreditations_p = data.service_accreditations ? data.service_accreditations : ''
                service_licenses_p = data.service_licenses ? data.service_licenses : ''
                service_schedules_p = data.service_schedule ? data.service_schedule.split(',') : []
                service_details_p = data.service_details ? data.service_details.split(',') : []
                service_address_p = data.service_address ? data.service_address.split(',') : []
                service_metadata_p = data.service_metadata ? data.service_metadata : ''
                service_airs_taxonomy_x_p = data.service_airs_taxonomy_x ? data.service_airs_taxonomy_x : ''
                service_phone_p = data.phone && data.phone.length > 0 && data.phone[0].phone_number ? data.phone[0].phone_number : ''

            }
            if (editServiceData == false) {
                all_services_taxonomies.push(service_taxonomies_p)
                all_services_schedules.push(service_schedules_p)
                all_services_details.push(service_details_p)
                all_services_address.push(service_address_p)

                service_alternate_name.push(service_alternate_name_p)
                service_program.push(service_program_p)
                service_status.push(service_status_p)
                service_application_process.push(service_application_process_p)
                service_wait_time.push(service_wait_time_p)
                service_fees.push(service_fees_p)
                service_accreditations.push(service_accreditations_p)
                service_licenses.push(service_licenses_p)
                service_metadata.push(service_metadata_p)
                service_airs_taxonomy_x.push(service_airs_taxonomy_x_p)
                $('#servicesTable').append('<tr id="serviceTr_' + s + '"><td>' + service_name_p + '<input type="hidden" name="service_name[]" value="' + service_name_p + '" id="service_name_' + s + '"></td><td>' + service_description_p + '<input type="hidden" name="service_description[]" value="' + service_description_p + '" id="service_description_' + s + '"></td><td class="text-center">' + service_url_p + '<input type="hidden" name="service_url[]" value="' + service_url_p + '" id="service_url_' + s + '"></td><td class="text-center">' + service_email_p + '<input type="hidden" name="service_email[]" value="' + service_email_p + '" id="service_email_' + s + '"></td><td class="text-center">' + service_phone_p + '<input type="hidden" name="service_phone[]" value="' + service_phone_p + '" id="service_phone_' + s + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="serviceEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeServiceData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="serviceRadio[]" value="' + serviceRadioValue + '" id="selectedServiceRadio_' + s + '"><input type="hidden" name="service_recordid[]" value="' + service_recordid_p + '" id="existingServiceIds_' + s + '"></td></tr>');
                s++;
            } else {
                if (selectedServiceTrId) {
                    serviceRadioValue = $('#selectedServiceRadio_' + selectedServiceTrId).val()
                    service_recordid_p = $('#existingServiceIds_' + selectedServiceTrId).val()
                    all_services_taxonomies[selectedServiceTrId] = service_taxonomies_p
                    all_services_schedules[selectedServiceTrId] = service_schedules_p
                    all_services_details[selectedServiceTrId] = service_details_p
                    all_services_address[selectedServiceTrId] = service_address_p
                    service_alternate_name[selectedServiceTrId] = service_alternate_name_p
                    service_program[selectedServiceTrId] = service_program_p
                    service_status[selectedServiceTrId] = service_status_p
                    service_application_process[selectedServiceTrId] = service_application_process_p
                    service_wait_time[selectedServiceTrId] = service_wait_time_p
                    service_fees[selectedServiceTrId] = service_fees_p
                    service_accreditations[selectedServiceTrId] = service_accreditations_p
                    service_licenses[selectedServiceTrId] = service_licenses_p
                    service_metadata[selectedServiceTrId] = service_metadata_p
                    service_airs_taxonomy_x[selectedServiceTrId] = service_airs_taxonomy_x_p
                    $('#serviceTr_' + selectedServiceTrId).empty()
                    $('#serviceTr_' + selectedServiceTrId).append('<td>' + service_name_p + '<input type="hidden" name="service_name[]" value="' + service_name_p + '" id="service_name_' + selectedServiceTrId + '"></td><td>' + service_description_p + '<input type="hidden" name="service_description[]" value="' + service_description_p + '" id="service_description_' + selectedServiceTrId + '"></td><td class="text-center">' + service_url_p + '<input type="hidden" name="service_url[]" value="' + service_url_p + '" id="service_url_' + selectedServiceTrId + '"></td><td class="text-center">' + service_email_p + '<input type="hidden" name="service_email[]" value="' + service_email_p + '" id="service_email_' + selectedServiceTrId + '"></td><td class="text-center">' + service_phone_p + '<input type="hidden" name="service_phone[]" value="' + service_phone_p + '" id="service_phone_' + selectedServiceTrId + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="serviceEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeServiceData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="serviceRadio[]" value="' + serviceRadioValue + '" id="selectedServiceRadio_' + selectedServiceTrId + '"><input type="hidden" name="service_recordid[]" value="' + service_recordid_p + '" id="existingServiceIds_' + selectedServiceTrId + '"></td>')
                }
            }
            $('#service_alternate_name').val(JSON.stringify(service_alternate_name))
            $('#service_program').val(JSON.stringify(service_program))
            $('#service_status').val(JSON.stringify(service_status))
            $('#service_taxonomies').val(JSON.stringify(all_services_taxonomies))
            $('#service_application_process').val(JSON.stringify(service_application_process))
            $('#service_wait_time').val(JSON.stringify(service_wait_time))
            $('#service_fees').val(JSON.stringify(service_fees))
            $('#service_accreditations').val(JSON.stringify(service_accreditations))
            $('#service_licenses').val(JSON.stringify(service_licenses))
            $('#service_schedules').val(JSON.stringify(all_services_schedules))
            $('#service_details').val(JSON.stringify(all_services_details))
            $('#service_address').val(JSON.stringify(all_services_address))
            $('#service_metadata').val(JSON.stringify(service_metadata))
            $('#service_airs_taxonomy_x').val(JSON.stringify(service_airs_taxonomy_x))

            $('#service_name_p').val('')
            $('#service_alternate_name_p').val('')
            $('#service_description_p').val('')
            $('#service_url_p').val('')
            $('#service_program_p').val('')
            $('#service_email_p').val('')
            $('#service_status_p').val('')
            $('#service_taxonomies_p').val('')
            $('#service_application_process_p').val('')
            $('#service_wait_time_p').val('')
            $('#service_fees_p').val('')
            $('#service_accreditations_p').val('')
            $('#service_licenses_p').val('')
            $('#service_schedules_p').val('')
            $('#service_details_p').val('')
            $('#service_address_p').val('')
            $('#service_metadata_p').val('')
            $('#service_airs_taxonomy_x_p').val('')
            $('#service_phone_p').val('')

            $('#service_taxonomies_p').selectpicker('refresh')
            $('#service_schedules_p').selectpicker('refresh')
            $('#service_details_p').selectpicker('refresh')
            $('#service_address_p').selectpicker('refresh')
            $('#servicemodal').modal('hide');

            editServiceData = false
            selectedServiceTrId = ''
        })
        $(document).on('click', '.removeServiceData', function() {
            var $row = jQuery(this).closest('tr');
            // var $columns = $row.find('td');
            // console.log()
            if (confirm("Are you sure want to remove this service?")) {
                let contactTrId = $row.attr('id')
                let id_new = contactTrId.split('_');
                let id = id_new[1]
                let deletedId = id
                let service_alternate_name_val = JSON.parse($('#service_alternate_name').val())
                let service_program_val = JSON.parse($('#service_program').val())
                let service_status_val = JSON.parse($('#service_status').val())
                let service_taxonomies_val = JSON.parse($('#service_taxonomies').val())
                let service_application_process_val = JSON.parse($('#service_application_process').val())
                let service_wait_time_val = JSON.parse($('#service_wait_time').val())
                let service_fees_val = JSON.parse($('#service_fees').val())
                let service_accreditations_val = JSON.parse($('#service_accreditations').val())
                let service_licenses_val = JSON.parse($('#service_licenses').val())
                let service_schedules_val = JSON.parse($('#service_schedules').val())
                let service_details_val = JSON.parse($('#service_details').val())
                let service_address_val = JSON.parse($('#service_address').val())
                let service_metadata_val = JSON.parse($('#service_metadata').val())
                let service_airs_taxonomy_x_val = JSON.parse($('#service_airs_taxonomy_x').val())

                service_alternate_name.splice(deletedId, 1)
                service_program.splice(deletedId, 1)
                service_status.splice(deletedId, 1)
                all_services_taxonomies.splice(deletedId, 1)
                service_application_process.splice(deletedId, 1)
                service_wait_time.splice(deletedId, 1)
                service_fees.splice(deletedId, 1)
                service_accreditations.splice(deletedId, 1)
                service_licenses.splice(deletedId, 1)
                all_services_schedules.splice(deletedId, 1)
                all_services_details.splice(deletedId, 1)
                all_services_address.splice(deletedId, 1)
                service_metadata.splice(deletedId, 1)
                service_airs_taxonomy_x.splice(deletedId, 1)

                $('#service_alternate_name').val(JSON.stringify(service_alternate_name))
                $('#service_program').val(JSON.stringify(service_program))
                $('#service_status').val(JSON.stringify(service_status))
                $('#service_taxonomies').val(JSON.stringify(all_services_taxonomies))
                $('#service_application_process').val(JSON.stringify(service_application_process))
                $('#service_wait_time').val(JSON.stringify(service_wait_time))
                $('#service_fees').val(JSON.stringify(service_fees))
                $('#service_accreditations').val(JSON.stringify(service_accreditations))
                $('#service_licenses').val(JSON.stringify(service_licenses))
                $('#service_schedules').val(JSON.stringify(all_services_schedules))
                $('#service_details').val(JSON.stringify(all_services_details))
                $('#service_address').val(JSON.stringify(all_services_address))
                $('#service_metadata').val(JSON.stringify(service_metadata))
                $('#service_airs_taxonomy_x').val(JSON.stringify(service_airs_taxonomy_x))
                $(this).closest('tr').remove()

                $('#servicesTable').each(function() {
                    var table = $(this);
                    table.find('tr').each(function(i) {
                        $(this).attr("id", "serviceTr_" + i)
                    });
                    //Code here
                });
                s = service_alternate_name_val.length
            }
            return false;

        });
        $(document).on('click', '.serviceModalOpenButton', function() {
            $('#serviceSelectData').val('')
            $('#service_name_p').val('')
            $('#service_alternate_name_p').val('')
            $('#service_description_p').val('')
            $('#service_url_p').val('')
            $('#service_program_p').val('')
            $('#service_email_p').val('')
            $('#service_status_p').val('')
            $('#service_taxonomies_p').val('')
            $('#service_application_process_p').val('')
            $('#service_wait_time_p').val('')
            $('#service_fees_p').val('')
            $('#service_accreditations_p').val('')
            $('#service_licenses_p').val('')
            $('#service_schedules_p').val('')
            $('#service_details_p').val('')
            $('#service_address_p').val('')
            $('#service_metadata_p').val('')
            $('#service_airs_taxonomy_x_p').val('')
            $('#service_phone_p').val('')

            $('#service_taxonomies_p').selectpicker('refresh')
            $('#service_schedules_p').selectpicker('refresh')
            $('#service_details_p').selectpicker('refresh')
            $('#service_address_p').selectpicker('refresh')
            $('#servicemodal').modal('show');
        });
        $(document).on('click', '.serviceCloseButton', function() {
            editServiceData = false
            $("input[name=serviceRadio][value='existing']").prop("disabled", false);
            $('#servicemodal').modal('hide');
        });
        $(document).on('click', '.serviceEditButton', function() {
            editServiceData = true
            var $row = jQuery(this).closest('tr');
            // var $columns = $row.find('td');
            // console.log()
            let serviceTrId = $row.attr('id')
            let id_new = serviceTrId.split('_');
            let id = id_new[1]
            selectedServiceTrId = id
            // $('.serviceRadio').val()
            let radioValue = $("#selectedServiceRadio_" + id).val();
            let service_name_p = $('#service_name_' + id).val()
            let service_description_p = $('#service_description_' + id).val()
            let service_url_p = $('#service_url_' + id).val()
            let service_email_p = $('#service_email_' + id).val()
            let service_phone_p = $('#service_phone_' + id).val()
            let service_recordid_p = $('#existingServiceIds_' + id).val()

            let service_alternate_name_val = JSON.parse($('#service_alternate_name').val())
            let service_program_val = JSON.parse($('#service_program').val())
            let service_status_val = JSON.parse($('#service_status').val())
            let service_taxonomies_val = JSON.parse($('#service_taxonomies').val())
            let service_application_process_val = JSON.parse($('#service_application_process').val())
            let service_wait_time_val = JSON.parse($('#service_wait_time').val())
            let service_fees_val = JSON.parse($('#service_fees').val())
            let service_accreditations_val = JSON.parse($('#service_accreditations').val())
            let service_licenses_val = JSON.parse($('#service_licenses').val())
            let service_schedules_val = JSON.parse($('#service_schedules').val())
            let service_details_val = JSON.parse($('#service_details').val())
            let service_address_val = JSON.parse($('#service_address').val())
            let service_metadata_val = JSON.parse($('#service_metadata').val())
            let service_airs_taxonomy_x_val = JSON.parse($('#service_airs_taxonomy_x').val())

            let service_alternate_name = service_alternate_name_val[id]
            let service_program = service_program_val[id]
            let service_status = service_status_val[id]
            let service_taxonomies = service_taxonomies_val[id]
            let service_application_process = service_application_process_val[id]
            let service_wait_time = service_wait_time_val[id]
            let service_fees = service_fees_val[id]
            let service_accreditations = service_accreditations_val[id]
            let service_licenses = service_licenses_val[id]
            let service_schedules = service_schedules_val[id]
            let service_details = service_details_val[id]
            let service_address = service_address_val[id]
            let service_metadata = service_metadata_val[id]
            let service_airs_taxonomy_x = service_airs_taxonomy_x_val[id]

            // serviceRadioValue = radioValue
            serviceRadioValue = 'new_data'
            // $("input[name=serviceRadio][value="+radioValue+"]").prop("checked",true);
            $("input[name=serviceRadio][value='new_data']").prop("checked", true);
            $("input[name=serviceRadio][value='existing']").prop("disabled", true);
            // if(radioValue == 'new_data'){
            $('#service_name_p').val(service_name_p)
            $('#service_description_p').val(service_description_p)
            $('#service_url_p').val(service_url_p)
            $('#service_email_p').val(service_email_p)
            $('#service_phone_p').val(service_phone_p)
            $('#service_alternate_name_p').val(service_alternate_name)
            $('#service_program_p').val(service_program)
            $('#service_status_p').val(service_status)
            $('#service_taxonomies_p').val(service_taxonomies)
            $('#service_application_process_p').val(service_application_process)
            $('#service_wait_time_p').val(service_wait_time)
            $('#service_fees_p').val(service_fees)
            $('#service_accreditations_p').val(service_accreditations)
            $('#service_licenses_p').val(service_licenses)
            $('#service_schedules_p').val(service_schedules)
            $('#service_details_p').val(service_details)
            $('#service_address_p').val(service_address)
            $('#service_metadata_p').val(service_metadata)
            $('#service_airs_taxonomy_x_p').val(service_airs_taxonomy_x)
            $('#serviceSelectData').val('')
            $('#newServiceData').show()
            $('#existingServiceData').hide()

            $('#service_taxonomies_p').selectpicker('refresh')
            $('#service_schedules_p').selectpicker('refresh')
            $('#service_details_p').selectpicker('refresh')
            $('#service_address_p').selectpicker('refresh')
            $('#service_status_p').selectpicker('refresh')
            $('#servicemodal').modal('show');
        });
    </script>
@endsection
