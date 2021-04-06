<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
@include('layouts.style')
@include('layouts.header')

    <!-- page content -->
    <div class="page pl-0 pr-0">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('status') == 'error' ? 'danger' : Session::get('status') }} alert-dismissable custom-success-box"
            style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> {{ session()->get('message') }} </strong>
        </div>
        @endif
        @if (Auth::user() && Auth::user()->roles == null )
        <div class="alert alert-warning alert-dismissable custom-success-box"
        style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> Your Account is Under Review. </strong>
        </div>
        @endif
	    @yield('content')
	<div class="overlay"></div>

	</div>
	@include('layouts.footer')
	<!-- /page content -->


@include('layouts.script')
{{-- @if(config('app.env') != 'local') --}}
    @include('layouts.analytics')
{{-- @endif --}}
@yield('customScript')
</body>
</html>
