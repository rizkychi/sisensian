@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <div class="flex-grow-1 oveflow-hidden">
                <h4 class="card-title mb-0 flex-grow-1">Daftar Pengajuan Cuti Karyawan</h4>
            </div>
            <div class="flex-shrink-0 ms-2">
                <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#req" role="tab">
                            Pengajuan Baru
                            @if ($pending > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pending }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#all" role="tab">
                            Semua Cuti
                        </a>
                    </li>
                </ul>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <div class="tab-pane active" id="req" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dtx-req" class="display table align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 0px">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal Cuti</th>
                                    <th scope="col">Jenis Cuti</th>
                                    <th scope="col">Alasan</th>
                                    <th scope="col">Tanggal Pengajuan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 0px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="all" role="tabpanel">
                    <div class="table-responsive">
                        <table id="dtx-all" class="display table align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 0px">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal Cuti</th>
                                    <th scope="col">Jenis Cuti</th>
                                    <th scope="col">Alasan</th>
                                    <th scope="col">Tanggal Pengajuan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 0px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end card-body -->
    </div>
    <!--end card-->
@endsection

@push('scripts')
    <script>
        $(function() {
            var table_req = $('#dtx-req').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('leave.json') . '?status=pending' }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            return dateFormat(data) + ' s/d ' + dateFormat(row.end_date);
                        }
                    },
                    {
                        data: 'leave_type',
                        name: 'leave_type',
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return dateFormat(data);
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data == 'approved') {
                                return '<span class="badge bg-success-subtle text-success"><i class="ri-checkbox-circle-line"></i> Disetujui</span>';
                            } else if (data == 'rejected') {
                                return '<span class="badge bg-danger-subtle text-danger"><i class="ri-close-circle-line"></i> Ditolak</span>';
                            } else {
                                return '<span class="badge bg-warning-subtle text-warning"><i class="ri-time-line"></i> Menunggu</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="{{ route("leave.show", '') }}/' + data +
                                '" class="btn btn-sm btn-info btn-icon waves-effect waves-light btn-details" title="Lihat"><i class="mdi mdi-eye fs-6"></i></a>';
                        }
                    }
                ],
            });

            var table_all = $('#dtx-all').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('leave.json') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            return dateFormat(data) + ' s/d ' + dateFormat(row.end_date);
                        }
                    },
                    {
                        data: 'leave_type',
                        name: 'leave_type',
                    },
                    {
                        data: 'reason',
                        name: 'reason',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return dateFormat(data);
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data == 'approved') {
                                return '<span class="badge bg-success-subtle text-success"><i class="ri-checkbox-circle-line"></i> Disetujui</span>';
                            } else if (data == 'rejected') {
                                return '<span class="badge bg-danger-subtle text-danger"><i class="ri-close-circle-line"></i> Ditolak</span>';
                            } else {
                                return '<span class="badge bg-warning-subtle text-warning"><i class="ri-time-line"></i> Menunggu</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<a href="{{ route("leave.show", '') }}/' + data +
                                '" class="btn btn-sm btn-info btn-icon waves-effect waves-light btn-details" title="Lihat"><i class="mdi mdi-eye fs-6"></i></a>';
                        }
                    }
                ],
            });
        });
    </script>
@endpush
