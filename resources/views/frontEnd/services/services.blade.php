@extends('layouts.app')
@section('title')
Services
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style>
    #sdoh_codes_tree .jstree-container-ul {
        height: 300px !important;
        overflow: scroll !important;
    }
    .sdoh_code_select button{
        background: #ffffff;
        border: 1px solid #dce0e5;
        border-radius: 8px;
        padding: 13px 15px;
        color: #1b1b1b;
        font-weight: 500;
        font-size: 15px;
        line-height: 18px;
        margin-right: 8px;
        box-shadow: none;
        height: 45px;
    }
    .sdoh_code_select button .filter-option-inner-inner {color: #1b1b1b;}
    .top_services_filter .dropdown-menu a:hover {border-radius:0px !important}
</style>
@section('content')
@include('layouts.filter')
<div>

    <!-- Page Content Holder -->
    <div class="top_services_filter">
        <div class="container">
            @include('layouts.sidebar')
            <!-- Types Of Services -->
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown1" data-toggle="dropdown"
                    aria-expanded="false">
                    Types Of Services
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="sidebar_tree">
                </div>
            </div>
            @if ($layout->meta_filter_activate == 1 && $layout->user_metafilter_option == 1)
            @php
                if(\Illuminate\Support\Facades\Session::has('filter_label')){
                   $filter_label =  \Illuminate\Support\Facades\Session::get('filter_label');
                }
            @endphp
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                    {{ (isset($filter_label) ? ($filter_label == 'off_label' ? $layout->meta_filter_off_label : $layout->meta_filter_on_label) : ($layout->default_label ? ($layout->default_label == 'off_label' ? $layout->meta_filter_off_label : $layout->meta_filter_on_label): $layout->meta_filter_off_label)) }}
                </button>
                <div class="dropdown-menu " aria-labelledby="exampleSizingDropdown1" role="menu">
                <a class="dropdown-item drop-label{{ (isset($filter_label) && $filter_label == 'off_label') || !isset($filter_label) && $layout->default_label == 'off_label'  ? ' active' : '' }}" href="javascript:void(0)" role="menuitem" data-id="off_label">{{ $layout->meta_filter_off_label }}</a>
                <a class="dropdown-item drop-label{{ (isset($filter_label) && $filter_label == 'on_label') || !isset($filter_label) && $layout->default_label == 'on_label'  ? ' active' : '' }}" href="javascript:void(0)" role="menuitem" data-id="on_label">{{ $layout->meta_filter_on_label }}</a>
                </div>
            </div>
            @endif
            <!--end  Types Of Services -->
            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin')))
            @php
            $org_activate = [];
            if(isset($selected_organization)){
                $org_activate = json_decode($selected_organization);
            }
            @endphp
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                    Organization Tags
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="organization_tags_tree">
                    {{-- {!! Form::select('organization_tags',$organization_tagsArray,'',['class' => 'btn dropdown-toggle']) !!} --}}
                    {{-- @foreach ($organization_tagsArray as $key => $value)
                    <a class="dropdown-item drop-tags {{ isset($selected_organization) && in_array($key,$org_activate) ? ' active' : '' }}" href="javascript:void(0)" role="menuitem" data-id="{{ $key }}">{{ $value }}</a>
                    @endforeach --}}
                    {{-- <select class="form-control selectpicker" multiple data-live-search="true" id="organization_tag" data-size="3" name="organization_tag[]">
                        <option value="" selected disabled hidden>Filter by Tags</option>
                        @foreach($organization_tag_list as $key => $organization_tag)
                            <option value="{{$organization_tag}}">{{$organization_tag}}</option>
                    @endforeach
                    </select> --}}
                </div>
            </div>
            @endif
            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin')))
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown1" data-toggle="dropdown"
                    aria-expanded="false">
                    Service Tags
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="service_tags_tree">
                </div>
            </div>
            @endif
            <!-- Sort By -->
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
                    <a @if(isset($sort) && $sort=='Service Name' ) class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                    <a @if(isset($sort) && $sort=='Organization Name' ) class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                    @if($sort_by_distance_clickable)
                    <a @if(isset($sort) && $sort=='Distance from Address' ) class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Distance from Address</a>
                    @endif
                </div>
            </div>
            <!--end  Sort By -->

            <!-- Results Per Page -->
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown3" data-toggle="dropdown" aria-expanded="false">
                    Results Per Page
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown3" role="menu">
                    <a @if(isset($pagination) && $pagination=='10' ) class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">10</a>
                    <a @if(isset($pagination) && $pagination=='25' ) class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                    <a @if(isset($pagination) && $pagination=='50' ) class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                </div>
            </div>
            <!--end Results Per Page -->
            <!-- SDOH Domain -->
            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin')))
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                    SDOH Domains
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="sdoh_codes_category_tree">
                </div>
            </div>
            @endif
            <!--end SDOH Domain -->
            <!-- SDOH Codes -->
            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && (Auth::user()->roles->name != 'Organization Admin' || Auth::user()->roles->name != 'Section Admin')))
            <div class="" style="display: inline-block;width: 169px;">
                <div class="sdoh_code_select" aria-labelledby="exampleSizingDropdown1" role="menu">
                    <select class="form-control selectpicker" multiple data-live-search="true" id="sdoh_codes_select" data-size="3" name="sdoh_codes[]">
                        @foreach($sdoh_codes_Array as $key => $code_data)
                            <option value="{{$code_data->id}}" {{ isset($selected_sdoh_code) && in_array($code_data->id,$selected_sdoh_code) ? 'selected' : '' }} >{{$code_data->code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <!--end SDOH Codes -->

            <!-- download -->
            @if ($layout->display_download_menu == 1)
            <div class="dropdown btn_download float-right">
                <button type="button" class="float-right btn_share_download dropdown-toggle" id="exampleBulletDropdown4"
                    data-toggle="dropdown" aria-expanded="false">
                    <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                    @if ($layout->display_download_csv == 1)
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    @endif
                    @if ($layout->display_download_pdf == 1)
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                    @endif
                </div>
            </div>
            @endif

            <!--end download -->
            @if ($layout->service_share_button == 1)
            <!-- share btn -->
            <button type="button" class="float-right btn_share_download" data-toggle="modal" data-target="#shareThisModal">
                <img src="/frontend/assets/images/share.png" alt="" title="" class="mr-10 share_image">
                Share
            </button>
            <!--end share btn -->
            @endif
        </div>
    </div>
    <div class="inner_services">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8">
                    @if (session('address'))
                    <div class="alert dark alert-danger alert-dismissible ml-15" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <b>{{ session('address') }}</b>
                    </div>
                    @endif
                    @if ((isset($selectedCodesName) && $selectedCodesName) || (isset($selectedCategoryName) && $selectedCategoryName))
                    <div class="card">
                        <div class="card-block">
                            @if (isset($selectedCodesName) && $selectedCodesName)
                            <h4 class="card-title mb-2">
                                SDOH Codes:
                                <span class="px-2">{{ implode(', ',$selectedCodesName) }}</span>
                                {{-- @foreach ($selectedCodesName as $k => $v)
                                <span class="px-2">{{ $v }}</span> {{ $k +1 < count($selectedCodesName) ? ',' : ''  }}
                                @endforeach --}}
                            </h4>
                            @endif
                            @if(isset($selectedCategoryName) && $selectedCategoryName)
                            <h4 class="card-title mb-0">
                                SDOH Domain:
                                <span class="px-2">{{ implode(', ',$selectedCategoryName) }}</span>
                                {{-- @foreach ($selectedCategoryName as $k => $v)
                                <span class="px-2">{{ $v }}</span>{{ $k+1 < count($selectedCategoryName) ? ',' : ''  }}
                                @endforeach --}}
                            </h4>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(count($services) != 0)
                    @foreach($services as $service)
                    @if($service->service_name != null && ((!Auth::user() && $service->access_requirement == 'none') || Auth::user()))
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">
                                <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                <p style="float: right;">
                                    {{ isset($service->miles)  ? floatval(number_format($service->miles,2)) .' miles'  : '' }}
                                </p>
                                @if ($service->organizations && $service->organizations->organization_status_x && isset($organizationStatus[$service->organizations->organization_status_x]) && ($organizationStatus[$service->organizations->organization_status_x] == 'Out of Business' || $organizationStatus[$service->organizations->organization_status_x] == 'Inactive'))
                                    <span class="badge badge-danger float-right m-2" style="color:#fff;">Inactive</span>
                                @endif
                            </h4>
                            <p class="org_title"><span class="subtitle"><b>Organization:</b></span>
                                @if(isset($service->organizations))
                                <a class="panel-link" class="notranslate"
                                    href="/organizations/{{$service->organizations()->first()->organization_recordid}}">
                                    {{$service->organizations()->first()->organization_name}}</a>
                                @endif
                            </p>
                            <div class="tagp_class">{!! Str::limit(str_replace(array('\n', '/n', '*'), array(' ', ' ',
                                ' '), $service->service_description), 200) !!}</div>
                            @if ($service->service_phones)
                            <div class="tagp_class">
                                <span><i class="icon md-phone font-size-18 vertical-align-top mr-10"></i>
                                    {!! $service->service_phones_data !!}
                                </span>
                            </div>
                            @endif
                            @isset($service->address)
                            @if (count($service->address) > 0)
                            <div class="tagp_class">
                                @if(isset($service->address))
                                <ul class="p-0" style="margin-left: 25px;">
                                    @foreach($service->address as $address)
                                    <span><i class="icon md-pin font-size-18 vertical-align-top mr-10"></i>
                                        <li>
                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                            {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                        </li>
                                    </span>
                                        @endforeach
                                    </ul>
                                    @endif
                            </div>
                            @endif
                            @endisset
                            @isset($service->taxonomy)
                            @if (count($service->taxonomy) > 0)
                            @php
                                $i = 0;
                                $j = 0;
                                $service_category_data = 0;
                                $service_eligibility_data = 0;
                                foreach ($service->taxonomy as $service_taxonomy_info) {
                                    $taxonomyTypeCategory = $service_taxonomy_info?->taxonomy_type->where('name','Service Category')->first();

                                    if (!empty($taxonomyTypeCategory)) {
                                        $service_category_data += 1;
                                    }
                                    $taxonomyTypeEligibility = $service_taxonomy_info?->taxonomy_type->where('name','Service Eligibility')->first();
                                    if (!empty($taxonomyTypeEligibility)) {
                                        $service_eligibility_data += 1;
                                    }
                                }
                            @endphp
                            @if ($service_category_data != 0)
                            <div class="tagp_class">
                                <span class="pl-0 category_badge subtitle">
                                    @foreach ($service->taxonomy as $service_taxonomy_info)
                                    @php
                                        $taxonomyTypeCategory = $service_taxonomy_info?->taxonomy_type->where('name','Service Category')->first();
                                    @endphp
                                    @if (!empty($taxonomyTypeCategory))
                                    @if ($i == 0)
                                    <b>Service Category:</b>
                                    @php
                                        $i ++;
                                    @endphp
                                    @endif
                                    <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                        at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                        style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                    {{--                                    @endif--}}
                                    @endif
                                    @endforeach
                                </span>
                            </div>
                            @endif
                            @if ($service_eligibility_data != 0)
                            <div class="tagp_class">
                                <span class="pl-0 category_badge subtitle">
                                    @foreach ($service->taxonomy as $service_taxonomy_info)
                                    @php
                                        $taxonomyTypeEligibility = $service_taxonomy_info?->taxonomy_type->where('name','Service Eligibility')->first();
                                    @endphp
                                    @if (!empty($taxonomyTypeEligibility))
                                    {{-- @if($service->service_taxonomy != null) --}}
                                    @if ($j == 0)
                                    <b>Service Eligibility:</b>
                                    @php
                                        $j ++;
                                    @endphp
                                    @endif
                                    <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                        at="child_{{$service_taxonomy_info->taxonomy_recordid}}"
                                        style="background-color: {{ $service_taxonomy_info->badge_color ? '#'.$service_taxonomy_info->badge_color : '#000' }} !important; color:#fff !important;">{{$service_taxonomy_info->taxonomy_name}}</a>
                                    {{-- @endif --}}
                                    @endif
                                    @endforeach
                                </span>
                            </div>
                            @endif
                            @endif
                            @endisset
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <div class="alert dark alert-warning ml-15" role="alert"
                        style="position: relative;left: auto;right: auto;width: 100%;top: auto;">
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
                            <p>Showing {{ $services->count() }} of {{ $services->total() }} results</p>
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
        $(".selectpicker").selectpicker({noneSelectedText: 'SDOH Codes'});
        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;
            var maplocation = <?php print_r(json_encode($map)) ?>;
            // var chip_address = <?php if(isset($chip_address)){print_r(json_encode($chip_address));}else{echo '0';} ?>;
            var chip_address = "{{isset($chip_address) ? $chip_address : '0'}}"
            var chip_service = "{{isset($chip_service) ? $chip_service : '0'}}"
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
            $.each(locations, function(index, value ){
                var name = value.organization == null? '' :value.organization.organization_name;
                var content = '<div id="iw-container">';
                let first_service = []
                if(chip_service != '0' && chip_service != ''){
                value.services.map(function(v,i){
                            // console.log(v,v.service_name.toLowerCase().includes(chip_service),chip_service)
                        if(v.service_name.toLowerCase().includes(chip_service)  && first_service.length == 0){
                            return first_service.push(i)
                        }
                    })
                }
                if(value.services.length > 0){
                    let indexValue
                    if(first_service.length > 0){
                        indexValue = first_service[0]
                    }else{
                        indexValue = 0
                    }
                    content +=  '<div class="iw-title"> <a href="/services/'+value.services[indexValue].service_recordid+'">'+value.services[indexValue].service_name+'</a></div>';
                }
                if(value.organization){

                content += '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + value.organization.organization_recordid + '">' + value.organization.organization_name +'</a>';
                }
                if(value.address){
                    for(i = 0; i < value.address.length; i ++){
                        // content +=  '<div class="iw-subTitle">Address</div>'+
                        //          value.address[i].address_1+ ', '+value.address[i].address_city+','+value.address[i].address_state_province+','+value.address[i].address_postal_code ;
                        content += '<div class="iw-subTitle">Address</div>' +
                                    value.address[i].address_1 + (value.address[i]
                                    .address_city ? ', ' + value.address[i]
                                    .address_city : '') +  (value.address[i].address_state_province ? ',' + value.address[i].address_state_province : '') +
                                    (value.address[i].address_postal_code ? ',' + value.address[i].address_postal_code : '');
                        // content += '<div><a href="https://www.google.com/maps/dir/?api=1&destination=' + value.address[i].address_1+ ', '+value.address[i].address_city+','+value.address[i].address_state_province+','+value.address[i].address_postal_code + '" target="_blank">View on Google Maps</a></div>';
                        content += '<div><a href="https://www.google.com/maps/dir/?api=1&destination=' + value.address[i].address_1 + (value.address[i].address_city ? ', ' + value.address[i].address_city : '') + (value.address[i].address_state_province ? ',' + value.address[i].address_state_province : '') + (value.address[i].address_postal_code ? ',' + value.address[i].address_postal_code : '') + '" target="_blank">View on Google Maps</a></div>';
                    }
                }
                if(value.services.length > 1){
                    content += '<div class="iw-subTitle">Other Services</div>';
                }
                content += '<ul>'
                    if(first_service.length > 0){
                        let indexValue = first_service[0]
                        for(i = 0; i < value.services.length; i ++){
                            if(i != indexValue)
                                content +=  '<li><a href="/services/'+value.services[i].service_recordid+'">'+value.services[i].service_name+'</a></li>';
                            }
                    }else{
                        for(i = 1; i < value.services.length; i ++){
                            content +=  '<li><a href="/services/'+value.services[i].service_recordid+'">'+value.services[i].service_name+'</a></li>';
                        }
                    }

                content += '</ul>'
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
