
$(document).ready(function () {
  
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
        $('.overlay').fadeIn();
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('#sidebarCollapse1').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
        $('.overlay').fadeOut();
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
    $('#district li').click(function(){

        var value = $(this).html();
        $('#btn-district span').html("District:"+value);
        $('#btn-district').show();
        sendfilter();
    });
    $('#projectstatus li').click(function(){
        var value = $('span',this).html();
        $('#btn-status span').html("Status:"+value);
        $('#btn-status').show();
        sendfilter();
    });
    $('#projectcategory li').click(function(){
        var value = $(this).html();

        $('#btn-category span').html("Category:"+value);
        $('#btn-category').show();
        sendfilter();
    });
    $('#cityagency li').click(function(){
        var value = $(this).html();
        $('#btn-city span').html("City:"+value);

        $('#btn-city').show();
        sendfilter();
    });
    $('#filter_buttons button').click(function(){
        $(this).hide();
        sendfilter();
    });
    function sendfilter(){
     
      var form_data = new FormData();    
      // var form_data = [];
      // var form_name = [];
      $('#filter_buttons button').each(function(index){
          
          if($(this).css('display') != 'none')
          {
            var values = $('span', $(this)).html();

            value_array = values.split(':');
            value_array[1] = value_array[1].replace('&amp;','&');    

            form_data.append(value_array[0],value_array[1]);
            //form_data[] = value_array[1];
          }
      });
      ///
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      })    
      $.ajax({
        type: 'POST',
        url: "/range",
        data: form_data,
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false,
        success: function(data) {

          $('#content').html(data);
        },
        error: function(errResponse) {
        
        }
      });
    }
});