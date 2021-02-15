@extends('backLayout.app')
@section('title')
Edit About
@stop

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit About</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                {!! Form::model($page, [
                    'url' => ['about_edit', 2],
                    'class' => 'form-horizontal', 'method' => 'put'
                ]) !!}

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                        {!! Form::label('body', 'Body ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('body',null, array('form-control','id'=>'summernote') ) !!}
                            {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Publish This Page</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                              <input type="checkbox" class="js-switch" value="checked" name="activate_about_home"  @if($layout->activate_about_home==1) checked @endif/>&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

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
