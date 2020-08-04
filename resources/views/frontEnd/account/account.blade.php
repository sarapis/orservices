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
            <div class="col-md-6 mx-auto" >
                @if ($user) 
                <div class="col-md-12 p-0 text-right mb-20">
                    @if ($user->role_id == 1)
                    <a href="/dashboard" class="btn btn-success ml-20 "> Access Backend </a>
                    @endif
                    <a href="/account/{{$user->id}}/change_password" class="btn btn-danger mr-20"> Change Password </a>
                </div>
                @endif
                <div class="card">
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
                            @if ($organization_list)
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
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
@endsection
