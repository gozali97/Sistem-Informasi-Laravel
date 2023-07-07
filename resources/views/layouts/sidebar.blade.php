<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <img src="{{ url('/assets/img/hilab.png') }}" width="100" style="margin-left: 20%!important" alt="">
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link" style="text-decoration: none">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @php
            $access = \App\Models\User::query()
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->where('users.id', Auth::user()->id)
                ->get();
        @endphp

        @foreach (getMenus() as $menu)
            @can('read ' . $menu->url)
                <li class="menu-item {{ request()->segment(1) == $menu->url ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle" style="text-decoration: none">
                        <i class="menu-icon tf-icons bx {{ $menu->icon }}" animation='tada'></i>
                        <div data-i18n="Account Settings">{{ $menu->name }}</div>
                    </a>
                    <ul class="menu-sub">
                        @foreach ($menu->subMenus as $subMenu)
                            @can('read ' . $subMenu->url)
                                <li
                                    class="menu-item {{ request()->segment(1) == explode('/', $menu->url)[0] && request()->segment(2) == explode('/', $subMenu->url)[1] ? 'active' : '' }}">
                                    <a href="{{ url($subMenu->url) }}" class="menu-link" style="text-decoration: none">
                                        <div data-i18n="Account">{{ $subMenu->name }}</div>
                                    </a>
                                </li>
                            @endcan
                        @endforeach

                    </ul>
                </li>
            @endcan
        @endforeach
    </ul>
</aside>
