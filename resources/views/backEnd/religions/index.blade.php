@extends('backLayout.app')
@section('title')
Religions
@stop

@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Religions</h2>
        <div class="nav navbar-right panel_toolbox">
          <a href="{{route('religions.create')}}" class="btn btn-success">New religion</a>

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
        <table class="table table-striped jambo_table bulk_action" id="tblReligions">
          <thead>
            <tr>
              {{-- <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /> --}}
              </th>
              <th>ID</th>
              <th>name</th>
              <th>type</th>
              <th>parent</th>
              <th>organizations</th>
              <th>icon</th>
              <th>Note</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($religions as $religion)
            <tr>
              {{-- <td>{{ Form::checkbox('sel', $religion->id, null, ['class' => ''])}}</td> --}}
              <td>{{$religion->id}}</td>
              <td>{{$religion->name}}</td>
              <td>{{ $religion->type == 'faith_tradition' ? 'Faith tradition' : $religion->type}}</td>
              <td>{{$religion->parent}}</td>
              <td>{{$religion->organizations}}</td>
              <td>{{$religion->icon}}</td>
              <td>{{$religion->notes}}</td>
              <td>{{$religion->created_at}}</td>
              <td>
                {{-- <a href="{{route('religions.show', $religion->id)}}" class="btn btn-info btn-xs"><i
                  class="fa fa-search" data-toggle="tooltip" data-placement="top" title=""
                  data-original-title="View"></i></a> --}}
                <a href="{{route('religions.edit', $religion->id)}}" class="btn btn-primary btn-xs"><i
                    class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>


                {!! Form::open(['method'=>'DELETE', 'route' => ['religions.destroy', $religion->id],
                'style' =>
                'display:inline']) !!}
                <button type="submit" class="btn btn-danger btn-xs" data-original-title="Delete"
                  onclick="return confirm('Are you sure to delete this religion')" data-placement="top"><i
                    class='fa fa-trash'></i></button>
                {!! Form::close() !!}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{-- @if (Sentinel::getUser()->hasAccess(['religions.destroy']))
        <button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
        @endif --}}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
        table = $('#tblReligions').DataTable({
          'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
          }],
          'order': [0, 'asc'],
          dom: "Blfrtip",
            buttons: [
            {
              extend: "copy",
              className: "btn-sm"
            },
            {
              extend: "csv",
              className: "btn-sm"
            },
            {
              extend: "excel",
              className: "btn-sm"
            },
            {
              extend: "pdfHtml5",
              className: "btn-sm"
            },
            {
              extend: "print",
              className: "btn-sm"
            },
            ],
            responsive: true
        });
    });
      // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
  $("#delete-confirm").on("click", function(){
        return confirm("Are you sure to delete this religion");
    });
  // start Delete All function
  $("#delete_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Delete Selected?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'delete'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
   });
  //End Delete All Function
  //Start Deactivate all Function
    $("#deactivate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Deactivate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'deactivate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
    });
    //End Deactivate Function
      //Start Activate all Function
    $("#activate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Activate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'activate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any checkbox before");}
        }
        return false;
    });
    //End Activate Function




   function get_Selected_id() {
    var searchIDs = $("input[name=sel]:checked").map(function(){
      return $(this).val();
    }).get();
    return searchIDs;
   }
</script>
@endsection
