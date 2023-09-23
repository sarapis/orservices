    <style>
        .pac-logo:after{
        display: none;
        }
        ul, #myUL {
        list-style-type: none;
        }
        .tree2{
            padding-left: 25px;
        }
        .indicator{
            margin-left: -18px;
        }
        .child-ul{
            padding-left: 18px;
        }
        .inputChecked{
            font-size: 1em !important;
            font-weight: 400;
        }
        .branch{
            padding: 5px 0;
        }
        .nobranch{
            padding: 5px 0;
        }
        .regular-checkbox{
            -webkit-appearance: none;
            background-color: #fafafa;
            border: 1px solid #2196F3;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
            padding: 9px !important;
            border-radius: 3px;
            display: inline-block;
            position: relative;
            top: 4px;
        }
        .regular-checkbox:active, .regular-checkbox:checked:active {
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
        }
        .regular-checkbox:checked {
            background-color: #2196F3;

        /*  box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);*/
            color: #ffffff;
        }
        .regular-checkbox:checked:after {
            content: '\2714';
            font-size: 14px;
            position: absolute;
            top: 0px;
            left: 3px;
            color: #ffffff;
        }
        #cityagency{
            padding-left: 12px;
        }
        #insurance{
            padding-left: 12px;
        }
        #ages{
            padding-left: 12px;
        }
        #languages{
            padding-left: 12px;
        }
        #service_settings{
            padding-left: 12px;
        }
        #culturals{
            padding-left: 12px;
        }
        .alert{
            padding-left: 15px;
            padding-right: 30px;
        }
        .mobile-btn{
            display: none;
        }
        .select2-container{
            width: 100% !important;
        }
        .select2-search__field {
            width: 100% !important;
        }
        @media (max-width: 768px) {
            .mobile-btn{
                display: block;
            }
            .btn-feature{
                display: none;
            }
        }

        @media (max-width: 375px) {
            .navbar-brand-text{
                display: block;
            }
            .navbar-header{
                height: 90px;
            }
        }

        .jstree-themeicon {
            display: none !important;
        }

        #mCSB_1_container {
            overflow: scroll !important;
        }

    </style>

    {{-- <nav id="sidebar"> --}}
    <nav id="">
        {{-- <ul class="list-unstyled components pt-0 mb-0 sidebar-menu">
            <li class="option-side">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services" style="display: block;padding-left: 10px;">Services</a></button>
            </li>
            <li class="option-side">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/organizations" style="display: block;padding-left: 10px;">Organizations</a></button>
            </li>
            <li class="option-side">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/about" style="display: block;padding-left: 10px;">About</a></button>
            </li>
        </ul> --}}

        <ul class="list-unstyled components pt-0">
            @if ((Request::path() == 'services') || (Request::segment(1) == 'search') || (Request::segment(1) == 'services') || (Request::segment(1) == 'organizations') || (Request::segment(1) == 'services_near_me') || (Request::segment(1) == 'organizations'))
            <!-- <li class="option-side">
                <a href="#target_populations" class="text-side" data-toggle="collapse" aria-expanded="false">Types of People</a>
                <ul class="collapse list-unstyled option-ul" id="target_populations">
                    <li>
                        <select class="js-example-basic-multiple js-example-placeholder-multiple form-control" multiple data-plugin="select2" id="target_multiple" name="target_populations[]">
                            @foreach($target_taxonomies as $child)
                                <option value="{{$child->taxonomy_recordid}}" @if((isset($target_populations) && in_array($child->taxonomy_recordid, $target_populations))) selected @endif>{{$child->taxonomy_name}}</option>
                            @endforeach
                        </select>
                    </li>
                </ul>
            </li> -->
            {{-- <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="false">Types of Services</a>

                <ul class="collapse list-unstyled option-ul show" id="projectcategory">
                    <div id="sidebar_tree">
                    </div>
                </ul>
            </li> --}}
            {{-- <li class="option-side mobile-btn">
                <a href="#export" class="text-side" data-toggle="collapse" aria-expanded="false">Download CSV/PDF</a>
                <ul class="collapse list-unstyled option-ul" id="export">
                    <li class="nobranch">
                        <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Export CSV</a>
                        <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                    </li>
                </ul>
            </li>
            <li class="option-side mobile-btn">
                <a href="#perpage" class="text-side" data-toggle="collapse" aria-expanded="false">Results Per Page</a>
                <ul class="collapse list-unstyled option-ul" id="perpage">
                    <li class="nobranch">
                        <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                        <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                        <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                    </li>
                </ul>
            </li>
            <li class="option-side mobile-btn">
                <a href="#sort" class="text-side" data-toggle="collapse" aria-expanded="false">Sort</a>
                <ul class="collapse list-unstyled option-ul">
                    <li class="nobranch">
                        <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                        <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                        <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Distance from Address</a>
                    </li>
                </ul>
            </li> --}}
            @endif
            <input type="hidden" name="paginate" id="paginate" @if(isset($pagination)) value="{{$pagination}}" @else value="10" @endif>
            <input type="hidden" name="sort" id="sort" @if(isset($sort)) value="{{$sort}}" @endif>
            <input type="hidden" name="target_all" id="target_all">
            <input type="hidden" name="pdf" id="pdf">
            <input type="hidden" name="csv" id="csv">
            <input type="hidden" name="filter_label" id="filter_label">
            <input type="hidden" name="organization_tags" id="organization_tags" value="{{ isset($selected_organization) ? $selected_organization : '' }}">
            <input type="hidden" name="service_tags" id="service_tags" value="{{ isset($selected_service_tags) ? $selected_service_tags : '' }}">
            <input type="hidden" name="sdoh_codes_category" id="sdoh_codes_category" value="{{ isset($sdoh_codes_category) ? $sdoh_codes_category : '' }}">
            <input type="hidden" name="sdoh_codes_data" id="sdoh_codes_data" value="{{ isset($selected_sdoh_code) ? json_encode($selected_sdoh_code) : '' }}">
            {{-- <input type="hidden" name="sdoh_codes_category" id="sdoh_codes_category" value="{{ isset($selected_sdoh_code_categoy) ? $selected_sdoh_code_categoy : '' }}"> --}}
            <input type="hidden" id="selected_taxonomies" name="selected_taxonomies">
        </ul>
    </nav>
