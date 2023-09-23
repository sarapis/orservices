@extends('layouts.app')
@section('title')
Terminology
@stop<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    tr.modified {
        background-color: red !important;
    }

    tr.modified>td {
        background-color: red !important;
        color: white;
    }
    .all_form_field .input-daterange input.form-control {width:auto !important;}
    .input-daterange .input-group-addon{
        line-height: 52px;
        vertical-align: middle;
        background-color: #eee;
        border: 1px solid #a4a8b8;
        border-width: 1px 0;
        padding: 0px 15px;
    }
    .input-daterange input:first-child{border-radius: 12px 0 0 12px;}
    .input-daterange input:last-child{border-radius: 0px 12px 12px 0px;}
    .datepicker td, .datepicker th{font-size: 11px;}
    #codes_table_wrapper > .row{width: 100%;margin: 0px;}
    #codes_table_wrapper > .row .col-sm-6, #codes_table_wrapper > .row .col-sm-12 {padding: 0px}
</style>
@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="locations-content" class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="card-title title_edit mb-30">
                    Terminology
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-12 control-label">SDOH Domain</label>
                                    <div>
                                        {!! Form::select('category',$category,$domain,['class' => 'form-control selectpicker ','id' => 'category','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-12 control-label">Resource</label>
                                    <div>
                                        {!! Form::select('resource',$resource,null,['class' => 'form-control selectpicker','id' => 'resource','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-6 control-label">Resource Element</label>
                                    <div>
                                        {!! Form::select('resource_element',$resource_element,null,['class' => 'form-control selectpicker','id' => 'resource_element','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-md-12 control-label">Code System</label>
                                    <div >
                                        {!! Form::select('code_system',$code_system,null,['class' => 'form-control selectpicker','id' => 'code_system','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-12 control-label">Procedure Grouping</label>
                                    <div>
                                        {{-- {!! Form::select('grouping',$groupings,null,['class' => 'form-control selectpicker','id' => 'grouping','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!} --}}
                                        <select name="grouping[]" class="form-control" id="grouping" data-live-search="true" data-size="5" multiple>
                                            @foreach ($groupings as $item)
                                                <option value="{{ $item->code_id }}">{{ $item->code_id .' - '. $item->get_category->name .' - '.$item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-12 control-label">Code</label>
                                    <div>
                                        {!! Form::select('code_selects',$code_selects,$code_selected,['class' => 'form-control selectpicker','id' => 'code_selects','multiple' => true, 'data-live-search' => 'true','data-size' => '5']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 control-label d-flex">Show Codes with Services
                                    <input type="checkbox" name="code_with_service" id="code_with_service" value="1" class="d-inline-block ml-2">
                                </div>
                            </div>
                        </div>
                        <table id="codes_table" class="display dataTable table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%" style="display:table;">
                            <thead>
                                <tr>
                                    {{-- <th class="text-center">UID</th>
                                    <th class="text-center">ID</th> --}}
                                    <th class="text-center">Code</th>
                                    <th class="text-center">SDOH Domain</th>
                                    <th class="text-center">Resource</th>
                                    <th class="text-center">Resource Element</th>
                                    <th class="text-center">Code System</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Grouping</th>
                                    {{-- <th class="text-center">Definition</th> --}}
                                    <th class="text-center">Notes</th>
                                    <th class="text-center">Services</th>
                                    {{-- <th class="text-center">Service Grouping</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="domain" id="domain" value="{{ json_encode($domain) }}">
<input type="hidden" name="code_selected" id="code_selected" value="{{ json_encode($code_selected) }}">
@endsection

@section('customScript')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    // $('#organization_tag').SumoSelect({ placeholder: 'Nothing selected' });
    $('#category').selectpicker()
    $('#resource').selectpicker()
    $('#resource_element').selectpicker()
    $('#code_system').selectpicker()
    $('#grouping').selectpicker()
    let codes_table;
    let extraData = {};
    let domain = JSON.parse($('#domain').val());
    let code_selected = JSON.parse($('#code_selected').val());
    if (domain.length > 0) {
        extraData.category = domain
    }
    if (code_selected.length > 0) {
        extraData.code = code_selected
    }
    let ajaxUrl
    ajaxUrl = "{{ route('terminology.index') }}";
  $(document).ready(function(){
    codes_table = $('#codes_table').DataTable({
            'order': [0, 'desc'],
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "get",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data : function (d){
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            d.extraData = extraData;
                        }
                    },
                },
            columns: [
                // { data: 'id', name: 'id' },
                // { data: 'code_id', name: 'code_id' },
                { data: 'code', name: 'code' },
                { data: 'category', name: 'category' },
                { data: 'resource', name: 'resource' },
                { data: 'resource_element', name: 'resource_element' },
                { data: 'code_system', name: 'code_system' },
                { data: 'description', name: 'description' },
                { data: 'grouping', name: 'grouping' },
                // { data: 'definition', name: 'definition' },
                { data: 'notes', name: 'notes' },
                { data: 'services', name: 'services' },
                // { data: 'procedure_grouping', name: 'procedure_grouping' },
                { data: 'action', name: 'action' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": true,
                    "class": "text-left",
                },
                {
                    "targets": 1,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 2,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 3,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 4,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 5,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 6,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 7,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 8,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 9,
                    "orderable": true,
                    "class": "text-left"
                },
                // {
                //     "targets": 10,
                //     "orderable": true,
                //     "class": "text-left"
                // },
                // {
                //     "targets": 11,
                //     "orderable": true,
                //     "class": "text-left"
                // },
            ],
        });
        $('#category').change(function(){
            let val = $(this).val()
            extraData.category = val
            codes_table.ajax.reload()
        })
        $('#resource').change(function(){
            let val = $(this).val()
            extraData.resource = val
            codes_table.ajax.reload()
        })
        $('#resource_element').change(function(){
            let val = $(this).val()
            extraData.resource_element = val
            codes_table.ajax.reload()
        })
        $('#code_system').change(function(){
            let val = $(this).val()
            extraData.code_system = val
            codes_table.ajax.reload()
        })
        $('#grouping').change(function(){
            let val = $(this).val()
            extraData.grouping = val
            codes_table.ajax.reload()
        })
        $('#code_selects').change(function(){
            let val = $(this).val()
            extraData.code_selects = val
            codes_table.ajax.reload()
        })
        $('#code_with_service').change(function(){
            let val = $(this).is(':checked')
            extraData.code_with_service = val
            codes_table.ajax.reload()
        })
        $('#start_date').change(function(){
            let val = $(this).val()
            extraData.start_date = val
            codes_table.ajax.reload()
        })
        $('#end_date').change(function(){
            let val = $(this).val()
            extraData.end_date = val
            codes_table.ajax.reload()
        })
        $('#export_csv').click(function () {
            _token = '{{ csrf_token() }}'
            $.ajax({
                url:"{{ route('codes.export') }}",
                method : 'POST',
                data:{extraData,},
                success:function(response){
                    // const url = window.URL.createObjectURL(new Blob([response]));
                    const a = document.createElement('a');
                            a.href = response.path;
                            a.download = 'codes.xlsx';
                            document.body.appendChild(a);
                            a.click();
                },
                error : function(error){
                    console.log(error)
                }
            })
        })
    });
</script>
@endsection
