@extends('layouts.app')
@section('title')
Home
@stop
<style>
    .navbar-container.container-fluid {
        display: none !important;
    }
    @media (max-width: 991px) {
        .page {
            padding-top: 0px !important;
        }
    }
    .pac-logo:after {
        display: none;
    }
    .home-category {
        cursor: pointer;
    }
    .glyphicon {
        font-family: "Material Design Iconic" !important;
        font-size: 16px;
    }
    .glyphicon-plus:before {
        display: inline-block;
        text-align: center;
        width: 20px;
        height: 20px;
        border: solid 1px #fff;
        border-radius: 50%;
        background-color: orange;
        color: white;
    }

</style>
<link href="{{asset('/css/treeview.css')}}" rel="stylesheet">
@section('content')

<div class="home-sidebar">
    @include('layouts.filter')
    @include('layouts.sidebar')
</div>
@if ($home->home_page_style == 'Alerts (ex. hc.flospaces.org)')
<div class="page-register layout-full page-dark">
    <div class="page vertical-align" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <div class="page-content vertical-align-middle">
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-md-6" style="text-align: center;">
                    <div class="inner_search">
                        {!! $home->sidebar_content !!}
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 home-browse-list" style="text-align: center;">
                    <div class="brand">
                        <h2 class="brand-text"></h2>
                        <h3 class="text-white">How can we help you?</h3>
                    </div>

                    <form method="post" role="form" autocomplete="off" class="home_serach_form" action="/search">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group text-left form-material" data-plugin="formMaterial">
                            <label for="inputName">
                                <h4 class="text-white">I'm looking for </h4>
                            </label>
                            <input type="text" class="form-control" id="inputName" name="find">
                        </div>
                        <div class="form-group text-left form-material" data-plugin="formMaterial">
                            <label for="inputName">
                                <h4 class="text-white">Near an Address?</h4>
                            </label>
                            <div class="form-group">
                                <div class="input-group">
                                    <a href="/services_near_me" class="input-search-btn" style="z-index: 100;"><img
                                            src="frontend/assets/examples/images/location.png"></a>
                                    <input type="text" class="form-control pr-50" id="location1" name="search_address">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="page-register layout-full page-dark">
    <div class="page" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <div class="container">
            <div class="col-md-6">
                <div class="page-content home_slide_content">
                    <h1 class="text-white">{{ $home->banner_text1 }}</h1>
                    {{-- @php
                        $banner_text2 = $home->banner_text2 ? explode(" ",$home->banner_text2) : [];
                        $seconLastWord = "";
                        if(count($banner_text2) > 0){
                            $seconLastWord = $banner_text2[count($banner_text2) - 2];
                        }
                        if($seconLastWord != "" && count($banner_text2) > 0){
                            $home->banner_text2 = str_replace($seconLastWord,'<span class="color_blue">'. $seconLastWord .'</span>', $home->banner_text2);
                        }

                    @endphp --}}
                    <h3 class="text-white">{!! $home->banner_text2 !!}</h3>
                    <form method="post" role="form" autocomplete="off" class="home_serach_form" action="/search">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group text-left form-material" data-plugin="formMaterial">
                            <img src="/frontend/assets/images/search.png" alt="" title="" class="form_icon_img">
                            <input type="text" class="form-control" id="search_service" name="find" placeholder="Search for service">
                            <div id="searchServiceList"></div>
                        </div>
                        <div class="form-group text-left form-material" data-plugin="formMaterial">
                            <div class="form-group">
                                <div class="row m-0">
                                    <div class="col-md-9 pl-0">
                                        <img src="/frontend/assets/images/location.png" alt="" title="" class="form_icon_img">
                                        {{-- <input type="text" class="form-control pr-50" id="location1" name="search_address" placeholder="Search Location..."> --}}
                                        <a href="javascript:void(0)" class="input-search-btn" style="z-index: 100;" onclick="getLocation()" ><img src="/frontend/assets/examples/images/location.png" style="width: 20px;margin: 22px 0;"></a>
                                        <input type="text" class="form-control pr-50" id="location1" name="search_address" placeholder="Search Location...">
                                    </div>
                                    <div class="col-md-3 p-0">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button> --}}
                    </form>
                </div>
                <div class="scoll_category">
                    <img src="/frontend/assets/images/Group.png" alt="" title="" >
                    <a href="#category" class="ml-10" style="font-size: 21px;color: #f4f7fb !important;text-decoration: underline;font-weight: 600;letter-spacing: 1;"> Scroll to see all categories</a>
                </div>
            </div>
        </div>
        {{-- <div class="page-content vertical-align-middle">
            <div class="brand">
                <h2 class="brand-text"></h2>
            </div>
            <h3 class="text-white">How can we help you?</h3>
            <form method="post" role="form" autocomplete="off" class="home_serach_form" action="/search">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group text-left form-material" data-plugin="formMaterial">
                    <label for="inputName">
                        <h4 class="text-white">I'm looking for </h4>
                    </label>
                    <input type="text" class="form-control" id="inputName" name="find">
                </div>
                <div class="form-group text-left form-material" data-plugin="formMaterial">
                    <label for="inputName">
                        <h4 class="text-white">Near an Address?</h4>
                    </label>
                    <div class="form-group">
                        <div class="input-group">
                            <a href="/services_near_me" class="input-search-btn" style="z-index: 100;"><img
                                    src="frontend/assets/examples/images/location.png"></a>
                            <input type="text" class="form-control pr-50" id="location1" name="search_address">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
            </form>
        </div> --}}
    </div>
