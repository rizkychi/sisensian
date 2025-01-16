@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h5 class="card-title mb-0 flex-grow-1">Data Karyawan</h5>
                    <a href="{{ route('employee.create') }}" class="btn btn-primary btn-label waves-effect waves-light">
                        <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                    </a>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtx" class="display table align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 0px;">No</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No. HP</th>
                                    <th scope="col">Kantor</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 0px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div><!--end row-->
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#dtx').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "print", "pdf"],
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee.json') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'id_number',
                        name: 'id_number'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'office_name',
                        name: 'office_name',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge badge-border bg-success-subtle text-success">Aktif</span>';
                            } else {
                                return '<span class="badge badge-border bg-danger-subtle text-danger">Tidak Aktif</span>';
                            }
                        }
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
