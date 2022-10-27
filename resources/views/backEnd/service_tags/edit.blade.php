@extends('backLayout.app')
@section('title')
    Edit Service Tag : {{ $service_tag->name }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Service Tag: {{ $service_tag->name }} </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {!! Form::model($service_tag, ['method' => 'PATCH', 'url' => ['service_tags', $service_tag->id], 'class' => 'form-horizontal']) !!}
                    <div class="form-group {{ $errors->has('tag') ? 'has-error' : '' }}">
                        {!! Form::label('tag', 'Tag', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('tag', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('tag', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            <a href="{{ route('service_tags.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
