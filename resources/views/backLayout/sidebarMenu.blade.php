<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ url('dashboard') }}" class="site_title"><span>{{ $layout->site_name }}</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <!--   <div class="profile">
    <div class="profile_pic">
      <a href="{{ url('dashboard') }}" class="site_title"><i class="fa fa-paw"></i> <span>NYC Services</span></a>
    </div>
    <div class="profile_info">
      <span>Welcome,</span>
      {{-- <h2>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</h2> --}}
    </div>
  </div> -->
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <ul class="nav side-menu">
                <li><a href="/" target="_blank"><i class="fa fa-desktop blue"></i> View Site</a></li>
            </ul>
        </div>
        <div class="menu_section">
            <h3>Main</h3>
            <ul class="nav side-menu">
                <li><a><i class="fa fa-windows"></i> Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/home_edit">Home</a></li>
                        <li><a href="/about_edit">About</a></li>
                        <li><a href="/login_register_edit">Login/Register</a></li>
                        <li><a href="/account">Account</a></li>
                    </ul>
                </li>
                <li><a><i class="fa fa-table"></i> Settings <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/layout_edit">Appearance</a></li>
                        <li><a href="/map">Map</a></li>
                        <li><a href="/add_country">Add Country in Address</a></li>
                        <li><a href="/messagesSetting">APIs</a></li>
                        <li><a href="/system_emails">System Emails</a></li>
                        {{-- <li><a href="/sections">Sections</a></li>
                        --}}
                        {{-- <li><a href="/meta_filter">Meta Filter</a></li>
                        <li><a href="{{ route('messagesSetting') }}">Campaigns</a></li> --}}
                    </ul>
                </li>
                <li><a><i class="fa fa-gears"></i> Tools <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ route('All_Sessions.index') }}">Sessions</a></li>
                        <li><a href="/meta_filter">Meta Filter</a></li>
                        <!-- <li><a href="/messagesSetting">Campaigns</a></li> -->
                        <li><a href="/analytics">Analytics</a></li>
                    </ul>
                </li>
                <!-- <li><a><i class="fa fa-line-chart"></i> Taxonomies <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="/service_taxonomy">Service Taxonomy</a></li>
              <li><a href="/tb_alt_taxonomy">Service Alt Taxonomy</a></li>
              <li><a href="/religions">Religions</a></li>
              <li><a href="/languages">Languages</a></li>
              <li><a href="/organizationTypes">Organization Type</a></li>
              <li><a href="/ContactTypes">Contact Type</a></li>
              <li><a href="/FacilityTypes">Location Type</a></li>
            </ul>
          </li> -->
                {{-- <li><a href="/analytics"><i class="fa fa-line-chart"></i>
                        Analytics</a> --}}
                <li><a><i class="fa fa-database"></i> Datasync <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/import">Import</a></li>
                        {{-- <li><a href="/importContact">Import Contact</a></li>
                        <li><a href="/importOrganization">Import Organization</a></li>
                        <li><a href="/importFacility">Import Loaction</a></li> --}}
                        <!-- <li><a href="/home_edit">Home</a></li> -->
                        <li><a href="/export">Export</a></li>
                    </ul>
                </li>
                </li>
                <li><a><i class="fa fa-envelope"></i> Inbox <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/contact_form">Contact Form</a></li>
                        <li><a href="/registrations">Registrations</a></li>
                    </ul>
                </li>
                <li><a><i class="fa fa-envelope"></i> Taxonomies <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/tb_taxonomy">Services</a></li>
                        <li><a href="{{ route('service_attributes.index') }}">Service Attributes</a></li>
                        <li><a href="{{ route('other_attributes.index') }}">Other Attributes</a></li>
                        <li><a href="{{ route('languages.index') }}">Language</a></li>
                        <li><a href="{{ route('XDetails.index') }}">Details</a></li>
                        <li><a href="{{ route('phone_types.index') }}">Phone Type</a></li>
                        <li><a href="{{ route('detail_types.index') }}">Detail Type</a></li>
                        <li><a href="{{ route('programs.index') }}">Programs</a></li>
                        {{-- <li><a href="{{ route('service_categories.index') }}">Service Category</a></li>
                        <li><a href="{{ route('service_eligibilities.index') }}">Service Eligibility</a></li> --}}
                    </ul>
                </li>
                <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/tb_services">Services</a></li>
                        <li><a href="/tb_locations">Locations</a></li>
                        <li><a href="/tb_organizations">Organizations</a></li>
                        <li><a href="/tb_contact">Contact</a></li>
                        <li><a href="/tb_phones">Phones</a></li>
                        <li><a href="/tb_address">Address</a></li>
                        <li><a href="/tb_schedule">Schedule</a></li>
                        {{-- <li><a href="/tb_taxonomy">Taxonomy</a></li> --}}
                        @if ($source_data->active == 1)
                            <li><a href="/tb_details">Details</a></li>
                        @endif
                        @if ($source_data->active == 0)
                            <li><a href="/tb_languages">Languages</a></li>
                            <li><a href="/tb_accessibility">Accessibility</a></li>
                        @endif
                        <li><a href="/tb_service_areas">Service area</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="menu_section">
            <h3>System</h3>
            <ul class="nav side-menu">
                {{-- @if (Sentinel::getUser()->hasAnyAccess(['user.*'])) --}}
                    <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('user.index') }}">All users</a></li>
                            <li><a href="{{ route('user.create') }}">New user</a></li>
                        </ul>
                    </li>
                    {{--
                @endif
                @if (Sentinel::getUser()->hasAnyAccess(['role.*']))
                    --}}
                    <li><a><i class="fa fa-cog"></i> Roles <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('role.index') }}">All Roles</a></li>
                            <li><a href="{{ route('role.create') }}">New Role</a></li>
                        </ul>
                    </li>
                    {{--
                @endif --}}
                <li><a><i class="fa fa-list"></i> Log Viewer <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="/log-viewer"> Dashboard</a></li>
                        <li><a href="/log-viewer/logs"> Logs</a></li>
                    </ul>
                </li>
                <li><a href="/logout"><i class="fa fa-sign-out "></i> Logout</a></li>
                <!-- <li><a><i class="fa fa-edit"></i> Forms <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="form.html">General Form</a></li>
            <li><a href="form_advanced.html">Advanced Components</a></li>
            <li><a href="form_validation.html">Form Validation</a></li>
            <li><a href="form_wizards.html">Form Wizard</a></li>
            <li><a href="form_upload.html">Form Upload</a></li>
            <li><a href="form_buttons.html">Form Buttons</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="general_elements.html">General Elements</a></li>
            <li><a href="media_gallery.html">Media Gallery</a></li>
            <li><a href="typography.html">Typography</a></li>
            <li><a href="icons.html">Icons</a></li>
            <li><a href="glyphicons.html">Glyphicons</a></li>
            <li><a href="widgets.html">Widgets</a></li>
            <li><a href="invoice.html">Invoice</a></li>
            <li><a href="inbox.html">Inbox</a></li>
            <li><a href="calendar.html">Calendar</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="tables.html">Tables</a></li>
            <li><a href="tables_dynamic.html">Table Dynamic</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="chartjs.html">Chart JS</a></li>
            <li><a href="chartjs2.html">Chart JS2</a></li>
            <li><a href="morisjs.html">Moris JS</a></li>
            <li><a href="echarts.html">ECharts</a></li>
            <li><a href="other_charts.html">Other Charts</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
            <li><a href="fixed_footer.html">Fixed Footer</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="menu_section">
      <h3>Live On</h3>
      <ul class="nav side-menu">
        <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="e_commerce.html">E-commerce</a></li>
            <li><a href="projects.html">Projects</a></li>
            <li><a href="project_detail.html">Project Detail</a></li>
            <li><a href="contacts.html">Contacts</a></li>
            <li><a href="profile.html">Profile</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="page_403.html">403 Error</a></li>
            <li><a href="page_404.html">404 Error</a></li>
            <li><a href="page_500.html">500 Error</a></li>
            <li><a href="plain_page.html">Plain Page</a></li>
            <li><a href="login.html">Login Page</a></li>
            <li><a href="pricing_tables.html">Pricing Tables</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
              <li><a href="#level1_1">Level One</a>
              <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li class="sub_menu"><a href="level2.html">Level Two</a>
                  </li>
                  <li><a href="#level2_1">Level Two</a>
                  </li>
                  <li><a href="#level2_2">Level Two</a>
                  </li>
                </ul>
              </li>
              <li><a href="#level1_2">Level One</a>
              </li>
          </ul>
        </li>
        <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li> -->
            </ul>
        </div>

    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <!--   <div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
      <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
      <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
      <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout">
      <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
  </div> -->
    <!-- /menu footer buttons -->
</div>
