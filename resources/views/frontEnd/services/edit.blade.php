@extends('layouts.app')
@section('title')
    Edit Service
@stop

<style type="text/css">
    button[data-id="service_organization"],
    button[data-id="service_locations"],
    button[data-id="service_contacts"],
    button[data-id="service_taxonomy"],
    button[data-id="service_details"],
    button[data-id="phone_type"],
    button[data-id="phone_language"],
    button[data-id="facility_organization"],
    button[data-id="facility_schedules"],
    button[data-id="facility_details"],
    button[data-id="facility_service"],
    button[data-id="facility_address_city"],
    button[data-id="facility_address_state"],
    button[data-id="contact_organization_name"],
    button[data-id="code_conditions"],
    button[data-id="goal_conditions"],
    button[data-id="activities_conditions"],
    button[data-id="contact_service"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    h4 .help-tip {
        top: 10px;
    }

    .card_services_title {
        position: relative;
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
                        Edit Service
                        {{-- <p class=" mb-30 ">A services that will improve your productivity</p> --}}
                    </h4>
                    {{-- <form action="/services/{{$service->service_recordid}}/update" method="GET"> --}}
                    {!! Form::model($service, ['route' => ['services.update', $service->service_recordid]]) !!}
                    <div class="card all_form_field">
                        <div class="card-block">
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Service Name </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The official or public name of the service.</p>
                                            </div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_name"
                                            name="service_name" value="{{ $service->service_name }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Name </label>
                                        <select class="form-control selectpicker" id="service_organization"
                                            data-live-search="true" data-size="5" name="service_organization">
                                            <option value="">Select organization</option>
                                            @foreach ($service_organization_list as $key => $service_org)
                                                <option value="{{ $service_org->organization_recordid }}"
                                                    @if ($service->service_organization == $service_org->organization_recordid) selected @endif>
                                                    {{ $service_org->organization_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('service_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Service Description </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A description of the service.</p>
                                            </div>
                                        </div>
                                        <textarea id="service_description" name="service_description" class="selectpicker" rows="5">{{ $service->service_description }}</textarea>
                                        @error('service_description')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service URL (website) </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>URL of the service</p>
                                            </div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_url"
                                            name="service_url" value="{{ $service->service_url }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('service_email') ? 'has-error' : '' }}">
                                        <label>Service Email </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Email address for the service, if any.</p>
                                            </div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_email" name="service_email" value="{{ $service->service_email }}">
                                        @error('service_email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('service_language') ? 'has-error' : '' }}">
                                        <label>Languages </label>
                                        {!! Form::select('service_language[]', $languages, ($service->service_language ? explode(',',$service->service_language) : []), [ 'class' => 'form-control selectpicker','multiple' => true]) !!}
                                        @error('service_language')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('service_interpretation') ? 'has-error' : '' }}">
                                        <label>Interpretation Services </label>
                                        <input class="form-control selectpicker" type="text" id="service_interpretation" name="service_interpretation" value="{{ $service->service_interpretation }}">
                                        @error('service_interpretation')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group {{ $errors->has('service_alert') ? 'has-error' : '' }}">
                                        <label>Service Alert </label>
                                        <input class="form-control selectpicker" type="text" id="service_alert" name="service_alert" value="{{ $service->service_alert }}">
                                        @error('service_alert')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Application Process </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The steps needed to access the service.</p>
                                            </div>
                                        </div>
                                        <textarea id="service_application_process" name="service_application_process" class="form-control selectpicker"
                                            rows="5" cols="30">{{ $service->service_application_process }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Fee Description </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Details of any charges for service users to access this service.</p>
                                            </div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_fees"
                                            name="service_fees" value="{{ $service->service_fees }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Eligibility Description </label>
                                        <input class="form-control selectpicker" type="text" id="eligibility_description" name="eligibility_description" value="{{ $service->eligibility_description }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Eligibility Requirement</label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Is this service accessible to anyone or is there an eligibility requirement.</p>
                                            </div>
                                        </div>
                                        {!! Form::select('access_requirement', ['none' => 'None', 'yes' => 'Yes'], null, [
                                            'class' => 'form-control selectpicker',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Minimum Age </label>
                                        <input class="form-control selectpicker" type="number" min="0" id="minimum_age" name="minimum_age" value="{{ $service->minimum_age }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Maximim Age </label>
                                        <input class="form-control selectpicker" type="number" min="0" id="maximum_age" name="maximum_age" value="{{ $service->maximum_age }}">
                                    </div>
                                </div>

                                {{-- <div class="text-right col-md-12 mb-20">
                                    <button type="button" class="btn btn_additional bg-primary-color"
                                        data-toggle="collapse" data-target="#demo">Additional Info
                                        <img src="/frontend/assets/images/white_arrow.png" alt=""
                                            title="" />
                                    </button>
                                </div> --}}
                                {{-- <div id="demo" class="collapse row m-0"> --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Area</label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>The geographic area where this service is accessible.</p>
                                                </div>
                                            </div>
                                            {!! Form::select('service_area[]', $service_area, $areas, [ 'class' => 'form-control selectpicker', 'multiple' => true,
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Alternate Name </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p> Alternative or commonly used name for a service.</p>
                                                </div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_alternate_name" name="service_alternate_name" value="{{ $service->service_alternate_name }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Licenses </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>An organization may have a license issued by a government entity to
                                                        operate legally. A list of any such licenses can be provided here.
                                                    </p>
                                                </div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_licenses"
                                                name="service_licenses" value="{{ $service->service_licenses }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Wait Time </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>Time a client may expect to wait before receiving a service.</p>
                                                </div>
                                            </div>
                                            <input class="form-control selectpicker" type="text"
                                                id="service_wait_time" name="service_wait_time"
                                                value="{{ $service->service_wait_time }}">
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Accreditations</label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>Details of any accreditations. Accreditation is the formal evaluation of an organization or program against best practice standards set by an accrediting organization.</p>
                                                </div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_accreditations" name="service_accreditations" value="{{ $service->service_accreditations }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Code </label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>This is an internal system code. If you don’t know what it is please
                                                        don’t edit it.
                                                    </p>
                                                </div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_code"
                                                name="service_code" value="{{ $service->service_code }}">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status(Verified): </label>
                                        {!! Form::select('service_status',$service_status_list,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'service_status','placeholder' => 'Select status']) !!}
                                    </div>
                                </div> --}}
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    @include('frontEnd.services.service_program')

                    {{-- tab-pannel top group --}}
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#types-tab">
                                <h4 class="card_services_title">Types
                                </h4>
                            </a>
                        </li>
                        @if ($layout->show_classification == 'yes')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#sdoh-category-tab">
                                    <h4 class="card_services_title">Social Needs
                                    </h4>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#sdoh-codes-tab">
                            <h4 class="card_services_title">SDOH Codes
                            </h4>
                        </a>
                    </li> --}}
                        @endif
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="types-tab">
                                    <div style="top:8px;">
                                        <div>
                                            <p class="service_help_text">
                                                {{ $help_text->service_classification ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="organization_services">
                                        @include('frontEnd.services.service_category')
                                        @include('frontEnd.services.service_eligibility')
                                        <div class="card all_form_field">
                                            <div class="card-block">
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Details
                                                    <div class="d-inline float-right" id="addDetailTr">
                                                        <a href="javascript:void(0)" id="addDetailData"
                                                            class="plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt=""
                                                                title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    {{-- service detail section --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <table class="table table_border_none" id="DetailTable">
                                                                    <thead>
                                                                        <th>Detail Type</th>
                                                                        <th>Detail Term</th>
                                                                        <th style="width:60px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (count($selected_details_types) > 0)
                                                                            @foreach ($selected_details_types as $key => $value)
                                                                                <tr>
                                                                                    <td>
                                                                                        {!! Form::select('detail_type[]', $detail_types, $value, ['class' => 'form-control selectpicker detail_type','placeholder' => 'Select Detail Type','id' => 'detail_type_' . $key,]) !!}

                                                                                    </td>
                                                                                    <td class="create_btn">
                                                                                        @php
                                                                                            // $create_new = ['create_new' => 'Create New'];
                                                                                            $detail_terms = \App\Model\Detail::where('detail_type', $value)
                                                                                                ->pluck('detail_value', 'detail_recordid')
                                                                                                ->put('create_new','Create New',);
                                                                                            // $detail_terms = array_merge($create_new,$detail_terms);
                                                                                            // dd($detail_terms);
                                                                                        @endphp
                                                                                        {!! Form::select('detail_term[]',$detail_terms,isset($selected_details_value[$value]) ? $selected_details_value[$value] : [],['class' => 'form-control selectpicker detail_term','id' => 'detail_term_' . $key,'multiple' => 'true','data-size' => '5','data-live-search' => 'true',]) !!}
                                                                                        <input type="hidden"name="term_type[]" id="term_type_{{ $key }}" value="old">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle">
                                                                                        <a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData">
                                                                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::select('detail_type[]', $detail_types, null, [ 'class' => 'form-control selectpicker detail_type', 'placeholder' => 'Select Detail Type', 'id' => 'detail_type_0', ]) !!}

                                                                                </td>
                                                                                <td class="create_btn">
                                                                                    {!! Form::select('detail_term[]', [], null, [ 'class' => 'form-control selectpicker detail_term', 'placeholder' => 'Select Detail Term', 'id' => 'detail_term_0', ]) !!}
                                                                                    <input type="hidden" name="term_type[]" id="term_type_0" value="old">
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <a href="javascript:void(0)" class="plus_delteicon btn-button">
                                                                                        <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
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
                                @if ($layout->show_classification == 'yes')
                                    <div class="tab-pane" id="sdoh-category-tab">
                                        <div class="organization_services">

                                            <div style="top:8px;">
                                                <div>
                                                    <p class="service_help_text">
                                                        {{ $help_text->sdoh_code_helptext ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card all_form_field">
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <h4 class="title_edit text-left mb-25 mt-10">
                                                                Categories
                                                                <div class="help-tip">
                                                                    <div>
                                                                        <p>{{ $help_text->code_category ?? '' }}</p>
                                                                    </div>
                                                                </div>
                                                            </h4>
                                                            <div class="accordion" id="accordion-sdoh-category">
                                                                @foreach ($codes as $key => $item)
                                                                    <div class="row inner-accordion-section pb-15">
                                                                        <div class="col-md-8">
                                                                            <p>
                                                                                {{ $item }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            {!! Form::checkbox('code_category_ids[]', $key, in_array($key, $selected_ids) ? 'selected' : '', [ 'class' => 'code_category_ids filled-in chk-col-blue', ]) !!}
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            {{-- <h4 class="title_edit text-left mb-25 mt-10">
                                                        SDOH Codes
                                                    </h4> --}}
                                                            <div class="accordion" id="accordion-sdoh-codes">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- end tab-pannel top group --}}


                    {{-- tab-pannel bottom group --}}
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
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="locations-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                {{-- location table --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Locations <a
                                                        class="locationModalOpenButton float-right plus_delteicon bg-primary-color"><img
                                                            src="/frontend/assets/images/plus.png" alt=""
                                                            title=""></a>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                                    cellspacing="0" width="100%"
                                                                    style="display: table;">
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
                                                                        @foreach ($service_locations_data as $key => $location)
                                                                            <tr id="locationTr_{{ $key }}">
                                                                                <td>
                                                                                    {{ $location->location_name }}
                                                                                    <input type="hidden" name="location_name[]" value="{{ $location->location_name }}" id="location_name_{{ $key }}">
                                                                                </td>
                                                                                <td>
                                                                                    {{ $location->location_address }}
                                                                                    <input type="hidden" name="location_address[]" value="{{ $location->location_address }}" id="location_address_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_city }}
                                                                                    <input type="hidden" name="location_city[]" value="{{ $location->location_city }}" id="location_city_{{ $key }}">
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    {{ $location->location_state }}
                                                                                    <input type="hidden" name="location_state[]" value="{{ $location->location_state }}" id="location_state_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_zipcode }}
                                                                                    <input type="hidden" name="location_zipcode[]" value="{{ $location->location_zipcode }}" id="location_zipcode_{{ $key }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $location->location_phone }}
                                                                                    <input type="hidden" name="location_phone[]" value="{{ $location->location_phone }}" id="location_phone_{{ $key }}">
                                                                                </td>
                                                                                <td style="vertical-align:middle;">
                                                                                    <a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"> <img src="/frontend/assets/images/edit_pencil.png" alt="" title=""> </a>
                                                                                    <a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button">
                                                                                        <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                    </a>
                                                                                    <input type="hidden" name="locationRadio[]" value="existing" id="selectedLocationRadio_{{ $key }}">
                                                                                    <input type="hidden" name="location_recordid[]" value="{{ $location->location_recordid }}" id="existingLocationIds_{{ $key }}">
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- location table end here --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="phones-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                {{-- phone table --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Phones
                                                    <div class="d-inline float-right">
                                                        <a href="javascript:void(0)" class="plus_delteicon bg-primary-color phoneModalOpenButton">
                                                            <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%" id="PhoneTable">
                                                                    <thead>
                                                                        <th>Number</th>
                                                                        <th>Extension</th>
                                                                        <th style="width:200px;position:relative;">Type
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div>
                                                                                    <p>Select “Main” if this is the organization's primary phone number (or leave blank) </p>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                        <th style="width:200px;">Language(s)</th>
                                                                        <th style="width:200px;position:relative;">
                                                                            Description
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div>
                                                                                    <p>A description providing extra information about the phone service (e.g. any special arrangements for accessing, or details of availability at particular times).
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                        <th>Main</th>
                                                                        <th style="width:140px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody id="phonesTable">
                                                                        @if (count($service->phone) > 0)
                                                                            @foreach ($service->phone as $key => $value)
                                                                                <tr id="phoneTr_{{ $key }}">
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control" name="service_phones[]" id="phone_number_{{ $key }}" value="{{ $value->phone_number }}">
                                                                                        {{ $value->phone_number }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control" name="phone_extension[]" id="phone_extension_{{ $key }}" value="{{ $value->phone_extension }}">
                                                                                        {{ $value->phone_extension }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control" name="phone_type[]" id="phone_type_{{ $key }}" value="{{ $value->phone_type }}">
                                                                                        {{ $value->type ? $value->type->type : '' }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control" name="phone_language[]" id="phone_language_{{ $key }}" value="{{ $value->phone_language }}">
                                                                                        {{ isset($phone_language_name[$key]) ? $phone_language_name[$key] : '' }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control" name="phone_description[]" id="phone_description_{{ $key }}" value="{{ $value->phone_description }}">
                                                                                        {{ $value->phone_description }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="form-check form-check-inline" style="margin-top: -10px;">
                                                                                            <input class="form-check-input " type="radio" name="main_priority[]" id="main_priority{{ $key }}" value="{{ $key }}" {{ $value->main_priority == 1 ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="main_priority{{ $key }}"></label>
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
                                                                                            class="plus_delteicon btn-button removeServicePhoneData">
                                                                                            <img src="/frontend/assets/images/delete.png"
                                                                                                alt=""
                                                                                                title="">
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
                                                @include('frontEnd.services.service_contact')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end tab-pannel bottom group --}}

                    {{-- tab-pannel additional-info group --}}
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#schedules-tab">
                                <h4 class="card_services_title">Schedules
                                </h4>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#additional-info-tab">
                        <h4 class="card_services_title">Additional Info
                        </h4>
                    </a>
                </li> --}}
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="schedules-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Regular Schedule
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                                cellspacing="0" width="100%" style="display: table">
                                                                    <thead>
                                                                        <th>Weekday</th>
                                                                        <th>Opens</th>
                                                                        <th>Closes</th>
                                                                        <th style="width:150px;">Closed All Day</th>
                                                                        <th style="width:150px;">Open 24 Hours</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                Monday
                                                                                <input type="hidden" name="weekday[]" value="monday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $monday ? $monday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $monday ? $monday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="1" {{ $monday && str_contains($monday->schedule_closed, '1') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="1" {{ $monday && str_contains($monday->open_24_hours,'1') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                Tuesday
                                                                                <input type="hidden" name="weekday[]" value="tuesday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $tuesday ? $tuesday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $tuesday ? $tuesday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="2" {{ $tuesday && str_contains($tuesday->schedule_closed, '2') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="2" {{ $tuesday && str_contains($tuesday->open_24_hours,'2') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Wednesday
                                                                                <input type="hidden" name="weekday[]" value="wednesday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $wednesday ? $wednesday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $wednesday ? $wednesday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="3" {{ $wednesday && str_contains($wednesday->schedule_closed, '3') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="3" {{ $wednesday && str_contains($wednesday->open_24_hours,'3') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Thursday
                                                                                <input type="hidden" name="weekday[]" value="thursday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $thursday ? $thursday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $thursday ? $thursday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="4" {{ $thursday && str_contains($thursday->schedule_closed, '4') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="4" {{ $thursday && str_contains($thursday->open_24_hours, '4') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Friday
                                                                                <input type="hidden" name="weekday[]" value="friday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $friday ? $friday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $friday ? $friday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="5" {{ $friday && str_contains($friday->schedule_closed, '5') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="5" {{ $friday && str_contains($friday->open_24_hours, '5') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Saturday
                                                                                <input type="hidden" name="weekday[]" value="saturday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $saturday ? $saturday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $saturday ? $saturday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="6" {{ $saturday && str_contains($saturday->schedule_closed, '6') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="6" {{ $saturday && str_contains($saturday->open_24_hours, '6') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Sunday
                                                                                <input type="hidden" name="weekday[]" value="sunday">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('opens[]', $sunday ? $sunday->opens : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes[]', $sunday ? $sunday->closes : null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>

                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="7" {{ $sunday && str_contains($sunday->schedule_closed, '7') ? 'checked' : '' }}>
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="open_24_hours[]" id="" value="7" {{ $sunday && str_contains($sunday->open_24_hours, '7') ? 'checked' : '' }}>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Holiday Schedule
                                                    <div class="d-inline float-right" id="addTr">
                                                        <a href="javascript:void(0)" id="addData" class="plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt=""
                                                                title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                                cellspacing="0" width="100%" style="display: table" id="myTable">
                                                                    <thead>
                                                                        <th>Start</th>
                                                                        <th>End</th>
                                                                        <th>Opens</th>
                                                                        <th>Closes</th>
                                                                        <th>Closed All Day</th>
                                                                        <th>Open 24 Hours</th>
                                                                        <th style="width:60px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (count($holiday_schedules) > 0)
                                                                            @foreach ($holiday_schedules as $key => $value)
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="date" name="holiday_start_date[]" id="" value="{{ $value->dtstart }}" class="form-control">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="date" name="holiday_end_date[]" id="" value="{{ $value->until }}" class="form-control">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="holiday_open_at[]" id="" value="{{ $value->opens }}" class="form-control timePicker">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="holiday_close_at[]" id="" value="{{ $value->closes }}" class="form-control timePicker">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="checkbox" name="holiday_closed[]" id="" value="{{ $key + 1 }}" {{ $value->schedule_closed == $key + 1 ? 'checked' : '' }}>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="checkbox" name="holiday_open_24_hours[]" id="" value="{{ $key + 1 }}" {{ $value->open_24_hours == $key + 1 ? 'checked' : '' }}>
                                                                                    </td>
                                                                                    <td style="vertical-align: middle">
                                                                                        <a href="javascript:void(0)" class="plus_delteicon btn-button removeData">
                                                                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr></tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="date" name="holiday_start_date[]" id="" class="form-control">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="date" name="holiday_end_date[]" id="" class="form-control">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="holiday_open_at[]" id="" class="form-control timePicker">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="holiday_close_at[]" id="" class="form-control timePicker">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="checkbox" name="holiday_closed[]" id="" value="1">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="checkbox" name="holiday_open_24_hours[]" id="" value="1">
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <a href="javascript:void(0)" class="plus_delteicon btn-button removeData">
                                                                                        <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <tr></tr>
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
                                <div class="tab-pane" id="additional-info-tab">
                                    <div class="organization_services p-0">
                                        <div class="card all_form_field">
                                            <div class="card-block p-0 border-0">
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Details
                                                    <div class="d-inline float-right" id="addDetailTr">
                                                        <a href="javascript:void(0)" id="addDetailData"
                                                            class="plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt=""
                                                                title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    {{-- service detail section --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <table class="table table_border_none" id="DetailTable">
                                                                    <thead>
                                                                        <th>Detail Type</th>
                                                                        <th>Detail Term</th>
                                                                        <th style="width:60px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (count($serviceDetailsData) > 0)
                                                                            @foreach ($serviceDetailsData as $key => $value)
                                                                                <tr>
                                                                                    <td>
                                                                                        {!! Form::select('detail_type[]', $detail_types, $value->detail_type, ['class' => 'form-control selectpicker detail_type','placeholder' => 'Select Detail Type','id' => 'detail_type_' . $key,]) !!}

                                                                                    </td>
                                                                                    <td class="create_btn">
                                                                                        @php
                                                                                            // $create_new = ['create_new' => 'Create New'];
                                                                                            $detail_terms = \App\Model\Detail::where('detail_type', $value->detail_type)->pluck('detail_value', 'detail_recordid')->put('create_new','Create New',);
                                                                                            // $detail_terms = array_merge($create_new,$detail_terms);
                                                                                            // dd($detail_terms);
                                                                                        @endphp
                                                                                        {!! Form::select('detail_term[]', $detail_terms, $value->detail_recordid, ['class' => 'form-control selectpicker detail_term','placeholder' => 'Select Detail Term','id' => 'detail_term_' . $key,]) !!}
                                                                                        <input type="hidden"
                                                                                            name="term_type[]"
                                                                                            id="term_type_{{ $key }}"
                                                                                            value="old">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle">
                                                                                        <a href="javascript:void(0)"
                                                                                            class="plus_delteicon btn-button removePhoneData">
                                                                                            <img src="/frontend/assets/images/delete.png"
                                                                                                alt=""
                                                                                                title="">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::select('detail_type[]', $detail_types, null, [
                                                                                        'class' => 'form-control
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        selectpicker detail_type',
                                                                                        'placeholder' => 'Select Detail Type',
                                                                                        'id' => 'detail_type_0',
                                                                                    ]) !!}

                                                                                </td>
                                                                                <td class="create_btn">
                                                                                    {!! Form::select('detail_term[]', [], null, [
                                                                                        'class' => 'form-control selectpicker
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        detail_term',
                                                                                        'placeholder' => 'Select Detail Term',
                                                                                        'id' => 'detail_term_0',
                                                                                    ]) !!}
                                                                                    <input type="hidden"
                                                                                        name="term_type[]"
                                                                                        id="term_type_0" value="old">
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <a href="javascript:void(0)"
                                                                                        class="plus_delteicon btn-button">
                                                                                        <img src="/frontend/assets/images/delete.png"
                                                                                            alt="" title="">
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
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
                            </div>
                        </div>
                    </div>

                    {{-- end tab-pannel additional-info group --}}


                    <input type="hidden" name="removePhoneDataId" id="removePhoneDataId">
                    <input type="hidden" name="deletePhoneDataId" id="deletePhoneDataId">
                    <input type="hidden" name="phone_language_data" id="phone_language_data"
                        value="{{ $phone_language_data }}">

                    <input type="hidden" name="location_alternate_name[]" id="location_alternate_name"
                        value="{{ $location_alternate_name }}">
                    <input type="hidden" name="location_transporation[]" id="location_transporation"
                        value="{{ $location_transporation }}">
                    <input type="hidden" name="location_service[]" id="location_service"
                        value="{{ $location_service }}">
                    <input type="hidden" name="location_schedules[]" id="location_schedules"
                        value="{{ $location_schedules }}">
                    <input type="hidden" name="location_description[]" id="location_description"
                        value="{{ $location_description }}">
                    <input type="hidden" name="location_details[]" id="location_details"
                        value="{{ $location_details }}">
                    <input type="hidden" name="location_accessibility[]" id="location_accessibility"
                        value="{{ $location_accessibility }}">
                    <input type="hidden" name="location_accessibility_details[]" id="location_accessibility_details"
                        value="{{ $location_accessibility_details }}">
                    <input type="hidden" name="location_regions[]" id="location_regions"
                        value="{{ $location_regions }}">

                    <input type="hidden" name="contact_service[]" id="contact_service" value="{{ $contact_service }}">
                    <input type="hidden" name="contact_department[]" id="contact_department"
                        value="{{ $contact_department }}">
                    {{-- <input type="hidden" name="contact_visibility[]" id="contact_visibility" value="{{ $contact_visibility }}"> --}}

                    {{-- contact phone --}}
                    <input type="hidden" name="contact_phone_numbers[]" id="contact_phone_numbers"
                        value="{{ $contact_phone_numbers }}">
                    <input type="hidden" name="contact_phone_extensions[]" id="contact_phone_extensions"
                        value="{{ $contact_phone_extensions }}">
                    <input type="hidden" name="contact_phone_types[]" id="contact_phone_types"
                        value="{{ $contact_phone_types }}">
                    <input type="hidden" name="contact_phone_languages[]" id="contact_phone_languages"
                        value="{{ $contact_phone_languages }}">
                    <input type="hidden" name="contact_phone_descriptions[]" id="contact_phone_descriptions"
                        value="{{ $contact_phone_descriptions }}">
                    {{-- location phone --}}
                    <input type="hidden" name="location_phone_numbers[]" id="location_phone_numbers"
                        value="{{ $location_phone_numbers }}">
                    <input type="hidden" name="location_phone_extensions[]" id="location_phone_extensions"
                        value="{{ $location_phone_extensions }}">
                    <input type="hidden" name="location_phone_types[]" id="location_phone_types"
                        value="{{ $location_phone_types }}">
                    <input type="hidden" name="location_phone_languages[]" id="location_phone_languages"
                        value="{{ $location_phone_languages }}">
                    <input type="hidden" name="location_phone_descriptions[]" id="location_phone_descriptions"
                        value="{{ $location_phone_descriptions }}">

                    {{-- schedule section --}}
                    <input type="hidden" name="opens_location_monday_datas" id="opens_location_monday_datas"
                        value="{{ $opens_location_monday_datas }}">
                    <input type="hidden" name="closes_location_monday_datas" id="closes_location_monday_datas"
                        value="{{ $closes_location_monday_datas }}">
                    <input type="hidden" name="schedule_closed_monday_datas" id="schedule_closed_monday_datas"
                        value="{{ $schedule_closed_monday_datas }}">
                    <input type="hidden" name="opens_location_tuesday_datas" id="opens_location_tuesday_datas"
                        value="{{ $opens_location_tuesday_datas }}">
                    <input type="hidden" name="closes_location_tuesday_datas" id="closes_location_tuesday_datas"
                        value="{{ $closes_location_tuesday_datas }}">
                    <input type="hidden" name="schedule_closed_tuesday_datas" id="schedule_closed_tuesday_datas"
                        value="{{ $schedule_closed_tuesday_datas }}">
                    <input type="hidden" name="opens_location_wednesday_datas" id="opens_location_wednesday_datas"
                        value="{{ $opens_location_wednesday_datas }}">
                    <input type="hidden" name="closes_location_wednesday_datas"
                        id="closes_location_wednesday_datas" value="{{ $closes_location_wednesday_datas }}">
                    <input type="hidden" name="schedule_closed_wednesday_datas" id="schedule_closed_wednesday_datas"
                        value="{{ $schedule_closed_wednesday_datas }}">
                    <input type="hidden" name="opens_location_thursday_datas" id="opens_location_thursday_datas"
                        value="{{ $opens_location_thursday_datas }}">
                    <input type="hidden" name="closes_location_thursday_datas" id="closes_location_thursday_datas"
                        value="{{ $closes_location_thursday_datas }}">
                    <input type="hidden" name="schedule_closed_thursday_datas" id="schedule_closed_thursday_datas"
                        value="{{ $schedule_closed_thursday_datas }}">
                    <input type="hidden" name="opens_location_friday_datas" id="opens_location_friday_datas"
                        value="{{ $opens_location_friday_datas }}">
                    <input type="hidden" name="closes_location_friday_datas" id="closes_location_friday_datas"
                        value="{{ $closes_location_friday_datas }}">
                    <input type="hidden" name="schedule_closed_friday_datas" id="schedule_closed_friday_datas"
                        value="{{ $schedule_closed_friday_datas }}">
                    <input type="hidden" name="opens_location_saturday_datas" id="opens_location_saturday_datas"
                        value="{{ $opens_location_saturday_datas }}">
                    <input type="hidden" name="closes_location_saturday_datas" id="closes_location_saturday_datas"
                        value="{{ $closes_location_saturday_datas }}">
                    <input type="hidden" name="schedule_closed_saturday_datas" id="schedule_closed_saturday_datas"
                        value="{{ $schedule_closed_saturday_datas }}">
                    <input type="hidden" name="opens_location_sunday_datas" id="opens_location_sunday_datas"
                        value="{{ $opens_location_sunday_datas }}">
                    <input type="hidden" name="closes_location_sunday_datas" id="closes_location_sunday_datas"
                        value="{{ $closes_location_sunday_datas }}">
                    <input type="hidden" name="schedule_closed_sunday_datas" id="schedule_closed_sunday_datas"
                        value="{{ $schedule_closed_sunday_datas }}">

                    <input type="hidden" name="location_holiday_start_dates" id="location_holiday_start_dates"
                        value="{{ $location_holiday_start_dates }}">
                    <input type="hidden" name="location_holiday_end_dates" id="location_holiday_end_dates"
                        value="{{ $location_holiday_end_dates }}">
                    <input type="hidden" name="location_holiday_open_ats" id="location_holiday_open_ats"
                        value="{{ $location_holiday_open_ats }}">
                    <input type="hidden" name="location_holiday_close_ats" id="location_holiday_close_ats"
                        value="{{ $location_holiday_close_ats }}">
                    <input type="hidden" name="location_holiday_closeds" id="location_holiday_closeds"
                        value="{{ $location_holiday_closeds }}">

                    <input type="hidden" name="interaction_method" id="interaction_method" value="">
                    <input type="hidden" name="interaction_disposition" id="interaction_disposition" value="">
                    <input type="hidden" name="interaction_notes" id="interaction_notes" value="">
                    <input type="hidden" name="service_status" id="service_status" value="">
                    <input type="hidden" name="service_notes" id="service_notes" value="">


                    <div class="col-md-12 text-center">
                        <a href="/services/{{ $service->service_recordid }}"
                            class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"
                            id="view-service-btn"> Close</a>
                        <button type="button"
                            class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn"
                            id="delete-service-btn" value="{{ $service->service_recordid }}" data-toggle="modal"
                            data-target=".bs-delete-modal-lg"> Delete</button>
                        <button type="button"
                            class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
                            id="save-service-btn" data-toggle="modal" data-target="#interactionModal"> Save</button>

                        <button type="submit"
                            class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
                            id="save-service-btn-submit" style="display:none;"> Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-4">
                    <h4 class="card-title title_edit mb-30"></h4>
                    <div class="card all_form_field mt-40">
                        <div class="card-block">
                            <h4 class="card_services_title mb-20">Change Log</h4>
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
                                            @endphp
                                            @if (
                                                (count($old_values) > 0 && count($new_values) > 0) ||
                                                    $item->new_values[$key] ||
                                                    ($v && count($old_values) > count($new_values)) ||
                                                    ($v && count($new_values) == count($old_values)))
                                                @if ($key == 0)
                                                    <p class="mb-5" style="color: #000;font-size: 16px;">On
                                                        <a href="/viewChanges/{{ $item->id }}/{{ $service->service_recordid }}"
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
                                                @endif
                                                <ul style="padding-left: 0px;font-size: 16px;">
                                                    @if ($v && count($old_values) > count($new_values))
                                                        @php
                                                            $diffData = array_diff($old_values, $new_values);
                                                        @endphp
                                                        <li
                                                            style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Removed <b
                                                                style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span
                                                                style="color: #FF5044">{{ implode(' | ', $diffData) }}</span>
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
                                                                    style="color: #35AD8B">{{ implode(' | ', $diffData) }}</span>
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
                                                                    style="color: #35AD8B">{{ implode(' | ', $diffData) }}</span>
                                                            </li>
                                                        @endif
                                                        {{-- <li style="color: #000;list-style: disc;list-style-position: inside;">Changed <b style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b> from <span style="color: #FF5044">{{ $v }}</span> to <span style="color: #35AD8B">{{ $new_values ? $new_values : 'none' }}</span>
                    </li> --}}
                                                    @elseif($item->new_values[$key])
                                                        <li
                                                            style="color: #000;list-style: disc;list-style-position: inside;">
                                                            Added
                                                            <b
                                                                style="font-family: Neue Haas Grotesk Display Medium;">{{ Str::ucfirst($fieldName) }}</b>
                                                            <span
                                                                style="color: #35AD8B">{{ $item->new_values[$key] }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @endif
                                        @endforeach
                                        {{-- <span><a href="/viewChanges/{{ $item->id }}/{{ $service->service_recordid }}"
                style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">View
                Changes</a></span> --}}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- phone modal --}}
            @include('frontEnd.services.phone')
            {{-- phone modala close --}}

            {{-- location Modal --}}
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="locationmodal">
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
                                    <select name="locations" id="locationSelectData" class="form-control selectpicker"
                                        data-live-search="true" data-size="5">
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
                                                <label>Location Name</label>
                                                <input class="form-control selectpicker" type="text"
                                                    id="location_name_p" name="location_name" value="">
                                                <span id="location_name_error" style="display: none;color:red">Location
                                                    Name is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Address"
                                                    id="location_address_p">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City </label>
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
                                                <label>State </label>
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="location_state_p" name="location_state" , data-size="5">
                                                    <option value="">Select state</option>
                                                    @foreach ($address_states_list as $key => $address_state)
                                                        <option value="{{ $address_state }}">{{ $address_state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Zip Code </label>
                                                <input type="text" class="form-control" placeholder="Zipcode"
                                                    id="location_zipcode_p">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>ADA Compliant </label>
                                                {!! Form::select( 'location_accessibility_p', $accessibilities, null, [ 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-size' => '5', 'id' => 'location_accessibility_p', 'placeholder' => 'select accessibility', ], ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Accessibility Details </label>
                                                {!! Form::textarea( 'location_accessibility_details_p', 'Visitors with concerns about the level of access for specific physical conditions, are always recommended to contact the organization directly to obtain the best possible information about physical access', ['class' => 'form-control', 'id' => 'location_accessibility_details_p', 'placeholder' => 'Accessibility Details'], ) !!}
                                            </div>
                                        </div>
                                        <div class="text-right col-md-12 mb-20">
                                            <button type="button" class="btn btn_additional bg-primary-color"
                                                data-toggle="collapse" data-target="#additional_location_modal">Additional
                                                Info
                                                <img src="/frontend/assets/images/white_arrow.png" alt=""
                                                    title="" />
                                            </button>
                                        </div>
                                        <div id="additional_location_modal" class="collapse row m-0 col-md-12">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Location Alternate Name </label>
                                                    <input class="form-control selectpicker" type="text"
                                                        id="location_alternate_name_p" name="location_alternate_name"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Location Transportation </label>
                                                    <input class="form-control selectpicker" type="text"
                                                        id="location_transporation_p" name="location_transporation"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Location Details </label>
                                                    <input class="form-control selectpicker" type="text"
                                                        id="location_details_p" name="location_details" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Regions </label>
                                                    {!! Form::select('location_region_p', $regions, null, [
                                                        'class' => 'form-control selectpicker',
                                                        'data-live-search' => 'true',
                                                        'data-size' => '5',
                                                        'id' => 'location_region_p',
                                                        'multiple' => true,
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Location Description </label>
                                                    <textarea id="location_description_p" name="location_description" class="form-control selectpicker" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{-- <label>Phones: <a id="addDataLocation"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                            <h4 class="title_edit text-left mb-25 mt-10 px-20">Phones
                                                {{-- <a id="addDataLocation" class="plus_delteicon bg-primary-color float-right"><img src="/frontend/assets/images/plus.png" alt="" title=""></a> --}}
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
                                                                    <p>Select “Main” if this is the organization's primary phone number (or leave blank) </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th style="width:200px;">Language(s)</th>
                                                        <th style="width:200px;position:relative;">Description
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>A description providing extra information about the phone service (e.g. any special arrangements for accessing, or details of availability at particular times).
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody id="addPhoneTrLocation">
                                                        <tr id="location_0">
                                                            <td>
                                                                <input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0">
                                                            </td>
                                                            <td>
                                                                {!! Form::select('phone_type[]', $phone_type, array_search('Voice', $phone_type->toArray()), [ 'class' => 'form-control selectpicker','data-live-search' => 'true','id' => 'phone_type_location_0','data-size' => 5,'placeholder' => 'select phone type',]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('phone_language[]',$phone_languages,[],['class' => 'form-control selectpicker phone_language','data-size' => 5,'data-live-search' =>'true','multiple' => true,'id' => 'phone_language_location_0',],) !!}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"name="phone_description[]"id="phone_description_location_0">
                                                            </td>
                                                            <td>
                                                                {{-- <a href="javascript:void(0)" id="addDataLocation" class="plus_delteicon bg-primary-color">
                                                                <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                            </a> --}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn locationCloseButton">Close</button>
                                <button type="button" id="locationSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- contact modal --}}
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="contactmodal">
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
                                                style="color: #000">Create New
                                                Data</b></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input contactRadio" type="radio"
                                            name="contactRadio" id="contactRadio1" value="existing">
                                        <label class="form-check-label" for="contactRadio1"><b
                                                style="color: #000">Existing
                                                Data</b></label>
                                    </div>

                                </div>
                                <div class="" id="existingContactData" style="display: none;">
                                    <select name="contacts" id="contactSelectData" class="form-control selectpicker"
                                        data-live-search="true" data-size="5">
                                        <option value="">Select Contacts</option>
                                        @foreach ($contactOrganization as $contact)
                                            <option value="{{ $contact }}"
                                                data-id="{{ $contact->contact_recordid }}">
                                                {{ $contact->contact_name }}</option>
                                        @endforeach
                                        {{-- @foreach ($all_contacts as $contact)
                            <option value="{{ $contact }}" data-id="{{ $contact->contact_recordid }}">
                                {{ $contact->contact_name }}</option>
                            @endforeach --}}
                                    </select>
                                </div>
                                <div id="newContactData">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" placeholder="Name"
                                                    id="contact_name_p">
                                                <span id="contact_name_error" style="display: none;color:red">Contact
                                                    Name is
                                                    required!</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                                <label>Visibility </label>
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
                                                                        phone
                                                                        number (or leave blank)
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th style="width:200px;">Language(s)</th>
                                                        <th style="width:200px;position:relative;">Description
                                                            <div class="help-tip" style="top:8px;">
                                                                <div>
                                                                    <p>A description providing extra information about the
                                                                        phone
                                                                        service (e.g. any special arrangements for
                                                                        accessing, or
                                                                        details of availability at particular times).
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody id="addPhoneTrContact">
                                                        <tr id="contact_0">
                                                            <td style="width: 20%;">
                                                                <input type="text" class="form-control"
                                                                    name="service_phones[]"
                                                                    id="service_phones_contact_0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="phone_extension[]"
                                                                    id="phone_extension_contact_0">
                                                            </td>
                                                            <td style="width: 15%;">
                                                                {!! Form::select('phone_type[]', $phone_type, array_search('Voice', $phone_type->toArray()), [
                                                                    'class' => 'form-control selectpicker',
                                                                    'data-live-search' => 'true',
                                                                    'id' => 'phone_type_contact_0',
                                                                    'data-size' => 5,
                                                                    'placeholder' => 'select
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    phone type',
                                                                ]) !!}
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
                                                            <td>
                                                                {{-- <a href="javascript:void(0)" id="addDataContact" class="plus_delteicon bg-primary-color">
                                                                <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                            </a> --}}
                                                            </td>
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


            <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('delete_service') }}" method="POST" id="service_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Delete service</h4>
                            </div>
                            <div class="modal-body text-center">
                                <input type="hidden" id="service_recordid" name="service_recordid">
                                <h4>Are you sure to delete this service?</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-lg btn_padding green_btn">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade bs-confirm-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" id="service_recordid" name="service_recordid">
                            <h4 id="ConfirmMessage"></h4>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"
                                class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Confirm</button>
                            <button type="button"
                                class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- alert modal --}}
            <div class="modal fade " id="alertModal" tabindex="-1" role="dialog"
                aria-labelledby="alertModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered " role="document">
                    <div class="modal-content" id="addClass">
                        <div class="modal-header ">
                            <h3 class="modal-title" id="exampleModalLongTitle" style="color:#fff">Alert</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="message"></div>
                        </div>
                        <div class="modal-footer text-center top_btn_data">
                            <button type="button" class="btn btn-default btn_black"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- detail term modal --}}
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="create_new_term">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close detailTermCloseButton"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Detail Term</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Detail Term</label>
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
                        {{-- <form action="/addInteractionService" method="POST"> --}}
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
                                            <input class="form-control selectpicker" type="text"
                                                id="interaction_notes_pop_up" name="interaction_notes" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Update Status: </label>
                                            {!! Form::select(
                                                'service_status',
                                                $serviceStatus,
                                                $service->service_status ? $service->service_status : $layout->default_service_status ?? null,
                                                ['class' => 'form-control selectpicker', 'id' => 'service_status_pop_up', 'placeholder' => 'Select Status'],
                                            ) !!}
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4 mt-25 text-center">
                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn waves-effect waves-classic" data-dismiss="modal" id="closeInteraction">Skip</button>
                            <button type="submit" class="btn btn-primary btn-lg btn_padding green_btn waves-effect waves-classic" id="submitInteraction"> Add</button>
                        </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
            {{-- end here --}}
        </div>
    </div>
    <script src="/js/jquery.timepicker.min.js"></script>
    @include('frontEnd.services.service_code_script')
    <script>
        $('.timePicker').timepicker({
            'scrollDefault': 'now'
        });
        let removePhoneDataId = []
        let deletePhoneDataId = []

        let selectedContactTrId = ''
        let selectedPhoneTrId = ''
        let editLocationData = false;
        let selectedLocationTrId = ''
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function() {
            $("#service_organization").selectpicker("");
            $("#service_locations").selectpicker("");
            $("#service_contacts").selectpicker("");
            $("#service_taxonomy").selectpicker("");
            $("#service_details").selectpicker("");
            $('#document_type_p').selectpicker("")
        });

        $('#closeInteraction').click(function() {
            $('#save-service-btn-submit').click();
        });
        $('#submitInteraction').click(function() {
            $('#interaction_method').val($('#interaction_method_pop_up').val());
            $('#interaction_disposition').val($('#interaction_disposition_pop_up').val());
            $('#interaction_notes').val($('#interaction_notes_pop_up').val());
            $('#service_status').val($('#service_status_pop_up').val());
            $('#service_notes').val(1);
            $('#save-service-btn-submit').click();
        });

        $(document).on("change", 'div > .detail_type', function() {
            let value = $(this).val()
            let id = $(this).attr('id')
            let idsArray = id ? id.split('_') : []
            let index = idsArray.length > 0 ? idsArray[2] : ''

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
                        $('#detail_term_' + index).append('<option value="' + i + '">' + v +'</option>');
                    })
                    $('#detail_term_' + index).append('<option value="create_new">+ Create New</option>');
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

            if (value.includes('create_new')) {
                $('#detail_term_index_p').val(index)
                $('#create_new_term').modal('show')
            } else if (value.includes(text)) {
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

        let d = {{ count($serviceDetailsData) > 0 ? count($serviceDetailsData) : 1 }}
        $('#addDetailTr').click(function() {
            $('#DetailTable').append('<tr><td><select name="detail_type[]" id="detail_type_' + d +'" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="detail_term[]" id="detail_term_' +d +'" class="form-control selectpicker detail_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="term_type[]" id="term_type_' + d +'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            d++;
        })

        $(document).on('click', '.removeData', function() {
            $(this).closest('tr').remove()
        });
        let i = {{ count($holiday_schedules) > 0 ? count($holiday_schedules) + 1 : 2 }};
        $('#addTr').click(function() {
            $('#myTable tr:last').before(
                '<tr><td><input class="form-control" type="date" name="holiday_start_date[]" id=""></td><td><input class="form-control" type="date" name="holiday_end_date[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_open_at[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_close_at[]" id=""></td><td><input  type="checkbox" name="holiday_closed[]" id="" value="' +i + '" ></td><td><input  type="checkbox" name="holiday_open_24_hours[]" id="" value="' +i + '" ></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            i++;
            $('.timePicker').timepicker({
                'scrollDefault': 'now'
            });
        });
        // phone table section
        // let phone_language_data = JSON.parse($('#phone_language_data').val())
        // $(document).on('change','div > .phone_language',function () {
        //     let value = $(this).val()
        //     let id = $(this).attr('id')
        //     let idsArray = id ? id.split('_') : []
        //     let index = idsArray.length > 0 ? idsArray[2] : ''
        //     phone_language_data[index] = value
        //     $('#phone_language_data').val(JSON.stringify(phone_language_data))
        // })
        // pt = {{ count($service->phone) }}
        // $('#addPhoneTr').click(function(){
        //     $('#PhoneTable tr:last').before('<tr><td><input type="text" class="form-control" name="service_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select( 'phone_type[]', $phone_type, [], [ 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'id' => 'phone_type', 'data-size' => 5, 'placeholder' => 'select phone type', ], ) !!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td><div class="form-check form-check-inline" style="margin-top: -10px;"><input class="form-check-input " type="radio" name="main_priority[]" id="main_priority'+pt+'" value="'+pt+'" ><label class="form-check-label" for="main_priority'+pt+'"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        //     $('.selectpicker').selectpicker();
        //     pt++;
        // })
        // let cp = JSON.parse($('#contact_phone_numbers').val()).length;
        let cp = 1;
        $('#addDataContact').click(function() {
            $('#addPhoneTrContact').append('<tr id="contact_' + cp +'"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_' +cp +'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_' +cp + '"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_contact_' + cp +'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : "" }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_' +cp +'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_' +cp +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            cp++
        })
        // let lp = JSON.parse($('#location_phone_numbers').val()).length;
        let lp = 1;
        $(document).on('click', '#addDataLocation', function() {
            // $('#addDataLocation').click(function(){
            $('#addPhoneTrLocation').append('<tr id="location_' + lp +'"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_' +lp +'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_' +lp + '"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_location_' +lp +'" class="form-control selectpicker" data-live-search="true" data-size="5"><option value="">Select phone type</option> @foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_' +lp +'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_' +lp +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            lp++
        })
        let ls = 1;
        $('#addScheduleHolidayLocation').click(function() {
            $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_' +ls +'"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_' +ls +'"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_' +ls +'"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_' +ls + '"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_' +ls +'" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
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
                    if ($('#contact_phone_numbers').val()) {
                        let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val());
                        let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val());
                        let contact_phone_types = JSON.parse($('#contact_phone_types').val());
                        let contact_phone_languages = JSON.parse($('#contact_phone_languages').val());
                        let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val());

                        contact_phone_numbers[selectedContactTrId] ? contact_phone_numbers[selectedContactTrId].splice(deletedId, 1) : contact_phone_numbers
                        contact_phone_extensions[selectedContactTrId] ? contact_phone_extensions[selectedContactTrId].splice(deletedId, 1) : contact_phone_extensions
                        contact_phone_types[selectedContactTrId] ? contact_phone_types[selectedContactTrId].splice(deletedId, 1) : contact_phone_types
                        contact_phone_languages[selectedContactTrId] ? contact_phone_languages[selectedContactTrId].splice(deletedId, 1) : contact_phone_languages
                        contact_phone_descriptions[selectedContactTrId] ? contact_phone_descriptions[selectedContactTrId].splice(deletedId, 1) : contact_phone_descriptions

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
                    cp = contact_phone_numbers[selectedContactTrId] ? contact_phone_numbers[selectedContactTrId].length : j

                } else if (name == 'location') {
                    if ($('#location_phone_numbers').val() && $('#location_phone_extensions').val()) {
                        let location_phone_numbers = JSON.parse($('#location_phone_numbers').val());
                        let location_phone_extensions = JSON.parse($('#location_phone_extensions').val());
                        let location_phone_types = JSON.parse($('#location_phone_types').val());
                        let location_phone_languages = JSON.parse($('#location_phone_languages').val());
                        let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val());

                        location_phone_numbers[selectedLocationTrId] ? location_phone_numbers[selectedLocationTrId].splice(deletedId, 1) : location_phone_numbers
                        location_phone_extensions[selectedLocationTrId] ? location_phone_extensions[selectedLocationTrId].splice(deletedId, 1) : location_phone_extensions
                        location_phone_types[selectedLocationTrId] ? location_phone_types[selectedLocationTrId].splice(deletedId, 1) : location_phone_types
                        location_phone_languages[selectedLocationTrId] ? location_phone_languages[selectedLocationTrId].splice(deletedId, 1) : location_phone_languages
                        location_phone_descriptions[selectedLocationTrId] ? location_phone_descriptions[selectedLocationTrId].splice(deletedId, 1) : location_phone_descriptions

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
                    lp = location_phone_numbers[selectedLocationTrId] ? location_phone_numbers[selectedLocationTrId].length : j

                }

            } else {
                $(this).closest('tr').remove()
            }
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
        $('button.delete-td').on('click', function() {
            var value = $(this).val();
            $('input#service_recordid').val(value);
        });

        $("#add-phone-input").click(function() {
            $("ol#phones-ul").append("<li class='service-phones-li mb-2  col-md-12 p-0'>" +"<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>" +"</li>");
        });

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
        let c = "{{ count($service->contact) }}";
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

                $('#contactsTable').append('<tr id="contactTr_' + c + '"><td>' + contact_name_p +'<input type="hidden" name="contact_name[]" value="' + contact_name_p +'" id="contact_name_' + c + '"></td><td>' + contact_title_p +'<input type="hidden" name="contact_title[]" value="' + contact_title_p +'" id="contact_title_' + c + '"></td><td class="text-center">' + contact_email_p +'<input type="hidden" name="contact_email[]" value="' + contact_email_p +'" id="contact_email_' + c + '"></td><td class="text-center">' + contact_visibility_p +'<input type="hidden" name="contact_visibility[]" value="' + contact_visibility_p +'" id="contact_visibility_' + c + '"></td><td class="text-center">' + contact_phone_list +'<input type="hidden" name="contact_phone[]" value="' + contact_phone_p +'" id="contact_phone_' + c +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeContactData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="' +contactRadioValue + '" id="selectedContactRadio_' + c +
                    '"><input type="hidden" name="contact_recordid[]" value="' + contact_recordid_p +
                    '" id="existingContactIds_' + c + '"></td></tr>');
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
                    $('#contactTr_' + selectedContactTrId).append('<td>' + contact_name_p +
                        '<input type="hidden" name="contact_name[]" value="' + contact_name_p +
                        '" id="contact_name_' + selectedContactTrId + '"></td><td>' + contact_title_p +
                        '<input type="hidden" name="contact_title[]" value="' + contact_title_p +
                        '" id="contact_title_' + selectedContactTrId + '"></td><td class="text-center">' +
                        contact_email_p + '<input type="hidden" name="contact_email[]" value="' +
                        contact_email_p + '" id="contact_email_' + selectedContactTrId +
                        '"></td><td class="text-center">' + contact_visibility_p +
                        '<input type="hidden" name="contact_visibility[]" value="' + contact_visibility_p +
                        '" id="contact_visibility_' + selectedContactTrId + '"></td><td class="text-center">' +
                        contact_phone_list + '<input type="hidden" name="contact_phone[]" value="' +
                        contact_phone_p + '" id="contact_phone_' + selectedContactTrId +
                        '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeContactData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="' +
                        contactRadioValue + '" id="selectedContactRadio_' + selectedContactTrId +
                        '"><input type="hidden" name="contact_recordid[]" value="' + contact_recordid_p +
                        '" id="existingContactIds_' + selectedContactTrId + '"></td>')
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
            $('#addPhoneTrContact').append(
                '<tr id="contact_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
            )
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
            // $('#addPhoneTrContact').empty()
            // $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
            $('.selectpicker').selectpicker('refresh');
            editContactData = false
            $("input[name=contactRadio][value='new_data']").prop("checked", true);
            $("input[name=contactRadio][value='existing']").prop("disabled", false);
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
            $('#addPhoneTrContact').append(
                '<tr id="contact_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ 'voice' == strtolower($value) ? 'selected' : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
            )
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
            for (let index = 1; index < phone_number_contact.length; index++) {
                $('#addPhoneTrContact').append('<tr id="contact_' + index +
                    '"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_' + index + '" value="' + phone_number_contact[index] +'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_' +index + '" value="' + (phone_extension_contact[index] != null ? phone_extension_contact[index] : "") +'"></td><td style="width: 15%;"><select name="phone_type" id="phone_type_contact_' + index +'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ 'voice' == strtolower($value) ? 'selected' : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language" id="phone_language_contact_' +index +'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_' +index + '" value="' + (phone_description_contact[index] != null ? phone_description_contact[index] : "") +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
                );

                if (phone_type_contact[index] != '') {
                    $("select[id='phone_type_contact_" + index + "'] option[value=" + phone_type_contact[index] +"]").prop('selected', true)
                }
                if (phone_language_contact[index] != '') {
                    for (let m = 0; m < phone_language_contact[index].length; m++) {
                        $("select[id='phone_language_contact_" + index + "'] option[value=" +phone_language_contact[index][m] + "]").prop('selected', true)
                    }
                }
                $('.selectpicker').selectpicker();
            }
            $('.selectpicker').selectpicker('refresh');
            cp = phone_number_contact.length != 0 ? phone_number_contact.length : 1

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
        // end contact section
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
        let l = "{{ count($service_locations_data) }}";
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
                // let accessibilities = data.accessibilities && data.accessibilities.length > 0 ? data.accessibilities : []
                // location_accessibility_p = accessibilities.map((v) => {
                //     return v.accessibility
                // }).join(',');
                location_accessibility_p = data.accessibility_recordid;

                // location_accessibility_details_p = accessibilities.map((v) => {
                //     return v.accessibility_details
                // }).join(',');
                location_accessibility_details_p = data.accessibility_details;

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

                $('#locationsTable').append('<tr id="locationTr_' + l + '"><td>' + location_name_p +'<input type="hidden" name="location_name[]" value="' + location_name_p +'" id="location_name_' + l + '"></td><td>' + location_address_p +'<input type="hidden" name="location_address[]" value="' + location_address_p +'" id="location_address_' + l + '"></td><td class="text-center">' + location_city_p +'<input type="hidden" name="location_city[]" value="' + location_city_p +'" id="location_city_' + l + '"></td><td class="text-center">' + location_state_p +'<input type="hidden" name="location_state[]" value="' + location_state_p +'" id="location_state_' + l + '"></td><td class="text-center">' + location_zipcode_p +'<input type="hidden" name="location_zipcode[]" value="' + location_zipcode_p +'" id="location_zipcode_' + l + '"></td><td class="text-center">' + location_phone_list +'<input type="hidden" name="location_phone[]" value="' + location_phone_p +'" id="location_phone_' + l +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="' +locationRadioValue + '" id="selectedLocationRadio_' + l +'"><input type="hidden" name="location_recordid[]" value="' + location_recordid_p +'" id="existingLocationIds_' + l + '"></td></tr>');
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
                    $('#locationTr_' + selectedLocationTrId).append('<td>' + location_name_p +'<input type="hidden" name="location_name[]" value="' + location_name_p +'" id="location_name_' + selectedLocationTrId + '"></td><td>' + location_address_p +'<input type="hidden" name="location_address[]" value="' + location_address_p +'" id="location_address_' + selectedLocationTrId + '"></td><td class="text-center">' +location_city_p + '<input type="hidden" name="location_city[]" value="' + location_city_p + '" id="location_city_' + selectedLocationTrId + '"></td><td class="text-center">' + location_state_p + '<input type="hidden" name="location_state[]" value="' + location_state_p +'" id="location_state_' + selectedLocationTrId + '"></td><td class="text-center">' + location_zipcode_p + '<input type="hidden" name="location_zipcode[]" value="' + location_zipcode_p + '" id="location_zipcode_' + selectedLocationTrId + '"></td><td class="text-center">' + location_phone_list + '<input type="hidden" name="location_phone[]" value="' + location_phone_p + '" id="location_phone_' + selectedLocationTrId + '"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="' + locationRadioValue + '" id="selectedLocationRadio_' + selectedLocationTrId +'"><input type="hidden" name="location_recordid[]" value="' + location_recordid_p +'" id="existingLocationIds_' + selectedLocationTrId + '"></td>')
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
            $('#addPhoneTrLocation').append(
                '<tr id="location_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ 'voice' == strtolower($value) ? 'selected' : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
            )
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
                        // $(this).find('td').find("input[name='location_phone[]']").attr("id","location_phone_"+i)
                    });
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
            $('#addPhoneTrLocation').empty()
            $('#addPhoneTrLocation').append(
                '<tr id="location_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ 'voice' == strtolower($value) ? 'selected' : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a class="float-right plus_delteicon bg-primary-color" id="addDataLocation"><img src="/frontend/assets/images/plus.png" alt="" title=""></a></td></tr>'
            )
            $('.selectpicker').selectpicker('refresh');

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
            $('#addPhoneTrLocation').append(
                '<tr id="location_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ 'voice' == strtolower($value) ? 'selected' : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a class="float-right plus_delteicon bg-primary-color" id="addDataLocation"><img src="/frontend/assets/images/plus.png" alt="" title=""></a></td></tr>'
            )
            $('.selectpicker').selectpicker('refresh');

            $('#scheduleHolidayLocation').empty()
            $('#scheduleHolidayLocation').append(
                '<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_0"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_0"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
            );
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
                $('#addPhoneTrLocation').append('<tr id="location_' + index +'"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_' +index + '" value="' + phone_number_location[index] +'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_' +index + '" value="' + (phone_extension_location[index] != null ? phone_extension_location[index] : "") +'"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_location_' +index +'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : '' }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_' +index +'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_' +index + '" value="' + (phone_description_location[index] != null ?phone_description_location[index] : "") +'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
                );

                if (phone_type_location[index] != '') {
                    $("select[id='phone_type_location_" + index + "'] option[value=" + phone_type_location[index] +"]").prop('selected', true)
                }
                if (phone_language_location[index] != '') {
                    for (let m = 0; m < phone_language_location[index].length; m++) {
                        $("select[id='phone_language_location_" + index + "'] option[value=" +phone_language_location[index][m] + "]").prop('selected', true)
                    }
                }
                $('.selectpicker').selectpicker();
            }
            $('.selectpicker').selectpicker('refresh');
            // $('#location_city_p').selectpicker('refresh');
            // $('#location_state_p').selectpicker('refresh');
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

            let location_holiday_start_dates = location_holiday_start_dates_val[id] ? location_holiday_start_dates_val[id] : []
            let location_holiday_end_dates = location_holiday_end_dates_val[id] ? location_holiday_end_dates_val[id] : []
            let location_holiday_open_ats = location_holiday_open_ats_val[id] ? location_holiday_open_ats_val[id] : []
            let location_holiday_close_ats = location_holiday_close_ats_val[id] ? location_holiday_close_ats_val[id] : []
            let location_holiday_closeds = location_holiday_closeds_val[id] ? location_holiday_closeds_val[id] : []

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
            $('#holiday_start_date_location_0').val(location_holiday_start_dates[0])
            $('#holiday_end_date_location_0').val(location_holiday_end_dates[0])
            $('#holiday_open_at_location_0').val(location_holiday_open_ats[0])
            $('#holiday_close_at_location_0').val(location_holiday_close_ats[0])
            $('#holiday_closed_location_0').val(1)

            if (location_holiday_closeds[0] == 1) {
                $('#holiday_closed_location_0').attr('checked', true)
            } else {
                $('#holiday_closed_location_0').attr('checked', false)
            }

            for (let index = 1; index < location_holiday_start_dates.length; index++) {
                $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_' +index + '" value="' + location_holiday_start_dates[index] +'"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_' +index + '" value="' + location_holiday_end_dates[index] +'"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_' +index + '" value="' + location_holiday_open_ats[index] +'"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_' +index + '" value="' + location_holiday_close_ats[index] +'"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_' +index +'" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
                );
                $('.timePicker').timepicker({
                    'scrollDefault': 'now'
                });
                if (location_holiday_closeds[index] == 1) {
                    $('#holiday_closed_location_' + index).attr('checked', true)
                } else {
                    $('#holiday_closed_location_' + index).attr('checked', false)
                }
            }

            ls = location_holiday_start_dates.length

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
            $('#location_city_p').val(location_city_p)
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

        $(document).ready(() => {
            $('#accordion-condition').on('shown.bs.collapse', function() {
                $('#accordion-condition .collapse.show').closest('.card').addClass('active');
            })
            $('#accordion-condition').on('hide.bs.collapse', function() {
                $('#accordion-condition .collapse.show').closest('.card').removeClass('active');
            })

            $('#accordion-activities').on('shown.bs.collapse', function() {
                $('#accordion-activities .collapse.show').closest('.card').addClass('active');
            })
            $('#accordion-activities').on('hide.bs.collapse', function() {
                $('#accordion-activities .collapse.show').closest('.card').removeClass('active');
            })

            $('#accordion-goals').on('shown.bs.collapse', function() {
                $('#accordion-goals .collapse.show').closest('.card').addClass('active');
            })
            $('#accordion-goals').on('hide.bs.collapse', function() {
                $('#accordion-goals .collapse.show').closest('.card').removeClass('active');
            })
        })
    </script>
@endsection
