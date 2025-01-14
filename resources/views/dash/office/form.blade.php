@extends('master.dashboard-master')
@section('title', 'Kantor')

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
                            <table id="dtx" class="table table-nowrap align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 0px;">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">Koordinat</th>
                                        <th scope="col">Radius (m)</th>
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
            
    </script>
@endpush
