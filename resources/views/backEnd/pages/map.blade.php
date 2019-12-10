@extends('backLayout.app')
@section('title')
Map Settings
@stop
<style>
  #map{
    height: 250px;
  }
</style>
@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Map Settings</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

          
            {{ Form::open(array('url' => ['map', 1], 'class' => 'form-horizontal form-label-left', 'method' => 'put', 'enctype'=> 'multipart/form-data')) }}
            <div class="row">
            <div class="col-md-8"> 
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <label>NYC&nbsp;&nbsp;
                      <input type="checkbox" class="js-switch" value="checked" name="active"  @if($map->active==1) checked @endif/>&nbsp;&nbsp;Out NYC
                    </label>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Google Maps API Key 
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <input type="text" name="api_key" class="form-control col-md-7 col-xs-12" value="{{$map->api_key}}" @if($map->active==0) disabled="disabled" @endif>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Map Center Lat/Long <span class="required">*</span>
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input id="occupation" type="text" name="lat" class="optional form-control col-md-7 col-xs-12" value="{{$map->lat}}" required="required" @if($map->active==0) disabled="disabled" @endif>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input id="occupation" type="text" name="long" class="optional form-control col-md-7 col-xs-12" value="{{$map->long}}" required="required" @if($map->active==0) disabled="disabled" @endif>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zoom">Browse Zoom Level
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" name="zoom" class="form-control col-md-7 col-xs-12" value="{{$map->zoom}}" @if($map->active==0) disabled="disabled" @endif>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zoom">Profile Zoom
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <input type="text" name="profile_zoom" class="form-control col-md-7 col-xs-12" value="{{$map->zoom_profile}}" @if($map->active==0) disabled="disabled" @endif>
                </div>
              </div>

            </div>
            <div class="col-md-4">
              <div id="map"></div>
            </div>
            </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <button id="send" type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>

             {!! Form::close() !!}
          
          
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Geocode</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">              
              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Scan database for geocodable locations
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <a class="btn btn-primary open-td" href="/scan_ungeocoded_location/" id="scan-btn" style="color: white;">Scan</a>                    
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output number of records with addresses but without latitude/longitude:
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5 id="invalid_location_numbers" style="color: blue; font-style: italic;">
                      {{$invalid_location_info_count}} locations are invalid.
                    </h5>
                    @if ($invalid_location_info_count == $ungeocoded_location_numbers)
                    <h5 id="ungeocoded_location_numbers" style="color: blue; font-style: italic;">
                      All valid locations have already been geocoded.
                    </h5>
                    @else
                    <h6 id="ungeocoded_location_numbers" style="color: blue; font-style: italic;">
                      {{$ungeocoded_location_numbers}} locations have not been geocoded.
                    </h6>
                    @endif
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Geocode
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <a class="btn btn-danger open-td" href="/apply_geocode/" id="apply-btn" style="color: white;">Geocode</a>                    
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output Status of Geocoding
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5 id="recent_geocoded_number" style="color: blue; font-style: italic;">
                      {{$geocode_status_text}}
                    </h5> 
                  </div>
                </div> 
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Enrich</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">              
              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Scan database for enrichable locations
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <a class="btn btn-primary open-td" href="/scan_enrichable_location/" id="scan-enrich-btn" style="color: white;">Scan</a>                    
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output number of fields without additional NYC data fields
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5 id="unenriched_location_numbers" style="color: blue; font-style: italic;">
                      {{$unenriched_location_count}}
                    </h5>
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Enrich these locations
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <a class="btn btn-danger open-td" href="/apply_enrich/" id="enrich-btn" style="color: white;">Enrich</a>                    
                  </div>
                </div> 
              </div>

              <div class="col-md-8"> 
                <div class="item form-group">
                  <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output status of the enrichment
                  </label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5 id="recent_enriched_number" style="color: blue; font-style: italic;">
                      {{$enrich_status_text}}                      
                    </h5> 
                  </div>
                </div> 
              </div>

            </div>
          </div>
        </div>
      </div>
    </div> -->

@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&callback=initMap"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.9/gmaps.min.js"></script>
<script>
$('#scan-btn').on('click', function(e){
  e.preventDefault();
  $("#ungeocoded_location_numbers").css('color', 'forestgreen');
  $("#invalid_location_numbers").css('color', 'forestgreen');
});
$('#scan-enrich-btn').on('click', function(e){
  e.preventDefault();
  $("#unenriched_location_numbers").css('color', 'forestgreen');
});

$(document).ready(function() {
    $('.js-switch').change(function(){
      var on = $('.js-switch').prop('checked');
      if(on == true){
        $('.item input').removeAttr('disabled');
        $('.usa-state').removeAttr('disabled');
      }
      else{
        $('.item input').attr('disabled','disabled'); 
        $('.usa-state').attr('disabled','disabled');
      }

    });
    $('.select2-search').select2();


    var locations = <?php print_r(json_encode($map)) ?>;

    if (locations.active == 1) {
        var map = new GMaps({
          el: '#map',
          lat: locations.lat,
          lng: locations.long,
          zoom: locations.zoom
        });
        map.addMarker({
          lat: locations.lat,
          lng: locations.long
        });
    }
    else{
        var map = new GMaps({
          el: '#map',
          lat: 40.712722,
          lng: -74.006058,
          zoom:10
        });
        map.addMarker({
          lat: 40.712722,
          lng: -74.006058
        });
    }

});
</script>
@endsection