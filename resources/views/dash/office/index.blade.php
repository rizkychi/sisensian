@extends('master.dashboard-master')
@section('title', 'Kantor')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Radius dan Lokasi Kantor</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table id="dtx" class="display table table-bordered dt-responsive w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 46px;">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Latitude</th>
                                        <th scope="col">Longitude</th>
                                        <th scope="col">Radius (m)</th>
                                        <th scope="col" style="width: 150px;">Action</th>
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
                        data: 'lat',
                        name: 'lat'
                    },
                    {
                        data: 'long',
                        name: 'long'
                    },
                    {
                        data: 'radius',
                        name: 'radius'
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
