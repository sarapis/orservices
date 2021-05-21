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
                {!! Form::model($facility,['route' => ['facilities.update',$facility->location_recordid]]) !!}

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
                                    {!! Form::text('location_name',null,['class' => 'form-control','id' => 'location_name']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization Name: </label>
                                    {!!
                                    Form::select('location_organization',$organization_names,null,['class' => 'form-control selectpicker','id' => 'location_organization' ,'placeholder' => 'Select organization', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Alternate Name: </label>
                                    <div class="help-tip">
                                        <div>
                                            <p>An alternative name for the location</p>
                                        </div>
                                    </div>
                                    {!! Form::text('location_alternate_name',null,['class' => 'form-control','id' => 'location_alternate_name']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Service: </label>
                                    <div class="help-tip">
                                        <div>
                                            <p>Link to any services provided at the facility.</p>
                                        </div>
                                    </div>
                                    {!!
                                    Form::select('facility_service[]',$service_info_list, $facility_service_list, ['class' => 'form-control selectpicker','id' => 'facility_service', 'multiple' => 'true', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Location Description: </label>
                                    <div class="help-tip">
                                        <div>
                                            <p>A description of this location.</p>
                                        </div>
                                    </div>
                                    {!! Form::textarea('location_description',null,['class' => 'form-control']) !!}
                                </div>
                            </div>

                            @php
                            $facilitySchedule = [];
                            if(isset($facility->schedules)){
                            foreach($facility->schedules as $value){
                            $facilitySchedule[] = $value->schedule_recordid;
                            }
                            }
                            @endphp
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Street Address: </label>
                                    {!! Form::text('facility_street_address',null,['class' => 'form-control','id' => 'facility_street_address']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City: </label>
                                    {!! Form::select('facility_address_city',$address_city_list,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'facility_address_city','placeholder' => 'select city']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State: </label>
                                    {!! Form::select('facility_address_state',$address_states_list,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'facility_address_state','placeholder' => 'select state']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Zip Code: </label>
                                    {!! Form::text('facility_zip_code',null,['class' => 'form-control','id' => 'facility_zip_code']) !!}
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
                                    @foreach($facility->phones as $phone)
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
                                        @foreach($schedule_info_list as $key => $schedule_info)
                                        <option value="{{$schedule_info->schedule_recordid}}"
            {{in_array($schedule_info->schedule_recordid,$facilitySchedule) ? 'selected' : '' }}
            >{{$schedule_info->opens_at}} ~ {{$schedule_info->closes_at}}</option>
            @endforeach
            </select>
        </div>
    </div> --}}


    <!-- <div class="form-group">
                                    <label>Phone Number: </label>
                                    <input class="form-control selectpicker" type="text" id="facility_phones"
                                        name="facility_phones" value="{{$facility_phone_number}}">
                                    <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                </div>
                            </div> -->

    <!-- <div class="form-group">
                                    <label>Facility Address: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" id="facility_address"
                                        name="facility_address[]" data-size="5" >
                                        @foreach($address_info_list as $key => $address_info)
                                        @if($address_info->address_1)
                                        <option value="{{$address_info->address_recordid}}">{{$address_info->address_1}}, {{$address_info->address_city}}, {{$address_info->address_state_province}}, {{$address_info->address_postal_code}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
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
                                        {!! Form::select('detail_type[]',$detail_types,$value->detail_type,['class' =>
                                        'form-control selectpicker detail_type','placeholder' => 'select detail
                                        type','id' => 'detail_type_'.$key]) !!}

                                    </td>
                                    <td class="create_btn">
                                        @php
                                        // $create_new = ['create_new' => 'Create New'];
                                        $detail_terms =
                                        \App\Model\Detail::where('detail_type',$value->detail_type)->pluck('detail_value','detail_recordid')->put('create_new','Create
                                        New');
                                        // $detail_terms = array_merge($create_new,$detail_terms);
                                        // dd($detail_terms);
                                        @endphp
                                        {!! Form::select('detail_term[]',$detail_terms,$value->detail_recordid,['class'
                                        => 'form-control selectpicker detail_term','placeholder' => 'select detail
                                        term','id' => 'detail_term_'.$key]) !!}
                                        <input type="hidden" name="term_type[]" id="term_type_{{ $key }}" value="old">
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
                                        {!! Form::select('detail_type[]',$detail_types,null,['class' => 'form-control
                                        selectpicker detail_type','placeholder' => 'select detail type','id' =>
                                        'detail_type_0']) !!}

                                    </td>
                                    <td class="create_btn">
                                        {!! Form::select('detail_term[]',[],null,['class' => 'form-control selectpicker
                                        detail_term','placeholder' => 'select detail term','id' => 'detail_term_0']) !!}
                                        <input type="hidden" name="term_type[]" id="term_type_0" value="old">
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a href="#" class="plus_delteicon btn-button">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
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
<div class="card all_form_field">
    <div class="card-block">
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
                                <th style="width:60px">&nbsp;</th>
                            </thead>
                            <tbody>
                                @if (count($facility->phones) > 0)
                                @foreach ($facility->phones as $key => $value)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="facility_phones[]" id=""
                                            value="{{ $value->phone_number }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_extension[]" id=""
                                            value="{{ $value->phone_extension }}">
                                    </td>
                                    <td>
                                        {!! Form::select('phone_type[]',$phone_type,$value->phone_type ?
                                        explode(',',$value->phone_type) : [],['class' => 'form-control
                                        selectpicker','data-live-search' => 'true','id' => 'phone_type','data-size' =>
                                        5,'placeholder' => 'select phone type'])!!}
                                    </td>
                                    <td>
                                        {!! Form::select('phone_language[]',$phone_languages,$value->phone_language ?
                                        explode(',',$value->phone_language) : [],['class' => 'form-control selectpicker
                                        phone_language','data-size' => 5,'data-live-search' => 'true', 'id' =>
                                        'phone_language_'.$key,'multiple' => true]) !!}
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_description[]" id=""
                                            value="{{ $value->phone_description }}">
                                    </td>
                                    {{-- <td>
                                                        <a href="javascript:void(0)" data-id="{{ $value->phone_recordid }}"
                                    class="removePhoneData" style="color:rgb(3, 65, 8);" data-toggle="tooltip"
                                    title="Remove"> <i class="fa fa-minus-circle" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0)" data-id="{{ $value->phone_recordid }}"
                                        class="deletePhoneData" style="color:red;" data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td> --}}
                                    <td style="vertical-align: middle">
                                        <a href="javascript:void(0)" data-id="{{ $value->phone_recordid }}"
                                            class="plus_delteicon btn-button removePhoneData">
                                            <img src="/frontend/assets/images/delete.png" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr></tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="facility_phones[]" id="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_extension[]" id="">
                                    </td>
                                    <td>
                                        {!! Form::select('phone_type[]',$phone_type,null,['class' => 'form-control
                                        selectpicker ','data-live-search' => 'true','id' => 'phone_type','data-size' =>
                                        5,'placeholder' => 'select phone type'])!!}
                                    </td>
                                    <td>
                                        {!! Form::select('phone_language[]',$phone_languages,[],['class' =>
                                        'form-control selectpicker phone_language','data-size' => 5,' data-live-search'
                                        => 'true','multiple'=>true ,'id' => 'phone_language_0']) !!}
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="phone_description[]" id="">
                                    </td>
                                    <td></td>
                                </tr>
                                @endif

                                {{-- <tr id="addPhoneTr">
                                                    <td colspan="6" class="text-center">
                                                        <a href="javascript:void(0)" id="addData" style="color:blue;" data-toggle="tooltip" title="Add"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                                    </td>
                                                </tr> --}}
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
<div class="col-md-12 text-center">
    <button type="button"
        class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"
        id="back-facility-btn"> Back</button>
    <button type="button"
        class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn"
        id="delete-facility-btn" value="{{$facility->location_recordid}}" data-toggle="modal"
        data-target=".bs-delete-modal-lg"> Delete</button>
    <button type="submit"
        class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
        id="save-facility-btn"> Save</button>

</div>
{!! Form::close() !!}
{{-- </form> --}}
</div>
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
                        style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB;text-decoration:underline;">{{ $item->user ? $item->user->first_name.' '.$item->user->last_name : '' }}</b>
                </p>
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
<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
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
    $(document).on("change",'div > .detail_type', function(){
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''
        console.log(value,index)
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
        if($('#detail_term_p').val() == ''){
                $('#detail_term_error').show()
            setTimeout(() => {
                $('#detail_term_error').hide()
            }, 5000);
            return false
        }
        let detail_term = $('#detail_term_p').val()
        let index = $('#detail_term_index_p').val()
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

    let d = {{ count($locationDetails) > 0 ? count($locationDetails) : 1  }}
    $('#addDetailTr').click(function(){
        $('#DetailTable').append('<tr><td><select name="detail_type[]" id="detail_type_'+d+'" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="detail_term[]" id="detail_term_'+d+'" class="form-control selectpicker detail_term"><option value="">Select Detail term</option> </select><input type="hidden" name="term_type[]" id="term_type_'+d+'" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
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

     // phone table section
     let phone_language_data = JSON.parse($('#phone_language_data').val())
    $(document).on('change','div > .phone_language',function () {
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''
        phone_language_data[index] = value
        $('#phone_language_data').val(JSON.stringify(phone_language_data))
    })
    pt = {{ count($facility->phones) }}
    $('#addPhoneTr').click(function(){
        $('#PhoneTable tr:last').before('<tr><td><input type="text" class="form-control" name="facility_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select("phone_type[]",$phone_type,[],["class" => "form-control selectpicker","data-live-search" => "true","id" => "phone_type","data-size" => 5,"placeholder" => "select phone type"])!!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key=>$value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        pt++;
    })
    $(document).on('click', '.removePhoneData', function(){
        $(this).closest('tr').remove()
        let id = $(this).data('id')
        removePhoneDataId.push(id)
        $('#removePhoneDataId').val(removePhoneDataId)
        // $('#ConfirmMessage').empty();
        // $('#ConfirmMessage').append('<h4 style="">Remove this data from service.</h4>')
        // $('.bs-confirm-lg').modal('show');

    });
    $(document).on('click', '.deletePhoneData', function(){
        $(this).closest('tr').remove()
        let id = $(this).data('id')
        deletePhoneDataId.push(id)
        $('#deletePhoneDataId').val(deletePhoneDataId)
        // $('#ConfirmMessage').empty();
        // $('#ConfirmMessage').append('<h4 style="">Delete data permanently</h4>')
        // $('.bs-confirm-lg').modal('show');

    });
    // end

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='facility-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>"
          + "</li>" );
    });

</script>
@endsection
