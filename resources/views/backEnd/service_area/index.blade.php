@extends('backLayout.app')
@section('title')
Service Area
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
        <h2>Service Areas</h2>
            <div class="nav navbar-right panel_toolbox">
                <a href="{{route('service_areas.create')}}" class="btn btn-primary">Add New Service Area</a>
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll;">
        <table id="service_area_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Name</th>
                    {{-- <th class="text-center">Services</th> --}}
                    <th class="text-center">Extent</th>
                    <th class="text-center">Extent Type</th>
                    <th class="text-center">URI</th>
                    <th class="text-center">Description</th>
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script> --}}
<script type="text/javascript">
    let service_area_table;
    let extraData = {};
    let ajaxUrl
    ajaxUrl = "{{ route('service_areas.index') }}";
  $(document).ready(function(){
    service_area_table = $('#service_area_table').DataTable({
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
                { data: 'name', name: 'name' },
                // { data: 'services', name: 'services' },
                { data: 'extent', name: 'extent' },
                { data: 'extent_type', name: 'extent_type' },
                { data: 'uri', name: 'uri' },
                { data: 'description', name: 'description' },
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
                // {
                //     "targets": 5,
                //     "orderable": true,
                //     "class": "text-left"
                // },
                {
                    "targets": 6,
                    "orderable": true,
                    "class": "text-left"
                },
                // {
                //     "targets": 7,
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
    });
</script>
@endsection
