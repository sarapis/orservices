@extends('backLayout.app')
@section('title')
User Profile
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
                <h2>User Profile</h2>
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
                {{ Form::model($user,array('route' => ['user.saveProfile',$user->id], 'class' => 'form-horizontal','files' => true)) }}
                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                        {!! Form::label('first_name', 'First Name', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                        {!! Form::label('last_name', 'Last name' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        {!! Form::label('email', 'Email', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::email('email', null, ['class' => 'form-control','readonly' => 'true']) !!}
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                        {!! Form::label('phone_number', 'Phone Number' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('phone_number', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('change_password') ? 'has-error' : ''}}">
                        <label class="col-md-3"></label>
                        <div class="col-sm-6">
                            {!! Form::checkbox('change_password', null,false, ['id' => 'change_password']) !!}
                            {!! $errors->first('change_password', '<p class="help-block">:message</p>') !!}
                            {!! Form::label('change_password', 'Change Password ?' , ['class' => 'control-label']) !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}" id="password" style="display: none;">
                        {!! Form::label('password', 'Password', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}" id="password_confirm" style="display: none;">
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-3 control-label'])
                        !!}
                        <div class="col-sm-6">
                            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                            {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('user.index')}}" class="btn btn-primary">Return to all users</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(document).ready(function(){
        let isChecked = $('#change_password').is(":checked")
        if(isChecked){
            $('#password_confirm').show();
            $('#password').show();
        }

        $('#change_password').on('change',function(e){
            let value = e.target.checked
            if(value){
                $('#password_confirm').show();
                $('#password').show();
            }else{
                $('#password_confirm').hide()
                $('#password').hide()
            }

        })
    })
</script>
@endsection
