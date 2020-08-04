@extends('layouts.app')
@section('title')
Organization Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="organization_services"] , button[data-id="organization_contacts"], button[data-id="organization_phones"], button[data-id="organization_locations"], button[data-id="organization_rating"] {
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
                            <p>Create New Organization</p>
                        </h4>
                        {{-- <form action="/add_new_organization" method="GET"> --}}
                        {!! Form::open(['route' => 'organizations.store']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b> Organization Name :</b> </label>
                                    <input class="form-control selectpicker" type="text" id="organization_name" name="organization_name" value="">
                                    @error('organization_name')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b> Alternate Name :</b> </label>
                                    <input class="form-control selectpicker" type="text" id="organization_alternate_name" name="organization_alternate_name" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b> Description : </b> </label>
                                    <textarea id="organization_description" name="organization_description" class="selectpicker" rows="5"></textarea>
                                    @error('organization_description')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b> Email :</b> </label>
                                    <input class="form-control selectpicker" type="text" id="organization_email" name="organization_email" value="">
                                    @error('organization_email')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b> URL :</b> </label>
                                    <input class="form-control selectpicker" type="text"  id="organization_url" name="organization_url" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Legal Status: </label>
                                    <input class="form-control selectpicker"  type="text" id="organization_legal_status"  name="organization_legal_status" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tax Status: </label>
                                    <input class="form-control selectpicker"  type="text" id="organization_tax_status" name="organization_tax_status" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tax ID: </label>
                                    <input class="form-control selectpicker"  type="text" id="organization_tax_id" name="organization_tax_id" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Year Incorporated: </label>
                                    <input class="form-control selectpicker"  type="text" id="organization_year_incorporated" name="organization_year_incorporated" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Services: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" id="organization_services" data-size="5" name="organization_services[]">
                                        @foreach($services_info_list as $key => $services_info)
                                            <option value="{{$services_info->service_recordid}}">{{$services_info->service_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contacts: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="organization_contacts" name="organization_contacts[]">
                                        @foreach($organization_contacts_list as $key => $organization_cont)
                                            <option value="{{$organization_cont->contact_recordid}}">{{$organization_cont->contact_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (Auth::user() && Auth::user()->roles->name == 'System Admin')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Website Rating: </label>
                                    <select class="form-control selectpicker" data-live-search="true" id="organization_rating" data-size="5" name="organization_rating">
                                        <option value="">Select Rating</option>
                                        @foreach($rating_info_list as $key => $rating_info)
                                            <option value="{{$rating_info}}">{{$rating_info}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a></label>
                                    <ol id="phones-ul" class="row p-0 m-0" style="list-style: none;">
                                        <li class="organization-phones-li mb-2 col-md-4">
                                            <input class="form-control selectpicker organization_phones"  type="text" name="organization_phones[]" value="">
                                        </li>
                                    </ol>
                                </div>
                            </div>                            
                            <div class="col-md-12 text-center">
                                <a href="/" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-organization-btn"> Close</a>
                                <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-organization-btn"> Save</button>
                                
                            </div>
                        </div>
                        {{-- </form> --}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        $(document).ready(function() {
            $("#organization_services").selectpicker("");
            $("#organization_contacts").selectpicker("");
            $("#organization_locations").selectpicker("");
        });

    $("#add-phone-input").click(function(){

        $("ol#phones-ul").append(
            "<li class='organization-phones-li mb-2 col-md-4'>"
          + "<input class='form-control selectpicker organization_phones'  type='text' name='organization_phones[]'>"
          + "</li>" );
    });

</script>
@endsection
