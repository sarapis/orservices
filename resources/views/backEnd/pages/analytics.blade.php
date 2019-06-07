@extends('backLayout.app')
@section('title')
Analytics
@stop

@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Analytics</h2>
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
            'url' => ['analytics', 4],
            'class' => 'form-horizontal', 'method' => 'put'
        ]) !!}
        
            <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                {!! Form::label('body', 'Google Analytics: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('body',null, ['class' => 'form-control'] ) !!}
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