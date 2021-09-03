@extends('backLayout.app')
@section('title')
Services
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
        <h2>Services</h2>
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
                    <th class="text-center">Url</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Locations</th>

                    <th class="text-center">Status</th>
                    <th class="text-center">Taxonomy</th>
                    <th class="text-center">Application Process</th>
                    <th class="text-center">Wait Time</th>
                    <th class="text-center">Fees</th>
                    <th class="text-center">Accreditations</th>
                    <th class="text-center">Licenses</th>
                    <th class="text-center">Phones</th>
                    <th class="text-center">Schedule</th>
                    <th class="text-center">Contacts</th>
                    @if($source_data->active == 1 || $source_data->active == 3)
                    <th class="text-center">Details</th>
                    @endif
                    <th class="text-center">Address</th>
                    @if($source_data->active == 0 )
                    <th class="text-center">Languages</th>
                    @endif
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($services as $key => $service)
                <tr id="service{{$service->id}}" class="{{$service->flag}}">
                  @if($source_data->active == 1 || $source_data->active == 3)
                  <td class="text-center">{{$key}}+1</td>
                  @elseif($source_data->active == 0)
                  <td class="text-center">{{$service->service_recordid}}</td>
                  @endif
                  <td>{{$service->service_name}}</td>


                  <td class="text-center">
                  @if(isset($service->organizations()->first()->organization_name))
                    <span class="badge bg-green">{{$service->organizations()->first()->organization_name}}</span>
                  @endif
                  </td>

                  <td class="text-center">{{$service->service_alternate_name}}</td>

                  <td class="text-center">{{$service->service_url}}</td>
                  <td class="text-center">{{$service->service_email}}</td>


                  <td class="text-center"><span style="white-space:normal;">{!! $service->service_description !!}</span></td>

                  <td class="text-center">


                      @foreach($service->locations as $location)

                      <span class="badge bg-purple">{{$location->location_name}}</span>

                      @endforeach


                  </td>

                  <td class="text-center">{{$service->service_status}}</td>


                  <td class="text-center">
                    @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                      @foreach($service->taxonomy as $taxonomy)
                      <span class="badge bg-blue">{{$taxonomy->taxonomy_name}}</span>
                      @endforeach
                    @endif
                  </td>

                  <td class="text-center"><span style="white-space:normal;">{!! $service->service_application_process !!}</span></td>
                  <td class="text-center">{{$service->service_wait_time}}</td>
                  <td class="text-center">{{$service->service_fees}}</td>
                  <td class="text-center">{{$service->service_accreditations}}</td>
                  <td class="text-center">{{$service->service_licenses}}</td>
                  <td class="text-center">@foreach($service->phone as $phone)
                    <span class="badge bg-red">{!! $phone->phone_number !!}</span>
                  @endforeach</td>

                  <td class="text-center">


                      @foreach($service->schedules as $schedule)

                      <span class="badge bg-purple">{{$schedule->schedule_days_of_week}} {{$schedule->opens_at}} {{$schedule->closes_at}}</span>

                      @endforeach


                  </td>

                  <td class="text-center"><span class="badge bg-blue">@if(isset($service->contact()->first()->contact_name))
                  {{$service->contact()->first()->contact_name}}</span>

                  @endif</td>

                  @if($source_data->active == 1 || $source_data->active == 3)
                  <td class="text-center">
                    @if($service->service_details != null )
                      @foreach($service->details as $deta)
                        <span class="badge bg-red">{{$deta->detail_value}}</span>
                      @endforeach
                    @endif
                  </td>
                  @endif

                  <td class="text-center"><span style="white-space:normal;">
                  @if(isset($service->address))
                    @foreach($service->address as $address)
                      <span class="badge bg-green">{{ $address->address_1 }}</span>
                    @endforeach
                    </span>
                  @endif
                  </td>

                  @if($source_data->active == 0 )
                  <td class="text-center">@if(isset($service->languages)) @foreach($service->languages as $language)
                    <span class="badge bg-green">{{$language->language}}</span>
                  @endforeach
                  @endif
                  </td>
                  @endif

                  <td class="text-center">
                    {{-- <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$service->service_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
        </table>
        {!! $services->links() !!}
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
                <h4 class="modal-title">Service</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_name" name="service_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Alternate name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_alternate_name" name="service_alternate_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="service_description" name="service_description" value="" rows="5"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="service_url" name="service_url" value="" rows="5"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Email</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="service_email" name="service_email" value="" rows="5"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="service_status">
                                <option></option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Application process</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="service_application_process" name="service_application_process" value="" rows="5"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Wait time</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_wait_time" name="service_wait_time" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Fees</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_fees" name="service_fees" value="">
                      </div>
                    </div>



                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Accreditations</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_accreditations" name="service_accreditations" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">License</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_licenses" name="service_licenses" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Metadata</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_metadata" name="service_metadata" value="">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="project_id" value="0">
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
<script src="{{asset('js/service_ajaxscript.js')}}"></script>
@endsection
