@extends('layouts.app')
@section('title')
Edit Service
@stop

<style type="text/css">
    button[data-id="account_organizations"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card all_form_field">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Edit Account Info</p>
                        </h4>
                        {!! Form::model($user_info,['route'=>['account.update',$user_info->id],'method' => 'PUT']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>First Name: </label>
                                    {!! Form::text('first_name',null,['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Last Name: </label>
                                    {!! Form::text('last_name',null,['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Email: </label>
                                    {!! Form::email('email',null,['class' => 'form-control','readonly' => 'readonly']) !!}
                                </div>
                            </div>
                            @if ($user_info->role_id != 1)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Organizations: </label>
                                    <select class="form-control selectpicker" multiple data-live-search="true" data-size="5"
                                        id="account_organizations" name="account_organizations[]">
                                        @foreach($organization_list as $key => $organization)
                                        <option value="{{$organization->organization_recordid}}" @if (in_array($organization->
                                            organization_recordid, $account_organization_list)) selected
                                            @endif>{{$organization->organization_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-service-btn"> Save</button>
                                <a href="{{ route('account.show',$user_info->id) }}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="view-service-btn"> Close</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#account_organizations").selectpicker("");
    });


</script>
@endsection
