<body>
  <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega navbar-inverse bg-primary-color" role="navigation">
    <div class="navbar-header">
      <a class="navbar-brand p-25 pl-15" href="/">
        @if($layout->logo_active == 1)
        <img class="navbar-brand-logo navbar-brand-logo-normal" src="../uploads/images/{{$layout->logo}}"
        title="{{$layout->site_name}}">
        <img class="navbar-brand-logo navbar-brand-logo-special" src="./uploads/images/{{$layout->logo}}"
        title="{{$layout->site_name}}">
        @endif
      </a>
      <a class="navbar-brand" href="/">{{$layout->site_name}}</a>
      @if($layout->tagline!=null)
      <div class="navbar-brand ticker well ml-10 mr-10" style="width: auto;">
         <label style="transition: none !important; display: content;"><b>{{$layout->tagline}}</b></label>
      </div>
      @endif  
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
    <div class="navbar-header" style="background: #A2E9FF;">
        
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
    background-color: {{$layout->secondary_color}};
  }
  .btn-button {
    border-color: {{$layout->button_color}};
    background-color: {{$layout->button_color}};
    color: white;
  }
  .btn-button:hover {
    border-color: {{$layout->button_hover_color}};
    background-color: {{$layout->button_hover_color}};
    color: white;
  }
</style>