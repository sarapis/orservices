@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="contact_organization"],
    button[data-id="contact_services"],
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
                    Edit Contact
                    {{-- <p class=" mb-30 ">A services that will improve your productivity</p> --}}
                </h4>
                {{-- <form action="/contact/{{$contact->contact_recordid}}/update" method="GET"> --}}
                {!! Form::model($contact,['route' => ['contacts.update',$contact->contact_recordid],'method' => 'PUT'])
                !!}
                <div class="card all_form_field">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Name: </label>
                                    {!! Form::text('contact_name',null,['class' => 'form-control','id' =>
                                    'contact_name']) !!}
                                    @error('contact_name')
                                    <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization: </label>
                                    {!! Form::select('contact_organizations',$organization_info_list,null,['class'=>
                                    'form-control selectpicker','id' => 'contact_organizations','data-live-search' =>
                                    'true','data-size' => '5','placeholder' => 'Select Organization']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Services: </label>
                                    {{-- {!! Form::select('contact_services[]',$service_info_list,null,['class'=> 'form-control selectpicker','id' => 'contact_services','data-live-search' => 'true','data-size' => '5']) !!} --}}
                                    <select class="form-control selectpicker" multiple data-live-search="true"
                                        id="contact_services" name="contact_services[]" data-size="8">
                                        @foreach($service_info_list as $key => $service_info)
                                        <option value="{{$service_info->service_recordid}}"
                                            {{ in_array($service_info->service_recordid,$contact_services) ? 'selected'  :'' }}>
                                            {{$service_info->service_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Title: </label>
                                    {!! Form::text('contact_title',null,['class' => 'form-control','id' =>
                                    'contact_title']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Department: </label>
                                    {!! Form::text('contact_department',null,['class' => 'form-control','id' =>
                                    'contact_department']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Visibility: </label>
                                    {!! Form::select('visibility',['public' => 'Public','private' => 'Private'],null,['class'=>'form-control selectpicker','id' => 'visibility']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Email: </label>
                                    {!! Form::text('contact_email',null,['class' => 'form-control','id' =>
                                    'contact_email']) !!}
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Phone: </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                        <input class="form-control selectpicker" type="text" id="contact_cell_phones"
                                            name="contact_cell_phones" @if ($contact_phone) value="{{$contact_phone->phone_number}}" @endif>
                                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                    </div>
                                </div> -->
                            {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label>
                                        <ol id="phones-ul" class="row p-0 m-0" style="list-style: none;">
                                            @foreach($contact->phone as $phone)
                                            @if ($phone->phone_number)
                                                <li class="contact-phones-li mb-2 col-md-4">
                                                    <input class="form-control selectpicker contact_phones"  type="text" name="contact_phones[]" value="{{$phone->phone_number}}">
                            </li>
                            @endif
                            @endforeach
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
                    <div class="d-inline float-right">
                        <a href="javascript:void(0)" class="phoneModalOpenButton plus_delteicon bg-primary-color">
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
                                        <th>Extension</th>
                                        <th style="width:200px;position:relative;">Type
                                            <div class="help-tip" style="top:8px;">
                                                <div>
                                                    <p>Select “Main” if this is the organization's primary phone number (or leave blank)
                                                    </p>
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
                                        <th>Main</th>
                                        <th style="width:140px">&nbsp;</th>
                                    </thead>
                                    <tbody id="phonesTable">
                                        @if (count($contact->phone) > 0)
                                        @foreach ($contact->phone as $key => $value)
                                        <tr id="phoneTr_{{ $key }}">
                                            <td>
                                                <input type="hidden" class="form-control" name="contact_phones[]" id="phone_number_{{ $key }}" value="{{ $value->phone_number }}">
                                                    {{ $value->phone_number }}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control" name="phone_extension[]" id="phone_extension_{{ $key }}" value="{{ $value->phone_extension }}" >
                                                 {{ $value->phone_extension }}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control" name="phone_type[]" id="phone_type_{{ $key }}" value="{{ $value->phone_type }}" >
                                                {{ $value->type ? $value->type->type : '' }}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control" name="phone_language[]" id="phone_language_{{ $key }}" value="{{ $value->phone_language }}" >
                                                {{ isset($phone_language_name[$key]) ? $phone_language_name[$key] : ''  }}
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
                                                <a href="javascript:void(0)" class="phoneEditButton plus_delteicon bg-primary-color">
                                                    <img src="/frontend/assets/images/edit_pencil.png" alt="" title="">
                                                </a>
                                                <a href="javascript:void(0)" class="plus_delteicon btn-button removeContactPhoneData" >
                                                    <img src="/frontend/assets/images/delete.png" alt="" title="">
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
                    <!-- <div class="form-group">
                                    <label>Phone Extension: </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                        <input class="form-control selectpicker" type="text" id="contact_phone_extension" name="contact_phone_extension"
                                            value="{{$contact->contact_phone_extension}}">
                                    </div>
                                </div> -->
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <a href="/contacts/{{$contact->contact_recordid}}"
                class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn">
                Back</a>
            <button type="button" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic red_btn" id="delete-contact-btn" value="{{$contact->contact_recordid}}" >Delete</button>
            <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-contact-btn "> Save</button>
        </div>

        {!! Form::close() !!}
        {{-- </form> --}}
    </div>
    @auth
    <div class="col-md-4">
        <h4 class="card-title title_edit mb-30"></h4>
        <div class="card all_form_field mt-40">
            <div class="card-block">
                <h4 class="card_services_title mb-20">Change Log</h4>
                @foreach ($contactAudits as $item)
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

                    <span><a href="/viewChanges/{{ $item->id }}/{{ $contact->contact_recordid }}"
                            style="font-family: Neue Haas Grotesk Display Medium; color:#5051DB; text-decoration:underline;">View
                            Changes</a></span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endauth
</div>
{{-- phone modal --}}
@include('frontEnd.contacts.contactPhone')
{{-- phone modala close --}}
<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="contact_delete_modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="/contact_delete_filter" method="POST" id="contact_delete_filter">
                {!! Form::token() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Delete Contact</h4>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="contact_recordid" name="contact_recordid">
                    <h4>Are you sure to delete this contact?</h4>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                    <button type="button"
                        class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic"
                        data-dismiss="modal">Close</button>
                </div>
                {{-- </form> --}}
                {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
</div>

<script>
    let removePhoneDataId = []
    let deletePhoneDataId = []
    $('[data-toggle="tooltip"]').tooltip();
    $('button#delete-contact-btn').on('click', function() {

        var value = $(this).val();
        $('input#contact_recordid').val(value);
        $('#contact_delete_modal').modal('show')
    });
    // $(document).ready(function(){
    //     $('#error_cell_phone').hide();
    //     $("#contacts-edit-content").submit(function(event){
    //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
    //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
    //         var contact_cell_phones_value = $("#contact_cell_phones").val();
    //         if (contact_cell_phones_value != ''){
    //             if(mob.test(contact_cell_phones_value) == false && contact_cell_phones_value != 10){
    //                 $('#error_cell_phone').show();
    //                 event.preventDefault();
    //             }
    //         }

    //     });
    // });
     // phone table section
    //  let phone_language_data = JSON.parse($('#phone_language_data').val())
    // $(document).on('change','div > .phone_language',function () {
    //     let value = $(this).val()
    //     let id = $(this).attr('id')
    //     let idsArray = id ? id.split('_') : []
    //     let index = idsArray.length > 0 ? idsArray[2] : ''
    //     phone_language_data[index] = value
    //     $('#phone_language_data').val(JSON.stringify(phone_language_data))
    // })
    pt = {{ count($contact->phone) }}
    $('#addPhoneTr').click(function(){
        $('#PhoneTable tr:last').before('<tr><td><input type="text" class="form-control" name="contact_phones[]" id=""></td><td><input type="text" class="form-control" name="phone_extension[]" id=""></td><td>{!! Form::select("phone_type[]",$phone_type,[],["class" => "form-control selectpicker","data-live-search" => "true","id" => "phone_type","data-size" => 5,"placeholder" => "select phone type"])!!}</td><td><select name="phone_language[]" id="phone_language_'+pt+'" class="form-control selectpicker phone_language" data-size="5" data-live-search="true" multiple> @foreach ($phone_languages as $key=>$value)<option value="{{ $key }}">{{ $value }}</option> @endforeach </select></td><td><input type="text" class="form-control" name="phone_description[]" id=""></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
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
            "<li class='contact-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</li>" );
    });
</script>
@endsection