</form>
{{-- let SDOH_code_list = [];
    var SDOH_codes_ids = []
    var selected_sdoh_code = '<?php isset($selected_sdoh_code) ? print_r($selected_sdoh_code) : print_r('') ; ?>'

    if(selected_sdoh_code.length > 0) {
        SDOH_codes_ids = JSON.parse(selected_sdoh_code);
    }
    let sdoh_codes_Array =  `<?php isset($sdoh_codes_Array) ? print($sdoh_codes_Array) : []; ?>`
    sdoh_codes_Array = sdoh_codes_Array ? JSON.parse(sdoh_codes_Array) : []

    if(sdoh_codes_Array.length > 0){
        $.each(sdoh_codes_Array,function name(key,value) {
            var alt_data = {};
            alt_data.text = value.code;
            alt_data.state = {};
            alt_data.id = 'sdoh_code_'+value.id;
            if (SDOH_codes_ids != null && SDOH_codes_ids.includes(value.id.toString())) {

                alt_data.state.selected = true;
            }
            SDOH_code_list.push(alt_data)
        })
    }
    $('#sdoh_codes_tree').jstree({
        'plugins': ["checkbox", "wholerow", "sort","search"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: SDOH_code_list
        },
        "search": {
            "case_insensitive": true,
            "show_only_matches" : true
        },
    });
    $('#sdoh_codes_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#sdoh_codes_tree').jstree("get_checked");

        var SDOH_codes_ids = []
        all_selected_ids.filter(function(id) {
            if(id.indexOf('sdoh_code_') > -1){
                SDOH_codes_ids.push(id.split('sdoh_code_')[1])
            }
        });
        $("#sdoh_codes_data").val(JSON.stringify(SDOH_codes_ids));
        $("#filter").submit();
    }); --}}
<script src="{{asset('js/treeview2.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>

