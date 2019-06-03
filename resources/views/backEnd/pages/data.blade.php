@extends('backLayout.app')
@section('title')
Data Settings
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
            <h2>Data Settings</h2>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Data Source (Select One)
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control">
                      <option>Choose option</option>
                      <option>Open Referral AirTable</option>
                      <option>HSDS SQL</option>
                    </select>
                  </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

@endsection
@section('scripts')

<script>
function readURL(input) {
    if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection