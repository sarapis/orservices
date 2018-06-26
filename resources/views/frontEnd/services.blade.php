@extends('layouts.app')
@section('title')
Services
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

            <div class="col-md-8 pt-15 pr-0">
                @foreach($services as $service)

                <div class="panel content-panel">
                    <div class="panel-body p-20">
                        
                        <a class="panel-link" href="/service_{{$service->service_recordid}}">{{$service->service_name}}</a>
                        @if($service->service_taxonomy!=0)
                        <h4><span class="badge bg-red">Category:</span> <a class="panel-link" href="/category_{{$service->taxonomy()->first()->taxonomy_recordid}}">{{$service->taxonomy()->first()->taxonomy_name}}</a></h4>
                        @endif
                        @if($service->service_organization!=null)
                        <h4><span class="badge bg-red">Organization:</span><a class="panel-link" href="/organization_{{$service->organization()->first()->organization_recordid}}"> {{$service->organization()->first()->organization_name}}</a></h4>
                        @endif
                        <h4><span class="badge bg-red">Phone:</span> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</h4>
                        <h4><span class="badge bg-blue">Address:</span>
                            @if($service->service_address!=NULL)
                                @foreach($service->address as $address)
                                  {{ $address->address_1 }}
                                @endforeach
                            @endif
                        </h4>
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


<script>
    
    var locations = <?php print_r(json_encode($locations)) ?>;
    // console.log(locations);

    var sumlat = 0.0;
    var sumlng = 0.0;
    var length = 0;
    // console.log(locations.length);
    for(var i = 0; i < locations.length; i ++)
    {
        if(locations[i].location_latitude)
        {
            sumlat += parseFloat(locations[i].location_latitude);
            sumlng += parseFloat(locations[i].location_longitude);
            length ++;
        }
    }
    if(length != 0){
        var avglat = sumlat/length;
        var avglng = sumlng/length;
    }
    else
    {
        avglat = 40.730981;
        avglng = -73.998107;
    }
    // console.log(avglat);
    var mymap = new GMaps({
      el: '#map',
      lat: avglat,
      lng: avglng,
      zoom:10
    });


    $.each( locations, function(index, value ){
        // console.log(value);
        if(value.location_latitude){
            mymap.addMarker({

                lat: value.location_latitude,
                lng: value.location_longitude,
                title: value.city,
                       
                infoWindow: {
                    maxWidth: 250,
                    content: ('<a href="/service_'+value.services[0].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[0].service_name+'</a><br><p>'+value.organization.organization_name+'</p>')
                }
            });
        }
   });
</script>
@endsection


