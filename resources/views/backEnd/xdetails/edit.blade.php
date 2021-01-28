@extends('backLayout.app')
@section('title')
Create Attribute
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
                <h2>Edit Attribute</h2>
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
                {{ Form::model($xdetails,array('method' => 'PATCH','url' => route('XDetails.update',$xdetails->id), 'class' => 'form-horizontal','files' => true)) }}
                    <div class="form-group {{ $errors->has('detail_value') ? 'has-error' : ''}}">
                        {!! Form::label('detail_value', 'Detail Value', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('detail_value', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('detail_value', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_type') ? 'has-error' : ''}}">
                        {!! Form::label('detail_type', 'Detail type', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_type',$detail_types,null,['class' => 'form-control','placeholder' => 'select service','id' => 'detail_type']) !!}
                            {!! $errors->first('detail_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_description') ? 'has-error' : ''}}">
                        {!! Form::label('detail_description', 'Detail description', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('detail_description', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('detail_description', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('detail_services') ? 'has-error' : ''}}">
                        {!! Form::label('detail_services', 'Services' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_services',$services,$serviceIds,['class' => 'form-control','id' => 'detail_services','multiple' => true]) !!}
                            {!! $errors->first('detail_services', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_organizations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_organizations', 'Organizations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_organizations',$organizations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_organizations' ]) !!}
                            {!! $errors->first('detail_organizations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_locations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_locations', 'Locations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_locations',$locations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_locations']) !!}
                            {!! $errors->first('detail_locations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('phone_id') ? 'has-error' : ''}}">
                        {!! Form::label('phone_id', 'Phone Id' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('phone_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('phone_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('XDetails.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $('#detail_services').SumoSelect({ placeholder: 'Nothing selected' });
    $('#detail_type').SumoSelect({ placeholder: 'Nothing selected' });
    $('#detail_organizations').SumoSelect({ placeholder: 'Nothing selected' });
    $('#detail_locations').SumoSelect({ placeholder: 'Nothing selected' });
</script>
@endsection
