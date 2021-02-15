<body>

	<nav class="site-navbar navbar navbar-inverse navbar-fixed-top navbar navbar-expand-lg navbar-mega {{ Request::Segment(1) != null ? 'inner_navstyle' : '' }} " role="navigation">
		<div class="container">
			<div class="navbar-header">
				<div class="site-gridmenu-toggle" data-toggle="gridmenu">
					<a class="navbar-brand" href="/">
						@if($layout->logo_active == 1)
							<img class="navbar-brand-logo navbar-brand-logo-normal" src="../uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}" style="height: auto;width:100px;">
							<img class="navbar-brand-logo navbar-brand-logo-special" src="./uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}" style="height: auto;width:100px;">
						@endif
						@if($layout->title_active == 1)
						<span class="navbar-brand-text">{{$layout->site_name}}</span>
						@endif
					</a>
				</div>
				{{-- <button type="button" data-target="#sidebarCollapse"  id="" class="navbar-toggler hamburger hamburger-close navbar-toggler-center hided" data-toggle="collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="hamburger-bar"></span>
				</button> --}}
				<button class="navbar-toggler hamburger hamburger-close navbar-toggler-center hided" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
					<span class="sr-only">Toggle navigation</span>
					<span class="hamburger-bar"></span>
				</button>
				@if($layout->tagline!=null && $layout->title ==1)
					<div class="navbar-brand ticker well ml-10 mr-10">
						<label style="transition: none !important; display: content;">
							<b>{{$layout->tagline}}</b>
						</label>
					</div>
				@endif

				{{-- <button type="button" class="navbar-toggler collapsed float-right" data-target="#site-navbar-collapse" data-toggle="collapse">
					<i class="icon wb-more-horizontal" aria-hidden="true"></i>
				</button> --}}
			</div>

			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<ul class="nav navbar-toolbar float-right navbar-toolbar-right">
					<li class="nav-item">
						<a class="nav-link" href="/services">Services</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/#category">Categories</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/organizations">Organizations</a>
                    </li>
                    @if (Auth::user() && Auth::user()->roles)
                    @if (Auth::user() && Auth::user()->roles->name != 'Organization Admin' || Auth::user() && Auth::user()->roles->name == 'System Admin' )
                    <li class="nav-item">
						<div class="dropdown">
							<button class="dropbtn" style="color: {{$layout->top_menu_link_color}}">More</button>
							<div class="dropdown-content">
								<a href="/contacts">Contacts</a>
								<a href="{{ route('facilities.index') }}">Locations</a>
							</div>
						</div>
					</li>
					@endif
					@endif

					<li class="nav-item">
                        <a class="nav-link" href="{{ route('suggest.create') }}">Suggest</a>
					</li>
                    @if($layout->activate_about_home == 1)
					<li class="nav-item">
						<a class="nav-link" href="/about">About</a>
					</li>
					@endif
					<li class="nav-item">
						<a id="google_translate_element" class="nav-link"></a>
                    </li>
                    @if (Auth::user() && Auth::user()->roles)
                    <li class="nav-item">
						<div class="dropdown">
							<button class="dropbtn" style="color: {{$layout->top_menu_link_color}}">(+)</button>
							<div class="dropdown-content">
								@if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin' || Auth::user() && Auth::user()->roles &&  Auth::user()->roles->name == 'System Admin')
								<a href="{{ route('organizations.create') }}">New Organization</a>
								@endif
								<a href="{{ route('contacts.create') }}">New Contact</a>
								<a href="{{ route('services.create') }}">New Service</a>
								<a href="{{ route('facilities.create') }}">New Location</a>
							</div>
						</div>
					</li>
                    @endif
					@if (Auth::user())
					<li class="nav-item">
						<a class="nav-link" href="/account/{{Auth::user()->id}}">My account</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/logout">Logout</a>
					</li>
					@else
					<li class="nav-item">
						<a class="nav-link" href="/login">Login</a>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
  <style type="text/css">
	.ticker {
	  width: 400px;
	  background-color:transparent;
	  color:#fff;
	  border: 0;
	}
	.search-near{
	  padding: 10px;
	  padding-left: 20px;
	  font-size: 1.1em;
	  display: block;
	  color: #424242;
	  font-weight: 400;
	}
	.color_blue,.card-block .card-title p {
		color:  {{$layout->primary_color}};
	}
	.category_icon:hover {
		color: #fff;
		background: {{$layout->primary_color}};
	}
	.bg-primary-color {
	  background-color: {{$layout->primary_color}};
	}
	.bg-secondary{
	  background-color: {{$layout->secondary_color}} !important;
	}
	.top_header_blank {
		height: 50px;
		background: {{$layout->secondary_color}} ;
	}
	.card-block .card-title a,.card-block .card-title a.title_org,.detail_services .card-block .card-title a,.card-block .organization_services .card-title a,.table a,.card-block .panel-link{
		  color: {{$layout->title_link_color}};
	}
	.site-navbar .navbar-header .navbar-brand{
		color: {{$layout->menu_title_color}};
	}
	.navbar-inverse.inner_navstyle .navbar-toolbar .nav-link:hover{
		color: {{$layout->top_menu_link_hover_color}};
	}
	.inner_navstyle .goog-te-gadget-simple .goog-te-menu-value span:hover {
		color: {{$layout->top_menu_link_hover_color}};
	}
	.all_form_field .card-title{
		border-bottom: 4px solid {{$layout->secondary_color}} ;
    }
    .navbar-fixed-bottom, .navbar-fixed-top{
        background-color: {{$layout->top_menu_color}}
    }
    .navbar-inverse .navbar-toolbar .nav-link, .goog-te-gadget-simple .goog-te-menu-value span {
        color: {{$layout->top_menu_link_color}}
    }
	.btn-button, .btn-primary, .btn_darkblack {
	  border-color: {{$layout->button_color}};
	  background-color: {{$layout->button_color}};
	  color: white;
	}
	.btn-button:hover , .btn-primary:hover, .btn-primary:focus, .btn-primary.disabled_btn, .btn_darkblack:hover, .btn_darkblack:focus{
	  border-color: {{$layout->button_hover_color}};
	  background-color: {{$layout->button_hover_color}};
	  color: white;
	}
	.example .pagination li.active span {
		color: #fff;
		background-color: {{$layout->primary_color}};
		border-color: {{$layout->primary_color}};
	}
	.example .pagination li:hover a {
		color: #fff;
		background-color: {{$layout->primary_color}};
		border-color: {{$layout->primary_color}};
	}
	table.dataTable thead th, table.dataTable thead td {
		background:  {{$layout->secondary_color}};
		border-bottom: 1px solid  {{$layout->secondary_color}};
	}
	.after_serach{
		background-image: url(../uploads/images/{{$layout->bottom_background}});
	}
	.page-register{
		background-image: url(../uploads/images/{{$layout->homepage_background}});
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
	}
	.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active, .dataTables_wrapper .dataTables_paginate .paginate_button:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.active {
	    color: {{$layout->primary_color}};
	    background: {{$layout->primary_color}};
	    border: 1px solid {{$layout->primary_color}};
	}
	.dropbtn {
		background: transparent;
		color: white;
		font-style: normal;
		font-weight: 500;
		font-size: 16px;
		padding: 10px 20px;
		border: none;
	}
	.dropdown:hover .dropbtn {
		color: #fff;
		background-color: #5051DB;
		border-radius: 4px;
	}
	.dropdown {
		position: relative;
		display: inline-block;
	}
	.dropdown-content {
		display: none;
		position: absolute;
		background-color: #f1f1f1;
		min-width: 160px;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		z-index: 1;
	}
	.dropdown-content a {
		color: white;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
		background: darkgreen;
	}
	.dropdown-content a:hover {
		background-color: yellow;
		color: black;
	}
	/* Show the dropdown menu on hover */
	.dropdown:hover .dropdown-content {
		display: block;
	}

  </style>
