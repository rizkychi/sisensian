@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Jenis Shift Kerja</h4>
                    <a href="{{ route('shift.create') }}" class="btn btn-primary btn-label waves-effect waves-light">
                        <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                    </a>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table id="dtx" class="table align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 0px;">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Waktu Masuk</th>
                                        <th scope="col">Waktu Pulang</th>
                                        <th scope="col">Shift Tetap</th>
                                        <th scope="col">Shift Malam</th>
                                        <th scope="col">Deskripsi</th>
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
                ajax: "{{ route('shift.json') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'time_in',
                        name: 'time_in'
                    },
                    {
                        data: 'time_out',
                        name: 'time_out',
                    },
                    {
                        data: 'is_fixed',
                        name: 'is_fixed',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<i class="bx bx-check fs-3 text-success"></i>';
                            } else {
                                return '<i class="bx bx-x fs-3 text-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'is_night_shift',
                        name: 'is_night_shift',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<i class="bx bx-check fs-3 text-success"></i>';
                            } else {
                                return '<i class="bx bx-x fs-3 text-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });
    </script>
@endpush
