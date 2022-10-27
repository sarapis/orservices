@extends('backLayout.app')
@section('title')
    Organizations
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    tr.modified {
        background-color: red !important;
    }

    tr.modified>td {
        background-color: red !important;
        color: white;
    }
</style>
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Organizations</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content" style="overflow: scroll;">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-md-3 control-label">Organization Tags</label>
                            <div class="col-sm-7" style="margin-bottom: 20px">
                                {!! Form::select('organization_tag', $organization_tags, null, ['class' => 'form-control selectpicker', 'id' => 'organization_tag', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-3 control-label">Service Tags</label>
                            <div class="col-sm-7" style="margin-bottom: 20px">
                                {!! Form::select('service_tag', $service_tags, null, ['class' => 'form-control selectpicker', 'id' => 'service_tag', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-md-3 control-label">Status</label>
                            <div class="col-sm-7" style="margin-bottom: 20px">
                                {!! Form::select('status', $organizationStatus, null, ['class' => 'form-control selectpicker', 'id' => 'status', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword3" class="col-md-3 control-label">Bookmarks Only</label>
                            <div class="col-sm-7">
                                <input type="checkbox" name="organization_bookmark_only" id="organization_bookmark_only" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputPassword3" class="col-sm-3 control-label">Start Date</label>
                            <div class="col-sm-7" style="margin-bottom: 20px">
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword3" class="col-sm-3 control-label">Saved Filter</label>
                            <div class="col-sm-7" >
                                <select name="saved_filter" id="saved_filters" class="form-control selectpicker">
                                    <option value="">Select</option>
                                    @foreach ($saved_filters as $value )
                                    <option value="{{ $value }}">{{ $value->filter_name }}</option>
                                    @endforeach
                                </select>
                                {{-- {!! Form::select('status', $saved_filters, null, ['class' => 'form-control selectpicker', 'id' => 'status', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!} --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputPassword3" class="col-sm-3 control-label">End Date</label>
                            <div class="col-sm-7" style="margin-bottom: 20px">
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                                {{-- <label for="inputPassword3" class="col-sm-3 control-label"></label> --}}
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-sm btn-success" id="saveFilterButton">Save New Filter</button>
                                <a href="/manage_filters" class="btn btn-sm btn-info">Manage Filters</a>
                                <a href="javascript:void(0)" id="export_csv" class="btn btn-sm btn-primary">Download CSV</a>
                            </div>
                        </div>
                    </div>
                    <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
                    <table id="tb_organizations_table"
                        class="display table-striped jambo_table table-bordered table-responsive" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" >Bookmark</th>
                                <th class="text-center" >Name</th>
                                {{-- <th class="text-center">Url</th> --}}
                                <th class="text-center">Organization Tags</th>
                                <th class="text-center">Organization Status</th>
                                <th class="text-center">Service Tags</th>
                                <th class="text-center">Date Last Verified</th>
                                <th class="text-center">User Verified</th>
                                <th class="text-center">Last Note Date</th>
                                <th class="text-center">Last Updated Date</th>
                                {{-- <th class="text-center">Description</th> --}}
                                {{-- <th class="text-center">Legal status</th> --}}
                                {{-- <th class="text-center">Tax status</th> --}}
                                {{-- <th class="text-center">Tax id</th> --}}
                                {{-- <th class="text-center">Year incorporated</th> --}}
                                {{-- <th class="text-center">Services</th> --}}
                                {{-- <th class="text-center">Phones</th> --}}
                                {{-- <th class="text-center">Location</th> --}}
                                {{-- <th class="text-center">Contact</th> --}}
                                {{-- <th class="text-center">Details</th> --}}
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
    <input id="url" type="hidden" value="{{ \Request::url() }}">

    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Organization</h4>
                </div>
                <form class=" form-horizontal user" id="frmProducts" name="frmProducts" novalidate=""
                    style="margin-bottom: 0;">
                    <div class="row modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_name"
                                        name="organization_name" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Alternate name</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_alternate_name"
                                        name="organization_alternate_name" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">X-uid</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_x_uid"
                                        name="organization_x_uid" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control" id="organization_description"
                                        name="organization_description" value="" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_email"
                                        name="organization_email" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_url" name="organization_url"
                                        value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_url" name="organization_url"
                                        value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Legal status</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_legal_status"
                                        name="organization_legal_status" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Tax status</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_tax_status"
                                        name="organization_tax_status" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Tax id</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_tax_id"
                                        name="organization_tax_id" value="">
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Year incorporated</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="organization_year_incorporated"
                                        name="organization_year_incorporated" value="">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                        <input type="hidden" id="id" name="organization_id" value="0">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editTagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Oraganization Tags</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="/saveOrganizationTags" method="post">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="organization_recordid" id="organization_recordid">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-1 text-right">Tag</label>
                        <div class="col-md-6" style="margin-bottom: 20px">
                            {!! Form::select('organization_tag[]', $organization_tags, null, ['class' => 'form-control selectpicker', 'id' => 'organization_tag_pop_up', 'multiple' => true, 'data-live-search' => 'true', 'data-size' => 5]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-target="#create_new_organization_tag" data-toggle="modal">Create New Tag</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    {{-- organization tag modal --}}
    <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="create_new_organization_tag">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/createNewOrganizationTag" method="post">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Create New Organization Tag</h4>
                    </div>
                    <input type="hidden" name="organization_recordid" id="organization_recordid_create">
                    <div class="modal-body all_form_field">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="margin-bottom:5px;font-weight:600;color: #000;letter-spacing: 0.5px;">Tag</label>
                                    <input type="text" class="form-control" name="tag" placeholder="tag" id="tag">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-lg btn_delete red_btn organizationTagCloseButton"  data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-lg btn_padding green_btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End here --}}
    <!-- Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Oraganization Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="/saveOrganizationStatus" method="post">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="organization_recordid" id="organization_recordid_status">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-1 text-right">Status</label>
                        <div class="col-md-6" style="margin-bottom: 20px">
                            {!! Form::select('organisation_status', $organizationStatus, null, ['class' => 'form-control selectpicker', 'id' => 'organisation_status_pop_up','data-live-search' => 'true', 'data-size' => 5]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-primary" data-target="#create_new_organization_tag" data-toggle="modal">Create New Tag</button> --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    <!-- save filter Modal -->
    <div class="modal fade" id="saveFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Save Filter Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="/saveOrganizationFilter" method="post">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="organization_recordid" id="organization_recordid_status">
                <input type="hidden" name="extraData" id="extra_data_filter_pop_up">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-1 text-right">Filter Name</label>
                        <div class="col-md-6" style="margin-bottom: 20px">
                            <input type="text" name="filter_name" id="filter_name_pop_up" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-primary" data-target="#create_new_organization_tag" data-toggle="modal">Create New Tag</button> --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
    <link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        // $('#organization_tag').SumoSelect({ placeholder: 'Nothing selected' });
        $("#organization_tag").selectpicker();
        $("#service_tag").selectpicker();
        let tb_organizations_table;
        let extraData = {};
        let ajaxUrl = "{{ route('tables.tb_organizations') }}";
        $(document).ready(function() {
            tb_organizations_table = $('#tb_organizations_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    method: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data: function(d) {
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            d.extraData = extraData;
                        }
                    },
                },
                columns: [
                    {
                        data: 'bookmark',
                        name: 'bookmark'
                    },
                    {
                        data: 'organization_name',
                        name: 'organization_name'
                    },
                    // {
                    //     data: 'organization_url',
                    //     name: 'organization_url'
                    // },
                    {
                        data: 'organization_tag',
                        name: 'organization_tag'
                    },
                    {
                        data: 'organization_status_x',
                        name: 'organization_status_x'
                    },
                    {
                        data: 'service_tag',
                        name: 'service_tag'
                    },
                    {
                        data: 'last_verified_at',
                        name: 'last_verified_at'
                    },
                    {
                        data: 'last_verified_by',
                        name: 'last_verified_by'
                    },
                    {
                        data: 'last_note_date',
                        name: 'last_note_date'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    // {
                    //     data: 'organization_description',
                    //     name: 'organization_description'
                    // },
                    // {
                    //     data: 'organization_legal_status',
                    //     name: 'organization_legal_status'
                    // },
                    // {
                    //     data: 'organization_tax_status',
                    //     name: 'organization_tax_status'
                    // },
                    // {
                    //     data: 'organization_tax_id',
                    //     name: 'organization_tax_id'
                    // },
                    // {
                    //     data: 'organization_year_incorporated',
                    //     name: 'organization_year_incorporated'
                    // },
                    // {
                    //     data: 'services',
                    //     name: 'services'
                    // },
                    // {
                    //     data: 'phones',
                    //     name: 'phones'
                    // },
                    // {
                    //     data: 'location',
                    //     name: 'location'
                    // },
                    // {
                    //     data: 'contact_name',
                    //     name: 'contact_name'
                    // },
                    // {
                    //     data: 'organization_details',
                    //     name: 'organization_details'
                    // },
                ],
                columnDefs: [{
                        "targets": 0,
                        "orderable": false,
                        "class": "text-center",
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
                    {
                        "targets": 6,
                        "orderable": true,
                        "class": "text-left"
                    },
                    {
                        "targets": 7,
                        "orderable": true,
                        "class": "text-left"
                    },
                    {
                        "targets": 8,
                        "orderable": true,
                        "class": "text-left"
                    },
                    // {
                    //     "targets": 9,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 10,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 11,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 12,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 13,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                    // {
                    //     "targets": 14,
                    //     "orderable": true,
                    //     "class": "text-left"
                    // },
                ],
            });
            $('#organization_tag').change(function() {
                let val = $(this).val()
                extraData.organization_tag = val
                tb_organizations_table.ajax.reload()
            })
            $('#service_tag').change(function() {
                let val = $(this).val()
                extraData.service_tag = val
                tb_organizations_table.ajax.reload()
            })
            $('#status').change(function() {
                let val = $(this).val()
                extraData.status = val
                tb_organizations_table.ajax.reload()
            })
            $('#organization_bookmark_only').change(function(){
                let val = $(this).is(':checked')
                extraData.organization_bookmark_only = val
                tb_organizations_table.ajax.reload()
            })
            $('#start_date').change(function() {
                let val = $(this).val()
                extraData.start_date = val
                tb_organizations_table.ajax.reload()
            })
            $('#end_date').change(function() {
                let val = $(this).val()
                extraData.end_date = val
                tb_organizations_table.ajax.reload()
            })
            $('#saved_filters').change(function() {
                let val = JSON.parse($(this).val())

                if(val.organization_tags){
                    extraData.organization_tag = val.organization_tags.split(',')
                }
                if(val.service_tags){
                    extraData.service_tag = val.service_tags.split(',')
                }
                if(val.status){
                    extraData.status = val.status.split(',')
                }
                if(val.bookmark_only){
                    extraData.organization_bookmark_only = val.bookmark_only
                }
                if(val.start_date){
                    extraData.start_date = val.start_date
                }
                if(val.end_date){
                    extraData.end_date = val.end_date
                }
                // extraData.end_date = val
                tb_organizations_table.ajax.reload()
            })
            $(document).on('click','.organizationTags',function(){
                let id = $(this).data('id');
                let tags = $(this).data('tags');

                if(tags.toString().indexOf(',') !== -1){
                    tags = tags.split(',')
                }
                if(id){
                    $('#organization_tag_pop_up').val(tags)
                    $('#organization_recordid').val(id)
                    $('#organization_recordid_create').val(id)
                    $('#organization_tag_pop_up').selectpicker()
                    $('#organization_tag_pop_up').selectpicker('refresh')
                    $('#editTagModal').modal('show')
                }
            })
            $(document).on('click','.organizationStatuses',function(){
                let id = $(this).data('id');
                let status = $(this).data('status');

                if(status.toString().indexOf(',') !== -1){
                    status = status.split(',')
                }
                if(id){
                    $('#organisation_status_pop_up').val(status)
                    $('#organization_recordid_status').val(id)
                    // $('#organization_recordid_create').val(id)
                    $('#organisation_status_pop_up').selectpicker()
                    $('#organisation_status_pop_up').selectpicker('refresh')
                    $('#editStatusModal').modal('show')
                }
            })
            $('#saveFilterButton').click(function(){
                $('#extra_data_filter_pop_up').val(JSON.stringify(extraData));
                $('#saveFilterModal').modal('show');
            })
            $(document).on('click','.clickBookmark',function(){
                let id = $(this).data('id');
                let value = $(this).data('value');
                if(value == 0){
                    value = 1
                }else{
                    value = 0
                }
                let self = $(this)
                $.ajax({
                    url : '{{ route("tables.saveOrganizationBookmark") }}',
                    method : 'post',
                    data : {value,id},
                    success: function (response) {
                        // location.reload()
                        tb_organizations_table.ajax.reload()
                        // if(value == 0){
                        //     self.find('img').attr('src','/images/bookmark.svg')
                        // }else{
                        //     self.find('img').attr('src','/images/unbookmark.svg')
                        // }
                        alert(response.message)
                    },
                    error : function (error) {
                        console.log(error)
                    }
                })
            })
            $('#export_csv').click(function () {
                _token = '{{ csrf_token() }}'
                $.ajax({
                    url:"{{ route('tracking.export') }}",
                    method : 'POST',
                    data:{extraData,_token},
                    success:function(response){
                        // const url = window.URL.createObjectURL(new Blob([response]));
                        const a = document.createElement('a');
                                a.href = response.path;
                                a.download = 'organizations.csv';
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
    <script src="{{ asset('js/organization_ajaxscript.js') }}"></script>
@endsection
