@extends('backLayout.app')
@section('title')
Edit Layout
@stop

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
        
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">NYC or Not?</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="">
                    <label>
                      <input type="checkbox" class="js-switch" value="checked" name="active"  @if($map->active==1) checked @endif />
                    </label>
                  </div>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Google Maps API Key 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" name="api_key" class="form-control col-md-7 col-xs-12" value="{{$map->api_key}}" @if($map->active==0) disabled="disabled" @endif>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">USA State 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="select2-search form-control usa-state" name="state" @if($map->active==0) disabled="disabled" @endif>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                  </select>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Map Center Lat/Long <span class="required">*</span>
                </label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input id="occupation" type="text" name="lat" class="optional form-control col-md-7 col-xs-12" value="{{$map->lat}}" required="required" @if($map->active==0) disabled="disabled" @endif>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input id="occupation" type="text" name="long" class="optional form-control col-md-7 col-xs-12" value="{{$map->long}}" required="required" @if($map->active==0) disabled="disabled" @endif>
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

@endsection
@section('scripts')
<script>
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
});
</script>
@endsection