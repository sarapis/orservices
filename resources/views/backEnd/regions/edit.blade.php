@extends('backLayout.app')
@section('title')
Edit region : {{$region->region}}
@stop

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit region</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($region,['route' => array('regions.update',$region->id), 'class' => 'form-horizontal','method' => 'PUT','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('region') ? 'has-error' : ''}}">
                        {!! Form::label('region', 'Region', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('region', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('region', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('regions.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>

@endsection
