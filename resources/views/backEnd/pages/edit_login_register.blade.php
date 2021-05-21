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
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                {!! Form::model($page, [
                    'url' => ['login_register_edit', 5],
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
                    <!-- <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                        {!! Form::label('body', 'Body ', ['class' => 'col-sm-3 control-label']) !!}
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Make Directory Private</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                              <input type="checkbox" class="js-switch" value="checked" name="activate_login_home"  @if($layout->activate_login_home==1) checked @endif/>&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Show Login button in main menu</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                              <input type="checkbox" class="js-switch" value="checked" name="activate_login_button"  @if($layout->activate_login_button==1) checked @endif/>&nbsp;&nbsp;On
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
        $('#summernote_register').summernote({
            height: 200
        });
        $('#summernote_login').summernote({
            height: 200
        });
    });
    $('.js-switch').change(function(){
      var on = $('.js-switch').prop('checked');
      if(on == true){
        $('.item input').removeAttr('disabled');
        $('.usa-state').removeAttr('disabled');
      }
      else{
        $('.item input').attr('disabled','disabled');
        $('.usa-state').attr('disabled','disabled');
      }
    });
</script>
@endsection
