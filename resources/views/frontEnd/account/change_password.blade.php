@extends('layouts.app')
@section('title')
Edit Service
@stop


@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-6 m-auto">
                <h4 class="card-title title_edit mb-30">
                    Change Password
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                        {!! Form::open(['route' => ['update_password',$user_info->id]]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>New Password: </label>
                                    {!! Form::password('password',['class' =>'form-control']) !!}
                                    @error('password')
                                    <span class="error-message"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Confirm Password: </label>
                                    {!! Form::password('password_confirmation',['class' =>'form-control']) !!}
                                </div>
                            </div>
                            {{-- <div class="form-group alert" style="text-align: center;">
                                <h5 style="font-style: italic; color: red;">new password is not matched with confirm password</h5>
                            </div> --}}
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="update-password-btn"> Save</button>
                                <a href="/account/{{$user_info->id}}" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="view-service-btn"> Close</a>
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
    $(document).ready(function(){
        $('.alert').hide();
        if ($('#new_password').val() != $('#confirm_password').val()) {
            $('.alert').show();
        }
    });

</script>
@endsection




