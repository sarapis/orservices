@extends('layouts.app')
@section('title')
Suggest a Change
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="suggest_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-6 m-auto">
                <div class="card all_form_field">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Suggest A Change</p>
                        </h4>
                        {{-- <form action="/add_new_suggestion" method="GET"> --}}
                            {!! Form::open(['route' => 'suggest.store']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Organization * </label>
                                        <p>Select the organization for which you're suggesting a change</p>
                                        {!! Form::select('suggest_organization',$organizations,null,['class'=> 'form-control selectpicker','id' => 'suggest_organization','data-live-search' => 'true','data-size' => '5']) !!}
                                         @error('suggest_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Suggestion * </label>
                                        <p>Explain what should be changed: Please be specific-reference the field that contains information which is incorrect or incomplete, and tell us what should be there instead. Thank you!</p>
                                        <textarea id="suggest_content" name="suggest_content" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Your Name * </label>
                                        {!! Form::text('name',null,['class' => 'form-control','id' => 'name']) !!}
                                        @error('name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Your Email * </label>
                                        {!! Form::email('email',null,['class' => 'form-control','id' => 'email']) !!}
                                        @error('email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Your Phone </label>
                                        {!! Form::text('phone',null,['class' => 'form-control','id' => 'phone']) !!}
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
