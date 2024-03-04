@extends('backLayout.app')
@section('title')
Add Source
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Add Source</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'import.store', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('format') ? 'has-error' : ''}}">
                        {!! Form::label('format', 'Format', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('format', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('format', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('import_type') ? 'has-error' : ''}}">
                        {!! Form::label('import_type', 'Format', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('import_type',['airtable_v3' => 'HSDS v.3.0 Airtable' ,'airtable' => 'HSDS v.2.0 Airtable' , 'zipfile' => 'HSDS v.2.0 Zip File','zipfile_api' => 'HSDS v.2.0 Zip API' ], 'airtable', ['class' => 'form-control select','id' => 'import_type']) !!}
                            {!! $errors->first('import_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('airtable_api_key') ? 'has-error' : ''}}" id="airtable_key_div">
                        {!! Form::label('airtable_api_key', 'Airtable API Key', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_api_key', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('airtable_api_key', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('airtable_access_token') ? 'has-error' : ''}}" id="airtable_key_div">
                        {!! Form::label('airtable_access_token', 'Airtable Access Token', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_access_token', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('airtable_access_token', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('airtable_base_id') ? 'has-error' : ''}}" id="airtable_base_id_div">
                        {!! Form::label('airtable_base_id', 'Airtable Base ID', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_base_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('airtable_base_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('zipfile') ? 'has-error' : ''}}" id="zipfile_div" style="display:none">
                        {!! Form::label('zipfile', 'Upload Zip', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::file('zipfile', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('zipfile', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('endpoint') ? 'has-error' : ''}}" style="display: none" id="endpoint_div">
                        {!! Form::label('endpoint', 'Endpoint', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('endpoint', null, ['class' => 'form-control','id' => 'endpoint']) !!}
                            {!! $errors->first('endpoint', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('key') ? 'has-error' : ''}}" id="key_div" style="display: none" >
                        {!! Form::label('key', 'API Key', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('key', null, ['class' => 'form-control','id' => 'key']) !!}
                            {!! $errors->first('key', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('auto_sync') ? 'has-error' : ''}}">
                        {!! Form::label('auto_sync', 'Auto Import', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('auto_sync',[0 => 'Off' , 1 => 'On'], null, ['class' =>
                            'form-control select','placeholder' => 'Select sync type','id' => 'auto_sync']) !!}
                            {!! $errors->first('auto_sync', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('mode') ? 'has-error' : ''}}">
                        {!! Form::label('mode', 'Mode', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('mode',['replace' => 'Replace' , 'add' => 'Add'], null, ['class' =>
                            'form-control select','placeholder' => 'Select mode']) !!}
                            {!! $errors->first('mode', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('sync_hours') ? 'has-error' : ''}}" id="sync_hours_div" style="display: none;">
                        {!! Form::label('sync_hours', 'Hours Between Imports', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::number('sync_hours', 1, ['class' => 'form-control','min' => 1]) !!}
                            {!! $errors->first('sync_hours', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('organization_tags') ? 'has-error' : ''}}">
                        {!! Form::label('organization_tags', 'Add Organization Tags to Imported Records', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('organization_tags', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('organization_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('import.index')}}" class="btn btn-primary">Back</a>
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
    $(document).ready(function(){
        $('#import_type').change(function () {
            let val = $(this).val()
            if(val == 'zipfile'){
                $('#airtable_base_id_div').hide()
                $('#airtable_key_div').hide()
                $('#endpoint_div').hide()
                $('#key_div').hide()
                $('#zipfile_div').show()
            }else if(val == 'zipfile_api'){
                $('#airtable_base_id_div').hide()
                $('#airtable_key_div').hide()
                $('#endpoint_div').show()
                $('#key_div').show()
                $('#zipfile_div').hide()
            }else{
                $('#airtable_base_id_div').show()
                $('#airtable_key_div').show()
                $('#endpoint_div').hide()
                $('#zipfile_div').hide()
                $('#key_div').hide()
            }
        })
        $('#auto_sync').change(function () {
            let val = $(this).val()
            if(val == 1){
                $('#sync_hours_div').show()
            }else{
                $('#sync_hours_div').hide()
            }
        })
    });
</script>
@endsection
