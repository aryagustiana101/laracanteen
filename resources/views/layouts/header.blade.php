<div id="kt_header" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                id="kt_aside_mobile_toggle">
                <i class="fa-solid fa-bars fa-2xl"></i>
            </div>
        </div>
        <!--end::Aside mobile toggle-->

        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="/" class="d-lg-none">
                <img alt="Logo" src="/img/logo.svg" class="h-30px" />
            </a>
        </div>
        <!--end::Mobile logo-->

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <!--begin::Navbar-->
            <div class="d-flex align-items-center" id="kt_header_nav">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_header_nav'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"></h1>
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Navbar-->

            <!--begin::Topbar-->
            <div class="d-flex align-items-stretch flex-shrink-0">
                <div class="d-flex align-items-stretch flex-shrink-0">

                    <!--begin::User-->
                    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
                            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                {{ auth()->user()->administrator->name[0] }}
                            </div>
                        </div>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-50px me-5">
                                        <div class="symbol-label fs-2 fw-bold text-success">
                                            {{ auth()->user()->administrator->name[0] }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div href="#" class="fw-bolder d-flex align-items-center fs-5">
                                            {{ auth()->user()->administrator->name }}
                                        </div>
                                        <div class="fw-bolder d-flex align-items-center fs-5">
                                            <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1">
                                                Administrator
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="/profile" class="menu-link px-5">Profile</a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="" class="menu-link px-5" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_1">Logout</a>
                            </div>
                        </div>
                    </div>
                    <!--end::User -->

                </div>
            </div>
            <!--end::Topbar-->
        </div>
    </div>
</div>