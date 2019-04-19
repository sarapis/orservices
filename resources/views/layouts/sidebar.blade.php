<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<style>
    .pac-logo:after{
      display: none;
    }
</style>
<nav id="sidebar">
    <ul class="list-unstyled components pt-0 mb-0 sidebar-menu"> 
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services" style="display: block;padding-left: 10px;">Services</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/organizations" style="display: block;padding-left: 10px;">Organizations</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/about" style="display: block;padding-left: 10px;">About</a></button>
        </li>
    </ul>
    <div class="sidebar-header p-10">
        <div class="form-group" style="margin: 0;">
        <!--begin::Form-->
            <div class="mb-5">

                <form action="/find" method="POST" class="input-search">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <i class="input-search-icon md-search" aria-hidden="true"></i>
                    <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address">
                    
                </form>
                
            </div>
        </div>
    </div>

       <ul class="list-unstyled components pt-0"> 

            <li class="option-side">
                <!--begin::Form-->
                <form method="post" action="/search_address" class="mb-5" id="search_location">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input id="location" type="text" class="form-control search-form" name="search_address" placeholder="Search Address">
                    </div>
                </form>
            </li>

            <li class="option-side">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services_near_me" style="display: block;padding-left: 10px;">Services Near Me</a></button>
            </li> 
                    
            <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="false">Category</a>
                <ul class="collapse list-unstyled option-ul" id="projectcategory">
                    @foreach($taxonomies as $taxonomy)
                        
                        <li class="option-li"><a href="category_{{$taxonomy->taxonomy_recordid}}" class="option-record">{{$taxonomy->taxonomy_name}}</a></li>
                       
                    @endforeach
                </ul>
            </li>
            <li class="option-side">
                <a href="#cityagency" class="text-side" data-toggle="collapse" aria-expanded="false">Organization</a>
                <ul class="collapse list-unstyled option-ul" id="cityagency">
                    @foreach($organizations as $organization)
                        
                        <li class="option-li"><a href="organization_{{$organization->organization_recordid}}" class="option-record">{{$organization->organization_name}}</a></li>
                       
                    @endforeach
                </ul>
            </li>
        </ul>

</nav>
@if(isset($location) == TRUE)
    <input type="hidden" name="location" id="location" value="{{$location}}">
@else
    <input type="hidden" name="location" id="location" value="">
@endif
@if($map->active == 0)

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

    $('.ui-menu').click(function(){
        $('#search_location').submit();
    });

  
});
</script>
@else
<script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
        //$(document).ready(function(){
            function initMap() {

            var input = document.getElementById('location');

            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

            var autocomplete = new google.maps.places.Autocomplete(input);

            // Set initial restrict to the greater list of countries.
            autocomplete.setComponentRestrictions(
                {'country': ['us']});

            // Specify only the data fields that are needed.
            autocomplete.setFields(
                ['address_components', 'geometry', 'icon', 'name']);


            autocomplete.addListener('place_changed', function() {
              infowindow.close();
              marker.setVisible(false);
              var place = autocomplete.getPlace();
              if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
              }

              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
              } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setPosition(place.geometry.location);
              marker.setVisible(true);

              var address = '';
              if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
              }

              infowindowContent.children['place-icon'].src = place.icon;
              infowindowContent.children['place-name'].textContent = place.name;
              infowindowContent.children['place-address'].textContent = address;
              infowindow.open(map, marker);
            });

            // Sets a listener on a given radio button. The radio buttons specify
            // the countries used to restrict the autocomplete search.
            // function setupClickListener(id, countries) {
            //   var radioButton = document.getElementById(id);
            //   radioButton.addEventListener('click', function() {
            //     autocomplete.setComponentRestrictions({'country': countries});
            //   });
            // }

            // setupClickListener('changecountry-usa', 'us');
            // setupClickListener(
            //     'changecountry-usa-and-uot', ['us', 'pr', 'vi', 'gu', 'mp']);

            var input1 = document.getElementById('location1');
            // var countries = document.getElementById('country-selector');

            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
            if(input1){
                var autocomplete1 = new google.maps.places.Autocomplete(input1);

                // Set initial restrict to the greater list of countries.
                autocomplete1.setComponentRestrictions(
                    {'country': ['us']});

                // Specify only the data fields that are needed.
                autocomplete1.setFields(
                    ['address_components', 'geometry', 'icon', 'name']);


                autocomplete1.addListener('place_changed', function() {
                  infowindow.close();
                  marker.setVisible(false);
                  var place = autocomplete1.getPlace();
                  if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                  }

                  // If the place has a geometry, then present it on a map.
                  if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                  } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                  }
                  marker.setPosition(place.geometry.location);
                  marker.setVisible(true);

                  var address = '';
                  if (place.address_components) {
                    address = [
                      (place.address_components[0] && place.address_components[0].short_name || ''),
                      (place.address_components[1] && place.address_components[1].short_name || ''),
                      (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                  }

                  infowindowContent.children['place-icon'].src = place.icon;
                  infowindowContent.children['place-name'].textContent = place.name;
                  infowindowContent.children['place-address'].textContent = address;
                  infowindow.open(map, marker);
                });
            }
            
          }
//      });
      
    </script>


@endif

