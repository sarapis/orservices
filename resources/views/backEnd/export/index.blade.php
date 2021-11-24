@extends('backLayout.app')
@section('title')
Export Configurations
@stop
<link href="{{ URL::asset('/css/switchery.min.css') }}" rel="stylesheet" />
@section('content')
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

    <style>
        .form-group {
            float: left;
            width: 100%
        }

    </style>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Export Configurations</h2>
                    <div class="nav navbar-right panel_toolbox">
                        <a href="{{ route('export.create') }}" class="btn btn-success">Add Export Configuration</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="form-group">
                        <table class="table table-responsive table-bordered table-striped " id="exportTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Filter</th>
                                    <th>Type</th>
                                    <th>Endpoint</th>
                                    <th>Key</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Export History</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="form-group">
                        <table class="table table-responsive table-bordered table-striped " id="exportHistoryTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Time Stamp</th>
                                    <th>Configuration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('/js/switchery.min.js ') }}"></script>
    <script type="text/javascript">
        let exportTable;
        let exportHistoryTable;
        let extraData = {};
        let ajaxUrl = "{{ route('export.getExportConfiguration') }}";
        let ajaxUrl2 = "{{ route('export.getExportHistory') }}";
        $(document).ready(function() {
            exportTable = $('#exportTable').DataTable({
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
                    // complete: function(data) {
                    //     var elem = document.getElementsByClassName("switch");
                    //     for (let index = 0; index < elem.length; index++) {
                    //         var init = new Switchery(elem[index]);
                    //     }
                    // }
                },

                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'organization_tags',
                        name: 'organization_tags'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'endpoint',
                        name: 'endpoint'
                    },
                    {
                        data: 'key',
                        name: 'key'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                columnDefs: [{
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
                    
                ],

            });
            exportHistoryTable = $('#exportHistoryTable').DataTable({
                "order": [[0,'desc']],
                ajax: {
                    url: ajaxUrl2,
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

                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_At'
                    },
                    // {
                    //     data: 'import_type',
                    //     name: 'import_type'
                    // },
                    {
                        data: 'configuration',
                        name: 'configuration'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                ],
                columnDefs: [{
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
                    // {
                    //     "targets": 4,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 8,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                ],
            });
            $(document).on('change','.switch',function(){
                let checked = $(this).is(':checked')
                let id = $(this).data('id')
                $.ajax({
                    url: "{{ route('export.changeAutoExport') }}",
                    method:'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data:{id,checked},
                    success:function(resp){
                        exportTable.ajax.reload()
                    },
                    error:function(xhr, status, error){
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.message)
                        exportTable.ajax.reload()
                    }
                })
            });
        });

    </script>

@endsection
