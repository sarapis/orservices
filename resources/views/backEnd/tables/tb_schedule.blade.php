@extends('backLayout.app')
@section('title')
Schedule
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
        <h2>Details</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display  table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Services</th>
                    <th class="text-center">Locations</th>
                    @if($source_data->active == 1 || $source_data->active == 3)
                    <th class="text-center">X-phones</th>
                    @endif
                    <th class="text-center">Days of Week</th>
                    <th class="text-center">Opens at</th>
                    <th class="text-center">Closes at</th>
                    @if($source_data->active == 0 )
                    <th class="text-center">Orgiinal text</th>
                    @else
                    <th class="text-center">Holiday</th>
                    <th class="text-center">Closed</th>
                    @endif
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($schedules as $key => $schedule)
                <tr id="schedule{{$schedule->id}}" class="{{$schedule->flag}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td>@if(isset($schedule->get_services))
                        @foreach($schedule->get_services as $service)
                          <span class="badge bg-blue">{{$service->service_name}}</span>
                        @endforeach
                      @endif
                  </td>

                  <td>
                  @if(isset($schedule->locations()->first()->location_name))
                    <span class="badge bg-green">{{$schedule->locations()->first()->location_name}}</span>
                  @endif
                  </td>
                  @if($source_data->active == 1 || $source_data->active == 3)
                  <td>@if($schedule->schedule_phone!='')
                    <span class="badge bg-red">{{$schedule->phone()->first()->phone_number}}</span>
                  @endif
                  </td>
                  @endif
                  <td class="text-center">{{$schedule->schedule_days_of_week}}</td>

                  <td class="text-center">{{$schedule->opens_at}}</td>

                  <td class="text-center">{{$schedule->closes_at}}</td>
                  @if($source_data->active == 0 )
                  <td class="text-center">{{$schedule->schedule_description}}</td>
                  @else
                  <td class="text-center">@if($schedule->schedule_holiday==1)<i class="icon fa fa-check"></i>@endif</td>


                  <td class="text-center">@if($schedule->schedule_closed==1)<i class="icon fa fa-check"></i>@endif</td>
                  @endif

                  <td class="text-center">
                    {{-- <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$schedule->schedule_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button> --}}
                  </td>

                </tr>
              @endforeach
            </tbody>
        </table>
        {!! $schedules->links() !!}
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
                <h4 class="modal-title">Schedule</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Days of Week</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="schedule_days_of_week" name="schedule_days_of_week" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Open at</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="opens_at" name="opens_at" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Closes at</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="closes_at" name="closes_at" value=""></input>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="text" class="col-sm-4 control-label">Holiday</label>

                      <div class="col-sm-7">
                        <input type="checkbox" style="margin-top: 10px;" id="schedule_holiday" name="schedule_holiday">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="text" class="col-sm-4 control-label">Closed</label>

                      <div class="col-sm-7">
                        <input type="checkbox" style="margin-top: 10px;" id="schedule_closed" name="schedule_closed">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="schedule_id" value="0">
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
<script src="{{asset('js/schedule_ajaxscript.js')}}"></script>
@endsection