</div>
@endif


<!-- start browse_category div -->
<div class="browse_category" id="category">
    <div class="page-content">
        <div class="container">
            <div class="text-center">
                <h3>Browse by Category</h3>
            </div>
                {{-- <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/care.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/care_white.png" alt="" title="" class="hover_display">
                                <h3>Care</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/emergency.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/emergency_white.png" alt="" title="" class="hover_display">
                                <h3>Emergency</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/food.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/food_white.png" alt="" title="" class="hover_display">
                                <h3>Food</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/health.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/health_white.png" alt="" title="" class="hover_display">
                                <h3>Health</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/legal.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/legal_white.png" alt="" title="" class="hover_display">
                                <h3>Legal</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/psychiatric-emergency.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/psychiatric-emergency_white.png" alt="" title="" class="hover_display">
                                <h3>Psychiatric Emergency</h3>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/education.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/education_white.png" alt="" title="" class="hover_display">
                                <h3>Education</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/employment.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/employment_white.png" alt="" title="" class="hover_display">
                                <h3>Employment</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/goods.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/goods_white.png" alt="" title="" class="hover_display">
                                <h3>Goods</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/housing.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/housing_white.png" alt="" title="" class="hover_display">
                                <h3>Housing</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/money.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/money_white.png" alt="" title="" class="hover_display">
                                <h3>Money</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a href="#">
                        <div class="category_icon">
                            <div class="inner_category">
                                <img src="/frontend/assets/images/work.png" alt="" title="" class="hover_none">
                                <img src="/frontend/assets/images/work_white.png" alt="" title="" class="hover_display">
                                <h3>Work</h3>
                            </div>
                        </div>
                    </a>
                </div> --}}
                {{-- <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                    <div id="accordion">
                        <div class="row"> --}}
                        @php
                            $c = 0;
                        @endphp
                        @if(!isset($taxonomy_tree['parent_taxonomies']))
                            <div class="col-12 col-md-6 col-lg-6 col-sm-12">
                                @foreach($taxonomy_tree as $key2 => $grandparent_taxonomy)
                                    @php $grand_parentscount = $grandparent_taxonomy['service_count']; @endphp
                                    @if ($key2 % 2 == 0)
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link @if($c != 0) collapsed @endif " data-toggle="collapse" href="#collapse{{$c}}"></a>
                                                <a class="grand_taxonomy card-link taxonomy-link" href="javascript:void(0);">{{$grandparent_taxonomy['alt_taxonomy_name']}} </a>
                                            </div>
                                            <div id="collapse{{$c}}" class="collapse @if($c++ == 0) show @endif" data-parent="#accordion">
                                                <div class="card-body">
                                                    <ul class="tree1">
                                                        @foreach($grandparent_taxonomy['parent_taxonomies'] as $key3 => $parent_taxonomy)
                                                            @if ($parent_taxonomy['child_taxonomies'] != "")
                                                            <li>
                                                                <a class="parent_taxonomy" href="javascript:void(0);">{{$parent_taxonomy['parent_taxonomy']}}</a>

                                                                    <ul>
                                                                        @foreach($parent_taxonomy['child_taxonomies'] as $key4 => $child_taxonomy)
                                                                            <li>
                                                                                <a class="child_node" href="javascript:void(0);"  value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_recordid}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>

                                                            </li>
                                                            @else
                                                            <li>
                                                                <a class="child_node" href="javascript:void(0);" value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_recordid}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 col-sm-12">
                                @foreach($taxonomy_tree as $key2 => $grandparent_taxonomy)
                                    @php $grand_parentscount = $grandparent_taxonomy['service_count']; @endphp
                                    @if ($key2 % 2 == 1)
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <a class="card-link @if($c != 0) collapsed @endif " data-toggle="collapse" href="#collapse{{$c}}"></a>
                                                <a class="grand_taxonomy card-link taxonomy-link" href="javascript:void(0);">{{$grandparent_taxonomy['alt_taxonomy_name']}} </a>
                                            </div> --}}
                                            <div id="collapse{{$c}}" class="collapse @if($c++ == 0) show @endif" data-parent="#accordion">
                                                <div class="card-body">
                                                    <ul class="tree1">
                                                        @foreach($grandparent_taxonomy['parent_taxonomies'] as $key3 => $parent_taxonomy)
                                                            @if ($parent_taxonomy['child_taxonomies'] != "")
                                                            <li>
                                                                <a class="parent_taxonomy" href="javascript:void(0);">{{$parent_taxonomy['parent_taxonomy']}}</a>

                                                                    <ul>
                                                                        @foreach($parent_taxonomy['child_taxonomies'] as $key4 => $child_taxonomy)
                                                                            <li>
                                                                                <a class="child_node" href="javascript:void(0);"  value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_recordid}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>

                                                            </li>
                                                            @else
                                                            <li>
                                                                <a class="child_node" href="javascript:void(0);" value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_recordid}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            @php
                                function cmp($a, $b){
                                    return strcmp($a->taxonomy_name, $b->taxonomy_name);
                                }
                                $taxonomy_tree['parent_taxonomies'] = json_decode(json_encode($taxonomy_tree['parent_taxonomies']));
                                usort($taxonomy_tree['parent_taxonomies'], "cmp");
                            @endphp
                            <div class="row">
                                @foreach($taxonomy_tree['parent_taxonomies'] as $key2 => $parent_taxonomy)
                                    @if ($key2 % 2 == 0)
                                    <div class="col-lg-2 col-md-2">
                                        <a class="child_node taxonomy-link" href="javascript:void(0);" value="child_{{$parent_taxonomy->taxonomy_recordid}}">
                                            <div class="category_icon">
                                                <div class="inner_category">
                                                    <!-- <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}.png" alt="" title="" class="hover_none">
                                                    <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}_white.png" alt="" title="" class="hover_display"> -->
                                                    <img src="{{$parent_taxonomy->category_logo}}" alt="" title="" class="hover_none">
                                                    <img src="{{$parent_taxonomy->category_logo_white}}" alt="" title="" class="hover_display">
                                                    <h3>{{$parent_taxonomy->taxonomy_name}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <a class="child_node card-link taxonomy-link" href="javascript:void(0);" value="child_{{$parent_taxonomy->taxonomy_recordid}}">{{$parent_taxonomy->taxonomy_name}}</a>
                                            </div>
                                        </div> --}}
                                    @endif
                                @endforeach
                                @foreach($taxonomy_tree['parent_taxonomies'] as $key2 => $parent_taxonomy)
                                    @if ($key2 % 2 == 1)
                                    <div class="col-lg-2 col-md-2">
                                        <a class="child_node taxonomy-link" href="javascript:void(0);" value="child_{{$parent_taxonomy->taxonomy_recordid}}">
                                            <div class="category_icon">
                                                <div class="inner_category">
                                                    <!-- <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}.png" alt="" title="" class="hover_none">
                                                    <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}_white.png" alt="" title="" class="hover_display"> -->
                                                    <img src="{{$parent_taxonomy->category_logo}}" alt="" title="" class="hover_none">
                                                    <img src="{{$parent_taxonomy->category_logo_white}}" alt="" title="" class="hover_display">
                                                    <h3>{{$parent_taxonomy->taxonomy_name}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <a class="child_node card-link taxonomy-link" href="javascript:void(0);" value="child_{{$parent_taxonomy->taxonomy_recordid}}">{{$parent_taxonomy->taxonomy_name}}</a>
                                            </div>
                                        </div> --}}
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        {{-- </div>
                    </div>
                </div> --}}
        </div>
    </div>
