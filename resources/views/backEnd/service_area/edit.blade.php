@extends('backLayout.app')
@section('title')
edit service area
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit Service Area</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($service_area,['route' => ['service_areas.update',$service_area->id], 'class' => 'form-horizontal','enctype' => 'multipart/form-data','method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('services') ? 'has-error' : ''}}">
                        {!! Form::label('services', 'Services', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('services', $services,null, ['class' => 'form-control selectpicker','id' => 'services','placeholder' => 'Select Services']) !!}
                            {!! $errors->first('services', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('extent') ? 'has-error' : ''}}">
                        {!! Form::label('extent', 'Extent', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('extent', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('extent', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('extent_type') ? 'has-error' : ''}}">
                        {!! Form::label('extent_type', 'Extent Type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('extent_type', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('extent_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('uri') ? 'has-error' : ''}}">
                        {!! Form::label('uri', 'URI', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('uri', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('uri', '<p class="help-block">:message</p>') !!}
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
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
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
