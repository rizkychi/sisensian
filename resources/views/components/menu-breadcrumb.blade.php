@php
    $currentMenu = null;
    foreach ((array) $menus as $menu) {
        $prefix = explode('.', $menu->route)[0];
        if (strpos(Route::currentRouteName(), $prefix) !== false && $menu->route != null) {
            $currentMenu = $menu;
            break;
        }
        if (@$menu->submenus) {
            foreach ((array) $menu->submenus as $sub) {
                $sub_prefix = explode('.', $menu->route)[0];
                $sub_prefix = explode('.', $menu->route)[0];
                if (strpos(Route::currentRouteName(), $sub_prefix) !== false && $sub->route != null) {
                    $currentMenu = $sub;
                    $currentParent = $menu;
                    break;
                }
            }
        }
    }
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">{{ $currentMenu->name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    @if (@$currentParent)
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $currentParent->name }}</a></li>
                    @endif
                    @if ($currentMenu)
                        <li class="breadcrumb-item active">{{ $currentMenu->name }}</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>