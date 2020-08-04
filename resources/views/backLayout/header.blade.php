<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title')| {{ config('app.name') }}</title>

  <!-- Bootstrap -->
  <link href="{{ URL::asset('/backend/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ URL::asset('/backend/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <link href="{{ URL::asset('/backend/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('/backend/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
  <!-- Datatables -->
  <link href="{{ URL::asset('/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('/backend/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}"
    rel="stylesheet">
  <link href="{{ URL::asset('/backend/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}"
    rel="stylesheet">
  <link href="{{ URL::asset('/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
    rel="stylesheet">
  <link href="{{ URL::asset('/backend/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}"
    rel="stylesheet">
    <script src="/frontend/global/vend/moment/moment.min.js"></script>
  <!-- Datatables -->
  <link rel="stylesheet" href="{{ URL::asset('/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <link rel="stylesheet"
    href="{{ URL::asset('/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}">
  <link href="{{ URL::asset('/backend/summernote/css/summernote.css')}}" rel="stylesheet" type="text/css" />

  <link href="{{ URL::asset('/backend/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">

  <link href="{{ URL::asset('/backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="{{ URL::asset('/backend/build/css/custom.min.css') }}" rel="stylesheet">
  @yield('style')
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
