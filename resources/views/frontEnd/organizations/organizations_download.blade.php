
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- Example Striped Rows -->

        <div class="container-fluid p-0" style="margin-right: 0">
            <h3>{{$layout->header_pdf}}</h3>
            <div class="col-md-8 pt-15 pr-0">

                @foreach($organizations as $organization)

                <div class="panel content-panel">
                    <div class="panel-body p-20">

                        <h3><span class="badge bg-red">Organization:</span> <a class="panel-link" href=" {{ url('/organizations/'.$organization->organization_recordid) }}"> {{$organization->organization_name}} {{ $organization->organization_alternate_name ? '('.$organization->organization_alternate_name.')' : '' }}</a></h3>

                        <p><span class="badge bg-red">Status:</span>
                            {{$organization->organization_status_x}}
                        </p>
                        <p><span class="badge bg-red">Discription:</span>
                            {!! nl2br($organization->organization_description) !!}
                        </p>
                        <p><span class="badge bg-red">URL:</span>
                            {{ $organization->organization_url }}
                        </p>
                        @if($organization->phones)
                        <p><span class="badge bg-red">Phone:</span>
                            <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                @foreach($organization->phones as $phone)
                                @if ($phone->phone_number)
                                {{$phone->phone_number}}
                                @endif
                                @endforeach
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
            <h3>{{$layout->footer_pdf}}</h3>
        </div>
    </div>
</div>


