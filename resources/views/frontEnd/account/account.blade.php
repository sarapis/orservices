@extends('layouts.app')
@section('title')
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">

</style>

@section('content')

    <div class="top_header_blank"></div>
    <div class="inner_services">
        <div id="contacts-content" class="container">
            <div class="row">
                <!-- <div class="col-md-12">
                            <input type="hidden" id="checked_terms" name="checked_terms">
                        </div> -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-block">
                            @if ($account)
                                {!! $account->top_content !!}
                            @endif
                        </div>
                    </div>
                    <h4 class="card-title mb-20 text-center"
                        style="font-weight: 500;font-size: 20px;color: #1b1b1b;font-family: Neue Haas Grotesk Display Medium">
                        My Organizations
                        {{-- <div class="form-group text-left form-material m-0" data-plugin="formMaterial">
                            <img src="/frontend/assets/images/search.png" alt="" title="" class="form_icon_img"> --}}
                            {{-- </div> --}}
                            @if ( $user && $user->role_id != 1 && $organization_list->total() > 5)
                            <div class="row float-right">
                                <div class="col-md-12">
                                    <input type="text" autocomplete="off" class="form-control" name="find" placeholder="Filter Organizations" id="search_organization">
                                    <div id="organizationList"></div>
                                </div>
                            </div>
                            @endif
                    </h4>
                    <div class="col-sm-12 p-0">
                        @if ( $user && $user->role_id == 1)
                        <div class="card">
                            <div class="card-block">
                                <p style="font-size:16px">You are a system administrator so you can edit all <a href="/organizations">organizations</a>.</p>
                            </div>
                        </div>
                        @else
                        {{-- @include('frontEnd.account.organizationData') --}}
                        <div id="organizationData">

                        </div>
                        @if (count($service_list) > 0)
                        <h4 class="card-title mb-20 text-center"
                        style="font-weight: 500;font-size: 20px;color: #1b1b1b;font-family: Neue Haas Grotesk Display Medium">
                        My Services
                        <div class="row float-right">
                            <div class="col-md-12">
                                <input type="text" autocomplete="off" class="form-control" name="find" placeholder="Filter Services" id="search_service">
                            </div>
                        </div>
                    </h4>
                        <div id="serviceData">

                        </div>

                        @endif
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card mb-20">
                        <div class="card-block">
                            <div class="card-title">
                                @if ($user)
                                    <h4 class="card-title mb-30 ">
                                        <a href="">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </a>
                                        <a href="/account/{{ $user->id }}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                    </h4>

                                    <h4>
                                        <span class="subtitle"><b>First Name:</b></span>
                                        {{ $user->first_name }}
                                    </h4>
                                    <h4>
                                        <span class="subtitle"><b>Last Name:</b></span>
                                        {{ $user->last_name }}
                                    </h4>
                                    <h4>
                                        <span class="subtitle"><b>Email:</b></span>
                                        {{ $user->email }}

                                    </h4>
                                    {{-- @if ($organization_list)
                                    @if ($user->role_id != 1)
                                    <h4>
                                        <span class="subtitle"><b>Organization:</b></span>
                                        <br>
                                        @foreach ($organization_list as $organization)
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <a href="/organizations/{{$organization->organization_recordid}}"  class="panel-link">
                                            {{$organization->organization_name}} </a>
                                        <br>
                                        @endforeach
                                    </h4>
                                    @endif
                                @endif --}}
                                    <a href="/account/{{ $user->id }}/change_password" class="comment_add"> Change
                                        Password </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- @if ($user)
                    <div class="col-md-12 p-0 text-right mb-20">
                        @if ($user->role_id == 1)
                        <a href="/dashboard" class="btn btn-success ml-20 "> Access Backend </a>
                        @endif
                        <a href="/account/{{$user->id}}/change_password" class="btn btn-danger mr-20"> Change Password </a>
                    </div>
                @endif --}}
                    <div class="card">
                        <div class="card-block">
                            @if ($account)
                                @if (is_array($account->sidebar_widget) && count($account->sidebar_widget) > 0)
                                    @foreach ($account->sidebar_widget as $key => $value)
                                        @if ($user && $user->roles && isset($account->appear_for[$key]) && count($account->appear_for[$key]) > 0 && in_array($user->roles->id,$account->appear_for[$key]))
                                        <p>{!! $value !!}</p>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            organizationDataAjax('')
            serviceDataAjax('')
            let searchData = ''
            let searchServiceData = ''
            $('#search_organization').keyup(function () {
                let query = $(this).val()
                // if(query != ''){
                    searchData = query
                    organizationDataAjax(query)

                // }
                // else{
                //     $('#organizationList').fadeOut();
                // }
            })
            $('#search_service').keyup(function () {
                let query = $(this).val()
                // if(query != ''){
                    searchServiceData = query
                    serviceDataAjax(query)

                // }
                // else{
                //     $('#organizationList').fadeOut();
                // }
            })
            $(window).on('hashchange', function() {
                // si il y a un hash dans l'url
                if (window.location.hash) {
                    // var $page contient la valeur du hash et supprime le # de l'url
                    var page = window.location.hash.replace('#', '');
                    // si $page n'est pas un nombre ou si c'est <= 1, ça retourne false
                    if (page == Number.NaN || page <= 0) {
                        return false;
                    }
                    // si c'est ok, la fonction getData() est retournée avec $page en paramètre
                    else {
                        getData(page);
                    }
                }
            });

            $(document).on('click', '#organizationData .pagination a', function(e) {
                e.preventDefault();
                $('.pagination li').removeClass('active');
                $(this).parent('li').addClass('active');
                var url = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];
                // getData(page,url);
                getData(page,url,searchData);
            });

            function getData(page,url,searchData) {
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: url,
                    method:"post",
                    data:{_token,searchData},
                    success:function(response){
                        // $('#organizationList').fadeIn();
                        $('#organizationData').empty().html(response.data)
                        // location.hash = page;
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
            function organizationDataAjax(searchData){
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ route('account.fetch_organization') }}",
                    method:"post",
                    data:{_token,searchData},
                    success:function(response){
                        // $('#organizationList').fadeIn();
                        $('#organizationData').empty().html(response.data)
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
            $(document).on('click', '#serviceData .pagination a', function(e) {
                e.preventDefault();
                $('.pagination li').removeClass('active');
                $(this).parent('li').addClass('active');
                var url = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];
                // getData(page,url);
                getServiceData(page,url,searchServiceData);
            });

            function getServiceData(page,url,searchServiceData) {
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: url,
                    method:"post",
                    data:{_token,searchServiceData},
                    success:function(response){
                        // $('#organizationList').fadeIn();
                        $('#serviceData').empty().html(response.data)
                        // location.hash = page;
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
            function serviceDataAjax(searchServiceData){
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ route('account.fetch_account_service') }}",
                    method:"post",
                    data:{_token,searchServiceData},
                    success:function(response){
                        // $('#organizationList').fadeIn();
                        $('#serviceData').empty().html(response.data)
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    </script>
@endsection

<style>
    .card-columns {
        column-count: 2 !important;
    }

    iframe {
        width: 100% !important
    }

</style>
