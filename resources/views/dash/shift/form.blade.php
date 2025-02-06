@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ $route_name }}" method="post">
                @csrf
                @isset($data)
                    @method('PUT')
                @endisset
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $route_label }} {{ $title }}</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Shift</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama Shift" value="{{ old('name', @$data->name) }}" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="time_in" class="form-label">Waktu Masuk</label>
                                <input type="text" class="form-control" id="time_ins" name="time_in"
                                    data-provider="timepickr"
                                    data-time-inline="{{ old('time_in', @$data->time_in) ?? '00:00' }}"
                                    data-time-hrs="true" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="time_out" class="form-label">Waktu Pulang</label>
                                <input type="text" class="form-control" id="time_outs" name="time_out"
                                    data-provider="timepickr"
                                    data-time-inline="{{ old('time_out', @$data->time_out) ?? '00:00' }}"
                                    data-time-hrs="true" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi">{{ old('description', @$data->description) }}</textarea>
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="hstack gap-5">
                            <div class="form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="customSwitchsizemd" name="is_fixed"
                                    {{ old('is_fixed', @$data->is_fixed) ?? !isset($data) ? 'checked' : '' }}>
                                <label class="form-check-label" for="customSwitchsizemd">Shift Tetap/Reguler?</label>
                            </div>
                            {{-- <div class="form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="switchshiftnight" name="is_night_shift"
                                    {{ old('is_night_shift', @$data->is_night_shift) ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchshiftnight">Shift Malam/Melewati Hari?</label>
                            </div> --}}
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                            </button>
                            <a href="{{ route('shift.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('styles')
    <style>
        .flatpickr-calendar.inline {
            top: 0;
        }
    </style>
@endpush
