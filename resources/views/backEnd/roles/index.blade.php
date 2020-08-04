@extends('backLayout.app')
@section('title')
User role
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>User role</h2>
				<div class="nav navbar-right panel_toolbox">
				{{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
				<a href="{{ url('role/create') }}" class="btn btn-success">New role</a>
				{{-- @endif --}}
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="">
                    <table class="table table-striped table-bordered jambo_table bulk_action" id="tblroles">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Slug</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><a href="{{ url('role', $item->id) }}">{{ $item->slug }}</a></td>
                                <td>{{ $item->name }}</td>
                                <td style="width:30%; text-center">
                                    <a href="{{route('user.index',['type='.$item->name])}}">
                                        <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="View" ></i>
                                    </a>
                                    <a href="{{ url('role/' . $item->id . '/edit') }}">
                                        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i>
                                    </a>
                                    <a href="{{ url('role/' . $item->id . '/permissions') }}" >
                                        <i class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="" data-original-title="Permission"  style="color: #ffa500;"></i>
                                    </a>
                                    {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['role', $item->id],
                                    'style' => 'display:inline'
                                    ]) !!}
                                        {{Form::button('<i class="fa fa-trash" style="color: #c3211c;"></i>', array('type' => 'submit', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm'))}}
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
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
            $('#tblroles').DataTable({
                columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                    },
                ],
                order: [[0, "asc"]],
            });
        });
     $(".deleteconfirm").on("click", function(){
            return confirm("Are you sure to delete this Role");
        });
</script>
@endsection