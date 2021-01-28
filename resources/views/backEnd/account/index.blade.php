@extends('backLayout.app')
@section('title')
Account
@stop

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Account</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                {!! Form::open(['rotue' => 'account.store','class' => 'form-horizontal']) !!}

                    <div class="form-group {{ $errors->has('top_content') ? 'has-error' : ''}}">
                        {!! Form::label('top_content', 'Top Content ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('top_content', $account ? $account->top_content : '' , ['class' => 'form-control','id' => 'top_content']) !!}
                            {!! $errors->first('top_content', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('sidebar_widget') ? 'has-error' : ''}}">
                        {!! Form::label('sidebar_widget', 'Sidebar Widget ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('sidebar_widget', $account ? $account->sidebar_widget : '', ['class' => 'form-control','id' => 'sidebar_widget']) !!}
                            {!! $errors->first('sidebar_widget', '<p class="help-block">:message</p>') !!}
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
        $('#top_content').summernote({
            height: 300
        });
        // $('#sidebar_widget').summernote({
        //     height: 300
        // });
    });
</script>
@endsection
