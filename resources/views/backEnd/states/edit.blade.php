@extends('backLayout.app')
@section('title')
Edit state : {{$state->state}}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit state</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($state,['route' => array('states.update',$state->id), 'class' => 'form-horizontal','method' => 'PUT','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                        {!! Form::label('state', 'State', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('state', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('states.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
