@extends('backLayout.app')
@section('title')
Services
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
    .dropdown-menu.open {
        max-height: 250px;
        overflow: auto !important;
        width: 100%;
    }
    .bootstrap-select .dropdown-toggle .filter-option {
        position: relative;
        top: 0;
        left: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        text-align: left;
    }
    .form-group{float: left;width: 100%}
    .panel-link {
        display: inline-block;
        padding: 4px 8px;
        text-align: center;
        margin: 4px;
        vertical-align: baseline;
        border-radius: 4px;
        font-weight: 500;
        font-size: 12px;
        line-height: 14px;
        font-family: "Neue Haas Grotesk Display Roman";
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Services</h2>
            <div class="nav navbar-right panel_toolbox">
                <a href="javascript:void(0)" id="export_csv" class="btn btn-success">Export Services</a>
            </div>
        <div class="clearfix"></div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select SDOH Condition</label>
                <div class="col-sm-7">
                    {!! Form::select('conditions[]',$conditions,null,['class' => 'form-control','id' => 'conditions', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>

      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Goal</label>
                <div class="col-sm-7">
                     {!! Form::select('goals',$goals,null,['class' => 'form-control','id' => 'goals', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Activities</label>
                <div class="col-sm-7">
                     {!! Form::select('activities',$activities,null,['class' => 'form-control','id' => 'activities', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Organizations</label>
                <div class="col-sm-7">
                     {!! Form::select('organizations',$organizations,null,['class' => 'form-control','id' => 'organizations', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Service Category</label>
                <div class="col-sm-7">
                     {!! Form::select('service_category',$service_category_types,null,['class' => 'form-control','id' => 'service_category', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Select Service Eligibility</label>
                <div class="col-sm-7">
                     {!! Form::select('service_eligibility',$service_eligibility_types,null,['class' => 'form-control','id' => 'service_eligibility', 'data-live-search' => 'true','data-size' => '5','multiple' => 'true']) !!}
                </div>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Only show services with SDOH Codes</label>
                <div class="row">
                    <div class="col-sm-1">
                         <input type="checkbox" name="service_with_codes" id="service_with_codes" value="1">
                    </div>
                </div>
            </div>
          </div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="services_table" class="table display nowrap table-striped jambo_table table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Service ID</th>
                    <th class="text-center">Service Name</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">Service Category</th>
                    <th class="text-center">Service Eligibility</th>
                    <th class="text-center">SDOH ID</th>
                    {{-- <th class="text-center">Service Grouping</th> --}}
                    {{-- <th class="text-center">Action</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $('#conditions').selectpicker()
    $('#goals').selectpicker()
    $('#activities').selectpicker()
    $('#organizations').selectpicker()
    $('#service_category').selectpicker()
    $('#service_eligibility').selectpicker()
    let services_table;
    let extraData = {};
    let ajaxUrl
    ajaxUrl = "{{ route('tb_service.get_service_data') }}";
  $(document).ready(function(){
    services_table = $('#services_table').DataTable({
            'order': [0, 'desc'],
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "post",
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
                { data: 'service_name', name: 'service_name' },
                { data: 'service_organization', name: 'service_organization' },
                { data: 'service_category', name: 'service_category' },
                { data: 'service_eligibility', name: 'service_eligibility' },
                { data: 'codes', name: 'codes' },
                // { data: 'procedure_grouping', name: 'procedure_grouping' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": true,
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
                // {
                //     "targets": 5,
                //     "orderable": true,
                //     "class": "text-left"
                // },
                // {
                //     "targets": 6,
                //     "orderable": true,
                //     "class": "text-left"
                // },
                // {
                //     "targets": 7,
                //     "orderable": true,
                //     "class": "text-left"
                // },
                // {
                //     "targets": 8,
                //     "orderable": true,
                //     "class": "text-left"
                // },
            ],
        });
        $('#conditions').change(function(){
            let val = $(this).val()
            extraData.conditions = val
            services_table.ajax.reload()
        })
        $('#goals').change(function(){
            let val = $(this).val()
            extraData.goals = val
            services_table.ajax.reload()
        })
        $('#activities').change(function(){
            let val = $(this).val()
            extraData.activities = val
            services_table.ajax.reload()
        })
        $('#organizations').change(function(){
            let val = $(this).val()
            extraData.organizations = val
            services_table.ajax.reload()
        })
        $('#service_category').change(function(){
            let val = $(this).val()
            extraData.service_category = val
            services_table.ajax.reload()
        })
        $('#service_eligibility').change(function(){
            let val = $(this).val()
            extraData.service_eligibility = val
            services_table.ajax.reload()
        })
        $('#start_date').change(function(){
            let val = $(this).val()
            extraData.start_date = val
            services_table.ajax.reload()
        })
        $('#end_date').change(function(){
            let val = $(this).val()
            extraData.end_date = val
            services_table.ajax.reload()
        })
        $('#service_with_codes').change(function(){
            let val = $(this).is(':checked')
            extraData.service_with_codes = val
            services_table.ajax.reload()
        })
        $('#export_csv').click(function () {
            _token = '{{ csrf_token() }}'
            $.ajax({
                url:"{{ route('tb_service.export') }}",
                method : 'POST',
                data:{extraData,},
                success:function(response){
                    // const url = window.URL.createObjectURL(new Blob([response]));
                    const a = document.createElement('a');
                            a.href = response.path;
                            a.download = 'services.csv';
                            document.body.appendChild(a);
                            a.click();
                },
                error : function(error){
                    console.log(error)
                }
            })
        })
    });
</script>
@endsection
