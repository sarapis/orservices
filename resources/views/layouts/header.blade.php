<body>
  <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega navbar-inverse bg-custom" role="navigation">
    <div class="navbar-header">
      <a class="navbar-brand p-25 pl-15" href="/">
        <img class="navbar-brand-logo navbar-brand-logo-normal" src="../uploads/images/{{$layout->logo}}"
        title="{{$layout->site_name}}">
        <img class="navbar-brand-logo navbar-brand-logo-special" src="./uploads/images/{{$layout->logo}}"
        title="{{$layout->site_name}}">
      </a>
      <a class="navbar-brand" href="/">{{$layout->site_name}}</a>
       <div class="navbar-brand ticker well ml-10 mr-10">
        <span>{{$layout->tagline}}</span>
      </div>
      <!-- <a class="navbar-brand nav-item nav-link mr-0 pl-0 pr-5" href="/explore">Explore</a>
      <a class="navbar-brand nav-item nav-link mr-0 pl-0 pr-5" href="/about">About</a>
      <a class="navbar-brand nav-item nav-link mr-0 pl-0 pr-5" href="https://www.participatorybudgeting.org/donate/" target="_blank">Donate</a>
      <a class="navbar-brand nav-item mr-0 pl-0 pr-5" href="">Español</a> -->
        
        <ul class="nav navbar-toolbar nav-menubar pull-right">
          <li class="nav-item nav-menu">
            <a class="nav-link text-white" href="/services"><b>Services</b></a>
          </li>
          <li class="nav-item nav-menu">
            <a class="nav-link text-white" href="/organizations"><b>Organizations</b></a>
          </li>
          <li class="nav-item nav-menu">
            <a class="nav-link text-white" href="/about"><b>About</b></a>
          </li>
          <li class="nav-item">
            <div id="google_translate_element" class="nav-link" style="width: 60px;"><!-- <b>Languages</b> -->
            </div>
          </li>
          <li class="nav-item">
            <div class="sharethis-inline-share-buttons pt-10"></div>
          </li>
          <li class="nav-item">
            <button type="button" id="sidebarCollapse" class="navbar-toggler hamburger hamburger-close navbar-toggler-center hided" style="color: white;">
              <i class="icon glyphicon glyphicon-align-justify"></i>
              <span>Toggle Sidebar</span>
            </button>
          </li>
        </ul>
        
    </div>
  

  </nav>
<style type="text/css">
  .ticker {
    width: 400px;
    background-color:transparent;
    color:#fff;
    border: 0;
  }
</style>