</div>

@if ($home->home_page_style == 'Services (ex. larable-dev.sarapisorg)')
<div class="home_page_content text-center" id="home_page_content">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-sm-12 col-md-5">
                {{-- <img src="/frontend/assets/images/circle_images.png" alt="" title="" class=""> --}}
                <img src="{{ $home->part_1_image }}" alt="" title="" class="">
            </div>
            <div class="col-lg-7 col-sm-12 col-md-7">
                <div class="row m-0">
                    {{-- <div class="col-md-2">
                        <img src="{{$home->part_1_image}}" alt="" title="" class="">
                    </div> --}}
                    <div class="col-md-12 col-lg-8 text-left p-0">
                       <!--  <p>The purpose of this system is to provide a searchable and filterable directory of organizations, contacts and facilities to signed in users. Users can export any data about those three elements from the directory.</p> -->
                       {!! $home->sidebar_content !!}
                    </div>
                </div>
                {{-- <div class="row m-0">
                    <div class="col-md-2">
                        <img src="{{$home->part_2_image}}" alt="" title="" class="">
                    </div>
                    <div class="col-md-8 text-left p-0">
                        <!-- <p>A special class of users can also send three types of messages to contacts in the directory: email messages, SMS and recorded voice messages.</p> -->
                        {!! $home->sidebar_content_part_2 !!}
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-md-2">
                        <img src="{{$home->part_3_image}}" alt="" title="" class="">
                    </div>
                    <div class="col-md-8 text-left p-0">
                        <!-- <p>Contacts can be organized into groups, and these messages can be sent to multiple groups of contacts at a single time. Replies to email, SMS and voice messages are recorded and attached to the message report for analysis.</p> -->
                        {!! $home->sidebar_content_part_3 !!}
                    </div>
                </div> --}}
                {{-- {!! $home->sidebar_content !!} --}}
            </div>
        </div>
    </div>
