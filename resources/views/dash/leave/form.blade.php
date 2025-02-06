@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form id="fieldForm" action="{{ $route_name }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $route_label }} {{ $title }}</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="id_number" class="form-label">Karyawan</label>
                                <input type="text" class="form-control" value="{{ @$data->id_number }} / {{ @$data->name }} / {{ @$data->office->name }}" disabled>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="id_number" class="form-label">Jenis Cuti</label>
                                <select class="form-select" id="leave_type" name="leave_type" required data-choices data-choices-sorting-false>
                                    <option value="">Pilih Jenis Cuti</option>
                                    @foreach ($leave_types as $key => $leave_type)
                                        <option value="{{ $key }}" {{ old('leave_type') == $key ? 'selected':'' }}>{{ $key }} - {{ $leave_type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="leave_date" class="form-label">Tanggal Cuti</label>
                                {{-- <input type="date" class="form-control" id="leave_date" name="leave_date" value="{{ old('leave_date') }}" required> --}}
                                <input type="text" class="form-control" id="leave_date" name="leave_date" placeholder="Pilih tanggal"
                                    data-provider="flatpickr"
                                    data-date-format="Y-m-d"
                                    data-altFormat="d-m-Y"
                                    data-range-date="true"
                                    data-minDate="today"
                                    data-allow-input="true"
                                    data-default-date="{{ old('leave_date') }}"
                                    required>
                                <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date') }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="leave_duration" class="form-label">Durasi Cuti</label>
                                <input type="text" class="form-control-plaintext" id="leave_duration" name="leave_duration" value="{{ old('leave_duration') ?? '0 hari' }}" readonly>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="reason" class="form-label">Alasan</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Alasan">{{ old('reason') }}</textarea>
                            </div>
                            
                        </div><!-- end row -->
                    </div><!-- end card-body -->

                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                            </button>
                            <a href="{{ route('leave.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const leaveDateInput = document.querySelector('input[name="leave_date"]');
            const leaveDurationInput = document.getElementById('leave_duration');
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');

            leaveDateInput.addEventListener('change', function () {
                const dates = leaveDateInput.value.split(' to ');
                if (dates.length === 2) {
                    const startDate = flatpickr.parseDate(dates[0], "Y-m-d");
                    const endDate = flatpickr.parseDate(dates[1], "Y-m-d");
                    const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                    leaveDurationInput.value = duration + ' hari';
                    startInput.value = dates[0];
                    endInput.value = dates[1];
                } else {
                    leaveDurationInput.value = '1 hari';
                    startInput.value = dates[0];
                    endInput.value = dates[0];
                }
            });
        });
    </script>
@endpush
