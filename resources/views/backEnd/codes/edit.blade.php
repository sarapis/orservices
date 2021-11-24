@extends('backLayout.app')
@section('title')
edit code {{ $code->code }}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit code</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($code,['route' => ['codes.update',$code->id], 'class' => 'form-horizontal','method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('uid') ? 'has-error' : ''}}">
                        {!! Form::label('uid', 'UID', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">

                            {!! Form::text('id', null, ['class' => 'form-control','readonly' => true]) !!}
                            {!! $errors->first('uid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('code') ? 'has-error' : ''}}">
                        {!! Form::label('code', 'Code', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('code', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('code_system') ? 'has-error' : ''}}">
                        {!! Form::label('code_system', 'Code System', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('code_system', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('code_system', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('resource') ? 'has-error' : ''}}">
                        {!! Form::label('resource', 'Resource', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('resource', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('resource', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('resource_element') ? 'has-error' : ''}}">
                        {!! Form::label('resource_element', 'Resource Element', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('resource_element', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('resource_element', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
                        {!! Form::label('category', 'Category', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('category', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description', 'Description', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('grouping') ? 'has-error' : ''}}">
                        {!! Form::label('grouping', 'Grouping', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('grouping', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('grouping', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('definition') ? 'has-error' : ''}}">
                        {!! Form::label('definition', 'Definition', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('definition', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('definition', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('is_panel_code') ? 'has-error' : ''}}">
                        {!! Form::label('is_panel_code', 'Panel Code', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('is_panel_code',['yes' => 'Yes','no' => 'No'],null,['class' => 'form-control','placeholder' => 'Select panel code']) !!}
                            {!! $errors->first('is_panel_code', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('is_multiselect') ? 'has-error' : ''}}">
                        {!! Form::label('is_multiselect', 'Multiselect', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('is_multiselect',['yes' => 'Yes','no' => 'No'],null,['class' => 'form-control','placeholder' => 'Select multiselect']) !!}
                            {!! $errors->first('is_multiselect', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('code_id') ? 'has-error' : ''}}">
                        {!! Form::label('code_id', 'ID', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">

                            {!! Form::text('code_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('code_id', '<p class="help-block">:message</p>') !!}
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
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('codes.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
