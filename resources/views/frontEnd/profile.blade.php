@extends('layouts.app')
@section('title')
Profile
@stop
<meta name="description" content="{{$project->project_title}}">
<style>
   .navbar-container.container-fluid{
        display: none !important;
    }
    @media (max-width: 991px){
        .page {
            padding-top: 0px !important;
        }
    }
    #map{
      position: fixed !important;

    }
    .profile-location{
      display: none;
    }
    @media (max-width: 768px) {
      #map{
          width: 100% !important;
          margin: 0 !important;
          position: relative !important;
          display: block !important;
          z-index: 1 !important;
        }    
    }
</style>
@section('content')
<div class="row container-fluid m-0 p-0">
  <div class="col-md-4">
  
    @if($project->project_status_category=='Complete')
        <h4 class="m-0 pt-30 pb-30"><button type="button" class="btn btn-floating btn-success btn-xs waves-effect waves-classic mr-10"><i class="icon fa-check mr-0" aria-hidden="true"></i></button>{{$project->project_title}}</h4>
    @elseif($project->project_status_category=='Project Status Needed')
        <h4 class="m-0 pt-30 pb-30"><button type="button" class="btn btn-floating  btn-xs waves-effect waves-classic mr-10"></button>{{$project->project_title}}</h4>
    @elseif($project->project_status_category=='Not funded')
        <h4 class="m-0 pt-30 pb-30"><button type="button" class="btn btn-floating btn-danger btn-xs waves-effect waves-classic mr-10"><i class="icon fa-remove mr-0" aria-hidden="true"></i></button>{{$project->project_title}}</h4>
    @else
        <h4 class="m-0 pt-30 pb-30"><button type="button" class="btn btn-floating btn-warning btn-xs waves-effect waves-classic mr-10"><i class="icon fa-minus mr-0" aria-hidden="true"></i></button>{{$project->project_title}}</h4>
    @endif
    <div class="panel mb-15">
      <div class="panel-body p-15">
        <h4><b>{{$project->project_status}}</b></h4>
        @if($project->status_date_update!=null)
        <p>Last updated {{$project->status_date_updated}}</p>
        @endif
      </div>
    </div>

    <div class="panel mb-15">
      <div class="panel-body p-15">
        <h4><b>BALLOT INFORMATION</b></h4>
        <h5 class="profile-title">Project appeared on {{$project->district->name}} PBNYC ballot in {{$project->vote_year}} with a cost of ${{number_format($project->cost_text)}}. {{$project->win_text}} with {{$project->votes}} votes</h5>
      </div>
    </div>

    @if($project->project_status_category != 'Not funded' && $project->project_status_category != 'Project Status Needed')
      @if($project->project_budget_type=='Capital')
      <div class="panel mb-0">
        <div class="panel-body p-15">
          <h5 class="profile-title">This is a <b>Capital Project</b> so it will likely take between 3-7 years from funding to completion.</h5>
        </div>
      </div>
      @endif
      @if($project->project_budget_type=='Expense')
      <div class="panel mb-0">
        <div class="panel-body p-15">
          <h5 class="profile-title">This is an <b>Expense Project</b> so it will likely be completed within 1-2 years.</h5>
        </div>
      </div>
      @endif
    @endif
  </div>

  <div class="col-md-5 pt-15">
    <!-- Panel -->      
    @if($project->project_status_category != 'Not funded' && $project->project_status_category != 'Project Status Needed')
    <div class="panel mb-15">
      <div class="panel-body p-15">
          <h4><b>IMPLEMENTATION PLAN</b></h4>
          <h5 class="profile-title">${{$project->cost_appropriated}} by NYCC in {{$project->fiscal_year}}, with implementation by {{$project->name_dept_agency_cbo}}.</h5>
      </div>
    </div>
    @endif
    <div class="panel mb-15" style="background: #3f8a7b;">
      <div class="panel-body text-center p-15">
        <h5 class="feedback-title">Not finding what you're looking for?</h5>
        <button class="btn btn-block btn-profile waves-effect waves-classic waves-effect waves-classic"><a href="mailto:{{$contact->email}}" target="_top"  class="ui-link">Email Your Council Member</a></button>
      </div>
    </div>
    <div class="panel mb-0">
      <div class="panel-body p-15">
          <h4><b>PROJECT DETAILS</b></h4>
          <h5 class="profile-title"><b>{{$project->category_type_topic_standardize}}</b></h5>
          <h5 class="profile-title">{{$project->project_description}}</h5>
      </div>
    </div>
    <!-- End Panel -->
  </div>
  <div class="col-md-3 p-15">
    <!-- Panel -->
    <div class="panel">
      <div id="map" class="mr-5 mt-0" style="width: 25%;"></div>
      <div class="panel-body profile-location">
          <h4>{{$project->project_address_clean}}</h4>
          <h4>{{$project->location_city}}, {{$project->state}}, {{$project->zipcode}}</h4>
      </div>
    </div>
  </div>
</div>

 <script type="text/javascript">

    var locations = <?php print_r(json_encode($project)) ?>;

    var mymap = new GMaps({
      el: '#map',
      lat: locations.latitude,
      lng: locations.longitude,
      zoom:13
    });

     mymap.addMarker({
      lat: locations.latitude,
      lng: locations.longitude,
      infoWindow: {
          maxWidth: 250,
          content: ('<span style="color:#424242;font-weight:500;font-size:14px;">'+locations.project_address_clean+', '+locations.location_city+', '+locations.state+', '+locations.zipcode+'</span>')
      }
    });

  </script>
@endsection