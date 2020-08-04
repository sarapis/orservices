@extends('backLayout.app')
@section('title')
Sessions
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
{{-- <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css" /> --}}

<style type="text/css">
    .dropdown-menu.open {
        max-height: 156px;
        overflow: hidden;
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
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Sessions</h2>
        {!! Form::open(['route' => 'All_Sessions.all_session_export']) !!}
        <button type="submit" class="btn btn-primary pull-right">Export</button>
        <input type="hidden" name="organization" id="filterOrganization">
        <input type="hidden" name="organization_tag" id="filterOrganizationTags">
        <input type="hidden" name="start_date" id="filterStartDate">
        <input type="hidden" name="end_date" id="filterEndDate">
        <input type="hidden" name="id" value="all">
        {!! Form::close() !!}
        <div class="nav navbar-right panel_toolbox">
          {{-- <a href="{{route('religions.create')}}" class="btn btn-success">New religion</a> --}}
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong> {{ session()->get('error') }} </strong>
        </div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong> {{ session()->get('success') }} </strong>
        </div>
        @endif


        <div class="col-md-12" style="margin-bottom: 20px;">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Organization</label>
                    {!! Form::select('organization[]', $organizations,[], ['class' => 'form-control selectpicker','multiple' => 'multiple','data-live-search' => 'true','data-size' => '5','id' => 'organization']) !!}
                </div>
                <div class="form-group col-md-6">
                    <label>Organization Tags </label>
                    {!! Form::select('organization_tags[]', $organization_tagsArray,[], ['class' => 'form-control selectpicker','multiple' => 'multiple','data-live-search' => 'true','data-size' => '5','id' => 'organization_tags']) !!}
                </div>
                <div class="form-group col-md-6">
                    <label>Session Start</label>
                    <input type="text" name="start_date" id="start_date" class="form-control date-range-filter" data-date-format="dd-mm-yyyy" data-link-format="yyyy-mm-dd" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label>Session End</label>
                    <input type="text" name="end_date" id="end_date" class="form-control date-range-filter" data-date-format="dd-mm-yyyy" data-link-format="yyyy-mm-dd" readonly>
                </div>
                <div class="form-group col-md-6">
                    <button type="button" class="btn btn-danger" id="clear_filter">Clear Filter</button>
                </div>
            </div>
        </div>
        <table class="table table-striped jambo_table bulk_action" id="tblSessions">
          <thead>
            <tr>
              <th>Session ID</th>
              <th>User Name</th>
              <th>Organization</th>
              <th>Organization Tags</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Duration</th>
              <th>Status</th>
              <th>Notes</th>
              <th>Method</th>
              <th>Timestamp</th>
              <th>Disposition</th>
              <th>Records Edited</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="interactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLongTitle">Interaction Log
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </h3>
        </div>
        <div class="modal-body">
            {!! Form::open(['route' => 'All_Sessions.all_interaction_export']) !!}
            <input type="hidden" name="session_recordid" id="filterSessionId">
            <button type="submit" class="btn btn-primary pull-right">Export</button>
            {!! Form::close() !!}
            <table id="interactionTable" class="table table-striped jambo_table bulk_action">
                <thead>
                    <th>Method</th>
                    <th>Timestamp</th>
                    <th>Notes</th>
                    <th>Disposition</th>
                    <th>Records Edited</th>
                </thead>
                <tbody id="interactionTableBody"></tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> --}}
<script type="text/javascript">
    let table;
    let ajaxUrl = "{{ route('All_Sessions.getSessions') }}";
    let extraData = {}
    $('#start_date').datetimepicker({
        //   pickTime: false,
        //   format: "DD-MM-YYYY",
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
        });
    $('#end_date').datetimepicker({
        //   pickTime: false,
        //   format: "DD-MM-YYYY",
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
        });
    // $('.end_date').datepicker();
  $(document).ready(function(){


        table = $('#tblSessions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "post",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                data : function (d){
                        if (typeof extraData !== 'undefined') {

                            d.extraData = extraData;
                        }
                    },
                },
            columns: [
                { data: 'session_recordid', name: 'session_recordid' },
                { data: 'session_performed_by', name: 'session_performed_by' },
                { data: 'session_organization', name: 'session_organization' },
                { data: 'organization_tags', name: 'organization_tags' },
                { data: 'session_start_datetime', name: 'session_start_datetime' },
                { data: 'session_end_datetime', name: 'session_end_datetime' },
                { data: 'session_duration', name: 'session_duration' },
                { data: 'session_verification_status', name: 'session_verification_status' },
                { data: 'session_notes', name: 'session_notes' },
                { data: 'session_method', name: 'session_method' },
                { data: 'created_at', name: 'created_at' },
                { data: 'session_disposition', name: 'session_disposition' },
                { data: 'session_edits', name: 'session_edits' },
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
                    "orderable": false,
                    "class": "text-left",
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
                    "orderable": false,
                    "class": "text-left",
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
                {
                    "targets": 11,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 12,
                    "orderable": true,
                    "class": "text-left"
                },
            ],
        });

    });
        $('select#organization').on('change', function() {
            var selectedList = $(this).val() != null ? $(this).val() : [] ;
            search = selectedList.join('|')
            $('#filterOrganization').val(search)
            extraData.organization = search;
            table.ajax.reload();
            // dataTable.column(7).search(search ? search : '', true, false,false).draw();
        });
        $('#clear_filter').click(function(){
            $('select#organization').val('')
            $('select#organization_tags').val('')
            $('input#start_date').val('')
            $('input#end_date').val('')
            $('.filter-option-inner-inner').text('Nothing selected')
            extraData = {}
            table.ajax.reload();
        })
        $('select#organization_tags').on('change', function() {
            var selectedList = $(this).val() != null ? $(this).val() : [] ;
            search = selectedList.join('|')
            $('#filterOrganizationTags').val(search)
            extraData.organization_tags = search;
            table.ajax.reload();
            // dataTable.column(7).search(search ? search : '', true, false,false).draw();
        });
        $('input#start_date').on('change', function() {
            var selectedList = $(this).val();
            console.log(selectedList)
            $('#filterStartDate').val(selectedList)
            extraData.start_date = selectedList;
            if($('#end_date').val() != ''){
                table.ajax.reload();
            }
            // dataTable.column(7).search(search ? search : '', true, false,false).draw();
        });
        $('input#end_date').on('change', function() {
            var selectedList = $(this).val();
            $('#filterEndDate').val(selectedList)
            extraData.end_date = selectedList;
            if($('#start_date').val() != ''){
                table.ajax.reload();
            }
            // dataTable.column(7).search(search ? search : '', true, false,false).draw();
        });
        $(document).on('click','.interactionLog',function(){
            let id = $(this).data('id')

            $.ajax({
                url : "{{ route('All_Sessions.getInteraction') }}",
                method : "get",
                data : {id},
                success : function(resp){
                    $('#interactionTableBody').empty()
                    let data = resp.data
                    $('#filterSessionId').val(id)
                    $.each(data,function(i,v){
                        $('#interactionTableBody').append('<tr><td>'+v.interaction_method+'</td><td>'+v.interaction_timestamp+'</td><td>'+v.interaction_notes+'</td><td>'+v.interaction_disposition+'</td><td>'+v.interaction_records_edited+'</td></tr>')
                    })
                    $('#interactionTable').DataTable();
                },
                error : function(error){
                    console.log(error)
                }
            })
        })
</script>
@endsection
