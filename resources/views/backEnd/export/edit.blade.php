@extends('backLayout.app')
@section('title')
    edit export configuration
@stop

@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Export</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {!! Form::Model($export_configuration, ['route' => ['export.update', $export_configuration->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'method' => 'PUT']) !!}
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        {!! Form::label('name', 'Name', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('filter') ? 'has-error' : '' }}">
                        {!! Form::label('filter', 'Filter', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('filter[]', ['organization_tags' => 'By Organization Tag', 'service_tags' => 'By Service Tag'], $export_configuration->filter ? explode(',', $export_configuration->filter) : [], ['class' => 'form-control select', 'id' => 'filter', 'multiple' => 'true']) !!}
                            {!! $errors->first('filter', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('organization_tags') ? 'has-error' : '' }}"
                        id="organization_tags_div" style="display: none;">
                        {!! Form::label('organization_tags', 'Organization Tags ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('organization_tags[]', $organization_tags, $export_configuration->organization_tags ? explode(',', $export_configuration->organization_tags) : [], ['class' => 'form-control  select', 'multiple' => 'true']) !!}
                            {!! $errors->first('organization_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('service_tags') ? 'has-error' : '' }}" id="service_tags_div"
                        style="display: none;">
                        {!! Form::label('service_tags', 'Service Tags ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('service_tags[]', $service_tags, $export_configuration->service_tags ? explode(',', $export_configuration->service_tags) : null, ['class' => 'form-control  select', 'multiple' => 'true']) !!}
                            {!! $errors->first('service_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        {!! Form::label('type', 'Type', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('type', ['api_feed' => 'API Feed', 'download' => 'Download','data_for_api' => 'Data for API','data_for_api_v2' => 'Data for API v2','data_for_api_v3' => 'Data for API V3 (This type only for export do not use for import data in orservice)'], null, ['class' => 'form-control select', 'placeholder' => 'Select type', 'id' => 'type']) !!}
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('endpoint') ? 'has-error' : '' }}" style="display: none"
                        id="endpoint_div">
                        {!! Form::label('endpoint', 'Endpoint', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('endpoint', null, ['class' => 'form-control', 'id' => 'endpoint', 'readonly' => true]) !!}
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

                    <div class="form-group {{ $errors->has('key') ? 'has-error' : '' }}" id="key_div"
                        style="display: none">
                        {!! Form::label('key', 'API Key', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('key', null, ['class' => 'form-control', 'id' => 'key']) !!}
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
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            <a href="{{ route('export.index') }}" class="btn btn-primary">Back</a>
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
        $('.select').SumoSelect({
            selectAll: false,
            placeholder: 'Nothing selected'
        });
        $(document).ready(function() {
            let auto_sync = "{{ $export_configuration->auto_sync }}"
            let filter = "{{ $export_configuration->filter }}"
            filter = filter ? filter.split(',') : []
            let type = "{{ $export_configuration->type }}"
            if (auto_sync == '1') {
                $('#hours_div').show()
            } else {
                $('#hours_div').hide()
            }
            if ($.inArray('service_tags', filter) !== -1) {
                $('#service_tags_div').show()
            } else {
                $('#service_tags_div').hide()
            }
            if ($.inArray('organization_tags', filter) !== -1) {
                $('#organization_tags_div').show()
            } else {
                $('#organization_tags_div').hide()
            }
            // if ($.inArray('organization_tags_and_service_tags', filter) !== -1) {
            //     $('#organization_tags_div').show()
            //     $('#service_tags_div').show()
            // }
            if (type == 'api_feed' || type == 'data_for_api') {
                $('#endpoint_div').show()
                $('#key_div').show()
                $('#auto_sync_div').show()
            } else {
                $('#endpoint_div').hide()
                $('#key_div').hide()
                $('#auto_sync_div').hide()
                // $('#hours_div').hide()
            }
            $('#auto_sync').change(function() {
                let val = $(this).val()
                if (val == 1) {
                    $('#hours_div').show()
                } else {
                    $('#hours_div').hide()
                }
            })
            $('#filter').change(function() {
                let val = $(this).val()
                if ($.inArray('service_tags', val) !== -1) {
                    $('#service_tags_div').show()
                } else {
                    $('#service_tags_div').hide()
                }
                if ($.inArray('organization_tags', val) !== -1) {
                    $('#organization_tags_div').show()
                } else {
                    $('#organization_tags_div').hide()
                }
                // if ($.inArray('organization_tags_and_service_tags', val) !== -1) {
                //     $('#organization_tags_div').show()
                //     $('#service_tags_div').show()
                // }
            })
            $('#type').change(function() {
                let val = $(this).val()
                if (val == 'api_feed' || val == 'data_for_api') {
                    $('#endpoint_div').show()
                    $('#key_div').show()
                    $('#auto_sync_div').show()
                } else {
                    $('#endpoint_div').hide()
                    $('#key_div').hide()
                    $('#auto_sync_div').hide()
                    // $('#hours_div').hide()
                }
            })
            $('#key').keyup(function() {
                let val = $(this).val()
                $('#endpoint').val('{{ url('/export_csv/') }}' + '/' + val)
            })
        });
    </script>
@endsection
