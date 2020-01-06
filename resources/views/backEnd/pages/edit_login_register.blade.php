@extends('backLayout.app')
@section('title')
Edit Login/Register Page
@stop

@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Edit Login/Register Page</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

        {!! Form::model($page, [
            'url' => ['login_register_edit', 5],
            'class' => 'form-horizontal', 'method' => 'put'
        ]) !!}
        
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
            <!-- <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                {!! Form::label('body', 'Body: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('body',null, array('form-control','id'=>'summernote') ) !!}
                    {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                </div>
            </div> -->

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Register Page Content
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="summernote_register" type="text" name="register_content" class="optional form-control col-md-7 col-xs-12">{{$layout->register_content}}</textarea>
                </div>
            </div>

            <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Login Page Content
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="summernote_login" type="text" name="login_content" class="optional form-control col-md-7 col-xs-12">{{$layout->login_content}}</textarea>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-3">
                    {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
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
        $('#summernote_register').summernote({
            height: 200
        });
        $('#summernote_login').summernote({
            height: 200
        });
    });
  </script>
@endsection