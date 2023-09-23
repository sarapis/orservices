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

                            <form method="post" role="form" autocomplete="off" class="home_serach_form"
                                  action="/search">
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
                                            <a href="/services_near_me" class="input-search-btn"
                                               style="z-index: 100;"><img
                                                    src="frontend/assets/examples/images/location.png"></a>
                                            <input type="text" class="form-control pr-50" id="location1"
                                                   name="search_address">
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
                            <form method="post" role="form" autocomplete="off" class="home_serach_form"
                                  action="/search">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group text-left form-material" data-plugin="formMaterial">
                                    <img src="/frontend/assets/images/search.png" alt="" title="" class="form_icon_img">
                                    <input type="text" class="form-control" id="search_service" name="find"
                                           placeholder="Search for service">
                                    <div id="searchServiceList">
                                    </div>
                                </div>
                                <div class="form-group text-left form-material" data-plugin="formMaterial">
                                    <div class="form-group">
                                        <div class="row m-0">
                                            <div class="col-md-9 pl-0">
                                                <img src="/frontend/assets/images/location.png" alt="" title=""
                                                     class="form_icon_img">
                                                {{-- <input type="text" class="form-control pr-50" id="location1" name="search_address" placeholder="Search Location..."> --}}
                                                <a href="javascript:void(0)" class="input-search-btn"
                                                   style="z-index: 100;" onclick="getLocation()"><img
                                                        src="/frontend/assets/examples/images/location.png"
                                                        style="width: 20px;margin: 22px 0;"></a>
                                                <input type="text" class="form-control pr-50" id="location1"
                                                       name="search_address" placeholder="Search Location...">
                                            </div>
                                            <div class="col-md-3 p-0">
                                                <button type="submit" class="btn btn-primary btn-block btn-lg">Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button> --}}
                            </form>
                        </div>
                        <div class="scoll_category">
                            <img src="/frontend/assets/images/Group.png" alt="" title="">
                            <a href="#category" class="ml-10"
                               style="font-size: 21px;color: #f4f7fb !important;text-decoration: underline;font-weight: 600;letter-spacing: 1;">
                                Scroll to see all categories</a>
                        </div>
                    </div>
                </div>

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
                                        <a class="card-link @if($c != 0) collapsed @endif " data-toggle="collapse"
                                           href="#collapse{{$c}}"></a>
                                        <a class="grand_taxonomy card-link taxonomy-link"
                                           href="javascript:void(0);">{{$grandparent_taxonomy['alt_taxonomy_name']}} </a>
                                    </div>
                                    <div id="collapse{{$c}}" class="collapse @if($c++ == 0) show @endif"
                                         data-parent="#accordion">
                                        <div class="card-body">
                                            <ul class="tree1">
                                                @foreach($grandparent_taxonomy['parent_taxonomies'] as $key3 => $parent_taxonomy)
                                                    @if ($parent_taxonomy['child_taxonomies'] != "")
                                                        <li>
                                                            <a class="parent_taxonomy"
                                                               href="javascript:void(0);">{{$parent_taxonomy['parent_taxonomy']}}</a>

                                                            <ul>
                                                                @foreach($parent_taxonomy['child_taxonomies'] as $key4 => $child_taxonomy)
                                                                    <li>
                                                                        <a class="child_node" href="javascript:void(0);"
                                                                           value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_recordid}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>

                                                        </li>
                                                    @else
                                                        <li>
                                                            <a class="child_node" href="javascript:void(0);"
                                                               value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_recordid}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
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
                                <div id="collapse{{$c}}" class="collapse @if($c++ == 0) show @endif"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <ul class="tree1">
                                            @foreach($grandparent_taxonomy['parent_taxonomies'] as $key3 => $parent_taxonomy)
                                                @if ($parent_taxonomy['child_taxonomies'] != "")
                                                    <li>
                                                        <a class="parent_taxonomy"
                                                           href="javascript:void(0);">{{$parent_taxonomy['parent_taxonomy']}}</a>

                                                        <ul>
                                                            @foreach($parent_taxonomy['child_taxonomies'] as $key4 => $child_taxonomy)
                                                                <li>
                                                                    <a class="child_node" href="javascript:void(0);"
                                                                       value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_recordid}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="child_node" href="javascript:void(0);"
                                                           value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_recordid}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
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
                                <a class="child_node taxonomy-link" href="javascript:void(0);"
                                   value="child_{{$parent_taxonomy->taxonomy_recordid}}">
                                    <div class="category_icon">
                                        <div class="inner_category">
                                            <!-- <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}.png" alt="" title="" class="hover_none">
                                                    <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}_white.png" alt="" title="" class="hover_display"> -->

                                            @if ($layout && $layout->taxonomy_icon_hover == 'yes')
                                                <img src="{{$parent_taxonomy->category_logo}}" alt="" title=""
                                                     class="hover_none">
                                                <img src="{{$parent_taxonomy->category_logo_white}}" alt="" title=""
                                                     class="hover_display">
                                            @else
                                                <img src="{{$parent_taxonomy->category_logo}}" alt="" title="" class="">
                                            @endif
                                            <h3>{{$parent_taxonomy->taxonomy_name}}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                    @foreach($taxonomy_tree['parent_taxonomies'] as $key2 => $parent_taxonomy)
                        @if ($key2 % 2 == 1)
                            <div class="col-lg-2 col-md-2">
                                <a class="child_node taxonomy-link" href="javascript:void(0);"
                                   value="child_{{$parent_taxonomy->taxonomy_recordid}}">
                                    <div class="category_icon">
                                        <div class="inner_category">
                                            <!-- <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}.png" alt="" title="" class="hover_none">
                                                    <img src="/frontend/assets/images/{{ strtolower($parent_taxonomy->taxonomy_name) }}_white.png" alt="" title="" class="hover_display"> -->
                                            @if ($layout && $layout->taxonomy_icon_hover == 'yes')
                                                <img src="{{$parent_taxonomy->category_logo}}" alt="" title=""
                                                     class="hover_none">
                                                <img src="{{$parent_taxonomy->category_logo_white}}" alt="" title=""
                                                     class="hover_display">
                                            @else
                                                <img src="{{$parent_taxonomy->category_logo}}" alt="" title="" class="">
                                            @endif
                                            <h3>{{$parent_taxonomy->taxonomy_name}}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    </div>

    @if ($home->home_page_style == 'Services (ex. larable-dev.sarapisorg)')
        <div class="home_page_content text-center" id="home_page_content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-sm-12 col-md-5">
                        <img src="{{ $home->part_1_image }}" alt="" title="" class="">
                    </div>
                    <div class="col-lg-7 col-sm-12 col-md-7">
                        <div class="row m-0">
                            <div class="col-md-12 col-lg-8 text-left p-0">{!! $home->sidebar_content !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    <script src="{{asset('/js/treeview.js')}}"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $('.grand_taxonomy').on('click', function (e) {
                var childs = $('.child_node', $(this).parent().parent());
                console.log(childs.length, '1');
                var selected_taxonomy_ids = [];
                for (var i = 0; i < childs.length; i++) {
                    selected_taxonomy_ids.push(childs.eq(i).attr('value'));
                }
                $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
                $("#filter").submit();
            });
            $('.parent_taxonomy').on('click', function (e) {
                var childs = $('.child_node', $(this).parent().parent());
                console.log(childs.length, '2');
                var selected_taxonomy_ids = [];
                for (var i = 0; i < childs.length; i++) {
                    selected_taxonomy_ids.push(childs.eq(i).attr('value'));
                }
                $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
                $("#filter").submit();
            });
            $('.child_node').on('click', function (e) {
                selected_taxonomy_ids = $(this).attr('value');
                console.log(selected_taxonomy_ids, '3');
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
                if (query != '') {
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('services.fetch') }}",
                        method: "post",
                        data: {_token, query},
                        success: function (data) {
                            $('#searchServiceList').fadeIn();
                            $('#searchServiceList').html('<span class="text-right" style="left:97%;position:relative;cursor: pointer;display:none;" id="close_icon"><i class="fa fa-times" aria-hidden="true"></i></span>' + data)
                            $('#close_icon').show()
                        },
                        error: function (err) {
                            $('#close_icon').hide()
                            console.log(err)
                        }
                    })
                } else {
                    $('#close_icon').hide()
                    $('#searchServiceList').fadeOut();
                }
            })
            $(document).on('click', '#searchServiceList li', function () {
                $('#search_service').val($(this).text())
                $('#close_icon').hide()
                $('#searchServiceList').fadeOut();
            })
            $(document).on('click', '#close_icon', function () {
                $('#close_icon').hide()
                $('#searchServiceList').fadeOut();
            })
        });
    </script>
@endsection

