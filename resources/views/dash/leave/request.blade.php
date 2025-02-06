@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex flex-column flex-md-row">
                    <h4 class="card-title mb-0 flex-grow-1">Daftar Pengajuan Cuti Karyawan</h4>
                    <div class="d-flex mt-3 mt-md-0">
                        <a href="{{ route('leave.create') }}" class="btn btn-primary btn-label waves-effect waves-light">
                            <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                        </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table id="dtx" class="table table-nowrap align-middle w-100">
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
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#dtx').DataTable({
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
                            return data + ' s/d ' + row.end_date;
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
