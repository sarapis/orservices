@extends('backLayout.app')
@section('title')
Edits
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
        <h2>Edits </h2>
        <div class="nav navbar-right panel_toolbox">
            <a href="javascript:void(0)" id="export_csv" class="btn btn-info">Download CSV</a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations</label>
                <div class="col-sm-7">
                    {!! Form::select('organization',$organizations,null,['class' => 'form-control selectpicker','id' => 'organization','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select User</label>
                <div class="col-sm-7">
                    {!! Form::select('user',$users,$id,['class' => 'form-control','id' => 'user','placeholder' => 'Select', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
      </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations Tags</label>
                <div class="col-sm-7">
                    {!! Form::select('organization_tag',$organization_tags,null,['class' => 'form-control selectpicker','id' => 'organization_tag','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Data type</label>
                <div class="col-sm-7">
                    <select class="form-control selectpicker" data-live-search="true" id="dataType" name="dataType" data-size="5">
                        <option value="">Select </option>
                        @foreach($dataTypes as $key => $type)
                        <option value="{{$type}}">{{$type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

      <div class="row">
        {{-- <div class="col-md-6">
          <div class="form-group">
              <label for="inputPassword3" class="col-sm-3 control-label">Select Disposition</label>
              <div class="col-sm-7">
                  <select class="form-control selectpicker" data-live-search="true" id="session_disposition"
                      name="session_disposition" data-size="5">
                      <option value="">Select Disposition</option>
                      @foreach($disposition_list as $key => $disposition)
                      <option value="{{$disposition}}">{{$disposition}}</option>
                      @endforeach
                  </select>
              </div>
          </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Start Date</label>
                <div class="col-sm-7">
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Field type</label>
                <div class="col-sm-7">
                    <select class="form-control selectpicker" data-live-search="true" id="fieldType" name="fieldType" data-size="5">
                        <option value="">Select </option>
                        @foreach($fieldTypes as $key => $type)
                        <option value="{{$type}}">{{$type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">End Date</label>
                <div class="col-sm-7">
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
            </div>
        </div>
    </div>

      <div class="x_content">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="edits_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Time</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Event</th>
                    <th class="text-center">Data Type</th>
                    <th class="text-center">Field Type</th>
                    <th class="text-center">Change From</th>
                    <th class="text-center">Change To</th>
                    <th class="text-center">Record ID</th>
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
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#organization').selectpicker()
    $('#dataType').selectpicker()
    $('#user').selectpicker()
    let edits_table;
    let extraData = {};
    let id = "{{ $id }}"
    let ajaxUrl
    if(id != 0){
         ajaxUrl = "{{ route('edits.userEdits',$id) }}";
         extraData.user = id
    }else{
         ajaxUrl = "{{ route('edits.index') }}";
    }
  $(document).ready(function(){
    edits_table = $('#edits_table').DataTable({
            "order": [[ 0, "desc" ]],
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
                { data: 'created_at', name: 'created_at' },
                { data: 'time', name: 'time' },
                { data: 'organization', name: 'organization' },
                { data: 'user', name: 'user' },
                { data: 'event', name: 'event' },
                { data: 'auditable_type', name: 'auditable_type' },
                { data: 'field_type', name: 'field_type' },
                { data: 'change_from', name: 'change_from' },
                { data: 'change_to', name: 'change_to' },
                { data: 'auditable_id', name: 'auditable_id' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": true,
                    "class": "text-left",
                    "type" : "date-eu"
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
        $('#organization').change(function(){
            let val = $(this).val()
            extraData.organization = val
            edits_table.ajax.reload()
            // edits_table.search( val ).draw();
        })
        $('#organization_tag').change(function(){
            let val = $(this).val()
            extraData.organization_tag = val
            edits_table.ajax.reload()
        })
        $('#dataType').change(function(){
            let val = $(this).val()
            extraData.dataType = val
            edits_table.ajax.reload()
        })
        $('#fieldType').change(function(){
            let val = $(this).val()
            extraData.fieldType = val
            edits_table.ajax.reload()
        })
        $('#session_disposition').change(function(){
            let val = $(this).val()
            extraData.session_disposition = val
            edits_table.ajax.reload()
        })
        $('#user').change(function(){
            let val = $(this).val()
            extraData.user = val
            edits_table.ajax.reload()
        })
        $('#start_date').change(function(){
            let val = $(this).val()
            extraData.start_date = val
            edits_table.ajax.reload()
        })
        $('#end_date').change(function(){
            let val = $(this).val()
            extraData.end_date = val
            edits_table.ajax.reload()
        })
        $('#export_csv').click(function () {
            _token = '{{ csrf_token() }}'
            $.ajax({
                url:"{{ route('edits.edits_export_csv') }}",
                method : 'POST',
                data:{extraData,},
                success:function(response){
                    // const url = window.URL.createObjectURL(new Blob([response]));
                    const a = document.createElement('a');
                            a.href = response.path;
                            a.download = 'edit.csv';
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
