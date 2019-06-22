@extends('backLayout.app')
@section('title')
Organizations
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
        <h2>Organizations</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Name</th>                                   
                    <th class="text-center">Alternate name</th>
                    <th class="text-center">X-uid</th>                               
                    <th class="text-center">Email</th>
                    <th class="text-center">Url</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Legal status</th>
                    <th class="text-center">Tax status</th>
                    <th class="text-center">Tax id</th>
                    <th class="text-center">Year incorporated</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Phones</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">Contact</th>
                    <th class="text-center">Details</th>                   
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($organizations as $key => $organization)
                <tr id="organization{{$organization->id}}" class="{{$organization->flag}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td class="">{{$organization->organization_name}}</td>
                  
                  <td class="text-center">{{$organization->organization_alternate_name}}</td>
                  <td class="text-center">{{$organization->organization_x_uid}}</td>
                  

                  <td class="text-center">{{$organization->organization_email}}</td>

                  <td class="text-center">{{$organization->organization_url}}</td>

                  <td class="text-center"><span style="white-space:normal;">{!! $organization->organization_description !!}</span></td>

                  <td class="text-center">{{$organization->organization_legal_status}}</td>

                  <td class="text-center">{{$organization->organization_tax_status}}</td>

                  <td class="text-center">{{$organization->organization_tax_id}}</td>

                  <td class="text-center">{{$organization->organization_year_incorporated}}</td>

                  <td class="text-center">@if($organization->organization_services)
                  @foreach($organization->services as $service)
                    <span class="badge bg-green">{{$service->service_name}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">@if($organization->organization_phones!='')
                  @foreach($organization->phones as $phone)
                    <span class="badge bg-blue">{{$phone->phone_number}}</span>
                  @endforeach
                  @endif
                  </td>


                  <td class="text-center">@if($organization->organization_locations!='')@foreach($organization->location as $location)
                    <span class="badge bg-blue">{{$location->location_name}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">@if($organization->organization_contact!='')
                    <span class="badge bg-red">{{$organization->contact()->first()->contact_name}}</span>
                  @endif
                  </td>

                  <td class="text-center">@if($organization->organization_details!='')@foreach($organization->detail as $detail)
                    <span class="badge bg-purple">{{$detail->detail_value}}</span>
                  @endforeach
                  @endif
                  </td>


                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$organization->organization_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
        {!! $organizations->links() !!}
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
                <h4 class="modal-title">Organization</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_name" name="organization_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Alternate name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_alternate_name" name="organization_alternate_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">X-uid</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_x_uid" name="organization_x_uid" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="organization_description" name="organization_description" value="" rows="5"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Email</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_email" name="organization_email" value="">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_url" name="organization_url" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_url" name="organization_url" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Legal status</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_legal_status" name="organization_legal_status" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Tax status</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_tax_status" name="organization_tax_status" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Tax id</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_tax_id" name="organization_tax_id" value="">
                      </div>

                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Year incorporated</label>  
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_year_incorporated" name="organization_year_incorporated" value="">
                      </div>

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="organization_id" value="0">
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
    } );
} );
</script>
<script src="{{asset('js/organization_ajaxscript.js')}}"></script>
@endsection
