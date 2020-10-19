@extends('layouts.app')
@section('title')
Services
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

@section('content')
@include('layouts.filter')
<div>

    <!-- Page Content Holder -->
    <div class="top_services_filter">
        <div class="container">
            @include('layouts.sidebar')
            <!-- Types Of Services -->
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                        Types Of Services
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="sidebar_tree">
                    </div>
                </div>
            <!--end  Types Of Services -->

            <!-- Sort By -->
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
                        <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                        <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                        @if($sort_by_distance_clickable)
                        <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem" >Distance from Address</a>
                        @endif
                    </div>
                </div>
            <!--end  Sort By -->

            <!-- Results Per Page -->
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown3" data-toggle="dropdown" aria-expanded="false">
                    Results Per Page
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown3" role="menu">
                    <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                    <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                    <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                </div>
            </div>
            <!--end Results Per Page -->

            <!-- download -->
            <div class="dropdown btn_download float-right">
                <button type="button" class="float-right btn_share_download dropdown-toggle" id="exampleBulletDropdown4" data-toggle="dropdown" aria-expanded="false">
                    <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                </div>
            </div>
            {{-- <div class="btn-group dropdown btn-feature">
                <button type="button" class="btn btn-primary dropdown-toggle btn-button" id="exampleBulletDropdown1" data-toggle="dropdown" aria-expanded="false">
                    Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown1" role="menu">
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                </div>
            </div> --}}
            <!--end download -->

            <!-- share btn -->
            <button type="button" class="float-right btn_share_download">
                <img src="/frontend/assets/images/share.png" alt="" title="" class="mr-10 share_image">
                <div class="sharethis-inline-share-buttons"></div>
            </button>
            <!--end share btn -->
        </div>
    </div>
    <div class="inner_services">
        <div id="content" class="container">
            <!-- Example Striped Rows -->
            {{-- <div class="col-md-8 pt-15 pb-15 pl-15">
                <div class="btn-group btn-feature">
                    @if(isset($search_results))
                    <p class="m-0 btn btn-primary btn-button">Results: {{$search_results}}</p>
                    @endif
                </div>
            </div> --}}
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
                        @foreach($services as $service)
                            @if($service->service_name != null)
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title">
                                        <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                        <p style="float: right;">{{ isset($service->miles)  ? floatval(number_format($service->miles,2)) .' miles'  : '' }}</p>
                                    </h4>
                                    <h4 class="org_title"><span class="subtitle"><b>Organization:</b></span>
                                        @if(isset($service->organizations))
                                            <a class="panel-link" class="notranslate" href="/organizations/{{$service->organizations()->first()->organization_recordid}}"> {{$service->organizations()->first()->organization_name}}</a>
                                        @endif
                                    </h4>
                                    <h4  style="line-height: inherit;">{!! Str::limit(str_replace(array('\n', '/n', '*'), array(' ', ' ', ' '), $service->service_description), 200) !!}</h4>
                                    <h4>
                                        <span><i class="icon md-phone font-size-18 vertical-align-top  mr-5 pr-10"></i> @foreach($service->phone as $phone)
                                            <a href="tel:{{$phone->phone_number}}">
                                                {!! $phone->phone_number !!} @endforeach
                                            </a>
                                        </span>
                                    </h4>
                                    <h4><span><i class="icon md-pin font-size-18 vertical-align-top mr-5 pr-10"></i>
                                        @if(isset($service->address))
                                            @foreach($service->address as $address)
                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endforeach
                                        @endif
                                        {{-- @if(isset($service->address))
                                            @foreach($service->locations()->first()->address as $address)
                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endforeach
                                        @endif --}}

                                        </span>
                                    </h4>
                                    <h4>
                                        <span class="pl-0 category_badge subtitle"><b>Types of Services:</b>
                                            @if($service->service_taxonomy != null)
                                                @php $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
                                                @endphp
                                                @foreach($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid)
                                                    @php $taxonomy_name = $service_taxonomy_info_list[$service_taxonomy_recordid];
                                                    @endphp
                                                    @if($taxonomy_name)
                                                        <a class="panel-link {{str_replace(' ', '_', $taxonomy_name)}}" at="child_{{$service_taxonomy_recordid}}">{{$taxonomy_name}}</a>
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
                </div>

                <div class="col-md-4 property">
                    <div class="card">
                        <div class="card-block p-0">
                            <div id="map" style="width: 100%; height: 100vh;border-radius:12px;box-shadow: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="example col-md-12">
                    <div class="row">
                        <div class="col-md-6 pagination_text">
                            <p>Showing {{ $services->currentPage() * Request::get('paginate') - intval(Request::get('paginate') - 1)  }}-{{ $services->currentPage() * Request::get('paginate')  }} of {{ $services->total() }} items  <span>Show {{ Request::get('paginate') }} per page</span></p>
                        </div>
                        <div class="col-md-6 text-right">
                            {{ $services->appends(\Request::except('page'))->render() }}
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
            // var chip_address = <?php if(isset($chip_address)){print_r(json_encode($chip_address));}else{echo '0';} ?>;
            var chip_address = "{{isset($chip_address) ? $chip_address : '0'}}"
            // var avarageLatitude = <?php if(isset($avarageLatitude)){print_r(json_encode($avarageLatitude));}else{echo '0';} ?>;
            var avarageLatitude = "{{ isset($avarageLatitude) ? $avarageLatitude : '0'  }}"
            // var avarageLongitude = <?php if(isset($avarageLongitude)){print_r(json_encode($avarageLongitude));}else{echo '0';} ?>;
            var avarageLongitude = "{{ isset($avarageLongitude) ? $avarageLongitude : '0' }}"
            var sumlat = 0.0;
            var sumlng = 0.0;
            var length = 0;
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
            if(chip_address != '0' && chip_address != ''){
                zoom = 15
                if(avarageLatitude != '0'){
                    avglat = avarageLatitude
                }
                if(avarageLongitude != '0'){
                    avglng = avarageLongitude
                }
            }
            var mymap = new GMaps({
              el: '#map',
              lat: avglat,
              lng: avglng,
              zoom: zoom
            });
            let checkunKnownAddress = [];
            $.each( locations, function(index, value ){

                var name = value.organization==null?'':value.organization.organization_name;


                var content = '<div id="iw-container">';
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<div class="iw-title"> <a href="/services/'+value.services[i].service_recordid+'">'+value.services[i].service_name+'</a></div>';
                }
                if(value.organization){

                content += '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + value.organization.organization_recordid + '">' + value.organization.organization_name +'</a>';
                }
                if(value.address){
                    for(i = 0; i < value.address.length; i ++){
                        content +=  '<div class="iw-subTitle">Address</div>'+
                                '<a href="https://www.google.com/maps/dir/?api=1&destination=' + value.address[i].address_1 + '" target="_blank">' + value.address[i].address_1 +'</a>';
                    }
                }
                content += '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
                        '</div>';


                if(value.location_latitude){

                    if(chip_address != '0' && avarageLatitude != '0' && avarageLongitude != '0' && value.location_latitude == avarageLatitude && value.location_longitude == avarageLongitude){
                        checkunKnownAddress.push(1)
                        let url = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
                        mymap.addMarker({

                            lat: value.location_latitude,
                            lng: value.location_longitude,
                            title: value.city,

                            infoWindow: {
                                maxWidth: 250,
                                content: (content)
                            },
                            icon: {
                                  url: url,
                                  scaledSize: new google.maps.Size(40, 40), // size
                                }
                        });
                    }else{
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
                }
            });
            let content = '';
            if(chip_address != '0'){
                content =  '<div class="iw-content"><div class="iw-subTitle">Address</div>'+
                            '<a href="https://www.google.com/maps/dir/?api=1&destination=' + chip_address + '" target="_blank">' + chip_address +'</a></div>';
            }
            if(chip_address != '0' && avarageLatitude != '0' && avarageLongitude != '0' && checkunKnownAddress.length == 0){
                let url = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
                mymap.addMarker({

                    lat: avarageLatitude,
                    lng: avarageLongitude,

                    infoWindow: {
                        maxWidth: 250,
                        content: (content)
                    },
                    icon: {
                          url: url,
                          scaledSize: new google.maps.Size(40, 40), // size
                        }
                });
            }
        }, 2000);

        $('.panel-link').on('click', function(e){
            if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
                return;
            var id = $(this).attr('at');
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


