@extends('backLayout.app')
@section('title')
create program
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Create new program</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'programs.store', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('alternate_name') ? 'has-error' : ''}}">
                        {!! Form::label('alternate_name', 'Alternate Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            <textarea name="alternate_name" class="form-control" id="alternate_name" cols="30" rows="10"></textarea>
                            {{--  --}}
                            {!! $errors->first('alternate_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('organizations') ? 'has-error' : ''}}">
                        {!! Form::label('organizations', 'Organization', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('organizations[]',$organizations, null, ['class' =>
                            'form-control select','multiple' => true]) !!}
                            {!! $errors->first('organizations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('services') ? 'has-error' : ''}}">
                        {!! Form::label('services', 'Services', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('services[]',$services, null, ['class' =>
                            'form-control select','multiple' => true]) !!}
                            {!! $errors->first('services', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('religions.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $('.select').SumoSelect({ selectAll: true, placeholder: 'Nothing selected' });
</script>
@endsection
