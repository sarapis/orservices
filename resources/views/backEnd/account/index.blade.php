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
                    <div id="widget_div_main">
                        @if (is_array($account->sidebar_widget) && count($account->sidebar_widget) > 0)
                        @foreach ($account->sidebar_widget as $key => $value)
                        <div id="widget_inner_div_{{ $key }}">
                            <div class="form-group {{ $errors->has('sidebar_widget') ? 'has-error' : ''}}">
                                {!! Form::label('sidebar_widget', 'Sidebar Widgets ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::textarea('sidebar_widget['.$key.']', $value, ['class' => 'form-control','id' => 'sidebar_widget']) !!}
                                    {!! $errors->first('sidebar_widget', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('sidebar_widget') ? 'has-error' : ''}}">
                                {!! Form::label('sidebar_widget', 'Appears For ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6">
                                    @foreach ($roles as $value1)
                                    <input type="checkbox" name="appear_for[{{ $key }}][]" value="{{ $value1->id }}" {{ isset($account->appear_for[$key]) && count($account->appear_for[$key]) > 0 && in_array($value1->id,$account->appear_for[$key]) ? 'checked' : '' }} class="" id=""> {{ $value1->name }} <br>
                                    @endforeach
                                    {!! $errors->first('sidebar_widget', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <div class="col-sm-offset-3 col-sm-6" style="margin-bottom: 20px;">
                                    <button type="button" class="btn btn-danger delete_widget" data-id="widget_inner_div_{{ $key }}">Delete Widget</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div id="widget_inner_div_0">
                            <div class="form-group {{ $errors->has('sidebar_widget') ? 'has-error' : ''}}">
                                {!! Form::label('sidebar_widget', 'Sidebar Widgets ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6">
                                    {!! Form::textarea('sidebar_widget[0]', $account ? $account->sidebar_widget : '', ['class' => 'form-control','id' => 'sidebar_widget']) !!}
                                    {!! $errors->first('sidebar_widget', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('sidebar_widget') ? 'has-error' : ''}}">
                                {!! Form::label('sidebar_widget', 'Appears For ', ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6">
                                    @foreach ($roles as $value)
                                    <input type="checkbox" name="appear_for[0][]" value="{{ $value->id }}" class="" id=""> {{ $value->name }} <br>
                                    @endforeach
                                    {!! $errors->first('sidebar_widget', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            {{-- <div class="form-group text-right">
                                <div class="col-sm-offset-3 col-sm-6" style="margin-bottom: 10px;">
                                    <button type="button" class="btn btn-danger" data-id="widget_inner_div_0">Delete Widget</button>
                                </div>
                            </div> --}}
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="button" class="btn btn-info" id="add_widget">Add New Widget</button>
                        </div>
                    </div>
                    <div class="form-group text-right">
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
        let i = '{{ ($account->sidebar_widget && count($account->sidebar_widget) > 0 ? count($account->sidebar_widget) : 1) }}';
        $('#add_widget').click(function(){
            $('#widget_div_main').append('<div id="widget_inner_div_'+i+'"><div class="form-group"> <label for="sidebar_widget" class="col-sm-3 control-label">Sidebar Widgets</label> <div class="col-sm-6"> <textarea name="sidebar_widget['+i+']" id="sidebar_widget" class="form-control" cols="50" rows="10"> </textarea> </div></div><div class="form-group"> <label for="sidebar_widget" class="col-sm-3 control-label">Appears For</label> <div class="col-sm-6"> @foreach ($roles as $value) <input type="checkbox" name="appear_for['+i+'][]" value="{{$value->id}}" class="" id="">{{$value->name}}<br>@endforeach </div></div><div class="form-group text-right"> <div class="col-sm-offset-3 col-sm-6"> <button type="button" class="btn btn-danger delete_widget" data-id="widget_inner_div_'+i+'">Delete Widget</button> </div></div></div>')
            i ++;
        })
        $(document).on('click','.delete_widget',function(){
            let id = $(this).data('id')
            console.log(id)
            $('#'+id).remove();
        })
    });
</script>
@endsection
