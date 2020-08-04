@extends('backLayout.app')
@section('title')
Message settings
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
            <h4>Campaigns</h4>
        </div>
        {!! Form::open(['route' => 'saveMessageCredential', 'class' => 'form-horizontal form-label-left']) !!}
        <div class="x_panel">
                {{-- <div class="x_title" style="margin-bottom:30px;">
                    <h5>Twillio</h5>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>Twillio SID:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control " type="text" value="{{$twillioSid}}" name="twillioSid"
                                id="twillioSid" required>
                            @if( $errors->first('twillioSid'))
                            <div class="alert alert-danger">{{ $errors->first('twillioSid') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>Twillio Key:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{$twillioKey}}" name="twillioKey"
                                id="twillioKey" required>
                            @if( $errors->first('twillioKey'))
                            <div class="alert alert-danger">{{ $errors->first('twillioKey') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label sel-label-org pl-4 col-md-3"><b>Twillio Number:</b> </label>
                        <div class="col-md-5 col-sm-6 col-xs-12 ">
                            <input class="form-control" type="text" value="{{$twllioNumber}}" name="twillioNumber"
                                id="twillioNumber" required>
                            @if( $errors->first('twillioNumber'))
                            <div class="alert alert-danger">{{ $errors->first('twillioNumber') }}</div>
                            @endif
                        </div>
                    </div>
                </div> --}}
            {{-- <div class="form-group">
                <div class="row">
                    <label class="control-label sel-label-org pl-4 col-md-3"></label>
                    <div class="col-md-5 col-sm-6 col-xs-12 ">
                        <button type="button" class="btn btn-primary" onclick="checkTwillio()">Check</button>
                    </div>
                </div>
            </div> --}}
            <div class="x_title" style="margin-bottom:30px;">
                <h5>SendGrid</h5>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="control-label sel-label-org pl-4 col-md-3"><b>SendGrid Api key:</b> </label>
                    <div class="col-md-5 col-sm-6 col-xs-12 ">
                        <input class="form-control" type="text" value="{{$sendgridKey}}" name="sendgridApiKey"
                            id="sendgridApiKey" required>
                        @if( $errors->first('sendgridApiKey'))
                        <div class="alert alert-danger">{{ $errors->first('sendgridApiKey') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="control-label sel-label-org pl-4 col-md-3"></label>
                    <div class="col-md-5 col-sm-6 col-xs-12 ">
                        <button type="button" class="btn btn-primary" onclick="checkSendgrid()">Check</button>
                    </div>
                </div>
            </div>
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
    function checkTwillio(){
            $('#loading').show();
            let twillioKey = $('#twillioKey').val();
            let twillioSid = $('#twillioSid').val();
            let twillioNumber = $('#twillioNumber').val();

            $.ajax({
                method: 'POST',
                url: '{{route("checkTwillio")}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{ twillioKey,twillioSid,twillioNumber },
                success:function(response){
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-success');
                    $('#message').append('<h4 style="color:#fff;">'+response.message+'</h4>')
                    $('#alertModal').modal('show');
                },
                error:function(error){
                    $('#loading').hide();
                    $('#message').empty();
                    $('#addClass').removeClass('bg-danger');
                    $('#addClass').removeClass('bg-success');
                    $('#addClass').addClass('bg-danger');
                    $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
                    $('#alertModal').modal('show');
                }

            })
        }
    function checkSendgrid(){
        $('#loading').show();
        let sendgridApiKey = $('#sendgridApiKey').val();

        $.ajax({
            method: 'POST',
            url: '{{route("checkSendgrid")}}',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{ sendgridApiKey },
            success:function(response){
                $('#loading').hide();
                $('#message').empty();
                $('#addClass').removeClass('bg-danger');
                $('#addClass').removeClass('bg-success');
                $('#addClass').addClass('bg-success');
                $('#message').append('<h4 style="color:#fff;">'+response.message+'</h4>')
                $('#alertModal').modal('show');
            },
            error:function(error){
                $('#loading').hide();
                $('#message').empty();
                $('#addClass').removeClass('bg-danger');
                $('#addClass').removeClass('bg-success');
                $('#addClass').addClass('bg-danger');
                $('#message').append('<h4 style="color:#fff;">'+error['responseJSON'].message+'</h4>')
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
