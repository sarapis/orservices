@extends('backLayout.app')
@section('title')
    Other Services
@stop
<style>
    .color-pick {
        padding: 0 !important;
    }

</style>
@section('content')
    <div class="row">
        <div id='loading' style="display:none;">
            <img src="/images/loader.gif" />
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:25px;">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="x_title" style="margin-bottom:30px;">
                <h4>Other Services</h4>
            </div>
            {!! Form::open(['route' => 'saveMessageCredential', 'class' => 'form-horizontal form-label-left']) !!}
            <div class="x_panel">
                <div class="x_title" style="margin-bottom:30px;">
                    <h5>SendGrid</h5>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>SendGrid Api key:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text"
                                value="{{ '********************' . substr($sendgridKey, -7) }}" name="sendgridApiKey1"
                                id="sendgridApiKey1" disabled>
                            <input class="form-control" type="text" value="{{ $sendgridKey }}" name="sendgridApiKey"
                                id="sendgridApiKey2" style="display: none;" required>
                            <button type="button" id="clickChangeKey">Change</button>
                            @if ($errors->first('sendgridApiKey'))
                                <div class="alert alert-danger">{{ $errors->first('sendgridApiKey') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>SendGrid Mail From Name:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{ $sendgridMailFromName }}"
                                name="sendgridMailFromName" id="sendgridMailFromName" required>
                            @if ($errors->first('sendgridMailFromName'))
                                <div class="alert alert-danger">{{ $errors->first('sendgridMailFromName') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>SendGrid Mail From Address:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{ $sendgridMailFromAddress }}"
                                name="sendgridMailFromAddress" id="sendgridMailFromAddress" required>
                            @if ($errors->first('sendgridMailFromAddress'))
                                <div class="alert alert-danger">{{ $errors->first('sendgridMailFromAddress') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"></label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <button type="button" class="btn btn-primary" onclick="checkSendgrid()">Check</button>
                            <button type="submit" class="btn btn-success btn-rounded" style="width:45%;"><i
                                    class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
                <div class="x_title" style="margin-bottom:30px;">
                    <h5>ShareThis</h5>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>ShareThis Domain Activate URL:</b>
                        </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{ $share_this_api_activate }}"
                                name="share_this_api_activate" id="share_this_api_activate">
                            <p>You can find this URL on the ShareThis app within a code embed string. The URL should look
                                like this:
                                https://platform-api.sharethis.com/js/sharethis.js#property=xxxxxxxxxxxxxx&product=sop.</p>
                            @if ($errors->first('share_this_api_activate'))
                                <div class="alert alert-danger">{{ $errors->first('share_this_api_activate') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>ShareThis API:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{ $share_this_api }}" name="share_this_api" id="share_this_api">
                            @if ($errors->first('share_this_api'))
                                <div class="alert alert-danger">{{ $errors->first('share_this_api') }}</div>
                            @endif
                        </div>
                    </div>
                </div> --}}
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b></b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <button type="submit" class="btn btn-success btn-rounded" style="width:45%;"><i
                                    class="fa fa-save"></i>
                                Save</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="x_panel">
                <div class="x_title" style="margin-bottom:30px;">
                    <h5>Google Analytics</h5>
                </div>
                {!! Form::model($page, ['url' => ['analytics', 4], 'class' => 'form-horizontal', 'method' => 'put']) !!}
                <div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
                    {!! Form::label('body', 'Google Analytics: ', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('body', null, ['class' => 'form-control']) !!}
                        {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade " id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content" id="addClass">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle" style="color:#fff">Alert</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#clickChangeKey').click(function() {
                $('#sendgridApiKey1').hide()
                $('#sendgridApiKey2').show()
                $('#sendgridApiKey2').val('')
                $(this).hide()
            })
        })
    </script>
    <script>
        function checkTwillio() {
            $('#loading').show();
            let twillioKey = $('#twillioKey').val();
            let twillioSid = $('#twillioSid').val();
            let twillioNumber = $('#twillioNumber').val();

            $.ajax({
                method: 'POST',
                url: '{{ route('checkTwillio') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data: {
                    twillioKey,
                    twillioSid,
                    twillioNumber
                },
                success: function(response) {
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-success');
                    $('#message').append('<h4 style="color:#fff;">' + response.message + '</h4>')
                    $('#alertModal').modal('show');
                },
                error: function(error) {
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-danger');
                    $('#message').append('<h4 style="color:#fff;">' + error['responseJSON'].message + '</h4>')
                    $('#alertModal').modal('show');
                }

            })
        }

        function checkSendgrid() {
            $('#loading').show();
            let sendgridApiKey = $('#sendgridApiKey2').val();

            $.ajax({
                method: 'POST',
                url: '{{ route('checkSendgrid') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data: {
                    sendgridApiKey
                },
                success: function(response) {
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-success');
                    $('#message').append('<h4 style="color:#fff;">' + response.message + '</h4>')
                    $('#alertModal').modal('show');
                },
                error: function(error) {
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-danger');
                    $('#message').append('<h4 style="color:#fff;">' + error['responseJSON'].message + '</h4>')
                    $('#alertModal').modal('show');
                }

            })
        }
    </script>
    <style>
        .bg-danger {
            color: #fff;
            background-color: #dc3545;
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: none;
        }

        .modal-footer {
            padding: 6px 20px 20px;
            border-top: none;
        }

        .modal-title {
            display: inline-block;
        }

        .bg-success {
            color: #fff;
            background-color: #28a745;
        }

    </style>
@endsection
