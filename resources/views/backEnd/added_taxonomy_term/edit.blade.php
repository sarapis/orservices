@extends('backLayout.app')
@section('title')
Taxonomy Edit
@stop
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Taxonomy Edit</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{-- {!! Form::model($taxonomy,['route' => array('tb_taxonomy.update',$taxonomy->id), 'class' => 'form-horizontal']) !!} --}}
                {{-- {!! Form::open(['route' => 'tb_taxonomy.updateStatus', 'class' => 'form-horizontal']) !!} --}}
                <form action="/updateStatus/{{ $taxonomy->id }}" class="form-horizontal" method="post">
                    @csrf
                <div class="form-group {{ $errors->has('contact_type') ? 'has-error' : ''}}">
                    {!! Form::label('contact_type', 'Contact Type', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('status',['Published' => 'Published','Unpublished' => 'Unpublished'],$taxonomy->status,['class' => 'form-control selectpicker']) !!}
                        {!! $errors->first('contact_type', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                        <a href="{{route('tb_taxonomy.show_added_taxonomy')}}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
            </div>
		</div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".selectpicker").selectpicker();
    });
</script>
@endsection
