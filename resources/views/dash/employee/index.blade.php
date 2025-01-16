@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h5 class="card-title mb-0 flex-grow-1">Data Karyawan</h5>
                    <button class="btn btn-info btn-label waves-effect waves-light me-3" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="bx bx-import label-icon align-middle fs-5"></i> Import
                    </button>
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

@push('modals')
    <!-- Grids in modals -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-modal="true">
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
@endpush

@push('scripts')
    <script>
        $(function() {
            var table = $('#dtx').DataTable({
                dom: "Bfrtip",
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            modifier: {
                                search: 'none',
                                order: 'applied'
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            modifier: {
                                search: 'none',
                                order: 'applied'
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            modifier: {
                                search: 'none',
                                order: 'applied'
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            modifier: {
                                search: 'none',
                                order: 'applied'
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            modifier: {
                                search: 'none',
                                order: 'applied'
                            },
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    return data;
                                }
                            }
                        }
                    }
                ],
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

            $('#importForm').on('submit', function() {
                $('#loading').show();
                $('#importModal').modal({ backdrop: 'static', keyboard: false });
            });

            $(document).ajaxStop(function() {
                $('#loading').hide();
                $('#importModal').modal('hide');
            });
        });
    </script>
@endpush
