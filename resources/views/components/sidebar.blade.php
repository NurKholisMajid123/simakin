<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <img src="{{asset('img/logopa.png')}}" alt="" class="w-25">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">SIMAKIN</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if (auth()->user()->role === 'admin')
            <!-- Dashboard -->
            <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>

            <!-- Tugas -->
            <li class="menu-item {{ request()->is('admin/tugas*') ? 'active' : '' }}">
                <a href="{{ route('admin.tugas.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-task"></i>
                    <div data-i18n="Tugas">Tugas</div>
                </a>
            </li>

            <!-- Ruangan -->
            <li class="menu-item {{ request()->is('admin/ruangan*') ? 'active' : '' }}">
                <a href="{{ route('admin.ruangan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Ruangan">Ruangan</div>
                </a>
            </li>

            <!-- Cleaning Records -->
            <li class="menu-item {{ request()->is('admin/cleaning-records*') ? 'active' : '' }}">
                <a href="{{ route('admin.cleaning-records.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div data-i18n="Cleaning Records">Rekam Kebersihan</div>
                </a>
            </li>

            <!-- Office Boy Management -->
            <li class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Users">Manajemen OB</div>
                </a>
            </li>

        @elseif(auth()->user()->role === 'ob')
            <!-- Dashboard -->
            <li class="menu-item {{ request()->is('ob/dashboard') ? 'active' : '' }}">
                <a href="{{ route('ob.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>

            <!-- Cleaning Records Only -->
            <li class="menu-item {{ request()->is('ob/cleaning-records*') ? 'active' : '' }}">
                <a href="{{ route('ob.cleaning-records.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div data-i18n="Cleaning Records">Rekam Kebersihan</div>
                </a>
            </li>
        @endif

    </ul>
</aside>