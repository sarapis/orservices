@extends('backLayout.app')
@section('title')
Codes
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
    .dropdown-menu.open {
        max-height: 250px;
        overflow: auto !important;
        width: 100%;
    }
    .bootstrap-select .dropdown-toggle .filter-option {
        position: relative;
        top: 0;
        left: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        text-align: left;
    }
    .form-group{float: left;width: 100%}
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Codes</h2>
            <div class="nav navbar-right panel_toolbox">
                <a href="{{route('codes.import')}}" class="btn btn-info">Import Codes</a>
                <a href="javascript:void(0)" id="export_csv" class="btn btn-success">Export Codes</a>
                <a href="{{route('codes.create')}}" class="btn btn-primary">Add New SDOH Code</a>
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select SDOH Categoty</label>
                <div class="col-sm-7">
                    {!! Form::select('category',$category,null,['class' => 'form-control','id' => 'category','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Resource</label>
                <div class="col-sm-7">
                     {!! Form::select('resource',$resource,null,['class' => 'form-control','id' => 'resource','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Resource Element</label>
                <div class="col-sm-7">
                     {!! Form::select('resource_element',$resource_element,null,['class' => 'form-control','id' => 'resource_element','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Code System</label>
                <div class="col-sm-7">
                     {!! Form::select('code_system',$code_system,null,['class' => 'form-control','id' => 'code_system','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label"></label>
                <div class="row">
                    <div class="col-sm-1">
                         <input type="checkbox" name="code_with_service" id="code_with_service" value="1">
                    </div>
                    <p>Show Codes with Services    </p>
                </div>
            </div>
          </div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="codes_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">SDOH Category</th>
                    <th class="text-center">Resource</th>
                    <th class="text-center">Resource Element</th>
                    <th class="text-center">Code System</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Services</th>
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
<!-- Passing BASE URL to AJAX -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $('#organization').selectpicker()
    $('#dataType').selectpicker()
    $('#user').selectpicker()
    let codes_table;
    let extraData = {};
    let ajaxUrl
    ajaxUrl = "{{ route('codes.index') }}";
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
                { data: 'id', name: 'id' },
                { data: 'code', name: 'code' },
                { data: 'category', name: 'category' },
                { data: 'resource', name: 'resource' },
                { data: 'resource_element', name: 'resource_element' },
                { data: 'code_system', name: 'code_system' },
                { data: 'description', name: 'description' },
                { data: 'services', name: 'services' },
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
                            a.download = 'codes.csv';
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
