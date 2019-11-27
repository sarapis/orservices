<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
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
    @media (max-width: 768px) {
        .mobile-btn{
            display: block;
        }
        .btn-feature{
            display: none;
        }
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
                        <select class="js-example-basic-multiple form-control" multiple data-plugin="select2" id="target_multiple" name="target_populations[]">
                           @foreach($target_taxonomies as $child)
                              
                                    <option value="{{$child->taxonomy_recordid}}" @if((isset($target_populations) && in_array($child->taxonomy_recordid, $target_populations))) selected @endif>{{$child->taxonomy_name}}</option>
                               
                            @endforeach
                           
                            
                        </select>
                    </li>
                </ul>
            </li>
            <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="true">Types of Services</a>
                 
                <ul class="collapse list-unstyled option-ul show" id="projectcategory">
                    @foreach($grandparent_taxonomies as $key => $grandparent_taxonomy) 
                    <ul class="tree2">
                        <li class="altbranch">
                            <input type="checkbox" id="category_{{str_replace(' ', '_', $grandparent_taxonomy)}}" class="regular-checkbox" name="grandparents[]" value="{{$grandparent_taxonomy}}" @if(  isset($grandparent_taxonomy_names) && in_array($grandparent_taxonomy, $grandparent_taxonomy_names)) checked @endif> <span class="inputChecked">{{$grandparent_taxonomy}}</span>
                            <ul class="tree2">
                               
                                    @foreach($parent_taxonomies as $parent_taxonomy)
                                        @php $flag = 'false'; @endphp
                                        @foreach($taxonomies->sortBy('taxonomy_name') as $key => $child)
                                            @if($parent_taxonomy == $child->taxonomy_parent_name && $grandparent_taxonomy == $child->taxonomy_grandparent_name)
                                             @if($flag == 'false')                               
                                                <li>
                                                        <input type="checkbox" class="regular-checkbox" name="checked_grandparents[]" value="{{$grandparent_taxonomy}}" @if( isset($parent_taxonomy_names) && in_array($parent_taxonomy, $parent_taxonomy_names) && isset($checked_grandparents) && in_array($grandparent_taxonomy, $checked_grandparents)) checked @endif style="display: none;" id="checked_{{str_replace(' ', '_', $grandparent_taxonomy)}}_{{str_replace(' ', '_', $parent_taxonomy)}}">

                                                        <input type="checkbox" class="regular-checkbox" name="parents[]" value="{{$parent_taxonomy}}" @if( isset($parent_taxonomy_names) && in_array($parent_taxonomy, $parent_taxonomy_names) && isset($checked_grandparents) && in_array($grandparent_taxonomy, $checked_grandparents)) checked @endif id="category_{{str_replace(' ', '_', $grandparent_taxonomy)}}_{{str_replace(' ', '_', $parent_taxonomy)}}">
                                                        <span class="inputChecked">{{$parent_taxonomy}}</span>
                                                    
                                                    <ul class="child-ul">
                                                    @php $flag = 'true'; @endphp
                                                    @endif
                                                        @if($grandparent_taxonomy == $child->taxonomy_grandparent_name && $parent_taxonomy == $child->taxonomy_parent_name)
                                                        <li class="nobranch">
                                                              <input type="checkbox" id="category_{{$child->taxonomy_recordid}}" name="childs[]" value="{{$child->taxonomy_recordid}}"  class="regular-checkbox" @if( isset($parent_taxonomy_names) && in_array($child->taxonomy_parent_name, $parent_taxonomy_names) && in_array($child->taxonomy_recordid, $child_taxonomy)) checked @endif/> <span class="inputChecked">{{$child->taxonomy_name}}</span>
                                                        </li>
                                                        @endif
                                                     
                                                @endif
                                        @endforeach
                                            @if ($flag == 'true')
                                                </ul>

                                            </li>
                                             @endif   
                                    @endforeach
                                
                            </ul>
                        </li>
                    </ul>
                    @endforeach
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
<script>
$(document).ready(function(){
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

    $('.regular-checkbox').each(function(){
        if($(this).prop('checked') && $('li', $(this).next().next()).length != 0 && $(this).parent().parent().parent().attr('id') != 'projectcategory'){
            if($('.indicator', $(this).parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
                $('.indicator', $(this).parent().parent().parent()).eq(0).trigger('click');
            if(!$('.regular-checkbox', $(this).parent().parent().parent()).eq(0).prop('checked'))
                $('.regular-checkbox', $(this).parent().parent().parent()).eq(0).addClass('minus-checkbox');
        }
        if($(this).prop('checked') && $(this).parent().hasClass('nobranch') ){
            if($('.indicator', $(this).parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
                $('.indicator', $(this).parent().parent().parent()).eq(0).trigger('click');
            if($('.indicator', $(this).parent().parent().parent().parent().parent().parent()).eq(0).hasClass('glyphicon-triangle-right'))
                $('.indicator', $(this).parent().parent().parent().parent().parent().parent()).eq(0).trigger('click');
            if(!$('.regular-checkbox', $(this).parent().parent().parent()).eq(1).prop('checked'))
                $('.regular-checkbox', $(this).parent().parent().parent()).eq(1).addClass('minus-checkbox');
            if(!$('.regular-checkbox', $(this).parent().parent().parent().parent().parent().parent()).eq(1).prop('checked'))
                $('.regular-checkbox', $(this).parent().parent().parent().parent().parent().parent()).eq(0).addClass('minus-checkbox');
        }
    });
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
            matcher: matchCustom
        });
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