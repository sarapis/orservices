<body>
	<nav class="site-navbar navbar navbar-inverse navbar-fixed-top navbar-mega bg-primary-color" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<div class="navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
					<a class="navbar-brand" href="/">
						@if($layout->logo_active == 1)
							<img class="navbar-brand-logo navbar-brand-logo-normal" src="../uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}" style="height: auto;">
							<img class="navbar-brand-logo navbar-brand-logo-special" src="./uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}" style="height: auto;">
						@endif
						@if($layout->title_active == 1)
						<span class="navbar-brand-text hidden-xs-down">{{$layout->site_name}}</span>
						@endif
					</a>
				</div>
				<button type="button" id="sidebarCollapse" class="navbar-toggler hamburger hamburger-close navbar-toggler-center hided" data-toggle="menubar">
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
				<button type="button" class="navbar-toggler collapsed float-right" data-target="#site-navbar-collapse" data-toggle="collapse">
					<i class="icon wb-more-horizontal" aria-hidden="true"></i>
				</button>
			</div>
			
			<div class="navbar-collapse navbar-collapse-toolbar collapse " id="site-navbar-collapse">
				<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/services">Services</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/#category">Categories</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/organizations">Organizations</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/about">About</a>
					</li>
				
					<li class="nav-item">
						<a id="google_translate_element" class="nav-link waves-effect waves-light waves-round"></a>
					</li>
					
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
	.bg-primary-color {
	  background-color: {{$layout->primary_color}};
	}
	.bg-secondary{
	  background-color: {{$layout->secondary_color}} !important;
	}
	.btn-button {
	  border-color: {{$layout->button_color}};
	  background-color: {{$layout->button_color}};
	  color: white;
	}
	.btn-button:hover {
	  border-color: {{$layout->button_hover_color}};
	  background-color: {{$layout->button_hover_color}};
	  color: {{$layout->button_color}};
	}
	.after_serach{
		background-image: url(../uploads/images/{{$layout->bottom_background}});
	}
	.page-register:before{
		background-image: url(../uploads/images/{{$layout->top_background}});
	}
  </style>