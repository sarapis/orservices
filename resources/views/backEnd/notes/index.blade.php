@extends('backLayout.app')
@section('title')
Notes
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
        <h2>Notes </h2>
        <div class="clearfix"></div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations</label>
                <div class="col-sm-7">
                    {!! Form::select('organization',$organizations,null,['class' => 'form-control','id' => 'organization','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Method</label>
                <div class="col-sm-7">
                    <select class="form-control selectpicker" data-live-search="true" id="session_method" name="session_method" data-size="5">
                        <option value="">Select </option>
                        @foreach($method_list as $key => $method)
                        <option value="{{$method}}">{{$method}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Disposition</label>
                <div class="col-sm-7">
                    <select class="form-control selectpicker" data-live-search="true" id="session_disposition"
                        name="session_disposition" data-size="5">
                        <option value="">Select </option>
                        @foreach($disposition_list as $key => $disposition)
                        <option value="{{$disposition}}">{{$disposition}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select User</label>
                <div class="col-sm-7">
                    {!! Form::select('user',$users,$id,['class' => 'form-control','id' => 'user','placeholder' => 'Select ', 'data-live-search' => 'true','data-size' => '5']) !!}
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

      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="notes_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Timestamp</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">Method</th>
                    <th class="text-center">Disposition</th>
                    <th class="text-center">Notes</th>
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
    let notes_table;
    let extraData = {};
    let id = "{{ $id }}"
    let ajaxUrl
    if(id != 0){
         ajaxUrl = "{{ route('notes.userNotes',$id) }}";
         extraData.user = id
    }else{
         ajaxUrl = "{{ route('notes.index') }}";
    }
  $(document).ready(function(){
    notes_table = $('#notes_table').DataTable({
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
                { data: 'created_at', name: 'created_at' },
                { data: 'user', name: 'user' },
                { data: 'session_organization', name: 'session_organization' },
                { data: 'session_method', name: 'session_method' },
                { data: 'session_disposition', name: 'session_disposition' },
                { data: 'session_notes', name: 'session_notes' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": false,
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
            ],
        });
        $('#organization').change(function(){
            let val = $(this).val()
            extraData.organization = val
            notes_table.ajax.reload()
        })
        $('#session_method').change(function(){
            let val = $(this).val()
            extraData.session_method = val
            notes_table.ajax.reload()
        })
        $('#session_disposition').change(function(){
            let val = $(this).val()
            extraData.session_disposition = val
            notes_table.ajax.reload()
        })
        $('#user').change(function(){
            let val = $(this).val()
            extraData.user = val
            notes_table.ajax.reload()
        })
        $('#start_date').change(function(){
            let val = $(this).val()
            extraData.start_date = val
            notes_table.ajax.reload()
        })
        $('#end_date').change(function(){
            let val = $(this).val()
            extraData.end_date = val
            notes_table.ajax.reload()
        })
    });
</script>
@endsection
