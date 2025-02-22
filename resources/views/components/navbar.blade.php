<div class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box horizontal-logo">
            <a href="index.html" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{asset('/assets/images/logo-sm.png')}}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('/assets/images/logo-dark.png')}}" alt="" height="17">
                </span>
            </a>

            <a href="index.html" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{asset('/assets/images/logo-sm.png')}}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('/assets/images/logo-light.png')}}" alt="" height="17">
                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
            <span class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>

    </div>

    <div class="d-flex align-items-center">

        <div class="dropdown d-none topbar-head-dropdown header-item">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-search fs-22"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                <form class="p-3">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="ms-1 header-item d-none d-sm-flex">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                <i class='bx bx-fullscreen fs-22'></i>
            </button>
        </div>

        <div class="ms-1 header-item d-none d-sm-flex">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle light-dark-mode">
                <i class='bx bx-moon fs-22'></i>
            </button>
        </div>

        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <i class='bx bx-bell fs-22'></i>
                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">3<span class="visually-hidden">unread messages</span></span>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">

                <div class="dropdown-head bg-primary bg-pattern rounded-top">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold text-white"> Pemberitahuan </h6>
                            </div>
                            <div class="col-auto dropdown-tabs">
                                <span class="badge bg-light text-body fs-13">4 Baru</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content position-relative" id="notificationItemsTabContent">
                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                            
                            {{-- Notification --}}
                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                <div class="d-flex">
                                    <img src="{{asset('/assets/images/users/avatar-2.jpg')}}" class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                    <div class="flex-grow-1">
                                        <a href="#!" class="stretched-link">
                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6>
                                        </a>
                                        <div class="fs-13 text-muted">
                                            <p class="mb-1">Answered to your comment on the cash flow forecast's
                                                graph 🔔.</p>
                                        </div>
                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                            <span><i class="mdi mdi-clock-outline"></i> 48 min ago</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- End Notification --}}

                            <div class="my-3 text-center view-all">
                                <button type="button" class="btn btn-soft-success waves-effect waves-light">Lihat Semua Pemberitahuan <i class="ri-arrow-right-line align-middle"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="dropdown ms-sm-3 header-item topbar-user">
            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center">
                    <img class="rounded-circle header-profile-user" src="{{asset('/assets/images/users/avatar-1.jpg')}}" alt="Header Avatar">
                    <span class="text-start ms-xl-2">
                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ $username }}</span>
                        <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">{{ $role }}</span>
                    </span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <h6 class="dropdown-header">Welcome {{ $surname }}!</h6>
                <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profil</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('password.index') }}"><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Pengaturan</span></a>
                <a class="dropdown-item" href="{{ route('logout') }}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Keluar</span></a>
            </div>
        </div>
    </div>
</div>