</div>
@endif

<!-- end below after serching div -->
{{-- <div id="content" class="container m-0" style="width: 100%;">
        <div class=" pt-20 pl-15" style="margin-right: 0">
            <div class="col-xl-7 col-md-7">
            <div class="panel mb-10">
                <div class="panel-heading text-center">
                    <h1 class="panel-title" style="font-size: 25px;">I'm looking for ...</h1>
                </div>
                <div class="panel-body text-center">
                    <form action="/search" method="POST" class="hidden-sm hidden-xs col-md-6 col-md-offset-3" style="display: block !important; padding-bottom: 30px;padding: 5px; ">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="input-group pull-right text-white pr-25">

    <input type="text" class="form-control" placeholder="Search here..." name="find"/ style="z-index: 0;">
    <div class="input-group-btn pull-right ">
        <button type="submit" class="btn btn-primary btn-search bg-primary-color"><i class="fa fa-search"></i></button>
    </div>

</div>
</form>
</div>
</div>
<div class="panel panel-bordered animation-scale-up">
    <div class="panel-heading text-center">
        <h3 class="panel-title" style="font-size: 25px;">Browse by Category</h3>
    </div>
    <div class="panel-body">

    </div>
</div>
</div>
<div class="col-xl-5 col-md-5">
    <div class="panel">
        <div class="panel-body bg-primary-color">
            <div class="form-group">
                <h4 class="text-white">Find Services Near an Address?</h4>
                <form method="post" action="/search" id="search_location">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">

                        <div class="input-search">
                            <i class="input-search-icon md-pin" aria-hidden="true"></i>
                            <input id="location1" type="text" class="form-control text-black" name="search_address"
                                placeholder="Search Address" style="border-radius:0;">
                        </div>

                    </div>
                    <button type="submit" class="btn btn_findout">
                        <h4 class="text-white mb-0">Search</h4>
                    </button>
                    <a href="/services_near_me" class="btn btn_findout pull-right">
                        <h4 class="text-white mb-0">Services Near Me</h4>
                    </a>
                </form>
            </div>
        </div>
        <div class="panel-body">
            {!! $home->sidebar_content !!}
        </div>
    </div>
</div>
</div>
</div> --}}


