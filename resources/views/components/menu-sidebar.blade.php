<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

    @foreach ($menus as $menu)
    @php
        $active = '';
        $prefix = explode('.', $menu->route)[0];
        if ($menu->route) {
            $active = strpos(Route::currentRouteName(), $prefix) !== false && $menu->route != null ? 'active' : '';
            $route = route($menu->route);
        } else {
            $route = '#';
        }

        $submenuActive = false;
        if (@$menu->submenus) {
            foreach ($menu->submenus as $sub) {
                $sub_prefix = explode('.', $sub->route)[0];
                if (strpos(Route::currentRouteName(), $sub_prefix) !== false && $sub->route != null) {
                    $submenuActive = true;
                    break;
                }
            }
        }
    @endphp
    <li class="nav-item">
        @if (!@$menu->submenus)
            <a class="nav-link menu-link {{ $active }} {{ $menu->show ? '':'d-none' }}" href="{{ $route }}">
                <i class="{{ $menu->icon }}"></i> <span data-key="t-widgets">{{ $menu->name }}</span>
            </a>
        @else
            <a class="nav-link menu-link {{ $submenuActive ? 'active' : '' }} {{ $menu->show ? '':'d-none' }}" href="#sidebar{{$menu->slug}}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $submenuActive ? 'true' : 'false' }}" aria-controls="sidebar{{$menu->slug}}">
                <i class="{{ $menu->icon }}"></i> <span data-key="t-widgets">{{ $menu->name }}</span>
            </a>
            <div class="collapse menu-dropdown {{ $submenuActive ? 'show' : '' }}" id="sidebar{{$menu->slug}}">
                <ul class="nav nav-sm flex-column">
                    @foreach ($menu->submenus as $sub)
                    @php
                        $sub_active = '';
                        $sub_prefix = explode('.', $sub->route)[0];
                        if ($sub->route) {
                            $sub_active = strpos(Route::currentRouteName(), $sub_prefix) !== false && $sub->route != null ? 'active' : '';
                        }
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $sub_active }} {{ $sub->show ? '':'d-none' }}" href="{{ $sub->route ? route($sub->route) : '#' }}">
                            <i class="{{ $sub->icon }}"></i> <span data-key="t-widgets">{{ $sub->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </li>
    @endforeach
</ul>