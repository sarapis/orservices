@extends('backLayout.app')
@section('title')
SDOH Code Ledger
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
    .panel-link {
        display: inline-block;
        padding: 4px 8px;
        text-align: center;
        margin: 4px;
        vertical-align: baseline;
        border-radius: 4px;
        font-weight: 500;
        font-size: 12px;
        line-height: 14px;
        font-family: "Neue Haas Grotesk Display Roman";
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>SDOH Code Ledger</h2>
            <div class="nav navbar-right panel_toolbox">
                <a href="javascript:void(0)" id="export_csv" class="btn btn-success">Export SDOH Code Ledger</a>
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Select Service</label>
              <div class="col-sm-7">
                  {!! Form::select('services[]',$services,null,['class' => 'form-control','id' => 'services','placeholder' => 'select', 'data-live-search' => 'true','data-size' => '5']) !!}
              </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Select Organization</label>
              <div class="col-sm-7">
                   {!! Form::select('organizations',$organizations,null,['class' => 'form-control','id' => 'organizations','placeholder' => 'select', 'data-live-search' => 'true','data-size' => '5']) !!}
              </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Select Resources</label>
              <div class="col-sm-7">
                   {!! Form::select('resources',$resources,null,['class' => 'form-control','id' => 'resources','placeholder' => 'select', 'data-live-search' => 'true','data-size' => '5']) !!}
              </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">SDOH Code Category</label>
              <div class="col-sm-7">
                   {!! Form::select('category',$category,null,['class' => 'form-control','id' => 'category','placeholder' => 'select', 'data-live-search' => 'true','data-size' => '5']) !!}
              </div>
          </div>
        </div>
    </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="code_ledger_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Action</th>
                    <th class="text-center">Ledger ID</th>
                    <th class="text-center">SDOH Code ID</th>
                    <th class="text-center">SDOH Code</th>
                    <th class="text-center">SDOH Code Category</th>
                    <th class="text-center">SDOH Code Resource</th>
                    <th class="text-center">SDOH Code Resource Element</th>
                    <th class="text-center">SDOH Code Description</th>
                    <th class="text-center">SDOH Code Type</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Grouping</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">Timestamp</th>
                    {{-- <th class="text-center">Action</th> --}}
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
    $('#services').selectpicker()
    $('#organizations').selectpicker()
    $('#resources').selectpicker()
    let code_ledger_table;
    let extraData = {};
    let ajaxUrl
    ajaxUrl = "{{ route('code_ledgers.index') }}";
  $(document).ready(function(){
    code_ledger_table = $('#code_ledger_table').DataTable({
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
                { data: 'action', name: 'action' },
                { data: 'id', name: 'id' },
                { data: 'code_id', name: 'code_id' },
                { data: 'code', name: 'code' },
                { data: 'category', name: 'category' },
                { data: 'resource', name: 'resource' },
                { data: 'resource_element', name: 'resource_element' },
                { data: 'description', name: 'description' },
                { data: 'code_type', name: 'code_type' },
                { data: 'rating', name: 'rating' },
                { data: 'service', name: 'service' },
                { data: 'procedure_grouping', name: 'procedure_grouping' },
                { data: 'organization', name: 'organization' },
                { data: 'created_at', name: 'created_at' },
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
                {
                    "targets": 10,
                    "orderable": true,
                    "class": "text-left"
                },
            ],
        });
        $('#services').change(function(){
            let val = $(this).val()
            extraData.services = val
            code_ledger_table.ajax.reload()
        })
        $('#resources').change(function(){
            let val = $(this).val()
            extraData.resources = val
            code_ledger_table.ajax.reload()
        })
        $('#category').change(function(){
            let val = $(this).val()
            extraData.category = val
            code_ledger_table.ajax.reload()
        })
        $('#organizations').change(function(){
            let val = $(this).val()
            extraData.organizations = val
            code_ledger_table.ajax.reload()
        })
        $('#export_csv').click(function () {
            _token = '{{ csrf_token() }}'
            $.ajax({
                url:"{{ route('code_ledgers.export') }}",
                method : 'POST',
                data:{extraData},
                success:function(response){
                    // const url = window.URL.createObjectURL(new Blob([response]));
                    const a = document.createElement('a');
                            a.href = response.path;
                            a.download = 'code_ledgers.csv';
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
