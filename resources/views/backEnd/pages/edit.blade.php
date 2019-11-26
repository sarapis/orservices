@extends('backLayout.app')
@section('title')
Edit Page
@stop

@section('content')

    <h3>Edit Page</h3>
    <hr/>

<!--     {!! Form::model($page, [
        'url' => ['pages', $page->id],
        'class' => 'form-horizontal'
    ]) !!} -->
    {{ Form::open(array('url' => ['pages', $page->id], 'class' => 'form-horizontal')) }}
            <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title', 'Title: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                {!! Form::label('body', 'Body: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('body',null, array('form-control','id'=>'summernote') ) !!}
                    {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
    $('#summernote').summernote({
        height: 300
    });
    });
  </script>
@endsection