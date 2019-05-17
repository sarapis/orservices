@extends('backLayout.app')
@section('title')
Edit Layout
@stop

@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Edit Layout</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            {{ Form::open(array('url' => ['layout_edit', 1], 'class' => 'form-horizontal form-label-left', 'method' => 'put', 'enctype'=> 'multipart/form-data')) }}


            <div class="form-group m-form__group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    Logo
                </label>
                <div class="col-lg-4 col-md-9 col-sm-12">
                    
                   
                    <img src="/uploads/images/{{$layout->logo}}" id="blah">
                    
                    <label class="custom-file">
                        <input type="file" id="file2" class="custom-file-input" onchange="readURL(this);" name="logo">
                        <span class="custom-file-control"></span>
                    </label>
                </div>
            </div>           

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Site Name 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="name" id="email" name="site_name" class="form-control col-md-7 col-xs-12" value="{{$layout->site_name}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Tagline 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="tagline" class="optional form-control col-md-7 col-xs-12" value="{{$layout->tagline}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Sidebar Content
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="summernote1" type="text" name="sidebar_content" class="optional form-control col-md-7 col-xs-12">{{$layout->sidebar_content}}</textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Contact Text 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="contact_text" class="optional form-control col-md-7 col-xs-12" value="{{$layout->contact_text}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Contact Button Label
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="contact_btn_label" class="optional form-control col-md-7 col-xs-12" value="{{$layout->contact_btn_label}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Contact Button Link 
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="url" id="website" name="contact_btn_link" placeholder="www.website.com" class="form-control col-md-7 col-xs-12" value="{{$layout->contact_btn_link}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Footer
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="summernote" type="text" name="footer" class="optional form-control col-md-7 col-xs-12">{{$layout->footer}}</textarea>
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Title Text for PDF Downloads
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="header_pdf" class="optional form-control col-md-7 col-xs-12" value="{{$layout->header_pdf}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Footer Text for PDF Downloads
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="footer_pdf" class="optional form-control col-md-7 col-xs-12" value="{{$layout->footer_pdf}}">
                </div>
              </div>

              <div class="item form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Source Text for CSV Downloads
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="occupation" type="text" name="footer_csv" class="optional form-control col-md-7 col-xs-12" value="{{$layout->footer_csv}}">
                </div>
              </div>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                  <button id="send" type="submit" class="btn btn-success">Submit</button>
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
        height: 200
    });
    $('#summernote1').summernote({
        height: 200
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection