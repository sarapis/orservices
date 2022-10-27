@extends('frontLayout.app')
	@section('title')
	Register
	@stop
@section('content')
<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
{{-- <style type="text/css">
	.dropdown-menu.open{
	max-height: 300px !important;
	max-width: 100px !important;
	}
	div.filter-option {
	background: white;
	color: #2c3e50;
	}

</style> --}}

<div class="inner_services login_register">

	@if (Session::has('message'))
	<div class="alert alert-{{(Session::get('status')=='error')?'danger':Session::get('status')}} " alert-dismissable fade in id="sessions-hide">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<strong>{{Session::get('status')}}!</strong> {!! Session::get('message') !!}
	</div>
	@endif
	<div class="col-md-6 col-md-offset-3">
		{{ Form::open(array('route' => 'register', 'class' => 'form-horizontal form-signin register_form','files' => true)) }}
			{!! csrf_field() !!}
			<h3 class="form-signin-heading">Register</h3>
            <p>{!! $layout->register_content !!}</p>
			<div class="col-md-6">
				<div class="form-group  {{ $errors->has('first_name') ? 'has-error' : ''}}">
					<label for="first_name">First Name</label>
					{!! Form::text('first_name', null, ['class' => 'form-control','placeholder '=>'Enter your first name']) !!}
					{!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group  {{ $errors->has('last_name') ? 'has-error' : ''}}">
					<label for="last_name">Last Name</label>
					{!! Form::text('last_name', null, ['class' => 'form-control','placeholder '=>'Enter your last name']) !!}
					{!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group  {{ $errors->has('email') ? 'has-error' : ''}}">
					<label for="email">Your Email</label>
					{!! Form::text('email', null, ['class' => 'form-control','placeholder '=>'E-mail']) !!}
					{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
				</div>
            </div>

            <div class="col-md-12">
				<div class="form-group  {{ $errors->has('phone_number') ? 'has-error' : ''}}">
					<label for="phone_number">Your Phone Number</label>
					{!! Form::text('phone_number', null, ['class' => 'form-control','placeholder '=>'Phone Number']) !!}
					{!! $errors->first('phone_number', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group  {{ $errors->has('organization') ? 'has-error' : ''}}">
					<label for="organization">Organization</label>
					<select class="form-control selectpicker" data-live-search="true" id="organization" name="organization">
						<option value="" selected>Select Organization</option>
					@foreach($organization_info_list as $key => $organization_info)
						<option value="{{$organization_info->organization_recordid}}">{{$organization_info->organization_name}}</option>
					@endforeach
					</select>
					{!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
				</div>
            </div>
            @if ($layout->show_registration_message == '1')
            <div class="col-md-12">
				<div class="form-group  {{ $errors->has('message') ? 'has-error' : ''}}">
					<label for="message">Your Message</label>
					{!! Form::textarea('message', null, ['class' => 'form-control','placeholder '=>'Message']) !!}
					{!! $errors->first('message', '<p class="help-block">:message</p>') !!}
				</div>
            </div>
            @endif
			<div class="col-md-6">
				<div class="form-group  {{ $errors->has('password') ? 'has-error' : ''}}">
					<label for="password">Password</label>
					{!! Form::password('password', ['class' => 'form-control','rel'=>'gp' ,'data-size'=>'10' ,'data-character-set'=>'a-z,A-Z,0-9,#' ,'placeholder '=>'Enter your Password']) !!}
					{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
					<label for="confirm">Confirm Password</label>
					{!! Form::password('password_confirmation', ['class' => 'form-control','rel'=>'gp' ,'data-size'=>'10' ,'data-character-set'=>'a-z,A-Z,0-9,#' ,'placeholder '=>'Confirm your Password']) !!}
					{!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
				</div>
			</div>
			<div class="col-md-12 ">
				<div class="text-center">
					<button class="btn btn-primary btn-lg btn-block register-button" type="submit" >Register</button>
				</div>
			</div>
			<div class="col-md-12 ">
				<div class="text-center">
					<a class="forget_password" style="margin-top: 15px;display: inline-block;margin-bottom: 0;" href="{{url('login')}}">Login</a>
				</div>
			</div>

			@if ($errors->has('global'))
				<span class="help-block danger">
					<strong style="color:red" >{{ $errors->first('global') }}</strong>
				</span>
			@endif
		{{ Form::close() }}
	</div>
</div>

@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
	$(document).ready(function() {
		$('#organization').selectpicker("");
	});
</script>

@endsection
