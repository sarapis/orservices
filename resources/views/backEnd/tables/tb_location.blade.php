@extends('backLayout.app')
@section('title')
Locations
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
        <h2>Locations</h2>
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
                    <th class="text-center">id</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Address</th>
                    <th class="text-center"># Congregations</th>
                    <th class="text-center">Building Status</th>
                    <th class="text-center">Call</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Contact</th>
                    <th class="text-center">Details</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($locations as $key => $location)
                <tr id="location{{$location->id}}" class="{{$location->flag}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td>{{$location->location_name}}</td>

                  <td>
                    @if(isset($location->organization()->first()->organization_name))
                    <span class="badge bg-green">{{$location->organization()->first()->organization_name}}</span>
                    @endif
                  </td>

                  <td class="text-center">{{$location->location_id}}</td>
                  <td class="text-center"><span style="white-space:normal;">{!! $location->location_type !!}</span></td>

                  <td class="text-center">
                  @if($location->location_address!='')
                    @foreach($location->address as $address)
                      <span class="badge bg-red">{{ $address->address }}</span>
                    @endforeach
                  @endif
                  </td>

                  <td class="text-center">{{$location->location_congregation}}</td>
                  <td class="text-center">{{$location->location_building_status}}</td>
                  <td class="text-center">{{$location->location_call}}</td>
                  <td class="text-center">{{$location->location_description}}</td>

                  <td class="text-center">
                      @foreach($location->services as $service)

                        <span class="badge bg-purple">{{$service->service_name}}</span>

                      @endforeach
                  </td>

                  <td class="text-center">{{$location->location_contact}}</td>

                  <td class="text-center">@if($location->location_details!='') @foreach($location->detail as $detail)
                    <span class="badge bg-red">{{$detail->detail_value}}</span>
                  @endforeach
                  @endif
                  </td>



                  <td class="text-center">
                    {{-- <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$location->location_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
        </table>
        {!! $locations->links() !!}
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
                <h4 class="modal-title">Location</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_name" name="location_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">id</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_id" name="location_id" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="location_type">
                                <option value="-">-</option>
                                <option value="Faith-Based Service Provider">Faith-Based Service Provider</option>
                                <option value="Faith-Based Service Provider, House of Worship">Faith-Based Service Provider, House of Worship</option>
                                <option value="House of Worship">House of Worship</option>

                                <option value="Religious School">Religious School</option>
                                <option value="Religious School, House of Worship">Religious School, House of Worship</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label"># Congregations</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_congregation" name="location_congregation" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Building Status</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_building_status" name="location_building_status" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Call</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_call" name="location_call" value=""></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_description" name="location_description" value=""></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Contact</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_contact" name="location_contact" value=""></textarea>
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
<script src="{{asset('js/location_ajaxscript.js')}}"></script>
@endsection
