@extends('backLayout.app')
@section('title')
Details
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
        <h2>Details</h2>
        <div class="nav navbar-right panel_toolbox">
            {{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
            <a href="{{route('XDetails.create')}}" class="btn btn-success">Add Detail</a>
            {{-- @endif --}}
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
          <div class="row">
              <div class="form-group">
                  <label class="col-md-4 text-right">Type</label>
                  <div class="col-sm-4" style="margin-bottom: 20px">
                      {!! Form::select('detail_type',$detail_types,null,['class' => 'form-control selectpicker','id' => 'detail_type','multiple' => true,'data-live-search' => 'true','data-size' => 5]) !!}
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="form-group">
                  <label class="col-md-4 text-right">Parent</label>
                  <div class="col-sm-4" style="margin-bottom: 20px">
                      {!! Form::select('parent',$parents,null,['class' => 'form-control selectpicker','id' => 'parent','multiple' => true,'data-live-search' => 'true','data-size' => 5]) !!}
                  </div>
              </div>
          </div>
        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="XDetails_table" class="display table-striped  table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Value</th>
                    <th>Parent</th>
                    <th>Detail Type </th>
                    <th>Description</th>
                    <th>Languages</th>
                    <th>Notes</th>
                    {{-- <th>Service Id</th>
                    <th>Organization Id</th>
                    <th>Location Id</th> --}}
                    {{-- <th>Phone Id</th> --}}
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              {{-- @foreach($xdetails as $key => $value)
                <tr>
                  <td class="text-center">{{$key + 1}}</td>
                  <td>{{ $value->value }}</td>
                  <td>{{ $value->type }}</td>
                  <td>{{ $value->description }}</td>
                  <td>{{ $value->service_id }}</td>
                  <td>{{ $value->organization_id }}</td>
                  <td>{{ $value->location_id }}</td>
                  <td>{{ $value->phone_id }}</td>
                  <td>
                    <a href="{{route('XDetails.edit', $value->id)}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>
                    {!! Form::open(['method'=>'DELETE', 'route' => ['XDetails.destroy', $value->id], 'style' =>
                    'display:inline']) !!}
                    {{Form::button('<i class="fa fa-trash" style="color: #c3211c;"></i>', array('type' => 'submit', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm'))}}
                    {!! Form::close() !!}
                  </td>
                </tr>
              @endforeach --}}
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
    let XDetails_table;
    let extraData = {};
    let ajaxUrl = "{{ route('XDetails.index') }}";
  $(document).ready(function(){
    XDetails_table = $('#XDetails_table').DataTable({
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
                { data: 'detail_value', name: 'detail_value' },
                { data: 'parent', name: 'parent' },
                { data: 'detail_type', name: 'detail_type' },
                { data: 'detail_description', name: 'detail_description' },
                { data: 'language', name: 'language' },
                { data: 'notes', name: 'notes' },
                // { data: 'detail_services', name: 'detail_services' },
                // { data: 'detail_organizations', name: 'detail_organizations' },
                // { data: 'detail_locations', name: 'detail_locations' },
                // { data: 'phone_id', name: 'phone_id' },
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
    $('#detail_type').change(function(){
        let val = $(this).val()
        extraData.detail_type = val
        XDetails_table.ajax.reload()
    })
    $('#parent').change(function(){
        let val = $(this).val()
        extraData.parent = val
        XDetails_table.ajax.reload()
    })
    });
</script>
@endsection
