@extends('master.dashboard-master')
@section('title', $title)
@section('content')
    
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-success-subtle border-success border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users icon-dual-success"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_employee }}</h5>
                    <p class="mb-0 text-muted">Karyawan</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-warning-subtle border-warning border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text icon-dual-warning"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_leave }}</h5>
                    <p class="mb-0 text-muted">Pengajuan Cuti</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-danger-subtle border-danger border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin icon-dual-danger"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_office }}</h5>
                    <p class="mb-0 text-muted">Lokasi Kantor</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-primary-subtle border-primary border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart icon-dual-primary"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_attendance }}</h5>
                    <p class="mb-0 text-muted">Presensi Bulan Ini</p>
                </div>
            </div>
        </div>
    </div><!--end col-->

</div>

@endsection