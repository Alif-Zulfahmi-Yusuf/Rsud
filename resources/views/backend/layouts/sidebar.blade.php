<nav class="navbar navbar-vertical navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">Dashboard</span></span>
                            </div>
                        </a>
                    </div>
                    <!-- parent pages-->
                    @can('data-master')
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-home" role="button"
                            data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-home">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="users"></span></span>
                                <span class="nav-link-text">Data Master</span>
                                <!-- <span class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span> -->
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('pangkat.*') || request()->routeIs('atasans.*') || request()->routeIs('perilaku.*') || request()->routeIs('category.*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                                <li class="collapsed-nav-item-title d-none">Data Master</li>
                                <li class="nav-item">
                                    @can('user-list')
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                        href="{{ route('users.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Data Users</span>
                                        </div>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('role-list')
                                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                                        href="{{ route('roles.index') }}">
                                        <div class="zd-flex align-items-center">
                                            <span class="nav-link-text">Data Roles</span>
                                        </div>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('pangkat.*') ? 'active' : '' }}"
                                        href="{{ route('pangkat.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Data Pangkat</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('atasans.*') ? 'active' : '' }}"
                                        href="{{ route('atasans.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Data Atasan</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
                                        href="{{ route('category.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Data Category</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('perilaku.*') ? 'active' : '' }}"
                                        href="{{ route('perilaku.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Data Perilaku</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endcan

                    @can('pengukuran-list')
                    <!-- pengukuran data -->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-pengukuran" role="button"
                            data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-pengukuran">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>

                                <span class="nav-link-icon"><span data-feather="tag"></span></span>
                                <span class="nav-link-text">Pengukuran Kinerja</span>
                                <!-- <span class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span> -->
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->routeIs('harian-pegawai.*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-pengukuran">
                                <li class="collapsed-nav-item-title d-none">Pengukuran Kinerja</li>


                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('harian-pegawai.*') ? 'active' : '' }}"
                                        href="{{ route('harian-pegawai.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Kegiatan Harian </span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end pengukuran data -->
                    @endcan

                    <!-- perencanaan kinerja -->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-perencanaan" role="button"
                            data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-perencanaan">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="file-text"></span></span>
                                <span class="nav-link-text">Perencaan Kinerja</span>
                                <!-- <span class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span> -->
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->routeIs('skp.*') || request()->routeIs('skp_atasan.*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-perencanaan">
                                <li class="collapsed-nav-item-title d-none">Perencaan Kinerja</li>

                                @can('skp-pegawai')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('skp.*') ? 'active' : '' }}"
                                        href="{{ route('skp.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">SKP</span>
                                        </div>
                                    </a>
                                </li>
                                @endcan

                                @can('skp-atasan')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('skp_atasan.*') ? 'active' : '' }}"
                                        href="{{ route('skp_atasan.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">SKP</span>
                                        </div>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                    <!-- end perencanaan kinerja -->

                    @can('validasi-list')
                    <!-- validasi data -->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-validasi" role="button"
                            data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-validasi">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>

                                <span class="nav-link-icon"><span data-feather="check-square"></span></span>
                                <span class="nav-link-text">Validasi Data</span>
                                <!-- <span class="fa-solid fa-circle text-info ms-1 new-page-indicator"
                                    style="font-size: 6px"></span> -->
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->routeIs('validasi.*') || request()->routeIs('validasi-harian.*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-validasi">
                                <li class="collapsed-nav-item-title d-none">Validasi Data</li>


                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('validasi.*') ? 'active' : '' }}"
                                        href="{{ route('validasi.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">SKP</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('validasi-harian.*') ? 'active' : '' }}"
                                        href="{{ route('validasi-harian.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Rencana Harian</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end validasi data -->
                    @endcan
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span>
            <span class="uil uil-arrow-from-right fs-8"></span>
            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>