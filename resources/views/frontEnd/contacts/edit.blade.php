@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="contact_organization"], button[data-id="contact_services"] {
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
                <div class="card all_form_field">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Edit Contact</p>
                        </h4>
                        {{-- <form action="/contact/{{$contact->contact_recordid}}/update" method="GET"> --}}
                            {!! Form::model($contact,['route' => ['contacts.update',$contact->contact_recordid],'method' => 'PUT']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Contact Name: </label>
                                        {!! Form::text('contact_name',null,['class' => 'form-control','id' => 'contact_name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Organization: </label>
                                        {!! Form::select('contact_organizations',$organization_info_list,null,['class'=> 'form-control selectpicker','id' => 'contact_organizations','data-live-search' => 'true','data-size' => '5','placeholder' => 'Select Organization']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Services: </label>
                                        {!! Form::select('contact_services[]',$service_info_list,null,['class'=> 'form-control selectpicker','id' => 'contact_services','data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Contact Title: </label>
                                        {!! Form::text('contact_title',null,['class' => 'form-control','id' => 'contact_title']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Contact Department: </label>
                                        {!! Form::text('contact_department',null,['class' => 'form-control','id' => 'contact_department']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label>Contact Email: </label>
                                        {!! Form::text('contact_email',null,['class' => 'form-control','id' => 'contact_email']) !!}
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
                                <div class="col-md-12">
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
                                </div>
                                <!-- <div class="form-group">
                                    <label>Phone Extension: </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                        <input class="form-control selectpicker" type="text" id="contact_phone_extension" name="contact_phone_extension"
                                            value="{{$contact->contact_phone_extension}}">
                                    </div>
                                </div> -->
                                <div class="col-md-12 text-center">
                                    <a href="/contacts/{{$contact->contact_recordid}}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"> Back</a>
                                    <button type="button" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic red_btn" id="delete-contact-btn" value="{{$contact->contact_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg" >Delete</button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-contact-btn "> Save</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <button type="submit" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                            <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                        </div>
                    {{-- </form> --}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('button#delete-contact-btn').on('click', function() {

        var value = $(this).val();
        $('input#contact_recordid').val(value);
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
    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='contact-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</li>" );
    });
</script>
@endsection
