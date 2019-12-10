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

/*#map{
    position: fixed !important;
}*/
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
        <!-- Example Striped Rows -->
        <div class="col-md-8 pt-15 pb-15 pl-15">
            <div class="btn-group dropdown btn-feature">
                <button type="button" class="btn btn-primary dropdown-toggle btn-button" id="exampleBulletDropdown1" data-toggle="dropdown" aria-expanded="false">
                    Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown1" role="menu">
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                </div>
            </div>
            <div class="btn-group dropdown btn-feature">
                <button type="button" class="btn btn-primary dropdown-toggle btn-button"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
                    Results Per Page
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
                    <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                    <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                    <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                </div>
            </div>
            <div class="btn-group dropdown btn-feature">
                <button type="button" class="btn btn-primary dropdown-toggle btn-button"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
                    Sort
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
                    <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>                    
                    <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                    @if($sort_by_distance_clickable)
                    <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem" >Distance from Address</a>
                    @endif

                </div>
            </div>

            <div class="btn-group btn-feature">
                <button type="button" class="btn btn-primary btn-button" style="padding: 1px;">
                    <div class="sharethis-inline-share-buttons"></div>
                </button>
            </div>
            
            <div class="btn-group btn-feature">
                @if(isset($search_results))
                <p class="m-0 btn btn-primary btn-button">Results: {{$search_results}}</p>
                @endif
            </div>
            
        </div>
        <div class="col-sm-12">
            <div class="row" >
                <div class="col-md-8">
                    @if (session('address'))
                        <div class="alert dark alert-danger alert-dismissible ml-15" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <b>{{ session('address') }}</b>
                        </div>
                    @endif
                    @if(count($services) != 0)                        
                        @if(isset($sort) && $sort ="Organization Name")
                            @foreach($services->sortBy('organization_name') as $service)
                                @if($service->service_name != null)
                                <div class="card">
                                    <div class="card-block">
                                        <h4 class="card-title">
                                            <a href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                        </h4>
                                        <h4><span class=""><b>Organization:</b></span>
                                            @if(isset($service->organizations))                        
                                                <a class="panel-link" class="notranslate" href="/organization/{{$service->organizations()->first()->organization_recordid}}"> {{$service->organizations()->first()->organization_name}}</a>                    
                                            @endif
                                        </h4>
                                        <h4  style="line-height: inherit;">{!! str_limit(str_replace(array('\n', '/n', '*'), array(' ', ' ', ' '), $service->service_description), 200) !!}</h4>
                                        <h4><span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span></h4>
                                        <h4><span><i class="icon md-pin font-size-24 vertical-align-top mr-5 pr-10"></i>
                                            @if(isset($service->address))
                                                @foreach($service->address as $address)
                                                {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                                @endforeach
                                            @endif
                                            </span>
                                        </h4>
                                        <h4>
                                            <span class="pl-0 category_badge"><b>Types of Services:</b>
                                                @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                                    @php 
                                                        $names = [];
                                                    @endphp
                                                    @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                                        @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                                            @if($taxonomy->taxonomy_grandparent_name)
                                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                                                @php
                                                                $names[] = $taxonomy->taxonomy_grandparent_name;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                        @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                                            @if($taxonomy->taxonomy_parent_name)
                                                                @if($taxonomy->taxonomy_parent_name == 'Target Populations')
                                                                <a class="{{str_replace(' ', '_', $taxonomy->taxonomy_name)}} panel-link target-population-child" at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                                                @elseif($taxonomy->taxonomy_grandparent_name)
                                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}" at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}">{{$taxonomy->taxonomy_parent_name}}</a>
                                                                @endif
                                                                @php
                                                                $names[] = $taxonomy->taxonomy_parent_name;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                        @if(!in_array($taxonomy->taxonomy_name, $names))
                                                            @if($taxonomy->taxonomy_name)
                                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}" at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                                                @php
                                                                $names[] = $taxonomy->taxonomy_name;
                                                                @endphp
                                                            @endif
                                                        @endif                                                    
                                                       
                                                    @endforeach
                                                @endif
                                            </span> 
                                            <br>
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
                                        </h4>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @else
                            @foreach($services->sortBy('service_name') as $service)
                                @if($service->service_name != null)
                                <div class="card">
                                    <div class="card-block">
                                        <h4 class="card-title">
                                            <a href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                        </h4>
                                        <h4><span class=""><b>Organization:</b></span>
                                            @if(isset($service->organizations))                        
                                                <a class="panel-link" class="notranslate" href="/organization/{{$service->organizations()->first()->organization_recordid}}"> {{$service->organizations()->first()->organization_name}}</a>                    
                                            @endif
                                        </h4>
                                        <h4  style="line-height: inherit;">{!! str_limit(str_replace(array('\n', '/n', '*'), array(' ', ' ', ' '), $service->service_description), 200) !!}</h4>
                                        <h4><span><i class="icon md-phone font-size-24 vertical-align-top  mr-5 pr-10"></i> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span></h4>
                                        <h4><span><i class="icon md-pin font-size-24 vertical-align-top mr-5 pr-10"></i>
                                            @if(isset($service->address))
                                                @foreach($service->address as $address)
                                                {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                                @endforeach
                                            @endif
                                            </span>
                                        </h4>
                                        <h4>
                                            <span class="pl-0 category_badge"><b>Types of Services:</b>
                                                @if($service->service_taxonomy != null)
                                                    @php $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
                                                    @endphp
                                                    @foreach($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid)
                                                        @php $taxonomy_name = $service_taxonomy_info_list[$service_taxonomy_recordid]; 
                                                        @endphp
                                                        @if($taxonomy_name)
                                                            <a class="panel-link {{str_replace(' ', '_', $taxonomy_name)}}" at="{{$service_taxonomy_recordid}}">{{$taxonomy_name}}</a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <div class="alert dark alert-warning ml-15" role="alert" style="background-color: lightblue; border-color: lightblue;">
                            <span style="color: #ffffff;">
                                <b>We’re unable to find any services based on your search.</b>
                            </span>
                            <p style="color: #ffffff;">
                                <b>To improve the search results, we suggest:</b>
                            </p>
                            <ul>
                                <li style="color: #ffffff; list-style: disc;">Remove any location filters</li>
                                <li style="color: #ffffff; list-style: disc;">Use more general terms in the search bar</li>
                                <li style="color: #ffffff; list-style: disc;">Click on Types of Services on the left side of the screen instead of using the search field</li>                            
                            </ul>
                        </div>
                    @endif
                    <div class="example">
                        <nav>
                            {{ $services->appends(\Request::except('page'))->render() }}
                        </nav>
                    </div>
                </div>
                
                <div class="col-md-4 property">
                    <div class="panel">
                        <div class="panel-body p-0">
                            <div id="map" style="width: 100%; height: 50vh;">
                            </div>
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
            var locations = <?php print_r(json_encode($locations)) ?>;
            var maplocation = <?php print_r(json_encode($map)) ?>;
            // console.log(map);

            var sumlat = 0.0;
            var sumlng = 0.0;
            var length = 0;
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
                zoom = 14;
            }
            console.log(maplocation.active);
            var mymap = new GMaps({
              el: '#map',
              lat: avglat,
              lng: avglng,
              zoom: zoom
            });

        

            $.each( locations, function(index, value ){
                // console.log(locations);
                var name = value.organization==null?'':value.organization.organization_name;
 

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
        }, 2000);

        $('.panel-link').on('click', function(e){
            if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
                return;
            var id = $(this).attr('at');
            console.log(id);
            selected_taxonomy_ids = id.toString();
            $("#selected_taxonomies").val(selected_taxonomy_ids);
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


