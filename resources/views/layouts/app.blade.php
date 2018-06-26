<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
@include('layouts.style')
@include('layouts.header')

    <!-- page content -->
    <div class="page container-fluid pl-0 pr-0">
	    @yield('content')
	<div class="overlay"></div>   
	
	</div>
	@include('layouts.footer')
	<!-- /page content -->


@include('layouts.script')