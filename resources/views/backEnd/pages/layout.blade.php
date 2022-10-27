@extends('backLayout.app')
@section('title')
    Appearance
@stop
<style>
    .color-pick {
        padding: 0 !important;
    }

</style>
@section('content')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Appearance</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    {{ Form::open(['url' => ['layout_edit', 1],'class' => 'form-horizontal form-label-left','method' => 'put','enctype' => 'multipart/form-data']) }}


                    <div class="form-group m-form__group row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            Logo
                        </label>
                        <div class="col-md-6 col-sm-12">

                            <div class="row">
                                <img src="/uploads/images/{{ $layout->logo }}" id="blah">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="custom-file">
                                        <input type="file" id="file2" class="custom-file-input" onchange="readURL(this);"
                                            name="logo">
                                        <span class="custom-file-control"></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Hide Logo</b>&nbsp;&nbsp;
                                        <input type="checkbox" class="js-switch" value="checked" name="logo_active"
                                            @if ($layout->logo_active == 1) checked @endif />&nbsp;&nbsp;<b>Show Logo</b></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Recommended size 30px wide and 30px tall.</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Site Name
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="name" id="email" name="site_name" class="form-control col-md-7 col-xs-12"
                                value="{{ $layout->site_name }}">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Show Site Title
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <span><b>Hide</b>&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="checked" name="site_title_active"
                                    @if ($layout->site_title_active == 1) checked @endif />&nbsp;&nbsp;<b>Show </b></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Tagline
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="tagline"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->tagline }}">
                        </div>
                    </div>
                    <!-- <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">
                    </label>
                    <div class="col-md-6">
                      <span><b>Hide Title and Tagline </b>&nbsp;&nbsp;
                        <input type="checkbox" class="js-switch" value="checked" name="title_active"  @if ($layout->title_active == 1) checked @endif/>&nbsp;&nbsp;<b>Show Title and Tagline </b>
                      </span>
                    </div>
                  </div> -->

                    <!-- <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">
                    </label>
                    <div class="col-md-6">
                      <span><b>Hide Bottom Section </b>&nbsp;&nbsp;
                        <input type="checkbox" class="js-switch" value="checked" name="bottom_section_active"  @if ($layout->bottom_section_active == 1) checked @endif/>&nbsp;&nbsp;<b>Show Bottom Section </b>
                      </span>
                    </div>
                  </div> -->

                    <!-- <div class="form-group m-form__group row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Bottom background Image
                    </label>
                    <div class="col-md-6 col-sm-12">

                        <div class="row">
                          <img src="/uploads/images/{{ $layout->bottom_background }}" id="blah2" style="width: 100%;">
                        </div>
                        <div class="row" style="margin-top: 10px;">
                          <div class="col-md-6">
                            <label class="custom-file">
                                <input type="file" id="file4" class="custom-file-input" onchange="readURL_bottom(this);" name="bottom_background">
                                <span class="custom-file-control"></span>
                            </label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label>Recommended size 1200px wide.</label>
                          </div>
                        </div>
                    </div>
                  </div> -->

                    <!-- <div class="item form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Bottom Section Content
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea id="summernote1" type="text" name="sidebar_content" class="optional form-control col-md-7 col-xs-12">{{ $layout->sidebar_content }}</textarea>
                    </div>
                  </div> -->
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Contact Text
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="contact_text"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->contact_text }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Contact Button Label
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="contact_btn_label"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->contact_btn_label }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Contact Button Link
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="url" id="website" name="contact_btn_link" placeholder="www.website.com"
                                class="form-control col-md-7 col-xs-12" value="{{ $layout->contact_btn_link }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Footer
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="summernote" type="text" name="footer"
                                class="optional form-control col-md-7 col-xs-12">{{ $layout->footer }}</textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Primary Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="primary_color" value="{{ $layout->primary_color }}"
                                id="primary_color" class=" form-control col-md-5 col-xs-5" style="padding:0px">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Secondary Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="secondary_color" id="secondary_color"
                                value="{{ $layout->secondary_color }}" style="padding:0px"
                                class="form-control col-md-5 col-xs-5">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Button Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="button_color" id="button_color" value="{{ $layout->button_color }}"
                                style="padding:0px" class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Button Hover Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="button_hover_color" id="button_hover_color"
                                value="{{ $layout->button_hover_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Title & Link Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="title_link_color" id="title_link_color"
                                value="{{ $layout->title_link_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Top Menu Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="top_menu_color" id="top_menu_color"
                                value="{{ $layout->top_menu_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Top Menu link Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="top_menu_link_color" id="top_menu_link_color"
                                value="{{ $layout->top_menu_link_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Top Menu Link Hover Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="top_menu_link_hover_color" id="top_menu_link_hover_color"
                                value="{{ $layout->top_menu_link_hover_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Top Menu Link Hover Background Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="top_menu_link_hover_background_color" id="top_menu_link_hover_background_color" value="{{ $layout->top_menu_link_hover_background_color }}" style="padding:0px" class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Menu Title Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="menu_title_color" id="menu_title_color"
                                value="{{ $layout->menu_title_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Submenu Highlight Color
                        </label>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <input type="color" name="submenu_highlight_color" id="submenu_highlight_color"
                                value="{{ $layout->submenu_highlight_color }}" style="padding:0px"
                                class="color-pick form-control col-md-5 col-xs-5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Show organization share button</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="organization_share_button"
                                    @if ($layout->organization_share_button == 1) checked @endif />&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Show service share button</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="service_share_button"
                                    @if ($layout->service_share_button == 1) checked @endif />&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Enable SDOH Classification</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>No&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="show_classification"
                                    @if ($layout->show_classification == 'yes') checked @endif />&nbsp;&nbsp;Yes
                            </label>
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

    <link rel="stylesheet" media="screen" type="text/css" href="/css/colorpicker.css" />
    <script type="text/javascript" src="/js/colorpicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 200
            });
            $('#summernote1').summernote({
                height: 200
            });
        });
        $('#primary_color').ColorPicker({
            color: $('#primary_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#primary_color').val('#' + hex);
            }
        });
        $('#secondary_color').ColorPicker({
            color: $('#secondary_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#secondary_color').val('#' + hex);
            }
        });
        $('#button_color').ColorPicker({
            color: $('#button_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#button_color').val('#' + hex);
            }
        });
        $('#button_hover_color').ColorPicker({
            color: $('#button_hover_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#button_hover_color').val('#' + hex);
            }
        });
        $('#title_link_color').ColorPicker({
            color: $('#title_link_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#title_link_color').val('#' + hex);
            }
        });
        $('#top_menu_color').ColorPicker({
            color: $('#top_menu_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#top_menu_color').val('#' + hex);
            }
        });
        $('#top_menu_link_color').ColorPicker({
            color: $('#top_menu_link_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#top_menu_link_color').val('#' + hex);
            }
        });
        $('#top_menu_link_hover_color').ColorPicker({
            color: $('#top_menu_link_hover_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#top_menu_link_hover_color').val('#' + hex);
            }
        });
        $('#top_menu_link_hover_background_color').ColorPicker({
            color: $('#top_menu_link_hover_background_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#top_menu_link_hover_background_color').val('#' + hex);
            }
        });
        $('#submenu_highlight_color').ColorPicker({
            color: $('#submenu_highlight_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#submenu_highlight_color').val('#' + hex);
            }
        });
        $('#menu_title_color').ColorPicker({
            color: $('#menu_title_color').val(),
            onShow: function(colpkr) {
                $(colpkr).fadeIn(500);
                return false;
            },
            onHide: function(colpkr) {
                $(colpkr).fadeOut(500);
                return false;
            },
            onChange: function(hsb, hex, rgb) {
                $('#menu_title_color').val('#' + hex);
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL_top(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah1')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL_bottom(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah2')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
