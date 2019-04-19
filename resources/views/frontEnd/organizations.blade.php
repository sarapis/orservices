@extends('layouts.app')
@section('title')
Organizations
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
    position: absolute;
    right: 25px;
    font-size: 25px;
    color: #000000;
}
.ui-menu .ui-menu-item .ui-state-active {
    padding-left: 0 !important;
}
ul#ui-id-1 {
    width: 260px !important;
}
#map{
    position: fixed !important;
}
</style>

@section('content')
<div class="wrapper">
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <div class="col-md-12 pt-15 pr-0">
                @foreach($organizations as $organization)
                <div class="panel content-panel">
                    <div class="panel-body p-20">
                        <a class="panel-link" href="/organization_{{$organization->organization_recordid}}">{{$organization->organization_name}}</a>
                        <h4>Number of Services: @if($organization->organization_services!=null)
                          {{sizeof(explode(",", $organization->organization_services))}}
                            @else 0 @endif</h4>
                        <h4><span class="badge bg-blue">Description:</span> {!! str_limit($organization->organization_description, 200) !!}</h4>
                    </div>
                </div>
                @endforeach
                <div class="pagination p-20">
                    {{ $organizations->appends(\Request::except('page'))->render() }}
                </div>
            </div>
<!--             
            <div class="col-md-4 p-0">
                <div id="map" style="position: fixed !important;width: 28%;"></div>
            </div> -->
        </div>
    </div>
</div>

@endsection


