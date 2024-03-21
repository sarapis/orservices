<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <!--   <meta name="description" content="bootstrap admin template"> -->
    <meta name="author" content="">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="Join the best company in the world!" />
    <meta property="og:url" content="http://www.sharethis.com" />
    <meta property="og:image" content="http://sharethis.com/images/logo.jpg" />
    <meta property="og:description"
        content="ShareThis is its people. It's imperative that we hire smart,innovative people who can work intelligently as we continue to disrupt the very category we created. Come join us!" />
    <meta property="og:site_name" content="ShareThis" />
    <script type='text/javascript' src='{{ env(' SHARETHIS_ACTIVATE')}}' async='async'></script>
    <title>@yield('title')| {{ $layout->site_name }}</title>
    <link rel="apple-touch-icon" href="../../frontend/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="../../frontend/assets/images/favicon.ico">
    <!-- Stylesheets -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="Stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css" />
    <!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/
    jquery-ui.css"> -->

    <link rel="stylesheet" href="../../frontend/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../frontend/global/css/bootstrap-extend.min.css">
    <link type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css"
        rel="stylesheet" />

    <link rel="stylesheet" href="../../frontend/assets/css/site.min.css">
    <link rel="stylesheet" href="/css/jquery.timepicker.min.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="../../frontend/global/vend/animsition/animsition.css">
    <link rel="stylesheet" href="../../frontend/global/vend/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="../../frontend/global/vend/switchery/switchery.css">
    <link rel="stylesheet" href="../../frontend/global/vend/intro-js/introjs.css">
    <link rel="stylesheet" href="../../frontend/global/vend/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="../../frontend/global/vend/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="../../frontend/global/vend/waves/waves.css">
    <link rel="stylesheet" href="../../frontend/assets/examples/css/uikit/dropdowns.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />


    <link href="https://sliptree.github.io/bootstrap-tokenfield/dist/css/tokenfield-typeahead.css" rel="stylesheet">
    <link href="https://sliptree.github.io/bootstrap-tokenfield/dist/css/bootstrap-tokenfield.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="stylesheet" href="../../../../frontend/global/fonts/web-icons/web-icons.css">
    <link rel="stylesheet" href="../../../../frontend/global/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="../../../../frontend/global/fonts/glyphicons/glyphicons.css">
    <link rel="stylesheet" href="../../../frontend/global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="../../../frontend/global/fonts/brand-icons/brand-icons.min.css">
    <link rel="stylesheet" href="../../../frontend/global/vend/asrange/asRange.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <link rel="stylesheet" href="../../../css/explorestyle.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

    <link rel="stylesheet" href="../../frontend/assets/examples/css/pages/register.css">

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
    <link rel="stylesheet" href="../../../css/responsive.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.jticker.js"></script>
    <script type="text/javascript">
        jQuery(function($) {
        $('.ticker').jTicker();
      });
    </script>
    <script src="../../../frontend/global/vend/breakpoints/breakpoints.js"></script>
    <script>
        Breakpoints();
    </script>

    @if($map && $map->active == 0)

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
              setTimeout(function(){
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

            var input2 = document.getElementById('location2');
            // var countries = document.getElementById('country-selector');

            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
            if(input2){
                var autocomplete2 = new google.maps.places.Autocomplete(input2);

                // Set initial restrict to the greater list of countries.
                autocomplete2.setComponentRestrictions(
                    {'country': ['us']});

                // Specify only the data fields that are needed.
                autocomplete2.setFields(
                    ['address_components', 'geometry', 'icon', 'name']);


                autocomplete2.addListener('place_changed', function() {

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
            var searchAddress = document.getElementById('searchAddress');
            // var countries = document.getElementById('country-selector');

            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
            if(searchAddress){
                var autocomplete1 = new google.maps.places.Autocomplete(searchAddress);

                // Set initial restrict to the greater list of countries.
                autocomplete1.setComponentRestrictions(
                    {'country': ['us']});

                // Specify only the data fields that are needed.
                autocomplete1.setFields(
                    ['address_components', 'geometry', 'icon', 'name']);


                autocomplete1.addListener('place_changed', function() {

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
              },1000);


          }
//      });

    </script>


    @endif
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{$map ? $map->api_key : ''}}&libraries=places&callback=initMap"
        async defer></script>
    <style>
        body {
            top: 0 !important;
        }

        #st-1 .st-btn[data-network='sharethis'] {
            /* background: transparent !important; */
            /* padding-left: 0; */
        }

        #google_translate_element {
            padding-top: 21px;
            width: 140px;
        }

        .goog-te-banner-frame.skiptranslate {
            display: none;
        }

        .goog-te-gadget img {
            display: none;
        }

        .goog-te-gadget-simple {
            background-color: transparent !important;
            border: 0 !important;
        }

        .goog-te-gadget-simple .goog-te-menu-value span {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .goog-te-menu-value span {
            font-family: 'Poppins', sans-serif !important;
        }

        .goog-te-menu-value span:nth-child(3) {
            display: none;
        }

        .goog-te-menu-value span:nth-child(5) {
            display: none;
        }

        .goog-te-menu-value span:nth-child(1) {}

        .goog-te-gadget-simple .goog-te-menu-value span:nth-of-type(1) {
            font-family: 'Font Awesome' !important;
            font-weight: normal;
            font-style: normal;
            font-size: 22px !important;
            position: relative;
            display: inline-block;
            -webkit-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            -o-transform: translate(0, 0);
            transform: translate(0, 0);
            text-rendering: auto;
            speak: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            visibility: hidden;
        }

        .goog-te-gadget-simple .goog-te-menu-value span:before {
            content: "Select Language";
            visibility: visible;
            font-family: "Neue Haas Grotesk Display Medium" !important;
            font-weight: 500;
            font-size: 16px;
        }

        .goog-te-menu-value {
            /* max-width: 22px; */
            display: inline-block;
        }
    </style>
</head>