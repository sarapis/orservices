	@extends('backLayout.app')
	@section('title')
	Edit Home
	@stop

	@section('content')

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Edit Home</h2>
					{{-- <ul class="nav navbar-right panel_toolbox">
						<li>
							<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
						<li>
							<a class="close-link"><i class="fa fa-close"></i></a>
						</li>
					</ul> --}}
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					{!! Form::model($page, [
					'url' => ['home_edit', 1],
					'class' => 'form-horizontal',
					'method' => 'put',
					'enctype'=> 'multipart/form-data'
					]) !!}

						<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
							{!! Form::label('name', 'Name ', ['class' => 'col-sm-3 control-label']) !!}
							<div class="col-sm-6">
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
							{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
							</div>
						</div>
						{{-- <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
							{!! Form::label('title', 'Title ', ['class' => 'col-sm-3 control-label']) !!}
							<div class="col-sm-6">
							{!! Form::text('title', null, ['class' => 'form-control']) !!}
							{!! $errors->first('title', '<p class="help-block">:message</p>') !!}
							</div>
                        </div> --}}
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Banner Text1
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input id="occupation" type="text" name="banner_text1" class="optional form-control col-md-7 col-xs-12" value="{{$layout->banner_text1}}">
                            </div>
                          </div>
                          <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Banner Text2
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input id="occupation" type="text" name="banner_text2" class="optional form-control col-md-7 col-xs-12" value="{{$layout->banner_text2}}">
                            </div>
                          </div>
						<!-- <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
								{!! Form::label('body', 'Body ', ['class' => 'col-sm-3 control-label']) !!}
								<div class="col-sm-6">
									{!! Form::textarea('body',null, array('form-control','id'=>'summernote') ) !!}
									{!! $errors->first('body', '<p class="help-block">:message</p>') !!}
								</div>
                            </div> -->
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Bottom Image
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file"  class="custom-file-input" name="part_1_image" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Bottom Content
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="summernote1" type="text" name="sidebar_content"
                                class="optional form-control col-md-7 col-xs-12">{{$layout->sidebar_content}}</textarea>
                            </div>
                        </div>
						<!-- part 1 -->
						{{-- <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Image 1
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="file"  class="custom-file-input" name="part_1_image" >
							</div>
						</div> --}}

						{{-- <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Home Page Sidebar Content
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea id="summernote1" type="text" name="sidebar_content_part_1"
								class="optional form-control col-md-7 col-xs-12">{{$layout->sidebar_content_part_1}}</textarea>
							</div>
						</div> --}}

						<!-- part 2 -->
						{{-- <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Image 2
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="file"  class="custom-file-input" name="part_2_image" >
							</div>
						</div>

						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Home Page Sidebar Content
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea id="summernote2" type="text" name="sidebar_content_part_2"
								class="optional form-control col-md-7 col-xs-12">{{$layout->sidebar_content_part_2}}</textarea>
							</div>
						</div> --}}

						<!-- part 3 -->
						{{-- <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Image 3
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="file" class="custom-file-input" name="part_3_image" >
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Home Page Sidebar Content
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea id="summernote3" type="text" name="sidebar_content_part_3"
								class="optional form-control col-md-7 col-xs-12">{{$layout->sidebar_content_part_3}}</textarea>
							</div>
						</div> --}}

						<div class="form-group m-form__group row">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">
							Backgroup Image of Home Page
							</label>
							<div class="col-md-6 col-sm-12">
								<div class="row">
									<img src="/uploads/images/{{$layout?->homepage_background}}" id="home_bk_img" style="width: 100%;">
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="custom-file">
											<input type="file" id="home_bk_img_file" class="custom-file-input" onchange="readURL(this);"
											name="home_bk_img_file" >
											<span class="custom-file-control"></span>
										</label>
									</div>
									<div class="col-md-12">
										<p>Recommended size 1824px wide and 1000px tall.</p>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-6">
									{!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
									{!! Form::close() !!}
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
		$(document).ready(function() {
			$('#summernote').summernote({
				height: 300
			});
			$('#summernote1').summernote({
				height: 100
			});
			$('#summernote2').summernote({
				height: 100
			});
			$('#summernote3').summernote({
				height: 100
			});
		});
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#home_bk_img')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
	</script>
	@endsection
