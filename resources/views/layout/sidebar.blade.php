<div class="col-md-3 left_col menu_fixed">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="" class="site_title"> <span>Dashboard</span></a>
    </div>

    <div class="clearfix"></div>

    <div class="profile">
      <div class="profile_pic">
        <img src="{{ asset('amadeo/images/img.jpg')}}" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Hai,</span>
        <h2>Admin</h2>
      </div>
    </div>

    <br />

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li class="">
            <a href="index.html"><i class="fa fa-home"></i> Beranda </a></li>
          <li class="{{ Route::is('provider*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Provider <span class="fa fa-chevron-down"></span>
            </a>
            <ul 
              class="nav child_menu" 
              style="{{ Route::is('provider*') ? 'display: block;' : '' }}"
            >
              <li class="{{ Route::is('provider.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider.index') }}">Provider</a>
              </li>
              <li class="{{ Route::is('provider-prefix.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider-prefix.index') }}">Provider Prefix</a>
              </li>
            </ul>
          </li>
          <li class="{{ Route::is('product*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Product <span class="fa fa-chevron-down"></span>
            </a>
            <ul 
              class="nav child_menu" 
              style="{{ Route::is('product*') ? 'display: block;' : '' }}"
            >
              <li class="{{ Route::is('product*') ? 'current-page' : '' }}">
                <a href="{{ route('product.index') }}">Product</a>
              </li>
              <li class="">
                <a href="index.hmtl">Product Sell Price</a>
              </li>
            </ul>
          </li>
          <li class="{{ Route::is('partner-pulsa*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Partner Pulsa <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('partner-pulsa*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('partner-pulsa*') ? 'current-page' : '' }}"><a href="{{ route('partner-pulsa.index') }}">Partner Pulsa</a></li>
              <li class=""><a href="index.html">Sub 2</a></li>
            </ul>
          </li>
          <li class="{{ Route::is('partner-product*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Partner Product <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('partner-product*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('partner-product*') ? 'current-page' : '' }}"><a href="{{ route('partner-product.index') }}">Partner Product</a></li>
              <li class=""><a href="index.html">Sub 2</a></li>
            </ul>
          </li>
          <li class="{{ Route::is('partner-server*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Partner <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="">
              <li class=""><a href="index.hmtl">Sub Menu 1</a></li>
              <li class="{{ Route::is('partner-server*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-server.index') }}">Partner Server</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="menu_section">
        <h3>Extra</h3>
        <ul class="nav side-menu">
          <li class="">
            <a>
              <i class="fa fa-users"></i> Manage User <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="">
              <li class=""><a href="index.hmtl">Sub Menu 1</a></li>
              <li class=""><a href="index.hmtl">Sub Menu 2</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <div class="sidebar-footer hidden-small">
      <a href="" data-toggle="tooltip" data-placement="top" title="Users">
        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Inbox">
        <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Profile">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</div>
