@extends('backLayout.app')
@section('title')
create service area
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Create new service area</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'service_areas.store', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Service Area', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('services') ? 'has-error' : ''}}">
                        {!! Form::label('services', 'Services', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('services', $services,null, ['class' => 'form-control selectpicker','id' => 'services','placeholder' => 'Select Services']) !!}
                            {!! $errors->first('services', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description', 'Description', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('service_areas.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
    $('#services').selectpicker()
</script>

@endsection