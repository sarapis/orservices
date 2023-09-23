@extends('backLayout.app')
@section('title')
Edit Code System : {{$codeSystem->name}}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit Code System</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($codeSystem,['route' => array('code_systems.update',$codeSystem->id), 'class' => 'form-horizontal','method' => 'PUT','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('url') ? 'has-error' : ''}}">
                        {!! Form::label('uri', 'URI', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('oid') ? 'has-error' : ''}}">
                        {!! Form::label('oid', 'OID', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('oid', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('oid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('versions') ? 'has-error' : ''}}">
                        {!! Form::label('versions', 'Versions', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('versions', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('versions', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('code_systems.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
