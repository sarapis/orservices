@extends('backLayout.app')
@section('title')
System Emails
@stop
<style>
    .form-group {
        margin-bottom: 10px;
        display: block;
        float: left;
        width: 100%;
    }
</style>
@section('content')

<div class="row">
    {!! Form::open(['route' => 'system_emails.store']) !!}
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Account Creation Email</h2>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                {{-- <label class="col-sm-3 control-label">&nbsp;</label> --}}
                <div class="col-sm-6">
                    <p>This email is automatically sent to users immediately after they register.</p>
                </div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Activate</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <label>Off&nbsp;&nbsp;
                        <input type="checkbox" class="js-switch" value="checked" name="create_status"  @if($createMail ? $createMail->status : ''==1) checked @endif/>&nbsp;&nbsp;On
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('create_subject') ? 'has-error' : ''}}">
                    {!! Form::label('create_subject', 'Subject', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('create_subject', $createMail ? $createMail->subject : '', ['class' => 'form-control']) !!}
                        {!! $errors->first('create_subject', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>


                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Body
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="summernote_register" type="text" name="create_body" class="optional form-control col-md-7 col-xs-12">{{$createMail ? $createMail->body : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Available Inclusions:</label>
                    <div class="col-sm-6">
                        <p>First Name: {first_name}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <p>Password: {password}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Account Activation Email</h2>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                {{-- <label class="col-sm-3 control-label">&nbsp;</label> --}}
                <div class="col-sm-12">
                    <p>This email is automatically sent to users the first time they are assigned a role within the system.</p>
                </div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Activate</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <label>Off&nbsp;&nbsp;
                        <input type="checkbox" class="js-switch" value="checked" name="activation_status"  @if($activationMail ? $activationMail->status : ''==1) checked @endif/>&nbsp;&nbsp;On
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('create_subject') ? 'has-error' : ''}}">
                    {!! Form::label('create_subject', 'Subject', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('activation_subject', $activationMail ? $activationMail->subject : '', ['class' => 'form-control']) !!}
                        {!! $errors->first('create_subject', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Body
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="summernote_activation" type="text" name="activation_body" class="optional form-control col-md-7 col-xs-12">{{$activationMail ? $activationMail->body : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Available Inclusions:</label>
                    <div class="col-sm-6">
                        <p>First Name: {first_name}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Invitation Email</h2>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                {{-- <label class="col-sm-3 control-label">&nbsp;</label> --}}
                <div class="col-sm-12">
                    <p>This email is for invite users.</p>
                </div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Activate</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <label>Off&nbsp;&nbsp;
                        <input type="checkbox" class="js-switch" value="checked" name="invitation_status"  @if($invitationMail ? $invitationMail->status : ''==1) checked @endif/>&nbsp;&nbsp;On
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('invitation_subject') ? 'has-error' : ''}}">
                    {!! Form::label('invitation_subject', 'Subject', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('invitation_subject', $invitationMail ? $invitationMail->subject : '', ['class' => 'form-control']) !!}
                        {!! $errors->first('invitation_subject', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Body
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="summernote_invitation" type="text" name="invitation_body" class="optional form-control col-md-7 col-xs-12">{{$invitationMail ? $invitationMail->body : ''}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Available Inclusions:</label>
                    <div class="col-sm-6">
                        <p>First Name: {first_name}</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300
        });
        $('#summernote_register').summernote({
            height: 200
        });
        $('#summernote_activation').summernote({
            height: 200
        });
        $('#summernote_invitation').summernote({
            height: 200
        });
    });
    $('.js-switch').change(function(){
      var on = $('.js-switch').prop('checked');
      if(on == true){
        $('.item input').removeAttr('disabled');
        $('.usa-state').removeAttr('disabled');
      }
      else{
        $('.item input').attr('disabled','disabled');
        $('.usa-state').attr('disabled','disabled');
      }
    });
</script>
@endsection
