<ul class="child-ul">
@foreach($childs as $child)
	<li class="nobranch">
          <input type="checkbox" name="childs[]" value="$taxonomy->taxonomy_recordid"  class="regular-checkbox"/> <span class="inputChecked">{{$taxonomy->taxonomy_name}}</span>
	    {{ $child->taxonomy_name }}
		@if(count($child->childs))
            @include('layouts.manageChild1',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>