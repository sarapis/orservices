@extends('backLayout.app')
@section('title')
Show user {{$user->first_name}}
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>The user : {{$user->first_name}}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="row form-group">
                    {!! Form::label('first_name','First name:', ['class' => 'col-md-3 text-right control-label']) !!}
                    <div class="col-sm-6">
                        <label>{{$user->first_name}}</label>
                    </div>
                </div>
                <div class="row form-group">
                    {!! Form::label('last_name', 'Last name:', ['class' => 'col-md-3 text-right control-label']) !!}
                    <div class="col-sm-6">
                        <label>{{$user->last_name}}</label>
                    </div>
                </div>
                <div class="row form-group">
                    {!! Form::label('email', 'Email:', ['class' => 'col-md-3 text-right control-label']) !!}
                    <div class="col-sm-6">
                        <label>{{$user->email}}</label>
                    </div>
                </div>
                <div class="row form-group">
                    {!! Form::label('role', 'Role:', ['class' => 'col-md-3 text-right control-label']) !!}
                    <div class="col-sm-6">
                        <label>{{$user->roles ? $user->roles->name : ''}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <a href="{{route('user.index')}}" class="btn btn-primary">Return to all users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
