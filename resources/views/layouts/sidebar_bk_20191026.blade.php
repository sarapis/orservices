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

    .jstree-themeicon {
        display: none !important;
    }

</style>

<nav id="sidebar">
    <ul class="list-unstyled components pt-0 mb-0 sidebar-menu"> 
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services" style="display: block;padding-left: 10px;">Services</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/organizations" style="display: block;padding-left: 10px;">Organizations</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/about" style="display: block;padding-left: 10px;">About</a></button>
        </li>
    </ul>

       <ul class="list-unstyled components pt-0"> 

            <li class="option-side">
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
            </li>
            <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="true">Types of Services</a>
                <input type="hidden" id="selected_taxonomies" name="selected_taxonomies">
                <ul class="collapse list-unstyled option-ul show" id="projectcategory">
                    <div id="sidebar_tree">
                        
                    </div>
                </ul>
            </li>
            
            <li class="option-side mobile-btn">
                <a href="#export" class="text-side" data-toggle="collapse" aria-expanded="false">Print/Export</a>
                <ul class="collapse list-unstyled option-ul" id="export">
                    <li class="nobranch">
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem">Expert CSV</a>
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem">Print PDF action</a>
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
            </li>
            <input type="hidden" name="paginate" id="paginate" @if(isset($pagination)) value="{{$pagination}}" @else value="10" @endif>
            <input type="hidden" name="sort" id="sort" @if(isset($sort)) value="{{$sort}}" @endif>

            <input type="hidden" name="target_all" id="target_all">

            <input type="hidden" name="pdf" id="pdf">

            <input type="hidden" name="csv" id="csv">

          
    </ul>

</nav>
</form>
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

    console.log(selected_taxonomies);

    for (alt_key = 0; alt_key < taxonomy_tree.length; alt_key++) {
        var alt_data = {};
        var alt_tree = taxonomy_tree[alt_key];
        alt_data.text = alt_tree.alt_taxonomy_name + ' (' + alt_tree.service_count + ')';
        alt_data.state = {};
        // alt_data.state.opened = true;
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
                    parent_data.id = alt_data.id + '_child_' + parent_tree.parent_taxonomy.taxonomy_id;
                    parent_data.state = {};
                    if (selected_taxonomies.indexOf(parent_data.id) > -1) {
                        parent_data.state.selected = true;
                        // parent_data.state.opened = true;
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
                        child_data.id = parent_data.id + '_child_' + child_tree.taxonomy_id;

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

     $('#sidebar_tree').jstree({
        'plugins': ["checkbox", "wholerow"],
        'core': {
            select_node: 'sidebar_taxonomy_tree',
            data: tree_data_list
        }
    });

    // $('#sidebar_tree').jstree({
    //     'plugins': ["checkbox", "wholerow"],
    //     'core': {
    //         select_node: 'sidebar_taxonomy_tree',
    //         data: [
    //             {
    //                 text: 'Alt1',
    //                 state: {
    //                     opened: true, //'opened' takes effect after refresh
    //                 },
    //                 children: [
    //                     {
    //                         text: 'parent1',
    //                         id: 'alt_1',
    //                         state: {
    //                           selected: true, 
    //                         }
    //                     }, 
    //                     {
    //                         text: 'parent2',
    //                         id: 'parent2',
    //                         state: {
    //                           selected: true ,
    //                           opened: true,
    //                         },
    //                         children : [
    //                             {
    //                                 text: 'child1',
    //                                 id: 'child1',
    //                                 state: {
    //                                   selected: true, 
    //                                 }
    //                             }, 
    //                             {
    //                                 text: 'child2',
    //                                 id: 'child2',
    //                                 state: {
    //                                   selected: true 
    //                                 }
    //                             }
    //                         ]
    //                     }
    //                 ]
    //             },
    //             {
    //                 text: 'Alt2',
    //                 state: {
    //                     opened: true, //'opened' takes effect after refresh
    //                 },
    //                 children: [
    //                     {
    //                         text: 'parent1',
    //                         id: 'alt_1',
    //                         state: {
    //                           selected: true, 
    //                         }
    //                     }, 
    //                     {
    //                         text: 'parent2',
    //                         id: 'alt_2',
    //                         state: {
    //                           selected: true 
    //                         }
    //                     }
    //                 ]
    //             },
    //         ]
    //     }
    // });



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
    $('#download_csv').on('click', function(){
        $("#csv").val('csv');
        $("#filter").submit();
        $("#csv").val('');
    });
    $('#download_pdf').on('click', function(){
        $("#pdf").val('pdf');
        $("#filter").submit();
        $("#pdf").val('');
    });
    $('#target_multiple').on('change', function(){

        $("#filter").submit();
    });

    // $('.regular-checkbox').each(function(){
    //     if($(this).prop('checked') && $('li', $(this).next().next()).length != 0){
            
    //         if($('.indicator', $(this).parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
    //             $('.indicator', $(this).parent().parent().parent()).eq(0).trigger('click');
    //         if(!$('.regular-checkbox', $(this).parent().parent().parent()).eq(0).prop('checked'))
    //             $('.regular-checkbox', $(this).parent().parent().parent()).eq(0).addClass('minus-checkbox');
    //     }
    //     if($(this).prop('checked') && $(this).parent().hasClass('nobranch') ){
            
    //         if($('.indicator', $(this).parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
    //             $('.indicator', $(this).parent().parent().parent()).eq(0).trigger('click');
    //         if($('.indicator', $(this).parent().parent().parent().parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
    //             $('.indicator', $(this).parent().parent().parent().parent().parent().parent()).eq(0).trigger('click');
    //         if(!$('.regular-checkbox', $(this).parent().parent().parent()).eq(1).prop('checked'))
    //             $('.regular-checkbox', $(this).parent().parent().parent()).eq(1).addClass('minus-checkbox');
    //         if(!$('.regular-checkbox', $(this).parent().parent().parent().parent().parent().parent()).eq(1).prop('checked'))
    //             $('.regular-checkbox', $(this).parent().parent().parent().parent().parent().parent()).eq(0).addClass('minus-checkbox');
    //     }
    // });
    // $('.branch').each(function(){
    //         if($('ul li', $(this)).length == 0)
    //             $(this).hide();
    //     }); 
    // if($('input[checked]', $('#projectcategory')).length > 0){
    //     $('#projectcategory').prev().trigger('click');
    // }
    // $('.indicator').click(function(){
    //     $('.branch').each(function(){
    //         if($('ul li', $(this)).length == 0)
    //             $(this).hide();
    //     });    
    // });

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

    $('#sidebar_tree').on("select_node.jstree", function (e, data) {
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('_child_') > -1;
        });
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