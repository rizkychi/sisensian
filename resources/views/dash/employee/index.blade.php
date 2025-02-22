@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex flex-column flex-md-row">
                    <h5 class="card-title mb-0 flex-grow-1">Data Karyawan</h5>
                    <div class="d-flex mt-3 mt-md-0">
                        <button class="btn btn-info btn-label waves-effect waves-light me-3" data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            <i class="bx bx-import label-icon align-middle fs-5"></i> Import
                        </button>
                        <a href="{{ route('employee.create') }}" class="btn btn-primary btn-label waves-effect waves-light">
                            <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                        </a>
                    </div>
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
                                    <th scope="col">Jabatan</th>
                                    <th scope="col">Kantor</th>
                                    <th scope="col">Jenis Jadwal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
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

@push('modals')
    <!-- Grids in modals -->
    <div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="importModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.import') }}" method="post" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-lg-3 align-self-center">
                                <label class="form-label mb-0">Template</label>
                            </div>
                            <div class="col-lg-9">
                                <a href="{{ route('employee.template') }}" target="_blank" class="btn btn-primary">Download</a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 align-self-center">
                                <label for="office_id" class="form-label mb-0">Kantor</label>
                            </div>
                            <div class="col-lg-9">
                                <select class="form-select" id="office_id" name="office_id" data-choices required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach ($office as $ofc)
                                        <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 align-self-center">
                                <label for="dateInput" class="form-label mb-0">File</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Import</button>
                        </div>
                    </form>
                    <div id="loading" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal with form -->

    <!-- Password modals -->
    <div class="modal fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="passwordModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Ganti Password Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.password') }}" method="post" enctype="multipart/form-data" id="passwordForm" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="employee_id" id="employee_id">
                        <div class="row mb-3">
                            <div class="col-3">
                                <p>ID</p>
                            </div>
                            <div class="col-9">
                                <p id="employeeId"></p>
                            </div>
                            <div class="col-3">
                                <p>Nama</p>
                            </div>
                            <div class="col-9">
                                <p id="employeeName"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="password" class="form-label">Password baru</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <div class="invalid-feedback">
                                    Password is required.
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="retypepassword" class="form-label">Ketik Ulang Password</label>
                                <input type="password" class="form-control" id="retypepassword" name="retypepassword"
                                    placeholder="Password" required>
                                <div class="invalid-feedback">
                                    Please retype the password.
                                </div>
                                <div class="invalid-feedback" id="passwordMismatch" style="display: none;">
                                    Passwords do not match.
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>

                    <script>
                        (function () {
                            'use strict'

                            var forms = document.querySelectorAll('.needs-validation')

                            Array.prototype.slice.call(forms)
                                .forEach(function (form) {
                                    form.addEventListener('submit', function (event) {
                                        if (!form.checkValidity()) {
                                            event.preventDefault()
                                            event.stopPropagation()
                                        }

                                        var password = document.getElementById('password').value;
                                        var retypepassword = document.getElementById('retypepassword').value;

                                        if (password !== retypepassword) {
                                            event.preventDefault()
                                            event.stopPropagation()
                                            document.getElementById('passwordMismatch').style.display = 'block';
                                        } else {
                                            document.getElementById('passwordMismatch').style.display = 'none';
                                        }

                                        form.classList.add('was-validated')
                                    }, false)
                                })
                        })()
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal with form -->
@endpush

@push('scripts')
    <script>
        $(function() {
            var table = $('#dtx').DataTable({
                dom: "Bfrtip",
                buttons: {
                    dom: {
                        button: {
                            tag: 'button',
                            className: ''
                        }
                    },
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-soft-success',
                            text: '<span class=""><i class="mdi mdi-file-excel"></i>  Excel</span>',
                            titleAttr: 'Excel',
                            action: newexportaction,
                            exportOptions: {
                                columns: ':not(:last-child)',
                            },
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-soft-danger',
                            text: '<span class=""><i class="mdi mdi-file-pdf-box"></i>  PDF</span>',
                            titleAttr: 'PDF',
                            action: newexportaction,
                            exportOptions: {
                                columns: ':not(:last-child)',
                            },
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-soft-warning',
                            text: '<span class=""><i class="mdi mdi-printer"></i>  Print</span>',
                            titleAttr: 'Print',
                            action: newexportaction,
                            exportOptions: {
                                columns: ':not(:last-child)',
                            },
                        }
                    ],
                },
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
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'office.name',
                        name: 'office.name',
                    },
                    {
                        data: 'category',
                        name: 'category',
                        render: function(data, type, row) {
                            if (data == 'regular') {
                                return '<span class="badge badge-border bg-primary-subtle text-primary">Reguler</span>';
                            } else if (data == 'shift') {
                                return '<span class="badge badge-border bg-secondary-subtle text-secondary">Shift</span>';
                            } 
                        }
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
                        data: 'user.username',
                        name: 'user.username',
                        visible: false,
                        // render: function (data, type, row) {
                        //     return data ? data.username : '';
                        // }
                    },
                    {
                        data: 'user.email',
                        name: 'user.email',
                        visible: false,
                        // render: function (data, type, row) {
                        //     return data ? data.email : '';
                        // }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            $('#importForm').on('submit', function() {
                $('#loading').show();
                $('#importModal').modal({ backdrop: 'static', keyboard: false });
            });

            $(document).ajaxStop(function() {
                $('#loading').hide();
                $('#importModal').modal('hide');
            });

            // Password Modal
            $('#dtx').on('click', '.modalPassword', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var data = table.row($(this).parents('tr')).data();
                $('#employeeId').text(': ' + data.id_number);
                $('#employeeName').text(': ' + data.name);
                $('#employee_id').val(data.id);
                $('#passwordModal').modal('show');
            });
        });
    </script>
@endpush
