<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<style>
    .pac-logo:after{
      display: none;
    }
    ul, #myUL {
      list-style-type: none;
    }
    #tree2{
        padding-left: 30px;
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
            <li class="option-side sidebar-menu">
                <form action="/find" method="POST" class="mb-5">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address">
                    </div>
                </form>
            </li>

            <li class="option-side sidebar-menu">
                <!--begin::Form-->
                <form method="post" action="/search_address" class="mb-5" id="search_location">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-search">
                        <i class="input-search-icon md-pin" aria-hidden="true"></i>
                        <input id="location" type="text" class="form-control search-form" name="search_address" placeholder="Search Address">
                    </div>
                </form>
            </li>

            <li class="option-side sidebar-menu">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services_near_me" style="display: block;padding-left: 10px;">Services Near Me</a></button>
            </li> 
                    
            <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="false">Category</a>
                <ul class="collapse list-unstyled option-ul" id="projectcategory">
                    <ul id="tree2">
                        @foreach($taxonomies as $taxonomy)
                            @if($taxonomy->taxonomy_name)
                            <li class="nobranch">
                               
                                  <input type="checkbox" name="parents[]" value="{{$taxonomy->taxonomy_recordid}}"  class="regular-checkbox" />
                                  <span class="inputChecked">{{$taxonomy->taxonomy_name}}</span>
                               
                                @if(count($taxonomy->childs))
                                    @include('layouts.manageChild1',['childs' => $taxonomy->childs])
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </ul>
            </li>
            <li class="option-side">
                <a href="#cityagency" class="text-side" data-toggle="collapse" aria-expanded="false">Organization</a>
                <ul class="collapse list-unstyled option-ul" id="cityagency">
                    @foreach($organizations as $organization)
                    <li class="nobranch">
                        <input type="checkbox" name="organizations[]" value="{{$organization->organization_recordid}}"  class="regular-checkbox" />
                        <span class="inputChecked">{{$organization->organization_name}}</span>
                    </li>   
                    @endforeach
                </ul>
            </li>
        </ul>

</nav>
@if(isset($location) == TRUE)
    <input type="hidden" name="location" id="location" value="{{$location}}">
@else
    <input type="hidden" name="location" id="location" value="">
@endif
<script src="{{asset('js/treeview2.js')}}"></script>


