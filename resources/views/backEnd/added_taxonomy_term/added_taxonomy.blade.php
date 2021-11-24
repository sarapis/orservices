@extends('backLayout.app')
@section('title')
Taxonomy terms
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
    #btn_create {
    	margin: 0 auto;
    	width: 250px;
    }
    #btn_delete {
    	margin: 0 auto;
    	width: 150px;
    }
</style>
@section('content')

<div class="row">

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Taxonomy terms</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" >

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="taxonomy_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">UID</th>
                    <th class="text-center">Timestamp</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Term Submitted</th>
                    <th class="text-center">Parent Term</th>
                    <th class="text-center">Related Service</th>
                    <th class="text-center">Related Organization</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @if($add_taxonomies)
              @foreach($add_taxonomies as $key => $taxonomy)
                <tr id="taxonomy{{$taxonomy->taxonomy_recordid}}">
                  <td class="text-center">{{$taxonomy->id}}</td>
                  <td class="text-center">{{$taxonomy->created_at}}</td>
                  <td class="text-center">{{$taxonomy->user->first_name ?? '' }}</td>
                  <td class="text-center">{{$taxonomy->taxonomy_name }}</td>
                  <td class="text-center">{{ $taxonomy->parent->taxonomy_name ?? '' }}</td>
                  <td class="text-center">
                    <span class="badge bg-blue">{{$taxonomy->temp_service->service_name ?? ''}}</span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-blue">{{$taxonomy->temp_organization->organization_name ?? ''}}</span>
                  </td>
                  <td class="text-center">
                      <a href="/edit_taxonomy_added/{{ $taxonomy->id }}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                  </td>
                </tr>
              @endforeach
            @endif
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
    <div class="x_panel" style="width: 50%;">
      <div class="x_title" style="text-align: center;">
        <h4>Forward submission to the following email addresses:</h4>
        <div class="clearfix"></div>
      </div>

      <div class="x_content" style="overflow: scroll;">
        <table id="email_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">No</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (sizeof($emails) > 0)
              	@foreach($emails as $key => $email)
	                <tr id="taxonomy">
	                  <td class="text-center">{{$key+1}}</td>
	                  <td class="text-center">{{$email->email}}</td>
	                  <td class="text-center">
	                    <button id="btn_delete" class="btn btn-block btn-danger btn-sm open_delete_modal" data-id="{{$email->email_recordid}}" ><i class="fa fa-fw fa-trash"></i>Delete</button>
	                  </td>
	                </tr>
	            @endforeach
	        @endif
            </tbody>
        </table>
      </div>

      <div style="text-align: center;">
        <button id="btn_create" class="btn btn-block btn-primary btn-sm " data-toggle="modal" data-target=".bs-create-modal-lg" >Add</button>
      </div>

    </div>
  </div>

  	<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/delete_taxonomy_email" method="POST" id="delete_taxonomy_email">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Delete Email</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="email_recordid" name="email_recordid">
                        <h4>Are you sure to delete this email?</h4>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" id="email_delete_btn" class="btn btn-danger btn-delete"  >Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bs-create-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/add_taxonomy_email" method="POST" id="add_taxonomy_email">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Add a New Email</h4>
                    </div>
                    <div class="modal-body">
	                    <label class="control-label sel-label-org pl-4">Email: </label>
	                    <div class="col-md-12 col-sm-12 col-xs-12 contact-email-div">
	                        <input class="form-control selectpicker" type="text" id="email"
	                            name="email" value="" required>
	                    </div>
                    </div>
                    <div class="modal-footer" style="margin-top: 40px;">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                        <button type="submit" id="email_create_btn" class="btn btn-primary btn-create">Add</button>
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

    $('#taxonomy_table').DataTable();

    $('#email_table').DataTable( {
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
        "autoWidth": false
    } );

    $('#btn_delete').on('click', function(e) {
        e.preventDefault();
    });
    $('.open_delete_modal').click(function(){
        let email_recordid =$(this).data('id')
        $('#email_recordid').val(email_recordid)
        $('.bs-delete-modal-lg').modal('show')
    })

    // $('#email_delete_btn').on('click', function(e) {
    //     e.preventDefault();
    //     var value = $(this).val();
    //     $('input#email_recordid').val(value);
    //     $('#email_delete_filter').submit();
    // });



} );
</script>
@endsection
