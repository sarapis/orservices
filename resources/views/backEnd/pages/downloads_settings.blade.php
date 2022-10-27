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
                    <h2>Downloads</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    {{ Form::open(['route' => ['layout_edit.save_dowload_settings', 1],'class' => 'form-horizontal form-label-left','method' => 'post','enctype' => 'multipart/form-data']) }}




                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Display Dowload Menu</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="display_download_menu"
                                    @if ($layout->display_download_menu == 1) checked @endif />&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Download PDF</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="display_download_pdf"
                                    @if ($layout->display_download_pdf == 1) checked @endif />&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dowload CSV</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <label>Off&nbsp;&nbsp;
                                <input type="checkbox" class="js-switch" value="1" name="display_download_csv"
                                    @if ($layout->display_download_csv == 1) checked @endif />&nbsp;&nbsp;On
                            </label>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Title Text for PDF
                            Downloads
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="header_pdf"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->header_pdf }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Footer Text for PDF
                            Downloads
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="footer_pdf"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->footer_pdf }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Source Text for CSV
                            Downloads
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="occupation" type="text" name="footer_csv"
                                class="optional form-control col-md-7 col-xs-12" value="{{ $layout->footer_csv }}">
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
