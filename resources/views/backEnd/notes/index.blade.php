@extends('backLayout.app')
@section('title')
    Notes
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    tr.modified {
        background-color: red !important;
    }

    tr.modified>td {
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

    .form-group {
        float: left;
        width: 100%
    }

</style>
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Interactions </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations</label>
                            <div class="col-sm-7">
                                {!! Form::select('organization', $organizations, $organization_id, ['class' => 'form-control', 'id' => 'organization', 'placeholder' => 'Select ', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Select Method</label>
                            <div class="col-sm-7">
                                <select class="form-control selectpicker" data-live-search="true" id="session_method"
                                    name="session_method" data-size="5">
                                    <option value="">Select </option>
                                    @foreach ($method_list as $key => $method)
                                        <option value="{{ $key }}">{{ $method }}</option>
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
                                    @foreach ($disposition_list as $key => $disposition)
                                        <option value="{{ $key }}">{{ $disposition }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Select User</label>
                            <div class="col-sm-7">
                                {!! Form::select('user', $users, $id, ['class' => 'form-control', 'id' => 'user', 'placeholder' => 'Select ', 'data-live-search' => 'true', 'data-size' => '5']) !!}
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
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">Select Service Status</label>
                          <div class="col-sm-7">
                               {!! Form::select('service_status',$service_statuses,null,['class' => 'form-control','id' => 'service_status', 'data-live-search' => 'true','data-size' => '5','placeholder' => 'select']) !!}
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">Select Service</label>
                          <div class="col-sm-7">
                               {!! Form::select('session_service',$services,null,['class' => 'form-control','id' => 'session_service', 'data-live-search' => 'true','data-size' => '5','placeholder' => 'Select']) !!}
                          </div>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Organization Status</label>
                            <div class="col-sm-7">
                                {!! Form::select('organization_status', $organization_status, null, ['class' => 'form-control', 'id' => 'organization_status', 'placeholder' => 'Select ', 'data-live-search' => 'true', 'data-size' => '5']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="x_content" style="overflow: scroll;">

                    <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
                    <table id="notes_table" class="display table-striped jambo_table table-bordered " cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Timestamp</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Organization</th>
                                <th class="text-center">Organization Status</th>
                                <th class="text-center">Services</th>
                                <th class="text-center">Service Status</th>
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
        $('#session_service').selectpicker()
        let notes_table;
        let extraData = {};
        let id = "{{ $id }}"
        let organization_id = "{{ $organization_id }}"
        let ajaxUrl
        ajaxUrl = "{{ route('notes.get_session_record') }}";
        if (id != 0) {
            extraData.user = id
        } else if (organization_id != 0) {
            extraData.organization = organization_id
        }
        $(document).ready(function() {
            notes_table = $('#notes_table').DataTable({
                'order': [1, 'desc'],
                processing: true,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    method: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data: function(d) {
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            d.extraData = extraData;
                        }
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'session_organization',
                        name: 'session_organization'
                    },
                    {
                        data: 'organization_status',
                        name: 'organization_status'
                    },
                    {
                        data: 'session_service',
                        name: 'session_service'
                    },
                    {
                        data: 'service_status',
                        name: 'service_status'
                    },
                    {
                        data: 'session_method',
                        name: 'session_method'
                    },
                    {
                        data: 'session_disposition',
                        name: 'session_disposition'
                    },
                    {
                        data: 'session_notes',
                        name: 'session_notes'
                    },
                ],
                columnDefs: [{
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
                        "targets": 8,
                        "orderable": true,
                        "class": "text-left"
                    },
                ],
            });
            $('#organization').change(function() {
                let val = $(this).val()
                extraData.organization = val
                notes_table.ajax.reload()
            })
            $('#session_service').change(function() {
                let val = $(this).val()
                extraData.session_service = val
                notes_table.ajax.reload()
            })
            $('#organization_status').change(function() {
                let val = $(this).val()
                extraData.organization_status = val
                notes_table.ajax.reload()
            })
            $('#service_status').change(function() {
                let val = $(this).val()
                extraData.service_status = val
                notes_table.ajax.reload()
            })
            $('#session_method').change(function() {
                let val = $(this).val()
                extraData.session_method = val
                notes_table.ajax.reload()
            })
            $('#session_disposition').change(function() {
                let val = $(this).val()
                extraData.session_disposition = val
                notes_table.ajax.reload()
            })
            $('#user').change(function() {
                let val = $(this).val()
                extraData.user = val
                notes_table.ajax.reload()
            })
            $('#start_date').change(function() {
                let val = $(this).val()
                extraData.start_date = val
                notes_table.ajax.reload()
            })
            $('#end_date').change(function() {
                let val = $(this).val()
                extraData.end_date = val
                notes_table.ajax.reload()
            })
        });
    </script>
@endsection
