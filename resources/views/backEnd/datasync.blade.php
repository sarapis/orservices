@extends('backLayout.app')
@section('title')
Datasync
@stop

@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Datasync</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content">

        <table class="table table-striped jambo_table bulk_action" id="tblUsers">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Table Name</th>
                    <th class="text-center">Table Source</th>
                    <th class="text-center">Total Records</th>
                    <th class="text-center">Last Synced</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($airtables as $key => $airtable)
                <tr>
                  <td class="text-center">{{$key+1}}</td>
                  <td class="text-center">{{$airtable->name}}</td>
                  <td class="text-center">ORnycServices(ATv1.1)</td>
                  <td class="text-center">{{$airtable->records}}</td>
                  <td class="text-center">{{$airtable->syncdate}}</td> 
                  <td class="text-center">
                    <button class="badge sync_now" name="{{$airtable->name}}" style="background: #00a65a;">Sync Now</button>
                    <button class="badge" style="background: #f39c12;"><a href="tb_{!! strtolower($airtable->name) !!}" style="color: white;">View Table</a></button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<style type="text/css">
    button{
        width: 85px !important;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
      var $img = $('<img class="probar titleimage" id="title" src="images/xpProgressBar.gif" alt="Loading..." />');

    $('.sync_now').click(function(){
        $(this).hide();
        name = $(this).attr('name');

        $(this).after($img);
        $here = $(this);
        name = name.toLowerCase();
        $.ajax({
            type: "GET",
            url: '/sync_'+name,
            success: function(result) {
                $img.remove();
                $here.show();
                $here.html('Updated');
                $here.removeClass('bg-yellow');
                $here.addClass('bg-purple');
                $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
            }
        });
    });
  });
</script>
@endsection
