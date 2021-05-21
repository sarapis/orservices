<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

@include('backLayout.header')

<div class="col-md-3 left_col">
	@include('backLayout.sidebarMenu')
</div>
<!-- top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		@include('backLayout.topMenu')
	</div>
</div>
<!-- /top navigation -->
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		@if (Session::has('message'))
		<div class="alert alert-{{ Session::get('status') == 'error' ? 'danger' : Session::get('status') }} alert-dismissable custom-success-box"
			style="margin: 15px;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong> {{ session()->get('message') }} </strong>
		</div>
		@endif
        <div id='loading' style="display:none;">
            <img src="/images/loader.gif" />
        </div>
		@yield('content')
	</div>
</div>
<!-- /page content -->
@include('backLayout.footer')
