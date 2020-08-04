@extends('layouts.app')
@section('title')
Contact Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
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
                <div class="card all_form_field">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Create New Contact</p>
                        </h4>
                        <form action="/add_new_contact_in_facility" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Name: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_name"
                                            name="contact_name" value="">
                                            @error('contact_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" id="contact_facility_recordid" name="contact_facility_recordid" value="{{$facility->location_recordid}}">
                                <input type="hidden" id="contact_organization" name="contact_organization" value="{{$facility->location_organization}}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Service: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="contact_service"
                                            name="contact_service[]" data-size="8">
                                            @foreach($service_info_list as $key => $service_info)
                                            <option value="{{$service_info->service_recordid}}">{{$service_info->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Title: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_title"
                                            name="contact_title" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Department: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_department" name="contact_department"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Email: </label>
                                        <input class="form-control selectpicker" type="text" id="contact_email"
                                            name="contact_email" value="">
                                    </div>
                                </div>
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

                                <div class="col-md-12 text-center">
                                    <button type="button"  class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-contact-btn"> Back</button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-contact-btn"> Save</button>
                                </div>
                            </div>
                        </form>
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
        $('select#contact_service').val([]).change();
    });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker contact_phones'  type='text' name='contact_phones[]'>"
          + "</li>" );
    });

</script>
@endsection
