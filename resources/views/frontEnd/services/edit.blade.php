@extends('layouts.app')
@section('title')
Edit Service
@stop

<style type="text/css">
    button[data-id="service_organization"] , button[data-id="service_locations"], button[data-id="service_contacts"], button[data-id="service_taxonomy"], button[data-id="service_details"] {
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
                            <p>Edit Service</p>
                        </h4>
                        {{-- <form action="/services/{{$service->service_recordid}}/update" method="GET"> --}}
                        {!! Form::model($service,['route' => ['services.update',$service->service_recordid]]) !!}
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Name: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_name" name="service_name" value="{{$service->service_name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Alternate Name: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_alternate_name" name="service_alternate_name" value="{{$service->service_alternate_name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Organization Name: </label>
                                    <select class="form-control selectpicker" data-live-search="true" id="service_organization" data-size="5" name="service_organization">
                                        @foreach($service_organization_list as $key => $service_org)
                                            <option value="{{$service_org->organization_recordid}}" @if ($service->service_organization == $service_org->organization_recordid) selected @endif>{{$service_org->organization_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Service Description: </label>
                                    <textarea id="service_description" name="service_description" class="selectpicker" rows="5">{{$service->service_description}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service URL: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_url" name="service_url" value="{{$service->service_url}}">
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Program: </label>
                                    <!-- <input class="form-control selectpicker" type="text" id="service_program"
                                        name="service_program" value=""> -->
                                    {!! Form::text('service_program',null,['class' => 'form-control selectpicker','id' => 'service_program']) !!}
                                </div>
                            </div> --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Email: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_email" name="service_email" value="{{$service->service_email}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_address" name="service_address" @if($service_address_city) value="{{$service_address_street->address_1}}, {{$service_address_city->address_city}}, {{$service_address_state->address_state_province}}, {{$service_address_postal_code->address_postal_code}} @endif">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_status" name="service_status" value="{{$service->service_status}}">
                                </div>
                            </div>


                            

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Taxonomy: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_taxonomy" name="service_taxonomy[]">
                                        @foreach($service_taxonomy_list as $key => $service_taxo)
                                            <option value="{{$service_taxo->taxonomy_recordid}}" @if (in_array($service_taxo->taxonomy_recordid, $taxonomy_info_list)) selected @endif>{{$service_taxo->taxonomy_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Application Process: </label>
                                    <textarea id="service_application_process" name="service_application_process" class="form-control selectpicker" rows="5" cols="30">{{$service->service_application_process}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Wait Time: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_wait_time" name="service_wait_time" value="{{$service->service_wait_time}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fees: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_fees" name="service_fees" value="{{$service->service_fees}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Accreditations: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_accreditations" name="service_accreditations" value="{{$service->service_accreditations}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Licenses: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_licenses" name="service_licenses" value="{{$service->service_licenses}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Schedule: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" id="service_schedules"
                                        name="service_schedules[]" data-size="5" >
                                        @foreach($schedule_info_list as $key => $schedule_info)
                                        <option value="{{$schedule_info->schedule_recordid}}" <?php echo in_array($schedule_info->schedule_recordid,$ServiceSchedule) ?  'selected' : ''; ?>>{{$schedule_info->schedule_opens_at}} ~ {{$schedule_info->schedule_closes_at}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Details: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" id="service_details"
                                        name="service_details[]" data-size="5" >
                                        @foreach($detail_info_list as $key => $detail_info)
                                        <option value="{{$detail_info->detail_recordid}}" <?php echo in_array($detail_info->detail_recordid,$ServiceDetails) ?  'selected' : ''; ?>>{{$detail_info->detail_value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Meta Data: </label>
                                    {!! Form::text('service_metadata',null,['class' => 'form-control selectpicker','id' => 'service_metadata']) !!}
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Airs Taxonomy X: </label>
                                    {!! Form::text('service_airs_taxonomy_x',null,['class' => 'form-control selectpicker','id' => 'service_airs_taxonomy_x']) !!}
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Locations: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_locations" name="service_locations[]">
                                        @foreach($service_location_list as $key => $service_loc)
                                            <option value="{{$service_loc->location_recordid}}" @if (in_array($service_loc->location_recordid, $location_info_list)) selected @endif>{{$service_loc->location_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <!-- <div class="form-group">
                                    <label>Phone1: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_phone1" name="service_phone1" @if($service_phone1) value="{{$service_phone1->phone_number}}" @endif>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label>Phone2: </label>
                                    <input class="form-control selectpicker"  type="text" id="service_phone2" name="service_phone2" @if($service_phone2) value="{{$service_phone2->phone_number}}" @endif>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Contacts: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="service_contacts" name="service_contacts[]">
                                        @foreach($service_contacts_list as $key => $service_cont)
                                            <option value="{{$service_cont->contact_recordid}}" @if (in_array($service_cont->contact_recordid, $contact_info_list)) selected @endif>{{$service_cont->contact_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Phones:<a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label>
                                    <ol id="phones-ul" class="row p-0 m-0" style="list-style: none; ">
                                        @foreach($service->phone as $phone)
                                            <li class="service-phones-li mb-2  col-md-12 p-0">
                                                <input class="form-control selectpicker service_phones"  type="text" name="service_phones[]" value="{{$phone->phone_number}}">
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            <!--   <div class="form-group">
                                    <label>Details: </label>
                                <div class="col-md-12 col-sm-12 col-xs-12 service-details-div">
                                    <select class="form-control selectpicker" data-live-search="true" data-size="5" id="service_details" name="service_details">
                                        @foreach($service_details_list as $key => $service_det)
                                            <option value="{{$service_det->detail_recordid}}" @if ($service->service_details == $service_det->detail_recordid) selected @endif>{{$service_det->detail_value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-12 text-center">
                                
                                <a href="/services/{{$service->service_recordid}}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="view-service-btn"> Close</a>
                                <button type="button" class="btn btn-danger btn-lg btn_delete waves-effect waves-classic waves-effect waves-classic delete-td red_btn" id="delete-service-btn" value="{{$service->service_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"> Delete</button>
                                <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-service-btn"> Save</button>
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
                    <form action="{{ route('delete_service') }}" method="POST" id="service_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete service</h4>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" id="service_recordid" name="service_recordid">
                            <h4>Are you sure to delete this service?</h4>
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
        $("#service_organization").selectpicker("");
        $("#service_locations").selectpicker("");
        $("#service_contacts").selectpicker("");
        $("#service_taxonomy").selectpicker("");
        $("#service_details").selectpicker("");
    });

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#service_recordid').val(value);
    });

    $("#add-phone-input").click(function(){
        $("ol#phones-ul").append(
            "<li class='service-phones-li mb-2  col-md-12 p-0'>"
          + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
          + "</li> " );
    });

</script>
@endsection




