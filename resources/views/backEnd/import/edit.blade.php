@extends('backLayout.app')
@section('title')
Edit Source
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit Source</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::Model($dataSource,['route' => ['import.update',$dataSource->id], 'class' => 'form-horizontal','enctype' => 'multipart/form-data','method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'name', ['class' => 'col-sm-3 control-label']) !!}
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
                            {!! Form::select('import_type',['airtable_v3' => 'HSDS v.3.0 Airtable' ,'airtable' => 'HSDS v.2.0 Airtable' , 'zipfile' => 'HSDS v.2.0 Zip File','zipfile_api' => 'HSDS v.2.0 Zip API' ], null, ['class' => 'form-control select','id' => 'import_type']) !!}
                            {!! $errors->first('import_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('airtable_api_key') ? 'has-error' : ''}}" id="airtable_key_div">
                        {!! Form::label('airtable_api_key', 'Airtable API Key', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_api_key1', $dataSource->airtableKeyInfo ? ('***********'.substr($dataSource->airtableKeyInfo->api_key, -4)) : '' , ['class' => 'form-control','id' => 'airtable_api_key1']) !!}
                            {!! Form::text('airtable_api_key', $dataSource->airtableKeyInfo ? $dataSource->airtableKeyInfo->api_key : '' , ['class' => 'form-control','id' => 'airtable_api_key','style' => 'display:none;']) !!}

                            {!! $errors->first('airtable_api_key', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <button type="button" class="btn btn-success" id="airtable_key_v1">edit</button>
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('airtable_access_token') ? 'has-error' : ''}}" id="airtable_key_div">
                        {!! Form::label('airtable_access_token', 'Airtable Access Token', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_access_token1', $dataSource->airtableKeyInfo ? ('***********'.substr($dataSource->airtableKeyInfo->access_token, -4)) : '' , ['class' => 'form-control','id' => 'airtable_access_token1']) !!}
                            {!! Form::text('airtable_access_token', $dataSource->airtableKeyInfo ? $dataSource->airtableKeyInfo->access_token : '' , ['class' => 'form-control','id' => 'airtable_access_token','style' => 'display:none;']) !!}

                            {!! $errors->first('airtable_access_token', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <button type="button" class="btn btn-success" id="airtable_key_v1">edit</button>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('airtable_base_id') ? 'has-error' : ''}}" id="airtable_base_id_div">
                        {!! Form::label('airtable_base_id', 'Airtable Base ID', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('airtable_base_id', $dataSource->airtableKeyInfo ? $dataSource->airtableKeyInfo->base_url : '' , ['class' => 'form-control','id' => 'airtable_base_id']) !!}
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
                            'form-control select','placeholder' => 'Select sync type']) !!}
                            {!! $errors->first('auto_sync', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('sync_hours') ? 'has-error' : ''}}" id="sync_hours_div">
                        {!! Form::label('sync_hours', 'Hours Between Imports', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::number('sync_hours', $dataSource->sync_hours ? $dataSource->sync_hours : 1, ['class' => 'form-control','min' => 1]) !!}
                            {!! $errors->first('sync_hours', '<p class="help-block">:message</p>') !!}
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
                    <div class="form-group {{ $errors->has('organization_tags') ? 'has-error' : ''}}">
                        {!! Form::label('organization_tags', 'Add Organization Tags to Imported Records', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('organization_tags', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('organization_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('import.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>
<div class="modal fade " id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalTitle"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="addClass">
            <div class="modal-header ">
                <h3 class="modal-title" id="exampleModalLongTitle">Warning</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="message">Your current API access token will disappear and you will need to enter a new one.</div>
                <input type="hidden" name="version" id="version_id">
            </div>
            <div class="modal-footer text-center top_btn_data">
                <button type="button" class="btn btn-default btn_black" data-dismiss="modal">Go Back</button>
                <button type="button" class="btn btn-default btn_black" data-dismiss="modal" id="continue_pop_up">Continue</button>
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
        let import_type = "{{ $dataSource->import_type }}"
        let auto_sync = "{{ $dataSource->auto_sync }}"

        // if(import_type == 'zipfile' || import_type == 'zipfile_api'){
        //     $('#airtable_base_id_div').hide()
        //     $('#airtable_key_div').hide()
        //     $('#zipfile_div').show()
        // }else{
        //     $('#airtable_base_id_div').show()
        //     $('#airtable_key_div').show()
        //     $('#zipfile_div').hide()
        // }
        if(import_type == 'zipfile'){
            $('#airtable_base_id_div').hide()
            $('#airtable_key_div').hide()
            $('#endpoint_div').hide()
            $('#key_div').hide()
            $('#zipfile_div').show()
        }else if(import_type == 'zipfile_api'){
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
        if(auto_sync == '1'){
            $('#sync_hours_div').show()
        }else{
            $('#sync_hours_div').hide()
        }

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
        $('#airtable_key_v1').click(function () {
            $('#alertModal').modal('show')
        })
        $('#continue_pop_up').click(function () {
                $('#airtable_access_token1').hide()
                $('#airtable_access_token').show()
                $('#airtable_access_token').val('')
                // $('#airtable_base_id').val('')
            $('#alertModal').modal('hide')
        })
    });
</script>
@endsection
