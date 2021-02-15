@extends('backLayout.app')
@section('title')
Login
@stop

@section('style')

@stop
@section('content')



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{$page ? $page->title : '' }}</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <p>{!! $page ? $page->body : '' !!}</p>
        </div>
    </div>
  </div>

</div>
<br />


@endsection

@section('scripts')


@endsection
