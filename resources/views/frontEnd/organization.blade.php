@extends('layouts.app')
@section('title')
{{$organization->organization_name}}
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
		<div class="row m-0">
        	<div class="col-md-8 pt-15 pb-15 pl-30">
               <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
							<a href="">@if($organization->organization_logo_x)<img src="{{$organization->organization_logo_x}}" height="80">@endif {{$organization->organization_name}} @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif
							</a>
                        </h4>

                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Status:</b></span> 
							{{$organization->organization_status_x}}
						</h4>

                        {{-- <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}</h4> --}}

                        <h4 style="line-height:inherit"> {{$organization->organization_description}}</h4>

                        <h4 style="line-height: inherit;">
                        	<span><i class="icon md-globe font-size-24 vertical-align-top  mr-5 pr-10"></i>
								<a href="{{$organization->organization_url}}" > {{$organization->organization_url}}</a>
							</span> 
						</h4>

                        @if($organization->organization_phones!='')
						<h4 style="line-height: inherit;">
                        	<span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i>
								@foreach($organization->phones as $phone)
								{!! $phone->phone_number !!}, 
								@endforeach
							</span> 
                        </h4>
                        @endif

                        @if(isset($organization->organization_forms_x_filename))
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Referral Forms:</b></span>
							<a href="{{$organization->organization_forms_x_url}}" class="panel-link"> {{$organization->organization_forms_x_filename}}</a>
						</h4>
                        @endif

                    </div>
                </div>

                @if(isset($organization->services))
                <h4 class="p-15 m-0 text-left bg-secondary" style=" border-radius:0; font-size:20px; background: #3f51b5;color: #fff;">Services (@if(isset($organization->services)){{$organization->services->count()}}@else 0 @endif)</h4>
                @foreach($organization->services as $service)
                    <div class="card">
					
						<div class="card-block">
							<h4 class="card-title">
								<a href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
							</h4>
							<h4 style="line-height: inherit;">{!! str_limit($service->service_description, 200) !!}</h4>
                           
                            <h4 style="line-height: inherit;">
								<span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i>
								@foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span>
							</h4>
							<h4>
								<span>
								<i class="icon md-pin font-size-24 vertical-align-top  mr-5 pr-10"></i>
                                @if(isset($service->address))
                                    @foreach($service->address as $address)
                                      {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                    @endforeach
								@endif
								</span>
                            </h4>
                            
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
                                <h4><span class="badge bg-red"><b>{{ $detail['detail_type'] }}:</b></span> {!! $detail['detail_value'] !!}</h4>  
                              @endforeach
							@endif
							               <h4 class="py-10" style="line-height: inherit;">
                                <span class="pl-0 category_badge"><b>Types of People:</b>
                                    @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                        @php 
                                            $names = [];
                                        @endphp
                                        @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                            
                                            @if($taxonomy->taxonomy_parent_name == 'Target Populations')
                                                @if(!in_array($taxonomy->taxonomy_name, $names))
                                                    @if($taxonomy->taxonomy_name)
                                                        <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}" at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                                        @php
                                                        $names[] = $taxonomy->taxonomy_name;
                                                        @endphp
                                                    @endif
                                                @endif                                                    
                                            @endif
                                        @endforeach
                                    @endif
                                </span> 
                                <br>
                                <span class="pl-0 category_badge"><b>Types of Services:</b>
                                    @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                        @php 
                                            $names = [];
                                        @endphp
                                        @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                            @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                                @if($taxonomy->taxonomy_grandparent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_grandparent_name;
                                                    @endphp
                                                @endif
                                            @endif
                                            @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                                @if($taxonomy->taxonomy_parent_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    @if($taxonomy->taxonomy_grandparent_name)
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}">{{$taxonomy->taxonomy_parent_name}}</a>
                                                    @endif
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_parent_name;
                                                    @endphp
                                                @endif
                                            @endif
                                            @if(!in_array($taxonomy->taxonomy_name, $names))
                                                @if($taxonomy->taxonomy_name && $taxonomy->taxonomy_parent_name != 'Target Populations')
                                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}" at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                                    @php
                                                    $names[] = $taxonomy->taxonomy_name;
                                                    @endphp
                                                @endif
                                            @endif                                                    
                                           
                                        @endforeach
                                    @endif
                                </span>  
                              </h4>
                        </div>
                    </div>
                    @endforeach
                  @endif
            </div>
            
            <div class="col-md-4 property">
				<div class="pt-10 pb-10 pl-0 btn-download">
					<a href="/download_organization/{{$organization->organization_recordid}}" class="btn btn-primary btn-button">Download PDF</a>
					<button type="button" class="btn btn-primary btn-button" style="padding: 1px;">
              <div class="sharethis-inline-share-buttons"></div>
          </button>
				</div>
				
				<div class="card">
					<div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
					<div class="card-block">
						<div class="p-10">
						@if(isset($organization->location))
							@foreach($organization->location as $location)
							<h4>
								<span><i class="icon fas fa-building font-size-24 vertical-align-top  "></i>
									{{$location->location_name}}
								</span> 
							</h4>
							<h4>
								<span><i class="icon md-pin font-size-24 vertical-align-top  "></i>
									@if(isset($location->address))
										@foreach($location->address as $address)
										{{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
										@endforeach
									@endif
								</span> 
							</h4>
							<h4>
								<span><i class="icon md-phone font-size-24 vertical-align-top  "></i>
									@foreach($location->phones as $phone)
									@php 
										$phones ='';
										$phones = $phones.$phone->phone_number.','; @endphp
									@endforeach
                  @if(isset($phones))
									{{ rtrim($phones, ',') }}
                  @endif
								</span>
							</h4>
							@endforeach
						@endif
						</div>
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
      var maplocation = <?php print_r(json_encode($map)) ?>;
      console.log(locations);

      if(maplocation.active == 1){
        avglat = maplocation.lat;
        avglng = maplocation.long;
        zoom = maplocation.zoom;
      }
      else
      {
          avglat = 40.730981;
          avglng = -73.998107;
          zoom = 12;
      }

      latitude = locations[0].location_latitude;
      longitude = locations[0].location_longitude;

      if(latitude == null){
        latitude = avglat;
        longitude = avglng;
      }

    
      var mymap = new GMaps({
        el: '#map',
        lat: latitude,
        lng: longitude,
        zoom: zoom
      });


      $.each( locations, function(index, value ){
            // console.log(locations);
            var name = value.organization==null?'':value.organization.organization_name;
            var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
            var service_name = value.services.length == 0?'':value.services[0].service_name;

            var content = "";
            for(i = 0; i < value.services.length; i ++){
                content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
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
    var showChar = 250;
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

    $('.panel-link').on('click', function(e){
          if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
              return;
          var id = $(this).attr('at');
          console.log(id);
          $("#category_" +  id).prop( "checked", true );
          $("#checked_" +  id).prop( "checked", true );
          $("#filter").submit();
      });
      
      $('.panel-link.target-population-link').on('click', function(e){
          $("#target_all").val("all");
          $("#filter").submit();
      });

      $('.panel-link.target-population-child').on('click', function(e){
          var id = $(this).attr('at');
          $("#target_multiple").val(id);
          $("#filter").submit();

      });
  });
</script>
@endsection


