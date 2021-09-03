@extends('backLayout.app')
@section('title')
Program
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
        <h2>Program </h2>
        <div class="nav navbar-right panel_toolbox">
            {{-- @if (Sentinel::getUser()->hasAccess(['user.create'])) --}}
            <a href="{{route('programs.create')}}" class="btn btn-success">New Program</a>
            {{-- @endif --}}
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="programs_table" class="display table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">name</th>
                    <th class="text-center">alternate_name</th>
                    <th class="text-center">organizations</th>
                    <th class="text-center">services</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              {{-- @foreach($programs as $key => $program)
                <tr id="program{{$program->id}}" class="{{$program->flag}}">

                  <td class="text-center">{{$key+1}}</td>

                  <td>{{$program->name}}</td>

                  <td>
                    {{$program->alternate_name}}
                  </td>

                  <td class="text-center">{{$program->organizations}}</td>
                  <td class="text-center">{{$program->services}}</td>

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$program->program_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach --}}
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
                <h4 class="modal-title">Address</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Address</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address" name="address" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Address 1</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_1" name="address_1" value="">
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
                        <input type="text" class="form-control" id="address_state" name="address_state_province" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Zip code</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_zip_code" name="address_postal_code" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">id</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_id" name="address_id" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Region</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="address_region" name="address_region" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Country</label>

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
                                <option value="Main Address">Main Address</option>
                                <option value="Mailing Address">Mailing Address</option>
                                <option value="Physical Address">Physical Address</option>
                                <option value="Postal Address">Postal Address</option>
                            </select>
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
    // $('#example').DataTable( {
    //     responsive: {
    //         details: {
    //             renderer: function ( api, rowIdx, columns ) {
    //                 var data = $.map( columns, function ( col, i ) {
    //                     return col.hidden ?
    //                         '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
    //                             '<td>'+col.title+':'+'</td> '+
    //                             '<td>'+col.data+'</td>'+
    //                         '</tr>' :
    //                         '';
    //                 } ).join('');

    //                 return data ?
    //                     $('<table/>').append( data ) :
    //                     false;
    //             }
    //         }
    //     },
    //     "paging": false,
    //     "pageLength": 20,
    //     "lengthChange": false,
    //     "searching": false,
    //     "ordering": true,
    //     "info": false,
    //     "autoWidth": true
    // } );
} );
    let programs_table;
    let extraData = {};
    let ajaxUrl = "{{ route('programs.index') }}";
  $(document).ready(function(){
    programs_table = $('#programs_table').DataTable({
        processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "get",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data : function (d){
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            d.extraData = extraData;
                        }
                    },
                },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'alternate_name', name: 'alternate_name' },
                { data: 'organizations', name: 'organizations' },
                { data: 'services', name: 'services' },
                // { data: 'phone_id', name: 'phone_id' },
                { data: 'action', name: 'action' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": false,
                    "class": "text-left",
                },
                {
                    "targets": 1,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 2,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 3,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 4,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 5,
                    "orderable": true,
                    "class": "text-left"
                },
                // {
                //     "targets": 8,
                //     "orderable": true,
                //     "class": "text-left"
                // },
            ],
        });
    });
</script>
<script src="{{asset('js/address_ajaxscript.js')}}"></script>
@endsection
