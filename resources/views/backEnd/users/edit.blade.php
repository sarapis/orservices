@extends('backLayout.app')
@section('title')
Edit User
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style type="text/css">
    .dropdown-menu.open {
        max-height: 156px;
        overflow: hidden;
        width: 100%;
    }
    .bootstrap-select .dropdown-toggle .filter-option {
        position: relative;
        top: 0;
        left: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        text-align: left;
    }
</style>
@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit user  {{$user->name}}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{ Form::model($user, array('method' => 'PATCH', 'url' => route('user.update', $user->id), 'class' => 'form-horizontal', 'files' => true)) }}
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
                            {!! Form::text('email', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('new_password') ? 'has-error' : ''}}">
                        {!! Form::label('new_password', 'Password', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::password('new_password', ['class' => 'form-control']) !!}
                            {!! $errors->first('new_password', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : ''}}">
                        {!! Form::label('new_password_confirmation', 'Password Confirmation', ['class' => 'col-md-3
                        control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::password('new_password_confirmation', ['class' => 'form-control']) !!}
                            {!! $errors->first('new_password_confirmation', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div id="role" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                        {!! Form::label('role','User role', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('role', $roles, $user->role_id, ['class' => 'form-control','placeholder' => 'Select user role','id' =>'role_id']) !!}
                            {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div id="organization" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                     {!! Form::label('organization','Organizations', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="user_organizations" name="user_organizations[]">
                                @foreach($organization_list as $key => $organization)
                                    <option value="{{$organization->organization_recordid}}" @if (in_array($organization->organization_recordid, $account_organization_list)) selected @endif>{{$organization->organization_name}}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div id="organization_tag_div" style="display: none;" class="form-group {{ $errors->has('role') ? 'has-error' : ''}}">
                        {!! Form::label('organization_tag','Organization Tag', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('organization_tags[]',$organization_tags,$account_organization_tag_list,['class' => 'form-control selectpicker','data-live-search' => 'true','data-size' => '5','id' => 'organization_tags','multiple' => 'true']) !!}
                            {!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('organization_tags') ? 'has-error' : '' }}"
                        id="organization_tags_div">
                        {!! Form::label('organization_tags', 'Organization Tags ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('organization_tags[]', $organization_tags, $account_organization_tag_list, ['class' => 'form-control  select', 'multiple' => 'true']) !!}
                            {!! $errors->first('organization_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('service_tags') ? 'has-error' : '' }}" id="service_tags_div"
                    >
                        {!! Form::label('service_tags', 'Service Tags ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('service_tags[]', $service_tags, $account_service_tag_list, ['class' => 'form-control  select', 'multiple' => 'true']) !!}
                            {!! $errors->first('service_tags', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('service_tags') ? 'has-error' : '' }}" id="service_tags_div"
                    >
                        {!! Form::label('verifier', 'Verifier ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::checkbox('verifier', '1', null,) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('user.index')}}" class="btn btn-primary">Return to all users</a>
                            <a href="{{route('user.send_activation',$user->id)}}" class="btn btn-info">Send Invite Email</a>
                            {{-- <a href="{{route('user.invite_user',$user->id)}}" class="btn btn-info">Invite a User</a> --}}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
    <link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function() {
        $("#user_organizations").selectpicker("");
        $('.select').SumoSelect({
            selectAll: false,
            placeholder: 'Nothing selected'
        });
        $("#role").selectpicker("");
        let role_name = $('#role_id').find('option:selected').text();
        if(role_name && role_name == 'Network Admin'){
            $('#organization_tag_div').show();
                $('#organization').hide();
                $('#user_organizations').val('');
                $("#user_organizations").selectpicker('refresh');
            }else{
                $('#organization').show();
                $('#organization_tag_div').hide();
            }
        $('#role_id').change(function(){
            var $option = $(this).find('option:selected');
            let text = $option.text();
            if(text == 'Network Admin'){
                $('#organization_tag_div').show();
                $('#organization').hide();
                $('#user_organizations').val('');
                $("#user_organizations").selectpicker('refresh');
            }else{
                $('#organization').show();
                $('#organization_tag_div').hide();

            }
        })
    });
</script>
@endsection
