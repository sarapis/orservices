@extends('layouts.app')
@section('title')
Organization Edit
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
                            <p>Edit Organization</p>
                        </h4>
                        {!! Form::model($organization,['route' => ['organizations.update',$organization->organization_recordid],'method' => 'PUT']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_name" name="organization_name" value="{{$organization->organization_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Alternate Name: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_alternate_name" name="organization_alternate_name" value="{{$organization->organization_alternate_name}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description: </label>
                                        <textarea id="organization_description" name="organization_description" class="selectpicker" rows="5" cols="30">{{$organization->organization_description}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_email" name="organization_email" value="{{$organization->organization_email}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>URL: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_url" name="organization_url" value="{{$organization->organization_url}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Legal Status: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_legal_status" name="organization_legal_status" value="{{$organization->organization_legal_status}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tax Status: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_tax_status" name="organization_tax_status" value="{{$organization->organization_tax_status}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tax ID: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_tax_id" name="organization_tax_id" value="{{$organization->organization_tax_id}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year Incorporated: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_year_incorporated" name="organization_year_incorporated" value="{{$organization->organization_year_incorporated}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Services: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="organization_services" data-size="5" name="organization_services[]">
                                            @foreach($services_info_list as $key => $services_info)
                                                <option value="{{$services_info->service_recordid}}" {{ in_array($services_info->service_recordid, $organization_service_list) ? 'selected' : '' }} >{{$services_info->service_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contacts: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="organization_contacts" name="organization_contacts[]">
                                            @foreach($organization_contacts_list as $key => $organization_cont)
                                                <option value="{{$organization_cont->contact_recordid}}" @if (in_array($organization_cont->contact_recordid, $contact_info_list)) selected @endif>{{$organization_cont->contact_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone: </label>
                                        <input class="form-control selectpicker"  type="text" id="organization_phones" name="organization_phones" value="{{isset($organization->phones) && count($organization->phones) > 0 ? $organization->phones[0]->phone_number : ''}}">
                                    </div>
                                </div>
                                <!--  <div class="form-group">
                                        <label>Phones: </label>
                                        <a id="add-phone-input">
                                            <span class="glyphicon glyphicon-plus-sign"></span>
                                        </a>
                                        <ol id="phones-ul">
                                            @foreach($organization->phones as $phone)
                                            <li class="organization-phones-li mb-2">
                                                <div class="col-md-12 col-sm-12 col-xs-12 organization-phones-div">
                                                    <input class="form-control selectpicker organization_phones"  type="text" name="organization_phones[]"value="{{$phone->phone_number}}">
                                                </div>
                                            </li>
                                            @endforeach
                                        </ol>
                                </div> -->
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Contacts: </label>
                                        <ol>
                                            @foreach($organization_services as $service)
                                                @foreach($service->contact as $contact)
                                                <li>
                                                    <h5>{{$contact->contact_name}}</h5>
                                                </li>
                                                @endforeach
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Locations: </label>
                                        <ol>
                                            @foreach($organization_services as $service)
                                            <li class="organization-phones-li mb-2">
                                                @foreach($service->locations as $location)
                                                    <h5>{{$location->location_name}}</h5>
                                                @endforeach
                                            </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Other Locations: </label>
                                        <ol id="other-locations-ul">
                                            @foreach($location_info_list as $location_info)
                                            <li class="organization-locations-li mb-2">
                                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="organization_locations" name="organization_locations[]">
                                                    @foreach($organization_locations_list as $key => $organization_loc)
                                                        <option value="{{$organization_loc->location_recordid}}" @if ($organization_loc->location_recordid==$location_info) selected @endif>{{$organization_loc->location_name}}</option>
                                                    @endforeach
                                                </select>
                                            </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div> -->
                                @if (Auth::user() && Auth::user()->roles->name == 'System Admin')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Website Rating: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="organization_rating" data-size="5" name="organization_rating">
                                            <option value="">Select Rating</option>
                                            @foreach($rating_info_list as $key => $rating_info)
                                                <option value="{{$rating_info}}" @if ($rating_info == $organization->organization_website_rating) selected @endif>{{$rating_info}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12 text-center">

                                    <a href="/organizations/{{$organization->organization_recordid}}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-organization-btn "> Close</a>
                                    <button type="button" class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn" id="delete-organization-btn " value="{{$organization->organization_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"> Delete</button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-organization-btn"> Save</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" id="organization_recordid" name="organization_recordid">
                            <h4>Are you sure to delete this organization?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                            <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                        </div>
                    </form>
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
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#organization_recordid').val(value);
    });
    // $("#add-phone-input").click(function(){
    //     $("ol#phones-ul").append(
    //         "<li class='organization-phones-li mb-2'>"
    //       + "<div class='col-md-12 col-sm-12 col-xs-12 organization-phones-div'>"
    //       + "<input class='form-control selectpicker organization_phones'  type='text' name='organization_phones[]'>"
    //       + "</div>"
    //       + "</li>" );
    // });
    $("#add-location-input").click(function(){
        $("ol#other-locations-ul").append(
            "<li class='organization-locations-li mb-2'>"
          + "<div class='col-md-12 col-sm-12 col-xs-12 organization-locations-div'>"
          + "<input class='form-control selectpicker organization_locations'  type='text' name='organization_locations[]'>"
          + "</div>"
          + "</li>" );
    });

</script>
@endsection
