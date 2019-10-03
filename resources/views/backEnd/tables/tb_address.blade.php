@extends('backLayout.app')
@section('title')
Address
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
                    <th class="text-center">Address 1</th>                   
                    <th class="text-center">Address 2</th>                   
                    <th class="text-center">City</th>
                    <th class="text-center">State province</th>
                    <th class="text-center">Postal code</th>
                    <th class="text-center">Region</th>             
                    <th class="text-center">Country</th>
                    <th class="text-center">Attention</th>
                    @if($source_data->active == 1 )
                    <th class="text-center">Address type</th>
                    @endif
                    <th class="text-center">Locations</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Organizations</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($addresses as $key => $address)
                <tr id="address{{$address->id}}" class="{{$address->flag}}">
                  @if($source_data->active == 1 )
                  <td class="text-center">{{$key}}+1</td>
                  @elseif($source_data->active == 0)
                  <td class="text-center">{{$address->address_recordid}}</td>
                  @endif
                  <td>{{$address->address_1}}</td>
                  
                  <td>
                    {{$address->address_2}}
                  </td>
                  
                  <td class="text-center">{{$address->address_city}}</td>
                  <td class="text-center">{{$address->address_state_province}}</td>
                  <td class="text-center">{{$address->address_postal_code}}</td>
                  <td class="text-center">{{$address->address_region}}</td>

                  <td class="text-center">{{$address->address_country}}</td>

                  <td class="text-center">{{$address->address_attention}}
                  </td>
                  @if($source_data->active == 1 )
                  <td class="text-center"><span class="badge bg-purple">{{$address->address_type}}</span></td>
                  @endif
                  <td class="text-center"><span style="white-space:normal;">
                  @if(isset($address->locations))
                    @foreach($address->locations as $location)
                      <span class="badge bg-red">{{ $location->location_name }}</span>
                    @endforeach
                  @endif</span>
                  </td>

                  <td class="text-center"><span style="white-space:normal;">
                  @if(isset($address->services))
                    @foreach($address->services as $service)
                      <span class="badge bg-blue">{{ $service->service_name }}</span>
                    @endforeach
                  @endif</span>
                  </td>

                  <td class="text-center">{{$address->address_organization}}
                  </td>

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$address->address_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
       {!! $addresses->links() !!}
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
                <h4 class="modal-title">Address</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Address 1</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_1" name="address_1" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Address 2</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_2" name="address_2" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">City</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_city" name="address_city" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">State province</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_state_province" name="address_state_province" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Postal code</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_postal_code" name="address_postal_code" value="">
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Postal Region</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_region" name="address_region" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Postal Country</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_country" name="address_country" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Attention</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_attention" name="address_attention" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Address Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="address_type">
                                <option></option>
                                <option value="physical_address">Physical address</option>
                                <option value="postal_address">Postal address</option>
                            </select>
                        </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="address_id" value="0">
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
<script src="{{asset('js/address_ajaxscript.js')}}"></script>
@endsection
