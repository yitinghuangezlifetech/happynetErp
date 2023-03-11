<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        @if(!empty($user->avatar))
        <img src="{{$user->avatar}}" class="img-circle elevation-2" alt="User Image">
        @else
        <img src="/admins/img/icon-user.png" class="img-circle elevation-2" alt="User Image">
        @endif
      </div>
      <div class="info">
        <a href="{{route('users.profile')}}" class="d-block">{{$user->name}}</a>
      </div>
    </div>

    <!-- 
      SidebarSearch Form 
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>
    -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if(!empty($menus) && $menus->count() > 0)
          @each('layouts.menu_item', $menus, 'item')
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>