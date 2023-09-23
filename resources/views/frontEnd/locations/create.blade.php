@extends('layouts.app')
@section('title')
    Location Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="location_organization"],
    button[data-id="facility_schedules"],
    button[data-id="facility_details"],
    button[data-id="location_services"],
    button[data-id="facility_address_city"],
    button[data-id="facility_address_state"],
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
                <div class="col-md-12">
                    <h4 class="card-title title_edit mb-30">
                        Create New Location
                    </h4>
                    {!! Form::open(['route' => 'facilities.store']) !!}

                    <div class="card all_form_field ">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Name: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>The name of the location (e.g., Northeast Center, Engine 18)</p>
                                            </div>
                                        </div>
                                        {!! Form::text('location_name', null, ['class' => 'form-control', 'id' => 'location_name']) !!}
                                        @error('location_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        {!! Form::select('location_organization', $organization_name_list, null, ['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'location_organization','placeholder' => 'select organization',]) !!}
                                        @error('location_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Alternate Name: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>An alternative name for the location</p>
                                            </div>
                                        </div>
                                        {!! Form::text('location_alternate_name', null, ['class' => 'form-control', 'id' => 'location_alternate_name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Service: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>Link to any services provided at the facility.</p>
                                            </div>
                                        </div>
                                        {!! Form::select('location_services[]', $service_info_list, null, [
                                            'class' => 'form-control selectpicker',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                            'id' => 'location_services',
                                            'multiple' => 'true',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Description: </label>
                                        <div class="help-tip">
                                            <div>
                                                <p>A description of this location.</p>
                                            </div>
                                        </div>
                                        {!! Form::textarea('location_description', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Street Address: </label>
                                        {!! Form::text('facility_street_address', null, ['class' => 'form-control', 'id' => 'facility_street_address']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City: </label>
                                        {!! Form::select('facility_address_city', $address_city_list, null, [
                                            'class' => 'form-control selectpicker',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                            'id' => 'facility_address_city',
                                            'placeholder' => 'select city',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State: </label>
                                        {!! Form::select('facility_address_state', $address_states_list, null, [
                                            'class' => 'form-control selectpicker',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                            'id' => 'facility_address_state',
                                            'placeholder' => 'select state',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Zip Code: </label>
                                        {!! Form::text('facility_zip_code', null, ['class' => 'form-control', 'id' => 'facility_zip_code']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Regions: </label>
                                        {!! Form::select('regions', $regions, null, [
                                            'class' => 'form-control selectpicker',
                                            'data-live-search' => 'true',
                                            'data-size' => '5',
                                            'id' => 'regions',
                                            'multiple' => true,
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ADA Compliant: </label>
                                        {!! Form::select( 'accessibility', $accessibilities,null,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'accessibility','placeholder' => 'select accessibility',],) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Accessibility Details: </label>
                                        {!! Form::textarea('accessibility_details','Visitors with concerns about the level of access for specific physical conditions, are always recommended to contact the organization directly to obtain the best possible information about physical access',['class' => 'form-control', 'id' => 'accessibility_details', 'placeholder' => 'Accessibility Details'],) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card all_form_field">
                        <div class="card-block">
                            <h4 class="title_edit text-left mb-25 mt-10">
                                Details
                                <div class="d-inline float-right" id="addDetailTr">
                                    <a href="javascript:void(0)" id="addDetailData" class="plus_delteicon bg-primary-color">
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
                                                cellspacing="0" width="100%" style="display: table" id="DetailTable">
                                                <thead>
                                                    <th>Detail Type</th>
                                                    <th>Detail Term</th>
                                                    <th style="width:60px">&nbsp;</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            {!! Form::select('detail_type[]', $detail_types, null, ['class' => 'form-control selectpicker detail_type','placeholder' => 'select detail type','id' => 'detail_type_0',]) !!}

                                                        </td>
                                                        <td class="create_btn">
                                                            {!! Form::select('detail_term[]', [], null, ['class' => 'form-control selectpicker detail_term','placeholder' => 'select detail term','id' => 'detail_term_0',]) !!}
                                                            <input type="hidden" name="term_type[]" id="term_type_0"value="old">
                                                        </td>
                                                        <td style="vertical-align: middle">
                                                            <a href="#" class="plus_delteicon btn-button">
                                                                <img src="/frontend/assets/images/delete.png" alt=""title="">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr></tr>
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
                        <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-facility-btn"> Back</button>
                        <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-facility-btn"> Save</button>
                    </div>

                    {!! Form::close() !!}
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
            // $('select#location_organization').val([]).change();
            $('select#facility_schedules').val([]).change();
            // $('select#facility_address_city').val([]).change();
            // $('select#facility_address_state').val([]).change();
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
            $('#DetailTable').append('<tr><td><select name="detail_type[]" id="detail_type_' + d + '" class="form-control selectpicker detail_type"><option value="">Select Detail Type</option> @foreach ($detail_types as $key => $type)<option value="{{ $key }}">{{ $type }}</option> @endforeach </select></td><td class="create_btn"> <select name="detail_term[]" id="detail_term_' + d + '" class="form-control selectpicker detail_term"><option value="">Select Detail term</option> </select><input type="hidden" name="term_type[]" id="term_type_' + d + '" value="old"></td><td style="vertical-align:middle;"><a href="javascript:void(0)" class="plus_delteicon btn-button removePhoneData"><img src="/frontend/assets/images/delete.png" alt="" title=""></a></td></tr>');
            $('.selectpicker').selectpicker();
            d++;
        })
        $(document).on('click', '.removePhoneData', function() {
            $(this).closest('tr').remove()
        });
        $("#add-phone-input").click(function() {
            $("ol#phones-ul").append("<li class='facility-phones-li mb-2  col-md-4'>" + "<input class='form-control selectpicker facility_phones'  type='text' name='facility_phones[]'>" + "</li>");
        });
    </script>
@endsection
