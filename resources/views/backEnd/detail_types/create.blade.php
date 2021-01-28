@extends('backLayout.app')
@section('title')
Create Detail Type
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Create Detail Type</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['url' => 'detail_types', 'class' => 'form-horizontal']) !!}
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                        {!! Form::label('type', 'Detail Type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('type', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('detail_types.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>
@endsection
