<!-- Core  -->

<script src="../../frontend/global/vend/babel-external-helpers/babel-external-helpers.js"></script>
<script src="../../frontend/global/vend/tether/tether.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.5/jquery.csv.min.js"
    integrity="sha256-zGo0JbZ5Sn6wU76MyVL0TrUZUq5GLXaFnMQCe/hSwVI=" crossorigin="anonymous"></script>
{{-- <script src="../../frontend/global/vend/animsition/animsition.js"></script> --}}
<script src="../../frontend/global/vend/mousewheel/jquery.mousewheel.js"></script>
<script src="../../frontend/global/vend/asscrollbar/jquery-asScrollbar.js"></script>
<script src="../../frontend/global/vend/asscrollable/jquery-asScrollable.js"></script>
<script src="../../frontend/global/vend/ashoverscroll/jquery-asHoverScroll.js"></script>
<script src="../../frontend/global/vend/waves/waves.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- Plugins -->
<script src="../../frontend/global/vend/switchery/switchery.min.js"></script>
<script src="../../frontend/global/vend/intro-js/intro.js"></script>
<script src="../../frontend/global/vend/screenfull/screenfull.js"></script>
<script src="../../frontend/global/vend/slidepanel/jquery-slidePanel.js"></script>
<script src="../../frontend/global/vend/moment/moment.min.js"></script>
<script src="../../frontend/global/vend/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>

<!-- Scripts -->

<script src="../../frontend/global/js/State.js"></script>
<script src="../../frontend/global/js/Component.js"></script>
<script src="../../frontend/global/js/Plugin.js"></script>
<script src="../../frontend/global/js/Base.js"></script>
<script src="../../frontend/global/js/Config.js"></script>
<script src="../../frontend/assets/js/Section/Menubar.js"></script>
<script src="../../frontend/assets/js/Section/Sidebar.js"></script>
<script src="../../frontend/assets/js/Section/PageAside.js"></script>
<script src="../../frontend/assets/js/Plugin/menu.js"></script>
<!-- Config -->
<script src="../../frontend/global/js/config/colors.js"></script>
<script src="../../frontend/assets/js/config/tour.js"></script>
<script>
    Config.set('assets', '../../frontend/assets');
</script>
<!-- Page -->
<script src="../../frontend/assets/js/Site.js"></script>
<script src="../../frontend/global/js/Plugin/asscrollable.js"></script>
<script src="../../frontend/global/js/Plugin/slidepanel.js"></script>
<script src="../../frontend/global/js/Plugin/switchery.js"></script>
<script src="../../../frontend/global/js/Plugin/select2.js"></script>
<script src="../../../frontend/global/js/Plugin/multi-select.js"></script>
{{-- <script src="../../frontend/assets/examples/js/pages/faq.js"></script> --}}
{{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script> --}}
<script>
    // $('#widget').draggable();
