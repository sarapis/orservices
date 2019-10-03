@extends('layouts.app')
@section('title')
Feedback
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
</style>
@section('content')
    <div class="row pt-20 pl-15"  style="margin-right: 0">
        <div class="col-xl-7 col-md-7">
          <!-- Panel -->
          <div class="panel">
            <div class="panel-body">
                {!! $feedback->body !!}
            </div>
          </div>
          <!-- End Panel -->
        </div>
        <div class="col-xl-5 col-md-5">
          <!-- Panel -->
            <div class="panel">
                <div class="panel-body bg-custom">
                    <div class="form-group">
                        <h4 class="text-white">Does your NYC neighborhood do PB?</h4>
                        <form method="get" action="/explore">
                          <div class="form-group">
                              
                                <div class="input-search">
                                    <i class="input-search-icon md-search" aria-hidden="true"></i>
                                    <input id="location" type="text" class="form-control text-black" name="address" placeholder="Search Street Address" style="border-radius:0;">
                                </div>
                              
                          </div>
                          <button type="submit" class="btn_findout"><h4 class="text-white mb-0">FIND OUT!</h4></button>
                        </form>
                    </div>
                </div>
                <div class="panel-body">
                    <p>What do people fund when given the opportunity? Check out how New York neighborhoods are spending public money and explore PB-generated projects here</p>
                </div>
            </div>
        </div>
          <!-- End Panel -->
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

