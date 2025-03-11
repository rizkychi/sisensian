<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

    <head>

        <meta charset="utf-8" />
        <title>@yield('title') | {{ config('app.name') }}
            @if (session('app_company') != null)
                - {{ session('app_company') }}
            @else
                - {{ config('app.company') }}
            @endif
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Sistem Informasi Presensi Karyawan" name="description" />
        <meta content="Rizkychi" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}">

        <!-- jsvectormap css -->
        <link href="{{asset('/assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- gridjs css -->
        <link rel="stylesheet" href="{{asset('/assets/libs/gridjs/theme/mermaid.min.css')}}">

        <!-- flatpickr css -->
        <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/plugins/monthSelect/style.css') }}">

        <!--datatable css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
        <!--datatable responsive css-->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

        <!-- Select 2 css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

        <!-- Sweet Alert css-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Layout config Js -->
        <script src="{{asset('/assets/js/layout.js')}}"></script>
        <!-- Bootstrap Css -->
        <link href="{{asset('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{asset('/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

        {{-- additional Css --}}
        @stack('styles')
        <style>
            #back-to-top {
                bottom: 50px;
            }
            .navbar-menu .navbar-nav .nav-sm .nav-link::before {
                content: none;
            }
            .choices[data-type*="select-one"] select.choices__input, .choices[data-type*="select-multiple"] select.choices__input {
                display: block !important;
                opacity: 0;
                pointer-events: none;
                position: absolute;
                left: 0;
                bottom: 0;
            }
            .flatpickr-monthSelect-months {
                margin: 2px;
            }
            .flatpickr-month {
                margin-bottom: 10px;
            }
            .flatpickr-current-month{
                font-size: 135%;
            }
        </style>
    </head>

    <body>
        @include('sweetalert::alert')

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="layout-width">
                    <x-navbar/>
                </div>
            </header>

            <!-- removeNotificationModal -->
            <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mt-2 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                    <h4>Are you sure ?</h4>
                                    <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                            </div>
                        </div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- ========== App Menu ========== -->
            <div class="app-menu navbar-menu">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <!-- Dark Logo-->
                    <a href="/" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{asset('/assets/images/logo-sm.png')}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset('/assets/images/logo-dark.png')}}" alt="" height="17">
                        </span>
                    </a>
                    <!-- Light Logo-->
                    <a href="/" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{asset('/assets/images/logo-sm.png')}}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{asset('/assets/images/logo-light.png')}}" alt="" height="17">
                        </span>
                    </a>
                    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                        <i class="ri-record-circle-line"></i>
                    </button>
                </div>
    
                <div id="scrollbar">
                    <div class="container-fluid">

                        <div id="two-column-menu">
                        </div>
                        
                        <!-- Menu Sidebar -->
                        <x-menu view='menu-sidebar'/>
                        
                    </div>
                    <!-- Sidebar -->
                </div>

                <div class="sidebar-background"></div>
            </div>
            <!-- Left Sidebar End -->
            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <x-menu view='menu-breadcrumb'/>
                        <!-- end page title -->

                        {{-- content --}}
                        @yield('content')
                        {{-- end content --}}

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © {{ env('APP_NAME') }}
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    @if (env('SHOW_APP_DEVELOPER'))
                                        Develop by <a href="https://www.masrizky.com/" target="_blank" class="text-reset">MasRizky</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Modals -->
        @stack('modals')

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        <!--preloader-->
        <div id="preloader">
            <div id="status">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Theme Settings -->
        <div class="d-none">
            <div class="colorscheme-cardradio">
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-mode-light" value="light">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="layout-mode-light">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>
    
                    <div class="col-4">
                        <div class="form-check card-radio dark">
                            <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-mode-dark" value="dark">
                            <label class="form-check-label p-0 avatar-md w-100 bg-dark material-shadow" for="layout-mode-dark">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-white bg-opacity-10 d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-white bg-opacity-10 rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-white bg-opacity-10 d-block p-1"></span>
                                            <span class="bg-white bg-opacity-10 d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>
                </div>
            </div>
    
            <div id="sidebar-color">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Color</h6>
                <p class="text-muted">Choose a color of Sidebar.</p>
    
                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-light" value="light">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-color-light">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark" value="dark">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-color-dark">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-white bg-opacity-10 rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient" aria-expanded="false" aria-controls="collapseBgGradient">
                            <span class="d-flex gap-1 h-100">
                                <span class="flex-shrink-0">
                                    <span class="bg-vertical-gradient d-flex h-100 flex-column gap-1 p-1">
                                        <span class="d-block p-1 px-2 bg-white bg-opacity-10 rounded mb-2"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                        <span class="d-block p-1 px-2 pb-0 bg-white bg-opacity-10"></span>
                                    </span>
                                </span>
                                <span class="flex-grow-1">
                                    <span class="d-flex h-100 flex-column">
                                        <span class="bg-light d-block p-1"></span>
                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                    </span>
                                </span>
                            </span>
                        </button>
                        <h5 class="fs-13 text-center mt-2">Gradient</h5>
                    </div>
                </div>
                <!-- end row -->
    
                <div class="collapse" id="collapseBgGradient">
                    <div class="d-flex gap-2 flex-wrap img-switch p-2 px-3 bg-light rounded">
    
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient" value="gradient">
                            <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient">
                                <span class="avatar-title rounded-circle bg-vertical-gradient"></span>
                            </label>
                        </div>
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-2" value="gradient-2">
                            <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-2">
                                <span class="avatar-title rounded-circle bg-vertical-gradient-2"></span>
                            </label>
                        </div>
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-3" value="gradient-3">
                            <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-3">
                                <span class="avatar-title rounded-circle bg-vertical-gradient-3"></span>
                            </label>
                        </div>
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-4" value="gradient-4">
                            <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-4">
                                <span class="avatar-title rounded-circle bg-vertical-gradient-4"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
    
            <div id="sidebar-size">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Size</h6>
                <p class="text-muted">Choose a size of Sidebar.</p>
    
                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-default" value="lg">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-size-default">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Default</h5>
                    </div>
    
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-compact" value="md">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-size-compact">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Compact</h5>
                    </div>
    
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small" value="sm">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-size-small">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1">
                                            <span class="d-block p-1 bg-primary-subtle mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Small (Icon View)</h5>
                    </div>
    
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small-hover" value="sm-hover">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="sidebar-size-small-hover">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1">
                                            <span class="d-block p-1 bg-primary-subtle mb-2"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                    </div>
                </div>
            </div>

            <div id="preloader-menu">
                <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Preloader</h6>
                <p class="text-muted">Choose a preloader.</p>
            
                <div class="row">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-preloader" id="preloader-view-custom" value="enable">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="preloader-view-custom">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                                <!-- <div id="preloader"> -->
                                <div id="status" class="d-flex align-items-center justify-content-center">
                                    <div class="spinner-border text-primary avatar-xxs m-auto" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <!-- </div> -->
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Enable</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-preloader" id="preloader-view-none" value="disable">
                            <label class="form-check-label p-0 avatar-md w-100 material-shadow" for="preloader-view-none">
                                <span class="d-flex gap-1 h-100">
                                    <span class="flex-shrink-0">
                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                            <span class="d-block p-1 px-2 bg-primary-subtle rounded mb-2"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                            <span class="d-block p-1 px-2 pb-0 bg-primary-subtle"></span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Disable</h5>
                    </div>
                </div>
            
            </div>
        </div>

        

        <!-- JAVASCRIPT -->
        <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
        <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
        <script src="{{asset('/assets/js/plugins.js')}}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <!-- apexcharts -->
        <script src="{{asset('/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Vector map-->
        <script src="{{asset('/assets/libs/jsvectormap/jsvectormap.min.js')}}"></script>
        <script src="{{asset('/assets/libs/jsvectormap/maps/world-merc.js')}}"></script>

        <!-- gridjs js -->
        <script src="{{asset('/assets/libs/gridjs/gridjs.umd.js')}}"></script>

        <!-- flatpickr -->
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/libs/flatpickr/plugins/monthSelect/index.js') }}"></script>

        <!-- Dashboard init -->
        {{-- <script src="assets/js/pages/dashboard-job.init.js"></script> --}}

        <!-- Datatable js-->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="{{ asset('/assets/js/datatables-custom.js') }}"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- App js -->
        <script src="{{asset('/assets/js/app.js')}}"></script>

        {{-- Custom Scripts --}}
        <script>
            function dateFormat(date) {
                var d = new Date(date);
                var month = ('0' + (d.getMonth() + 1)).slice(-2);
                var day = '' + d.getDate();
                var year = d.getFullYear();

                return [day, month, year].join('-');
            }

            if (document.querySelector('.empty-notification-elem')) {
                $('.empty-notification-elem').find('img').attr('src', "{{ asset('assets/images/svg/bell.svg') }}");
                $('.empty-notification-elem').find('h6').text('Hey! Kamu tidak memiliki pemberitahuan baru');
                $('.empty-notification-elem').find('h6').parents('div').removeClass('pb-5');
            }
        </script>
        @stack('scripts')
    </body>

</html>