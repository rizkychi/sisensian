@extends('master.dashboard-master')
@section('title', "$title $route_label")

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header align-items-end">
                    <form method="get">
                        <div class="row justify-content-md-start justify-content-center">
                            <div class="col-auto mb-2">
                                <h4 class="card-title">Penjadwalan Jadwal Reguler</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                {{-- <label for="office_id" class="form-label">Kantor</label> --}}
                                <select class="form-select" id="office_id" name="office_id" required>
                                    <option value="" disabled selected>Pilih Kantor</option>
                                    @foreach ($office as $ofc)
                                        <option value="{{ $ofc->id }}"
                                            {{ old('office_id', @$_GET['office_id']) == $ofc->id ? 'selected' : '' }}>
                                            {{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-md-0 align-self-end justify-content-between d-flex">
                                <button type="submit" class="btn btn-info btn-label waves-effect waves-light">
                                    <i class="ri-filter-2-fill label-icon align-middle fs-4"></i> Filter
                                </button>
                                <a href="{{ route('regular.create') }}" class="btn btn-primary btn-label waves-effect waves-light ms-3">
                                    <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtx" class="table table-sm w-100">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" rowspan="2" class="text-center align-middle" style="width: 0px">No</th>
                                    <th scope="col" rowspan="2" class="text-center align-middle">Nama</th>
                                    <th scope="col" colspan="7" class="text-center align-middle">Hari</th>
                                    <th scope="col" rowspan="2" class="text-center align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    @foreach ($days as $d => $day)
                                        <th scope="col" class="text-center">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee as $key => $empl)
                                    <tr>
                                        <td class="align-middle text-end">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $empl->name }}</td>
                                        @foreach ($days as $d => $day)
                                            <td class="text-center align-middle">
                                                @php
                                                    $schedule = $empl->schedule->where('day_of_week', $d)->first();
                                                @endphp
                                                @if ($schedule)
                                                    {{ $schedule->shift->time_in }} - {{ $schedule->shift->time_out }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center">
                                            <a href="{{ route('regular.edit', ['regular' => $empl->id]) }}" class="btn btn-sm btn-warning btn-icon waves-effect waves-light" title="Edit"><i class="bx bxs-pencil fs-6"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('#office_id').select2();
            $('#employee_id').select2();
            $('#dtx').DataTable({
                columnDefs: [
                    { orderable: false, targets: [2, 3, 4, 5, 6, 7, 8, 9] }
                ]
            });
        });
    </script>
@endpush
