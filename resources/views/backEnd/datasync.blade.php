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
    #airtable_auto_sync_period {
        display: inline-block;
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
                        <select class="form-control" name="source_data" id="source_data">
                          <option>Choose option</option>
                          <option value="1" @if($source_data->active == 1) selected @endif>HSDS v.1.1 Airtable</option>
                          <option value="3" @if($source_data->active == 3) selected @endif>HSDS v.2.0 Airtable</option>
                          {{-- <option value="2" @if($source_data->active == 2) selected @endif> HowCalm Airtable</option> --}}
                          <option value="0" @if($source_data->active == 0) selected @endif>HSDS v.1.1 CSVs</option>
                        </select>
                    </div>
                    {{-- <div class="col-md-2 col-sm-2 col-xs-12">
                        <button id="send" type="submit" class="btn btn-success">Save</button>
                    </div> --}}
                </div>
                {!! Form::close() !!}
            </div>
             {{-- @if($source_data->active == 1)
            <div class="col-md-2">
                <div class="clearfix text-right"><button class="btn btn-primary btn-sm sync_all" id="sync_1">SYNC ALL</button>  </div>
            </div>
            @elseif($source_data->active == 3)
                <div class="clearfix text-right"><button class="btn btn-primary btn-sm sync_all_v2" id="sync_1_v2">SYNC ALL</button>  </div>
            @else --}}
            @if($source_data->active != 1 && $source_data->active != 3)
            <div class="col-md-2">
                <div class="clearfix text-right">
                    <button class="btn btn-danger" id="get_zip_from_api"><label for="get_zip_from_api">Import via API</label></button>
                    <p id="zipping" style="font-style: italic;  color: blue;"> downloading zip file ... </p>
                    <p id="zipped" style="color: blue;"> downloaded. </p>
                    <p id="zip-fail" style="color: red;"> download failed.</p>
                    <p id="zip-alert" style="color: green;"> Your api url or header info is invalid.</p>
                </div>
                <div class="clearfix text-right">
                    <input type="file" name="file_zip" id="file_zip" class="inputfile-zip" />
                        <button class="btn btn-primary"><label for="file_zip" class="choose-csv">Upload HSDS ZIP File</label></button>
                </div>
            </div>
            @endif
        </div>
        @if($source_data->active == 1)
            @include('backEnd.sync.syncv1')
        @elseif($source_data->active == 2)
        <div class="x_content">
            <div class="form-group">
                <h5>
                    You can import data organized in the <a href="#" style="color: #027bff;">HowCalm Airtable Template</a> into this software by filling our the following information and clicking “Sync All”

                    Find your Airtable ID and API Key by clicking the “Help” menu item in your base and selecting the “API documentation” option.

                    The Base ID is in the top section entitled “introduction”.

                    The API Key is accessible by clicking “show API key” on the top right of the page. The key will then be displayed on the right side in the Authentication section of the docs.
                </h5>
            </div>
            <div class="form-group" style="width:100%;float:left">
                <label for="airtable_api_key_input" class=" control-label col-md-3">Airtable API Key</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="airtable_api_key_input" id="airtable_api_key_input" required />
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button  class="btn btn-success">edit</button>
                </div>
            </div>
            <div class="form-group" style="width:100%;float:left">
                <label for="airtable_base_url_input" class=" control-label col-md-3">Airtable Base ID</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="airtable_base_url_input" id="airtable_base_url_input" required />
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button  class="btn btn-success">edit</button>
                </div>
            </div>
            <div class="form-group">
                <label for="airtable_enable_auto_sync">Enable auto-sync: </label>
                <div class="row">
                    <form action="/cron_datasync" method="GET" id="cron_airtable">
                        {!! Form::token() !!}
                        <div class="col-sm-4">
                            @if ($autosync->option == 'no')
                            <input class="form-control" type="checkbox" name="airtable_enable_auto_sync" id="airtable_enable_auto_sync" onclick="airtable_enable_autosync_Function()" >
                            @endif
                            @if ($autosync->option == 'yes')
                            <input class="form-control" type="checkbox" name="airtable_enable_auto_sync" id="airtable_enable_auto_sync" onclick="airtable_enable_autosync_Function()" checked>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" id="auto_sync_div">
                                <label for="airtable_auto_sync_period">Sync every</label>
                                <input class="form-control" type="text" name="airtable_auto_sync_period" id="airtable_auto_sync_period" value="{{$autosync->days}}" style="width: 75px;" required />
                                <label for="airtable_auto_sync_period">number of days</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            @if ($autosync->option == 'yes')
                                @if ($autosync->working_status == 'no')
                                <button type="submit" name="btn_submit" class="btn btn-primary btn-start autosyncbtn" value="autosyncbtn-start" id="autosyncbtn-start">Start</button>
                                @endif
                                @if ($autosync->working_status == 'yes')
                                <button type="submit" name="btn_submit" class="btn btn-warning btn-stop autosyncbtn" value="autosyncbtn-stop" id="autosyncbtn-stop">Stop</button>
                                @endif
                            @endif
                        </div>
                    </form>
                </div>
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
        @elseif($source_data->active == 3)
        @include('backEnd.sync.syncv2')
        @else
        <div class="x_content">
            <div class="form-group">
                <h5>
                    You can upload individual CSVs that follow the Open Referral Human Services Data (HSDS) format.
                    You can also import an entire dataset by uploading an HSDS Zip File.</br>
                    You can automate the import of an HSDS Zip File by filling out the following info and then clicking the Import via API button.
                </h5>
            </div>
            <div class="form-group">
                <label for="import_csv_url_path_input">API URL</label>
                <p for="import_csv_url_path_example" style="font-style: italic; color: grey;">   Example: http://52.188.77.23:3000/datapackages</p>
                <input class="form-control" type="text" name="import_csv_api_url" id="import_csv_api_url" required />
                <p id="validation-csv-api-url" style="font-style: italic; color: blue;">API url is required.</p>
            </div>
            <div class="form-group">
                <label for="import_csv_api_header_input">Authorization in Header</label>
                <p for="import_csv_api_header_example" style="font-style: italic; color: grey;">   Example: Bearer cwpr9HS5o8nZNBly6l2A0A</p>
                <input class="form-control" type="text" name="import_csv_api_header" id="import_csv_api_header" required />
                <p id="validation-csv-api-header" style="font-style: italic; color: blue;">Authorization in Header is required.</p>
            </div>
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
                        {{-- <button class="badge" style="background: #9B59B6;"><a href="{{ route('export.'.strtolower($csv->name)) }}" style="color: white;">Download CSV</a></button> --}}
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
<div class="modal fade " id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalTitle"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="addClass">
            <div class="modal-header ">
                <h3 class="modal-title" id="exampleModalLongTitle">Warning</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="message">Your current API Key will disappear and you will need to enter a new one.</div>
                <input type="hidden" name="version" id="version_id">
            </div>
            <div class="modal-footer text-center top_btn_data">
                <button type="button" class="btn btn-default btn_black" data-dismiss="modal">Go Back</button>
                <button type="button" class="btn btn-default btn_black" data-dismiss="modal" id="continue_pop_up">Continue</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script type="text/javascript">

    $(document).ready(function() {

        $('#source_data').change(function () {
            let source_data = $(this).val()
            let airtable_api_key_input
            let airtable_base_url_input
            let id = 1
            if(source_data == 1){
                airtable_api_key_input = $('#airtable_api_key_input').val()
                airtable_base_url_input = $('#airtable_base_url_input').val()
            }else if(source_data == 3){
                airtable_api_key_input = $('#airtable_api_key_input_v2').val()
                airtable_base_url_input = $('#airtable_base_url_input_v2').val()
            }
            $.ajax({
                method : 'POST',
                url : '{{ route("data.save_source_data") }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data : {
                    source_data,airtable_api_key_input,airtable_base_url_input,id
                },
                success:function(response){
                    window.location.reload()
                },
                error: function(error){
                    console.log(error)
                }
            })
        })



        var $img = $('<img class="probar titleimage" id="title" src="images/xpProgressBar.gif" alt="Loading..." />');
        var field_invalid= $('.field-invalid');
        field_invalid.hide();
        $('.sync_all').click(function(){
            sync_all_now(this, 0);
        });
        // for version 2.0
        $('.sync_all_v2').click(function(){
            sync_all_now_v2(this, 0);
        });
        $('#zipping').hide();
        $('#zipped').hide();
        $('#zip-fail').hide();
        $('#zip-alert').hide();
        $('#validation-csv-api-url').hide();
        $('#validation-csv-api-header').hide();

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
        // for version 2.0
        function sync_all_now_v2(parent, c){

            current = $('.sync_now_v2').eq(c);

            current.hide();

            current.after($img);

            var name = current.parent().prev().prev().prev().html();


            current.after($img);

            $here = current;
            name = name.toLowerCase();
            airtable_api_key = $('#airtable_api_key_input_v2').val();
            airtable_base_url = $('#airtable_base_url_input_v2').val();

            $.ajax({
                type: "GET",
                url: '/sync_v2_'+name+'/'+airtable_api_key+'/'+airtable_base_url,
                success: function(result) {
                    $img.remove();
                    $here.show();
                    $here.html('Updated');
                    $here.removeClass('bg-yellow');
                    $here.addClass('bg-purple');
                    $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                    c ++;
                    if($('.sync_now_v2').length != c)
                        sync_all_now_v2(parent, c);
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    $img.remove();
                    $here.show();
                    $('.field-invalid').append('<span>'+err.Message+'</span>');
                    field_invalid.show();
                }
            });
        }
        $('#get_zip_from_api').click(function(){
            var origin_api_url = $('#import_csv_api_url').val();
            var origin_api_header_authorization = $('#import_csv_api_header').val();

            if ((origin_api_url != '') && (origin_api_header_authorization != '')) {

                var api_url = 'https://cors-anywhere.herokuapp.com/' + origin_api_url;
                var api_header_authorization = origin_api_header_authorization;
                console.log(api_url,api_header_authorization)
                // var api_url = 'https://cors-anywhere.herokuapp.com/http://52.188.77.23:3000/datapackages';
                // var api_header_authorization = 'Bearer cwpr9HS5o8nZNBly6l2A0A';

                $('#zipping').show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'Authorization': api_header_authorization,
                    },
                    url: api_url,
                    success: function(result) {
                        console.log(result,'this is a result')
                        var id = result.data[0].id;
                        var request_url = api_url + '/' + id;
                        $.ajax({
                            type: "POST",
                            url: request_url,
                            headers: {
                                'Authorization': api_header_authorization
                            },
                            success: function (data) {
                                var date_time = data.data.attributes.created_at;
                                var file_name = data.data.attributes.name;
                                var downloaded_date = date_time.split("T")[0];
                                var downloaded_time = date_time.split("T")[1];
                                var downloaded_file_name = 'datapackage' + '_' + downloaded_date + '_' + downloaded_time.replace(/:/g, '_').split(".")[0] + '.zip';
                                console.log(downloaded_file_name);

                                var download_request_url = 'http://52.188.77.23:3000' + data.data.attributes.url;
                                console.log(download_request_url);
                                window.location.href = download_request_url;

                                $('#zipped').show();
                                $('#zipping').hide();
                            },
                            error: function(e){
                                console.log(e)
                                console.log('data packages download error!');
                                $('#zip-fail').show();
                                $('#zipping').hide();
                            }
                        });
                    },
                    error: function(e){
                        console.log('data packages download error!');
                        $('#zip-fail').show();
                        $('#zip-alert').show();
                        $('#zipping').hide();
                    }
                });

            }

            if ((origin_api_url == '') && (origin_api_header_authorization != '')) {
                $('#validation-csv-api-url').show();
            }

            if ((origin_api_url != '') && (origin_api_header_authorization == '')) {
                $('#validation-csv-api-header').show();
            }


        });
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
        $('.sync_now_v2').click(function(){
            $(this).hide();
            var name = $(this).parent().prev().prev().prev().html();

            $(this).after($img);
            $here = $(this);
            name = name.toLowerCase();
            airtable_api_key = $('#airtable_api_key_input_v2').val();
            airtable_base_url = $('#airtable_base_url_input_v2').val();
            $.ajax({
                type: "GET",
                url: '/sync_v2_'+name+'/'+airtable_api_key+'/'+airtable_base_url,
                success: function(result) {
                    $img.remove();
                    $here.show();
                    $here.html('Updated');
                    $here.removeClass('bg-yellow');
                    $here.addClass('bg-purple');
                    $here.parent().prev().html('<?php echo date("Y/m/d H:i:s"); ?>');
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    $img.remove();
                    $here.show();
                    $('.field-invalid').append('<span>'+name + ' : '+err.message+'</span>');
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
        $('#airtable_key_v1').click(function () {
            $('#version_id').val('v1');
            $('#alertModal').modal('show')
            // $('#airtable_api_key_input1').hide()
            // $('#airtable_api_key_input').show()
            // $(this).hide()
        })
        $('#airtable_key_v2').click(function () {
            $('#version_id').val('v2');
            $('#alertModal').modal('show')
            // $('#airtable_api_key_input_v2_1').hide()
            // $('#airtable_api_key_input_v2').show()
            // $(this).hide()
        })
        $('#continue_pop_up').click(function () {
            let version_id = $('#version_id').val()
            if(version_id == 'v1'){
                $('#airtable_api_key_input1').hide()
                $('#airtable_api_key_input').show()
                $('#airtable_api_key_input').val('')
                // $('#airtable_base_url_input1').hide()
                // $('#airtable_base_url_input').show()
                // $('#airtable_base_url_input').val('')
                $('#airtable_key_v1').hide()
            }else{
                $('#airtable_api_key_input_v2_1').hide()
                $('#airtable_api_key_input_v2').show()
                $('#airtable_api_key_input_v2').val('')
                // $('#airtable_base_url_input_v2_1').hide()
                // $('#airtable_base_url_input_v2').show()
                // $('#airtable_base_url_input_v2').val('')
                $('#airtable_key_v2').hide()
            }
            $('#alertModal').modal('hide')
        })
    });

    function airtable_enable_autosync_Function() {
        var checkBox = document.getElementById("airtable_enable_auto_sync");
        if (checkBox.checked == true){
            $("#auto_sync_div").show();
            $(".autosyncbtn").show();
          } else {
            $("#auto_sync_div").hide();
            $(".autosyncbtn").hide();
          }
    }

</script>
@endsection
