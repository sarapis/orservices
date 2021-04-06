@extends('layouts.app')
@section('title')
{{ $modal }}
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="contact_organization"], button[data-id="contact_services"], button[data-id="phone_type"], button[data-id="phone_language"] {
        height: 100%;
        border: 1px solid #ddd;
    }

</style>

@section('content')
<div class="top_header_blank"></div>
<div id="app">
    <editchange-component :id="{{ $id }}" :recordid="{{ $recordid }}"></editchange-component>
</div>
<script src="/js/app.js"></script>
<script>
    window.csrf_token = "{{ csrf_token() }}"
</script>
@endsection
