@extends('layouts.app')
@section('title')
Organization
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
    height: 30vh !important;
}
.morecontent span {
  display: none;

}
.morelink{
  color: #428bca;
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
    
                <div class="panel ml-15 mr-15 mb-15">
                    <div class="panel-body p-20">
                        <h2><img src="{{$organization->organization_logo_x}}" height="80"> {{$organization->organization_name}} @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif</h2>

                        <h4 class="panel-text"><span class="badge bg-red">Status:</span> {{$organization->organization_status_x}}</h4>

                        <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}</h4>

                        <h4 class="panel-text"><span class="badge bg-red">Description:</span> <span class="comment more"> {{$organization->organization_description}}</span></h4>

                        <h4 class="panel-text"><span class="badge bg-red">Website:</span> <a href="{{$organization->organization_url}}" class="panel-link"> {{$organization->organization_url}}</a></h4>

                        @if($organization->organization_phones!='')
                        <h4 class="panel-text"><span class="badge bg-red">Main Phone:</span> @foreach($organization->phones as $phone)
                           {!! $phone->phone_number !!}, 
                        @endforeach
                        </h4>
                        @endif

                        <h4 class="panel-text"><span class="badge bg-red">Referral Forms:</span> <a href="{{$organization->organization_forms_x_url}}" class="panel-link"> {{$organization->organization_forms_x_filename}}</a></h4>

                    </div>
                  </div>

                  @if($organization->organization_services!=null)
                    @foreach($organization->services as $service)
                    <div class="panel content-panel">
                        <div class="panel-body p-20">

                            <h4><span class="badge bg-red">Service:</span><a class="panel-link" href="/service_{{$service->service_recordid}}"> {{$service->service_name}}</a></h4>

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

                            <h4><span class="badge bg-red">Phone:</span> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</h4>
                            <h4><span class="badge bg-blue">Address:</span>
                                @if($service->service_address!=NULL)
                                    @foreach($service->address as $address)
                                      {{ $address->address_1 }}
                                    @endforeach
                                @endif
                            </h4>
                            <h4><span class="badge bg-blue">Description:</span> {!! str_limit($service->service_description, 200) !!}</h4>

                            @if($service->service_details!=NULL)
                                @php
                                    $show_details = [];
                                @endphp
                              @foreach($service->details->sortBy('detail_type') as $detail)
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
                            
                        </div>
                    </div>
                    @endforeach
                  @endif
              
            </div>
            
            <div class="col-md-4 p-0 pr-15">
                <div class="pb-10 pt-20">
                    <a href="/download_organization/{{$organization->organization_recordid}}"><button type="button" class="btn btn-info btn-sort btn-button">Download PDF</button></a>
                </div>
                <div id="map" style="width: 100%; margin-top: 0;"></div>
                
               
                  <hr>
                  <div class="panel m-0 mt-5">
                      <div class="panel-body p-20">
                       @if($organization->organization_locations!='')
                          @foreach($organization->location as $location)
                          

                                  <h4><span class="badge bg-red">Location:</span> {{$location->location_name}}</h4>
                                  <h4><span class="badge bg-red">Address:</span> @if($location->location_address!='')
                                    @foreach($location->address as $address)
                                      {{ $address->address_1 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                    @endforeach
                                  @endif
                                  </h4>
                                  <h4><span class="badge bg-red">Phone:</span>
                                     @foreach($location->phones as $phone)
                                      @php 
                                        $phones ='';
                                        $phones = $phones.$phone->phone_number.','; @endphp
                                     @endforeach
                                     {{ rtrim($phones, ',') }}
                                  </h4>
                              
                          @endforeach
                        @endif
                    
                  </div>
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
      var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
      // console.log(locations);

      var sumlat = 0.0;
      var sumlng = 0.0;
      var length = 0;
      console.log(locations.length);
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
          if(maplocation.active == 1){
          avglat = maplocation.lat;
          avglng = maplocation.long;
          }
          else
          {
              avglat = 40.730981;
              avglng = -73.998107;
          }
      }
    
      var mymap = new GMaps({
        el: '#map',
        lat: avglat,
        lng: avglng,
        zoom:12
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

  $(document).ready(function() {
    var showChar = 300;
    var ellipsestext = "...";
    var moretext = "More";
    var lesstext = "Less";
    $('.more').each(function() {
      var content = $(this).html();

      if(content.length > showChar) {

        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);

        var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

        $(this).html(html);
      }

    });

    $(".morelink").click(function(){
      if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
      } else {
        $(this).addClass("less");
        $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });
  });
</script>
@endsection


