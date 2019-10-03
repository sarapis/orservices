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
                    <th class="text-center">Alternate name</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Latitude</th>
                    <th class="text-center">Longitude</th>             
                    <th class="text-center">Transportation</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Phones</th>
                    @if($source_data->active == 1 )
                    <th class="text-center">Details</th>
                    @endif
                    <th class="text-center">Schedule</th>
                    
                    <th class="text-center">Address</th>
                    @if($source_data->active == 0 )
                    <th class="text-center">Languages</th>
                    <th class="text-center">Accessibility</th>
                    @endif
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($locations as $key => $location)
                <tr id="location{{$location->id}}" class="{{$location->flag}}">
                  @if($source_data->active == 1 )
                  <td class="text-center">{{$key+1}}</td>
                  @else
                  <td>{{$location->location_recordid}}</td>
                  @endif
                  <td>{{$location->location_name}}</td>
                  
                  <td>
                    @if(isset($location->organization()->first()->organization_name))
                    <span class="badge bg-green">{{$location->organization()->first()->organization_name}}</span>
                    @endif
                  </td>
                  
                  <td class="text-center">{{$location->location_alternate_name}}</td>
                  <td class="text-center"><span style="white-space:normal;">{!! $location->location_description !!}</span></td>
                  <td class="text-center">{{$location->location_latitude}}</td>
                  <td class="text-center">{{$location->location_longitude}}</td>
                  <td class="text-center">{{$location->location_transportation}}</td>
                  

                  <td class="text-center">
                      @foreach($location->services as $service)
                        
                        <span class="badge bg-purple">{{$service->service_name}}</span>
                      
                      @endforeach
                  </td>

                  <td class="text-center">

                      @foreach($location->phones as $phone)
                        
                      <span class="badge bg-blue">{{$phone->phone_number}}</span>
                      
                      @endforeach
                           
                  </td>

                  <td class="text-center">
                  @if($location->location_address!='')
                    @foreach($location->address as $address)
                      <span class="badge bg-red">{{ $address->address_1 }}</span>
                    @endforeach
                  @endif
                  </td>

                  @if($source_data->active == 1 )
                  <td class="text-center">@if($location->location_details!='') @foreach($location->detail as $detail)
                    <span class="badge bg-red">{{$detail->detail_value}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">
                  @if($location->location_schedule!='')
                    @foreach($location->schedules as $schedule)
                      <span class="badge bg-red">{{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}} {{$schedule->schedule_closes_at}}</span>
                    @endforeach
                  @endif
                  </td>
                  @endif

                  <td class="text-center">
                    @foreach($location->address as $address)
                      <span class="badge bg-red">{{ $address->address_1 }} {{ $address->address_2 }}, {{ $address->address_city }}, {{ $address->address_state_province }}, {{ $address->address_postal_code }}</span>
                    @endforeach
                  </td>

                  @if($source_data->active == 0 )
                  <td class="text-center">@if(isset($location->languages)) @foreach($location->languages as $language)
                    <span class="badge bg-green">{{$language->language}}</span>
                  @endforeach
                  @endif
                  </td>
                  @endif

                  @if($source_data->active == 0 )
                  <td class="text-center">
                  @if(isset($location->accessibilities()->first()->accessibility))
                    <span class="badge bg-green">{{$location->accessibilities()->first()->accessibility}}</span>
                    @endif
                  </td>
                  @endif
                  

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$location->location_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
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
                      <label for="inputPassword3" class="col-sm-3 control-label">Alternate name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_alternate_name" name="location_alternate_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Transportation</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_transportation" name="location_transportation" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Latitude</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_latitude" name="location_latitude" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Longitude</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="location_longitude" name="location_longitude" value="">
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="location_description" name="location_description" value="" rows="5"></textarea>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="location_id" value="0">
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
