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
	<nav id="sidebar" style="display: none;">
	   	<ul class="list-unstyled components pt-0">
	        <input type="hidden" name="paginate" id="paginate" @if(isset($pagination)) value="{{$pagination}}" @else value="10" @endif>
	        <input type="hidden" name="sort" id="sort" @if(isset($sort)) value="{{$sort}}" @endif>
	        <input type="hidden" name="organization_tags" id="organization_tags" @if(isset($organization_tags)) value="{{($organization_tags)}}" @endif>
		</ul>
    </nav>
            <input type="hidden" id="selected_taxonomies" name="selected_taxonomies">
</form>
<script src="{{asset('js/treeview2.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>

<script>
$(document).ready(function(){

    $('.drop-sort').on('click', function(){
        $("#sort").val($(this).text());
        $("#filter_organization").submit();
    });

});

</script>
