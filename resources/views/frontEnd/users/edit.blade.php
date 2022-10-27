@extends('layouts.app')
@section('title')
Create User
@stop

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="suggest_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    .form-control[disabled], .form-control[readonly] {
        background-color: #80808073;
    }
</style>
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <h4 class="card-title title_edit mb-30">
                    Edit user
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                            {{ Form::model($user, array('method' => 'PATCH', 'url' => route('users_lists.update', $user->id), 'class' => 'form-horizontal', 'files' => true)) }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>First Name * </label>
                                        {!! Form::text('first_name', null, ['class' => 'form-control','readonly' => true]) !!}
                                        @error('first_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name * </label>
                                        {!! Form::text('last_name', null, ['class' => 'form-control','readonly' => true]) !!}
                                        @error('last_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email </label>
                                        {!! Form::text('email', null, ['class' => 'form-control','readonly' => true]) !!}
                                        @error('email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('organization','Organizations', ['class' => 'col-md-3 control-label']) !!}
                                        <select class="form-control selectpicker" multiple data-live-search="true" data-size="5" id="user_organizations" name="user_organizations[]" {{ $user->role_id == 3 ? '' : 'disabled' }}>
                                            @foreach($organization_list as $key => $organization)
                                                <option value="{{$organization->organization_recordid}}" @if (in_array($organization->organization_recordid, $account_organization_list)) selected @endif>{{$organization->organization_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20 text-center">
                                    <a href="/users_lists" class="btn btn-danger btn-lg btn_delete delete-td red_btn waves-effect waves-classic"> Back</a>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-suggestion-btn">Submit</button>
                                    <a href="{{route('user.invite_user',$user->id)}}" class="btn btn-lg btn_darkblack yellow_btn  waves-effect waves-classic">Send Email Invite</a>
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
        $('select#suggest_organization').val([]).change();
    });
</script>
@endsection
