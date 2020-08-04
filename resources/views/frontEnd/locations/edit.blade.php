@extends('layouts.app')
@section('title')
Facility Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="facility_organization_name"], button[data-id="facility_service"], button[data-id="facility_schedules"], button[data-id="facility_details"], button[data-id="facility_service"], button[data-id="facility_address_city"], button[data-id="facility_address_state"] {
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
                <div class="card all_form_field ">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Edit Facility</p>
                        </h4>
                        {{-- <form action="/facility/{{$facility->location_recordid}}/update" method="GET"> --}}
                            {!! Form::model($facility,['route' => ['facilities.update',$facility->location_recordid]]) !!}
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facility Name: </label>
                                        <input class="form-control selectpicker" type="text" id="location_name"
                                            name="location_name" value="{{$facility->location_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        {!!
                                        Form::select('facility_organization_name',$organization_names,$facility->location_organization,['class'
                                        => 'form-control
                                        selectpicker','id' => 'facility_organization_name' ,'placeholder' => 'Select organization', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facility Alternate Name: </label>
                                        <input class="form-control selectpicker" type="text" id="location_alternate_name"
                                            name="location_alternate_name" value="{{$facility->location_alternate_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facility Transportation: </label>
                                        <input class="form-control selectpicker" type="text" id="location_transporation"
                                            name="location_transporation" value="{{$facility->location_transportation}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facility Service: </label>
                                        {!!
                                        Form::select('facility_service[]',$service_info_list, $facility_service_list, ['class'
                                        => 'form-control
                                        selectpicker','id' => 'facility_service', 'multiple' => 'true', 'data-live-search' => 'true', 'data-size' => '5']) !!}
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
                                        <label>Facility Schedule: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="facility_schedules"
                                            name="facility_schedules[]" data-size="5" >
                                            @foreach($schedule_info_list as $key => $schedule_info)
                                            <option value="{{$schedule_info->schedule_recordid}}" {{in_array($schedule_info->schedule_recordid,$facilitySchedule) ? 'selected' : '' }} >{{$schedule_info->schedule_opens_at}} ~ {{$schedule_info->schedule_closes_at}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Facility Description: </label>
                                        <textarea id="location_description" name="location_description" class=" selectpicker" rows="5" cols="30">{{$facility->location_description}}</textarea>
                                    </div>
                                </div>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Street Address: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_street_address"
                                            name="facility_street_address" value="{{$location_street_address}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="facility_address_city"
                                            name="facility_address_city", data-size="5">
                                            @foreach($address_city_list as $key => $address_city)
                                            <option value="{{$address_city}}" @if ($location_address_city==$address_city) selected @endif>{{$address_city}}</option>
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
                                            <option value="{{$address_state}}" @if ($location_state==$address_state) selected @endif>{{$address_state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Zip Code: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_zip_code"
                                            name="facility_zip_code" value="{{$location_zip_code}}" required pattern="[0-9]{5}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facility Details: </label>
                                        <input class="form-control selectpicker" type="text" id="location_details"
                                            name="location_details" value="{{$facility->location_details}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
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
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-facility-btn"> Back</button>
                                    <button type="button" class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn" id="delete-facility-btn" value="{{$facility->location_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"> Delete</button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-facility-btn"> Save</button>
                                    
                                </div>
                            </div>
                            {!! Form::close() !!}
                        {{-- </form> --}}
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
                    <button type="submit" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                    <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#back-facility-btn').click(function() {
        history.go(-1);
        return false;
    });
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#facility_recordid').val(value);
    });
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
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='facility-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>"
          + "</li>" );
    });

</script>
@endsection




