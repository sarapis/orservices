@extends('backLayout.app')
@section('title')
Contacts
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
        <h2>Contacts</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Organizations</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                    @if($source_data->active == 0 )
                    <th class="text-center">Phone Areacode</th>
                    <th class="text-center">Phone extension</th>
                    @endif
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($contacts as $key => $contact)
                <tr id="contact{{$contact->id}}" class="{{$contact->flag}}">
                  @if($source_data->active == 1 )
                  <td class="text-center">{{$key}}+1</td>
                  @elseif($source_data->active == 0)
                  <td class="text-center">{{$contact->contact_recordid}}</td>
                  @endif
                  <td class="text-center">{{$contact->contact_name}}</td>
                  <td class="text-center">
                    @if($contact->contact_organizations!=0)
                    <span class="badge bg-red">{{$contact->organization()->first()->organization_name}}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($contact->contact_services!=0)
                    <span class="badge bg-blue">{{$contact->service()->first() ? $contact->service()->first()->service_name : ''}}</span>
                    @endif
                  </td>
                  <td class="text-center">{{$contact->contact_title}}</td>
                  <td class="text-center">{{$contact->contact_department}}</td>
                  <td class="text-center">{{$contact->contact_email}}</td>
                  <td class="text-center">
                    @if(isset($contact->phone()->first()->phone_number))
                      <span class="badge bg-purple">{{$contact->phone()->first()->phone_number}}</span>
                    @endif
                  </td>
                  @if($source_data->active == 0 )
                  <td class="text-center">{{$contact->contact_phone_areacode}}</td>
                  <td class="text-center">{{$contact->contact_phone_extension}}</td>
                  @endif
                  <td>
                    {{-- <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$contact->contact_recordid}}"><i class="fa fa-fw fa-edit"></i>Edit</button> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
        </table>
        {!! $contacts->links() !!}
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
<input id="url" type="hidden" value="{{ \Request::url() }}">

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Contacts</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="modal-body">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_name" name="contact_name" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Title</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_title" name="contact_title" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Department</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_department" name="contact_department" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Email</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_email" name="contact_email" value="">
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
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true
    });
});
</script>
<script src="{{asset('js/contacts_ajaxscript.js')}}"></script>
@endsection
