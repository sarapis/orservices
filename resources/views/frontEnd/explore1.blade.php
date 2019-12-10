<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="../../../frontend/global/vend/footable/footable.core.css">
  <link rel="stylesheet" href="../../frontend/assets/examples/css/tables/footable.css">

<style type="text/css">
.table a{
    text-decoration:none !important;
    color: #424242;
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
    position: absolute;
    right: 25px;
    font-size: 25px;
    color: #000000;
}
#content{
    padding-top: 0 !important;

}
/*.page{
    margin-top: 55px !important;
}*/
#map{
    position: fixed !important;
}
#example_wrapper .row{
  width: 100%;
  margin: 0;
}
.col-sm-12{
  padding: 0;
}
</style>
<script src="../../../frontend/global/vend/breakpoints/breakpoints.js"></script>
<script>
Breakpoints();
</script>

  <div class="row" style="margin-right: 0">
      <div class="col-md-8 pr-0">

          <div class="panel m-15 content-panel">
              <div class="panel-body p-0">

                      <table class="table table-striped toggle-arrow-tiny" id="example" style="width:100%">
                          <thead>
                              <tr class="footable-header">
                                  <th class="text-center">Status</th>
                                  <th data-toggle="true" class="pr-20">Name</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($projects as $project)
                              <tr> 
                                  <td class="text-center">
                                      @if($project->project_status_category!='')
                                          @if($project->project_status_category=='Complete')
                                              <button type="button" class="btn btn-floating btn-success btn-xs waves-effect waves-classic"><i class="icon fa-check" aria-hidden="true"></i></button>
                                          @elseif($project->project_status_category=='Project Status Needed')
                                              <button type="button" class="btn btn-floating  btn-xs waves-effect waves-classic"></button>
                                          @elseif($project->project_status_category=='Not funded')
                                              <button type="button" class="btn btn-floating btn-danger btn-xs waves-effect waves-classic"><i class="icon fa-remove" aria-hidden="true"></i></button>
                                          @else
                                              <button type="button" class="btn btn-floating btn-warning btn-xs waves-effect waves-classic"><i class="icon fa-minus" aria-hidden="true"></i></button>
                                          @endif
                                      @endif
                                  </td>
                                  <td>
                                      @if($project->project_title!='')
                                          <a href="/profile/{{$project->id}}">{{$project->project_title}}</a>
                                      @endif
                                  </td>
                                  <td><i class="fa fa-chevron-right" style="padding-top: 8px;color: #000000;float: right;padding-right: 10px;"></i></td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>

              </div>
          </div>
      </div>
      <div class="col-md-4 p-0">
          <div id="map" style="position: fixed !important;width: 28%;"></div>
      </div>
  </div>
<script>
$(document).ready(function(){
    $(document).ajaxStart(function(){

         $("*").animsition({
          inClass: 'fade-in',
          inDuration: 800,
          loading: true,
          loadingClass: 'loader-overlay',
          loadingParentElement: 'html',
          loadingInner: '\n      <div class="loader-content">\n        <div class="loader-index">\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>',
          onLoadEvent: true
        });

        var address_district = <?php echo json_encode($address_district); ?>;
        if( address_district != ''){
        
            $('#btn-district span').html("District:"+address_district);
            $('#btn-district').show();
        };
    });
    $(document).ajaxComplete(function(){

        $('.loader-overlay').remove();

    });
});
</script>

<script>
  var locations = <?php print_r(json_encode($projects)) ?>;
  

     var sumlat = 0.0;
    var sumlng = 0.0;
    for(var i = 0; i < locations.length; i ++)
    {
        sumlat += parseFloat(locations[i].latitude);
        sumlng += parseFloat(locations[i].longitude);

    }

    var avglat = sumlat/locations.length;
    var avglng = sumlng/locations.length;

  

    if(!avglat){
        avglat = 40.730981;
        avglng = -73.998107
    }

    var mymap = new GMaps({
      el: '#map',
      lat: avglat,
      lng: avglng,
      zoom:10
    });


    $.each( locations, function(index, value ){
        var icon;
        if(value.project_status_category == "Complete")
            icon = '<button type="button" class="btn btn-floating btn-success btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-check" aria-hidden="true"></i></button>';
        else if(value.project_status_category == "Project Status Needed")
            icon = '<button type="button" class="btn btn-floating  btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"></button>';
        else if(value.project_status_category == "Not funded")
            icon = '<button type="button" class="btn btn-floating btn-danger btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-remove" aria-hidden="true"></i></button>';
        else
            icon ='<button type="button" class="btn btn-floating btn-warning btn-xs waves-effect waves-classic mr-5" style="box-shadow:none;"><i class="icon fa-minus" aria-hidden="true"></i></button>';

        mymap.addMarker({
          lat: value.latitude,
          lng: value.longitude,
          infoWindow: {
            maxWidth: 250,
            content: ('<a href="/profile/'+value.id+'" style="color:#424242;font-weight:500;font-size:14px;">'+icon+value.project_title+'</a>')
          }
        });
   });

        if(screen.width < 768){
          var text= $('.navbar-container').css('height');
          var height = text.slice(0, -2);
          $('.page').css('padding-top', height);
          $('#content').css('top', height);
        }
        else{
          var text= $('.navbar-container').css('height');
          var height = text.slice(0, -2);
          $('.page').css('margin-top', height);
        }

</script>

<script>
  $(document).ready(function() {
    $('#example').DataTable( {
        
        "pageLength": 25,
        "searching": false,
        "info":     false,
        "lengthChange": false
    } );
} );
</script>