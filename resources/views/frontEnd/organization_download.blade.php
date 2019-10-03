
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

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
    height: 200px;
}
</style>


<div class="wrapper">

    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <h3>{{$layout->header_pdf}}</h3>
            <div class="col-md-8 pt-15 pr-0">
                <div class="panel ml-15 mr-15 mb-15">
                    <div class="panel-body p-20">
                        <h2>{{$organization->organization_name}} @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif</h2>

                        <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}</h4>

                        <h4 class="panel-text"><span class="badge bg-red">Description:</span> {{$organization->organization_description}}</h4>

                        <h4 class="panel-text"><span class="badge bg-red">Website:</span> <a href="{{$organization->organization_url}}"> {{$organization->organization_url}}</a></h4>

                        @if($organization->organization_phones!='')
                        <h4 class="panel-text"><span class="badge bg-red">Main Phone:</span> @foreach($organization->phones as $phone)
                           {!! $phone->phone_number !!}, 
                        @endforeach</h4>
                        @endif

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
                                {{$phone->phone_number}},
                               @endforeach
                            </h4>

                          @endforeach
                        @endif
                    </div>
                </div>
                <hr>
                @if($organization->organization_services!=null)
                  @foreach($organization->services as $service)
                  <div class="panel content-panel">
                      <div class="panel-body p-20">

                          <h3><span class="badge bg-red">Service:</span><a class="panel-link" href="{{ config('app.url')}}/service/{{$service->service_recordid}}"> {{$service->service_name}}</a></h3>

                          <h4><span class="badge bg-red">Category:</span> 
                              @if($service->service_taxonomy!=0)
                                  @foreach($service->taxonomy as $key => $taxonomy)
                                      @if($loop->last)
                                      <a class="panel-link" href="{{ config('app.url')}}/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>                                    @else
                                      <a class="panel-link" href="{{ config('app.url')}}/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>,
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
                          <h4><span class="badge bg-blue">Description:</span> {!! $service->service_description !!}</h4>
                      </div>
                  </div>
                  <hr>
                  @endforeach
                @endif
              
            </div>
            <h3>{{$layout->footer_pdf}}</h3>
        </div>
    </div>
</div>




