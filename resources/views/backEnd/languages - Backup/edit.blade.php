@extends('backLayout.app')
@section('title')
Edit language : {{$language->language_name}}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit language</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($language,['route' => array('languages.update',$language->id), 'class' => 'form-horizontal','method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('language_name') ? 'has-error' : ''}}">
                        {!! Form::label('language_name', 'Language name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('language_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('language_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
                        {!! Form::label('note', 'Note', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('languages.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection