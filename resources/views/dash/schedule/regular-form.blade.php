@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form id="fieldForm" action="{{ $route_name }}" method="post">
                @csrf
                @isset($data)
                    @method('PUT')
                @endisset
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $route_label }} {{ $title }}</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <!-- warning Alert -->
                        <div class="alert alert-info material-shadow" role="alert">
                            <i class="ri-information-line fs-5 me-2 align-middle"></i>Hanya pilih <b>kantor</b> apabila ingin menambahkan jadwal ke <b>semua karyawan</b>.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="office_id" class="form-label">Kantor</label>
                                <select class="form-select" id="office_id" name="office_id" required {{ @$data ? 'disabled':'' }}>
                                    <option value="" disabled selected>Pilih Kantor</option>
                                    @foreach ($office as $ofc)
                                        <option value="{{ $ofc->id }}"
                                            {{ old('office_id', @$data->office->id) == $ofc->id ? 'selected' : '' }}>
                                            {{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Karyawan</label>
                                <select class="form-select" id="employee_id" name="employee_id" {{ @$data ? 'disabled':'' }}>
                                    <option value="" disabled selected>Pilih Karyawan</option>
                                    @if (@$data)
                                        <option value="{{ @$data->id }}" selected>{{ @$data->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div><!-- end row -->

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Tetapkan Jadwal Per Hari</label>
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 25%">Hari</th>
                                            <th style="width: 75%">Jadwal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($days as $key => $day)
                                            @php
                                                $schedule = @$data ? @$data->schedule->where('day_of_week', $key)->first() : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $day }}</td>
                                                <td>
                                                    <select class="form-select form-control" id="shift_id" name="shift_id[{{$key}}]" data-choices data-choices-removeItem>
                                                        <option value="" disabled selected>Pilih Jadwal</option>
                                                        @foreach ($shift as $sf)
                                                            <option value="{{ $sf->id }}" {{ @$schedule->shift->id == $sf->id ? 'selected':'' }}>{{ $sf->name }} ({{ $sf->time_in }} - {{ $sf->time_out }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                            </button>
                            <a href="{{ route('regular') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('scripts')
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('#office_id').select2();
            $('#employee_id').select2();

            $('#office_id').on('change', function() {
                var office_id = $(this).val();
                var url = "{{ route('employee.get') }}/"+office_id;

                $.get(url, function(response) {
                    $('#employee_id').empty();
                    $('#employee_id').append('<option value="" disabled selected>Pilih Karyawan</option>');
                    $.each(response, function(key, value) {
                        $('#employee_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                });
            });
        });
    </script>
@endpush
