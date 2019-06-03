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
    ul#tree1 {
        column-count: 2;
    }
    .home-category{
        cursor: pointer;
    }
</style>
<link href="{{asset('css/treeview.css')}}" rel="stylesheet">
@section('content')
    <div class="home-sidebar">
    @include('layouts.sidebar')
    </div>
    <div id="content" class="container m-0" style="width: 100%;">
        <div class="row pt-20 pl-15" style="margin-right: 0">
            <div class="col-xl-7 col-md-7">
              <!-- Panel -->
              <div class="panel mb-10">
                <div class="panel-heading text-center">
                    <h1 class="panel-title" style="font-size: 25px;">I'm looking for ...</h1>
                </div>
                <div class="panel-body text-center">
                    <form action="/find" method="POST" class="hidden-sm hidden-xs col-md-6 col-md-offset-3" style="display: block !important; padding-bottom: 30px;padding: 5px; ">
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
              <!-- End Panel -->
              <div class="panel panel-bordered animation-scale-up">
                <div class="panel-heading text-center">
                    <h3 class="panel-title" style="font-size: 25px;">Browse by Category</h3>
                </div>
                <div class="panel-body">
                    <ul id="tree1">
                        @foreach($taxonomies as $taxonomy)
                            <li>
                                <a at="{{$taxonomy->taxonomy_recordid}}" class="home-category">{{$taxonomy->taxonomy_name}}</a>
                                @if(count($taxonomy->childs))
                                    @include('layouts.manageChild',['childs' => $taxonomy->childs])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-5">
              <!-- Panel -->
                <div class="panel">
                    <div class="panel-body bg-primary-color">
                        <div class="form-group">
                            <h4 class="text-white">Find Services Near an Address?</h4>
                            <form method="post" action="/search_address" id="search_location">
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
              <!-- End Panel -->
        </div>
    </div>


<script src="{{asset('js/treeview.js')}}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
    $('.home-category').on('click', function(e){
        var id = $(this).attr('at');
        console.log(id);
        $("#category_" +  id).prop( "checked", true );
        $("#filter").submit();
    });
});
</script>
@endsection