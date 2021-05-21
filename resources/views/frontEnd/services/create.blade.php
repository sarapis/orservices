@extends('layouts.app')
@section('title')
Service Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="service_organization"],
    button[data-id="service_locations"],
    button[data-id="service_status"],
    button[data-id="service_taxonomies"],
    button[data-id="service_schedules"],
    button[data-id="service_contacts"],
    button[data-id="service_details"],
    button[data-id="service_address"],
    button[data-id="phone_type"],
    button[data-id="phone_language"],
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
</style>

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <h4 class="card-title title_edit mb-30">
                    Create New Service
                </h4>
                {!! Form::open(['route' => 'services.store']) !!}
                <div class="card all_form_field">
                    <div class="card-block">
                        {{-- <h4 class="card-title mb-30 ">
                                <p>Create New Service</p>
                            </h4> --}}
                        {{-- <form action="/add_new_service" method="GET"> --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Name:</label>
                                    <div class="help-tip">
                                        <div>
                                            <p>The official or public name of the service.</p>
                                        </div>
                                    </div>
                                    <input class="form-control selectpicker" type="text" id="service_name"
                                        name="service_name" value="">
                                    @error('service_name')
                                    <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Alternate Name: </label>
                                    <div class="help-tip">
                                        <div>
                                            <p>Alternative or commonly used name for a service.</p>
                                        </div>
                                    </div>
                                    <input class="form-control selectpicker" type="text" id="service_alternate_name"
                                        name="service_alternate_name" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization Name: </label>

                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="service_organization" name="service_organization" data-size="5">
                                        @foreach($organization_name_list as $key => $org_name)
                                        <option value="{{$org_name}}">{{$org_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('service_organization')
                                    <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Service Description: </label>
                                    <div class="help-tip">
                                        <div>
                                            <p>A description of the service.</p>
                                        </div>
                                    </div>
                                    <textarea id="service_description" name="service_description" class="selectpicker"
                                        rows="5"></textarea>
                                    {{-- <input class="form-control selectpicker" type="text" id="service_description"
                                            name="service_description" value="" rows="5" > --}}
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Locations: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_locations"
                                            name="service_locations[]" data-size="5" >
                                            @foreach($facility_info_list as $key => $location_info)
                                            <option value="{{$location_info->location_recordid}}">{{$location_info->location_name}}
                            </option>
                            @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Service URL (website) </label>
                            <div class="help-tip">
                                <div>
                                    <p>URL of the service</p>
                                </div>
                            </div>
                            <input class="form-control selectpicker" type="text" id="service_url" name="service_url"
                                value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('service_email') ? 'has-error' : ''}}">
                            <label>Service Email: </label>
                            <div class="help-tip">
                                <div>
                                    <p>Email address for the service, if any.</p>
                                </div>
                            </div>
                            <input class="form-control selectpicker" type="text" id="service_email" name="service_email"
                                value="">
                            @error('service_email')
                            <span class="error-message"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Access Requirement</label>
                            {{-- <div class="help-tip">
                                <div>
                                    <p>URL of the service</p>
                                </div>
                            </div> --}}
                            {!! Form::select('access_requirement',['none'=>'None','yes'=>'Yes'],'none',['class' => 'form-control selectpicker']) !!}
                        </div>
                    </div>

            <div class="text-right col-md-12 mb-20">
                <button type="button" class="btn btn_additional bg-primary-color" data-toggle="collapse"
                    data-target="#demo">Additional Info
                    <img src="/frontend/assets/images/white_arrow.png" alt="" title="" />
                </button>
            </div>
            <div id="demo" class="collapse row m-0">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Licenses: </label>
                        <div class="help-tip">
                            <div>
                                <p>An organization may have a license issued by a government entity to operate legally.
                                    A list of any such licenses can be provided here.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_licenses"
                            name="service_licenses" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Application Process: </label>
                        <div class="help-tip">
                            <div>
                                <p>The steps needed to access the service.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_application_process"
                            name="service_application_process" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Wait Time: </label>
                        <div class="help-tip">
                            <div>
                                <p>Time a client may expect to wait before receiving a service.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_wait_time"
                            name="service_wait_time" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fees: </label>
                        <div class="help-tip">
                            <div>
                                <p>Details of any charges for service users to access this service.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_fees" name="service_fees"
                            value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Accrediations: </label>
                        <div class="help-tip">
                            <div>
                                <p>Details of any accreditations. Accreditation is the formal evaluation of an
                                    organization or program against best practice standards set by an accrediting
                                    organization.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_accrediations"
                            name="service_accrediations" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Service Grouping: </label>
                        <div class="help-tip">
                            <div>
                                <p>Some organizations organize their services into service groupings (e.g., Senior
                                    Services).. A service grouping brings together a number of related services.</p>
                            </div>
                        </div>
                        <input class="form-control selectpicker" type="text" id="service_program" name="service_program"
                            value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Service Grouping Description: </label>
                        {{-- <div class="help-tip">
                                                <div><p></p></div>
                                            </div> --}}
                        <textarea name="program_alternate_name" id="program_alternate_name" cols="30" rows="10"
                            class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status(Verified): </label>
                        <select class="form-control selectpicker" data-live-search="true" id="service_status"
                            name="service_status" data-size="5">
                            <option value="">Select status</option>
                            @foreach($service_status_list as $key => $service_status)
                            <option value="{{$service_status}}">{{$service_status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

{{-- taxonomy section --}}
</div>
</div>
{{-- </form> --}}
</div>
</div>
<div class="card all_form_field">
    <div class="card-block">
        <h4 class="title_edit text-left mb-25 mt-10">
            Category
            <div class="d-inline float-right" id="addServiceCategoryTr">
                <a href="javascript:void(0)" id="addServiceCategoryData" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="">
                        <table class="table table_border_none " id="ServiceCategoryTable">
                            <thead>
                                <th>Type</th>
                                <th>Term</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {!! Form::select('service_category_type[]',$service_category_types,null,['class'
                                        => 'form-control selectpicker service_category_type','placeholder' => 'Select
                                        Type','id' => 'service_category_type_0']) !!}

                                    </td>
                                    <td class="create_btn">
                                        {!! Form::select('service_category_term[]',[],null,['class' => 'form-control
                                        selectpicker service_category_term','placeholder' => 'Select Term','id' =>
                                        'service_category_term_0']) !!}
                                        <input type="hidden" name="service_category_term_type[]"
                                            id="service_category_term_type_0" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                                {{-- <tr id="addServiceCategoryTr">
                                                        <td colspan="6" class="text-center">
                                                            <a href="javascript:void(0)" id="addServiceCategoryData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
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

<div class="card all_form_field">
    <div class="card-block">
        <h4 class="title_edit text-left mb-25 mt-10">
            Eligibility
            <div class="d-inline float-right" id="addServiceEligibilityTr">
                <a href="javascript:void(0)" id="addServiceEligibilityData" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="">
                        <table class="table table_border_none" id="ServiceEligibilityTable">
                            <thead>
                                <th>Type</th>
                                <th>Term</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {!!
                                        Form::select('service_eligibility_type[]',$service_eligibility_types,null,['class'
                                        => 'form-control selectpicker service_eligibility_type','placeholder' => 'Select
                                        Service Eligibility Type','id' => 'service_eligibility_type_0']) !!}
                                    </td>
                                    <td class="create_btn">
                                        {!! Form::select('service_eligibility_term[]',[],null,['class' => 'form-control
                                        selectpicker service_eligibility_term','placeholder' => 'Select Service
                                        Eligibility Term','id' => 'service_eligibility_term_0']) !!}
                                        <input type="hidden" name="service_eligibility_term_type[]"
                                            id="service_eligibility_term_type_0" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                                {{-- <tr id="addServiceEligibilityTr">
                                                        <td colspan="6" class="text-center">
                                                            <a href="javascript:void(0)" id="addServiceEligibilityData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
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
                                <tr>
                                    <td>
                                        {!! Form::select('detail_type[]',$detail_types,null,['class' => 'form-control
                                        selectpicker detail_type','placeholder' => 'Select Detail Type','id' =>
                                        'detail_type_0']) !!}

                                    </td>
                                    <td class="create_btn">
                                        {!! Form::select('detail_term[]',[],null,['class' => 'form-control selectpicker
                                        detail_term','placeholder' => 'Select Detail Term','id' => 'detail_term_0']) !!}
                                        <input type="hidden" name="term_type[]" id="term_type_0" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
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
            {{-- end here --}}

            {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label>
                                        <ol id="phones-ul" class="row p-0 m-0" style="list-style: none; ">
                                            <li class="service-phones-li mb-2 col-md-4">
                                                <input class="form-control selectpicker service_phones"  type="text" name="service_phones[]" value="">
                                            </li>
                                        </ol>
                                    </div>
                                </div> --}}
            {{-- location table --}}
        </div>
    </div>
</div>

<div class="card all_form_field">
    <div class="card-block">
        {{-- phone table --}}
        <h4 class="title_edit text-left mb-25 mt-10">
            Phones
            <div class="d-inline float-right" id="addPhoneTr">
                <a href="javascript:void(0)" id="addData" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="">
                        <table class="table table_border_none" id="PhoneTable">
                            <thead>
                                <th>Number</th>
                                <th>extension</th>
                                <th style="width:200px;position:relative;">Type
                                    <div class="help-tip" style="top:8px;">
                                        <div>
                                            <p>Select “Main” if this is the organization's primary phone number (or
                                                leave blank)
                                            </p>
                                        </div>
                                    </div>
                                </th>
                                <th style="width:200px;">Language(s)</th>
                                <th style="width:200px;position:relative;">Description
                                    <div class="help-tip" style="top:8px;">
                                        <div>
                                            <p>A description providing extra information about the phone service (e.g.
                                                any special arrangements for accessing, or details of availability at
                                                particular times).
                                            </p>
                                        </div>
                                    </div>
                                </th>
                                <th>Main</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="service_phones[]" id="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_extension[]" id="">
                                    </td>
                                    <td>
                                        {!! Form::select('phone_type[]',$phone_type,[],['class' => 'form-control
                                        selectpicker','data-live-search' => 'true','id' => 'phone_type','data-size' =>
                                        5,'placeholder' => 'select phone type'])!!}
                                    </td>
                                    <td>
                                        {!! Form::select('phone_language[]',$phone_languages,[],['class' =>
                                        'form-control selectpicker phone_language','data-size' => 5,' data-live-search'
                                        => 'true',"multiple" => true,"id" => "phone_language_0"]) !!}
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_description[]" id="">
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline" style="margin-top: -10px;">
                                            <input class="form-check-input " type="radio" name="main_priority[]" id="main_priority" value="1" checked>
                                            <label class="form-check-label" for="main_priority"></label>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                                {{-- <tr id="addPhoneTr">
                                                        <td colspan="6" class="text-center">
                                                            <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                                        </td>
                                                    </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="phone_language_data" id="phone_language_data">
        </div>
        <hr />
        {{-- end here --}}

        {{-- contact table --}}
        <h4 class="title_edit text-left mb-25 mt-10">
            Contacts <a class="contactModalOpenButton float-right plus_delteicon bg-primary-color"><img
                    src="/frontend/assets/images/plus.png" alt="" title=""></a>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table_border_none">
                            <thead>
                                <th>Name</th>
                                <th>Title</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody id="contactsTable">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- end here --}}
        <h4 class="title_edit text-left mb-25 mt-10">
            Locations
            <a class="locationModalOpenButton float-right plus_delteicon bg-primary-color">
                <img src="/frontend/assets/images/plus.png" alt="" title="">
            </a>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table_border_none">
                            <thead>
                                <th>Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zipcode</th>
                                <th>Phone</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody id="locationsTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        {{-- location table end here --}}
        <input type="hidden" name="location_alternate_name[]" id="location_alternate_name">
        <input type="hidden" name="location_transporation[]" id="location_transporation">
        <input type="hidden" name="location_service[]" id="location_service">
        <input type="hidden" name="location_schedules[]" id="location_schedules">
        <input type="hidden" name="location_description[]" id="location_description">
        <input type="hidden" name="location_details[]" id="location_details">
        <input type="hidden" name="contact_service[]" id="contact_service">
        <input type="hidden" name="contact_department[]" id="contact_department">
        {{-- contact phone --}}
        <input type="hidden" name="contact_phone_numbers[]" id="contact_phone_numbers">
        <input type="hidden" name="contact_phone_extensions[]" id="contact_phone_extensions">
        <input type="hidden" name="contact_phone_types[]" id="contact_phone_types">
        <input type="hidden" name="contact_phone_languages[]" id="contact_phone_languages">
        <input type="hidden" name="contact_phone_descriptions[]" id="contact_phone_descriptions">
        {{-- location phone --}}
        <input type="hidden" name="location_phone_numbers[]" id="location_phone_numbers" value="[]">
        <input type="hidden" name="location_phone_extensions[]" id="location_phone_extensions">
        <input type="hidden" name="location_phone_types[]" id="location_phone_types">
        <input type="hidden" name="location_phone_languages[]" id="location_phone_languages">
        <input type="hidden" name="location_phone_descriptions[]" id="location_phone_descriptions">

        {{-- schedule section --}}
        <input type="hidden" name="opens_at_location_monday_datas" id="opens_at_location_monday_datas">
        <input type="hidden" name="closes_at_location_monday_datas" id="closes_at_location_monday_datas">
        <input type="hidden" name="schedule_closed_monday_datas" id="schedule_closed_monday_datas">
        <input type="hidden" name="opens_at_location_tuesday_datas" id="opens_at_location_tuesday_datas">
        <input type="hidden" name="closes_at_location_tuesday_datas" id="closes_at_location_tuesday_datas">
        <input type="hidden" name="schedule_closed_tuesday_datas" id="schedule_closed_tuesday_datas">
        <input type="hidden" name="opens_at_location_wednesday_datas" id="opens_at_location_wednesday_datas">
        <input type="hidden" name="closes_at_location_wednesday_datas" id="closes_at_location_wednesday_datas">
        <input type="hidden" name="schedule_closed_wednesday_datas" id="schedule_closed_wednesday_datas">
        <input type="hidden" name="opens_at_location_thursday_datas" id="opens_at_location_thursday_datas">
        <input type="hidden" name="closes_at_location_thursday_datas" id="closes_at_location_thursday_datas">
        <input type="hidden" name="schedule_closed_thursday_datas" id="schedule_closed_thursday_datas">
        <input type="hidden" name="opens_at_location_friday_datas" id="opens_at_location_friday_datas">
        <input type="hidden" name="closes_at_location_friday_datas" id="closes_at_location_friday_datas">
        <input type="hidden" name="schedule_closed_friday_datas" id="schedule_closed_friday_datas">
        <input type="hidden" name="opens_at_location_saturday_datas" id="opens_at_location_saturday_datas">
        <input type="hidden" name="closes_at_location_saturday_datas" id="closes_at_location_saturday_datas">
        <input type="hidden" name="schedule_closed_saturday_datas" id="schedule_closed_saturday_datas">
        <input type="hidden" name="opens_at_location_sunday_datas" id="opens_at_location_sunday_datas">
        <input type="hidden" name="closes_at_location_sunday_datas" id="closes_at_location_sunday_datas">
        <input type="hidden" name="schedule_closed_sunday_datas" id="schedule_closed_sunday_datas">

        <input type="hidden" name="location_holiday_start_dates" id="location_holiday_start_dates">
        <input type="hidden" name="location_holiday_end_dates" id="location_holiday_end_dates">
        <input type="hidden" name="location_holiday_open_ats" id="location_holiday_open_ats">
        <input type="hidden" name="location_holiday_close_ats" id="location_holiday_close_ats">
        <input type="hidden" name="location_holiday_closeds" id="location_holiday_closeds">
    </div>
</div>
<div class="card all_form_field">
    <div class="card-block">
        <h4 class="title_edit text-left mb-25 mt-10">
            Regular Schedule
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table_border_none">
                            {{-- <thead>
                                                    <th colspan="4" class="text-center">Regular Schedule</th>
                                                </thead> --}}
                            <thead>
                                <th>Weekday</th>
                                <th>Opens</th>
                                <th>Closes</th>
                                <th style="width:150px;">Closed All Day</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Monday
                                        <input type="hidden" name="byday[]" value="monday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker','id' =>
                                        'opens_at']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="1">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Tuesday
                                        <input type="hidden" name="byday[]" value="tuesday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="2">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wednesday
                                        <input type="hidden" name="byday[]" value="wednesday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="3">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Thursday
                                        <input type="hidden" name="byday[]" value="thursday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="4">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Friday
                                        <input type="hidden" name="byday[]" value="friday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="5">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Saturday
                                        <input type="hidden" name="byday[]" value="saturday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="6">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sunday
                                        <input type="hidden" name="byday[]" value="sunday">
                                    </td>
                                    <td>
                                        {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                    </td>

                                    <td style="vertical-align: middle">
                                        <input type="checkbox" name="schedule_closed[]" id="" value="7">
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
            <div class="d-inline float-right">
                <a href="javascript:void(0)" id="addTr" class="plus_delteicon bg-primary-color">
                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                </a>
            </div>
        </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table_border_none" id="myTable">
                            <thead>
                                <th>Start</th>
                                <th>End</th>
                                <th>Opens</th>
                                <th>Closes</th>
                                <th>Closed All Day</th>
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>

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
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
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
        </div>
    </div>
</div>
<div class="col-md-12 text-center">
    <button type="button"
        class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"
        id="back-service-btn"> Back </button>
    <button type="submit"
        class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
        id="save-service-btn"> Save </button>
</div>
{!! Form::close() !!}
</div>
{{-- location Modal --}}
<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="locationmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close locationCloseButton"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Locations</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input locationRadio" type="radio" name="locationRadio"
                                id="locationRadio2" value="new_data" checked>
                            <label class="form-check-label" for="locationRadio2"><b style="color: #000">Create New
                                    Data</b></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input locationRadio" type="radio" name="locationRadio"
                                id="locationRadio1" value="existing">
                            <label class="form-check-label" for="locationRadio1"><b style="color: #000">Existing
                                    Data</b></label>
                        </div>
                    </div>
                    <div class="" id="existingLocationData" style="display: none;">
                        <select name="locations" id="locationSelectData" class="form-control selectpicker"
                            data-live-search="true" data-size="5">
                            <option value="">Select Locations</option>
                            @foreach ($all_locations as $location)
                            <option value="{{ $location }}" data-id="{{ $location->location_recordid }}">
                                {{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="newLocationData">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Name:</label>
                                    <input class="form-control selectpicker" type="text" id="location_name_p"
                                        name="location_name" value="">
                                    <span id="location_name_error" style="display: none;color:red">Location Name is
                                        required!</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Alternate Name: </label>
                                    <input class="form-control selectpicker" type="text" id="location_alternate_name_p"
                                        name="location_alternate_name" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Transportation: </label>
                                    <input class="form-control selectpicker" type="text" id="location_transporation_p"
                                        name="location_transporation" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description: </label>
                                    <textarea id="location_description_p" name="location_description"
                                        class="form-control selectpicker" rows="5"></textarea>
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
                                    <label>City: </label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="location_city_p" name="location_city" , data-size="5">
                                        <option value="">Select city</option>
                                        @foreach($address_city_list as $key => $address_city)
                                        <option value="{{$address_city}}">{{$address_city}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State: </label>
                                    <select class="form-control selectpicker" data-live-search="true"
                                        id="location_state_p" name="location_state" , data-size="5">
                                        <option value="">Select state</option>
                                        @foreach($address_states_list as $key => $address_state)
                                        <option value="{{$address_state}}">{{$address_state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Zip Code: </label>
                                    <input type="text" class="form-control" placeholder="Zipcode"
                                        id="location_zipcode_p">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Details: </label>
                                    <input class="form-control selectpicker" type="text" id="location_details_p"
                                        name="location_details" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <h4 class="title_edit text-left mb-25 mt-10 pl-20">
                                    Phones
                                </h4>
                                <div class="col-md-12">
                                    <table class="table table_border_none" id="PhoneTableLocation">
                                        <thead>
                                            <th>Number</th>
                                            <th>extension</th>
                                            <th style="width:200px;position:relative;">Type
                                                <div class="help-tip" style="top:8px;">
                                                    <div>
                                                        <p>Select “Main” if this is the organization's primary phone
                                                            number (or leave blank)
                                                        </p>
                                                    </div>
                                                </div>
                                            </th>
                                            <th style="width:200px;">Language(s)</th>
                                            <th style="width:200px;position:relative;">Description
                                                <div class="help-tip" style="top:8px;">
                                                    <div>
                                                        <p>A description providing extra information about the phone
                                                            service (e.g. any special arrangements for accessing, or
                                                            details of availability at particular times).
                                                        </p>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody id="addPhoneTrLocation">
                                            <tr id="location_0">
                                                <td>
                                                    <input type="text" class="form-control" name="service_phones[]"
                                                        id="service_phones_location_0">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="phone_extension[]"
                                                        id="phone_extension_location_0">
                                                </td>
                                                <td>
                                                    {!! Form::select('phone_type[]',$phone_type,[],['class' =>
                                                    'form-control selectpicker','data-live-search' => 'true','id' =>
                                                    'phone_type_location_0','data-size' => 5,'placeholder' => 'select
                                                    phone type'])!!}
                                                </td>
                                                <td>
                                                    {!! Form::select('phone_language[]',$phone_languages,[],['class' =>
                                                    'form-control selectpicker phone_language','data-size' => 5,'
                                                    data-live-search' => 'true',"multiple" => true,"id" =>
                                                    "phone_language_location_0"]) !!}
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="phone_description[]"
                                                        id="phone_description_location_0">
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" id="addDataLocation"
                                                        class="plus_delteicon bg-primary-color">
                                                        <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- schedule section --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{-- <label class="p-0">Regular Schedule: </label> --}}
                                    <h4 class="title_edit text-left mb-25 mt-10">
                                        Regular Schedule
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table">
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
                                                        <input type="hidden" name="byday" value="monday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control
                                                        timePicker','id'
                                                        => 'opens_at_location_monday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' => 'closes_at_location_monday'])
                                                        !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_monday"
                                                            value="1" id="schedule_closed_location_monday">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Tuesday
                                                        <input type="hidden" name="byday" value="tuesday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control
                                                        timePicker'
                                                        ,'id' => 'opens_at_location_tuesday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' => 'closes_at_location_tuesday'])
                                                        !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_tuesday"
                                                            value="2" id="schedule_closed_location_tuesday">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Wednesday
                                                        <input type="hidden" name="byday" value="wednesday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control
                                                        timePicker','id'
                                                        => 'opens_at_location_wednesday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' =>
                                                        'closes_at_location_wednesday']) !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_wednesday"
                                                            value="3" id="schedule_closed_location_wednesday">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Thursday
                                                        <input type="hidden" name="byday" value="thursday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control
                                                        timePicker','id'
                                                        => 'opens_at_location_thursday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at',null, ['class' => 'form-control
                                                        timePicker','id'
                                                        => 'closes_at_location_thursday']) !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_thursday"
                                                            value="4" id="schedule_closed_location_thursday">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Friday
                                                        <input type="hidden" name="byday" value="friday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at',null, ['class' => 'form-control timePicker','id'
                                                        => 'opens_at_location_friday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' => 'closes_at_location_friday']) !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_friday"
                                                            id="schedule_closed_location_friday" value="5">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Saturday
                                                        <input type="hidden" name="byday" value="saturday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                                                        => 'opens_at_location_saturday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' => 'closes_at_location_saturday']) !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_saturday"
                                                            id="schedule_closed_location_saturday" value="6">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sunday
                                                        <input type="hidden" name="byday" value="sunday">
                                                    </td>
                                                    <td>
                                                        {!! Form::text('opens_at', null, ['class' => 'form-control timePicker','id'
                                                        => 'opens_at_location_sunday']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('closes_at', null, ['class' =>
                                                        'form-control timePicker','id' => 'closes_at_location_sunday']) !!}
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="schedule_closed_location_sunday"
                                                            id="schedule_closed_location_sunday" value="7">
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
                                    <div class="table-responsive">
                                        <table class="table table_border_none" id="">
                                            <thead>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Opens</th>
                                                <th>Closes</th>
                                                <th>Closed All Day</th>
                                                <th>&nbsp;</th>
                                            </thead>
                                            <tbody id="scheduleHolidayLocation">
                                                <tr>
                                                    <td>
                                                        <input type="date" name="holiday_start_date"
                                                            id="holiday_start_date_location_0" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="date" name="holiday_end_date"
                                                            id="holiday_end_date_location_0" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="holiday_open_at"
                                                            id="holiday_open_at_location_0" class="form-control timePicker">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="holiday_close_at"
                                                            id="holiday_close_at_location_0" class="form-control timePicker">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="checkbox" name="holiday_closed"
                                                            id="holiday_closed_location_0" value="1">
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0)" id="addScheduleHolidayLocation"
                                                            class="plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                        </a>
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
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="contactmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close contactCloseButton"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Contacts</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input contactRadio" type="radio" name="contactRadio"
                                id="contactRadio2" value="new_data" checked>
                            <label class="form-check-label" for="contactRadio2"><b style="color: #000">Create New
                                    Data</b></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input contactRadio" type="radio" name="contactRadio"
                                id="contactRadio1" value="existing">
                            <label class="form-check-label" for="contactRadio1"><b style="color: #000">Existing
                                    Data</b></label>
                        </div>

                    </div>
                    <div class="" id="existingContactData" style="display: none;">
                        <select name="contacts" id="contactSelectData" class="form-control selectpicker"
                            data-live-search="true" data-size="5">
                            <option value="">Select Contacts</option>
                            @foreach ($all_contacts as $contact)
                            <option value="{{ $contact }}" data-id="{{ $contact->contact_recordid }}">
                                {{ $contact->contact_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="newContactData">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Name" id="contact_name_p">
                                    <span id="contact_name_error" style="display: none;color:red">Contact Name is
                                        required!</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Title" id="contact_title_p">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Department: </label>
                                    <input class="form-control selectpicker" type="text" id="contact_department_p"
                                        name="contact_department" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" placeholder="Email" id="contact_email_p">
                                </div>
                            </div>
                            <div class="form-group">
                                {{-- <a id="addDataContact"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> --}}
                                <h4 class="title_edit text-left mb-25 mt-10 px-20">Phones
                                    <a id="addDataContact" class="plus_delteicon bg-primary-color float-right"><img
                                            src="/frontend/assets/images/plus.png" alt="" title=""></a>
                                </h4>
                                <div class="col-md-12">
                                    <table class="table table_border_none" id="PhoneTableContact">
                                        <thead>
                                            <th>Number</th>
                                            <th>extension</th>
                                            <th style="width:200px;position:relative;">Type
                                                <div class="help-tip" style="top:8px;">
                                                    <div>
                                                        <p>Select “Main” if this is the organization's primary phone
                                                            number (or leave blank)
                                                        </p>
                                                    </div>
                                                </div>
                                            </th>
                                            <th style="width:200px;">Language(s)</th>
                                            <th style="width:200px;position:relative;">Description
                                                <div class="help-tip" style="top:8px;">
                                                    <div>
                                                        <p>A description providing extra information about the phone
                                                            service (e.g. any special arrangements for accessing, or
                                                            details of availability at particular times).
                                                        </p>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody id="addPhoneTrContact">
                                            <tr id="contact_0">
                                                <td>
                                                    <input type="text" class="form-control" name="service_phones[]"
                                                        id="service_phones_contact_0">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="phone_extension[]"
                                                        id="phone_extension_contact_0">
                                                </td>
                                                <td>
                                                    {!! Form::select('phone_type[]',$phone_type,[],['class' =>
                                                    'form-control selectpicker','data-live-search' => 'true','id' =>
                                                    'phone_type_contact_0','data-size' => 5,'placeholder' => 'select
                                                    phone type'])!!}
                                                </td>
                                                <td>
                                                    {!! Form::select('phone_language[]',$phone_languages,[],['class' =>
                                                    'form-control selectpicker','data-size' => 5,' data-live-search' =>
                                                    'true', 'id' => 'phone_language_contact_0','multiple' => true]) !!}
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="phone_description[]"
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
{{-- detail term modal --}}
<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="create_new_term">
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
                                <label>Detail Term</label>
                                <input type="text" class="form-control" placeholder="Detail Term" id="detail_term_p">
                                <input type="hidden" name="detail_term_index_p" value="" id="detail_term_index_p">
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
{{-- service category term modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
    id="create_new_service_category_term">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close serviceCategoryTermCloseButton"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Service Category Term</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{-- <label>Service Category Term</label> --}}
                                <input type="text" class="form-control" placeholder="Service Category Term"
                                    id="service_category_term_p">
                                <input type="hidden" name="service_category_term_index_p" value=""
                                    id="service_category_term_index_p">
                                <span id="service_category_term_error" style="display: none;color:red">Service Category
                                    Term is required!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn serviceCategoryTermCloseButton">Close</button>
                    <button type="button" id="serviceCategoryTermSubmit"
                        class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End here --}}
{{-- service eligibility term modal --}}
<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"
    id="create_new_service_eligibility_term">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close serviceEligibilityTermCloseButton"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Service Eligibility Term</h4>
                </div>
                <div class="modal-body all_form_field">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    style="margin-bottom:5px;font-weight:600;color: #000;letter-spacing: 0.5px;">Service
                                    Eligibility Term</label>
                                <input type="text" class="form-control" placeholder="Service Eligibility Term"
                                    id="service_eligibility_term_p">
                                <input type="hidden" name="service_eligibility_term_index_p" value=""
                                    id="service_eligibility_term_index_p">
                                <span id="service_eligibility_term_error" style="display: none;color:red">Service
                                    Eligibility Term is required!</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-lg btn_delete red_btn serviceEligibilityTermCloseButton">Close</button>
                    <button type="button" id="serviceEligibilityTermSubmit"
                        class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End here --}}
</div>
</div>
</div>



<script src="/js/jquery.timepicker.min.js"></script>
<script>
    $('.timePicker').timepicker({ 'scrollDefault': 'now' });
    $('#back-service-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).on("change",'div > .detail_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''
        if(value == ''){
            $('#detail_term_'+index).empty()
            $('#detail_term_'+index).val('')
            $('#detail_term_'+index).selectpicker('refresh')
            return false
        }
        $.ajax({
            url : '{{ route("getDetailTerm") }}',
            method : 'get',
            data : {value},
            success: function (response) {
                let data = response.data
                $('#detail_term_'+index).empty()
                $.each(data,function(i,v){
                    $('#detail_term_'+index).append('<option value="'+i+'">'+v+'</option>');
                })
                $('#detail_term_'+index).append('<option value="create_new">+ Create New</option>');
                $('#detail_term_'+index).val('')
                $('#detail_term_'+index).selectpicker('refresh')
            },
            error : function (error) {
                console.log(error)
            }
        })
    })
    $(document).on("change",'div >.detail_term', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let text = $( "#"+id+" option:selected" ).text();
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''

        if(value == 'create_new'){
            $('#detail_term_index_p').val(index)
            $('#create_new_term').modal('show')
        }else if(text == value){
            $('#term_type_'+index).val('new')
        }else{
            $('#term_type_'+index).val('old')
        }
    })
    $('#detailTermSubmit').click(function () {

        let detail_term = $('#detail_term_p').val()
        let index = $('#detail_term_index_p').val()
        if($('#detail_term_p').val() == ''){
                $('#detail_term_error').show()
            setTimeout(() => {
                $('#detail_term_error').hide()
            }, 5000);
            return false
        }
        $('#term_type_'+index).val('new')
        $('#detail_term_'+index).append('<option value="'+detail_term+'">'+detail_term+'</option>');
        $('#detail_term_'+index).val(detail_term)
        $('#detail_term_'+index).selectpicker('refresh')
        $('#create_new_term').modal('hide')
        $('#detail_term_p').val('')
        $('#detail_term_index_p').val('')
    })
    $('.detailTermCloseButton').click(function () {

        let detail_term = $('#detail_term_p').val()
        let index = $('#detail_term_index_p').val()
        $('#term_type_'+index).val('old')
        $('#detail_term_'+index).val('');
        $('#detail_term_'+index).selectpicker('refresh')
        $('#create_new_term').modal('hide')
        $('#detail_term_p').val('')
        $('#detail_term_index_p').val('')
    })
    let d = 1
    $('#addDetailTr').click(function(){
        $('#DetailTable tr:last').before('<tr><td><select name="detail_type[]" id="detail_type_'+d+'" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="detail_term[]" id="detail_term_'+d+'" class="form-control selectpicker detail_term"><option value="">Select Detail term</option> </select><input type="hidden" name="term_type[]" id="term_type_'+d+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        d++;
    })

    $(document).on("change",'div > .service_category_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''
        if(value == ''){
            $('#service_category_term_'+index).empty()
            $('#service_category_term_'+index).val('')
            $('#service_category_term_'+index).selectpicker('refresh')
            return false
        }
        $.ajax({
            url : '{{ route("getTaxonomyTerm") }}',
            method : 'get',
            data : {value},
            success: function (response) {
                let data = response.data
                $('#service_category_term_'+index).empty()
                $('#service_category_term_'+index).append('<option value="">Select term</option>');
                $.each(data,function(i,v){
                    $('#service_category_term_'+index).append('<option value="'+i+'">'+v+'</option>');
                })
                $('#service_category_term_'+index).append('<option value="create_new">+ Create New</option>');
                $('#service_category_term_'+index).val('')
                $('#service_category_term_'+index).selectpicker('refresh')
            },
            error : function (error) {
                console.log(error)
            }
        })
    })
    $(document).on("change",'div >.service_category_term', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let text = $( "#"+id+" option:selected" ).text();
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''

        if(value == 'create_new'){
            $('#service_category_term_index_p').val(index)
            $('#create_new_service_category_term').modal('show')
        }else if(text == value){
            $('#service_category__type_'+index).val('new')
        }else{
            $('#service_category__type_'+index).val('old')
        }
    })
    $('#serviceCategoryTermSubmit').click(function () {

        // let service_category_term = $('#service_category_term_p').val()
        // let index = $('#service_category_term_index_p').val()
        // if($('#service_category_term_p').val() == ''){
        //         $('#service_category_term_error').show()
        //     setTimeout(() => {
        //         $('#service_category_term_error').hide()
        //     }, 5000);
        //     return false
        // }
        // $('#service_category_term_type_'+index).val('new')
        // $('#service_category_term_'+index).append('<option value="'+service_category_term+'">'+service_category_term+'</option>');
        // $('#service_category_term_'+index).val(service_category_term)
        // $('#service_category_term_'+index).selectpicker('refresh')
        // $('#create_new_service_category_term').modal('hide')
        // $('#service_category_term_p').val('')
        // $('#service_category_term_index_p').val('')
        let service_category_term = $('#service_category_term_p').val()
        let index = $('#service_category_term_index_p').val()
        let category_type_recordid = $('#service_category_type_'+index).val()
        let _token = "{{ csrf_token() }}"
        let service_recordid = ""
        let organization_recordid = ""
        $.ajax({
            url : '{{ route("saveTaxonomyTerm") }}',
            method : 'post',
            data : {category_type_recordid,service_category_term,_token,service_recordid,organization_recordid},
            success: function (response) {
                $('#loading').hide()
                alert('Thank you for submitting a new term. It is being evaluated by the system administrators. We will let you know if it becomes available.');
                $('#service_category_type_'+index).val('')
                $('#service_category_term_'+index).empty()
                $('#service_category_term_'+index).selectpicker('refresh')
                $('#service_category_type_'+index).selectpicker('refresh')
                $('#create_new_service_category_term').modal('hide')
                $('#service_category_term_p').val('')
            },
            error : function (error) {
                $('#loading').hide()
                $('#create_new_service_category_term').modal('hide')
                console.log(error)
            }
        })
    })
    $('.serviceCategoryTermCloseButton').click(function () {

        let detail_term = $('#service_category_term_p').val()
        let index = $('#service_category_term_index_p').val()
        $('#service_category_term_type_'+index).val('old')
        $('#service_category_term_'+index).val('');
        $('#service_category_term_'+index).selectpicker('refresh')
        $('#create_new_service_category_term').modal('hide')
        $('#service_category_term_p').val('')
        $('#service_category_term_index_p').val('')
    })


    $(document).on("change",'div > .service_eligibility_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''
        if(value == ''){
            $('#service_eligibility_term_'+index).empty()
            $('#service_eligibility_term_'+index).val('')
            $('#service_eligibility_term_'+index).selectpicker('refresh')
            return false
        }
        $.ajax({
            url : '{{ route("getTaxonomyTerm") }}',
            method : 'get',
            data : {value},
            success: function (response) {
                let data = response.data
                $('#service_eligibility_term_'+index).empty()
                $('#service_eligibility_term_'+index).append('<option value="">Select term</option>');
                $.each(data,function(i,v){
                    $('#service_eligibility_term_'+index).append('<option value="'+i+'">'+v+'</option>');
                })
                $('#service_eligibility_term_'+index).append('<option value="create_new">+ Create New</option>');
                $('#service_eligibility_term_'+index).val('')
                $('#service_eligibility_term_'+index).selectpicker('refresh')
            },
            error : function (error) {
                console.log(error)
            }
        })
    })
    $(document).on("change",'div >.service_eligibility_term', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let text = $( "#"+id+" option:selected" ).text();
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[3] : ''

        if(value == 'create_new'){
            $('#service_eligibility_term_index_p').val(index)
            $('#create_new_service_eligibility_term').modal('show')
        }else if(text == value){
            $('#service_eligibility__type_'+index).val('new')
        }else{
            $('#service_eligibility__type_'+index).val('old')
        }
    })
    $('#serviceEligibilityTermSubmit').click(function () {

        let service_eligibility_term = $('#service_eligibility_term_p').val()
        let index = $('#service_eligibility_term_index_p').val()
        if($('#service_eligibility_term_p').val() == ''){
                $('#service_eligibility_term_error').show()
            setTimeout(() => {
                $('#service_eligibility_term_error').hide()
            }, 5000);
            return false
        }
        $('#service_eligibility_term_type_'+index).val('new')
        $('#service_eligibility_term_'+index).append('<option value="'+service_eligibility_term+'">'+service_eligibility_term+'</option>');
        $('#service_eligibility_term_'+index).val(service_eligibility_term)
        $('#service_eligibility_term_'+index).selectpicker('refresh')
        $('#create_new_service_eligibility_term').modal('hide')
        $('#service_eligibility_term_p').val('')
        $('#service_eligibility_term_index_p').val('')
    })
    $('.serviceEligibilityTermCloseButton').click(function () {

        let detail_term = $('#service_eligibility_term_p').val()
        let index = $('#service_eligibility_term_index_p').val()
        $('#service_eligibility_term_type_'+index).val('old')
        $('#service_eligibility_term_'+index).val('');
        $('#service_eligibility_term_'+index).selectpicker('refresh')
        $('#create_new_service_eligibility_term').modal('hide')
        $('#service_eligibility_term_p').val('')
        $('#service_eligibility_term_index_p').val('')
    })

    let sc = 1
    $('#addServiceCategoryTr').click(function(){
        $('#ServiceCategoryTable tr:last').before('<tr><td><select name="service_category_type[]" id="service_category_type_'+sc+'" class="form-control selectpicker service_category_type"><option value="">Select Type</option> @foreach ($service_category_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="service_category_term[]" id="service_category_term_'+sc+'" class="form-control selectpicker service_category_term"></select><input type="hidden" name="service_category_term_type[]" id="service_category_term_type_'+sc+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        sc++;
    })
    let se = 1
    $('#addServiceEligibilityTr').click(function(){
        $('#ServiceEligibilityTable tr:last').before('<tr><td><select name="service_eligibility_type[]" id="service_eligibility_type_'+se+'" class="form-control selectpicker service_eligibility_type"><option value="">Select Type</option> @foreach ($service_eligibility_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="service_eligibility_term[]" id="service_eligibility_term_'+se+'" class="form-control selectpicker service_eligibility_term"></select><input type="hidden" name="service_eligibility_term_type[]" id="service_eligibility_term_type_'+se+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        se++;
    })

    let editContactData = false;
    let selectedContactTrId = ''
    let editLocationData = false;
    let selectedLocationTrId = ''
    $(document).ready(function() {
        $('select#service_organization').val([]).change();
        $('select#service_locations').val([]).change();
        $('select#service_schedules').val([]).change();
    });
    let phone_language_data = []
    $(document).on('change','div > .phone_language',function () {
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''
        phone_language_data[index] = value
        $('#phone_language_data').val(JSON.stringify(phone_language_data))
    })
    let hs = 2
    $('#addTr').click(function(){
        $('#myTable tr:last').before('<tr><td><input class="form-control" type="date" name="holiday_start_date[]" id=""></td><td><input class="form-control" type="date" name="holiday_end_date[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_open_at[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_close_at[]" id=""></td><td><input  type="checkbox" name="holiday_closed[]" id="" value="'+hs+'" ></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        hs++;
        $('.timePicker').timepicker({ 'scrollDefault': 'now' });
    });
    pt = 1
    $('#addPhoneTr').click(function(){
        $('#PhoneTable tr:last').before('<tr ><td><input type="text" class="form-control" name="service_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select("phone_type[]",$phone_type,[],["class" => "form-control selectpicker","data-live-search" => "true","id" => "phone_type","data-size" => 5,"placeholder" => "select phone type"])!!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key=>$value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td><div class="form-check form-check-inline" style="margin-top: -10px;"> <input class="form-check-input " type="radio" name="main_priority[]" id="main_priority" value="1" > <label class="form-check-label" for="main_priority"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        pt++;
    })
    let cp = 1;
    $('#addDataContact').click(function(){
        $('#addPhoneTrContact').append('<tr id="contact_'+cp+'"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_'+cp+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_'+cp+'"></td><td><select name="phone_type[]" id="phone_type_contact_'+cp+'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_'+cp+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_'+cp+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        cp ++
    })
    let lp = 1;
    $('#addDataLocation').click(function(){
        $('#addPhoneTrLocation').append('<tr id="location_'+lp+'"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_'+lp+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_'+lp+'"></td><td><select name="phone_type[]" id="phone_type_location_'+lp+'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_'+lp+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_'+lp+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        lp ++
    })
    let ls = 1;
    $('#addScheduleHolidayLocation').click(function(){
        $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_'+ls+'"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_'+ls+'"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_'+ls+'"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_'+ls+'"></td><td> <input  type="checkbox" name="holiday_closed" id="holiday_closed_location_'+ls+'" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        ls++;
        $('.timePicker').timepicker({ 'scrollDefault': 'now' });
    });
    $(document).on('click', '.removePhoneData', function(){

        var $row = jQuery(this).closest('tr');
        let TrId = $row.attr('id')
        if(TrId){
            let id_new = TrId.split('_');
            let id = id_new[1]
            let name = id_new[0]
            let deletedId = parseInt(id)
            if(name == 'contact'){
                if($('#contact_phone_numbers').val()){

                let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val());
                let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val());
                let contact_phone_types = JSON.parse($('#contact_phone_types').val());
                let contact_phone_languages = JSON.parse($('#contact_phone_languages').val());
                let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val());

                contact_phone_numbers[selectedContactTrId].splice(deletedId,1)
                contact_phone_extensions[selectedContactTrId].splice(deletedId,1)
                contact_phone_types[selectedContactTrId].splice(deletedId,1)
                contact_phone_languages[selectedContactTrId].splice(deletedId,1)
                contact_phone_descriptions[selectedContactTrId].splice(deletedId,1)


                $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
                $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
                $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
                $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
                $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))
                cp = contact_phone_numbers[selectedContactTrId].length

                }

                $(this).closest('tr').remove()

                $('#addPhoneTrContact').each(function () {
                    var table = $(this);
                    table.find('tr').each(function (i) {
                        $(this).find('td').each(function(j){
                            if(j == 0){
                                $(this).find('input').attr('id','service_phones_contact_'+i)
                            }else if(j == 1){
                                $(this).find('input').attr('id','phone_extension_contact_'+i)
                            }else if(j == 2){
                                $(this).find('select').attr('id','phone_type_contact_'+i)
                                $('#phone_type_contact_'+i).selectpicker('refresh')
                            }else if(j == 3){
                                $(this).find('select').attr('id','phone_language_contact_'+i)
                                $('#phone_language_contact_'+i).selectpicker('refresh')
                            }else if(j == 4){
                                $(this).find('input').attr('id','phone_description_contact_'+i)
                            }
                        })
                        $(this).attr("id","contact_"+i)
                    });
                    //Code here
                });
            }else if(name == 'location'){
                if($('#location_phone_numbers').val() && $('#location_phone_extensions').val()){

                    let location_phone_numbers = JSON.parse($('#location_phone_numbers').val());
                    let location_phone_extensions = JSON.parse($('#location_phone_extensions').val());
                    let location_phone_types = JSON.parse($('#location_phone_types').val());
                    let location_phone_languages = JSON.parse($('#location_phone_languages').val());
                    let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val());

                    location_phone_numbers[selectedLocationTrId].splice(deletedId,1)
                    location_phone_extensions[selectedLocationTrId].splice(deletedId,1)
                    location_phone_types[selectedLocationTrId].splice(deletedId,1)
                    location_phone_languages[selectedLocationTrId].splice(deletedId,1)
                    location_phone_descriptions[selectedLocationTrId].splice(deletedId,1)

                    $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
                    $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
                    $('#location_phone_types').val(JSON.stringify(location_phone_types))
                    $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
                    $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))
                    lp = location_phone_numbers[selectedLocationTrId].length
                }

                $(this).closest('tr').remove()

                $('#addPhoneTrLocation').each(function () {
                    var table = $(this);
                    table.find('tr').each(function (i) {
                        $(this).find('td').each(function(j){
                            if(j == 0){
                                $(this).find('input').attr('id','service_phones_location_'+i)
                            }else if(j == 1){
                                $(this).find('input').attr('id','phone_extension_location_'+i)
                            }else if(j == 2){
                                $(this).find('select').attr('id','phone_type_location_'+i)
                                $('#phone_type_location_'+i).selectpicker('refresh')
                            }else if(j == 3){
                                $(this).find('select').attr('id','phone_language_location_'+i)
                                $('#phone_language_location_'+i).selectpicker('refresh')
                            }else if(j == 4){
                                $(this).find('input').attr('id','phone_description_location_'+i)
                            }
                        })
                        $(this).attr("id","location_"+i)
                    });
                    //Code here
                });
            }

        }else{
            $(this).closest('tr').remove()
        }
    });
    $(document).ready(function() {
        $('a .removePhone').on('click',function(){

        })
    })

    // contact section
    let contactRadioValue = 'new_data'
    $('.contactRadio').on('change',function () {
        let value = $(this).val()
        contactRadioValue = value
        if(value == 'new_data'){
            $('#newContactData').show()
            $('#existingContactData').hide()
        }else{
            $('#newContactData').hide()
            $('#existingContactData').show()
        }
    })
    let i =  0;
    let contact_service = []
    let contact_department = []

    let contact_phone_numbers = []
    let contact_phone_extensions = []
    let contact_phone_types = []
    let contact_phone_languages = []
    let contact_phone_descriptions = []

    $('#contactSubmit').click(function(){
        let contact_name_p = ''
        let contact_service_p = ''
        let contact_title_p = ''
        let contact_department_p = ''
        let contact_email_p = ''
        let contact_phone_p = ''
        let contact_recordid_p = ''
        if(contactRadioValue == 'new_data' && $('#contact_name_p').val() == ''){
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


        if(contactRadioValue == 'new_data'){
            contact_name_p = $('#contact_name_p').val()
            contact_service_p = $('#contact_service_p').val()
            contact_title_p = $('#contact_title_p').val()
            contact_department_p = $('#contact_department_p').val()
            contact_email_p = $('#contact_email_p').val()
            // contact_phone_p = $('#contact_phone_p').val()
            for (let index = 0; index < cp; index++) {
                phone_number_contact.push($('#service_phones_contact_'+index).val())
                phone_extension_contact.push($('#phone_extension_contact_'+index).val())
                phone_type_contact.push($('#phone_type_contact_'+index).val())
                phone_language_contact.push($('#phone_language_contact_'+index).val())
                phone_description_contact.push($('#phone_description_contact_'+index).val())
            }
            // contactsTable
        }else{
            let data = JSON.parse($('#contactSelectData').val())
            contact_name_p = data.contact_name ? data.contact_name : ''
            contact_title_p = data.contact_title ? data.contact_title : ''
            contact_department_p = data.contact_department ? data.contact_department : ''
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
                let phone_languages_data = phone[index].phone_language ? phone[index].phone_language.split(',') : []
                phone_language_contact.push(phone_languages_data)
                phone_description_contact.push(phone[index].phone_description)
            }
        }
        phone_number_contact = phone_number_contact.filter(function( element ) {
                        return element !== undefined;
                        })
        let contact_phone_list = phone_number_contact.join(',')
        if(editContactData == false){
            contact_service.push(contact_service_p)
            contact_department.push(contact_department_p)
            contact_phone_numbers[i] = phone_number_contact
            contact_phone_extensions[i] = phone_extension_contact
            contact_phone_types[i] = phone_type_contact
            contact_phone_languages[i] = phone_language_contact
            contact_phone_descriptions[i] = phone_description_contact
            $('#contactsTable').append('<tr id="contactTr_'+i+'"><td>'+contact_name_p+'<input type="hidden" name="contact_name[]" value="'+contact_name_p+'" id="contact_name_'+i+'"></td><td>'+contact_title_p+'<input type="hidden" name="contact_title[]" value="'+contact_title_p+'" id="contact_title_'+i+'"></td><td class="text-center">'+contact_email_p+'<input type="hidden" name="contact_email[]" value="'+contact_email_p+'" id="contact_email_'+i+'"></td><td class="text-center">'+contact_phone_list+'<input type="hidden" name="contact_phone[]" value="'+contact_phone_p+'" id="contact_phone_'+i+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="'+contactRadioValue+'" id="selectedContactRadio_'+i+'"><input type="hidden" name="contact_recordid[]" value="'+contact_recordid_p+'" id="existingContactIds_'+i+'"></td></tr>');
            i++;
        }else{
            if(selectedContactTrId){
                contactRadioValue = $('#selectedContactRadio_'+selectedContactTrId).val()
                contact_recordid_p = $('#existingContactIds_'+selectedContactTrId).val()
                contact_service[selectedContactTrId] = contact_service_p
                contact_department[selectedContactTrId] = contact_department_p

                contact_phone_numbers[selectedContactTrId] = phone_number_contact
                contact_phone_extensions[selectedContactTrId] = phone_extension_contact
                contact_phone_types[selectedContactTrId] = phone_type_contact
                contact_phone_languages[selectedContactTrId] = phone_language_contact
                contact_phone_descriptions[selectedContactTrId] = phone_description_contact

                $('#contactTr_'+selectedContactTrId).empty()
                $('#contactTr_'+selectedContactTrId).append('<td>'+contact_name_p+'<input type="hidden" name="contact_name[]" value="'+contact_name_p+'" id="contact_name_'+selectedContactTrId+'"></td><td>'+contact_title_p+'<input type="hidden" name="contact_title[]" value="'+contact_title_p+'" id="contact_title_'+selectedContactTrId+'"></td><td class="text-center">'+contact_email_p+'<input type="hidden" name="contact_email[]" value="'+contact_email_p+'" id="contact_email_'+selectedContactTrId+'"></td><td class="text-center">'+contact_phone_list+'<input type="hidden" name="contact_phone[]" value="'+contact_phone_p+'" id="contact_phone_'+selectedContactTrId+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="'+contactRadioValue+'" id="selectedContactRadio_'+selectedContactTrId+'"><input type="hidden" name="contact_recordid[]" value="'+contact_recordid_p+'" id="existingContactIds_'+selectedContactTrId+'"></td>')
            }
        }
        $('#contact_service').val(JSON.stringify(contact_service))
        $('#contact_department').val(JSON.stringify(contact_department))

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


        $('#contact_service_p').selectpicker('refresh')

        $('#addPhoneTrContact').empty()
        $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
        $('.selectpicker').selectpicker('refresh');
        $('#contactmodal').modal('hide');

        cp = 1
        editContactData = false
        selectedContactTrId = ''
    })
    $(document).on('click', '.removeContactData', function(){
        var $row = jQuery(this).closest('tr');
        if (confirm("Are you sure want to remove this contact?")) {
            let contactTrId = $row.attr('id')
            let id_new = contactTrId.split('_');
            let id = id_new[1]
            let deletedId = id

            let contact_service_val = JSON.parse($('#contact_service').val())
            let contact_department_val = JSON.parse($('#contact_department').val())

            // contact modal phone section
            let contact_phone_numbers = JSON.parse($('#contact_phone_numbers').val())
            let contact_phone_extensions = JSON.parse($('#contact_phone_extensions').val())
            let contact_phone_types = JSON.parse($('#contact_phone_types').val())
            let contact_phone_languages = JSON.parse($('#contact_phone_languages').val())
            let contact_phone_descriptions = JSON.parse($('#contact_phone_descriptions').val())

            contact_service_val.splice(deletedId,1)
            contact_department_val.splice(deletedId,1)
            contact_phone_numbers.splice(deletedId,1)
            contact_phone_extensions.splice(deletedId,1)
            contact_phone_types.splice(deletedId,1)
            contact_phone_languages.splice(deletedId,1)
            contact_phone_descriptions.splice(deletedId,1)

            $('#contact_service').val(JSON.stringify(contact_service_val))
            $('#contact_department').val(JSON.stringify(contact_department_val))
            $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
            $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
            $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
            $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
            $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))
            $(this).closest('tr').remove()

            $('#contactsTable').each(function () {
                var table = $(this);
                table.find('tr').each(function (i) {
                    $(this).attr("id","contactTr_"+i)
                });
                //Code here
            });
            s = contact_service_val.length
            cp = 1
        }
        return false
    });
    $(document).on('click', '.contactModalOpenButton', function(){
        $('#contactSelectData').val('')
        $('#contact_name_p').val('')
        $('#contact_title_p').val('')
        $('#contact_email_p').val('')
        $('#contact_phone_p').val('')
        $('#contact_service_p').val('')
        $('#contact_department_p').val('')

        // $('#addPhoneTrContact').empty()
        // $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" id="addDataContact" class="plus_delteicon bg-primary-color"><img src="/frontend/assets/images/plus.png" alt="" title=""></a></td></tr>')
        $('.selectpicker').selectpicker('refresh');

        $('#contact_service_p').selectpicker('refresh')
        $('#contactmodal').modal('show');
    });
    $(document).on('click', '.contactCloseButton', function(){
        editContactData = false
        $("input[name=contactRadio][value='existing']").prop("disabled",false);
        $('#contactmodal').modal('hide');
    });
    $(document).on('click', '.contactEditButton', function(){
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
        let radioValue = $("#selectedContactRadio_"+id).val();
        let contact_name_p = $('#contact_name_'+id).val()
        let contact_title_p = $('#contact_title_'+id).val()
        let contact_email_p = $('#contact_email_'+id).val()
        let contact_phone_p = $('#contact_phone_'+id).val()
        let contact_recordid_p = $('#existingContactIds_'+id).val()
        let contact_service_val = JSON.parse($('#contact_service').val())
        let contact_department_val = JSON.parse($('#contact_department').val())

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
            $('#addPhoneTrContact').append('<tr id="contact_'+index+'"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_'+index+'" value="'+phone_number_contact[index]+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_'+index+'" value="'+(phone_extension_contact[index] != null ? phone_extension_contact[index] : "" )+'"></td><td><select name="phone_type" id="phone_type_contact_'+index+'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language" id="phone_language_contact_'+index+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}" >{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_'+index+'" value="'+(phone_description_contact[index] != null ? phone_description_contact[index] : "")+'"></td><td class="text-center"><a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a></td></tr>');

            if(phone_type_contact[index] != ''){
                $("select[id='phone_type_contact_"+index+"'] option[value="+phone_type_contact[index]+"]").prop('selected', true)
            }
            if(phone_language_contact[index] != ''){
                for (let m = 0; m < phone_language_contact[index].length; m++) {
                    $("select[id='phone_language_contact_"+index+"'] option[value="+phone_language_contact[index][m]+"]").prop('selected', true)
                }
            }
            $('.selectpicker').selectpicker();
        }
        $('.selectpicker').selectpicker('refresh');
        cp = phone_number_contact.length

        let contact_department_p = contact_department_val[id]
        let contact_service_p = contact_service_val[id]
        // contactRadioValue = radioValue
        contactRadioValue = 'new_data'
        // $("input[name=contactRadio][value="+radioValue+"]").prop("checked",true);
        $("input[name=contactRadio][value='new_data']").prop("checked",true);
        $("input[name=contactRadio][value='existing']").prop("disabled",true);
        // if(radioValue == 'new_data'){
            $('#contact_name_p').val(contact_name_p)
            $('#contact_title_p').val(contact_title_p)
            $('#contact_email_p').val(contact_email_p)
            $('#contact_phone_p').val(contact_phone_p)
            $('#contact_department_p').val(contact_department_p)
            $('#contact_service_p').val(contact_service_p)
            $('#contactSelectData').val('')
            $('#contact_service_p').selectpicker('refresh')
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
    $('.locationRadio').on('change',function () {
        let value = $(this).val()
        locationRadioValue = value
        if(value == 'new_data'){
            $('#newLocationData').show()
            $('#existingLocationData').hide()
        }else{
            $('#newLocationData').hide()
            $('#existingLocationData').show()
        }
    })
    let l =  0;
    let location_alternate_name = []
    let location_transporation = []
    let location_service = []
    let location_schedules = []
    let location_description = []
    let location_details = []

    let location_phone_numbers = []
    let location_phone_extensions = []
    let location_phone_types = []
    let location_phone_languages = []
    let location_phone_descriptions = []

    // schedule variables
    let opens_at_location_monday_datas = []
    let closes_at_location_monday_datas = []
    let schedule_closed_monday_datas = []
    let opens_at_location_tuesday_datas = []
    let closes_at_location_tuesday_datas = []
    let schedule_closed_tuesday_datas = []
    let opens_at_location_wednesday_datas = []
    let closes_at_location_wednesday_datas = []
    let schedule_closed_wednesday_datas = []
    let opens_at_location_thursday_datas = []
    let closes_at_location_thursday_datas = []
    let schedule_closed_thursday_datas = []
    let opens_at_location_friday_datas = []
    let closes_at_location_friday_datas = []
    let schedule_closed_friday_datas = []
    let opens_at_location_saturday_datas = []
    let closes_at_location_saturday_datas = []
    let schedule_closed_saturday_datas = []
    let opens_at_location_sunday_datas = []
    let closes_at_location_sunday_datas = []
    let schedule_closed_sunday_datas = []

    let location_holiday_start_dates = []
    let location_holiday_end_dates = []
    let location_holiday_open_ats = []
    let location_holiday_close_ats = []
    let location_holiday_closeds = []

    $('#locationSubmit').click(function(){
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
        let location_phone_p = ''
        let location_recordid_p = ''

        // schedule section
        let opens_at_location_monday = ''
        let closes_at_location_monday = ''
        let schedule_closed_monday = ''

        let opens_at_location_tuesday = ''
        let closes_at_location_tuesday = ''
        let schedule_closed_tuesday = ''

        let opens_at_location_wednesday = ''
        let closes_at_location_wednesday = ''
        let schedule_closed_wednesday = ''

        let opens_at_location_thursday = ''
        let closes_at_location_thursday = ''
        let schedule_closed_thursday = ''

        let opens_at_location_friday = ''
        let closes_at_location_friday = ''
        let schedule_closed_friday = ''

        let opens_at_location_saturday = ''
        let closes_at_location_saturday = ''
        let schedule_closed_saturday = ''

        let opens_at_location_sunday = ''
        let closes_at_location_sunday = ''
        let schedule_closed_sunday = ''


        if(locationRadioValue == 'new_data' && $('#location_name_p').val() == ''){
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

        if(locationRadioValue == 'new_data'){
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
            // location_phone_p = $('#location_phone_p').val()

            for (let index = 0; index < lp; index++) {
                phone_number_location.push($('#service_phones_location_'+index).val())
                phone_extension_location.push($('#phone_extension_location_'+index).val())
                phone_type_location.push($('#phone_type_location_'+index).val())
                phone_language_location.push($('#phone_language_location_'+index).val())
                phone_description_location.push($('#phone_description_location_'+index).val())
            }

            // schedule section
            for (let index = 0; index < ls; index++) {
                holiday_start_date_location.push($('#holiday_start_date_location_'+index).val())
                holiday_end_date_location.push($('#holiday_end_date_location_'+index).val())
                holiday_open_at_location.push($('#holiday_open_at_location_'+index).val())
                holiday_close_at_location.push($('#holiday_close_at_location_'+index).val())
                holiday_closed_location.push($('#holiday_closed_location_'+index).is(":checked") ? 1 : '')
            }
            opens_at_location_monday = $('#opens_at_location_monday').val()
            closes_at_location_monday = $('#closes_at_location_monday').val()
            schedule_closed_monday = $('#schedule_closed_location_monday').is(":checked") ? 1 : ''

            opens_at_location_tuesday = $('#opens_at_location_tuesday').val()
            closes_at_location_tuesday = $('#closes_at_location_tuesday').val()
            schedule_closed_tuesday = $('#schedule_closed_location_tuesday').is(":checked") ? 2 : ''

            opens_at_location_wednesday = $('#opens_at_location_wednesday').val()
            closes_at_location_wednesday = $('#closes_at_location_wednesday').val()
            schedule_closed_wednesday = $('#schedule_closed_location_wednesday').is(":checked") ? 3 : ''

            opens_at_location_thursday = $('#opens_at_location_thursday').val()
            closes_at_location_thursday = $('#closes_at_location_thursday').val()
            schedule_closed_thursday = $('#schedule_closed_location_thursday').is(":checked") ? 4 : ''

            opens_at_location_friday = $('#opens_at_location_friday').val()
            closes_at_location_friday = $('#closes_at_location_friday').val()
            schedule_closed_friday = $('#schedule_closed_location_friday').is(":checked") ? 5 : ''

            opens_at_location_saturday = $('#opens_at_location_saturday').val()
            closes_at_location_saturday = $('#closes_at_location_saturday').val()
            schedule_closed_saturday = $('#schedule_closed_location_saturday').is(":checked") ? 6 : ''

            opens_at_location_sunday = $('#opens_at_location_sunday').val()
            closes_at_location_sunday = $('#closes_at_location_sunday').val()
            schedule_closed_sunday = $('#schedule_closed_location_sunday').is(":checked") ? 7 : ''

            // locationsTable
        }else{
            let data = JSON.parse($('#locationSelectData').val())

            location_name_p = data.location_name ? data.location_name : ''
            location_recordid_p = data.location_recordid ? data.location_recordid : ''

            location_alternate_name_p = data.location_alternate_name ? data.location_alternate_name : ''
            location_transporation_p = data.location_transportation ? data.location_transportation : ''
            location_description_p = data.location_description ? data.location_description : ''
            location_details_p = data.location_details ? data.location_details : ''

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
                let phone_language_location_data = phone[index].phone_language ? phone[index].phone_language.split(',') : []
                phone_language_location.push(phone_language_location_data)
                phone_description_location.push(phone[index].phone_description)
            }
            let schedule = data.schedules && data.schedules.length > 0 ? data.schedules : []

            for (let index = 0; index < schedules.length; index++) {
                if(schedule[index].schedule_holiday == 1){
                    holiday_start_date_location.push(schedule[index].dtstart)
                    holiday_end_date_location.push(schedule[index].until)
                    holiday_open_at_location.push(schedule[index].opens_at)
                    holiday_close_at_location.push(schedule[index].closes_at)
                    holiday_closed_location.push(schedule[index].schedule_closed)
                }else{
                    if(schedule[index].byday == 'monday'){
                        opens_at_location_monday = schedule[index].opens_at
                        closes_at_location_monday = schedule[index].closes_at
                        schedule_closed_monday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'tuesday'){
                        opens_at_location_tuesday = schedule[index].opens_at
                        closes_at_location_tuesday = schedule[index].closes_at
                        schedule_closed_tuesday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'wednesday'){
                        opens_at_location_wednesday = schedule[index].opens_at
                        closes_at_location_wednesday = schedule[index].closes_at
                        schedule_closed_wednesday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'thursday'){
                        opens_at_location_thursday = schedule[index].opens_at
                        closes_at_location_thursday = schedule[index].closes_at
                        schedule_closed_thursday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'friday'){
                        opens_at_location_friday = schedule[index].opens_at
                        closes_at_location_friday = schedule[index].closes_at
                        schedule_closed_friday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'saturday'){
                        opens_at_location_saturday = schedule[index].opens_at
                        closes_at_location_saturday = schedule[index].closes_at
                        schedule_closed_saturday = schedule[index].schedule_closed
                    }else if(schedule[index].byday == 'sunday'){
                        opens_at_location_sunday = schedule[index].opens_at
                        closes_at_location_sunday = schedule[index].closes_at
                        schedule_closed_sunday = schedule[index].schedule_closed
                    }
                }

            }

        }
        phone_number_location = phone_number_location.filter(function( element ) {
                        return element !== undefined;
                        })
        let location_phone_list = phone_number_location.join(',');
        if(editLocationData == false){
            location_alternate_name.push(location_alternate_name_p)
            location_transporation.push(location_transporation_p)
            location_service.push(location_service_p)
            location_schedules.push(location_schedules_p)
            location_description.push(location_description_p)
            location_details.push(location_details_p)

            location_phone_numbers[l] = phone_number_location
            location_phone_extensions[l] = phone_extension_location
            location_phone_types[l] = phone_type_location
            location_phone_languages[l] = phone_language_location
            location_phone_descriptions[l] = phone_description_location

            opens_at_location_monday_datas[l] = opens_at_location_monday
            closes_at_location_monday_datas[l] = closes_at_location_monday
            schedule_closed_monday_datas[l] = schedule_closed_monday
            opens_at_location_tuesday_datas[l] = opens_at_location_tuesday
            closes_at_location_tuesday_datas[l] = closes_at_location_tuesday
            schedule_closed_tuesday_datas[l] = schedule_closed_tuesday
            opens_at_location_wednesday_datas[l] = opens_at_location_wednesday
            closes_at_location_wednesday_datas[l] = closes_at_location_wednesday
            schedule_closed_wednesday_datas[l] = schedule_closed_wednesday
            opens_at_location_thursday_datas[l] = opens_at_location_thursday
            closes_at_location_thursday_datas[l] = closes_at_location_thursday
            schedule_closed_thursday_datas[l] = schedule_closed_thursday
            opens_at_location_friday_datas[l] = opens_at_location_friday
            closes_at_location_friday_datas[l] = closes_at_location_friday
            schedule_closed_friday_datas[l] = schedule_closed_friday
            opens_at_location_saturday_datas[l] = opens_at_location_saturday
            closes_at_location_saturday_datas[l] = closes_at_location_saturday
            schedule_closed_saturday_datas[l] = schedule_closed_saturday
            opens_at_location_sunday_datas[l] = opens_at_location_sunday
            closes_at_location_sunday_datas[l] = closes_at_location_sunday
            schedule_closed_sunday_datas[l] = schedule_closed_sunday

            location_holiday_start_dates[l] = holiday_start_date_location
            location_holiday_end_dates[l] = holiday_end_date_location
            location_holiday_open_ats[l] = holiday_open_at_location
            location_holiday_close_ats[l] = holiday_close_at_location
            location_holiday_closeds[l] = holiday_closed_location

            $('#locationsTable').append('<tr id="locationTr_'+l+'"><td>'+location_name_p+'<input type="hidden" name="location_name[]" value="'+location_name_p+'" id="location_name_'+l+'"></td><td>'+location_address_p+'<input type="hidden" name="location_address[]" value="'+location_address_p+'" id="location_address_'+l+'"></td><td class="text-center">'+location_city_p+'<input type="hidden" name="location_city[]" value="'+location_city_p+'" id="location_city_'+l+'"></td><td class="text-center">'+location_state_p+'<input type="hidden" name="location_state[]" value="'+location_state_p+'" id="location_state_'+l+'"></td><td class="text-center">'+location_zipcode_p+'<input type="hidden" name="location_zipcode[]" value="'+location_zipcode_p+'" id="location_zipcode_'+l+'"></td><td class="text-center">'+location_phone_list+'<input type="hidden" name="location_phone[]" value="'+location_phone_p+'" id="location_phone_'+l+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="'+locationRadioValue+'" id="selectedLocationRadio_'+l+'"><input type="hidden" name="location_recordid[]" value="'+location_recordid_p+'" id="existingLocationIds_'+l+'"></td></tr>');
            l++;
        }else{
            if(selectedLocationTrId){
                locationRadioValue = $('#selectedLocationRadio_'+selectedLocationTrId).val()
                location_recordid_p = $('#existingLocationIds_'+selectedLocationTrId).val()
                location_alternate_name[selectedLocationTrId] = location_alternate_name_p
                location_transporation[selectedLocationTrId] = location_transporation_p
                location_service[selectedLocationTrId] = location_service_p
                location_schedules[selectedLocationTrId] = location_schedules_p
                location_description[selectedLocationTrId] = location_description_p
                location_details[selectedLocationTrId] = location_details_p

                location_phone_numbers[selectedLocationTrId] = phone_number_location
                location_phone_extensions[selectedLocationTrId] = phone_extension_location
                location_phone_types[selectedLocationTrId] = phone_type_location
                location_phone_languages[selectedLocationTrId] = phone_language_location
                location_phone_descriptions[selectedLocationTrId] = phone_description_location

                opens_at_location_monday_datas[selectedLocationTrId] = opens_at_location_monday
                closes_at_location_monday_datas[selectedLocationTrId] = closes_at_location_monday
                schedule_closed_monday_datas[selectedLocationTrId] = schedule_closed_monday
                opens_at_location_tuesday_datas[selectedLocationTrId] = opens_at_location_tuesday
                closes_at_location_tuesday_datas[selectedLocationTrId] = closes_at_location_tuesday
                schedule_closed_tuesday_datas[selectedLocationTrId] = schedule_closed_tuesday
                opens_at_location_wednesday_datas[selectedLocationTrId] = opens_at_location_wednesday
                closes_at_location_wednesday_datas[selectedLocationTrId] = closes_at_location_wednesday
                schedule_closed_wednesday_datas[selectedLocationTrId] = schedule_closed_wednesday
                opens_at_location_thursday_datas[selectedLocationTrId] = opens_at_location_thursday
                closes_at_location_thursday_datas[selectedLocationTrId] = closes_at_location_thursday
                schedule_closed_thursday_datas[selectedLocationTrId] = schedule_closed_thursday
                opens_at_location_friday_datas[selectedLocationTrId] = opens_at_location_friday
                closes_at_location_friday_datas[selectedLocationTrId] = closes_at_location_friday
                schedule_closed_friday_datas[selectedLocationTrId] = schedule_closed_friday
                opens_at_location_saturday_datas[selectedLocationTrId] = opens_at_location_saturday
                closes_at_location_saturday_datas[selectedLocationTrId] = closes_at_location_saturday
                schedule_closed_saturday_datas[selectedLocationTrId] = schedule_closed_saturday
                opens_at_location_sunday_datas[selectedLocationTrId] = opens_at_location_sunday
                closes_at_location_sunday_datas[selectedLocationTrId] = closes_at_location_sunday
                schedule_closed_sunday_datas[selectedLocationTrId] = schedule_closed_sunday

                location_holiday_start_dates[selectedLocationTrId] = holiday_start_date_location
                location_holiday_end_dates[selectedLocationTrId] = holiday_end_date_location
                location_holiday_open_ats[selectedLocationTrId] = holiday_open_at_location
                location_holiday_close_ats[selectedLocationTrId] = holiday_close_at_location
                location_holiday_closeds[selectedLocationTrId] = holiday_closed_location

                $('#locationTr_'+selectedLocationTrId).empty()
                $('#locationTr_'+selectedLocationTrId).append('<td>'+location_name_p+'<input type="hidden" name="location_name[]" value="'+location_name_p+'" id="location_name_'+selectedLocationTrId+'"></td><td>'+location_address_p+'<input type="hidden" name="location_address[]" value="'+location_address_p+'" id="location_address_'+selectedLocationTrId+'"></td><td class="text-center">'+location_city_p+'<input type="hidden" name="location_city[]" value="'+location_city_p+'" id="location_city_'+selectedLocationTrId+'"></td><td class="text-center">'+location_state_p+'<input type="hidden" name="location_state[]" value="'+location_state_p+'" id="location_state_'+selectedLocationTrId+'"></td><td class="text-center">'+location_zipcode_p+'<input type="hidden" name="location_zipcode[]" value="'+location_zipcode_p+'" id="location_zipcode_'+selectedLocationTrId+'"></td><td class="text-center">'+location_phone_list+'<input type="hidden" name="location_phone[]" value="'+location_phone_p+'" id="location_phone_'+selectedLocationTrId+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="locationEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="locationRadio[]" value="'+locationRadioValue+'" id="selectedLocationRadio_'+selectedLocationTrId+'"><input type="hidden" name="location_recordid[]" value="'+location_recordid_p+'" id="existingLocationIds_'+selectedLocationTrId+'"></td>')


            }
        }
        $('#location_alternate_name').val(JSON.stringify(location_alternate_name))
        $('#location_transporation').val(JSON.stringify(location_transporation))
        $('#location_service').val(JSON.stringify(location_service))
        $('#location_schedules').val(JSON.stringify(location_schedules))
        $('#location_description').val(JSON.stringify(location_description))
        $('#location_details').val(JSON.stringify(location_details))

        $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
        $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
        $('#location_phone_types').val(JSON.stringify(location_phone_types))
        $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
        $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))

        $('#addPhoneTrLocation').empty()
        $('#addPhoneTrLocation').append('<tr id="location_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
        $('.selectpicker').selectpicker('refresh');

        $('#opens_at_location_monday_datas').val(JSON.stringify(opens_at_location_monday_datas))
        $('#closes_at_location_monday_datas').val(JSON.stringify(closes_at_location_monday_datas))
        $('#schedule_closed_monday_datas').val(JSON.stringify(schedule_closed_monday_datas))
        $('#opens_at_location_tuesday_datas').val(JSON.stringify(opens_at_location_tuesday_datas))
        $('#closes_at_location_tuesday_datas').val(JSON.stringify(closes_at_location_tuesday_datas))
        $('#schedule_closed_tuesday_datas').val(JSON.stringify(schedule_closed_tuesday_datas))
        $('#opens_at_location_wednesday_datas').val(JSON.stringify(opens_at_location_wednesday_datas))
        $('#closes_at_location_wednesday_datas').val(JSON.stringify(closes_at_location_wednesday_datas))
        $('#schedule_closed_wednesday_datas').val(JSON.stringify(schedule_closed_wednesday_datas))
        $('#opens_at_location_thursday_datas').val(JSON.stringify(opens_at_location_thursday_datas))
        $('#closes_at_location_thursday_datas').val(JSON.stringify(closes_at_location_thursday_datas))
        $('#schedule_closed_thursday_datas').val(JSON.stringify(schedule_closed_thursday_datas))
        $('#opens_at_location_friday_datas').val(JSON.stringify(opens_at_location_friday_datas))
        $('#closes_at_location_friday_datas').val(JSON.stringify(closes_at_location_friday_datas))
        $('#schedule_closed_friday_datas').val(JSON.stringify(schedule_closed_friday_datas))
        $('#opens_at_location_saturday_datas').val(JSON.stringify(opens_at_location_saturday_datas))
        $('#closes_at_location_saturday_datas').val(JSON.stringify(closes_at_location_saturday_datas))
        $('#schedule_closed_saturday_datas').val(JSON.stringify(schedule_closed_saturday_datas))
        $('#opens_at_location_sunday_datas').val(JSON.stringify(opens_at_location_sunday_datas))
        $('#closes_at_location_sunday_datas').val(JSON.stringify(closes_at_location_sunday_datas))
        $('#schedule_closed_sunday_datas').val(JSON.stringify(schedule_closed_sunday_datas))

        $('#location_holiday_start_dates').val(JSON.stringify(location_holiday_start_dates))
        $('#location_holiday_end_dates').val(JSON.stringify(location_holiday_end_dates))
        $('#location_holiday_open_ats').val(JSON.stringify(location_holiday_open_ats))
        $('#location_holiday_close_ats').val(JSON.stringify(location_holiday_close_ats))
        $('#location_holiday_closeds').val(JSON.stringify(location_holiday_closeds))

        $('#scheduleHolidayLocation').empty()
        $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_0"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_0"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.timePicker').timepicker({ 'scrollDefault': 'now' });
        $('#opens_at_location_monday').val('')
        $('#closes_at_location_monday').val('')
        $('#schedule_closed_location_monday').val(1)
        $('#opens_at_location_tuesday').val('')
        $('#closes_at_location_tuesday').val('')
        $('#schedule_closed_location_tuesday').val(2)
        $('#opens_at_location_wednesday').val('')
        $('#closes_at_location_wednesday').val('')
        $('#schedule_closed_location_wednesday').val(3)
        $('#opens_at_location_thursday').val('')
        $('#closes_at_location_thursday').val('')
        $('#schedule_closed_location_thursday').val(4)
        $('#opens_at_location_friday').val('')
        $('#closes_at_location_friday').val('')
        $('#schedule_closed_location_friday').val(5)
        $('#opens_at_location_saturday').val('')
        $('#closes_at_location_saturday').val('')
        $('#schedule_closed_location_saturday').val(6)
        $('#opens_at_location_sunday').val('')
        $('#closes_at_location_sunday').val('')
        $('#schedule_closed_location_sunday').val(7)

        $('#schedule_closed_location_monday').attr('checked',false)
        $('#schedule_closed_location_tuesday').attr('checked',false)
        $('#schedule_closed_location_wednesday').attr('checked',false)
        $('#schedule_closed_location_thursday').attr('checked',false)
        $('#schedule_closed_location_friday').attr('checked',false)
        $('#schedule_closed_location_saturday').attr('checked',false)
        $('#schedule_closed_location_sunday').attr('checked',false)


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
        $('#location_service_p').selectpicker('refresh')
        $('#location_schedules_p').selectpicker('refresh')
        $('#locationmodal').modal('hide');

        ls = 1
        lp = 1
        editLocationData = false
        selectedLocationTrId = ''
    })
    $(document).on('click', '.removeLocationData', function(){
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

            // location modal phone section
            let location_phone_numbers = JSON.parse($('#location_phone_numbers').val())
            let location_phone_extensions = JSON.parse($('#location_phone_extensions').val())
            let location_phone_types = JSON.parse($('#location_phone_types').val())
            let location_phone_languages = JSON.parse($('#location_phone_languages').val())
            let location_phone_descriptions = JSON.parse($('#location_phone_descriptions').val())

            let opens_at_location_monday_datas = JSON.parse($('#opens_at_location_monday_datas').val())
            let closes_at_location_monday_datas = JSON.parse($('#closes_at_location_monday_datas').val())
            let schedule_closed_monday_datas = JSON.parse($('#schedule_closed_monday_datas').val())
            let opens_at_location_tuesday_datas = JSON.parse($('#opens_at_location_tuesday_datas').val())
            let closes_at_location_tuesday_datas = JSON.parse($('#closes_at_location_tuesday_datas').val())
            let schedule_closed_tuesday_datas = JSON.parse($('#schedule_closed_tuesday_datas').val())
            let opens_at_location_wednesday_datas = JSON.parse($('#opens_at_location_wednesday_datas').val())
            let closes_at_location_wednesday_datas = JSON.parse($('#closes_at_location_wednesday_datas').val())
            let schedule_closed_wednesday_datas = JSON.parse($('#schedule_closed_wednesday_datas').val())
            let opens_at_location_thursday_datas = JSON.parse($('#opens_at_location_thursday_datas').val())
            let closes_at_location_thursday_datas = JSON.parse($('#closes_at_location_thursday_datas').val())
            let schedule_closed_thursday_datas = JSON.parse($('#schedule_closed_thursday_datas').val())
            let opens_at_location_friday_datas = JSON.parse($('#opens_at_location_friday_datas').val())
            let closes_at_location_friday_datas = JSON.parse($('#closes_at_location_friday_datas').val())
            let schedule_closed_friday_datas = JSON.parse($('#schedule_closed_friday_datas').val())
            let opens_at_location_saturday_datas = JSON.parse($('#opens_at_location_saturday_datas').val())
            let closes_at_location_saturday_datas = JSON.parse($('#closes_at_location_saturday_datas').val())
            let schedule_closed_saturday_datas = JSON.parse($('#schedule_closed_saturday_datas').val())
            let opens_at_location_sunday_datas = JSON.parse($('#opens_at_location_sunday_datas').val())
            let closes_at_location_sunday_datas = JSON.parse($('#closes_at_location_sunday_datas').val())
            let schedule_closed_sunday_datas = JSON.parse($('#schedule_closed_sunday_datas').val())

            let location_holiday_start_dates_val = JSON.parse($('#location_holiday_start_dates').val())
            let location_holiday_end_dates_val = JSON.parse($('#location_holiday_end_dates').val())
            let location_holiday_open_ats_val = JSON.parse($('#location_holiday_open_ats').val())
            let location_holiday_close_ats_val = JSON.parse($('#location_holiday_close_ats').val())
            let location_holiday_closeds_val = JSON.parse($('#location_holiday_closeds').val())

            location_alternate_name_val.splice(deletedId,1)
            location_transporation_val.splice(deletedId,1)
            location_service.splice(deletedId,1)
            location_schedules_val.splice(deletedId,1)
            location_description_val.splice(deletedId,1)
            location_details_val.splice(deletedId,1)
            location_phone_numbers.splice(deletedId,1)
            location_phone_extensions.splice(deletedId,1)
            location_phone_types.splice(deletedId,1)
            location_phone_languages.splice(deletedId,1)
            location_phone_descriptions.splice(deletedId,1)
            opens_at_location_monday_datas.splice(deletedId,1)
            closes_at_location_monday_datas.splice(deletedId,1)
            schedule_closed_monday_datas.splice(deletedId,1)
            opens_at_location_tuesday_datas.splice(deletedId,1)
            closes_at_location_tuesday_datas.splice(deletedId,1)
            schedule_closed_tuesday_datas.splice(deletedId,1)
            opens_at_location_wednesday_datas.splice(deletedId,1)
            closes_at_location_wednesday_datas.splice(deletedId,1)
            schedule_closed_wednesday_datas.splice(deletedId,1)
            opens_at_location_thursday_datas.splice(deletedId,1)
            closes_at_location_thursday_datas.splice(deletedId,1)
            schedule_closed_thursday_datas.splice(deletedId,1)
            opens_at_location_friday_datas.splice(deletedId,1)
            closes_at_location_friday_datas.splice(deletedId,1)
            schedule_closed_friday_datas.splice(deletedId,1)
            opens_at_location_saturday_datas.splice(deletedId,1)
            closes_at_location_saturday_datas.splice(deletedId,1)
            schedule_closed_saturday_datas.splice(deletedId,1)
            opens_at_location_sunday_datas.splice(deletedId,1)
            closes_at_location_sunday_datas.splice(deletedId,1)
            schedule_closed_sunday_datas.splice(deletedId,1)
            location_holiday_start_dates_val.splice(deletedId,1)
            location_holiday_end_dates_val.splice(deletedId,1)
            location_holiday_open_ats_val.splice(deletedId,1)
            location_holiday_close_ats_val.splice(deletedId,1)
            location_holiday_closeds_val.splice(deletedId,1)

            $('#location_alternate_name').val(JSON.stringify(location_alternate_name_val))
            $('#location_transporation').val(JSON.stringify(location_transporation_val))
            $('#location_service').val(JSON.stringify(location_service))
            $('#location_schedules').val(JSON.stringify(location_schedules_val))
            $('#location_description').val(JSON.stringify(location_description_val))
            $('#location_details').val(JSON.stringify(location_details_val))
            $('#location_phone_numbers').val(JSON.stringify(location_phone_numbers))
            $('#location_phone_extensions').val(JSON.stringify(location_phone_extensions))
            $('#location_phone_types').val(JSON.stringify(location_phone_types))
            $('#location_phone_languages').val(JSON.stringify(location_phone_languages))
            $('#location_phone_descriptions').val(JSON.stringify(location_phone_descriptions))
            $('#opens_at_location_monday_datas').val(JSON.stringify(opens_at_location_monday_datas))
            $('#closes_at_location_monday_datas').val(JSON.stringify(closes_at_location_monday_datas))
            $('#schedule_closed_monday_datas').val(JSON.stringify(schedule_closed_monday_datas))
            $('#opens_at_location_tuesday_datas').val(JSON.stringify(opens_at_location_tuesday_datas))
            $('#closes_at_location_tuesday_datas').val(JSON.stringify(closes_at_location_tuesday_datas))
            $('#schedule_closed_tuesday_datas').val(JSON.stringify(schedule_closed_tuesday_datas))
            $('#opens_at_location_wednesday_datas').val(JSON.stringify(opens_at_location_wednesday_datas))
            $('#closes_at_location_wednesday_datas').val(JSON.stringify(closes_at_location_wednesday_datas))
            $('#schedule_closed_wednesday_datas').val(JSON.stringify(schedule_closed_wednesday_datas))
            $('#opens_at_location_thursday_datas').val(JSON.stringify(opens_at_location_thursday_datas))
            $('#closes_at_location_thursday_datas').val(JSON.stringify(closes_at_location_thursday_datas))
            $('#schedule_closed_thursday_datas').val(JSON.stringify(schedule_closed_thursday_datas))
            $('#opens_at_location_friday_datas').val(JSON.stringify(opens_at_location_friday_datas))
            $('#closes_at_location_friday_datas').val(JSON.stringify(closes_at_location_friday_datas))
            $('#schedule_closed_friday_datas').val(JSON.stringify(schedule_closed_friday_datas))
            $('#opens_at_location_saturday_datas').val(JSON.stringify(opens_at_location_saturday_datas))
            $('#closes_at_location_saturday_datas').val(JSON.stringify(closes_at_location_saturday_datas))
            $('#schedule_closed_saturday_datas').val(JSON.stringify(schedule_closed_saturday_datas))
            $('#opens_at_location_sunday_datas').val(JSON.stringify(opens_at_location_sunday_datas))
            $('#closes_at_location_sunday_datas').val(JSON.stringify(closes_at_location_sunday_datas))
            $('#schedule_closed_sunday_datas').val(JSON.stringify(schedule_closed_sunday_datas))
            $('#location_holiday_start_dates').val(JSON.stringify(location_holiday_start_dates_val))
            $('#location_holiday_end_dates').val(JSON.stringify(location_holiday_end_dates_val))
            $('#location_holiday_open_ats').val(JSON.stringify(location_holiday_open_ats_val))
            $('#location_holiday_close_ats').val(JSON.stringify(location_holiday_close_ats_val))
            $('#location_holiday_closeds').val(JSON.stringify(location_holiday_closeds_val))
            $(this).closest('tr').remove()


            $('#locationsTable').each(function () {
                var table = $(this);
                table.find('tr').each(function (i) {
                    $(this).attr("id","locationTr_"+i)
                });
                //Code here
            });
            l = location_alternate_name_val.length
            lp = 1
        }
        return false;
    });
    $(document).on('click', '.locationModalOpenButton', function(){
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
        $('#location_service_p').selectpicker('refresh')
        $('#location_schedules_p').selectpicker('refresh')

        $('#schedule_closed_location_monday').attr('checked',false)
        $('#schedule_closed_location_tuesday').attr('checked',false)
        $('#schedule_closed_location_wednesday').attr('checked',false)
        $('#schedule_closed_location_thursday').attr('checked',false)
        $('#schedule_closed_location_friday').attr('checked',false)
        $('#schedule_closed_location_saturday').attr('checked',false)
        $('#schedule_closed_location_sunday').attr('checked',false)

        $('#locationmodal').modal('show');

    });
    $(document).on('click', '.locationCloseButton', function(){
        editLocationData = false

        $('#schedule_closed_location_monday').attr('checked',false)
        $('#schedule_closed_location_tuesday').attr('checked',false)
        $('#schedule_closed_location_wednesday').attr('checked',false)
        $('#schedule_closed_location_thursday').attr('checked',false)
        $('#schedule_closed_location_friday').attr('checked',false)
        $('#schedule_closed_location_saturday').attr('checked',false)
        $('#schedule_closed_location_sunday').attr('checked',false)

        $("input[name=locationRadio][value='existing']").prop("disabled",false);
        $('#locationmodal').modal('hide');
    });
    $(document).on('click', '.locationEditButton', function(){
        editLocationData = true
        var $row = jQuery(this).closest('tr');
        // var $columns = $row.find('td');
        // console.log()
        let locationTrId = $row.attr('id')
        let id_new = locationTrId.split('_');
        let id = id_new[1]
        selectedLocationTrId = id

        $('#addPhoneTrLocation').empty()
        $('#addPhoneTrLocation').append('<tr id="location_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_0"></td><td><select name="phone_type[]" id="phone_type_location_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
        $('.selectpicker').selectpicker('refresh');

        $('#scheduleHolidayLocation').empty()
        $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_0"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_0"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_0"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_0" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.timePicker').timepicker({ 'scrollDefault': 'now' });
        // $('.locationRadio').val()
        let radioValue = $("#selectedLocationRadio_"+id).val();
        let location_name_p = $('#location_name_'+id).val()
        let location_address_p = $('#location_address_'+id).val()
        let location_city_p = $('#location_city_'+id).val()
        let location_state_p = $('#location_state_'+id).val()
        let location_zipcode_p = $('#location_zipcode_'+id).val()
        let location_phone_p = $('#location_phone_'+id).val()
        let location_recordid_p = $('#existingLocationIds_'+id).val()

        let location_alternate_name_val = JSON.parse($('#location_alternate_name').val())
        let location_transporation_val = JSON.parse($('#location_transporation').val())
        let location_service_val = JSON.parse($('#location_service').val())
        let location_schedules_val = JSON.parse($('#location_schedules').val())
        let location_description_val = JSON.parse($('#location_description').val())
        let location_details_val = JSON.parse($('#location_details').val())

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
            $('#addPhoneTrLocation').append('<tr id="location_'+index+'"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_location_'+index+'" value="'+phone_number_location[index]+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_location_'+index+'" value="'+(phone_extension_location[index] != null ? phone_extension_location[index] : "") +'"></td><td><select name="phone_type[]" id="phone_type_location_'+index+'" class="form-control selectpicker" data-live-search="true" data-size="5"><option value="">Select phone type</option> @foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_location_'+index+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_location_'+index+'" value="'+(phone_description_location[index] != null ? phone_description_location[index] : "") +'"></td><td class="text-center"><a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a></td></tr>');

            if(phone_type_location[index] != ''){
                $("select[id='phone_type_location_"+index+"'] option[value="+phone_type_location[index]+"]").prop('selected', true)
            }
            // if(phone_language_location[index] != ''){
            //     $("select[id='phone_language_location_"+index+"'] option[value="+phone_language_location[index]+"]").prop('selected', true)
            // }
            if(phone_language_location[index] != ''){
                for (let m = 0; m < phone_language_location[index].length; m++) {
                    $("select[id='phone_language_location_"+index+"'] option[value="+phone_language_location[index][m]+"]").prop('selected', true)
                }
            }
            $('.selectpicker').selectpicker();
        }
        $('.selectpicker').selectpicker('refresh');
        lp = phone_number_location.length

        // location schedule section

        let opens_at_location_monday_datas = JSON.parse($('#opens_at_location_monday_datas').val())
        let closes_at_location_monday_datas = JSON.parse($('#closes_at_location_monday_datas').val())
        let schedule_closed_monday_datas = JSON.parse($('#schedule_closed_monday_datas').val())
        let opens_at_location_tuesday_datas = JSON.parse($('#opens_at_location_tuesday_datas').val())
        let closes_at_location_tuesday_datas = JSON.parse($('#closes_at_location_tuesday_datas').val())
        let schedule_closed_tuesday_datas = JSON.parse($('#schedule_closed_tuesday_datas').val())
        let opens_at_location_wednesday_datas = JSON.parse($('#opens_at_location_wednesday_datas').val())
        let closes_at_location_wednesday_datas = JSON.parse($('#closes_at_location_wednesday_datas').val())
        let schedule_closed_wednesday_datas = JSON.parse($('#schedule_closed_wednesday_datas').val())
        let opens_at_location_thursday_datas = JSON.parse($('#opens_at_location_thursday_datas').val())
        let closes_at_location_thursday_datas = JSON.parse($('#closes_at_location_thursday_datas').val())
        let schedule_closed_thursday_datas = JSON.parse($('#schedule_closed_thursday_datas').val())
        let opens_at_location_friday_datas = JSON.parse($('#opens_at_location_friday_datas').val())
        let closes_at_location_friday_datas = JSON.parse($('#closes_at_location_friday_datas').val())
        let schedule_closed_friday_datas = JSON.parse($('#schedule_closed_friday_datas').val())
        let opens_at_location_saturday_datas = JSON.parse($('#opens_at_location_saturday_datas').val())
        let closes_at_location_saturday_datas = JSON.parse($('#closes_at_location_saturday_datas').val())
        let schedule_closed_saturday_datas = JSON.parse($('#schedule_closed_saturday_datas').val())
        let opens_at_location_sunday_datas = JSON.parse($('#opens_at_location_sunday_datas').val())
        let closes_at_location_sunday_datas = JSON.parse($('#closes_at_location_sunday_datas').val())
        let schedule_closed_sunday_datas = JSON.parse($('#schedule_closed_sunday_datas').val())

        let location_holiday_start_dates_val = JSON.parse($('#location_holiday_start_dates').val())
        let location_holiday_end_dates_val = JSON.parse($('#location_holiday_end_dates').val())
        let location_holiday_open_ats_val = JSON.parse($('#location_holiday_open_ats').val())
        let location_holiday_close_ats_val = JSON.parse($('#location_holiday_close_ats').val())
        let location_holiday_closeds_val = JSON.parse($('#location_holiday_closeds').val())

        let opens_at_location_monday = opens_at_location_monday_datas[id]
        let closes_at_location_monday = closes_at_location_monday_datas[id]
        let schedule_closed_monday = schedule_closed_monday_datas[id]
        let opens_at_location_tuesday = opens_at_location_tuesday_datas[id]
        let closes_at_location_tuesday = closes_at_location_tuesday_datas[id]
        let schedule_closed_tuesday = schedule_closed_tuesday_datas[id]
        let opens_at_location_wednesday = opens_at_location_wednesday_datas[id]
        let closes_at_location_wednesday = closes_at_location_wednesday_datas[id]
        let schedule_closed_wednesday = schedule_closed_wednesday_datas[id]
        let opens_at_location_thursday = opens_at_location_thursday_datas[id]
        let closes_at_location_thursday = closes_at_location_thursday_datas[id]
        let schedule_closed_thursday = schedule_closed_thursday_datas[id]
        let opens_at_location_friday = opens_at_location_friday_datas[id]
        let closes_at_location_friday = closes_at_location_friday_datas[id]
        let schedule_closed_friday = schedule_closed_friday_datas[id]
        let opens_at_location_saturday = opens_at_location_saturday_datas[id]
        let closes_at_location_saturday = closes_at_location_saturday_datas[id]
        let schedule_closed_saturday = schedule_closed_saturday_datas[id]
        let opens_at_location_sunday = opens_at_location_sunday_datas[id]
        let closes_at_location_sunday = closes_at_location_sunday_datas[id]
        let schedule_closed_sunday = schedule_closed_sunday_datas[id]

        let location_holiday_start_dates = location_holiday_start_dates_val[id]
        let location_holiday_end_dates = location_holiday_end_dates_val[id]
        let location_holiday_open_ats = location_holiday_open_ats_val[id]
        let location_holiday_close_ats = location_holiday_close_ats_val[id]
        let location_holiday_closeds = location_holiday_closeds_val[id]

        $('#opens_at_location_monday').val(opens_at_location_monday)
        $('#closes_at_location_monday').val(closes_at_location_monday)
        $('#schedule_closed_location_monday').val(1)
        if(schedule_closed_monday == 1){
            $('#schedule_closed_location_monday').attr('checked',true)
        }else{
            $('#schedule_closed_location_monday').attr('checked',false)
        }

        $('#opens_at_location_tuesday').val(opens_at_location_tuesday)
        $('#closes_at_location_tuesday').val(closes_at_location_tuesday)
        $('#schedule_closed_location_tuesday').val(2)
        if(schedule_closed_tuesday == 2){
            $('#schedule_closed_location_tuesday').attr('checked',true)
        }else{
            $('#schedule_closed_location_tuesday').attr('checked',false)
        }

        $('#opens_at_location_wednesday').val(opens_at_location_wednesday)
        $('#closes_at_location_wednesday').val(closes_at_location_wednesday)
        $('#schedule_closed_location_wednesday').val(3)
        if(schedule_closed_wednesday == 3){
            $('#schedule_closed_location_wednesday').attr('checked',true)
        }else{
            $('#schedule_closed_location_wednesday').attr('checked',false)
        }

        $('#opens_at_location_thursday').val(opens_at_location_thursday)
        $('#closes_at_location_thursday').val(closes_at_location_thursday)
        $('#schedule_closed_location_thursday').val(4)
        if(schedule_closed_thursday == 4){
            $('#schedule_closed_location_thursday').attr('checked',true)
        }else{
            $('#schedule_closed_location_thursday').attr('checked',false)
        }

        $('#opens_at_location_friday').val(opens_at_location_friday)
        $('#closes_at_location_friday').val(closes_at_location_friday)
        $('#schedule_closed_location_friday').val(5)
        if(schedule_closed_friday == 5){
            $('#schedule_closed_location_friday').attr('checked',true)
        }else{
            $('#schedule_closed_location_friday').attr('checked',false)
        }

        $('#opens_at_location_saturday').val(opens_at_location_saturday)
        $('#closes_at_location_saturday').val(closes_at_location_saturday)
        $('#schedule_closed_location_saturday').val(6)
        if(schedule_closed_saturday == 6){
            $('#schedule_closed_location_saturday').attr('checked',true)
        }else{
            $('#schedule_closed_location_saturday').attr('checked',false)
        }

        $('#opens_at_location_sunday').val(opens_at_location_sunday)
        $('#closes_at_location_sunday').val(closes_at_location_sunday)
        $('#schedule_closed_location_sunday').val(7)
        if(schedule_closed_sunday == 7){
            $('#schedule_closed_location_sunday').attr('checked',true)
        }else{
            $('#schedule_closed_location_sunday').attr('checked',false)
        }

        $('#holiday_start_date_location_0').val(location_holiday_start_dates[0])
        $('#holiday_end_date_location_0').val(location_holiday_end_dates[0])
        $('#holiday_open_at_location_0').val(location_holiday_open_ats[0])
        $('#holiday_close_at_location_0').val(location_holiday_close_ats[0])
        $('#holiday_closed_location_0').val(1)
        if(location_holiday_closeds[0] == 1){
            $('#holiday_closed_location_0').attr('checked',true)
        }else{
            $('#holiday_closed_location_0').attr('checked',false)
        }

        for (let index = 1; index < location_holiday_start_dates.length; index++) {
            $('#scheduleHolidayLocation').append('<tr><td> <input class="form-control" type="date" name="holiday_start_date" id="holiday_start_date_location_'+index+'" value="'+location_holiday_start_dates[index]+'"></td><td> <input class="form-control" type="date" name="holiday_end_date" id="holiday_end_date_location_'+index+'" value="'+location_holiday_end_dates[index]+'"></td><td> <input class="form-control timePicker" type="text" name="holiday_open_at" id="holiday_open_at_location_'+index+'" value="'+location_holiday_open_ats[index]+'"></td><td> <input class="form-control timePicker" type="text" name="holiday_close_at" id="holiday_close_at_location_'+index+'" value="'+location_holiday_close_ats[index]+'"></td><td> <input type="checkbox" name="holiday_closed" id="holiday_closed_location_'+index+'" value="1"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.timePicker').timepicker({ 'scrollDefault': 'now' });
            if(location_holiday_closeds[index] == 1){
                $('#holiday_closed_location_'+index).attr('checked',true)
            }else{
                $('#holiday_closed_location_'+index).attr('checked',false)
            }
        }

        ls = location_holiday_start_dates.length

        let location_alternate_name_p = location_alternate_name_val[id]
        let location_transporation_p = location_transporation_val[id]
        let location_service_p = location_service_val[id]
        let location_schedules_p = location_schedules_val[id]
        let location_description_p = location_description_val[id]
        let location_details_p = location_details_val[id]


        // locationRadioValue = radioValue
        locationRadioValue = 'new_data'
        // $("input[name=locationRadio][value="+radioValue+"]").prop("checked",true);
        $("input[name=locationRadio][value='new_data']").prop("checked",true);
        $("input[name=locationRadio][value='existing']").prop("disabled",true);
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
            $('#locationSelectData').val('')
            $('#newLocationData').show()
            $('#existingLocationData').hide()
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
        $('#locationmodal').modal('show');
    });
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
          + "<a class='removePhone'><i class='fas fa-minus btn-danger btn float-right mb-5' style='border-radius: 50%;    font-size: 13px;width: 20px;height: 20px; position: absolute;top: 0;right: 15px;padding: 0;'></i></a>"
          + "</li>" );
    });
</script>
@endsection
