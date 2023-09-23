@extends('backLayout.app')
@section('title')
Taxonomy
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
    .dropdown-menu.open {
            max-height: 156px;
            overflow: hidden;
            width: 100%;
        }
    .dropdown-menu.open .selected .active {
            background-color: #da4a4a !important;
        }
        .bootstrap-select .dropdown-toggle .filter-option {
            position: relative;
            top: 0;
            left: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            text-align: left;
        }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Service Taxonomy</h2>
        <div class="nav navbar-right panel_toolbox">
            <a href="javascript:void(0)" id="export_csv" class="btn btn-info">Download CSV</a>
            <a href="{{route('tb_taxonomy.create')}}" class="btn btn-success">Add Service Term</a>
        </div>
        <div class="clearfix"></div>
      </div>
      {!! Form::open(['route' => 'tb_taxonomy.save_vocabulary']) !!}
        {{-- <div class="form-group" style="float:left;width:100%;">
            <label for="inputPassword3" class="col-sm-3 control-label">Exclude vocabularies from front-end Service taxonomy options.</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="exclude_vocabulary" name="exclude_vocabulary" value="{{ $layout->exclude_vocabulary }}">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div> --}}
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-3 control-label">Select Taxonomies</label>
            <div class="col-sm-7">
                <select name="taxonomy_select" id="taxonomy_select" class="form-control">
                    <option value="">None</option>
                    @foreach ($taxonomieTypes as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
                {{-- <button type="submit" class="btn btn-success" style="margin-top:10px;">Search</button> --}}
            </div>
            {{-- <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Save</button>
            </div> --}}
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-3 control-label">Parent</label>
            <div class="col-sm-7">
                <select name="parent_filter" id="parent_filter" class="form-control selectpicker" multiple>
                    <option value="none">None</option>
                    <option value="all">All</option>
                    @foreach ($taxonomieParents as $key=>$value)
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {!! Form::close() !!}
      <div class="x_content" >
        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display table-striped  jambo_table table-bordered table-responsive" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center">Id</th>
                    {{-- @if($source_data->active == 0 ) --}}
                    {{-- <th class="text-center">ID</th> --}}
                    {{-- @endif --}}
                    <th class="text-center">Term</th>
                    <th class="text-center">Parent Term</th>
                    {{-- @if($source_data->active == 0 )
                    <th class="text-center">Grandparent name</th>
                    @endif --}}
                    <th class="text-center">Taxonomy</th>
                    <th class="text-center">Language</th>
                    <th class="text-center">Created</th>
                    <th class="text-center">Created By</th>
                    <th class="text-center">Status</th>
                    {{-- @if($source_data->active == 1 || $source_data->active == 3 )
                    <th class="text-center">Description</th>
                    <th class="text-center">x-Taxonomies</th>
                    <th class="text-center">X-note</th>
                    @endif --}}

                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              {{-- @foreach($taxonomies as $key => $taxonomy)
                <tr id="taxonomy{{$taxonomy->id}}" class="{{$taxonomy->flag}}">
                  @if($source_data->active == 1 || $source_data->active == 3 )
                  <td class="text-center">{{$key}}+1</td>
                  @elseif($source_data->active == 0)
                  <td class="text-center">{{$taxonomy->taxonomy_recordid}}</td>
                  <td class="text-center">{{$taxonomy->taxonomy_id}}</td>
                  @endif
                  <td class="text-center">{{ $taxonomy->order }}</td>
                  <td>{{$taxonomy->taxonomy_name}}</td>

                  <td>
                      @php
                        $taxonomy_parent_name = $taxonomy->taxonomy_parent_name ? explode('');
                        $getTaxonomy = \App\Model\Taxonomy::where('taxo')
                      @endphp
                    <span class="badge bg-blue">{{$taxonomy->parent->taxonomy_name ?? ''}}</span>
                  </td>

                  @if($source_data->active == 0 )
                  <td class="text-center">{{$taxonomy->taxonomy_grandparent_name}}</td>
                  @endif
                  <td class="text-center">{{$taxonomy->taxonomy_type && count($taxonomy->taxonomy_type) > 0 ? $taxonomy->taxonomy_type[0]->name : ''}}</td>
                  <td >{{ $taxonomy->language }}
                      <input type="text" class="form-control" id="language_{{ $taxonomy->id }}" value="{{ $taxonomy->language }}"><button class="btn btn-sm btn-primary" onclick="saveLanguage({{ $taxonomy->id }})">save</button>
                    </td>
                    <td>{{ $taxonomy->created_at }}</td>
                    <td>{{ $taxonomy->user->first_name ?? '' }}</td>
                    <td>{{ $taxonomy->status }}</td>
                  @if($source_data->active == 1 || $source_data->active == 3 )
                  <td class="text-center">{{$taxonomy->taxonomy_x_description}}</td>
                  <td class="text-center">{{$taxonomy->additional_taxonomy_type && count($taxonomy->additional_taxonomy_type) > 0 ? $taxonomy->additional_taxonomy_type[0]->name : ''}}</td>
                  <td class="text-center">{{$taxonomy->taxonomy_x_notes}}</td>
                  @endif

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$taxonomy->taxonomy_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                    {!! Form::open(['method'=>'DELETE', 'route' => ['tb_taxonomy.destroy', $taxonomy->id], 'style' =>
                    'display:inline']) !!}
                        {{Form::button('<i class="fa fa-trash" "></i> Delete', array('type' => 'submit', 'data-placement' => 'top', 'data-original-title' => 'Delete', 'id'=>'delete-confirm','class' => 'btn btn-danger'))}}
                    {!! Form::close() !!}

                  </td>
                </tr>
              @endforeach --}}
            </tbody>
        </table>
       <!--  -->
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
<input id="url" type="hidden" value="{{ \Request::url() }}">
<input id="taxonomyAllParents" type="hidden" value="{{ $taxonomyAllParents }}">

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Taxonomy</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts" action="{{route('tb_taxonomy.taxonommyUpdate')}}" method="post" enctype="multipart/form-data"  novalidate="" style="margin-bottom: 0;">
              @csrf
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Term</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="taxonomy_name" name="taxonomy_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Taxonomy</label>

                      <div class="col-sm-7">
                        {{-- <input type="text" class="form-control" id="taxonomy_vocabulary" name="taxonomy_vocabulary" value=""> --}}
                        {!! Form::select('taxonomy',$taxonomieTypes,null,['class' => 'form-control','id' =>'taxonomy','placeholder' => 'Select taxonomy','disabled' => true]) !!}
                      </div>
                    </div>
                    <div class="form-group" id="parentDiv">
                        <label for="inputPassword3" class="col-sm-3 control-label">Parent</label>
                        <div class="col-sm-7">
                              {!! Form::select('taxonomy_parent_name',$taxonomy_parent_name,null,['class' => 'form-control','id' => 'taxonomy_parent_name','placeholder' => 'select parent','data-live-search' => 'true']) !!}

                        </div>
                      </div>
                    <div class="form-group {{ $errors->has('x_taxonomies') ? 'has-error' : ''}}">
                        {!! Form::label('x_taxonomies', 'x-Taxonomies', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-7">
                            {!! Form::select('x_taxonomies',$taxonomieTypesExternal,null,['class' => 'form-control','id' =>'x_taxonomies','placeholder' => 'Select x_taxonomies']) !!}
                            {{-- {!! Form::select('taxonomy',['Service Eligibility' => 'Service Eligibility','Service Category' => 'Service Category'],null,['class' => 'form-control','id' =>'taxonomy','placeholder' => 'Select Type']) !!} --}}
                            {!! $errors->first('x_taxonomies', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="email">Alt Taxonomy
                        </label>
                        <div class="col-sm-7">
                            <select class="form-control" name="taxonomy_grandparent_name" id="taxonomy_grandparent_name">
                              <option>Choose option</option>
                              @foreach($alt_taxonomies as $alt_taxonomy)
                              <option value="{{$alt_taxonomy->alt_taxonomy_name}}">{{$alt_taxonomy->alt_taxonomy_name}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Language</label>

                        <div class="col-sm-7">
                          {{-- <input type="text" class="form-control" id="language" name="language" value=""> --}}
                          {!! Form::select('language',$languages,null,['class' => 'form-control','id' =>'language','placeholder' => 'Select language']) !!}
                        </div>
                      </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <textarea type="text" class="form-control" id="taxonomy_x_description" name="taxonomy_x_description" value="" rows="2"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">X-notes</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="taxonomy_x_notes" name="taxonomy_x_notes" value="">
                      </div>
                    </div>
                    <div class="form-group" id="orderDiv">
                      <label for="inputPassword3" class="col-sm-3 control-label">Order</label>

                      <div class="col-sm-7">
                        <input type="number" class="form-control" id="order" name="order" value="" min="1">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Term Icon</label>
                      <div class="col-sm-7">
                        <input type="file" class="form-control" id="category_logo" name="category_logo">
                        <p>Recommended image size is 60px x 60px</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">&nbsp;</label>
                      <div class="col-sm-7">
                        <img src="" id="category_logo_image" width="100px">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Hover Term Icon</label>
                      <div class="col-sm-7">
                        <input type="file" class="form-control" id="category_logo_white" name="category_logo_white">
                        <p>Recommended image size is 60px x 60px</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">&nbsp;</label>
                      <div class="col-sm-7">
                        <img src="" width="100px" id="white_logo_image" style="background: #c1c1c1;padding: 10px;border-radius: 12px;">
                      </div>
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        <label for="inputPassword3" class="col-sm-3 control-label">Badge Color</label>
                        <div class="col-sm-3">
                            <input type="color" name="badge_color" id="badge_color" class="color-pick form-control" style="padding:0px;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        <label for="inputPassword3" class="col-sm-3 control-label">Created</label>
                        <div class="col-sm-3">
                            <input type="text" name="created_at" id="created_at" class="form-control" style="padding:0px;" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        <label for="inputPassword3" class="col-sm-3 control-label">Created By</label>
                        <div class="col-sm-3">
                            <input type="text" name="created_by" id="created_by" class="form-control" style="padding:0px;" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        <label for="inputPassword3" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-3">
                            {!! Form::select('status',['Published'=>'Published','Unpublished' => 'Unpublished'],null,['class' => 'form-control','id'=>'status','readonly' => (Auth::user() && Auth::user()->roles &&Auth::user()->roles->id != 1) ]) !!}
                        </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="id" value="0">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<input type="hidden" id="singleTaxonomy" name="singleTaxonomy" value="{{ $taxonomies && count($taxonomies) ? $taxonomies[0] : '' }}">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
let table
let extraData = {}
$("#taxonomy_select").selectpicker();
$("#parent_filter").selectpicker();
$(document).ready(function() {
    let ajaxUrl = "{{ route('tb_taxonomy.getAllTaxonomy') }}"
    table = $('#example').DataTable( {
        ajax: {
                url: ajaxUrl,
                method : "post",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data : function (d){
                        if (typeof extraData !== 'undefined') {
                            // $('#loading').show();
                            d.extraData = extraData;
                        }
                    },
                },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'taxonomy_name', name: 'taxonomy_name' },
                { data: 'taxonomy_parent_name', name: 'taxonomy_parent_name' },
                { data: 'taxonomy', name: 'taxonomy' },
                { data: 'language', name: 'language' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by' },
                { data: 'status', name: 'status' },
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
                {
                    "targets": 6,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 7,
                    "orderable": true,
                    "class": "text-left"
                },
                {
                    "targets": 8,
                    "orderable": true,
                    "class": "text-left"
                },
            ],
    } );
} );
$('#taxonomy_select').change(function () {
    let value = $(this).val()
    extraData.taxonomy_select = value
    // table.column(3).search(value).draw();
    table.ajax.reload();
})
$('#parent_filter').change(function () {
    let value = $(this).val()
    extraData.parent_filter = value
    let taxonomyAllParents = JSON.parse($('#taxonomyAllParents').val());
    let search
    if(value){
        if(value.includes("all")){
            search = taxonomyAllParents.join('|');
        }else if(value.includes("none")){
            search = '^$';
        }else{
            search = value.join('|');
        }
    }else{
        search = '';
    }
    table.ajax.reload();
    // table.column(2).search(search, true, false).draw();
})
let id = "{{ isset($id) ? $id : '' }}"

if(id){
    extraData.id = id
    let data = JSON.parse($('#singleTaxonomy').val())

    if(data){
        $('#parentDiv').show()
        $('#orderDiv').show()
    }else{
        $('#parentDiv').hide()
        $('#orderDiv').hide()
    }
    $('#id').val(data.taxonomy_recordid);
    $('#taxonomy_name').val(data.taxonomy_name);
    $('#taxonomy').val(data.taxonomy);
    $('#x_taxonomies').val(data.x_taxonomies);
    $('#taxonomy_x_description').val(data.taxonomy_x_description);
    $('#taxonomy_grandparent_name').val(data.taxonomy_parent_name);
    $('#language').val(data.language);
    $('#order').val(data.order);
    $('#taxonomy_parent_name').val(data.taxonomy_parent_name);
    $('#taxonomy_x_notes').val(data.taxonomy_x_notes);
    $('#exclude_vocabulary').val(data.exclude_vocabulary);
    $('#badge_color').val(data.badge_color);
    $('#created_at').val(data.created_at);
    $('#created_by').val(data.user ? data.user.first_name : '' );
    $('#status').val(data.status);
    $('#white_logo_image').attr('src',data.category_logo_white)
    $('#category_logo_image').attr('src',data.category_logo)
    $("#status").selectpicker('refresh');
    $("#taxonomy").selectpicker('refresh');
    $("#x_taxonomies").selectpicker('refresh');
    $("#taxonomy_parent_name").selectpicker('refresh');
    $('#btn-save').val("update");
    $('#myModal').modal('show');
}
$('#export_csv').click(function () {
    _token = '{{ csrf_token() }}'
    $.ajax({
        url:"{{ route('tb_taxonomy.taxonomy_export_csv') }}",
        method : 'POST',
        data:{extraData},
        success:function(response){
            // const url = window.URL.createObjectURL(new Blob([response]));
            const a = document.createElement('a');
                    a.href = response.path;
                    a.download = 'taxonomy_terms.csv';
                    document.body.appendChild(a);
                    a.click();
        },
        error : function(error){
            console.log(error)
        }
    })
})
$("#taxonomy_parent_name").selectpicker();
    $(document).ready(function(){
        $('#taxonomy').change(function(){
            let taxonomyTypeId = $(this).val()
            $.ajax({
                url: "{{ route('tb_taxonomy.getParentTerm') }}",
                method : 'get',
                data:{taxonomyTypeId},
                success : function(response){
                    let data = response.data
                    $('#taxonomy_parent_name').empty()
                    $('#taxonomy_parent_name').append('<option value="">Select Parent</option>');
                    $.each(data,function(i,v){
                        $('#taxonomy_parent_name').append('<option value="'+i+'" style="background-color: ' + (v.includes('---') ? '#c961d6;' : (v.includes('--') ? '#a59524;' : (v.includes('-') ? '#4ada70;' : '#059ff9;'))) + ' color:#fff;">'+v+'</option>');
                    })
                    $('#taxonomy_parent_name').val('')
                    $('#taxonomy_parent_name').selectpicker('refresh')
                },
                error : function(error){
                    console.log(error)
                }
            })
        })
    })
</script>
<script src="{{asset('js/taxonomy_ajaxscript.js')}}"></script>

@endsection
