<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- Example Striped Rows -->

        <div class="container-fluid p-0" style="margin-right: 0">
            <h3>{{ $layout->header_pdf }}</h3>
            <div class="col-md-8 pt-15 pr-0">

                @foreach ($services as $service)

                    <div class="panel content-panel">
                        <div class="panel-body p-20">

                            <h3><span class="badge bg-red">Service:</span> <a class="panel-link"
                                    href="{{ config('app.url') }}/services/{{ $service->service_recordid }}">
                                    {{ $service->service_name }}</a></h3>
                            <h4><span class="badge bg-red">Category:</span>
                                    @if ($service->service_taxonomy != 0)
                                        @foreach ($service->taxonomy as $key => $taxonomy)
                                            @if ($loop->last)
                                            <a class="panel-link"
                                                href="{{ config('app.url') }}/category/{{ $taxonomy->taxonomy_recordid }}">{{ $taxonomy->taxonomy_name }}</a>
                                        @else
                                            <a class="panel-link"
                                                href="{{ config('app.url') }}/category/{{ $taxonomy->taxonomy_recordid }}">{{ $taxonomy->taxonomy_name }}</a>,
                                        @endif
                                    @endforeach
                                @endif
                            </h4>
                            <h4><span class="badge bg-red">Organization:</span>
                                @if ($service->service_organization != 0)
                                    @if (isset($service->organizations))
                                        @foreach ($service->organizations as $organization)
                                            @if (isset($organization->organization_name))
                                                @if ($loop->last)
                                                    <a class="panel-link"
                                                        href="{{ config('app.url') }}/organizations/{{ $organization->organization_recordid }}">
                                                        {{ $organization->organization_name }}</a>
                                                @else
                                                    <a class="panel-link"
                                                        href="{{ config('app.url') }}/organizations/{{ $organization->organization_recordid }}">
                                                        {{ $organization->organization_name }}</a>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </h4>
                            <h4><span class="badge bg-red">Phone:</span>
                                @foreach ($service->phone as $phone) {!!
                                    $phone->phone_number !!} @endforeach
                            </h4>
                            <h4><span class="badge bg-blue">Address:</span>
                                @if ($service->service_address != null)
                                    @foreach ($service->address as $address)
                                        {{ $address->address_1 }}
                                    @endforeach
                                @endif
                            </h4>
                            <h4><span class="badge bg-blue">Description:</span> {!! $service->service_description !!}
                            </h4>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
            <h3>{{ $layout->footer_pdf }}</h3>
        </div>
    </div>
</div>
