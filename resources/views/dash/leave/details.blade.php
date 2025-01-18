@extends('master.dashboard-master')
@section('title', $title)

@php
    $status = [
        // 'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    $readonly = false;
    $disabled = '';
    $labels = '';

    if ($data->status == 'approved' || $data->status == 'rejected' || auth()->user()->role == 'user') {
        $readonly = true;
        $disabled = 'disabled';
    }

    if ($data->status == 'pending') {
        $labels = "Menunggu persetujuan";
    } else {
        $labels = "{$status[$data->status]} oleh {$data->confirmedBy->username} pada tanggal $data->confirmed_at";
    }

    $start_date = \Carbon\Carbon::parse($data->start_date);
    $end_date = \Carbon\Carbon::parse($data->end_date);
    $duration = $start_date->diffInDays($end_date) + 1; // Including the start date
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form id="fieldForm" action="{{ route('leave.update', ['leave' => $data->id]); }}" method="post">
                @csrf
                @isset($data)
                    @method('PUT')
                @endisset
                
                <div class="card ribbon-box border shadow-none right material-shadow">
                    <div class="card-body">
                        @if ($data->status == 'approved')
                            <div class="ribbon-three ribbon-three-success"><span class="fs-6">Disetujui</span></div>
                        @elseif ($data->status == 'rejected')
                            <div class="ribbon-three ribbon-three-danger"><span class="fs-6">Ditolak</span></div>
                        @elseif ($data->status == 'pending')
                            <div class="ribbon-three ribbon-three-warning"><span class="fs-6">Menunggu</span></div>
                        @endif
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="sticky-side-div">
                                    <div class="p-3 text-center">
                                        <img src="https://themesbrand.com/velzon/html/master/assets/images/users/avatar-2.jpg" alt="" class="avatar-lg img-thumbnail rounded-circle mx-auto profile-img">
                                        <div class="mt-3">
                                            <h5 class="fs-15 profile-name">{{ @$data->employee->name }}</h5>
                                            <p class="text-muted profile-designation">{{ @$data->employee->position }}</p>
                                            <p class="text-muted profile-designation">{{ @$data->employee->office->name }}</p>
                                        </div>
                                        <div class="hstack gap-2 justify-content-center mt-4">
                                            @if (@$data->employee->phone)
                                                <div class="avatar-xs">
                                                    <a href="https://wa.me/62{{ $data->employee->phone }}" class="avatar-title bg-success-subtle text-success rounded fs-16">
                                                        <i class="ri-whatsapp-fill"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            <div class="avatar-xs">
                                                <a href="mailto:{{ $data->employee->user->email }}" class="avatar-title bg-info-subtle text-info rounded fs-16">
                                                    <i class="ri-mail-fill"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-8">
                                <div class="mt-0 mt-md-3">
                                    <h4>Detail Pengajuan Cuti</h4>
                                    <div class="hstack flex-wrap">
                                        <div class="text-muted">Kantor : <span class="text-body fw-medium">{{ $data->employee->office->name }}</span></div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Alamat Kantor : <span class="text-body fw-medium">{{ @$data->employee->office->address ?? '-' }}</span></div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded text-center">
                                                <div>
                                                    <p class="text-muted fw-medium mb-1">Lama Cuti</p>
                                                    <h5 class="fs-17 text-success mb-0">{{ $duration }} hari</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded text-center">
                                                <div>
                                                    <p class="text-muted fw-medium mb-1">Mulai Cuti</p>
                                                    <h5 class="fs-17 mb-0">{{ $data->start_date }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded text-center">
                                                <div>
                                                    <p class="text-muted fw-medium mb-1">Selesai Cuti</p>
                                                    <h5 class="fs-17 mb-0">{{ $data->end_date }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="p-2 border border-dashed rounded text-center">
                                                <div>
                                                    <p class="text-muted fw-medium mb-1">Tanggal Pengajuan</p>
                                                    <h5 class="fs-17 mb-0">{{ $data->created_at }}</h5>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                    </div><!--end row-->
                                    <div class="mt-4 text-muted">
                                        <h5 class="fs-14">Jenis Cuti :</h5>
                                        <p class="d-flex align-items-center"><span class="badge bg-secondary me-2">{{ $data->leave_type }}</span> {{ App\Models\LeaveType::getName($data->leave_type) }}</p>
                                    </div>
                                    <div class="mt-4 text-muted">
                                        <h5 class="fs-14">Alasan :</h5>
                                        <p>{{ @$data->reason }}</p>
                                    </div>
                                    <div class="mt-4">
                                        {{-- <h5 class="fs-14 mb-3">Aksi :</h5> --}}
                                        <form action="{{ route('leave.update', ['leave' => $data->id]) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" name="status" id="status" data-choices required {{ $disabled }}>
                                                    <option value="">Menunggu</option>
                                                    @foreach ($status as $key => $value)
                                                        <option value="{{ $key }}" {{ old('status', $data->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="note" class="form-label">Catatan</label>
                                                <textarea class="form-control" name="note" id="note" rows="3" {{ $disabled }}>{{ old('note', @$data->note) }}</textarea>
                                            </div>

                                            @if (!$readonly)
                                                <div class="float-end">
                                                    <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                                        <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                                                    </button>
                                                    <a href="{{ route('leave.index') }}" class="btn btn-light">Batal</a>
                                                </div>
                                            @else
                                                <div class="float-end">
                                                    <span class="text-muted fst-italic me-4">{{ $labels }}</span>
                                                    <a href="{{ route('leave.index') }}" class="btn btn-light">Kembali</a>
                                                </div>
                                            @endif
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div><!--end card-->

            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')<!-- Sweet Alerts js -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {
            $('#fieldForm').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0ab39c',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        })
    </script>
@endpush
