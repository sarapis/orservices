@if ($organization->program && count($organization->program) > 0)
    <div class="tagp_class">
        @foreach ($organization->program as $key => $program)
        {{-- {{ dd($organization->program) }} --}}
            <span class="pl-0 category_badge subtitle"><b>{{ $program->name . ($program->alternate_name ? '( '.$program->alternate_name.' )' : '')}}</b></span>

            <p>{!! nl2br($program->description) !!}</p>
            @if ($program->service()->count())
                Associated Services:
                <ul>
                    @foreach ($program->service as $service)
                    <li>
                        <a href="/services/{{ $service->service_recordid }}" target="_blank" >{{ $service->service_name }}</a>
                    </li>
                    @endforeach
                </ul>
            @endif
            @if (!$loop->last)
            <hr>
            @endif
        @endforeach
        {{-- @foreach ($organization_services as $service)
        {{ dd($service->program) }}
            @if ($service->program && count($service->program) > 0)
                @foreach ($service->program as $key => $program)
                <span class="pl-0 category_badge subtitle"><b>{{ $program->name . ($program->alternate_name ? '( '.$program->alternate_name.' )' : '')}}</b></span>
                <p>{!! nl2br($program->description) !!}</p>
                @endforeach
            @endif
        @endforeach --}}
    </div>
@endif


<style>
    .text_tooltips .help-tip {top: 0px}
</style>
