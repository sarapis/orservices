@extends('layouts.app')
@section('title')
Locations
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.dataTables.min.css"> --}}
{{--
<style type="text/css">
    .table a {
        text-decoration: none !important;
        color: rgba(40, 53, 147, .9);
        white-space: normal;
    }

    .footable.breakpoint>tbody>tr>td>span.footable-toggle {
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

    #map {
        position: relative !important;
        z-index: 0 !important;
    }

    @media (max-width: 768px) {
        .property {
            padding-left: 30px !important;
        }

        #map {
            display: block !important;
            width: 100% !important;
        }
    }

    .morecontent span {
        display: none;

    }

    .morelink {
        color: #428bca;
    }

    button[data-id="type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="borough"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="tag"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="city_council_district"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="community_district"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="zipcode"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="address"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .sel-label-org {
        width: 15%;
    }

    #clear-filter-locations-btn {
        width: 100%;
    }

</style> --}}

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="locations-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Facility</p>
                        </h4>
                        <div class="table-responsive">
                            <table class="table jambo_table bulk_action" id="tbl-location">
                                <thead>
                                    <tr>
                                        {{-- <th class="default-active"></th> --}}
                                        <th class="default-active">Facility Name</th>
                                        <th class="default-active">Organization</th>
                                        <th class="default-active">Address</th>
                                        <th class="default-active">Contacts</th>
                                        <th class="default-active">Services</th>
                                        {{-- <th class="default-active">Facility Description</th> --}}
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
    var ajaxUrl = "{{ route('getFacilities') }}"

    // $(document).ready(function() {
    //     // $('#waiting').hide();
    //     sessionStorage.setItem('check_marks', '');
    //     dataTable = $('#tbl-').DataTable({
    //         "dom": 'lBfrtip',
    //         "order": [[ 1, 'desc' ]],
    //         "serverSide": true,
    //         "searching": true,
    //         "scrollY": 500,
    //         "ajax": function (data, callback, settings) {
    //                 var start = data.start;
    //                 var length = data.length;
    //                 var search_term = data.search.value;
    //                 $.ajaxSetup({
    //                     headers: {
    //                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //                     }
    //                 });
    //                 // $('#waiting').show();
    //                 $('#loading').show();

    //                 $.ajax({
    //                     type: "POST",
    //                     url: "/get_all_facilities",
    //                     data: {
    //                         start: start,
    //                         length: length,
    //                         search_term: search_term,
    //                     },
    //                     success: function (response) {
    //                         // $('#waiting').hide();
    //                         $('#loading').hide();
    //                         callback({
    //                             draw: data.draw,
    //                             data: response.data,
    //                             recordsTotal: response.recordsTotal,
    //                             recordsFiltered: response.recordsFiltered,
    //                         });
    //                     },
    //                     error: function (data) {
    //                         $('#loading').hide();
    //                         console.log('Error:', data);
    //                     }
    //                 });
    //             },
    //         "columnDefs": [
    //             { targets: 'default-inactive', visible: false},
    //             {
    //                 "targets": 0,
    //                 "data": null,
    //                 "render": function ( data, type, row ) {
    //                     return '<a class=" open-td" href="/facility/' + row[1] + '" style="color: #007bff;"><i class="fa fa-fw fa-eye"></a>';
    //                 }

    //             }
    //         ],
    //         'select': {
    //             'style': 'multi'
    //         },
    //         'scroller': {
    //             'loadingIndicator': true
    //         }
    //     });
    // })
     $(document).ready(function() {
        dataTable = $('#tbl-location').DataTable({
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
                    { data: 'location_name', name: 'location_name' },
                    { data: 'location_organization', name: 'location_organization' },
                    { data: 'location_address', name: 'location_address' },
                    { data: 'location_contact', name: 'location_contact' },
                    { data: 'location_service', name: 'location_service' },
                    // { data: 'contact_organizations', name: 'contact_organizations' },
                    // { data: 'political_party', name: 'political_party' },
                    // { data: 'date_last_voted', name: 'date_last_voted' },
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
                    // {
                    //     "targets": 4,
                    //     "orderable": false,
                    //     "class": "text-center"
                    // },
                    // {
                    //     "targets": 5,
                    //     "orderable": true,
                    //     "class": "text-center"
                    // },
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
