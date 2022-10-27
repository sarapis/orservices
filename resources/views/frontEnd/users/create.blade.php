@extends('layouts.app')
@section('title')
Create User
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="suggest_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')

{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-6 m-auto">
                <h4 class="card-title title_edit mb-30">
                    Create user
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                        {{-- <form action="/add_new_suggestion" method="GET"> --}}
                            {!! Form::open(['route' => 'users_lists.store']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>First Name <span style="color: red;">*</span> </label>
                                        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                                        @error('first_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name <span style="color: red;">*</span> </label>
                                        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                                        @error('last_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email <span style="color: red;">*</span> </label>
                                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                        @error('email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password <span style="color: red;">*</span></label>
                                        {!! Form::password('password', ['class' => 'form-control']) !!}
                                        @error('password')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password Confirmation <span style="color: red;">*</span></label>
                                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                                        @error('password_confirmation')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Role </label>
                                        {!! Form::select('role', $roles, null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12 mt-20 text-center">
                                    <!-- <a href="/contacts" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="view-contact-btn"><i class="fa fa-arrow-left"></i> Back</a> -->
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-suggestion-btn">Submit</button>
                                </div>
                            </div>
                        {{-- </form> --}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $('#back-contact-btn').click(function() {
    //     history.go(-1);
    //     return false;
    // });
    $(document).ready(function() {
        $('select#suggest_organization').val([]).change();
    });
</script>
@endsection