</script>
<!--  <script>
  (function(document, window, $) {
    'use strict';
    var Site = window.Site;
    $(document).ready(function() {
      Site.run();
    });
  })(document, window, jQuery);
  </script> -->
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js">
</script>
<script type="text/javascript" src="{{ env('SHARETHIS_API') }}">
</script>
{{-- <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f55fb3cd449570011d2b2dd&product=inline-share-buttons' async='async'></script> --}}
<script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es,ht', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
    }
    setInterval(function(){
    var iframe = $('.goog-te-menu-frame');
    // $('table a .text', iframe.contents()).eq(1).text('中文');
    $('table a .text', iframe.contents()).eq(1).text('Creole');
    // $('table a .text', iframe.contents()).eq(3).text('Pусский');
    // $('table a .text', iframe.contents()).eq(4).text('Español');
    $('.st-btn[data-network="buffer"]').hide();
    $('.st-btn[data-network="blogger"]').hide();
    $('.st-btn[data-network="delicious"]').hide();
    $('.st-btn[data-network="diaspora"]').hide();
    $('.st-btn[data-network="douban"]').hide();
    $('.st-btn[data-network="evernote"]').hide();
    $('.st-btn[data-network="digg"]').hide();
    $('.st-btn[data-network="getpocket"]').hide();
    $('.st-btn[data-network="github"]').hide();
    $('.st-btn[data-network="gmail"]').hide();
    $('.st-btn[data-network="googlebookmarks"]').hide();
    $('.st-btn[data-network="hackernews"]').hide();
    $('.st-btn[data-network="instapaper"]').hide();
    $('.st-btn[data-network="line"]').hide();
    $('.st-btn[data-network="linkedin"]').hide();
    $('.st-btn[data-network="medium"]').hide();
    $('.st-btn[data-network="quora"]').hide();
    $('.st-btn[data-network="qzone"]').hide();
    $('.st-btn[data-network="refind"]').hide();
    $('.st-btn[data-network="renren"]').hide();
    $('.st-btn[data-network="skype"]').hide();
    $('.st-btn[data-network="soundcloud"]').hide();
    $('.st-btn[data-network="spotify"]').hide();
    $('.st-btn[data-network="surfingbird"]').hide();
    $('.st-btn[data-network="telegram"]').hide();
    $('.st-btn[data-network="twitch"]').hide();
    $('.st-btn[data-network="yahoomail"]').hide();
    $('.st-btn[data-network="whatsapp"]').hide();
    $('.st-btn[data-network="wordpress"]').hide();
    $('.st-btn[data-network="yelp"]').hide();
    $('.st-btn[data-network="youtube"]').hide();
    $('.st-btn[data-network="flipboard"]').hide();
    $('.st-btn[data-network="googleplus"]').hide();
    $('.st-btn[data-network="livejournal"]').hide();
    $('.st-btn[data-network="mailru"]').hide();
    $('.st-btn[data-network="meneame"]').hide();
    $('.st-btn[data-network="messenger"]').hide();
    $('.st-btn[data-network="odnoklassniki"]').hide();
    $('.st-btn[data-network="pinterest"]').hide();
    $('.st-btn[data-network="reddit"]').hide();
    $('.st-btn[data-network="tumblr"]').hide();
    $('.st-btn[data-network="vk"]').hide();
    $('.st-btn[data-network="wechat"]').hide();
    $('.st-btn[data-network="weibo"]').hide();
    $('.st-btn[data-network="print"]').hide();
    $('.st-btn[data-network="xing"]').hide();
  }, 500);
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

<script src="{{ secure_asset('js/gmaps.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
          var selected_sort="";
          $("#sidebar").mCustomScrollbar({
              theme: "minimal"
          });

          $('#sidebarCollapse').on('click', function () {
              $('#sidebar, #content').toggleClass('active');
              $('.overlay').fadeToggle();
              $('.collapse.in').toggleClass('in');
              $('a[aria-expanded=true]').attr('aria-expanded', 'false');
          });

          $('#sidebarCollapse1').on('click', function () {
              $('#sidebar, #content').toggleClass('active');
              $('.overlay').fadeOut();
              $('.collapse.in').toggleClass('in');
              $('a[aria-expanded=true]').attr('aria-expanded', 'false');
          });
          // $('#district li').click(function(){

          //     var value = $(this).html();
          //     $('#btn-district span').html("District: "+value);
          //     $('#btn-district').show();
          //     sendfilter();
          // });

          // $('#search_address').change(function(){
          //     var value = $(this).val();
          //     $('#btn-search span').html("Search: "+value);
          //     $('#btn-search').show();
          //     if(value == "")
          //       $('#btn-search').hide();
          //     sendfilter();
          // });



          // $('#projectstatus li').click(function(){
          //     var value = $('span',this).html();
          //     $('#btn-status span').html("Status: "+value);
          //     $('#btn-status').show();
          //     sendfilter();
          // });
          // $('#projectcategory li').click(function(){
          //     var value = $(this).html();

          //     $('#btn-category span').html("Category: "+value);


          //     $('#btn-category').show();
          //     sendfilter();
          // });
          // $('#cityagency li').click(function(){
          //     var value = $(this).html();
          //     $('#btn-city span').html("City: "+value);

          //     $('#btn-city').show();
          //     sendfilter();
          // });
          // $('#btn-cost').click(function(){
          //   $( "#slider-range" ).slider({
          //     values: [ 0, 1500000 ]
          //   });
          //   $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
          //     " - $" + $( "#slider-range" ).slider( "values", 1 ) );
          // });

          // $('#btn-year').click(function(){
          //   $( "#slider-range-year" ).slider({
          //     values: [ 2012, 2018 ]
          //   });
          //   $( "#amount-year" ).val(  $( "#slider-range-year" ).slider( "values", 0 ) +
          //    " - " + $( "#slider-range-year" ).slider( "values", 1 ) );
          // });

          // $('#btn-vote').click(function(){
          //   $( "#slider-range-vote" ).slider({
          //     values: [ 0, 5293 ]
          //   });
          //   $( "#amount-vote" ).val(  $( "#slider-range-vote" ).slider( "values", 0 ) +
          //     " - " + $( "#slider-range-vote" ).slider( "values", 1 ) );
          // });

          // $('#filter_buttons button').click(function(){
          //     $(this).hide();
          //     sendfilter();
          // });






    // $( "#slider-range" ).slider({
    //   range: true,
    //   min: 0,
    //   max: 1500000,
    //   values: [ 0, 1500000 ],
    //   slide: function( event, ui ) {
    //     $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

    //     var value1 = ui.values[0]; var value2 = ui.values[1];
    //     $( "#btn-cost span" ).html("Cost: " + $("#amount").val());
    //     $('#btn-cost').show();

    //   }
    // });


    // $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
    //   " - $" + $( "#slider-range" ).slider( "values", 1 ) );


    // // $( "#slider-range-year" ).slider({
    // //   range: true,
    // //   min: 2012,
    // //   max: 2018,
    // //   values: [ 2012, 2017 ],
    // //   slide: function( event, ui ) {
    // //     $( "#amount-year" ).val(  ui.values[ 0 ] + " - " + ui.values[ 1 ] );
    // //   }
    // // });

    // $('#exampleTopComponents a').click(function(){
    //     selected_sort = $(this).html();
    //     sendfilter();
    // });

    //  $( "#slider-range-year" ).slider({
    //   range: true,
    //   min: 2012,
    //   max: 2018,
    //   values: [ 2012, 2018 ],
    //   slide: function( event, ui ) {
    //     $( "#amount-year" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
    //     var value1 = ui.values[0]; var value2 = ui.values[1];
    //     $( "#btn-year span" ).html("Year of Vote: " + $("#amount-year").val());
    //     $('#btn-year').show();
    //      } });



    // $( "#amount-year" ).val(  $( "#slider-range-year" ).slider( "values", 0 ) +
    //   " - " + $( "#slider-range-year" ).slider( "values", 1 ) );

    // $( "#slider-range-vote" ).slider({
    //   range: true,
    //   min: 0,
    //   max: 5293,
    //   values: [ 0, 5293 ],
    //   slide: function( event, ui ) {
    //     $( "#amount-vote" ).val(  ui.values[ 0 ] + " - " + ui.values[ 1 ] );
    //     var value1 = ui.values[0]; var value2 = ui.values[1];
    //     $( "#btn-vote span" ).html("Vote: " + $("#amount-vote").val());
    //     $('#btn-vote').show();
    //     }
    // });

    // $( "#amount-vote" ).val(  $( "#slider-range-vote" ).slider( "values", 0 ) +
    //   " - " + $( "#slider-range-vote" ).slider( "values", 1 ) );

    // $('.ui-slider-handle.ui-corner-all.ui-state-default').mouseup(function(){
    //       sendfilter();
    //   });

          // function sendfilter(){

          //   var form_data = new FormData();
          //   // var form_data = [];
          //   // var form_name = [];
          //   form_data.append('price_min',$( "#slider-range" ).slider( "values", 0 ));
          //   form_data.append('price_max',$( "#slider-range" ).slider( "values", 1 ));
          //   form_data.append('vote_min',$( "#slider-range-vote" ).slider( "values", 0 ));
          //   form_data.append('vote_max',$( "#slider-range-vote" ).slider( "values", 1 ));
          //   form_data.append('year_min',$( "#slider-range-year" ).slider( "values", 0 ));
          //   form_data.append('year_max',$( "#slider-range-year" ).slider( "values", 1 ));
          //   if(selected_sort)
          //     form_data.append('selected_sort',selected_sort);
          //   form_data.append('is_ajax',1);
          //   form_data.append('address',$('#location').val());
          //   $('#filter_buttons button').each(function(index){

          //       if($(this).css('display') != 'none')
          //       {
          //         var values = $('span', $(this)).html();

          //         value_array = values.split(':');
          //         value_array[1] = value_array[1].replace('&amp;','&');
          //         value_array[1] = value_array[1].slice(1);
          //         form_data.append(value_array[0],value_array[1]);
          //         //form_data[] = value_array[1];
          //       }
          //   });
          //   ///
          //   $("*").animsition({
          //     inClass: 'fade-in',
          //     inDuration: 800,
          //     loading: true,
          //     loadingClass: 'loader-overlay',
          //     loadingParentElement: 'html',
          //     loadingInner: '\n      <div class="loader-content">\n        <div class="loader-index">\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>',
          //     onLoadEvent: true
          //   });

          //   $.ajaxSetup({
          //     headers: {
          //       'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          //     }
          //   })
          //   $.ajax({
          //     type: 'POST',
          //     url: "/range",
          //     data: form_data,
          //     contentType: false, // The content type used when sending data to the server.
          //     cache: false, // To unable request pages to be cached
          //     processData: false,
          //     success: function(data) {

          //       $('.loader-overlay').remove();

          //       $('#content').html(data);
          //     },
          //     error: function(errResponse) {

          //     }
          //   });
          // }
      });

  // Example Multi-Select
  // --------------------

</script>
<script>
    (function(document, window, $){
      'use strict';

      var Site = window.Site;
      $(document).ready(function(){
        Site.run();
      });
    })(document, window, jQuery);
</script>
