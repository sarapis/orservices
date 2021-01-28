@extends('backLayout.app')
@section('title')
Edit Service Eligibilty : {{$service_eligibility->term}}
@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit Service Eligibilty: {{$service_eligibility->term}} </h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::model($service_eligibility, [
                    'method' => 'PATCH',
                    'url' => ['service_eligibilities', $service_eligibility->id],
                    'class' => 'form-horizontal'
                ]) !!}
                    <div class="form-group {{ $errors->has('term') ? 'has-error' : ''}}">
                        {!! Form::label('term','Term', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('term', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('term', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('service_eligibilities.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
		</div>
    </div>
</div>
@endsection
