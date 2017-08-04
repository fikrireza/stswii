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
        <h2>{{ Auth::user()->name }}</h2>
      </div>
    </div>

    <br />

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li class="{{ Route::is('home*') ? 'active' : '' }}">
            <a href="{{ route('home.index') }}"><i class="fa fa-home"></i> Home </a>
          </li>
          <li class="{{ Route::is('provider*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Provider <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('provider*') ? 'display: block;' : '' }}">
              @can('view-provider')
              <li class="{{ Route::is('provider.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider.index') }}">Provider</a>
              </li>
              @endcan
              @can('view-provider-prefix')
              <li class="{{ Route::is('provider-prefix.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider-prefix.index') }}">Provider Prefix</a>
              </li>
              @endcan
            </ul>
          </li>
          <li class="{{ Route::is('product.*') ? 'active' : '' }}{{ Route::is('product-*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Product <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('product.*') ? 'display: block;' : '' }}{{ Route::is('product-*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('product.*') ? 'current-page' : '' }}"><a href="{{ route('product.index') }}">Product</a></li>
              <li class="{{ Route::is('product-*') ? 'current-page' : '' }}"><a href="{{ route('product-sell-price.index') }}">Product Sell Price</a></li>
            </ul>
          </li>
          <li class="{{ Route::is('partner-product*') ? 'active' : '' }}{{ Route::is('partner-pulsa*') ? 'active' : '' }}{{ Route::is('partner-server*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Partner <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('partner-product*') ? 'display: block;' : '' }}{{ Route::is('partner-pulsa*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('partner-pulsa*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-pulsa.index') }}">Partner Pulsa</a>
              </li>
              <li class="{{ Route::is('partner-product.*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-product.index') }}">Partner Product</a>
              </li>
              <li class="{{ Route::is('partner-product-purch-price*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-product-purch-price.index') }}">Partner Product Purch Price</a>
              </li>
              <li class="{{ Route::is('partner-server*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-server.index') }}">Partner Server</a>
              </li>
            </ul>
          </li>
          <li class="{{ Route::is('agent*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Agent <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('agent*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('agent.index.table.js') ? 'current-page' : '' }}">
                <a href="{{ route('agent.index.table.js') }}">Agent Js</a>
              </li>
              <li class="{{ Route::is('agent.index.table.php') ? 'current-page' : '' }}">
                <a href="{{ route('agent.index.table.php') }}">Agent PHP</a>
              </li>
            </ul>
          </li>
          <li class="{{ Route::is('paloma*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Paloma Deposit <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="">
              <li class="{{ Route::is('paloma.transaction*') ? 'current-page' : '' }}">
                <a href="{{ route('paloma.transaction.index') }}">Transaction</a>
              </li>
              <li class="{{ Route::is('paloma.balance*') ? 'current-page' : '' }}">
                <a href="#">Balance</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="menu_section">
        <h3>Extra</h3>
        <ul class="nav side-menu">
          @can('user-management')
          <li class="{{ Route::is('account*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-users"></i> Manage Account <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('account*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('account.index') ? 'current-page' : '' }}"><a href="{{ route('account.index') }}">Users</a></li>
              @can('role-management')
              <li class="{{ Route::is('account.role') ? 'current-page' : '' }}"><a href="{{ route('account.role') }}">Role Task</a></li>
              @endcan
            </ul>
          </li>
          @endcan
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
