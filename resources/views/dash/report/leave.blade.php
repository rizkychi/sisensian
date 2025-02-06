@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header align-items-end">
                    <form method="get">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="office_id" class="form-label">Kantor</label>
                                <select class="form-select" id="office_id" name="office_id" data-choices required>
                                    <option value="" disabled selected>Pilih Kantor</option>
                                    @foreach ($office as $ofc)
                                        <option value="{{ $ofc->id }}"
                                            {{ old('office_id', @$_GET['office_id']) == $ofc->id ? 'selected' : '' }}>
                                            {{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="date_period" class="form-label">Periode</label>
                                <input type="text" class="form-control tanggal" name="date_period" placeholder="Pilih Periode" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-info btn-label waves-effect waves-light">
                                    <i class="ri-filter-2-fill label-icon align-middle fs-4"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->

                @if ($show)
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
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('assets/libs/flatpickr/plugins/monthSelect/style.css') }}" rel="stylesheet">
    <style>
        .flatpickr-monthSelect-months {
            margin: 2px;
        }
        .flatpickr-month {
            margin-bottom: 10px;
        }
        .flatpickr-current-month{
            font-size: 135%;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/plugins/monthSelect/index.js') }}"></script>
    <script>
        $(function() {
            flatpickr(".tanggal", {
                disableMobile: "true",
                defaultDate: "{{ old('date_period', @$_GET['date_period']) }}",
                altInput: true, // Enable altInput to show the altFormat
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        altFormat: "F Y", // Format for the displayed value
                        dateFormat: "Y-m", // Format for the actual value
                    })
                ]
            });
        });
    </script>
@endpush