<script>
$(document).ready(function(){

    var tree_data_list = [];

    var taxonomy_tree = <?php print_r(json_encode($taxonomy_tree)) ?>;
    var alt_key;
    var urlParams = new URLSearchParams(window.location.search);
    var selected_taxonomies = [];
    if(urlParams.has('selected_taxonomies')) {
        selected_taxonomies = urlParams.get('selected_taxonomies').split(',');
    }


    if (typeof taxonomy_tree == 'array') {
        for (alt_key = 0; alt_key < taxonomy_tree.length; alt_key++) {
            var alt_data = {};
            var alt_tree = taxonomy_tree[alt_key];
            alt_data.text = alt_tree.alt_taxonomy_name;
            alt_data.state = {};
            alt_data.id = 'alt_' + alt_key;
            var alt_tree_parent_taxonomies = [];
            if (alt_tree.parent_taxonomies != undefined) {
                alt_tree_parent_taxonomies = alt_tree.parent_taxonomies;
            }

            var parent_data_list = [];
            for (parent_key = 0; parent_key < alt_tree_parent_taxonomies.length; parent_key++) {
                var parent_tree = alt_tree_parent_taxonomies[parent_key];
                var parent_data = {};

                if (parent_tree.parent_taxonomy != undefined) {
                    if (typeof(parent_tree.parent_taxonomy) == "string") {
                        parent_data.text = parent_tree.parent_taxonomy;
                        parent_data.id = alt_data.id + '_parent_' + parent_key;
                    }
                    else {
                        parent_data.text = parent_tree.parent_taxonomy.taxonomy_name;
                        parent_data.id = alt_data.id + '_child_' + parent_tree.parent_taxonomy.taxonomy_recordid;
                        parent_data.state = {};
                        if (selected_taxonomies.indexOf(parent_data.id) > -1) {
                            parent_data.state.selected = true;
                        }
                    }
                    var parent_tree_child_taxonomies = [];
                    if (parent_tree.child_taxonomies != undefined) {
                        parent_tree_child_taxonomies = parent_tree.child_taxonomies;
                    }
                    var child_data_list = [];
                    for (child_key = 0; child_key < parent_tree_child_taxonomies.length; child_key++) {
                        var child_tree = parent_tree_child_taxonomies[child_key];
                        var child_data = {};
                        if (child_tree != undefined) {
                            child_data.text = child_tree.taxonomy_name;
                            child_data.state = {};
                            child_data.id = parent_data.id + '_child_' + child_tree.taxonomy_recordid;

                            if (selected_taxonomies.indexOf(child_data.id) > -1) {
                                child_data.state.selected = true;
                            }
                            child_data_list.push(child_data);
                        }
                    }
                    if (child_data_list.length != 0) {
                        parent_data.children = child_data_list;
                    }
                    parent_data_list.push(parent_data);
                }
            }
            if (parent_data_list.length != 0) {
                alt_data.children = parent_data_list;
            }
            tree_data_list[alt_key] = alt_data;
        }
    } else {

        for (parent_key = 0; parent_key < taxonomy_tree.parent_taxonomies.length; parent_key ++) {
            var parent_data = {};
            parent_data.id = 'child_' + taxonomy_tree.parent_taxonomies[parent_key].taxonomy_recordid;
            parent_data.text = taxonomy_tree.parent_taxonomies[parent_key].taxonomy_name;
            parent_data.state = {};
            if (selected_taxonomies.indexOf(parent_data.id) > -1) {
                parent_data.state.selected = true;
            }
            // var parent_tree_child_taxonomies = taxonomy_tree.parent_taxonomies[parent_key].child_taxonomies;
            // var child_data_list = [];
            // for (child_key = 0; child_key < parent_tree_child_taxonomies.length; child_key++) {
            //     var child_tree = parent_tree_child_taxonomies[child_key];
            //     var child_data = {};
            //     if (child_tree != undefined) {
            //         child_data.text = child_tree.taxonomy_name;
            //         child_data.state = {};
            //         child_data.id = parent_data.id + '_child_' + child_tree.taxonomy_recordid;

            //         if (selected_taxonomies.indexOf(child_data.id) > -1) {
            //             child_data.state.selected = true;
            //         }
            //         child_data_list.push(child_data);
            //     }
            // }
            // if (child_data_list.length != 0) {
            //     parent_data.children = child_data_list;
            // }
            tree_data_list[parent_key] = parent_data;
        }
    }

     $('#sidebar_tree').jstree({
        'plugins': ["checkbox", "wholerow", "sort"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: tree_data_list
        }
    });



    $('.regular-checkbox').on('click', function(e){
        $(this).prev().trigger('click');
        $('input', $(this).next().next()).prop('checked',0);
        $("#filter").submit();
    });
    $('.drop-paginate').on('click', function(){
        $("#paginate").val($(this).text());
        $("#filter").submit();
    });
    $('.drop-sort').on('click', function(){
        $("#sort").val($(this).text());
        $("#filter").submit();
    });
    let organization_tags = $('#organization_tags').val() != '' ? JSON.parse($('#organization_tags').val()) : [];

    // if(organization_tags == ""){
    //     organization_tags = []
    // }else{
    //     organization_tags = JSON.parse(organization_tags)
    // }
    // $('.drop-tags').on('click', function(){
    //     // let text = $(this).text();
    //     let text = $(this).data('id');
    //     if($.inArray(text,organization_tags) == -1){
    //         organization_tags.push(text)
    //     }else{
    //         organization_tags.splice(organization_tags.indexOf(text),1)
    //     }
    //     $("#organization_tags").val(JSON.stringify(organization_tags));
    //     // $("#organization_tags").val($(this).text());
    //     $("#filter").submit();
    // });

    // organization_tags_tree
    let tree_tags_list = [];
    var selected_org_tags_ids = []
    var selected_organization = '<?php isset($selected_organization) ? print_r($selected_organization) : print_r('') ; ?>'
    if(selected_organization.length > 0) {
        selected_org_tags_ids = selected_organization;
    }
    let organization_tagsArray =  `<?php isset($organization_tagsArray) ? print_r($organization_tagsArray) : print_r(''); ?>`
    organization_tagsArray = organization_tagsArray ? JSON.parse(organization_tagsArray) : []
    if(organization_tagsArray.length > 0){
        $.each(organization_tagsArray,function name(key,value) {
            var alt_data = {};
            alt_data.text = value.tag;
            alt_data.state = {};
            alt_data.id = 'orgtag_'+value.id;
            if (selected_org_tags_ids.indexOf(value.id) > -1) {
                alt_data.state.selected = true;
            }
            tree_tags_list.push(alt_data)
        })
    }
    $('#organization_tags_tree').jstree({
        'plugins': ["checkbox", "wholerow", "sort"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: tree_tags_list
        }
    });
    $('#organization_tags_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#organization_tags_tree').jstree("get_checked");
        var selected_org_tags_ids = []
        all_selected_ids.filter(function(id) {
            if(id.indexOf('orgtag_') > -1){
                selected_org_tags_ids.push(id.split('orgtag_')[1])
            }
            // return id.indexOf('orgtag_') > -1;
        });
        // selected_org_tags_ids = selected_org_tags_ids.toString();
        $("#organization_tags").val(JSON.stringify(selected_org_tags_ids));
        $("#filter").submit();
    });

    // service tags
    let service_tree_tags_list = [];
    var selected_service_tags_ids = []
    var selected_service = '<?php isset($selected_service_tags) ? print_r($selected_service_tags) : print_r('') ; ?>'

    if(selected_service.length > 0) {
        selected_service_tags_ids = selected_service;
    }
    let service_tagsArray =  `<?php isset($service_tagsArray) ? print_r($service_tagsArray) : []; ?>`
    service_tagsArray = service_tagsArray ? JSON.parse(service_tagsArray) : []
    if(service_tagsArray.length > 0){
        $.each(service_tagsArray,function name(key,value) {
            var alt_data = {};
            alt_data.text = value.tag;
            alt_data.state = {};
            alt_data.id = 'servicetag_'+value.id;
            if (selected_service_tags_ids.indexOf(value.id) > -1) {
                alt_data.state.selected = true;
            }
            service_tree_tags_list.push(alt_data)
        })
    }
    $('#service_tags_tree').jstree({
        'plugins': ["checkbox", "wholerow", "sort"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: service_tree_tags_list
        }
    });
    $('#service_tags_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#service_tags_tree').jstree("get_checked");
        var selected_service_tags_ids = []
        all_selected_ids.filter(function(id) {
            if(id.indexOf('servicetag_') > -1){
                selected_service_tags_ids.push(id.split('servicetag_')[1])
            }
            // return id.indexOf('servicetag_') > -1;
        });
        // selected_service_tags_ids = selected_service_tags_ids.toString();
        $("#service_tags").val(JSON.stringify(selected_service_tags_ids));
        $("#filter").submit();
    });

    // sdoh codes category
    let SDOH_code_category_list = [];
    var SDOH_codes_category_ids = []
    var selected_sdoh_code_category = `<?php isset($sdoh_codes_category) ? print_r($sdoh_codes_category) : print_r('') ; ?>`

    if(selected_sdoh_code_category.length > 0) {
        SDOH_codes_category_ids = JSON.parse(selected_sdoh_code_category);
    }

    let sdoh_codes_category_Array =  `<?php isset($sdoh_codes_category_Array) ? print_r($sdoh_codes_category_Array) : []; ?>`
    sdoh_codes_category_Array = sdoh_codes_category_Array ? JSON.parse(sdoh_codes_category_Array) : []

    if(sdoh_codes_category_Array.length > 0){
        $.each(sdoh_codes_category_Array,function name(key,value) {

            var alt_data = {};
            alt_data.text = value.name;
            alt_data.state = {};
            // alt_data.id = 'sdohcodes_category_'+value.replace(' ','_');
            alt_data.id = 'sdohcodes_category_'+value.id;

            // if (SDOH_codes_category_ids.length > 0 && SDOH_codes_category_ids.indexOf(value.replace(' ','_')) > -1) {
            if (SDOH_codes_category_ids.length > 0 && SDOH_codes_category_ids.indexOf((value.id.toString())) > -1 ) {
                alt_data.state.selected = true;
            }
            SDOH_code_category_list.push(alt_data)
        })
    }
    $('#sdoh_codes_category_tree').jstree({
        'plugins': ["checkbox", "wholerow", "sort"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: SDOH_code_category_list
        }
    });
    $('#sdoh_codes_category_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#sdoh_codes_category_tree').jstree("get_checked");
        var SDOH_codes_category_ids = []
        all_selected_ids.filter(function(id) {
            if(id.indexOf('sdohcodes_category_') > -1){
                SDOH_codes_category_ids.push(id.split('sdohcodes_category_')[1])
            }
            // return id.indexOf('servicetag_') > -1;
        });
        // SDOH_codes_category_ids = SDOH_codes_category_ids.toString();
        $("#sdoh_codes_category").val(JSON.stringify(SDOH_codes_category_ids));
        $("#filter").submit();
    });

    // sdoh codes



