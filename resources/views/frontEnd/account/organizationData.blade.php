@foreach ($organization_list as $organization)
    <div class="card">
        <div class="card-block">
            <h4 class="card-title">
                <a href="/organizations/{{ $organization->organization_recordid }}"
                    class="notranslate title_org">{{ $organization->organization_name }}</a>
            </h4>
            <p class="card-text" style="font-weight:400;">{!! Str::limit($organization->organization_description, 200) !!}</p>
            <h4><span>Number of Services:@php
                if (count($organization->services) == 0) {
                    $organization_services = $organization->getServices->count();
                }
            @endphp
                    @if (isset($organization->services) && count($organization->services) != 0)
                        {{ $organization->services->count() }}
                    @elseif(count($organization->services) == 0)
                        {{ $organization->getServices->count() }}
                    @else
                        0
                    @endif
                </span>
                <a href="/organizations/{{ $organization->organization_recordid }}">
                    <img src="/frontend/assets/images/arrow_right.png" alt="" title=""
                        class="float-right">
                </a>
            </h4>
            <h4> <span> Last updated : </span> {{ $organization->updated_at }}</h4>
        </div>
    </div>
@endforeach
{{ $organization_list->links() }}
