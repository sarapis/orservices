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
<div class="wrapper">
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <div class="alert alert-alt alert-success alert-dismissible alert-chip" role="alert">
                  {{$chip_title}}: <a class="alert-link">{{$chip_name}}</a>
            </div>
            <div class="col-md-12 p-0">
                <div class="col-md-8 pt-15 pr-0">
                    @foreach($services as $service)
                    <div class="panel content-panel">
                        <div class="panel-body p-20">
                            <h4>{{$service->taxonomy()->first()->taxonomy_name}}</h4>
                            <h4>{{$service->service_name}}</h4>
                            <h4>Provided by: {{$service->organization()->first()->organization_name}}</h4>
                            <h4><span class="badge bg-red">Phone:</span> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</h4>
                            <h4><span class="badge bg-blue">Address:</span> @if($service->service_contacts!=NULL) {{$service->contact()->first()->contact_name}} @endif</h4>
                            <h4><span class="badge bg-blue">Description:</span> {!! str_limit($service->service_description, 200) !!}</h4>
                        </div>
                    </div>
                    @endforeach
                    <div class="pagination p-20">
                        {{ $services->appends(\Request::except('page'))->render() }}
                    </div>
                </div>
                
                <div class="col-md-4 p-0">
                    <div id="map" style="position: fixed !important;width: 28%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
   
    if( address_district != ''){
    
        $('#btn-district span').html("District: "+address_district);
        $('#btn-district').show();
    };
});
</script>
<script>
    $(document).ready(function(){
        if(screen.width < 768){
          var text= $('.navbar-header').css('height');
          var height = text.slice(0, -2);
          $('.page').css('padding-top', height);
          $('#content').css('top', height);
        }
        else{
          var text= $('.navbar-header').css('height');
          var height = 0;
          $('.page').css('margin-top', height);
        }
    });
</script>
<script>
    
    var locations = <?php print_r(json_encode($locations)) ?>;
    console.log(locations);
    var sumlat = 0.0;
    var sumlng = 0.0;
    for(var i = 0; i < locations.length; i ++)
    {
        sumlat += parseFloat(locations[i].location_latitude);
        sumlng += parseFloat(locations[i].location_longitude);

    }
    var avglat = sumlat/locations.length;
    var avglng = sumlng/locations.length;
    var mymap = new GMaps({
      el: '#map',
      lat: avglat,
      lng: avglng,
      zoom:10
    });


    $.each( locations, function(index, value ){

        mymap.addMarker({
            lat: value.location_latitude,
            lng: value.location_longitude,
            title: value.city,
                   
            infoWindow: {
                maxWidth: 250,
                content: ('<a href="/service_'+value.service.service_recordid+'" style="color:#424242;font-weight:500;font-size:14px;">'+value.service.service_name+'<br>'+value.organization.organization_name+'</a>')
            }
        });
   });
</script>
@endsection


