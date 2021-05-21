@extends('backLayout.app')
@section('title')
Edit Taxonomy
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
                <h2>Edit Taxonomy</h2>
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
                {{ Form::model($taxonomy_type,array('url' => route('taxonomy_types.update',$taxonomy_type->id), 'class' => 'form-horizontal','files' => true,'method' => 'put')) }}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
                        {!! Form::label('order', 'Order', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('order', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                        {!! Form::label('type', 'Type', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('type',['internal' => 'Internal','external' => 'External'],null,['class' => 'form-control','placeholder' => 'select type','id' => 'type']) !!}
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('reference_url') ? 'has-error' : ''}}">
                        {!! Form::label('reference_url', 'Reference URL', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('reference_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('reference_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('notes') ? 'has-error' : ''}}">
                        {!! Form::label('notes', 'Notes' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('notes', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('detail_organizations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_organizations', 'Organizations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_organizations',$organizations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_organizations']) !!}
                            {!! $errors->first('detail_organizations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_locations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_locations', 'Locations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_locations',$locations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_locations']) !!}
                            {!! $errors->first('detail_locations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
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
                            <a href="{{route('taxonomy_types.index')}}" class="btn btn-primary">Back</a>
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
    $('#type').SumoSelect({ placeholder: 'Nothing selected' });
    $('#detail_organizations').SumoSelect({ placeholder: 'Nothing selected' });
    $('#detail_locations').SumoSelect({ placeholder: 'Nothing selected' });
</script>
@endsection
