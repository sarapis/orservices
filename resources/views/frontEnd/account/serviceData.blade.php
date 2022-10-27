@foreach ($service_list as $service)
    <div class="card">
        <div class="card-block">
            <h4 class="card-title">
                <a href="/services/{{ $service->service_recordid }}"
                    class="notranslate title_org">{{ $service->service_name }}</a>
            </h4>
            <p class="org_title"><span class="subtitle"><b>Organization:</b></span>
                @if(isset($service->organizations))
                <a class="panel-link" class="notranslate"
                    href="/organizations/{{$service->organizations()->first()->organization_recordid}}">
                    {{$service->organizations()->first()->organization_name}}</a>
                @endif
            </p>
            <p class="card-text" style="font-weight:400;">{!! Str::limit($service->service_description, 200) !!}</p>
                <a href="/services/{{ $service->service_recordid }}">
                    <img src="/frontend/assets/images/arrow_right.png" alt="" title=""
                        class="float-right">
                </a>
            </h4>
            <h4> <span> Last updated : </span> {{ $service->updated_at }}</h4>
        </div>
    </div>
@endforeach
{{ $service_list->links() }}
