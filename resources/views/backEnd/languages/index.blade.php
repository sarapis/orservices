@extends('backLayout.app')
@section('title')
Languages
@stop

@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Languages</h2>
        <div class="nav navbar-right panel_toolbox">
          <a href="{{route('languages.create')}}" class="btn btn-success">New language</a>

        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissable custom-success-box" style="margin: 15px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong> {{ session()->get('error') }} </strong>
        </div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong> {{ session()->get('success') }} </strong>
        </div>
        @endif
        <table class="table table-striped jambo_table bulk_action" id="tbllanguages">
          <thead>
            <tr>
              {{-- <th>Select All <input name="select_all" value="1" id="example-select-all" type="checkbox" /> --}}
              </th>
              <th>ID</th>
              <th>language name</th>
              <th>Order</th>
              <th>Service Id</th>
              <th>Location Id</th>
              {{-- <th>Created At</th> --}}
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {{-- @foreach($languages as $language)
            <tr>
              <td>{{$language->id}}</td>
              <td>{{$language->language}}</td>
              <td>{{$language->language_service}}</td>
              <td>{{$language->language_location}}</td>
              <td>{{$language->created_at}}</td>
              <td>
                <a href="{{route('languages.edit', $language->id)}}" class="btn btn-primary btn-xs"><i
                    class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                {!! Form::open(['method'=>'DELETE', 'route' => ['languages.destroy', $language->id],
                'style' =>
                'display:inline']) !!}
                {{Form::button('<i class="fa fa-trash"></i>', array('type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'onclick'=>'confirm(Are you sure to delete this language)'))}}
                {!! Form::close() !!}
              </td>
            </tr>
            @endforeach --}}
          </tbody>
        </table>
        {{-- @if (Sentinel::getUser()->hasAccess(['language.destroy']))
        <button id="delete_all" class='btn btn-danger btn-xs'>Delete Selected</button>
        @endif --}}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    let tbllanguages;
    let ajaxUrl = "{{ route('languages.index') }}";
  $(document).ready(function(){
    tbllanguages = $('#tbllanguages').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                method : "get",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'language', name: 'language' },
                { data: 'order', name: 'order' },
                { data: 'language_service', name: 'language_service' },
                { data: 'language_location', name: 'language_location' },
                { data: 'action', name: 'action' },
            ],
            columnDefs : [
                {
                    "targets": 0,
                    "orderable": false,
                    "class": "text-left",
                },
                {
                    "targets": 1,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 2,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 3,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 4,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 5,
                    "orderable": true,
                    "class": "text-left"
                },
            ],
        });
    });
      // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
  $("#delete-confirm").on("click", function(){
        return confirm("Are you sure to delete this language");
    });
  // start Delete All function
  $("#delete_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Delete Selected?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'delete'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
   });
  //End Delete All Function
  //Start Deactivate all Function
    $("#deactivate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Deactivate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'deactivate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any item before");}
        }
        return false;
    });
    //End Deactivate Function
      //Start Activate all Function
    $("#activate_all").click(function(event){
        event.preventDefault();
        if (confirm("Are you sure to Activate Selected ?")) {
            var value=get_Selected_id();
            if (value!='') {

                 $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('backend\UserController@ajax_all')}}",
                    data: {all_id:value,action:'activate'},
                        success: function(data) {
                          location.reload()
                        }
                    })

                }else{return confirm("You have to select any checkbox before");}
        }
        return false;
    });
    //End Activate Function




   function get_Selected_id() {
    var searchIDs = $("input[name=sel]:checked").map(function(){
      return $(this).val();
    }).get();
    return searchIDs;
   }
</script>
@endsection
