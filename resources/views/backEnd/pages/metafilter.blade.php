@extends('backLayout.app')
@section('title')
Meta Filter
@stop
<style>
  .color-pick{
    padding: 0 !important;
  }
  .stepContainer{
    height: auto !important;
  }
</style>
@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Meta Filter</h2>
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
              <div class="col-md-6">
                {{ Form::open(array('url' => ['meta', 1], 'class' => 'form-horizontal form-label-left', 'method' => 'post', 'enctype'=> 'multipart/form-data')) }}
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" style="padding-top: 2px; margin-right: 10px;">Activate</label>
                    <input type="checkbox" class="js-switch" value="checked" id="meta_filter_activate" name="meta_filter_activate" @if($meta->meta_filter_activate==1) checked @endif />
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12" style="padding-top: 2px; margin-right: 10px;">Show meta filter options</label>
                    <input type="checkbox" class="js-switch" value="checked" name="user_metafilter_option" @if($meta->user_metafilter_option==1) checked @endif />
                </div>
                @if ($meta->user_metafilter_option==1)
                <div class="form-group" id="off_label_div">
                  <label class="control-label col-md-2 col-sm-2 col-xs-12">Off Label</label>
                  <div class="col-md-6 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" placeholder="Off Label" name="meta_filter_off_label" value="{{$meta->meta_filter_off_label}}">
                </div>

                <label class="radio-inline">
                  <input type="radio" name="default_label" id="inlineRadio1" value="off_label" {{ $meta->default_label == 'off_label' ? 'checked' : '' }}> Default
                </label>
                </div>

                <div class="form-group" id="on_label_div">
                  <label class="control-label col-md-2 col-sm-2 col-xs-12">On Label</label>
                  <div class="col-md-6 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" placeholder="On Label" name="meta_filter_on_label" value="{{$meta->meta_filter_on_label}}">
                </div>
                <label class="radio-inline">
                  <input type="radio" name="default_label" id="inlineRadio1" value="on_label" {{ $meta->default_label == 'on_label' ? 'checked' : '' }}> Default
                </label>
                </div>
                @endif

                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                    <button type="submit" class="btn btn-success">Save</button>
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
              <div class="col-md-6">
                <h1>Filtered Services: {{$service_count}}</h1>
              </div>
              <div class="col-md-6">
                <h1>Filtered Organiazations: {{$organizations_count}}</h1>
              </div>
              <div class="col-md-6">
                <h1>Filtered count: {{$filter_count}}</h1>
              </div>
              {{-- @if($meta->meta_filter_activate==1) --}}
              <div class="col-md-12" id="filter_table_div">
                <div class="x_title">
                  <h2>Filters</h2>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg" style="margin-left: 10px;">Add</button>
                  @if($errors->any())
                  <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    {{$errors->first()}}
                  </div>
                  @endif
                  <div class="x_content">
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Meta Filter</h4>
                        </div>
                        <div class="modal-body">
                        <!-- Smart Wizard -->
                          <form class="form-horizontal form-label-left" action="/meta_filter" method="POST" id="meta_filter" enctype="multipart/form-data">
                            {!! Form::token() !!}
                            <div id="wizard" class="form_wizard wizard_horizontal">
                              <ul class="wizard_steps">
                                <li>
                                  <a href="#step-1">
                                    <span class="step_no">1</span>
                                    <span class="step_descr">
                                        Step 1<br />
                                        <small>Select Filter Options</small>
                                    </span>
                                  </a>
                                </li>
                                <li>
                                  <a href="#step-2">
                                    <span class="step_no">2</span>
                                    <span class="step_descr">
                                        Step 2<br />
                                        <small>Add Filter Data</small>
                                    </span>
                                  </a>
                                </li>
                                <li>
                                  <a href="#step-3">
                                    <span class="step_no">3</span>
                                    <span class="step_descr">
                                        Step 3<br />
                                        <small>Confirm Data</small>
                                    </span>
                                  </a>
                                </li>
                              </ul>

                              <div id="step-1">
                                  <input type="hidden" id="status" name="status" value="0">
                                  <div class="form-group">
                                  <h2 class="StepTitle">Add Filter - Step 1</h2>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Operation</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" id="operation">
                                      <select class="form-control" name="operation">
                                        <option value="Include">Include</option>
                                        <option value="Exclude">Exclude</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Facet</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" id="facet">
                                      <select class="form-control" name="facet">
                                        <option value="Taxonomy">Taxonomy</option>
                                        <option value="Postal_code">Postal code</option>
                                        <option value="Service_area">Service area</option>
                                        <option value="organization_status">Organization status</option>
                                        <option value="Service_status">Service status</option>
                                        <option value="service_tag">Service tag</option>
                                        <option value="organization_tag">Organization Tag</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label id="select_mothod_label" class="control-label col-md-3 col-sm-3 col-xs-12">Select Method</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" id="method">
                                      <select class="form-control" name="method">
                                        <option value="Checklist">Checklist</option>
                                        <option value="CSV">CSV</option>
                                      </select>
                                    </div>
                                  </div>

                              </div>
                              <div id="step-2">
                                <div class="form-group">
                                  <h2 class="StepTitle">Step 2 Content</h2>
                                </div>
                                <div class="form-group" id="csv_form_2">
                                  <label class="control-label col-md-2 col-sm-2 col-xs-12">Import CSV</label>
                                  <div class="col-md-6 col-sm-9 col-xs-12">
                                  <input type="file" class="form-control" name="csv_import_2">
                                  </div>
                                </div>
                                <div class="form-group" id="checklist_form_2">
                                  <div class="table-responsive" id="list_tb_2" style="overflow-y: scroll;height: 50%;">

                                  </div>
                                </div>
                              </div>
                              <div id="step-3">
                                <div class="form-group">
                                  <h2 class="StepTitle">Step 3 Content</h2>
                                </div>
                                <div class="form-group" id="csv_form_3">
                                  <label class="control-label col-md-2 col-sm-2 col-xs-12">Import CSV</label>
                                  <div class="col-md-6 col-sm-9 col-xs-12">
                                  <input type="file" class="form-control" name="csv_import_3">
                                  </div>
                                </div>
                                <div class="form-group" id="checklist_form_3">
                                  <div class="table-responsive" id="list_tb_3" style="overflow-y: scroll;height: 50%;">

                                  </div>
                                </div>
                              </div>


                            </div>
                          </form>
                          <!-- End SmartWizard Content -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade bs-edit-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Meta Filter</h4>
                          </div>
                          <div class="modal-body">
                          <!-- Smart Wizard -->
                            <form class="form-horizontal form-label-left" action="/meta_filter" method="POST" id="meta_filter_edit" enctype="multipart/form-data">
                              {!! Form::token() !!}
                                <input type="hidden" id="edit-status" name="status" value="0">
                                <input type="hidden" id="edit-operation" name="operation">
                                <input type="hidden" id="edit-facet" name="facet">
                                <input type="hidden" id="edit-method" name="method">

                                <div class="form-group" id="checklist_form_edit">
                                  <div class="table-responsive" id="list_tb_edit" style="overflow-y: scroll;height: 50%;">
                                  </div>
                                </div>

                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success btn-save">Save</button>
                                </div>
                            </form>
                            <!-- End SmartWizard Content -->

                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <form action="/meta_delete_filter" method="POST" id="meta_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">Delete Meta filter</h4>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" id="id" name="id" value="0">
                                <h4>Are you sure delete this meta filter?</h4>

                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-danger btn-delete">Delete</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="clearfix"></div>
                </div>
                <table class="table table-striped jambo_table bulk_action" id="tblUsers">
                  <thead>
                      <tr>
                          <th class="text-center">No</th>
                          <th class="text-center">Operations</th>
                          <th class="text-center">Facet</th>
                          <th class="text-center">Method</th>
                          <th class="text-center">Edit</th>
                          <th class="text-center">Delete</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($metafilters as $key => $metafilter)
                      <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td class="text-center">{{$metafilter->operations}}</td>
                        <td class="text-center">{{$metafilter->facet}}</td>
                        <td class="text-center">{{$metafilter->method}}</td>
                        <td class="text-center"><button class="btn btn-block btn-primary btn-sm edit-meta" value="{{$metafilter->id}}" data-toggle="modal" data-target=".bs-edit-modal-lg"><i class="fa fa-fw fa-edit"></i>Edit</button></td>
                        <td class="text-center"><button class="btn btn-block btn-danger btn-sm delete-meta" value="{{$metafilter->id}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button></td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
              {{-- @endif --}}
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
                {{ Form::open(array('url' => ['meta_additional_setting', 1], 'class' => 'form-horizontal form-label-left', 'method' => 'post', 'enctype'=> 'multipart/form-data')) }}
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="padding-top: 2px; margin-right: 10px;">Hide Organizations with No Filtered Services</label>
                    <input type="checkbox" class="js-switch" value="checked" id="hide_organizations_with_no_filtered_services" name="hide_organizations_with_no_filtered_services" @if($meta->hide_organizations_with_no_filtered_services==1) checked @endif />
                </div>
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="padding-top: 2px; margin-right: 10px;">Hide Service Categories with no Filtered Services</label>
                    <input type="checkbox" class="js-switch" value="checked" id="hide_service_category_with_no_filter_service" name="hide_service_category_with_no_filter_service" @if($meta->hide_service_category_with_no_filter_service==1) checked @endif />
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

<script src="{{asset('js/operation_ajaxscript.js')}}"></script>
<script>
  $('select[name="facet"]').on('change', function(e) {
    var value = e.target.value;
    if (value == "Service_status" || value == "organization_status" || value == "service_tag" || value == "organization_tag") {
      $('select[name="method"]').val('Checklist');
      $('div[id="method"]').hide();
      $('label[id="select_mothod_label"]').hide();
      $('#step-3').hide();
    }
  });
  // $('#meta_filter_activate').change(function(){
  //     if($(this).prop('checked')){
  //       $('#on_label_div').show()
  //       $('#off_label_div').show()
  //       $('#filter_table_div').show()
  //   }else{
  //         $('#on_label_div').hide()
  //         $('#off_label_div').hide()
  //         $('#filter_table_div').hide()
  //     }
  // })
 </script>
@endsection
