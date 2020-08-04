@extends('backLayout.app')
@section('title')
Edit organization type : {{$organizationType->organization_type}}
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit religion</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($organizationType,['route' => array('organizationTypes.update',$organizationType->id), 'class' => 'form-horizontal','method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('organization_type') ? 'has-error' : ''}}">
                        {!! Form::label('organization_type', 'Organization type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('organization_type', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('organization_type', '<p class="help-block">:message</p>') !!}
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
                            {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
                        </div>
                        <a href="{{route('religions.index')}}" class="btn btn-default">Back</a>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection