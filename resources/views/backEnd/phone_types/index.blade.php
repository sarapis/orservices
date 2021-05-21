@extends('backLayout.app')
@section('title')
Phone Type
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Phone Type</h2>
				<div class="nav navbar-right panel_toolbox">
				{{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
				<a href="{{ url('phone_types/create') }}" class="btn btn-success">Add Phone Type</a>
				{{-- @endif --}}
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="">
                    <table class="table table-striped table-bordered jambo_table bulk_action" id="tblphonetypes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phone_types as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->order }}</td>
                                <td style="width:30%; text-center">
                                    <a href="{{ url('phone_types/' . $item->id . '/edit') }}">
                                        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i>
                                    </a>
                                    {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['phone_types', $item->id],
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
            $('#tblphonetypes').DataTable();
        });
     $(".deleteconfirm").on("click", function(){
            return confirm("Are you sure to delete this Role");
        });
</script>
@endsection
