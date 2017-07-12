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
            <a href=""><i class="fa fa-home"></i> Beranda </a></li>
          <li class="">
            <a>
              <i class="fa fa-beer"></i> Menu <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="">
              <li class=""><a href="index.hmtl">Sub Menu 1</a></li>
              <li class=""><a href="index.hmtl">Sub Menu 2</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="menu_section">
        <h3>Extra</h3>
        <ul class="nav side-menu">
          <li class="">
            <a href="index.html"><i class="fa fa-inbox"></i> Menu 2</a>
          </li>
          <li class="">
            <a><i class="fa fa-windows"></i> Menu 3 <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="index.html">Sub Menu 3</a></li>
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
