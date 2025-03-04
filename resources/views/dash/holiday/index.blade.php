@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-end">
                    <form method="get">
                        <div class="row justify-content-md-start justify-content-center">
                            <div class="col-auto mb-2">
                                <h4 class="card-title">Hari Libur Nasional</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="row justify-content-md-start justify-content-center">
                                    <div class="col-6">
                                        <select class="form-select" id="year" name="year" data-choices required>
                                            <option value="" disabled selected>Pilih Tahun</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}"
                                                    {{ old('year', (@$_GET['year'] ?? date('Y'))) == $year ? 'selected' : '' }}>
                                                    {{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-info btn-label waves-effect waves-light">
                                            <i class="ri-filter-2-fill label-icon align-middle fs-4"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-md-0 align-self-end justify-content-md-end justify-content-center d-flex flex-grow-1">
                                    <button id="syncButton" type="button" class="btn btn-success btn-label waves-effect waves-light">
                                        <i class="mdi mdi-calendar-sync label-icon align-middle fs-4"></i> Sinkronisasi
                                    </button>
                                    <a href="{{ route('holiday.create') }}" class="btn btn-primary btn-label waves-effect waves-light ms-3">
                                        <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table id="dtx" class="table align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 0px;">No</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Cuti?</th>
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

@push('modals')
    <!-- Grids in modals -->
    <div class="modal fade" id="syncModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="syncModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="syncModalLabel">Sinkronisasi Data Hari Libur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('holiday.sync') }}" method="post" enctype="multipart/form-data" id="syncForm">
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <select class="form-select" name="year" data-choices required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Sinkronisasi</button>
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('holiday.json') }}",
                    data: function(d) {
                        d.year = $('#year').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date',
                        render: function(data, type, row) {
                            var date = new Date(data);
                            var options = { day: '2-digit', month: 'long', year: 'numeric' };
                            return date.toLocaleDateString('id-ID', options);
                        }

                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'is_day_off',
                        name: 'is_day_off',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="badge badge-border bg-success-subtle text-success">Ya</span>';
                            } else {
                                return '<span class="badge badge-border bg-danger-subtle text-danger">Tidak</span>';
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

            $('#syncButton').on('click', function() {
                $('#syncModal').modal('show');
                $('#loading').hide();
            });

            $('#syncForm').on('submit', function() {
                $('#loading').show();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        year: $(this).find('select[name="year"]').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#loading').hide();
                        $('#syncModal').modal('hide');
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sinkronisasi Berhasil',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sinkronisasi Gagal',
                                text: response.message
                            });
                        }
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'Sinkronisasi Gagal',
                            text: xhr.responseJSON.message
                        });
                    }
                });
                return false;
            });
        });
    </script>
@endpush
