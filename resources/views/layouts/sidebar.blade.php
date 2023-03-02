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
          @foreach($menus as $item)
            @if($item->no_show == 2)
            <li class="nav-item @if($mainMenu == $item->menu_name){{'menu-open'}}@endif">
              <a href="@if(!empty($item->slug))/{{$item->slug}}@endif" class="nav-link @if($mainMenu == $item->menu_name){{'active'}}@endif">
                <i class="{{ $item->icon_class }}"></i>
                <p>
                  {{ $item->menu_name }}
                  @if($item->getChilds->count() > 0)
                  <i class="right fas fa-angle-left"></i>
                  @endif
                </p>
              </a>
              @if($item->getChilds->count() > 0)
                @foreach ($item->getChilds as $child)
                  @if(empty($child->model))
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="/{{ $child->slug }}" class="nav-link  @if(isset($menu)) @if($child->slug == $menu->slug){{'active'}}@endif @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $child->menu_name }}</p>
                      </a>
                    </li>
                  </ul>
                  @else
                    @can('browse_'.$child->slug, app($child->model))
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="/{{ $child->slug }}" class="nav-link  @if(isset($menu)) @if($child->slug == $menu->slug){{'active'}}@endif @endif">
                          <i class="far fa-circle nav-icon"></i>
                          <p>{{ $child->menu_name }}</p>
                        </a>
                      </li>
                    </ul>
                    @endcan
                  @endif
                @endforeach
              @endif
            </li>
            @endif
          @endforeach
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>