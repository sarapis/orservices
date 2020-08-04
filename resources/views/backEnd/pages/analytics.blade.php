@extends('backLayout.app')
@section('title')
Analytics
@stop

@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Analytics</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

        
          <div class="table table-responsive">
            <h2>Search Analytics</h2>
            <table class="table table-striped jambo_table bulk_action" id="tblroles">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Last Time Searched</th>
                    <th>Search Term</th>
                    <th>Amount of results of the last search</th>
                    <th>Times Searched</th>
                </tr>
            </thead>
                <tbody>
                  @foreach($analytics as $analytic)
                  <tr>
                    <td>{{$analytic->id}}</td>
                    <td>{{$analytic->created_at}}</td>
                    <td>{{$analytic->search_term}}</td>
                    <td>{{$analytic->search_results}}</td>
                    <td>{{$analytic->times_searched}}</td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
          </div>

          {!! Form::model($page, [
            'url' => ['analytics', 4],
            'class' => 'form-horizontal', 'method' => 'put'
        ]) !!}
        
            <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                {!! Form::label('body', 'Google Analytics: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('body',null, ['class' => 'form-control'] ) !!}
                    {!! $errors->first('body', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
          {!! Form::close() !!}
        </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
      $('#summernote').summernote({
          height: 300
      });
        $('#tblroles').DataTable({
            columnDefs: [{
                targets: [0],
                visible: false,
                searchable: false
                },
            ],
            order: [[0, "asc"]],
        });
    });
  </script>
@endsection