@extends('backLayout.app')
@section('title')
Export
@stop
<style>
  .color-pick{
    padding: 0 !important;
  }
</style>
@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Export</h2>
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
              <div class="item form-group">
                <button class="btn btn-primary"><a href="/zip/datapackage.zip" style="color: white;" download>Download HSDS Zip File</a></button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

@endsection
@section('scripts')

@endsection