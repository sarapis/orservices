@extends('layouts.app')
@section('title')
Home
@stop
<style>
   .navbar-container.container-fluid{
        display: none !important;
    }
    @media (max-width: 991px){
        .page {
            padding-top: 0px !important;
        }
    }
    .pac-logo:after{
      display: none;
    }
    .home-category{
        cursor: pointer;
    }
    .layout-full{
        height: 430px !important;
    }
    .glyphicon{
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

<link href="{{asset('css/treeview.css')}}" rel="stylesheet">
@section('content')

    <div class="home-sidebar">
        @include('layouts.filter')
        @include('layouts.sidebar')
    </div>
    <!-- start top form content div -->
    <div class="page-register layout-full page-dark">
        <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
            <div class="page-content vertical-align-middle">
                <div class="brand">
                    <h2 class="brand-text"></h2>
                </div>
                <h3 class="text-white">How can we help you?</h3>
                <form method="post" role="form" autocomplete="off" class="home_serach_form" action="/search">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group text-left form-material" data-plugin="formMaterial">
                        <label for="inputName"><h4 class="text-white">I'm looking for </h4></label>
                        <input type="text" class="form-control" id="inputName" name="find">
                    </div>
                    <div class="form-group text-left form-material" data-plugin="formMaterial">
                        <label for="inputName"><h4 class="text-white">Near an Address?</h4></label>
                        <div class="form-group">
                            <div class="input-group">
                                <a href="/services_near_me" class="input-search-btn" style="z-index: 100;"><img src="frontend/assets/examples/images/location.png"></a>
                                <input type="text" class="form-control pr-50" id="location1" name="search_address" >
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
                </form>
            </div>
        </div>
        <!-- End Page -->
    </div>
    <!--end top form content div -->

    <!-- start browse_category div -->
    <div class="browse_category" id="category">
        <div class="page-content">
            <div class="py-15">
                <div class="text-center">
                    <h3>Browse by Category</h3>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                        <div id="accordion">
                            <input type="hidden" id="selected_taxonomies" name="selected_taxonomies">
                            <div class="row">
                            @php
                                $c = 0;
                            @endphp
                            @if(count($taxonomy_tree) > 0)
                                <div class="col-12 col-md-6 col-lg-6 col-sm-12">
                                    @foreach($taxonomy_tree as $key2 => $grandparent_taxonomy)
                                        @php $grand_parentscount = $grandparent_taxonomy['service_count']; @endphp
                                        @if ($key2 % 2 == 0)
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link @if($c != 0) collapsed @endif " data-toggle="collapse" href="#collapse{{$c}}"></a>
                                                    <a class="grand_taxonomy card-link taxonomy-link" href="javascript:void(0);">{{$grandparent_taxonomy['alt_taxonomy_name']}}  ({{$grand_parentscount}})</a>
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
                                                                                    <a class="child_node" href="javascript:void(0);"  value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_id}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    
                                                                </li>
                                                                @else
                                                                <li>
                                                                    <a class="child_node" href="javascript:void(0);" value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_id}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
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
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link @if($c != 0) collapsed @endif " data-toggle="collapse" href="#collapse{{$c}}"></a>
                                                    <a class="grand_taxonomy card-link taxonomy-link" href="javascript:void(0);">{{$grandparent_taxonomy['alt_taxonomy_name']}}  ({{$grand_parentscount}})</a>
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
                                                                                    <a class="child_node" href="javascript:void(0);"  value="alt_{{$key2}}_parent_{{$key3}}_child_{{$child_taxonomy->taxonomy_id}}">{{$child_taxonomy->taxonomy_name}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    
                                                                </li>
                                                                @else
                                                                <li>
                                                                    <a class="child_node" href="javascript:void(0);" value="alt_{{$key2}}_child_{{$parent_taxonomy['parent_taxonomy']->taxonomy_id}}">{{$parent_taxonomy['parent_taxonomy']->taxonomy_name}}</a>
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
                            @endif
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end browse_category div -->

    <!-- start below after serching div -->
    <div class="after_serach">
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-sm-12 col-md-1"></div>
                <div class="col-lg-10 col-sm-12 col-md-10">
                    <div class="inner_search">
                        {!! $home->sidebar_content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                        <input id="location1" type="text" class="form-control text-black" name="search_address" placeholder="Search Address" style="border-radius:0;">
                                    </div>
                                
                            </div>
                            <button type="submit" class="btn btn_findout"><h4 class="text-white mb-0">Search</h4></button>
                            <a href="/services_near_me" class="btn btn_findout pull-right"><h4 class="text-white mb-0">Services Near Me</h4></a>
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


<script src="{{asset('js/treeview.js')}}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
    $('.grand_taxonomy').on('click', function(e){
        var childs = $('.child_node', $(this).parent().parent());
        console.log(childs.length);
        var selected_taxonomy_ids = [];
        for (var i = 0; i < childs.length; i ++) {
            selected_taxonomy_ids.push(childs.eq(i).attr('value'));
        }
        $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
        $("#filter").submit();
    });
    $('.parent_taxonomy').on('click', function(e){
        var childs = $('.child_node', $(this).parent().parent());
        console.log(childs.length);
        var selected_taxonomy_ids = [];
        for (var i = 0; i < childs.length; i ++) {
            selected_taxonomy_ids.push(childs.eq(i).attr('value'));
        }
        $('#selected_taxonomies').val(selected_taxonomy_ids.toString());
        $("#filter").submit();
    });
    $('.child_node').on('click', function(e){
        selected_taxonomy_ids = $(this).attr('value');
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
});
</script>
@endsection
