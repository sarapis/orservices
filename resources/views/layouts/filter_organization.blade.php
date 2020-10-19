<style type="text/css">
</style>
<form action="/search_organization" method="GET" id="filter_organization" class="m-0">
  	<div class="filter-bar container-fluid bg-primary-color home_serach_form filter_serach">
		<div class="container">
			<div class="row">
				<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
				<div class="col-md-5 col-sm-8">
					<div class="form-group text-left form-material m-0" data-plugin="formMaterial">
						<img src="/frontend/assets/images/search.png" alt="" title="" class="form_icon_img">
						<input type="text" class="form-control search-form" name="find" placeholder="Search for Organization" id="search_organization" @if(isset($chip_organization)) value="{{$chip_organization}}" @endif>
					</div>
				</div>
				<!-- <div class="col-md-5 col-sm-5">
					<div class="form-group text-left form-material m-0" data-plugin="formMaterial">
						<img src="/frontend/assets/images/location.png" alt="" title="" class="form_icon_img">
						<input type="text" class="form-control pr-50" id="location1" name="search_address" placeholder="Search Location...">
					</div>
				</div> -->
				<div class="col-md-2 col-sm-4">
					<button class="btn btn-raised btn-lg btn_darkblack search_btn" title="Search" style="line-height: 31px;">Search</button>
				</div>
			</div>
		</div>
		{{-- <div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="row">
					<div class="col-md-2">
						<div class="col-md-8 pt-15 pb-15 pl-15">
				            <div class="btn-group dropdown btn-feature">
				                <button type="button" class="btn btn-primary dropdown-toggle btn-button"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
				                    Sort by Updated
				                </button>
				                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
				                    <a @if(isset($sort) && $sort == 'Most Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Most Recently Updated</a>
				                    <a @if(isset($sort) && $sort == 'Least Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Least Recently Updated</a>
				                </div>
				            </div>
				        </div>
					</div>
		          	<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
					<div class="col-md-4 m-auto">
						<div class="input-search">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control search-form" name="find" placeholder="Search for Organization" id="search_organization" @if(isset($chip_organization)) value="{{$chip_organization}}" @endif>
						</div>
					</div>
					@if (Auth::user())
					<div class="col-md-4 m-auto">
						<div class="organization-tags-div">
		                    <select class="form-control selectpicker" multiple data-live-search="true" id="organization_tag" data-size="3" name="organization_tag[]">
		                    	<option value="" selected disabled hidden>Filter by Tags</option>
		                        @foreach($organization_tag_list as $key => $organization_tag)
		                            <option value="{{$organization_tag}}">{{$organization_tag}}</option>
		                        @endforeach
		                    </select>
		                </div>
		            </div>
		            @endif
				</div>
			</div>
		</div> --}}
  	</div>
<div class="top_services_filter">
	<div class="container">
        <!-- Types Of Services -->
            @auth
            @if (Auth::user()->roles && Auth::user()->roles->name == 'System Admin' || (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin'))
			<div class="dropdown">
				<button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
					Organization Tags
                </button>
				<div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" >
                    {{-- {!! Form::select('organization_tags',$organization_tagsArray,'',['class' => 'btn dropdown-toggle']) !!} --}}
                    @foreach ($organization_tagsArray as $value)
                        <a class="dropdown-item drop-tags{{ isset($organization_tags) && strpos($organization_tags,$value) !== false  ? ' active' : '' }}" href="javascript:void(0)" role="menuitem">{{ $value }}</a>
                    @endforeach
                    {{-- <select class="form-control selectpicker" multiple data-live-search="true" id="organization_tag" data-size="3" name="organization_tag[]">
                        <option value="" selected disabled hidden>Filter by Tags</option>
                        @foreach($organization_tag_list as $key => $organization_tag)
                            <option value="{{$organization_tag}}">{{$organization_tag}}</option>
                        @endforeach
                    </select> --}}

                </div>
            </div>
            @endif
            @endauth
		<!--end  Types Of Services -->
		<!-- Sort By -->
			<div class="dropdown">
				<button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
					Sort By
				</button>
				<div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
					<a @if(isset($sort) && $sort == 'Most Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Most Recently Updated</a>
                    <a @if(isset($sort) && $sort == 'Least Recently Updated') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Least Recently Updated</a>
				</div>
			</div>
		<!--end  Sort By -->

		<!-- Results Per Page -->
		<div class="dropdown">
			<button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown3" data-toggle="dropdown" aria-expanded="false">
				Results Per Page
            </button>
			<div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown3" role="menu">
				<a class="dropdown-item drop-paginate {{ isset($pagination) && $pagination == '10' ? 'active' : ''}}" >10</a>
				<a class="dropdown-item drop-paginate {{ isset($pagination) && $pagination == '25' ? 'active' : ''}}" >25</a>
				<a class="dropdown-item drop-paginate {{ isset($pagination) && $pagination == '50' ? 'active' : ''}}">50</a>
			</div>
		</div>
		<!--end Results Per Page -->
        <div class="dropdown btn_download float-right">
                <button type="button" class="float-right btn_share_download dropdown-toggle" id="exampleBulletDropdown4" data-toggle="dropdown" aria-expanded="false">
                    <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                </div>
            </div>
            <input type="hidden" name="organization_pdf" id="organization_pdf" value="">
            <input type="hidden" name="organization_csv" id="organization_csv" value="">
            <button type="button" class="float-right btn_share_download">
                <img src="/frontend/assets/images/share.png" alt="" title="" class="mr-10 share_image">
                <div class="sharethis-inline-share-buttons"></div>
            </button>
	</div>
</div>
<style>
/* @media (max-width: 768px){
  .filter-bar{
    display: none;
  }
} */
</style>

<script type="text/javascript">
$(document).ready(function(){
	$('.dropdown-status').click(function(){
		var status = $(this).attr('at');
		var status_meta = $(this).html();
		$("#meta_status").html(status_meta);
		$("#status").val(status);
		$("#filter_organization").submit();
	});

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



    $('.download_pdf').on('click', function(e){
        $('#organization_pdf').val('pdf');
        $("#filter_organization").submit();
        $('#organization_pdf').val('');
    });
    $('.download_csv').on('click', function(e){
        $('#organization_csv').val('csv');
        $("#filter_organization").submit();
        $('#organization_csv').val('');
    });
    $('.regular-checkbox').on('click', function(e){
        $(this).prev().trigger('click');
        $('input', $(this).next().next()).prop('checked',0);
        $("#filter_organization").submit();
    });
    $('.drop-paginate').on('click', function(){
        $("#paginate").val($(this).text());
        $("#filter_organization").submit();
    });
    $('.drop-sort').on('click', function(){
        $("#sort").val($(this).text());
        $("#filter_organization").submit();
    });
    let organization_tags = "{{ isset($organization_tags) ? $organization_tags : '' }}"

    if(organization_tags == ""){
        organization_tags = []
    }else{
        organization_tags = organization_tags.split(',')
    }

    $('.drop-tags').on('click', function(){
        let text = $(this).text();
        if($.inArray(text,organization_tags) == -1){
            organization_tags.push(text)
        }else{
            organization_tags.splice(organization_tags.indexOf(text),1)
        }
        $("#organization_tags").val(organization_tags);
        $("#filter_organization").submit();
    });
    $('#sidebar_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('child_') > -1;
        });
        // console.log(selected_taxonomy_ids);
        selected_taxonomy_ids = selected_taxonomy_ids.toString();
        $("#selected_taxonomies").val(selected_taxonomy_ids);
        $("#filter_organization").submit();
    });


});
</script>
