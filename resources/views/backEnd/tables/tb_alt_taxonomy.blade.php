@extends('backLayout.app')
@section('title')
Alt Taxonomy
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
        <h2>Alt Taxonomy</h2>
        <button class="btn btn-block btn-primary btn-md pull-right" style="width: 80px;" id="btn-add"><i class="fa fa-plus text-white"></i> Add</button>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">
        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Name</th>   
                    <th class="text-center">Vocabulary</th>                              
                    <th class="text-center"># Terms</th>           
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($alt_taxonomies as $key => $alt_taxonomy)
                <tr id="alt_taxonomy{{$alt_taxonomy->id}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td>{{$alt_taxonomy->alt_taxonomy_name}}</td>                 
                  <td>{{$alt_taxonomy->alt_taxonomy_vocabulary}}</td>
                  <td>{{$counts[$key]}}</td>
                  <td class="text-center">
                    <button class="btn btn-default btn-success btn-sm open_term_modal"  value="{{$alt_taxonomy->id}}" style="width: 80px;"><i class="fa fa-fw fa-pencil-square"></i>Term</button>
                    <button class="btn btn-default btn-info btn-sm open_modal"  value="{{$alt_taxonomy->id}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                    <button class="btn btn-default btn-danger btn-sm delete-product"  value="{{$alt_taxonomy->id}}" style="width: 80px;"><i class="fa fa-fw fa-remove"></i>Delete</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
        {!! $alt_taxonomies->links() !!}
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
                <h4 class="modal-title">Alt Taxonomy</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Alt Taxonomy Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="alt_taxonomy_name" name="alt_taxonomy_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Vocabulary</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="alt_taxonomy_vocabulary" name="alt_taxonomy_vocabulary" value="">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="id" value="0">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
  </div>
  <!-- /.modal-dialog -->

  <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content form-horizontal">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">Delete Alt Taxonomy</h4>
              </div>
              
              <div class="modal-body">
                  <form class="m-form m-form--fit m-form--label-align-right user" id="deleteProducts" name="deleteProducts">
                      <div class="form-group m-form__group row">
                          <h4>Are you sure you want to delete this Alt taxonomy ?</h4>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-delete" value="delete">Delete</button>
                    <input type="hidden" id="product_id" name="product_id" value="0">
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade in" id="open_term_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content form-horizontal">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>                 
              </div>
              
              <div class="modal-body">
                  <form class="m-form m-form--fit m-form--label-align-right user" action="/tb_alt_taxonomy" method="POST" id="form-open-terms" name="form-open-terms" enctype="multipart/form-data">
                  {!! Form::token() !!}                      
                      <div class="form-group m-form__group" id="open_term_form">
                          <div class="table-responsive" id="list_tb_open_term" style="overflow-y: scroll;height: 50%;">
                          </div>
                          <input type="hidden" id="alt_taxonomy_id" name="alt_taxonomy_id">
                          <input type="hidden" id="checked_terms" name="checked_terms">
                      </div>                      

                      <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Save</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                  </form>
              </div>              
          </div>
      </div>
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
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "stateSave": true
    } );
} );
</script>

<link type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css" rel="stylesheet" />
<link type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/custom-data-source/dom-checkbox.js"></script>
<script src="{{asset('js/alt_taxonomy_ajaxscript.js')}}"></script>
@endsection
