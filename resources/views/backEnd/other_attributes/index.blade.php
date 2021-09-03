@extends('backLayout.app')
@section('title')
Taxonomy
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Other Attributes</h2>
        <div class="nav navbar-right panel_toolbox">
            {{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
            <a href="{{route('other_attributes.create')}}" class="btn btn-success">Add Atrribute</a>
            {{-- @endif --}}
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll; ">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="other_attributes_table" class="display table-striped  jambo_table table-bordered table-responsive" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Link Id</th>
                    <th>Link Type</th>
                    <th>Taxonomy Term Id</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              {{-- @foreach($other_attributes as $key => $value)
                <tr>
                  <td class="text-center">{{$key + 1}}</td>
                  <td>{{ $value->link_id }}</td>
                  <td>{{ $value->link_type }}</td>
                  <td>{{ $value->taxonomy_term_id }}</td>
                  <td>
                    <a href="{{route('other_attributes.edit', $value->id)}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>
                    {!! Form::open(['method'=>'DELETE', 'route' => ['other_attributes.destroy', $value->id], 'style' =>
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
<script>
    let other_attributes_table;
    let ajaxUrl = "{{ route('other_attributes.index') }}";
  $(document).ready(function(){
    other_attributes_table = $('#other_attributes_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "get",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'link_id', name: 'link_id' },
                { data: 'link_type', name: 'link_type' },
                { data: 'taxonomy_term_id', name: 'taxonomy_term_id' },
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
            ],
        });
    });
</script>
@endsection
