@extends('backLayout.app')
@section('title')
Create Attribute
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
                <h2>Create Attribute</h2>
                {{-- @if (count($errors) > 0)
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="alert alert-danger">
                            <strong>Upsss !</strong> There is an error...<br /><br />
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif --}}
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{ Form::open(array('url' => route('other_attributes.store'), 'class' => 'form-horizontal','files' => true)) }}
                    <div class="form-group {{ $errors->has('link_id') ? 'has-error' : ''}}">
                        {!! Form::label('link_id', 'Link Id', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('link_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('link_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('link_type') ? 'has-error' : ''}}">
                        {!! Form::label('link_type', 'Link Type', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('link_type', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('link_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('taxonomy_term_id') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_term_id', 'Taxonomy Term Id' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('taxonomy_term_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('taxonomy_term_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('other_attributes.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@endsection
