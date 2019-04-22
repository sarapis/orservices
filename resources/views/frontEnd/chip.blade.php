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
@include('layouts.filter')
<div class="wrapper">
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <div class="alert alert-alt alert-success alert-dismissible alert-chip" role="alert">
                  {{$chip_title}} <a class="alert-link">{{$chip_name}}</a>
            </div>
            <div class="col-md-12 p-0">
                <div class="col-md-8 pt-15 pr-0">
                    @foreach($services as $service)

                    <div class="panel content-panel">
                        <div class="panel-body p-20">
                            
                            <a class="panel-link" href="/service_{{$service->service_recordid}}">{{$service->service_name}}</a>

                            <h4><span class="badge bg-red">Category:</span> 
                                @if($service->service_taxonomy!=0)
                                    @foreach($service->taxonomy as $key => $taxonomy)
                                        @if($loop->last)
                                        <a class="panel-link" href="/category_{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>                                    @else
                                        <a class="panel-link" href="/category_{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>,
                                        @endif
                                    @endforeach
                                @endif    
                            </h4>
                            <h4><span class="badge bg-red">Organization:</span>
                                @if($service->service_organization!=0)                        
                                    @foreach($service->organizations as $organization)
                                        @if($loop->last)
                                        <a class="panel-link" href="/organization_{{$organization->organization_recordid}}"> {{$organization->organization_name}}</a>
                                        @else
                                        <a class="panel-link" href="/organization_{{$organization->organization_recordid}}"> {{$organization->organization_name}}</a>,
                                        @endif
                                    @endforeach                       
                                @endif
                            </h4>

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
</div>

<!-- <script>
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
</script> -->
<script>
   $(document).ready(function(){
        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;
            var maplocation = <?php print_r(json_encode($map)) ?>;
            // console.log(locations);
            var avglat = 0.0;
            var avglat = 0.0;
            
            if(maplocation.active == 1){
                avglat = maplocation.lat;
                avglng = maplocation.long;
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
              zoom:11
            });


            $.each( locations, function(index, value ){
                // console.log(locations);
                var name = value.organization==null?'':value.organization.organization_name;
                var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
                var service_name = value.services.length == 0?'':value.services[0].service_name;

                var content = "";
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<a href="/service_'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
                }
                content += '<p>'+name+'</p>';

                if(value.location_latitude){
                    mymap.addMarker({

                        lat: value.location_latitude,
                        lng: value.location_longitude,
                        title: value.city,
                               
                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        }
                    });
                }
           });
        }, 2000)
   }); 
    
</script>
@endsection