<script src="{{asset('/js/treeview.js')}}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
    $('.grand_taxonomy').on('click', function(e){
        var childs = $('.child_node', $(this).parent().parent());
        console.log(childs.length,'1');
        var selected_taxonomy_ids = [];
        for (var i = 0; i < childs.length; i ++) {
            selected_taxonomy_ids.push(childs.eq(i).attr('value'));
        }
        $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
        $("#filter").submit();
    });
    $('.parent_taxonomy').on('click', function(e){
        var childs = $('.child_node', $(this).parent().parent());
        console.log(childs.length,'2');
        var selected_taxonomy_ids = [];
        for (var i = 0; i < childs.length; i ++) {
            selected_taxonomy_ids.push(childs.eq(i).attr('value'));
        }
        $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
        $("#filter").submit();
    });
    $('.child_node').on('click', function(e){
        selected_taxonomy_ids = $(this).attr('value');
        console.log(selected_taxonomy_ids,'3');
        $('#selected_taxonomies').val(selected_taxonomy_ids);
        $("#filter").submit();
    });
    // $('.card-link.taxonomy-link').on('click', function(e){
    //     console.log($(this).attr('at'));
    //     var id = $(this).attr('at').replace('/', 'AAA').replace('(', 'BBB').replace(')', 'CCC');
    //     console.log(id);

    //     $("#category_" +  id).prop( "checked", true );
    //     $("#filter").submit();
    // });
    // $('.child-link').on('click', function(e){
    //     var id = $(this).attr('at');
    //     $('#category_'+id).prop('checked', true);
    //     $("#filter").submit();
    // });
    // $('.branch').each(function(){
    //     if($('ul li', $(this)).length == 0)
    //         $(this).hide();
    // });
    $('#search_service').keyup(function () {
        let query = $(this).val()
        if(query != ''){
                var _token = "{{ csrf_token() }}";
            $.ajax({
                url: "{{ route('services.fetch') }}",
                method:"post",
                data:{_token,query},
                success:function(data){
                    $('#searchServiceList').fadeIn();
                    $('#searchServiceList').html(data)
                },
                error : function(err){
                    console.log(err)
                }
            })
        }else{
            $('#searchServiceList').fadeOut();
        }
    })
    $(document).on('click', '#searchServiceList li',function () {
        $('#search_service').val($(this).text())
        $('#searchServiceList').fadeOut();
    })
});
</script>
@endsection

