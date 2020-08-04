@extends('layouts.app')
@section('title')
Add Interaction
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="session_method"], button[data-id="session_disposition"], button[data-id="session_status"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')
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
                            <p>Edit Session Info</p>
                        </h4>
                        <form action="/session_info/{{$session->session_recordid}}/update" method="GET">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Session Status: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="session_status"
                                            name="session_status" data-size="5">
                                            @foreach($session_status_list as $key => $session_status)
                                            <option value="{{$session_status}}" @if ($session->session_status == $session_status) selected @endif>{{$session_status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Session Notes: </label>
                                        <input class="form-control selectpicker" type="text" id="session_notes" name="session_notes" value="{{$session->session_notes}}">
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="back-session-btn"> Back</button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-session-btn"> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#back-session-btn').click(function() {
        history.go(-1);
        return false;
    });

</script>
@endsection
