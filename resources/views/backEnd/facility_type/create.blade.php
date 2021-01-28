@extends('backLayout.app')
@section('title')
create location type
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Create new location type</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'FacilityTypes.store', 'class' => 'form-horizontal']) !!}
                    <div class="form-group {{ $errors->has('facility_type') ? 'has-error' : ''}}">
                        {!! Form::label('facility_type', 'Location type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('facility_type', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('facility_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('notes') ? 'has-error' : ''}}">
                        {!! Form::label('notes', 'Notes', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('FacilityTypes.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
