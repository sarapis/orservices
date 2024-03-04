@extends('backLayout.app')
@section('title')
Taxonomies
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
        <h2>Taxonomies</h2>
        <div class="nav navbar-right panel_toolbox">
            {{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
            <a href="{{route('taxonomy_types.export')}}" class="btn btn-info">CSV Download</a>
            <a href="{{route('taxonomy_types.create')}}" class="btn btn-success">Add Taxonomy</a>
            {{-- @endif --}}
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="taxonomy_type_table" class="display table-striped  table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Name</th>
                    <th>Order</th>
                    <th>Type</th>
                    <th>URI</th>
                    <th>Version</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
       <!--  -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
    // $('#detail_type').SumoSelect({ placeholder: 'Nothing selected' });
    $("#detail_type").selectpicker();
    let taxonomy_type_table;
    let extraData = {};
    let ajaxUrl = "{{ route('taxonomy_types.index') }}";
  $(document).ready(function(){
    taxonomy_type_table = $('#taxonomy_type_table').DataTable({
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
                { data: 'order', name: 'order' },
                { data: 'type', name: 'type' },
                { data: 'reference_url', name: 'reference_url' },
                { data: 'version', name: 'version' },
                { data: 'notes', name: 'notes' },
                { data: 'action', name: 'action' },
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
                {
                    "targets": 7,
                    "orderable": true,
                    "class": "text-left"
                },
            ],
        });
    $('#detail_type').change(function(){
        let val = $(this).val()
        extraData.detail_type = val
        taxonomy_type_table.ajax.reload()
    })
    });
</script>
@endsection
