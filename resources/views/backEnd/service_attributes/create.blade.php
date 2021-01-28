@extends('backLayout.app')
@section('title')
Create user
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
                {{ Form::open(array('url' => route('service_attributes.store'), 'class' => 'form-horizontal','files' => true)) }}
                    <div class="form-group {{ $errors->has('service_recordid') ? 'has-error' : ''}}">
                        {!! Form::label('service_recordid', 'Service Id', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('service_recordid',$services,null,['class' => 'form-control','placeholder' => 'select service']) !!}
                            {!! $errors->first('service_recordid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('taxonomy_recordid') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_recordid', 'Taxonomy Term Id' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('taxonomy_recordid',$taxonomy,null,['class' => 'form-control','placeholder' => 'select taxonomy']) !!}
                            {!! $errors->first('taxonomy_recordid', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('service_attributes.index')}}" class="btn btn-primary">Back</a>
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
