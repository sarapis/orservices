@extends('layouts.app')
@section('title')
About
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="suggest_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
</style>

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-6 m-auto">
                <h4 class="card-title title_edit mb-30">
                    {{ $page ? $page->title : '' }}
                </h4>
                <div class="card all_form_field">
                    <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $page ? $page->body : '' !!}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $('#back-contact-btn').click(function() {
    //     history.go(-1);
    //     return false;
    // });
    $(document).ready(function() {
        $('select#suggest_organization').val([]).change();
    });
</script>
@endsection
