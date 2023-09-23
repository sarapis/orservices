@extends('layouts.app')
@section('title')
    Location Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="facility_schedules"],
    button[data-id="facility_details"],
    button[data-id="facility_service"],
    button[data-id="facility_address_city"],
    button[data-id="facility_address_state"] {
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
                                <input type="hidden" id="facility_service" name="facility_service[]"
                                    value="{{ $service_recordid }}">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        {{-- <select class="form-control selectpicker" data-live-search="true" id="facility_organization"
                                            name="facility_organization" data-size="5">
                                            <option value="">Select Organization</option>
                                            @foreach ($organization_name_list as $key => $org_name)
                                            <option value="{{$org_name}}">{{$org_name}}</option>
                                            @endforeach
                                        </select> --}}
                                        {!! Form::select('facility_organization', $organizations, $organization_recordid, [
                                            'class' => 'form-control selectpicker',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                            'placeholder' => 'Select organization',
                                        ]) !!}
                                        @error('facility_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Street Address: </label>
                                        <input class="form-control selectpicker" type="text" id="facility_street_address"
                                            name="facility_street_address" value="">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City: </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="facility_address_city" name="facility_address_city", data-size="5">
                                            @foreach ($address_city_list as $key => $address_city)
                                                <option value="{{ $address_city }}">{{ $address_city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State: </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="facility_address_state" name="facility_address_state", data-size="5">
                                            @foreach ($address_states_list as $key => $address_state)
                                                <option value="{{ $address_state }}">{{ $address_state }}</option>
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
                                {{-- <div class="text-right col-md-12 mb-20">
                                    <button type="button" class="btn btn_additional bg-primary-color"
                                        data-toggle="collapse" data-target="#additional_location">Additional Info
                                        <img src="/frontend/assets/images/white_arrow.png" alt="" title="" />
                                    </button>
                                </div>
                                <div id="additional_location" class="collapse row m-0 col-md-12"> --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Regions </label>
                                            {!! Form::select('location_region_p', $regions, null, [
                                                'class' => 'form-control selectpicker',
                                                'data-live-search' => 'true',
                                                'data-size' => '5',
                                                'id' => 'location_region_p',
                                                'multiple' => true,
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ADA Compliant: </label>
                                            {!! Form::select('accessibility_recordid',$accessibilities,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'accessibility','placeholder' => 'select accessibility',],) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Accessibility Details: </label>
                                            {!! Form::textarea(
                                                'accessibility_details',
                                                'Visitors with concerns about the level of access for specific physical conditions, are always recommended to contact the organization directly to obtain the best possible information about physical access',
                                                ['class' => 'form-control', 'id' => 'accessibility_details', 'placeholder' => 'Accessibility Details'],
                                            ) !!}
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Alternate Name: </label>
                                            <input class="form-control selectpicker" type="text"
                                                id="location_alternate_name" name="location_alternate_name" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Transportation: </label>
                                            <input class="form-control selectpicker" type="text"
                                                id="location_transporation" name="location_transporation" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Location Description: </label>
                                            <textarea id="location_description" name="location_description" class="form-control selectpicker" rows="5"
                                                cols="30"></textarea>
                                        </div>
                                    </div> --}}
                                {{-- </div> --}}
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
                                Details
                                <div class="d-inline float-right" id="addDetailTr">
                                    <a href="javascript:void(0)" id="addDetailData"
                                        class="plus_delteicon bg-primary-color">
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
                                                    <tr>
                                                        <td>
                                                            {!! Form::select('detail_type[]', $detail_types, null, [
                                                                'class' => 'form-control selectpicker detail_type',
                                                                'placeholder' => 'select detail type',
                                                                'id' => 'detail_type_0',
                                                            ]) !!}

                                                        </td>
                                                        <td class="create_btn">
                                                            {!! Form::select('detail_term[]', [], null, [
                                                                'class' => 'form-control selectpicker detail_term',
                                                                'placeholder' => 'select detail term',
                                                                'id' => 'detail_term_0',
                                                            ]) !!}
                                                            <input type="hidden" name="term_type[]" id="term_type_0"
                                                                value="old">
                                                        </td>
                                                        <td style="vertical-align: middle">
                                                            <a href="#" class="plus_delteicon btn-button">
                                                                <img src="/frontend/assets/images/delete.png"
                                                                    alt="" title="">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr></tr>
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
                                <div class="d-inline float-right">
                                    <a href="javascript:void(0)"
                                        class="phoneModalOpenButton plus_delteicon bg-primary-color">
                                        <img src="/frontend/assets/images/plus.png" alt="" title="">
                                    </a>
                                </div>
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table
                                                class="display dataTable table-striped jambo_table table-bordered table-responsive"
                                                cellspacing="0" width="100%" style="display: table" id="PhoneTable">
                                                <thead>
                                                    <th>Number</th>
                                                    <th>Extension</th>
                                                    <th style="width:200px;position:relative;">Type
                                                        <div class="help-tip" style="top:8px;">
                                                            <div>
                                                                <p>Select “Main” if this is the organization's primary phone
                                                                    number (or leave blank)
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th style="width:200px;">Language(s)</th>
                                                    <th style="width:200px;position:relative;">Description
                                                        <div class="help-tip" style="top:8px;">
                                                            <div>
                                                                <p>A description providing extra information about the phone
                                                                    service (e.g. any special arrangements for accessing, or
                                                                    details of availability at particular times).
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>Main</th>
                                                    <th style="width:140px">&nbsp;</th>
                                                </thead>
                                                <tbody id="phonesTable">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="phone_language_data" id="phone_language_data"
                                    value="{{ $phone_language_data }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button"
                            class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn"
                            id="back-facility-btn"> Back</button>
                        <button type="submit"
                            class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn"
                            id="save-facility-btn"> Save</button>
                    </div>
                    {!! Form::close() !!}
                    {{-- </form> --}}
                </div>
            </div>
            {{-- phone modal --}}
            @include('frontEnd.locations.locationPhone')
            {{-- phone modal close --}}
            {{-- detail term modal --}}
            <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"
                id="create_new_term">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <button type="button" class="close detailTermCloseButton"><span
                                        aria-hidden="true">×</span>
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
                                            <input type="text" class="form-control" placeholder="Detail Term"
                                                id="detail_term_p">
                                            <input type="hidden" name="detail_term_index_p" value=""
                                                id="detail_term_index_p">
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
        $(document).on("change", 'div > .detail_type', function() {
            let value = $(this).val()
            let id = $(this).attr('id')
            let idsArray = id ? id.split('_') : []
            let index = idsArray.length > 0 ? idsArray[2] : ''
            if (value == '') {
                $('#detail_term_' + index).empty()
                $('#detail_term_' + index).val('')
                $('#detail_term_' + index).selectpicker('refresh')
                return false
            }
            $.ajax({
                url: '{{ route('getDetailTerm') }}',
                method: 'get',
                data: {
                    value
                },
                success: function(response) {
                    let data = response.data
                    $('#detail_term_' + index).empty()
                    $.each(data, function(i, v) {
                        $('#detail_term_' + index).append('<option value="' + i + '">' + v +
                            '</option>');
                    })
                    $('#detail_term_' + index).append(
                        '<option value="create_new">+ Create New</option>');
                    $('#detail_term_' + index).val('')
                    $('#detail_term_' + index).selectpicker('refresh')
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })
        $(document).on("change", 'div >.detail_term', function() {
            let value = $(this).val()
            let id = $(this).attr('id')
            let text = $("#" + id + " option:selected").text();
            let idsArray = id ? id.split('_') : []
            let index = idsArray.length > 0 ? idsArray[2] : ''

            if (value == 'create_new') {
                $('#detail_term_index_p').val(index)
                $('#create_new_term').modal('show')
            } else if (text == value) {
                $('#term_type_' + index).val('new')
            } else {
                $('#term_type_' + index).val('old')
            }
        })
        $('#detailTermSubmit').click(function() {

            let detail_term = $('#detail_term_p').val()
            let index = $('#detail_term_index_p').val()
            if ($('#detail_term_p').val() == '') {
                $('#detail_term_error').show()
                setTimeout(() => {
                    $('#detail_term_error').hide()
                }, 5000);
                return false
            }
            $('#term_type_' + index).val('new')
            $('#detail_term_' + index).append('<option value="' + detail_term + '">' + detail_term + '</option>');
            $('#detail_term_' + index).val(detail_term)
            $('#detail_term_' + index).selectpicker('refresh')
            $('#create_new_term').modal('hide')
            $('#detail_term_p').val('')
            $('#detail_term_index_p').val('')
        })
        $('.detailTermCloseButton').click(function() {

            let detail_term = $('#detail_term_p').val()
            let index = $('#detail_term_index_p').val()
            $('#term_type_' + index).val('old')
            $('#detail_term_' + index).val('');
            $('#detail_term_' + index).selectpicker('refresh')
            $('#create_new_term').modal('hide')
            $('#detail_term_p').val('')
            $('#detail_term_index_p').val('')
        })

        let d = 1
        $('#addDetailTr').click(function() {
            $('#DetailTable').append('<tr><td><select name="detail_type[]" id="detail_type_' + d +
                '" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="detail_term[]" id="detail_term_' +
                d +
                '" class="form-control selectpicker detail_term"><option value="">Select Detail term</option> </select><input type="hidden" name="term_type[]" id="term_type_' +
                d +
                '" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>'
                );
            $('.selectpicker').selectpicker();
            d++;
        })
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

        $(document).on('click', '.removePhoneData', function() {
            $(this).closest('tr').remove()
        });

        $("#add-phone-input").click(function() {
            $("ol#phones-ul").append(
                "<li class='facility-phones-li mb-2 col-md-4'>" +
                "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>" +
                "</li>");
        });
    </script>
@endsection
