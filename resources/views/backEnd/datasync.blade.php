@extends('backLayout.app')
@section('title')
Import
@stop

@section('content')
<style>
    .inputfile,  .inputfile-zip{
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
    }
    .choose-csv{
        margin-bottom: 0;
        cursor: pointer;
    }
    .badge{
        width: 100px;
    }
</style>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="x_panel">
        <div class="col-md-12">
            <div class="col-md-2">
              <h2>Import</h2>
            </div>
            <div class="col-md-8">
                {{ Form::open(array('url' => ['data', 1], 'class' => 'form-horizontal form-label-left', 'method' => 'put', 'enctype'=> 'multipart/form-data')) }}
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Data Source (Select One)
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control" name="source_data">
                          <option>Choose option</option>
                          <option value="1" @if($source_data->active == 1) selected @endif>Open Referral AirTable</option>
                          <option value="0" @if($source_data->active == 0) selected @endif>CSV</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <button id="send" type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
             @if($source_data->active == 1)
            <div class="col-md-2">
                <div class="clearfix text-right"><button class="btn btn-primary btn-sm sync_all" id="sync_1">SYNC ALL</button>  </div>
            </div>  
            @else     
            <div class="col-md-2">
                <div class="clearfix text-right">
                    <input type="file" name="file_zip" id="file_zip" class="inputfile-zip" />
                        <button class="btn btn-primary"><label for="file_zip" class="choose-csv">Upload HSDS ZIP File</label></button>
                </div>
            </div>  
            @endif
        </div>
        @if($source_data->active == 1)
        <div class="x_content">
            <div class="form-group">
                <label for="airtable_api_key_input">Airtable Key</label>
                <input class="form-control" type="text" name="airtable_api_key_input" id="airtable_api_key_input" required />                
            </div>
            <div class="form-group">
                <label for="airtable_base_url_input">Airtable Base Url</label>
                <input class="form-control" type="text" name="airtable_base_url_input" id="airtable_base_url_input" required />
            </div>
            <div class="alert alert-danger alert-dismissible field-invalid">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Either of these infos is invalid or empty. Retype valid Airtable Key and Airtable Base Url.</strong> 
            </div> 
            
            

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

        @else
        <div class="x_content">

            <table class="table table-striped jambo_table bulk_action" id="tbcsv">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Table Name</th>
                        <th class="text-center">Source CSV</th>
                        <th class="text-center">Total Records</th>
                        <th class="text-center">Last Synced</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($csvs as $key => $csv)
                    <tr>
                      <td class="text-center">{{$key+1}}</td>
                      <td class="text-center">{{$csv->name}}</td>
                      <td class="text-center">{{$csv->source}}</td>
                      <td class="text-center">{{$csv->records}}</td>
                      <td class="text-center">{{$csv->syncdate}}</td> 
                      <td class="text-center">
                        <input type="file" name="file_{{$csv->name}}" id="file_{{$csv->name}}" class="inputfile" />
                        <button class="badge" style="background: #00a65a;"><label for="file_{{$csv->name}}" class="choose-csv">Choose CSV</label></button>
                        <button class="badge" style="background: #f39c12;"><a href="tb_{!! strtolower($csv->name) !!}" style="color: white;">View Table</a></button>
                        <button class="badge" style="background: #9B59B6;"><a href="/csv/{{$csv->source}}" style="color: white;" download>Download CSV</a></button>
                      </td>
                    </tr>
                    
                  @endforeach             
                </tbody>
            </table>

        </div>
        @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')


