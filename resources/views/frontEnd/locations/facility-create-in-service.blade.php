@extends('layouts.app')
@section('title')
Location Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="facility_schedules"], button[data-id="facility_details"], button[data-id="facility_service"], button[data-id="facility_address_city"], button[data-id="facility_address_state"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <h4 class="card-title title_edit mb-30">
                    Create New Location
                </h4>
                {{-- <form action="/add_new_facility_in_organization" method="GET"> --}}
                {!! Form::open(['route' => 'add_new_facility_in_service']) !!}
                    <div class="card all_form_field">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Name: </label>
                                        <input class="form-control selectpicker" type="text" id="location_name"
                                            name="location_name" value="">
                                    </div>
                                </div>
                                <input type="hidden" id="facility_service" name="facility_service" value="{{$service_recordid}}">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        {{-- <select class="form-control selectpicker" data-live-search="true" id="facility_organization"
                                            name="facility_organization" data-size="5">
                                            <option value="">Select Organization</option>
                                            @foreach($organization_name_list as $key => $org_name)
                                            <option value="{{$org_name}}">{{$org_name}}</option>
                                            @endforeach
                                        </select> --}}
                                        {!! Form::select('facility_organization',$organizations,$organization_recordid,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','placeholder' => 'Select organization']) !!}
                                        @error('facility_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Alternate Name: </label>
                                        <input class="form-control selectpicker" type="text" id="location_alternate_name"
                                            name="location_alternate_name" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Transportation: </label>
                                        <input class="form-control selectpicker" type="text" id="location_transporation"
                                            name="location_transporation" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Details: </label>
                                        <input class="form-control selectpicker" type="text" id="location_details"
                                            name="location_details" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Description: </label>
                                        <textarea id="location_description" name="location_description" class="form-control selectpicker" rows="5" cols="30"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Service: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_service"
                                            name="facility_service[]" data-size="8">
                                            @foreach($service_info_list as $key => $service_info)
                                            <option value="{{$service_info->service_recordid}}">{{$service_info->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> -->
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Schedule: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_schedules"
                                            name="facility_schedules[]" data-size="5" >
                                            @foreach($schedule_info_list as $key => $schedule_info)
                                            <option value="{{$schedule_info->schedule_recordid}}">{{$schedule_info->opens_at}} ~ {{$schedule_info->closes_at}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Street Address: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_street_address"
                                            name="facility_street_address" value="">
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                        <label>Phone Number: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_phones"
                                            name="facility_phones" value="">
                                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                    </div>
                                </div> -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_city"
                                            name="facility_address_city", data-size="5">
                                            @foreach($address_city_list as $key => $address_city)
                                            <option value="{{$address_city}}">{{$address_city}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_state"
                                            name="facility_address_state", data-size="5">
                                            @foreach($address_states_list as $key => $address_state)
                                            <option value="{{$address_state}}">{{$address_state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Zip Code: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_zip_code"
                                            name="facility_zip_code" value="">
                                    </div>
                                </div>

                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label>
                                        <ol id="phones-ul" class="row p-0 m-0" style="list-style: none;">
                                            <li class="facility-phones-li mb-2 col-md-4">
                                                <input class="form-control selectpicker facility_phones"  type="text" name="facility_phones[]" value="">
                                            </li>
                                        </ol>
                                    </div>
                                </div> --}}
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
                                                    <th style="width:60px">&nbsp;</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="facility_phones[]" id="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="phone_extension[]" id="">
                                                        </td>
                                                        <td>
                                                            {!! Form::select('phone_type[]',$phone_type,[],['class' => 'form-control selectpicker','data-live-search' => 'true','id' => 'phone_type','data-size' => 5,'placeholder' => 'select phone type'])!!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select('phone_language[]',$phone_languages,[],['class' => 'form-control selectpicker phone_language','data-size' => 5,' data-live-search' => 'true',"multiple" => true,"id" => "phone_language_0"]) !!}
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="phone_description[]" id="">
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
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-facility-btn"> Back</button>
                        <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-facility-btn"> Save</button>
                    </div>
                {!! Form::close() !!}
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>

<script>
    $('#back-facility-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#facility_schedules').val([]).change();
        $('select#facility_address_city').val([]).change();
        $('select#facility_address_state').val([]).change();
    });
    // $(document).ready(function(){
    //     $('#error_cell_phone').hide();
    //     $("#facility-create-content").submit(function(event){
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

    let phone_language_data = []
    $(document).on('change','div > .phone_language',function () {
        let value = $(this).val()
        let id = $(this).attr('id')
        let idsArray = id ? id.split('_') : []
        let index = idsArray.length > 0 ? idsArray[2] : ''
        phone_language_data[index] = value
        $('#phone_language_data').val(JSON.stringify(phone_language_data))
    })
    pt = 1
    $('#addPhoneTr').click(function(){
        $('#PhoneTable tr:last').before('<tr><td><input type="text" class="form-control" name="facility_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select("phone_type[]",$phone_type,[],["class" => "form-control selectpicker","data-live-search" => "true","id" => "phone_type","data-size" => 5,"placeholder" => "select phone type"])!!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key=>$value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
        $('.selectpicker').selectpicker();
        pt++;
    })
    $(document).on('click', '.removePhoneData', function(){
        $(this).closest('tr').remove()
    });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='facility-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>"
          + "</li>" );
    });

</script>
@endsection
