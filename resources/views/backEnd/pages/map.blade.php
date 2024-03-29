@extends('backLayout.app')
@section('title')
    Map Settings
@stop
<style>
    #map {
        height: 250px;
    }
</style>
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Map</h2>
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
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Apply Unique NYC Map
                                    Settings</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <label>On&nbsp;&nbsp;
                                        <input type="checkbox" class="js-switch" value="checked" name="active"
                                               @if($map->active==1) checked @endif/>&nbsp;&nbsp;Off
                                    </label>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Google Maps JavaScript API Key
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input class="form-control" type="text"
                                           value="{{ '********************' . substr($map->javascript_map_key, -7) }}"
                                           name="api_key1" id="api_key1" disabled>
                                    <input class="form-control" type="text" value="{{ $map->javascript_map_key }}" name="javascript_map_key"
                                           id="api_key2" style="display: none;" >
                                    <button type="button" id="clickChangeKey">Change</button>
                                    @if ($errors->first('javascript_map_key'))
                                        <div class="alert alert-danger">{{ $errors->first('javascript_map_key') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Google Maps Geocoding API Key
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <input class="form-control" type="text"
                                           value="{{ '********************' . substr($map->geocode_map_key, -7) }}"
                                           name="api_key1" id="geocode_api_key1" disabled>
                                    <input class="form-control" type="text" value="{{ $map->geocode_map_key }}" name="geocode_map_key"
                                           id="geocode_api_key2" style="display: none;" >
                                    <button type="button" id="clickChangeKeyGeocode">Change</button>
                                    @if ($errors->first('geocode_map_key'))
                                        <div class="alert alert-danger">{{ $errors->first('geocode_map_key') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('distance_unit') ? 'has-error' : ''}}">
                                <label class="col-sm-3 control-label text-right">Select Distance Unit</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        {!! Form::select('distance_unit',['miles' => 'miles', 'km' => 'km'],$map->distance_unit,['class' => 'form-control']) !!}
                                        {!! $errors->first('distance_unit', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Map Center
                                    Lat/Long <span class="required">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input id="occupation" type="text" name="lat"
                                           class="optional form-control col-md-7 col-xs-12" value="{{$map->lat}}"
                                           required="required" @if($map->active==0) disabled="disabled" @endif>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input id="occupation" type="text" name="long"
                                           class="optional form-control col-md-7 col-xs-12" value="{{$map->long}}"
                                           required="required" @if($map->active==0) disabled="disabled" @endif>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zoom">Browse Zoom Level
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" name="zoom" class="form-control col-md-7 col-xs-12"
                                           value="{{$map->zoom}}" @if($map->active==0) disabled="disabled" @endif>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zoom">Profile Zoom
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" name="profile_zoom" class="form-control col-md-7 col-xs-12"
                                           value="{{$map->zoom_profile}}"
                                           @if($map->active==0) disabled="disabled" @endif>
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
                                <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Scan database to
                                    add NYC specific information to locations
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <a class="btn btn-primary open-td" href="/scan_ungeocoded_location/" id="scan-btn"
                                       style="color: white;">Scan</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="item form-group">
                                <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output number of
                                    records with addresses but without latitude/longitude:
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
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <a class="btn btn-danger open-td" href="/apply_geocode/" id="apply-btn"
                                       style="color: white;">Geocode</a>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <a class="btn btn-info open-td" href="/apply_geocode_again/" id="apply-btn"
                                       style="color: white;">Geocode Everything Again</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="item form-group">
                                <label class="control-label col-md-6 col-sm-6 col-xs-12" for="email">Output Status of
                                    Geocoding
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{$map->javascript_map_key}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.9/gmaps.min.js"></script>
    <script src='//cdn.jsdelivr.net/gmaps4rails/2.1.2/gmaps4rails.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore.js'></script>
    <script>
        $(document).ready(function () {
            $('#clickChangeKey').click(function () {
                $('#api_key1').hide()
                $('#api_key2').show()
                $('#api_key2').val('')
                $(this).hide()
            })
            $('#clickChangeKeyGeocode').click(function () {
                $('#geocode_api_key1').hide()
                $('#geocode_api_key2').show()
                $('#geocode_api_key2').val('')
                $(this).hide()
            })
        })
    </script>
    <script>
        $('#scan-btn').on('click', function (e) {
            e.preventDefault();
            $("#ungeocoded_location_numbers").css('color', 'forestgreen');
            $("#invalid_location_numbers").css('color', 'forestgreen');
        });
        $('#scan-enrich-btn').on('click', function (e) {
            e.preventDefault();
            $("#unenriched_location_numbers").css('color', 'forestgreen');
        });

        $(document).ready(function () {
            $('.js-switch').change(function () {
                var on = $('.js-switch').prop('checked');
                if (on == true) {
                    $('.item input').removeAttr('disabled');
                    $('.usa-state').removeAttr('disabled');
                } else {
                    $('.item input').attr('disabled', 'disabled');
                    $('.usa-state').attr('disabled', 'disabled');
                }

            });
            $('.select2-search').select2();


            var locations = <?php print_r(json_encode($map)) ?>;


            let position
            let zoom
            if (locations.active == 1) {
                position = {
                    lat: parseFloat(locations.lat),
                    lng: parseFloat(locations.long),
                }
                zoom = locations.zoom
            } else {
                position = {
                    lat: 40.712722,
                    lng: -74.006058,
                }
                zoom = 10
            }
            setTimeout(() => {
                async function initMap() {
                    const { Map } = await google.maps.importLibrary("maps");
                    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
                        "marker",
                    );

                    const map = new Map(document.getElementById("map"), {
                        center: position,
                        zoom: zoom,
                        mapId: "4504f8b37365c3d0",
                    });
                    const marker = new AdvancedMarkerElement({
                            map,
                            position: position,
                            title: location.location_name,
                        });
                }
                initMap()
            }, 2000);
        });
    </script>
@endsection
