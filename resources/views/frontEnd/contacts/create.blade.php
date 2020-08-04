@extends('layouts.app')
@section('title')
Contact Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
   button[data-id="contact_organization_name"],button[data-id="contact_service"] {
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
                            <p>Create New Contact</p>
                        </h4>
                        {{-- <form action="/add_new_contact" method="GET"> --}}
                            {!! Form::open(['route' => 'contacts.store']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Name: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_name" name="contact_name" value="">
                                        @error('contact_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="contact_organization_name"
                                            name="contact_organization_name" data-size="5" >
                                            @foreach($organization_name_list as $key => $org_name)
                                            <option value="{{$org_name}}">{{$org_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('contact_organization_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Service: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="contact_service"
                                            name="contact_service[]" data-size="8">
                                            @foreach($service_info_list as $key => $service_info)
                                            <option value="{{$service_info->service_recordid}}">{{$service_info->service_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('contact_service')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Title: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_title" name="contact_title" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Department: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_department" name="contact_department" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Email: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_email" name="contact_email" value="">
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Phone Number: </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                        <input class="form-control selectpicker" type="text" id="contact_cell_phones"
                                            name="contact_cell_phones" value="">
                                        <p id="error_cell_phone" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                    </div>
                                </div> -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label>
                                        <ol id="phones-ul" class="row p-0 m-0" style="list-style: none;">
                                            <li class="contact-phones-li mb-2 col-md-4">
                                                <input class="form-control selectpicker contact_phones"  type="text" name="contact_phones[]" value="">
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Phone Extension: </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                                        <input class="form-control selectpicker" type="text" id="contact_phone_extension" name="contact_phone_extension"
                                            value="">
                                    </div>
                                </div> -->
                                <div class="col-md-12 text-center">
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->user_organization &&  Auth::user()->roles->name == 'Organization Admin')
                                        <a href="/" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-contact-btn"> Back</a>
                                    @else
                                        <a href="/contacts" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-contact-btn"> Back</a>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-contact-btn"> Save</button>

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
<script>
    $('#back-contact-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#contact_organization_name').val([]).change();
        $('select#contact_service').val([]).change();
    });

    // $(document).ready(function(){
    //     $('#error_cell_phone').hide();
    //     $("#contacts-create-content").submit(function(event){
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
            "<li class='service-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</li>" );
    });

</script>
@endsection
