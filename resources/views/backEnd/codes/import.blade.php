@extends('backLayout.app')
@section('title')
Import Codes
@stop

@section('content')
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

<style>
    .form-group {
        float: left;
        width: 100%
    }
</style>


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Import Codes</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'codes.ImportCodesExcel','enctype' => 'multipart/form-data']) !!}
                    <div class="form-group {{ $errors->has('import_codes') ? 'has-error' : ''}}">
                        <label class="col-sm-3 control-label text-right">Import Codes</label>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="file" name="import_codes" class="form-control">
                                {!! $errors->first('import_codes', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('import_type') ? 'has-error' : ''}}">
                        <label class="col-sm-3 control-label text-right">Import Type</label>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="import_type" id="import_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="add">Add Codes</option>
                                    <option value="replace">Replace Codes</option>
                                </select>
                                {!! $errors->first('import_type', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <a href="/uploads/codes-template.csv" class="btn btn-info" download>Download Import Template</a>
                            <input type="submit" value="Import" class="btn btn-success">
                            <a href="{{ route('codes.index') }}" class="btn btn-warning">Code List</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#import_type').change(function(){
                if($(this).val() == 'replace' && confirm('Replacing Codes will delete all existing codes. Are you sure you want to proceed?')){
                    return true
                }else if($(this).val() == 'add'){
                    return true
                }else{
                    $('#import_type').val('')
                }
            })
        })
    </script>
@endsection
