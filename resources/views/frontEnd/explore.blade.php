@extends('layouts.app')
@section('title')
Explore
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
@include('layouts.filter')
<div class="wrapper">
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <div class="col-md-8 pr-0">
                @if (session('success'))
                <div class="pl-15 pr-15 pt-15">
                    <div class="alert dark alert-dismissible" role="alert" style="background: #3f8a7b;
                    color: white;">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      <h4 class="feedback-title">Your district doesn't have Participatory Budgeting (PB) yet. PB is a democratic process in which community members directly decide how to spend part of a public budget in their neighborhood. In the US and Canada 414,000 people have worked together to decide how to spend over $299 Million dollars during the last decade - and we’re just getting started. To advocate to your elected officials to PB, get their contact info <a href="https://myreps.participatorybudgeting.org" target="_blank" style="text-decoration: underline;">here.</a></h4>
                    </div>
                </div>
                @endif
                <div class="panel m-15 content-panel">

                    <div class="panel-body p-0">
                        <div class="example table-responsive">
                            <table class="table"  id="test_table" data-pagination="true" data-show-columns="true">
                                <thead>
                                  <tr>
                                    <th class="text-center">Status</th>
                                    <th class="pr-20">Name</th>
                                    <!-- <th data-breakpoints="all">@sortablelink('cost_num', 'Price')</th>
                                    <th data-breakpoints="all">@sortablelink('process.vote_year', 'Year')</th>
                                    <th data-breakpoints="all">@sortablelink('votes', 'Votes')</th>
                                    <th data-breakpoints="all">@sortablelink('status_date_updated', 'Update')</th>
                                    <th data-toggle="true"></th> -->
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr> 
                                        <td class="text-center">
                                            @if($project->project_status_category!='')
                                                @if($project->project_status_category=='Complete')
                                                    <button type="button" class="btn btn-floating btn-success btn-xs waves-effect waves-classic"><i class="icon fa-check" aria-hidden="true"></i></button>
                                                @elseif($project->project_status_category=='Project Status Needed')
                                                    <button type="button" class="btn btn-floating  btn-xs waves-effect waves-classic"></button>
                                                @elseif($project->project_status_category=='Not funded')
                                                    <button type="button" class="btn btn-floating btn-danger btn-xs waves-effect waves-classic"><i class="icon fa-remove" aria-hidden="true"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-floating btn-warning btn-xs waves-effect waves-classic"><i class="icon fa-minus" aria-hidden="true"></i></button>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="pr-30">
                                            @if($project->project_title!='')
                                                <a href="/profile/{{$project->id}}">{{$project->project_title}}</a>
                                            @endif
                                        </td>
                                        <!-- <td>
                                            @if($project->cost_num!='')
                                                ${{number_format($project->cost_num)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->process->vote_year!='')
                                                {{$project->process->vote_year}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->votes!='')
                                                {{$project->votes}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->status_date_updated!='')
                                                {{$project->status_date_updated}}
                                            @endif
                                        </td> -->
                                        <td><i class="fa fa-chevron-right" style="padding-top: 8px;color: #000000;"></i></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td colspan="5">
                                      <div class="text-right">
                                        <ul class="pagination">
                                            
                                        </ul>
                                      </div>
                                    </td>
                                  </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="pagination">
                        {{ $projects->appends(\Request::except('page'))->render() }}
                        </div>
                    </div>
                    <!-- End Example Striped Rows -->
                </div>
            </div>
            <div class="col-md-4 p-0">
                <div id="map" style="position: fixed !important;width: 28%;"></div>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
    var address_district = <?php echo json_encode($address_district); ?>;
    if( address_district != ''){
    
        $('#btn-district span').html("District: "+address_district);
        $('#btn-district').show();
    };
});
</script>
<script>
    $(document).ready(function(){
        if(screen.width < 768){
          var text= $('.navbar-container').css('height');
          var height = text.slice(0, -2);
          $('.page').css('padding-top', height);
          $('#content').css('top', height);
        }
        else{
          var text= $('.navbar-container').css('height');
          var height = text.slice(0, -2);
          $('.page').css('margin-top', height);
        }
    });
</script>
<script>
    var locations = <?php print_r(json_encode($projects)) ?>;
    var sumlat = 0.0;
    var sumlng = 0.0;
    for(var i = 0; i < locations['data'].length; i ++)
    {
        sumlat += parseFloat(locations['data'][i].latitude);
        sumlng += parseFloat(locations['data'][i].longitude);

    }
    var avglat = sumlat/locations['data'].length;
    var avglng = sumlng/locations['data'].length;
    var mymap = new GMaps({
      el: '#map',
      lat: avglat,
      lng: avglng,
      zoom:10
    });


    $.each( locations['data'], function(index, value ){
        var icon;
        if(value.project_status_category == "Complete")
            icon = '<button type="button" class="btn btn-floating btn-success btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-check" aria-hidden="true"></i></button>';
        else if(value.project_status_category == "Project Status Needed")
            icon = '<button type="button" class="btn btn-floating  btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"></button>';
        else if(value.project_status_category == "Not funded")
            icon = '<button type="button" class="btn btn-floating btn-danger btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-remove" aria-hidden="true"></i></button>';
        else
            icon ='<button type="button" class="btn btn-floating btn-warning btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-minus" aria-hidden="true"></i></button>';

        mymap.addMarker({
            lat: value.latitude,
            lng: value.longitude,
            title: value.city,
                   
            infoWindow: {
                maxWidth: 250,
                content: ('<a href="/profile/'+value.id+'" style="color:#424242;font-weight:500;font-size:14px;">'+icon+value.project_title+'</a>')
            }
        });
   });
</script>
@endsection


