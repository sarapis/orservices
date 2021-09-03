@extends('backLayout.app')
@section('title')
Create service term
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<style>
    .dropdown-menu.open {
        max-height: 156px;
        overflow: hidden;
        width: 100%;
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
                <h2>Create service term</h2>
                {{-- @if (count($errors) > 0)
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="alert alert-danger">
                            <strong>Upsss !</strong> There is an error...<br /><br />
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif --}}
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{ Form::open(array('url' => route('tb_taxonomy.store'), 'class' => 'form-horizontal','files' => true)) }}
                    <div class="form-group {{ $errors->has('taxonomy_name') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_name', 'Term', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('taxonomy_name', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('taxonomy_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('taxonomy') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy', 'Taxonomy', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('taxonomy',$taxonomieTypes,null,['class' => 'form-control','id' =>'taxonomy','placeholder' => 'Select Type']) !!}
                            {{-- {!! Form::select('taxonomy',['Service Eligibility' => 'Service Eligibility','Service Category' => 'Service Category'],null,['class' => 'form-control','id' =>'taxonomy','placeholder' => 'Select Type']) !!} --}}
                            {!! $errors->first('taxonomy', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('taxonomy_parent_name') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_parent_name', 'Parent', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('taxonomy_parent_name',[],null,['class' => 'form-control','id' => 'taxonomy_parent_name','placeholder' => 'select parent','data-live-search' => 'true']) !!}
                            {!! $errors->first('taxonomy_parent_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('x_taxonomies') ? 'has-error' : ''}}">
                        {!! Form::label('x_taxonomies', 'x-Taxonomies', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('x_taxonomies',$taxonomieTypesExternal,null,['class' => 'form-control','id' =>'x_taxonomies','placeholder' => 'Select taxonomies']) !!}
                            {{-- {!! Form::select('x_taxonomies',['Service Eligibility' => 'Service Eligibility','Service Category' => 'Service Category'],null,['class' => 'form-control','id' =>'x_taxonomies','placeholder' => 'Select Type']) !!} --}}
                            {!! $errors->first('x_taxonomies', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('taxonomy_grandparent_name') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_grandparent_name', 'Alt Taxonomy', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            <select class="form-control" name="taxonomy_grandparent_name" id="taxonomy_grandparent_name">
                                <option value="">Choose option</option>
                                @foreach($alt_taxonomies as $alt_taxonomy)
                                <option value="{{$alt_taxonomy->alt_taxonomy_name}}">{{$alt_taxonomy->alt_taxonomy_name}}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('taxonomy_grandparent_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('language') ? 'has-error' : ''}}">
                        {!! Form::label('language', 'Language', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('language',$languages,null,['class' => 'form-control','id' =>'language','placeholder' => 'Select language']) !!}
                            {!! $errors->first('language', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('taxonomy_x_description') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_x_description', 'Description' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('taxonomy_x_description', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('taxonomy_x_description', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('taxonomy_x_notes') ? 'has-error' : ''}}">
                        {!! Form::label('taxonomy_x_notes', 'X-notes', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('taxonomy_x_notes', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('taxonomy_x_notes', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
                        {!! Form::label('order', 'Order', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::number('order', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Term Icon (Dark)</label>
                        <div class="col-sm-7">
                          <input type="file" class="form-control" id="category_logo" name="category_logo">
                          <p>Recommended image size is 60px x 60px</p>
                        </div>
                      </div>
                      {{-- <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-7">
                          <img src="" id="category_logo_image" width="100px">
                        </div>
                      </div> --}}
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Term Icon (Light)</label>
                        <div class="col-sm-7">
                          <input type="file" class="form-control" id="category_logo_white" name="category_logo_white">
                          <p>Recommended image size is 60px x 60px</p>
                        </div>
                      </div>
                      {{-- <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-7">
                          <img src="" width="100px" id="white_logo_image" style="background: #c1c1c1;padding: 10px;border-radius: 12px;">
                        </div>
                      </div> --}}
                      <div class="form-group" style="margin-top:30px;">
                          <label for="inputPassword3" class="col-sm-3 control-label">Badge Color</label>
                          <div class="col-sm-3">
                              <input type="color" name="badge_color" id="badge_color" class="color-pick form-control" style="padding:0px;">
                          </div>
                      </div>
                    {{-- <div class="form-group {{ $errors->has('detail_organizations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_organizations', 'Organizations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_organizations',$organizations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_organizations']) !!}
                            {!! $errors->first('detail_organizations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('detail_locations') ? 'has-error' : ''}}">
                        {!! Form::label('detail_locations', 'Locations' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('detail_locations',$locations,null,['class' => 'form-control','multiple' => true,'id' => 'detail_locations']) !!}
                            {!! $errors->first('detail_locations', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    {{-- <div class="form-group {{ $errors->has('phone_id') ? 'has-error' : ''}}">
                        {!! Form::label('phone_id', 'Phone Id' , ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('phone_id', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('phone_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{route('XDetails.index')}}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $("#taxonomy_vocabulary").selectpicker();
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
@endsection
