@extends('backLayout.app')
@section('title')
Add Country
@stop

@section('content')
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('error') }} </strong>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session()->get('success') }} </strong>
</div>
@endif


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Localization</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'add_country.save_country','enctype' => 'multipart/form-data']) !!}
                    <div class="row">
                        <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Select County</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::select('country', $countries, ($layout->localization ?? 'US'),['class' => 'form-control']) !!}
                                    {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Select Timezone</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::select('timezone',$zones_array, ($layout->timezone ?? 'UTC') ,['class' => 'form-control selectpicker','data-live-search' => 'true']) !!}
                                    {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Current Time</label>
                            <div class="col-sm-6">
                                <div class="form-group" id="currentDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right"></label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="submit" value="Save" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" integrity="sha512-hUhvpC5f8cgc04OZb55j0KNGh4eh7dLxd/dPSJ5VyzqDWxsayYbojWyl5Tkcgrmb/RVKCRJI1jNlRbVP4WWC4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.45/moment-timezone.min.js" integrity="sha512-EUm65YBi2BbIovgy8ZNxiNEa0xnA3LSxYYcMuYdCpxwNILaHa+IXNJcnJQo9AugzC3uQ9tsf0n2aoSRaHIfQjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script>
    $(function() {
        $('.selectpicker').selectpicker();
    });
    // moment.tz.setDefault("America/New_York");
    var tzString = "{{ $layout->timezone ?? 'UTC' }}"
    // console.log(moment(moment().format('Y-MM-DD hh:mm:ss a')).tz("America/New_York").format(),'tzString')
    // moment.tz.setDefault(tzString);
    setInterval(() => {
        // var currentDate = moment().format('DD-MM-YYYY HH:mm:ss');
        $('#currentDate').empty()
        $('#currentDate').append(new Date().toLocaleString("en-US", {timeZone: tzString}))
        // $('#currentDate').append(moment().format('Y-MM-DD hh:mm:ss a'));
    }, 1000);
</script>
@endsection

