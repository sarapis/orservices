@extends('layouts.app')
@section('title')
Service Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="service_organization"], button[data-id="service_status"], button[data-id="service_taxonomies"], button[data-id="service_schedules"], button[data-id="service_contacts"], button[data-id="service_details"], button[data-id="service_address"] {
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
                {{-- <form action="/add_new_service_in_facility" method="GET"> --}}
                {!! Form::open(['route' => 'add_new_service_in_facility']) !!}
                    <div class="card all_form_field">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Name </label>
                                        <div class="help-tip">
                                            <div><p>The official or public name of the service.</p></div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_name" name="service_name" value="" >
                                        @error('service_name')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <input type="hidden" id="service_locations" name="service_locations" value="{{$facility->location_recordid}}">
                                <input type="hidden" id="service_organization" name="service_organization" value="{{$facility->location_organization}}">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Service Description </label>
                                        <div class="help-tip">
                                            <div><p>A description of the service.</p></div>
                                        </div>
                                        <textarea id="service_description" name="service_description" class="selectpicker" rows="5"></textarea>
                                        {{-- <input class="form-control selectpicker" type="text" id="service_description" name="service_description" value="" > --}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service URL (website) </label>
                                        <div class="help-tip">
                                            <div><p>URL of the service</p></div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_url" name="service_url"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Email </label>
                                        <div class="help-tip">
                                            <div><p> Email address for the service, if any.</p></div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_email"
                                            name="service_email" value="">
                                        @error('service_email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
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
                                        {!! Form::select('access_requirement',['none'=>'None','yes'=>'Yes'],'none',['class' =>
                                        'form-control selectpicker']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Application Process </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The steps needed to access the service.</p>
                                            </div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_application_process" name="service_application_process" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fee Options</label>
                                        {!! Form::select('fee_option[]',$fee_options,null,['class' =>
                                        'form-control selectpicker','multiple' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fees Details</label>
                                        <div class="help-tip">
                                            <div><p>Details of any charges for service users to access this service.</p></div>
                                        </div>
                                        <input class="form-control selectpicker" type="text" id="service_fees" name="service_fees" value="">
                                    </div>
                                </div>
                                <div class="text-right col-md-12 mb-20">
                                    <button type="button" class="btn btn_additional bg-primary-color" data-toggle="collapse" data-target="#demo">Additional Info
                                        <img src="/frontend/assets/images/white_arrow.png" alt="" title="" />
                                    </button>
                                </div>
                                <div id="demo" class="collapse row m-0">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Area</label>
                                            <div class="help-tip">
                                                <div>
                                                    <p>The geographic area where this service is accessible.</p>
                                                </div>
                                            </div>
                                            {!! Form::select('service_area[]',$service_area,null,['class' =>
                                            'form-control selectpicker','multiple' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Alternate Name </label>
                                            <div class="help-tip">
                                                <div><p> Alternative or commonly used name for a service.</p></div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_alternate_name" name="service_alternate_name" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Licenses </label>
                                            <div class="help-tip">
                                                <div><p>An organization may have a license issued by a government entity to operate legally. A list of any such licenses can be provided here.</p></div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_licenses" name="service_licenses" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Wait Time </label>
                                            <div class="help-tip">
                                                <div><p>Time a client may expect to wait before receiving a service.</p></div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_wait_time" name="service_wait_time" value="">
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Accrediations </label>
                                            <div class="help-tip">
                                                <div><p>Details of any accreditations. Accreditation is the formal evaluation of an organization or program against best practice standards set by an accrediting organization.</p></div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_accrediations" name="service_accrediations" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Grouping</label>
                                            <div class="help-tip">
                                                <div><p>Some organizations organize their services into service groupings (e.g., Senior Services).. A service grouping brings together a number of related services.</p></div>
                                            </div>
                                            <input class="form-control selectpicker" type="text" id="service_program" name="service_program" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Service Grouping Description </label>
                                            {{-- <div class="help-tip">
                                                <div><p></p></div>
                                            </div> --}}
                                            <textarea name="program_alternate_name" id="program_alternate_name" cols="30" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status(Verified) </label>
                                            {!! Form::select('service_status',$service_status_list,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'service_status','placeholder' => 'Select status']) !!}
                                        </div>
                                    </div> --}}

                                </div>
                                {{-- taxonomy section --}}
                            </div>
                        </div>
                    </div>
                    {{-- tab-pannel top group --}}
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#types-tab">
                                <h4 class="card_services_title">Types</h4>
                            </a>
                        </li>
                        @if ($layout->show_classification == 'yes')
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#sdoh-category-tab">
                                <h4 class="card_services_title">SDOH Codes</h4>
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="types-tab">
                                    <div  style="top:8px;">
                                        <div>
                                            <p class="service_help_text">
                                                {{ $help_text->service_classification ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="organization_services">
                                        {{-- service category --}}
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
                                                                <table class="table table_border_none" id="ServiceCategoryTable">
                                                                    <thead>
                                                                        <th>Type</th>
                                                                        <th>Term</th>
                                                                        <th style="width:60px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::select('service_category_type[]',$service_category_types,null,['class' => 'form-control selectpicker service_category_type','placeholder' => 'select  type','id' => 'service_category_type_0']) !!}

                                                                            </td>
                                                                            <td class="create_btn">
                                                                                {!! Form::select('service_category_term[0][]',[],null,['class' => 'form-control selectpicker service_category_term','id' => 'service_category_term_0','multiple' => true,'data-size' => '5','data-live-search' => 'true']) !!}
                                                                                <input type="hidden" name="service_category_term_type[]" id="service_category_term_type_0" value="old">
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
                                        {{-- service eligibility --}}
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
                                                                                {!! Form::select('service_eligibility_type[]',$service_eligibility_types,null,['class' => 'form-control selectpicker service_eligibility_type','placeholder' => 'select service eligibility type','id' => 'service_eligibility_type_0']) !!}

                                                                            </td>
                                                                            <td class="create_btn">
                                                                                {!! Form::select('service_eligibility_term[0][]',[],null,['class' => 'form-control selectpicker service_eligibility_term','id' => 'service_eligibility_term_0','multiple' => 'true','data-size' => '5','data-live-search' => 'true']) !!}
                                                                                <input type="hidden" name="service_eligibility_term_type[]" id="service_eligibility_term_type_0" value="old">
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
                                                    {{-- end here --}}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- details --}}
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
                                                                                {!! Form::select('detail_type[]',$detail_types,null,['class' => 'form-control selectpicker detail_type','placeholder' => 'select detail type','id' => 'detail_type_0']) !!}

                                                                            </td>
                                                                            <td class="create_btn">
                                                                                {!! Form::select('detail_term[]',[],null,['class' => 'form-control selectpicker detail_term','id' => 'detail_term_0','multiple' => 'true','data-size' => '5','data-live-search' => 'true']) !!}
                                                                                <input type="hidden" name="term_type[]" id="term_type_0" value="old">
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <a href="#" class="plus_delteicon btn-button">
                                                                                    <img src="/frontend/assets/images/delete.png" alt="" title="">
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr></tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end here --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($layout->show_classification == 'yes')
                                <div class="tab-pane" id="sdoh-category-tab">
                                    <div class="organization_services">

                                        <div  style="top:8px;" >
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
                                                                <div class="col-md-6">
                                                                    <p>
                                                                        {{ $item }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    {!! Form::checkbox('code_category_ids[]',$key, in_array($key,$selected_ids) ? 'selected' : '', ['class' => 'code_category_ids filled-in chk-col-blue']) !!}
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
                    {{-- tab-pannel bottom group --}}
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#phones-tab">
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
                                    <div class="organization_services">
                                        <div class="card all_form_field">
                                            <div class="card-block">
                                                {{-- phone table --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Phones
                                                    <div class="d-inline float-right" >
                                                        <a href="javascript:void(0)" class="phoneModalOpenButton plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    {{-- phone table --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <table class="table table_border_none" id="PhoneTable">
                                                                    <thead>
                                                                        <th>Number</th>
                                                                        <th>extension</th>
                                                                        <th style="width:200px;position:relative;">Type
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div><p>Select “Main” if this is the organization's primary phone number (or leave blank)
                                                                                </p></div>
                                                                            </div>
                                                                        </th>
                                                                        <th style="width:200px;">Language(s)</th>
                                                                        <th style="width:200px;position:relative;">Description
                                                                            <div class="help-tip" style="top:8px;">
                                                                                <div><p>A description providing extra information about the phone service (e.g. any special arrangements for accessing, or details of availability at particular times).
                                                                                </p></div>
                                                                            </div>
                                                                        </th>
                                                                        <th>Main</th>
                                                                        <th style="width:140px">&nbsp;</th>
                                                                    </thead>
                                                                    <tbody id="phonesTable">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end here --}}
                                                    <input type="hidden" name="phone_language_data" id="phone_language_data" value="{{ $phone_language_data }}">
                                                    <input type="hidden" name="contact_service[]" id="contact_service">
                                                    <input type="hidden" name="contact_department[]" id="contact_department">
                                                    {{-- contact phone --}}
                                                    <input type="hidden" name="contact_phone_numbers[]" id="contact_phone_numbers">
                                                    <input type="hidden" name="contact_phone_extensions[]" id="contact_phone_extensions">
                                                    <input type="hidden" name="contact_phone_types[]" id="contact_phone_types">
                                                    <input type="hidden" name="contact_phone_languages[]" id="contact_phone_languages">
                                                    <input type="hidden" name="contact_phone_descriptions[]" id="contact_phone_descriptions">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="contacts-tab">
                                    <div class="organization_services">
                                        {{-- contact table --}}
                                        <div class="card all_form_field">
                                            <div class="card-block">
                                                {{-- contact --}}
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Contacts
                                                    <div class="d-inline float-right">
                                                        <a href="javascript:void(0)" class="contactModalOpenButton plus_delteicon bg-primary-color">
                                                            <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                        </a>
                                                    </div>
                                                </h4>
                                                <div class="row">
                                                    {{-- contact table --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{-- <label>Contacts: <a class="contactModalOpenButton"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                                            <div class="table-responsive">
                                                                <table class="table table_border_none" >
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
                                                    {{-- end here --}}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end here --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- tab-pannel additional-info group --}}
                    <ul class="nav nav-tabs tabpanel_above">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#schedules-tab">
                                <h4 class="card_services_title">Schedules
                                </h4>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#additional-info-tab">
                                <h4 class="card_services_title">Additional Info
                                </h4>
                            </a>
                        </li> --}}
                    </ul>
                    <div class="card">
                        <div class="card-block" style="border-radius: 0 0 12px 12px">
                            <div class="tab-content">
                                <div class="tab-pane active" id="schedules-tab">
                                    <div class="organization_services">
                                        {{-- schedule section --}}
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
                                                                                {!! Form::text('opens_at[]',null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::text('closes_at[]',null, ['class' => 'form-control timePicker']) !!}
                                                                            </td>
                                                                            <td style="vertical-align: middle">
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="1" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="2" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="3" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="4" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="5" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="6" >
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
                                                                                <input type="checkbox" name="schedule_closed[]" id="" value="7" >
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <h4 class="title_edit text-left mb-25 mt-10">
                                                    Holiday Schedule
                                                    <div class="d-inline float-right" >
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
                                <div class="tab-pane " id="additional-info-tab">
                                    <div class="organization_services">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-service-btn"> Back</button>
                        <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-service-btn"> Save</button>
                    </div>

                {!! Form::close() !!}
                {{-- </form> --}}
            </div>
            {{-- phone modal --}}
            @include('frontEnd.services.phone')
            {{-- phone modala close --}}
            {{-- contact modal --}}
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="contactmodal" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close contactCloseButton" ><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Contacts</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input contactRadio" type="radio" name="contactRadio" id="contactRadio2" value="new_data" checked>
                                        <label class="form-check-label" for="contactRadio2"><b style="color: #000">Create New Contact</b></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input contactRadio" type="radio" name="contactRadio" id="contactRadio1" value="existing">
                                        <label class="form-check-label" for="contactRadio1"><b style="color: #000">Use Existing Contact</b></label>
                                    </div>

                                </div>
                                <div class="" id="existingContactData" style="display: none;">
                                    <select name="contacts" id="contactSelectData" class="form-control" >
                                        <option value="">Select Contacts</option>
                                        @foreach ($all_contacts as $contact)
                                        <option value="{{ $contact }}" data-id="{{ $contact->contact_recordid }}">{{ $contact->contact_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="newContactData">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" placeholder="Name" id="contact_name_p">
                                                <span id="contact_name_error" style="display: none;color:red" >Contact Name is required!</span>
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
                                                <input class="form-control selectpicker" type="text" id="contact_department_p" name="contact_department" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control" placeholder="Email" id="contact_email_p">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Visibility </label>
                                                <select class="form-control selectpicker" data-live-search="true" id="contact_visibility_p" name="contact_visibility_p[]" data-size="8">
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h4 class="title_edit text-left mb-25 mt-10 px-20">
                                                Phones
                                                <a href="javascript:void(0)" id="addDataContact" class="plus_delteicon bg-primary-color float-right">
                                                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                </a>
                                            </h4>
                                            {{-- <label>Phones: <a id="addDataContact"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label> --}}
                                            <div class="col-md-12">
                                                <table class="table table_border_none" id="PhoneTableContact">
                                                    <thead>
                                                        <th>Number</th>
                                                        <th>Extension</th>
                                                        <th style="width:200px;position:relative;">Type
                                                            <div class="help-tip" style="top:8px;">
                                                                <div><p>Select “Main” if this is the organization's primary phone number (or leave blank)
                                                                </p></div>
                                                            </div>
                                                        </th>
                                                        <th style="width:200px;">Language(s)</th>
                                                        <th style="width:200px;position:relative;">Description
                                                            <div class="help-tip" style="top:8px;">
                                                                <div><p>A description providing extra information about the phone service (e.g. any special arrangements for accessing, or details of availability at particular times).
                                                                </p></div>
                                                            </div>
                                                        </th>
                                                        <th>&nbsp;</th>
                                                    </thead>
                                                    <tbody id="addPhoneTrContact">
                                                        <tr>
                                                            <td style="width: 20%;">
                                                                <input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0">
                                                            </td>
                                                            <td style="width: 15%;">
                                                                {!! Form::select('phone_type[]',$phone_type,[],['class' => 'form-control selectpicker','data-live-search' => 'true','id' => 'phone_type_contact_0','data-size' => 5,'placeholder' => 'select phone type'])!!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('phone_language[]',$phone_languages,[],['class' => 'form-control selectpicker','data-size' => 5,' data-live-search' => 'true', 'id' => 'phone_language_contact_0']) !!}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0">
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0)" id="addDataContact" class="plus_delteicon bg-primary-color">
                                                                    <img src="/frontend/assets/images/plus.png" alt="" title="">
                                                                </a>
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
                                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn contactCloseButton">Close</button>
                                <button type="button" id="contactSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- detail term modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="create_new_term" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close detailTermCloseButton" ><span aria-hidden="true">×</span>
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
                                            <span id="detail_term_error" style="display: none;color:red" >Detail Term is required!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn detailTermCloseButton">Close</button>
                                <button type="button" id="detailTermSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- service category term modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="create_new_service_category_term" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close serviceCategoryTermCloseButton" ><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Service Category Term</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{-- <label>Service Category Term</label> --}}
                                            <input type="text" class="form-control" placeholder="Service Category Term" id="service_category_term_p">
                                            <input type="hidden" name="service_category_term_index_p" value="" id="service_category_term_index_p">
                                            <span id="service_category_term_error" style="display: none;color:red" >Service Category Term is required!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn serviceCategoryTermCloseButton">Close</button>
                                <button type="button" id="serviceCategoryTermSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
            {{-- service eligibility term modal --}}
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="create_new_service_eligibility_term" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close serviceEligibilityTermCloseButton" ><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Add Service Eligibility Term</h4>
                            </div>
                            <div class="modal-body all_form_field">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Service Eligibility Term</label>
                                            <input type="text" class="form-control" placeholder="Service Eligibility Term" id="service_eligibility_term_p">
                                            <input type="hidden" name="service_eligibility_term_index_p" value="" id="service_eligibility_term_index_p">
                                            <span id="service_eligibility_term_error" style="display: none;color:red" >Service Eligibility Term is required!</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-lg btn_delete red_btn serviceEligibilityTermCloseButton">Close</button>
                                <button type="button" id="serviceEligibilityTermSubmit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End here --}}
        </div>
    </div>
</div>
@include('frontEnd.services.service_code_script')
<script src="/js/jquery.timepicker.min.js"></script>
<script src="/js/jquery.timepicker.min.js"></script>
<script>
    $('.timePicker').timepicker({ 'scrollDefault': 'now' });
    $('#back-service-btn').click(function() {
        history.go(-1);
        return false;
    });
    let editContactData = false;
    let selectedContactTrId = ''
    $(document).ready(function() {
        $('select#service_organization').val([]).change();
        $('select#service_schedules').val([]).change();
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

        if(value.includes('create_new')){
            $('#detail_term_index_p').val(index)
            $('#create_new_term').modal('show')
        }else if(value.includes(text)){
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
        let detail_type_name = $( "#detail_type_"+index+" option:selected" ).val()
        $.ajax({
            url : '{{ route("addDetailTerm") }}',
            method : 'get',
            data : {detail_type_name,detail_term},
            success: function (response) {
                $('#term_type_'+index).val('new')
                $('#detail_term_'+index).prepend('<option value="'+response.data+'">'+detail_term+'</option>');
                $('#detail_term_'+index).val(detail_term)
                $('#detail_term_'+index).selectpicker('refresh')
                $('#create_new_term').modal('hide')
                $('#detail_term_p').val('')
                $('#detail_term_index_p').val('')
            },
            error : function (error) {
                console.log(error)
            }
        })
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
        $('#DetailTable tr:last').before('<tr><td><select name="detail_type[]" id="detail_type_'+d+'" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="detail_term[]" id="detail_term_'+d+'" class="form-control selectpicker detail_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="term_type[]" id="term_type_'+d+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
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
                // $('#service_category_term_'+index).append('<option value="">Select term</option>');
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

        if(value.includes('create_new')){
            $('#service_category_term_index_p').val(index)
            $('#create_new_service_category_term').modal('show')
        }else if(value.includes(text)){
            $('#service_category_term_type_'+index).val('new')
        }else{
            $('#service_category_term_type_'+index).val('old')
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
                // $('#service_eligibility_term_'+index).append('<option value="">Select term</option>');
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

        if(value.includes('create_new')){
            $('#service_eligibility_term_index_p').val(index)
            $('#create_new_service_eligibility_term').modal('show')
        }else if(value.includes(text)){
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
        $('#ServiceCategoryTable tr:last').before('<tr><td><select name="service_category_type[]" id="service_category_type_'+sc+'" class="form-control selectpicker service_category_type"><option value="">Select Type</option> @foreach ($service_category_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="service_category_term['+sc+'][]" id="service_category_term_'+sc+'" class="form-control selectpicker service_category_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="service_category_term_type[]" id="service_category_term_type_'+sc+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        sc++;
    })
    let se = 1
    $('#addServiceEligibilityTr').click(function(){
        $('#ServiceEligibilityTable tr:last').before('<tr><td><select name="service_eligibility_type[]" id="service_eligibility_type_'+se+'" class="form-control selectpicker service_eligibility_type"><option value="">Select Type</option> @foreach ($service_eligibility_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td  class="create_btn"> <select name="service_eligibility_term['+se+'][]" id="service_eligibility_term_'+se+'" class="form-control selectpicker service_eligibility_term" data-size="5" data-live-search="true" multiple></select><input type="hidden" name="service_eligibility_term_type[]" id="service_eligibility_term_type_'+se+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        se++;
    })

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
          + "</li>" );
    });
    let hs = 2
    $('#addTr').click(function(){
        $('#myTable tr:last').before('<tr><td><input class="form-control" type="date" name="holiday_start_date[]" id=""></td><td><input class="form-control" type="date" name="holiday_end_date[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_open_at[]" id=""></td><td><input class="form-control timePicker" type="text" name="holiday_close_at[]" id=""></td><td><input  type="checkbox" name="holiday_closed[]" id="" value="'+hs+'" ></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        hs++;
        $('.timePicker').timepicker({ 'scrollDefault': 'now' });
    });

    // let phone_language_data = []
    // $(document).on('change','div > .phone_language',function () {
    //     let value = $(this).val()
    //     let id = $(this).attr('id')
    //     let idsArray = id ? id.split('_') : []
    //     let index = idsArray.length > 0 ? idsArray[2] : ''
    //     phone_language_data[index] = value
    //     $('#phone_language_data').val(JSON.stringify(phone_language_data))
    // })
    // pt = 1
    $('#addPhoneTr').click(function(){
        $('#PhoneTable tr:last').before('<tr ><td><input type="text" class="form-control" name="service_phones[]" id="" style="width: 20%;"></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select("phone_type[]",$phone_type,[],["class" => "form-control selectpicker","data-live-search" => "true","id" => "phone_type","data-size" => 5,"placeholder" => "select phone type"])!!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key=>$value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td><div class="form-check form-check-inline" style="margin-top: -10px;"> <input class="form-check-input " type="radio" name="main_priority[]" id="main_priority" value="1" > <label class="form-check-label" for="main_priority"></label></div></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        pt++;
    })
    let cp = 1;
    $('#addDataContact').click(function(){
        $('#addPhoneTrContact').append('<tr id="contact_'+cp+'"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_'+cp+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_'+cp+'"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_contact_'+cp+'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : "" }} >{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_'+cp+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_'+cp+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        cp ++
    })

    $(document).on('click', '.removePhoneData', function(){
        var $row = jQuery(this).closest('tr');
        let TrId = $row.attr('id')
        if(TrId){
            let id_new = TrId.split('_');
            let id = id_new[1]
            let name = id_new[0]
            let deletedId = parseInt(id)
            if(name == 'contact'){
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

                console.log(contact_phone_numbers,selectedContactTrId)

                $('#contact_phone_numbers').val(JSON.stringify(contact_phone_numbers))
                $('#contact_phone_extensions').val(JSON.stringify(contact_phone_extensions))
                $('#contact_phone_types').val(JSON.stringify(contact_phone_types))
                $('#contact_phone_languages').val(JSON.stringify(contact_phone_languages))
                $('#contact_phone_descriptions').val(JSON.stringify(contact_phone_descriptions))
                cp = contact_phone_numbers[selectedContactTrId].length

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
        let contact_visibility_p = ''
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
            contact_visibility_p = $('#contact_visibility_p').val()
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
            $('#contactsTable').append('<tr id="contactTr_'+i+'"><td>'+contact_name_p+'<input type="hidden" name="contact_name[]" value="'+contact_name_p+'" id="contact_name_'+i+'"></td><td>'+contact_title_p+'<input type="hidden" name="contact_title[]" value="'+contact_title_p+'" id="contact_title_'+i+'"></td><td class="text-center">'+contact_email_p+'<input type="hidden" name="contact_email[]" value="'+contact_email_p+'" id="contact_email_'+i+'"></td><td class="text-center">'+contact_visibility_p+'<input type="hidden" name="contact_visibility[]" value="'+contact_visibility_p+'" id="contact_visibility_'+i+'"></td><td class="text-center">'+contact_phone_list+'<input type="hidden" name="contact_phone[]" value="'+contact_phone_p+'" id="contact_phone_'+i+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="'+contactRadioValue+'" id="selectedContactRadio_'+i+'"><input type="hidden" name="contact_recordid[]" value="'+contact_recordid_p+'" id="existingContactIds_'+i+'"></td></tr>');
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
                $('#contactTr_'+selectedContactTrId).append('<td>'+contact_name_p+'<input type="hidden" name="contact_name[]" value="'+contact_name_p+'" id="contact_name_'+selectedContactTrId+'"></td><td>'+contact_title_p+'<input type="hidden" name="contact_title[]" value="'+contact_title_p+'" id="contact_title_'+selectedContactTrId+'"></td><td class="text-center">'+contact_email_p+'<input type="hidden" name="contact_email[]" value="'+contact_email_p+'" id="contact_email_'+selectedContactTrId+'"></td><td class="text-center">'+contact_visibility_p+'<input type="hidden" name="contact_visibility[]" value="'+contact_visibility_p+'" id="contact_visibility_'+selectedContactTrId+'"></td><td class="text-center">'+contact_phone_list+'<input type="hidden" name="contact_phone[]" value="'+contact_phone_p+'" id="contact_phone_'+selectedContactTrId+'"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="contactEditButton plus_delteicon bg-primary-color"><img src="/frontend/assets/images/edit_pencil.png" alt="" title=""></a><a href="javascript:void(0)" class="removeLocationData plus_delteicon btn-button"><img src="/frontend/assets/images/delete.png" alt="" title=""></a><input type="hidden" name="contactRadio[]" value="'+contactRadioValue+'" id="selectedContactRadio_'+selectedContactTrId+'"><input type="hidden" name="contact_recordid[]" value="'+contact_recordid_p+'" id="existingContactIds_'+selectedContactTrId+'"></td>')
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
        $('#contact_visibility_p').val('public')


        $('#contact_service_p').selectpicker('refresh')

        $('#addPhoneTrContact').empty()
        $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : "" }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
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
        $('#contact_visibility_p').val('public')

        // $('#addPhoneTrContact').empty()
        // $('#addPhoneTrContact').append('<tr id="contact_0"><td><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true"> @foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
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
        $('#addPhoneTrContact').append('<tr id="contact_0"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_0"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_0"></td><td style="width: 15%;"><select name="phone_type[]" id="phone_type_contact_0" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><select name="phone_language[]" id="phone_language_contact_0" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_0"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>')
        $('.selectpicker').selectpicker('refresh');

        // $('.contactRadio').val()
        let radioValue = $("#selectedContactRadio_"+id).val();
        let contact_name_p = $('#contact_name_'+id).val()
        let contact_visibility_p = $('#contact_visibility_'+id).val()
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
            $('#addPhoneTrContact').append('<tr id="contact_'+index+'"><td style="width: 20%;"><input type="text" class="form-control" name="service_phones[]" id="service_phones_contact_'+index+'" value="'+phone_number_contact[index]+'"></td><td><input type="text" class="form-control" name="phone_extension[]" id="phone_extension_contact_'+index+'" value="'+(phone_extension_contact[index] != null ? phone_extension_contact[index] : "" )+'"></td><td style="width: 15%;"><select name="phone_type" id="phone_type_contact_'+index+'" class="form-control selectpicker" data-live-search="true" data-size="5"> <option value="">Select phone type</option>@foreach ($phone_type as $key => $value)<option value="{{ $key }}" {{ "voice" == strtolower($value) ? "selected" : "" }}>{{ $value }}</option> @endforeach </select></td><td><select name="phone_language" id="phone_language_contact_'+index+'" class="form-control selectpicker" data-size="5" data-live-search="true" multiple="true">@foreach ($phone_languages as $key => $value)<option value="{{ $key }}" >{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id="phone_description_contact_'+index+'" value="'+(phone_description_contact[index] != null ? phone_description_contact[index] : "")+'"></td><td class="text-center"><a href="javascript:void(0)" class="removePhoneData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a></td></tr>');

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
            $('#contact_visibility_p').val(contact_visibility_p)
            $('#contact_department_p').val(contact_department_p)
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
</script>
@endsection
