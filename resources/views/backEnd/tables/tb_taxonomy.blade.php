@extends('backLayout.app')
@section('title')
Taxonomy
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
        <h2>Address</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Name</th>                   
                    <th class="text-center">Parent name</th>                   
                    <th class="text-center">Vocabulary</th>
                    <th class="text-center">X-description</th>
                    <th class="text-center">X-note</th>
                    <th class="text-center">Services</th>             
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($taxonomies as $key => $taxonomy)
                <tr id="taxonomy{{$taxonomy->id}}" class="{{$taxonomy->flag}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td>{{$taxonomy->taxonomy_name}}</td>

                  <td>@if($taxonomy->taxonomy_parent_name!='')
                    <span class="badge bg-blue">{{$taxonomy->parent()->first()->taxonomy_name}}</span>
                  @endif
                  </td>
                  
                  <td class="text-center">{{$taxonomy->taxonomy_vocabulary}}</td>
                  <td class="text-center">{{$taxonomy->taxonomy_x_description}}</td>
                  <td class="text-center">{{$taxonomy->taxonomy_x_notes}}</td>
                  <td class="text-center">@if($taxonomy->taxonomy_services!='')@foreach($taxonomy->service as $service)
                    <span class="badge bg-green">{{$service->service_name}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$taxonomy->taxonomy_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
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
                <h4 class="modal-title">Taxonomy</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="taxonomy_name" name="taxonomy_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Vocabulary</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="taxonomy_vocabulary" name="taxonomy_vocabulary" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">X-description</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="taxonomy_x_description" name="taxonomy_x_description" value="" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">X-notes</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="taxonomy_x_notes" name="taxonomy_x_notes" value="">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="taxonomy_id" value="0">
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
        },
        "paging": true,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true
    } );
} );
</script>
<script src="{{asset('js/taxonomy_ajaxscript.js')}}"></script>
@endsection
