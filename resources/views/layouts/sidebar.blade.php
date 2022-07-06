<div id="kt_aside" class="aside aside-light aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">

        <a href="/dashboard">
            <img alt="Logo" src="/img/logo.svg" class="h-40px logo" />
        </a>

        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="rotate-180">
                <i class="fa-solid fa-angles-left"></i>
            </span>
        </div>
        <!--end::Aside toggler-->

    </div>
    <!--end::Brand-->

    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">

            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Menu Utama</span>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
                        <span class="menu-icon">
                            <i class="fa fa-solid fa-border-all fa-xl"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('users') ? 'active' : '' }}" href="/users">
                        <span class="menu-icon">
                            <i class="fa-solid fa-user-gear fa-lg"></i>
                        </span>
                        <span class="menu-title">Akun</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('withdraw') ? 'active' : '' }}" href="/withdraw">
                        <span class="menu-icon">
                            <i class="fa-solid fa-money-bill-transfer fa-lg"></i>
                        </span>
                        <span class="menu-title">Penarikan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('incomes') ? 'active' : '' }}" href="/incomes">
                        <span class="menu-icon">
                            <i class="fa-solid fa-circle-dollar-to-slot fa-lg"></i>
                        </span>
                        <span class="menu-title">Pendapatan</span>
                    </a>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-users fa-lg"></i>
                        </span>
                        <span class="menu-title">Pengguna</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link" href="/users">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Administator</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="/users">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Guru</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="/users">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pengguna</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="/users">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Peserta Didik</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="/users">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Toko</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('profile') ? 'active' : '' }}" href="/profile">
                        <span class="menu-icon">
                            <i class="fa-solid fa-address-card fa-lg"></i>
                        </span>
                        <span class="menu-title">Profile</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ Request::is('transactions') ? 'active' : '' }}" href="/transactions">
                        <span class="menu-icon">
                            <i class="fa-solid fa-money-bill-trend-up fa-lg"></i>
                        </span>
                        <span class="menu-title">Transaksi</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->

        </div>
    </div>
</div>