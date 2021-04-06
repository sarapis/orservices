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
                <label for="inputPassword3" class="col-sm-3 control-label">End Date</label>
                <div class="col-sm-7">
                    <input type="date" name="end_date" id="end_date" class="form-control">
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
              <label for="inputPassword3" class="col-sm-3 control-label">Select User</label>
              <div class="col-sm-7">
                  {!! Form::select('user',$users,$id,['class' => 'form-control','id' => 'user','placeholder' => 'Select', 'data-live-search' => 'true','data-size' => '5']) !!}
              </div>
          </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations Tags</label>
                <div class="col-sm-7">
                    {!! Form::select('organization_tag',$organization_tagsArray,null,['class' => 'form-control selectpicker','id' => 'organization_tag','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
        </div>
    </div>

      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="edits_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Timestamp</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Data Type</th>
                    <th class="text-center">Log</th>
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
                { data: 'organization', name: 'organization' },
                { data: 'user', name: 'user' },
                { data: 'auditable_type', name: 'auditable_type' },
                { data: 'log', name: 'log' },
                { data: 'auditable_id', name: 'auditable_id' },
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
            ],
        });
        $('#organization').change(function(){
            let val = $(this).val()
            // extraData.organization = val
            // edits_table.ajax.reload()
            edits_table.search( val ).draw();
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
    });
</script>
@endsection
