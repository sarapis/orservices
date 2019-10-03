@extends('layouts.app')
@section('title')
About
@stop
<style>
   .navbar-container.container-fluid{
        display: none !important;
    }
    @media (max-width: 991px){
        .page {
            padding-top: 0px !important;
        }
    }
    .pac-logo:after{
      display: none;
	}
	.browse_category h3::after { left: 0 !important;}
</style>
@section('content')
    <div class="home-sidebar">
    @include('layouts.sidebar')
    </div>
    <div id="content" class="container m-0" style="width: 100%;">
		<!-- start aboutus content -->
			<div class="browse_category">
				<div class="page-content">
					<div class="py-15">
						<div class="text-center">
							<h3>About Us</h3>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2"></div>
							<div class="col-lg-8 col-md-8 col-sm-12 col-12">
								{!! $about->body !!}
							</div>
						</div>
					</div>
				</div>
			</div>

    </div>
<!--     <div class="fade-in">
      <div class="loader-overlay">
        <div class="loader-content">
          <div class="loader-index">
          </div>
        </div>
      </div>
    </div> -->


<!-- <script>
  function initAutocomplete() {

      var options = {
        types: ['geocode'],
        componentRestrictions: { country: 'us'}
       };

      var input = document.getElementById('location');
      var searchBox = new google.maps.places.Autocomplete(input, options);
  }
  </script> -->

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-RQR_KenWPqcgUbOtMLreNVWeTV1wcSo&libraries=places&callback=initAutocomplete" async defer></script> -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script>
    $(document).ready(function(){
        if(screen.width < 768){
          var text= $('.navbar-header').css('height');
          var height = text.slice(0, -2);
          $('.page').css('padding-top', height);
          $('#content').css('top', height);
        }
        else{
          var text= $('.navbar-header').css('height');
          var height = 0;
          $('.page').css('margin-top', height);
        }
    });
</script> -->
<script>

$(function () {
    var getData = function (request, response) {
        $.getJSON(
            "https://geosearch.planninglabs.nyc/v1/autocomplete?text=" + request.term,
            function (data) {
                response(data.features);
                
                var label = new Object();
                for(i = 0; i < data.features.length; i++)
                    label[i] = data.features[i].properties.label;
                response(label);
            });
    };
 
    var selectItem = function (event, ui) {
        $("#location").val(ui.item.value);
        return false;
    }
 
    $("#location").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            console.log(selectItem);

        }
    });
});
</script>
@endsection

