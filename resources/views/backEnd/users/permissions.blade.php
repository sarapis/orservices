@extends('backLayout.app')
@section('title')
User details
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>User {{$user->name}} Permissions</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{ Form::open(array('url' => route('user.save', $user->id), 'class' => 'form-horizontal')) }}
                    <div class="row">
                        @foreach($actions as $action)
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php $first= array_values($action)[0]; $firstname =explode(".", $first)[0];  ?>
                                {{Form::label($firstname, $firstname, ['class' => 'form col-md-4 text-right capital_letter'])}}
                                <div class="col-md-6">
                                    <select name="permissions[]" class="select form-control" multiple="multiple">
                                    @foreach($action as $act)
                                        @if(explode(".", $act)[0]=="api")
                                            <option value="{{$act}}"
                                                {{$user->permissions!= null && in_array($act, json_decode($user->permissions))?"selected":""}}>
                                                {{isset(explode(".", $act)[2])?explode(".", $act)[1].".".explode(".", $act)[2]:explode(".", $act)[1]}}
                                            </option>
                                        @else
                                            <option value="{{$act}}"
                                                {{$user->permissions!= null && in_array($act, json_decode($user->permissions))?"selected":""}}>
                                                {{explode(".", $act)[1]}}
                                            </option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-3">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{in_array($user->role ? $user->role->name : '', ['Client', 'Deliver']) ? route('user.index', ['type='.$user->role ? $user->role->name : '']):route('user.index')}}" class="btn btn-primary">Back to list</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $('.select').SumoSelect({ selectAll: true, placeholder: 'Nothing selected' });
</script>
@endsection
