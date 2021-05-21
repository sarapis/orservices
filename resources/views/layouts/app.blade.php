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
        <div id='loading' style="display:none;">
            <img src="/images/loader.gif" />
        </div>
	    @yield('content')
	<div class="overlay"></div>

	</div>

	@include('layouts.footer')
	<!-- /page content -->
    <div class="modal fade" id="shareThisModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Share</h4>
            </div>
            <div class="modal-body">
                <div class="sharethis-inline-share-buttons text-left"></div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-danger btn-lg btn_delete red_btn programCloseButton waves-effect waves-classic" data-dismiss="modal">Close</button> --}}
              {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
          </div>
        </div>
      </div>

@include('layouts.script')
{{-- @if(config('app.env') != 'local') --}}
    @include('layouts.analytics')
{{-- @endif --}}
@yield('customScript')
<style>
    #loading{
        position: fixed;
        width: 100%;
        left: 0;
        top: 0;
        height: 100vh;
        text-align: center;
        z-index: 1800;
        background: #ffffff94;
    }
    #loading img {
        top: 40%;
        position: relative;
        width: 150px;
    }
</style>
</body>
</html>
