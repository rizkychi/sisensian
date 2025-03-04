@extends('master.dashboard-master')
@section('title', "$route_label $title")

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
                                <label for="name" class="form-label">Nama Hari Libur</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama Hari Libur" value="{{ old('name', @$data->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="text" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-altInput="true" id="date" name="date" placeholder="Tanggal" value="{{ old('date', @$data->date) }}" required>
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="form-check form-switch form-switch-md" dir="ltr">
                            <input type="checkbox" class="form-check-input" id="customSwitchsizemd" name="is_day_off"
                            {{ old('is_day_off', @$data->is_day_off) ?? !isset($data) ? 'checked' : '' }}>
                            <label class="form-check-label" for="customSwitchsizemd">Cuti?</label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                            </button>
                            <a href="{{ route('holiday.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection
