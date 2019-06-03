@extends('layouts.app')
@section('title')
Service
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
    position: relative !important;
    z-index: 0 !important;
}
@media (max-width: 768px) {
    .property{
        padding-left: 30px !important;
    }
    #map{
        display: block !important;
        width: 100% !important;
    }
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
            <div class="col-md-8 pt-15 pr-0">
    
                <div class="panel ml-15 mr-15">
                    <div class="panel-body p-20">
                        <h2>{{$service->service_name}}</h2>
                        <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$service->service_alternate_name}}</h4>

                         <h4 class="panel-text"><span class="badge bg-red">Category:</span> 
                            @if($service->service_taxonomy!=0)
                                @foreach($service->taxonomy as $key => $taxonomy)
                                    @if($loop->last)
                                    <a class="panel-link" href="/category_{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>                                    @else
                                    <a class="panel-link" href="/category_{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>,
                                    @endif
                                @endforeach
                            @endif    
                        </h4>
                        <h4 class="panel-text"><span class="badge bg-red">Organization:</span>
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

                        <h4 class="panel-text"><span class="badge bg-blue">Description:</span> {!! $service->service_description !!}</h4>

                        <h4 class="panel-text"><span class="badge bg-red">Phone:</span>
                            @if($service->service_phones!=0)                        
                                @foreach($service->phone as $phone)
                                    @if($loop->last)
                                    {{$phone->phone_number}}
                                    @else
                                    {{$phone->phone_number}},
                                    @endif
                                @endforeach                       
                            @endif
                        </h4>

                        <h4 class="panel-text"><span class="badge bg-red">Extension:</span>
                            @if($service->service_phones!=0)
                                @foreach($service->phone as $phone) {!! $phone->phone_extension !!} @endforeach
                            @endif  
                        </h4>

                        <h4 class="panel-text" style="word-wrap: break-word;"><span class="badge bg-blue" >Url:</span> @if($service->service_url!=NULL)<a href="{!! $service->service_url !!}">{!! $service->service_url !!}</a> @endif</h4>

                        @if($service->service_email!=NULL) 
                        <h4 class="panel-text"><span class="badge bg-blue">Email:</span> {{$service->service_email}}</h4>
                        @endif  
                        <hr>
                        
                            @if($service->service_details!=NULL)
                                @php
                                    $show_details = [];
                                @endphp
                              @foreach($service->details as $detail)
                                @php
                                    for($i = 0; $i < count($show_details); $i ++){
                                        if($show_details[$i]['detail_type'] == $detail->detail_type)
                                            break;
                                    }
                                    if($i == count($show_details)){
                                        $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=> $detail->detail_value);
                                    }
                                    else{
                                        $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].', '.$detail->detail_value;
                                    }
                                @endphp                                
                              @endforeach
                              @foreach($show_details as $detail)
                                <h4><span class="badge bg-red">{{ $detail['detail_type'] }}:</span> {!! $detail['detail_value'] !!}</h4>  
                              @endforeach
                            @endif

                        @if($service->service_application_process || $service->service_wait_time || $service->service_fees || $service->service_accreditations || $service->service_licenses)
                        <hr>
                        <h3>Additional Info</h3>
                        @endif

                        @if($service->service_application_process)
                        <h4 class="panel-text"><span class="badge bg-blue">Application Process:</span> {!! $service->service_application_process !!}</h4>
                        @endif

                        @if($service->service_wait_time)
                        <h4 class="panel-text"><span class="badge bg-blue">Wait Time:</span> {{$service->service_wait_time}}</h4>
                        @endif

                        @if($service->service_fees)
                        <h4 class="panel-text"><span class="badge bg-blue">Fees:</span> {{$service->service_fees}}</h4>
                        @endif

                        @if($service->service_accreditations)
                        <h4 class="panel-text"><span class="badge bg-blue">Accreditations:</span> {{$service->service_accreditations}}</h4>
                        @endif

                        @if($service->service_licenses)
                        <h4 class="panel-text"><span class="badge bg-blue">Licenses:</span> {{$service->service_licenses}}</h4>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 pt-15 property">
                
                <div class="panel">
                    <div class="panel-body p-0">
                        <div class="p-10">
                            <a href="/download_service/{{$service->service_recordid}}"><button type="button" class="btn btn-info btn-sort">Download PDF</button></a>
                        </div>
                        <div id="map" style="width:initial;margin: 0;height: 50vh;"></div>
                        <hr>
                        @if($service->service_address!=NULL)
                        <div class="p-20">
                            <h4><span class="badge bg-blue">Address:</span>
                                
                                    @foreach($service->address as $address)
                                       <br>{{ $address->address_1 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                    @endforeach
                                
                            </h4>
                            @endif
                            @if($service->service_contacts!=0)
                            <h4><span class="badge bg-red">Contact:</span>
                              
                                {{$service->contact()->first()->contact_name}}
                            
                            </h4>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    setTimeout(function(){
        var locations = <?php print_r(json_encode($location)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;

        console.log(locations);
        var show = 1;
        if(locations.length == 0){
          show = 0;
        }

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
        }

        var mymap = new GMaps({
          el: '#map',
          lat: avglat,
          lng: avglng,
          zoom:10
        });

        if(show == 1){
          $.each( locations, function(index, value ){
              mymap.addMarker({
                  lat: value.location_latitude,
                  lng: value.location_longitude,
                  title: value.location_name
                         
                  
              });
         });
        }
    }, 2000)
});


</script>
@endsection


