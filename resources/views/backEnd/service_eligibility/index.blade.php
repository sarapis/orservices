@extends('backLayout.app')
@section('title')
Service Eligibility
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Service Eligibility</h2>
				<div class="nav navbar-right panel_toolbox">
				{{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
				<a href="{{ url('service_eligibilities/create') }}" class="btn btn-success">Add Service Eligibility</a>
				{{-- @endif --}}
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="">
                    <table class="table table-striped table-bordered jambo_table bulk_action" id="tblserviceeligibility">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Term</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service_eligibilities as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->term }}</td>
                                <td style="width:30%; text-center">
                                    <a href="{{ url('service_eligibilities/' . $item->id . '/edit') }}">
                                        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit" style="color: #4caf50;"></i>
                                    </a>
                                    {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['service_eligibilities', $item->id],
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
            $('#tblserviceeligibility').DataTable();
        });
     $(".deleteconfirm").on("click", function(){
            return confirm("Are you sure to delete this term");
        });
</script>
@endsection
