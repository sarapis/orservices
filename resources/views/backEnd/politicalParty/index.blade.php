@extends('backLayout.app')
@section('title')
Political Party
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Political Parties</h2>
				<div class="nav navbar-right panel_toolbox">
					<a href="{{route('parties.create')}}" class="btn btn-success">Create New Party</a>
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
				<table class="table table-striped table-bordered jambo_table bulk_action" id="tblparty">
					<thead>
						<tr>
							{{-- <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /> --}}
							</th>
							<th>ID</th>
							<th>name</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($parties as $party)
						<tr>
							<td>{{$party->id}}</td>
							<td>{{$party->name}}</td>
							<td>{{$party->user ? $party->user->first_name.' '.$party->user->last_name : ''}}</td>
							<td>{{$party->created_at}}</td>
							<td>
								<a href="{{route('parties.edit', $party->id)}}" >
									<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i>
								</a>
								{!! Form::open(['method'=>'DELETE', 'route' => ['parties.destroy', $party->id],
								'style' =>
								'display:inline']) !!}
								<button type="submit" style="border:none; background:none" data-original-title="Delete" onclick="return confirm('Are you sure to delete this party')" data-placement="top">
									<i class='fa fa-trash' style="color: #c3211c;"></i>
								</button>
								{!! Form::close() !!}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
		table = $('#tblparty').DataTable({
		'columnDefs': [{
			'targets': 0,
			'searchable':false,
			'orderable':false,
		}],
		'order': [0, 'desc'],
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
</script>
@endsection