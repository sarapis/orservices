@extends('backLayout.app')
@section('title')
{{trans('role.role')}}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>{{trans('role.role')}} {{$role->name}}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="row form-group">
                    {{ Form::label('slug', trans('role.slug'), ['class' => 'col-md-3 text-right control-label']) }}
                    <div class="col-md-6">
                        <label>{{ $role->slug}}</label>
                    </div>
                </div>
                <div class="row form-group">
                    {{ Form::label('name', trans('basic.name'), ['class' => 'col-md-3 text-right control-label']) }}
                    <div class="col-md-6">
                        <label>{{ $role->name}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <a href="{{route('role.index')}}" class="btn btn-primary">{{trans('basic.back')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection