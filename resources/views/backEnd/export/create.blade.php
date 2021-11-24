@extends('backLayout.app')
@section('title')
create export configuration
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Add Export</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'export.store', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('filter') ? 'has-error' : ''}}">
                        {!! Form::label('filter', 'Filter', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('filter',['none' => 'None' , 'organization_tags' => 'By Organization Tag'], null, ['class' =>
                            'form-control select','placeholder' => 'Select filter','id' => 'filter']) !!}
                            {!! $errors->first('filter', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('organization_tags') ? 'has-error' : ''}}" id="organization_tags_div" style="display: none;">
                        {!! Form::label('organization_tags', 'Organization Tags ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('organization_tags[]',$organization_tags,null,['class' => 'form-control  select','multiple' => 'true']) !!}
                            {!! $errors->first('organization_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                        {!! Form::label('type', 'Type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('type',['api_feed' => 'API Feed' , 'download' => 'Download'], null, ['class' =>
                            'form-control select','placeholder' => 'Select type','id' => 'type']) !!}
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('endpoint') ? 'has-error' : ''}}" style="display: none" id="endpoint_div">
                        {!! Form::label('endpoint', 'Endpoint', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('endpoint', url('/export_csv/'.$key), ['class' => 'form-control','readonly' => true,'id' => 'endpoint']) !!}
                            {!! $errors->first('endpoint', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('format') ? 'has-error' : ''}}">
                        {!! Form::label('format', 'Format', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('format', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('format', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}

                    <div class="form-group {{ $errors->has('key') ? 'has-error' : ''}}" id="key_div" style="display: none" >
                        {!! Form::label('key', 'API Key', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('key', $key, ['class' => 'form-control','id' => 'key']) !!}
                            {!! $errors->first('key', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div style="display: none" id="auto_sync_div">
                        <div class="form-group {{ $errors->has('auto_sync') ? 'has-error' : ''}}" >
                            {!! Form::label('auto_sync', 'Auto Import', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::select('auto_sync',[0 => 'Off' , 1 => 'On'], null, ['class' =>
                                'form-control select','placeholder' => 'Select sync type','id' => 'auto_sync']) !!}
                                {!! $errors->first('auto_sync', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('hours') ? 'has-error' : ''}}" id="hours_div" style="display: none;">
                            {!! Form::label('hours', 'Hours Between Exports', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::number('hours', 1, ['class' => 'form-control','min' => 1]) !!}
                                {!! $errors->first('hours', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('export.index')}}" class="btn btn-primary">Back</a>
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
        $('#auto_sync').change(function () {
            let val = $(this).val()
            if(val == 1){
                $('#hours_div').show()
            }else{
                $('#hours_div').hide()
            }
        })
        $('#filter').change(function () {
            let val = $(this).val()
            if(val == 'organization_tags'){
                $('#organization_tags_div').show()
            }else{
                $('#organization_tags_div').hide()
            }
        })
        $('#type').change(function () {
            let val = $(this).val()
            if(val == 'api_feed'){
                $('#endpoint_div').show()
                $('#key_div').show()
                $('#auto_sync_div').show()
            }else{
                $('#endpoint_div').hide()
                $('#key_div').hide()
                $('#auto_sync_div').hide()
                // $('#hours_div').hide()
            }
        })
        $('#key').keyup(function () {
            let val = $(this).val()
            $('#endpoint').val('{{ url("/export_csv/") }}'+'/'+val)
        })
    });
</script>
@endsection
