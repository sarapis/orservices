@extends('backLayout.app')
@section('title')
Phones
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Phones</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Number</th>                   
                    <th class="text-center">Locations</th>                   
                    <th class="text-center">Extension</th>             
                    <th class="text-center">Type</th>
                    <th class="text-center">Language</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($phones as $key => $phone)
                <tr id="phone{{$phone->id}}" class="{{$phone->flag}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td>{!! str_limit($phone->phone_number, 10) !!}</td>

                  <td>
                    @if($phone->phone_locations!='') 
                  
                      @foreach($phone->locations as $location)
                        
                      <span class="badge bg-purple">{{$location->location_name}}</span>
                      
                      @endforeach
                           
                    @endif

                  <td>{{$phone->phone_extension}}</td>

                  <td>{{$phone->phone_type}}</td>

                  <td>{{$phone->phone_language}}</td>

                  <td>{{$phone->phone_description}}</td>

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$phone->phone_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
<input id="url" type="hidden" value="{{ \Request::url() }}">

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Phone</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Number</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Extension</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="phone_extension" name="phone_extension" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="phone_type">
                                <option></option>
                                <option value="voice">voice</option>
                                <option value="textphone">textphone</option>
                                <option value="cell">cell</option>
                                <option value="fax">fax</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Language</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="phone_language" name="phone_language" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="phone_description" name="phone_description" value="">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="phone_id" value="0">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        responsive: {
            details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');
 
                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        }
    } );
} );
</script>
<script src="{{asset('js/phone_ajaxscript.js')}}"></script>
@endsection
