@extends('backLayout.app')
@section('title')
Help Text
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


<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Help Text</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {!! Form::open(['route' => 'helptexts.save_helptexts','enctype' => 'multipart/form-data']) !!}
                    <div class="row">
                        <div class="form-group {{ $errors->has('service_classification') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Service Classification</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('service_classification',($helptext->service_classification ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('service_classification', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('service_conditions') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Service Conditions</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('service_conditions',($helptext->service_conditions ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('service_conditions', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('service_goals') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Service Goals</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('service_goals',($helptext->service_goals ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('service_goals', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('service_activities') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">Service Activities</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('service_activities',($helptext->service_activities ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('service_activities', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('code_category') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">SDOH Categories</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('code_category',($helptext->code_category ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('code_category', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('sdoh_code_helptext') ? 'has-error' : ''}}">
                            <label class="col-sm-3 control-label text-right">SDOH Codes</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::textarea('sdoh_code_helptext',($helptext->sdoh_code_helptext ?? null),['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                                    {!! $errors->first('sdoh_code_helptext', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-right"></label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="submit" value="Save" class="btn btn-primary">
                                </div>
                            </div>
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
        $('.code_category_ids').change(function(){
            if(!$(this).prop('checked')){
                if(confirm('Deselecting this category will clear codes for this category from this services.')){
                    $(this).removeAttr('checked');
                }else{
                    $(this).prop('checked',true);
                }
            }
        })
    });
</script>
@endsection

