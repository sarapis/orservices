@extends('layouts.app')
@section('title')
    Contacts
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css"> -->
{{--
<style type="text/css">
    .table a{
        text-decoration:none !important;
        color: rgba(40,53,147,.9);
        white-space: normal;
    }
    .footable.breakpoint > tbody > tr > td > span.footable-toggle{
        position: absolute;
        right: 25px;
        font-size: 25px;
        color: #000000;
    }
    .ui-menu .ui-menu-item .ui-state-active {
        padding-left: 0 !important;
    }
    ul#ui-id-1 {
        width: 260px !important;
    }

    #map{
        position: relative !important;
        z-index: 0 !important;
    }
    @media (max-width: 768px) {
        .property{
            padding-left: 30px !important;
        }
        #map{
            display: block !important;
            width: 100% !important;
        }
    }

    button[data-id="religion"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="faith_tradition"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="denomination"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="judicatory_body"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="has-email"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="has-phone"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="tag"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_address"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_languages"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_borough"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    button[data-id="contact_zipcode"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    .sel-label-org {
        width: 15%;
    }
    #clear-filter-contacts-btn {
        width: 100%;
    }
    #tbl-contact_wrapper {
        overflow-x: scroll;
    }
</style> --}}

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <h4 class="card-title title_edit mb-30">
                    Contacts
                </h4>
                <div class="card">
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        {{-- <th>
                                            <input type="checkbox" name="All_check" id="check_all">
                                        Action</th> --}}
                                        <!-- <th class="default-inactive">Id</th> -->
                                        <th>Name</th>
                                        <th>Contact Title</th>
                                        {{-- <th>Contact Department</th> --}}
                                        <th>Contact Email</th>
                                        <th>Organization</th>
                                        <th>Location</th>
                                        <th>Services</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('customScript')
<script>
    var dataTable;
    var checked_terms_set;
    var filter_map = "";
    var marks = [];
    var ajaxUrl = "{{ route('getContacts') }}";

    $(document).ready(function() {
        dataTable = $('#table').DataTable({
            processing: true,
            serverSide: true,
            "lengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
            // retrieve: true,
            ajax: {
                    url: ajaxUrl,
                    method : "post",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    "data": function (d){
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            // d.extraData = extraData;
                        }
                    },
                    complete: function (data) {
                            // $('.contactCheckbox').prop('checked',false);
                            // $('#check_all').prop('checked',false);
                            // contact_id = []
                            // contact_id = data.id;
                            // $('#loading').hide();
                          //  console.log(data['responseJSON']);
                        //   locations = []
                        //     data['responseJSON'].data.map(function(item,key){

                        //         locations[item.id] = item.location;
                        //         locations[item.id]['name'] = item.first_name + ' '+ item.last_name;
                        //         locations[item.id]['phone'] = item.phone != null ? item.phone : '';
                        //         allContactsIds[item.id] = item.id;
                        //     });
                      //      locations = data['responseJSON'].locations
                           // allContactsIds = data['responseJSON'].contactId
                        //    if(!councilDistrictFilter && !electionDistrictFilter && !assemblyDistrictFilter){
                        //     fnCallback()
                        //    }
                        },
                    // "success" : function(res){
                    //     console.log(res)
                    //     // locations = res.data.locations
                    // },
                    // "error" : function(err){
                    //     console.log(err)
                    // }
                },
            columns: [
                    // { data: 'action', name: 'action' },
                    { data: 'contact_name', name: 'contact_name' },
                    { data: 'contact_title', name: 'contact_title' },
                    // { data: 'contact_department', name: 'contact_department' },
                    { data: 'contact_email', name: 'contact_email' },
                    { data: 'contact_organizations', name: 'contact_organizations' },
                    { data: 'contact_facility', name: 'contact_facility' },
                    { data: 'contact_service', name: 'contact_service' },
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
                        "orderable": false,
                        "class": "text-left"
                    },
                    {
                        "targets": 5,
                        "orderable": true,
                        "class": "text-left"
                    },
                    // {
                    //     "targets": 6,
                    //     "orderable": true,
                    //     "class": "text-center",
                    // },
                    // {
                    //     "targets": 7,
                    //     "orderable": true,
                    //     "class": "text-center",
                    // },
            ],
                // "fnDrawCallback": function(){
                //     if($('#check_all').is(":checked")) {
                //         $('.contactCheckbox').prop('checked',true);
                //     }else{
                //         $('.contactCheckbox').prop('checked',false);
                //     }
                //     if(!councilDistrictFilter && !electionDistrictFilter && !assemblyDistrictFilter){
                //         fnCallback()
                //     }
                //     funCheckBox()
                // }
        })
    })

</script>

@endsection


