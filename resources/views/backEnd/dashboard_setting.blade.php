@extends('backLayout.app')
@section('title')
Dashboard
@stop

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Dashboard</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                {!! Form::model($page, [
                    'url' => ['dashboard_setting', 6],
                    'class' => 'form-horizontal', 'method' => 'put'
                ]) !!}

                    <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Main Body
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{-- <textarea id="summernote_dashboard" type="text" name="bady" class="optional form-control col-md-7 col-xs-12">{{$page->body}}</textarea> --}}
                            {!! Form::textarea('body',null,['class' => 'optional form-control col-md-7 col-xs-12','id' => 'summernote_dashboard']) !!}
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Make Directory Contents Public</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>On&nbsp;&nbsp;
                              <input type="checkbox" class="js-switch" value="checked" name="activate_login_home"  @if($layout->activate_login_home==1) checked @endif/>&nbsp;&nbsp;Off
                            </label>
                        </div>
                    </div> --}}
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
        $('#summernote_dashboard').summernote({
            height: 300
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
