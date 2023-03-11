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