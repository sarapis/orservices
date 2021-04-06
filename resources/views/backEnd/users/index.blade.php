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
				{{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
				<a href="{{route('user.create')}}" class="btn btn-success">New User</a>
				{{-- @endif --}}
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<table class="table table-bordered jambo_table bulk_action table-responsive" id="tblUsers">
					<thead>
						<tr>
							<th><input name="select_all" value="1" id="example-select-all" type="checkbox" /> </th>
							<th>ID</th>
							<th class="text-center">First name</th>
							<th class="text-center">Last name</th>
							<th class="text-center">E-mail</th>
							<th class="text-center">Cell Phone</th>
							<th class="text-center">User Organizations</th>
							<th class="text-center">User Role</th>
							<th class="text-center">Edits </th>
							<th class="text-center">Notes </th>
							<th class="text-center">Created At</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<td>{{ Form::checkbox('sel', $user->id, null, ['class' => ''])}}</td>
							<td>{{$user->id}}</td>
							<td class="text-center"><a href="{{route('user.show', $user->id)}}">{{$user->first_name}}</a></td>
							<td class="text-center"><a href="{{route('user.show', $user->id)}}">{{$user->last_name}}</a></td>
							<td class="text-center">{{$user->email}}</td>
							<td class="text-center">{{$user->phone_number}}</td>
							@if($user->organizations)
							<td class="text-center">
							@foreach($user->organizations as $value)
							<a href="/organizations/{{$value->organization_recordid}}"  class="panel-link" style="color: blue;">{{$value->organization_name}} </a><br>
							@endforeach
							</td>
							@else
							<td class="text-center"></td>
							@endif
							<td class="text-center"> <a href="{{route('user.index')}}">{{empty($user->roles ) ? " ": $user->roles->name}}</a></td>
                            <td><a href="{{route('edits.userEdits',$user->id)}}">{{ count($user->edits) }}</a></td>
							<td class="text-center"><a href="{{route('notes.userNotes',$user->id)}}">{{$user->interations ? count($user->interations) : 0 }}</a></td>
							<td class="text-center">{{$user->created_at}}</td>
							<td class="text-center">
								{{-- @if ($authUser->roles && $authUser->roles->permissions && in_array('user.show',json_decode($authUser->roles->permissions)))
								<a href="{{route('user.show', $user->id)}}"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" ></i></a>
								@endif --}}
								@if ($authUser->roles && $authUser->roles->permissions && in_array('user.edit',json_decode($authUser->roles->permissions)))
								<a href="{{route('user.edit', $user->id)}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i></a>
								@endif
								{{-- @if ($authUser->permissions && in_array('user.permissions',json_decode($authUser->permissions)))
								<a href="{{route('user.permissions', $user->id)}}"><i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="" data-original-title="Permission"  style="color: #ffa500;"></i> </a>
								@endif --}}
								{{-- @if ($authUser->roles && $authUser->roles->permissions && in_array('user.activate',json_decode($authUser->roles->permissions)) &&
								$user->status == 0)
								<a href="{{route('user.activate', $user->id)}}"><i class="fa fa-ban" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deactivate"  style="color: #9a9a9a;"></i></a>
								@else
								@if ($authUser->roles && $authUser->roles->permissions && in_array('user.deactivate',json_decode($authUser->roles->permissions)))
								<a href="{{route('user.deactivate', $user->id)}}"> <i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activate"  style="color: #4caf50;"></i> </a>
								@endif
								@endif --}}

								@if ($authUser->roles && $authUser->roles->permissions && in_array('user.destroy',json_decode($authUser->roles->permissions)))
								{!! Form::open(['method'=>'DELETE', 'route' => ['user.destroy', $user->id], 'style' =>
								'display:inline']) !!}
								{{Form::button('<i class="fa fa-trash" style="color: #c3211c;"></i>', array('type' => 'submit', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm'))}}
								{!! Form::close() !!}
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@if ($authUser->roles && $authUser->roles->permissions && in_array('user.destroy',json_decode($authUser->roles->permissions)))
				<button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
				@endif
				@if ($authUser->roles && $authUser->roles->permissions && in_array('user.activate',json_decode($authUser->roles->permissions)))
				<button id="activate_all" class='btn btn-primary btn-xs'>Activate Selected</button>
				@endif
				@if ($authUser->roles && $authUser->roles->permissions && in_array('user.deactivate',json_decode($authUser->roles->permissions)))
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
	$("button#delete-confirm").on("click", function(){
			return confirm("Are you sure to delete this user");
		});
	// start Delete All function
	$("#delete_all").click(function(event){
			event.preventDefault();
			if (confirm("Are you sure to Delete Selected?")) {
				var value = get_Selected_id();
				if (value!='') {

					$.ajax({
						type: "POST",
						// cache: false,
						url : "{{route('user.ajax_all')}}",
						data: {all_id:value,action:'delete'},
							success: function(data) {
							location.reload()
							},
							error:function(err){
							console.log(err)
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
                    url : "{{route('user.ajax_all')}}",
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
                    url : "{{route('user.ajax_all')}}",
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