// ------------- //  ----------//

    $('#sdoh_codes_select').on('change', function(){
        // let text = $(this).text();
        let SDOH_codes_ids = $(this).val()
        $("#sdoh_codes_data").val(JSON.stringify(SDOH_codes_ids));
        $("#filter").submit();
    });
    $('.drop-label').on('click', function(){
        // let text = $(this).text();
        let text = $(this).data('id');

        $('#filter_label').val(text)
        $("#filter").submit();
    });
    $('.download_csv').on('click', function(){
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('child_') > -1;
        });
        // console.log(selected_taxonomy_ids);
        selected_taxonomy_ids = selected_taxonomy_ids.toString();
        $("#selected_taxonomies").val(selected_taxonomy_ids);
        $("#csv").val('csv');
        $("#filter").submit();
        $("#csv").val('');
    });
    $('.download_pdf').on('click', function(){
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('child_') > -1;
        });
        // console.log(selected_taxonomy_ids);
        selected_taxonomy_ids = selected_taxonomy_ids.toString();
        $("#selected_taxonomies").val(selected_taxonomy_ids);
        $("#pdf").val('pdf');
        $("#filter").submit();
        $("#pdf").val('');
    });
    $('#target_multiple').on('change', function(){

        $("#filter").submit();
    });

    function matchCustom(params, data) {
    // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
          return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
          return null;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (data.text.indexOf(params.term) > -1) {
          var modifiedData = $.extend({}, data, true);
          // modifiedData.text += ' (matched)';

          // You can return modified objects from here
          // This includes matching the `children` how you want in nested data sets
          return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            matcher: matchCustom,
            placeholder: "Search here"
        });
    });

    $('#sidebar_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('child_') > -1;
        });
        console.log(selected_taxonomy_ids);
        selected_taxonomy_ids = selected_taxonomy_ids.toString();
        $("#selected_taxonomies").val(selected_taxonomy_ids);
        $("#filter").submit();
    });

});
</script>
<style>
    @foreach ($grandparent_taxonomies as $chunk)
        .{{str_replace(' ', '_', $chunk)}} {
            background-color: rgb({{rand(0, 255)}}, {{rand(0, 255)}}, {{rand(0, 255)}}) !important;
        }
    @endforeach
    @foreach ($parent_taxonomies as $chunk)
        .{{str_replace(' ', '_', $chunk)}} {
            background-color: rgb({{rand(0, 255)}}, {{rand(0, 255)}}, {{rand(0, 255)}}) !important;
        }
    @endforeach
    @foreach ($son_taxonomies as $chunk)
        .{{str_replace(' ', '_', $chunk)}} {
            background-color: rgb({{rand(0, 255)}}, {{rand(0, 255)}}, {{rand(0, 255)}}) !important;
        }
    @endforeach
</style>
