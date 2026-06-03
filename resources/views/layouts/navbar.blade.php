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

        <li class="menu-item {{ request()->routeIs('stock.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <div class="text-truncate">Stock</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('stock.pesan') ? 'active' : '' }}">
                    <a href="{{ Route::has('stock.pesan') ? route('stock.pesan') : '#' }}" class="menu-link">
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

        <li class="menu-item {{ request()->routeIs('management.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <div class="text-truncate">Managemen</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('management.layanan') ? 'active' : '' }}">
                    <a href="{{ Route::has('management.layanan') ? route('management.layanan') : '#' }}" class="menu-link">
                        <div class="text-truncate">Layanan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('management.kategori') ? 'active' : '' }}">
                    <a href="{{ Route::has('management.kategori') ? route('management.kategori') : '#' }}" class="menu-link">
                        <div class="text-truncate">Kategori</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('management.pengguna') ? 'active' : '' }}">
                    <a href="{{ Route::has('management.pengguna') ? route('management.pengguna') : '#' }}" class="menu-link">
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
