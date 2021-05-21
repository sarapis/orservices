@extends('backLayout.app')
@section('title')
Show user {{$user->first_name}}
@stop

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>The user : {{$user->first_name}} Change Log</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row form-group">
                    <ul>
                        @forelse ($audits as $audit)
                        <li>
                            @lang('language.updated.metadata', $audit->getMetadata())

                            @foreach ($audit->getModified() as $attribute => $modified)
                            <ul>
                                {{-- <li>@lang('language.'.$audit->event.'.modified.'.$attribute, $modified)</li> --}}
                                <li>The {{ $attribute }} has been modified from <strong>{{ $modified['old'] }}</strong>
                                    to <strong>{{ $modified['new'] }}</strong></li>
                            </ul>
                            @endforeach
                        </li>
                        @empty
                        <p>@lang('language.unavailable_audits')</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
