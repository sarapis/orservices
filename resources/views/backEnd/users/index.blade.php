@extends('backLayout.app')
@section('title')
Users
@stop

@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Users</h2>
        <div class="nav navbar-right panel_toolbox">
          @if (Sentinel::getUser()->hasAccess(['user.create']))
          <a href="{{route('user.create')}}" class="btn btn-success">New User</a>
        @endif
        </div>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content">

        <table class="table table-striped jambo_table bulk_action" id="tblUsers">
            <thead>
                <tr>
                    <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>E-mail</th>
                    <th>user Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ Form::checkbox('sel', $user->id, null, ['class' => ''])}}</td>
                        <td>{{$user->id}}</td>
                        <td><a href="{{route('user.show', $user->id)}}">{{$user->first_name}}</a></td>
                        <td><a href="{{route('user.show', $user->id)}}">{{$user->last_name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td> <a href="{{route('user.index',['type='.$user->roles()->first()->name])}}">{{empty($user->roles()->first())?"":$user->roles()->first()->name}}</a>  </td>
                        <td>{{$user->created_at}}</td>
                        <td>
                            @if (Sentinel::getUser()->hasAccess(['user.show']))
                            <a href="{{route('user.show', $user->id)}}" class="btn btn-info btn-xs"><i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"></i></a>
                            @endif
                            @if (Sentinel::getUser()->hasAccess(['user.edit']))
                            <a href="{{route('user.edit', $user->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                            @endif
                            @if (Sentinel::getUser()->hasAccess(['user.permissions']))
                            <a href="{{route('user.permissions', $user->id)}}" class="btn btn-success btn-xs"><i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="" data-original-title="Permission"></i> </a>
                            @endif

                            @if(sizeof($user->activations) == 0)
                            @if (Sentinel::getUser()->hasAccess(['user.activate']))
                            <a href="{{route('user.activate', $user->id)}}" class="btn btn-primary btn-xs"> <i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"></i> </a>
                            @endif
                            @else
                            @if (Sentinel::getUser()->hasAccess(['user.deactivate']))
                             <a href="{{route('user.deactivate', $user->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"></i></a>
                             @endif
                            @endif

                            @if (Sentinel::getUser()->hasAccess(['user.destroy']))
                            {!! Form::open(['method'=>'DELETE', 'route' => ['user.destroy', $user->id], 'style' => 'display:inline']) !!}
                            {{Form::button('<i class="fa fa-lock"></i>', array('type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm'))}}
                            {!! Form::close() !!}
                            @endif
                            
                            
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (Sentinel::getUser()->hasAccess(['user.destroy']))
        <button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
        @endif
        @if (Sentinel::getUser()->hasAccess(['user.activate']))
        <button id="activate_all" class='btn btn-primary btn-xs'>Activate Selected</button>
        @endif
        @if (Sentinel::getUser()->hasAccess(['user.deactivate']))
        <button id="deactivate_all" class='btn btn-warning btn-xs'>Deactivate Selected</button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        table = $('#tblUsers').DataTable({
          'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
          }],
          'order': [1, 'asc'],
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
  $("input#delete-confirm").on("click", function(){
        return confirm("Are you sure to delete this user");
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
                    url : "{{action('UserController@ajax_all')}}",
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
                    url : "{{action('UserController@ajax_all')}}",
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
                    url : "{{action('UserController@ajax_all')}}",
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
