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
                <h4 class="card-title mb-20 text-center" style="font-weight: 500;font-size: 20px;color: #1b1b1b;font-family: Neue Haas Grotesk Display Medium">My Organizations</h4>
                <div class="col-sm-12 p-0 card-columns">
                    @if ($organization_list && count($organization_list) > 0 && $user && $user->role_id != 1)
                    @foreach($organization_list as $organization)
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">
                                <a href="/organizations/{{$organization->organization_recordid}}" class="notranslate title_org">{{ $organization->organization_name }}</a>
                            </h4>
                            <p class="card-text" style="font-weight:400;">{!! Str::limit($organization->organization_description, 200) !!}</p>
                            <h4><span>Number of Services:@php
                                        if(count($organization->services) == 0){
                                            $organization_services = $organization->getServices->count();
                                        }
                                    @endphp
                                    @if(isset($organization->services) && count($organization->services) != 0)
                                        {{$organization->services->count()}}
                                    @elseif(count($organization->services) == 0)
                                        {{ $organization->getServices->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <a href="/organizations/{{$organization->organization_recordid}}">
                                    <img src="/frontend/assets/images/arrow_right.png" alt="" title="" class="float-right">
                                </a>
                            </h4>
                            <h4> <span> Last updated : </span> {{ $organization->updated_at }}</h4>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="card">
                        <div class="card-block">
                            <p>You are a system administrator so you can edit all <a href="/organizations">organizations</a> .</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-5" >
                <div class="card mb-20">
                    <div class="card-block">
                        <div class="card-title">
                            @if ($user)
                                <h4 class="card-title mb-30 ">
                                    <a href="">
                                        {{$user->first_name}} {{$user->last_name}}
                                    </a>
                                    <a href="/account/{{$user->id}}/edit" class="float-right" >
                                        <i class="icon md-edit mr-0"></i>
                                    </a>
                                </h4>

                                <h4>
                                    <span class="subtitle"><b>First Name:</b></span>
                                    {{$user->first_name}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Last Name:</b></span>
                                    {{$user->last_name}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Email:</b></span>
                                    {{$user->email}}

                                </h4>
                                {{-- @if ($organization_list)
                                    @if ($user->role_id != 1)
                                    <h4>
                                        <span class="subtitle"><b>Organization:</b></span>
                                        <br>
                                        @foreach($organization_list as $organization)
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <a href="/organizations/{{$organization->organization_recordid}}"  class="panel-link">
                                            {{$organization->organization_name}} </a>
                                        <br>
                                        @endforeach
                                    </h4>
                                    @endif
                                @endif --}}
                                <a href="/account/{{$user->id}}/change_password" class="comment_add"> Change Password </a>
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
                            <p>{!! $account->sidebar_widget !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
@endsection

<style>
    .card-columns {
        column-count: 2 !important;
    }
    iframe{width:100% !important}
</style>
