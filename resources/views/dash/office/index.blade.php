@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Radius dan Lokasi Kantor</h4>
                    <a href="{{ route('office.create') }}" class="btn btn-primary btn-label waves-effect waves-light">
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
                                        <th scope="col">Alamat</th>
                                        <th scope="col">Koordinat</th>
                                        <th scope="col">Radius (m)</th>
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
                ajax: "{{ route('office.json') }}",
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
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'lat',
                        name: 'lat',
                        orderable: false,
                        render: function(data, type, row) {
                            link = 'https://www.google.com/maps/search/?api=1&query=' + row.lat + ',' + row.long;
                            el = '<span class="text-nowrap"><a href="' + link + '" target="_blank" class="btn btn-info btn-icon btn-sm waves-effect waves-light me-2"><i class="bx bxs-map fs-6"></i></a>'
                            el += row.lat + ', ' + row.long + '</span>';
                            return el
                        }
                    },
                    {
                        data: 'radius',
                        name: 'radius'
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
