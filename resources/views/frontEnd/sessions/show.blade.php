@extends('layouts.app')
@section('title')
Session Profile Page
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style type="text/css">
    button[data-id="interaction_method"], button[data-id="interaction_disposition"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
			<div class="col-md-6">
				<div class="card">
                    <div class="card-block" style="padding-bottom: 40px;">
                        <div class="row">
                            <div class="col-md-12">
                            	<h4 class="card-title mb-20">
                                    <!-- Info box  -->
                                    <div id="carouselButtons" class="float-right">
                                        <button id="playButton" type="button" class="btn btn-success  btn-xs">
                                            <i class="fas fa-play"></i>
                                         </button>
                                        <button id="pauseButton" type="button" class="btn btn-danger btn-xs">
                                            <i class="far fa-pause-circle"></i>
                                        </button>
                                    </div>
                                </h4>
                                <!-- <h4>
                                    <span class="subtitle"><b>Timer: </b></span>
                                    <label id="minutes">00</label>:<label id="seconds">00</label>
                                </h4> -->
                                <h4>
                                    <span class="subtitle"><b>Session ID: </b></span>
                                    {{$session->session_recordid}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>User Name: </b></span>
                                    {{$session->user->first_name}} {{$session->user->last_name}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Organization: </b></span>
                                    <a class="panel-link" href="/organizations/{{$session->organization->organization_recordid}}">{{$session->organization->organization_name}}</a>
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Start Time: </b></span>
                                    <label id="start-time">{{$session->session_start}}</label>
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>End Time: </b></span>
                                    <label id="end-time">{{$session->session_end}}</label>
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Duration: </b></span>
                                    <label id="duration">{{$session->session_duration}}</label>
                                </h4>
                                <a href="/session_info/{{$session->session_recordid}}/edit"  class="btn btn-success btn-xs float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                <h4>
                                    <span class="subtitle"><b>Status: </b></span>
                                    {{$session->session_verification_status}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Notes: </b></span>
                                    {{$session->session_notes}}
                                </h4>
                            </div>
                            <!-- <div class="col-md-5">
                                <a href="/session_info/{{$session->session_recordid}}/edit"  class="btn btn-success btn-xs float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                <h4>
                                    <span class="subtitle"><b>Status: </b></span>
                                    {{$session->session_verification_status}}
                                </h4>
                                <h4>
                                    <span class="subtitle"><b>Notes: </b></span>
                                    {{$session->session_notes}}
                                </h4>

                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="card all_form_field">
                    <div class="card-block">
                    	<h4 class="card-title mb-30 ">
                            <p>Add Interaction</p>
                        </h4>
                        <div id="facility-create-content">
                            <form action="/add_interaction" method="POST">
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Interaction Method: </label>
                                            <select class="form-control selectpicker" data-live-search="true" id="interaction_method" name="interaction_method" data-size="5">
                                                @foreach($method_list as $key => $method)
                                                <option value="{{$method}}">{{$method}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" id="session_recordid" name="session_recordid" value="{{$session->session_recordid}}">
                                    </div>
                                    <div class="col-md-4 p-0">
                                        <div class="form-group">
                                            <label>Interaction Disposition: </label>
                                            <select class="form-control selectpicker" data-live-search="true" id="interaction_disposition"
                                                name="interaction_disposition" data-size="5">
                                                @foreach($disposition_list as $key => $disposition)
                                                <option value="{{$disposition}}">{{$disposition}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Records Edited: </label>
                                            <input class="form-control selectpicker" type="text" id="interaction_records_edited" name="interaction_records_edited" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Interaction  Notes: </label>
                                            <input class="form-control selectpicker" type="text" id="interaction_notes" name="interaction_notes" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-25 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic btn_padding" id="save-interaction-btn"> Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Interaction Log
                            </p>
                            {!! Form::open(['route'=>'interactionExport']) !!}
                            <input type="hidden" name="session_recordid" value="{{ $id }}">
                            <button type="submit" class="btn btn-info pull-right mb-10">export</a>
                            {!! Form::close() !!}
                        </h4>
                        <div class="table-responsive">
                            <table class="table dataTable " id="tbl-session-log">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Timestamp</th>
                                        <th>Notes</th>
                                        <th>Disposition</th>
                                        <th>Records Edited</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interaction_list as $key => $interaction)
                                    <tr>
                                        <td>{{$interaction->interaction_method}}</td>
                                        <td>{{$interaction->interaction_timestamp}}</td>
                                        <td>{{$interaction->interaction_notes}}</td>
                                        <td>{{$interaction->interaction_disposition}}</td>
                                        <td>{{$interaction->interaction_records_edited}}</td>
                                    </tr>
                                    @endforeach
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<script>

	var minutesLabel = document.getElementById("minutes");
	var secondsLabel = document.getElementById("seconds");
	var startTimeLabel = document.getElementById("start-time");
	var endTimeLabel = document.getElementById("end-time");
    var durationLabel = document.getElementById("duration");
	var totalSeconds = 0;
	var interval = null;
	var startTime, endTime;

	$("#playButton").on('click', function(e) {
      	e.preventDefault();
	    console.log('timer has been started');

	    startTime = new Date();
        console.log(startTime);
		startTimeLabel.innerHTML = startTime;

        $.ajax({
            type: 'post',
            url: '/session_start',
            data: {
                "_token": "{{ csrf_token() }}",
                "session_id": '{{$session->session_recordid}}',
                "session_start_time": startTime
            }
        })

	    interval = setInterval(setTime, 1000);

		function setTime() {
		  ++totalSeconds;
		  secondsLabel.innerHTML = pad(totalSeconds % 60);
		  minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
		}

		function pad(val) {
		  var valString = val + "";
		  if (valString.length < 2) {
		    return "0" + valString;
		  } else {
		    return valString;
		  }
		}
  	});

  	$("#pauseButton").on('click', function(e) {
	    e.preventDefault();
	    console.log('timer has been stop');
	    clearInterval(interval); // stop interval
	    endTime = new Date();
	    endTimeLabel.innerHTML = endTime;

        $.ajax({
            type: 'post',
            url: '/session_end',
            data: {
                "_token": "{{ csrf_token() }}",
                "session_id": '{{$session->session_recordid}}',
                "session_end_time": endTime
            },
            success: function (response) {
                durationLabel.innerHTML = response;
            }
        })

  	});

</script>
@endsection
