@extends('backLayout.app')
@section('title')
    Service Status
@stop

@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Service Status</h2>
                    <div class="nav navbar-right panel_toolbox">
                        <a href="{{ url('service_status/create') }}" class="btn btn-success">Add Service Status</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="">
                        <table class="table table-striped table-bordered jambo_table bulk_action" id="tblservice_status">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($service_statuses as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td style="width:30%; text-center">
                                            <a href="{{ route('service_status.edit', $item->id) }}">
                                                <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top"
                                                    title="Edit" style="color: #4caf50;"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'url' => ['service_status', $item->id], 'style' => 'display:inline']) !!}
                                            {{ Form::button('<i class="fa fa-trash" style="color: #c3211c;"></i>', ['type' => 'submit','data-placement' => 'top','data-original-title' => 'Delete','id' => 'delete-confirm']) }}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Additional Settings</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="form-horizontal form-label-left">
                <div class="col-md-12">
                  {{ Form::open(array('url' => ['save_default_service_status'], 'class' => 'form-horizontal form-label-left', 'method' => 'post', 'enctype'=> 'multipart/form-data')) }}
                  <div class="form-group">
                      <label class="col-md-2 col-sm-2" style="">Default</label>
                      <div class="col-md-6 col-sm-6 ">
                      <select name="default_service_status" id="" class="form-control">
                        <option value="">Select Status</option>
                        @foreach ($service_statuses as $item)
                        <option value="{{ $item->id }}" {{ $layout->default_service_status == $item->id ? 'selected' : '' }}>{{ $item->status }}</option>
                        @endforeach
                      </select>
                      </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                      <button type="submit" class="btn btn-success">Save</button>
                    </div>
                  </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tblservice_status').DataTable();
        });
        $(".deleteconfirm").on("click", function() {
            return confirm("Are you sure to delete this.");
        });
    </script>
@endsection
