@extends('backLayout.app')
@section('title')
Details
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
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">X-Id</th>
                    {{-- <th class="text-center">Value</th> --}}
                    <th class="text-center">X-Type</th>
                    <th class="text-center">X-Description</th>
                    <th class="text-center">X-Organizations</th>
                    <th class="text-center">X-Services</th>
                    <th class="text-center">X-Locations</th>
                    <th class="text-center">Y-Phones</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($details as $key => $detail)
                <tr id="detail{{$detail->id}}" class="{{$detail->flag}}">
                  <td class="text-center">{{ $detail->detail_recordid }}</td>
                  {{-- <td><span style="white-space:normal;">{!! $detail->detail_value !!}</span></td> --}}

                  <td class="text-center">{{$detail->detail_type}}</td>

                  <td class="text-center">{{$detail->detail_description}}</td>

                  <td>@if($detail->detail_organizations!='')
                    <span class="badge bg-blue">{{$detail->organization ? $detail->organization()->first()->organization_name : ''}}</span>
                    @endif
                 </td>
                 <td class="text-center">{{$detail->detail_services}}</td>

                  <td>@if($detail->detail_locations!='')
                    <span class="badge bg-blue">{{$detail->location && $detail->location()->first() ? $detail->location()->first()->location_name : ''}}</span>
                  @endif
                  </td>
                  <td class="text-center">{{$detail->phones}}</td>


                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$detail->id}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
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
                <h4 class="modal-title">Details</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Value</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="detail_value" name="detail_value" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Detail Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="detail_type">
                                <option></option>
                                <option value="language">language</option>
                                <option value="payment_accepted">payment_accepted</option>
                                <option value="required_document">required_documentd</option>
                                <option value="eligibility">eligibility</option>
                                <option value="funding_source">funding_source</option>
                                <option value="page_type">page_type</option>
                                <option value="uid">uid</option>
                                <option value="program_code">program_code</option>
                                <option value="blurb">blurb</option>
                                <option value="reu">reu</option>
                                <option value="accessibility_for_disabilities">accessibility_for_disabilities</option>
                                <option value="program">program</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="detail_description" name="detail_description" value="">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="detail_id" value="0">
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
        "autoWidth": false
    } );
} );
</script>
<script src="{{asset('js/detail_ajaxscript.js')}}"></script>
@endsection
