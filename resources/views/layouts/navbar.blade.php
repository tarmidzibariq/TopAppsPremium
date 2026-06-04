<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                   
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ">Top Apps Premium</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <div class="text-truncate">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('order.*','stock.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <div class="text-truncate">Stock</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('order.index') ? 'active' : '' }}">
                    <a href="{{ Route::has('order.index') ? route('order.index') : '#' }}" class="menu-link">
                        <div class="text-truncate">Pesan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('stock.index') ? 'active' : '' }}">
                    <a href="{{ Route::has('stock.index') ? route('stock.index') : '#' }}" class="menu-link">
                        <div class="text-truncate">Stock</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('service.*', 'users.*', 'category.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <div class="text-truncate">Managemen</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('service.*') ? 'active' : '' }}">
                    <a href="{{ Route::has('service.index') ? route('service.index') : '#' }}" class="menu-link">
                        <div class="text-truncate">Layanan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('category.*') ? 'active' : '' }}">
                    <a href="{{ Route::has('category.index') ? route('category.index') : '#' }}" class="menu-link">
                        <div class="text-truncate">Kategori</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ Route::has('users.index') ? route('users.index') : '#' }}" class="menu-link">
                        <div class="text-truncate">Pengguna</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('laporan') ? 'active' : '' }}">
            <a href="{{ Route::has('laporan') ? route('laporan') : '#' }}" class="menu-link">
                <div class="text-truncate">Laporan</div>
            </a>
        </li>

        <li class="menu-header small mt-4">
            <span class="menu-header-text">Akun</span>
        </li>
        <li class="menu-item ">
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="menu-link w-100 border-0 bg-primary text-start">
                    <div class="text-truncate">Log Out</div>
                </button>
            </form>
        </li>
    </ul>
</aside>