<script type="text/javascript">
    $(document).ready(function() {
        var $img = $('<img class="probar titleimage" id="title" src="images/xpProgressBar.gif" alt="Loading..." />');   
        var field_invalid= $('.field-invalid');
        field_invalid.hide();
        $('.sync_all').click(function(){
            sync_all_now(this, 0);
        });

        function sync_all_now(parent, c){

            current = $('.sync_now').eq(c);

            current.hide();

            current.after($img);

            var name = current.parent().prev().prev().prev().prev().html();


            current.after($img);
            
            $here = current;
            name = name.toLowerCase();
            airtable_api_key = $('#airtable_api_key_input').val();
            airtable_base_url = $('#airtable_base_url_input').val();
            
            $.ajax({
                type: "GET",
                url: '/sync_'+name+'/'+airtable_api_key+'/'+airtable_base_url,
                success: function(result) {
                    $img.remove();
                    $here.show();
                    $here.html('Updated');
                    $here.removeClass('bg-yellow');
                    $here.addClass('bg-purple');
                    $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                    c ++;
                    if($('.sync_now').length != c)
                        sync_all_now(parent, c);
                },
                error: function(e) {
                    $img.remove();
                    $here.show();
                    field_invalid.show();
                }
            });
        }


        $('.sync_now').click(function(){
            $(this).hide();
            var name = $(this).parent().prev().prev().prev().prev().html();

            $(this).after($img);
            $here = $(this);
            name = name.toLowerCase();
            airtable_api_key = $('#airtable_api_key_input').val();
            airtable_base_url = $('#airtable_base_url_input').val();
            $.ajax({
                type: "GET",
                url: '/sync_'+name+'/'+airtable_api_key+'/'+airtable_base_url,
                success: function(result) {
                    $img.remove();
                    $here.show();
                    $here.html('Updated');
                    $here.removeClass('bg-yellow');
                    $here.addClass('bg-purple');
                    $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                },
                error: function(e) {
                    $img.remove();
                    $here.show();
                    field_invalid.show();
                }
            });
        });
        $('.inputfile').change(function(e){

            e.preventDefault(); 
            if($(this).val() == "")
                return;
            $(this).next().hide();
            var formData = new FormData();
            formData.append('csv_file', $(this)[0].files[0]);
            // console.log($(this)[0].files[0]);
            // formData.append('file', $(this)[0]);

            var name = $(this).parent().prev().prev().prev().prev().html();

            $(this).after($img);
            $here = $(this);
            name1 = name.toLowerCase();
            
                
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "post",
                async: true,
                url: '/csv_'+name1,
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {

                    if(result.status == 'error'){
                        $img.remove();
                        $here.next().remove();
                        $here.after('<button class="badge bg-red"><label for="file_'+name+'" class="choose-csv">Try Again</label></button>');
                        $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                        var add_alert = '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>'+result.result+'</strong></div>';
                        $('.x_panel').before(add_alert);
                        
                    }
                    else{
                        $img.remove();
                        $here.next().remove();
                        $here.after('<button class="badge bg-purple" style="background: #00a65a;"><label for="file" class="choose-csv">Updated</label></button>');
                        $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                        // $('.x_panel').before(add_alert);
                    }
                },
                error: function (result) {
                    
                }
            });
        });

        $('.inputfile-zip').change(function(e){

            e.preventDefault(); 
            if($(this).val() == "")
                return;
            $(this).next().hide();
            var formData = new FormData();
            formData.append('file_zip', $(this)[0].files[0]);
            // console.log($(this)[0].files[0]);
            // formData.append('file', $(this)[0]);
            $(this).after($img);
            $here = $(this);
                 
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "post",
                async: true,
                url: '/csv_zip',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {

                    if(result.status == 'error'){
                        $img.remove();
                        $here.next().remove();
                        $here.after('<button class="btn btn-danger"><label for="file_zip" class="choose-csv">Try Again</label></button>');
                        $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                        var add_alert = '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>'+result.result+'</strong></div>';
                        $('.x_panel').before(add_alert);
                        
                    }
                    else{
                        $img.remove();
                        $here.next().remove();
                        $here.after('<button class="btn btn-success"><label for="file_zip" class="choose-csv">Updated</label></button>');
                        $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                        // $('.x_panel').before(add_alert);
                    }
                },
                error: function (result) {
                    
                }
            });
        });
    });
</script>
@endsection
