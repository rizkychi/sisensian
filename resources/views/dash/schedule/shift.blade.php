@extends('master.dashboard-master')
@section('title', $title)

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
                            <div class="col-md-4 mb-3 mb-md-0">
                                {{-- <label for="date_period" class="form-label">Tanggal Cuti</label> --}}
                                <input type="text" class="form-control" name="date_period" placeholder="Pilih tanggal" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-range-date="true" data-allow-input="true" data-default-date="{{ old('date_period', @$_GET['date_period']) }}" required>
                                {{-- <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date', @$_GET['start_date']) }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date', @$_GET['end_date']) }}"> --}}
                            </div>
                            <div class="col-auto mb-md-0 align-self-end justify-content-between d-flex">
                                <button type="submit" class="btn btn-info btn-label waves-effect waves-light">
                                    <i class="ri-filter-2-fill label-icon align-middle fs-4"></i> Filter
                                </button>
                                {{-- <a href="{{ route('regular.create') }}" class="btn btn-primary btn-label waves-effect waves-light ms-3">
                                    <i class="ri-add-fill label-icon align-middle fs-4"></i> Tambah
                                </a> --}}
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtx" class="table table-sm table-bordered w-100">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" rowspan="2" class="text-center align-middle" style="width: 0px">No</th>
                                    <th scope="col" rowspan="2" class="text-center align-middle">Tanggal</th>
                                    <th scope="col" colspan="{{ count(@$shifts) }}" class="text-center align-middle">Jadwal</th>
                                </tr>
                                <tr>
                                    @foreach (@$shifts as $shift)
                                        <th scope="col" class="text-center" data-shift-id="{{ $shift->id }}">{{ $shift->name }}<br />{{ $shift->time_in }} - {{ $shift->time_out }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($show)
                                    @php
                                        $no = 1;
                                        // $start_date = \Carbon\Carbon::parse(request('start_date'));
                                        // $end_date = \Carbon\Carbon::parse(request('end_date'));
                                        $start_date = \Carbon\Carbon::parse($start_date);
                                        $end_date = \Carbon\Carbon::parse($end_date);

                                        if ($start_date && $end_date) {
                                            for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
                                                echo '<tr>';
                                                echo '<td class="text-center pt-2"><span>' . $no++ . '</span></td>';
                                                echo '<td><div class="d-flex justify-content-between align-items-center">';
                                                echo '<span class="date-label">' . $date->translatedFormat('l, d F Y') . '</span>';
                                                echo '<div class="col-auto">';
                                                echo '<button data-date="'.$date->format('Y-m-d').'" class="btn btn-sm btn-soft-primary btn-icon waves-effect waves-light btn-clone-employee" title="Salin"><i class="bx bxs-copy-alt fs-6"></i></button>';
                                                echo '</div>';
                                                echo '</div></td>';
                                                
                                                foreach (@$shifts as $shift) {
                                                    $shiftSchedule = $schedule->where('date', $date->format('Y-m-d'))->where('shift_id', $shift->id);
                                                    
                                                    echo '<td>';
                                                    echo '<div class="d-flex justify-content-between">';
                                                    echo '<div class="col-auto">';
                                                    if ($shiftSchedule->count() > 0) {
                                                        foreach ($shiftSchedule as $sch) {
                                                            echo '<span class="badge bg-info">' . $sch->employee->name . '</span><br />';
                                                        }
                                                    }
                                                    echo '</div>';
                                                    echo '<div class="col-auto">';
                                                    echo '<button data-shift-id="'.$shift->id.'" data-date="'.$date->format('Y-m-d').'" class="btn btn-sm btn-warning btn-icon waves-effect waves-light btn-add-employee" title="Edit"><i class="bx bxs-pencil fs-6"></i></button>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                }
                                                echo '</tr>';
                                            }
                                        } 
                                    @endphp
                                @else
                                    <tr>
                                        <td colspan="{{ count(@$shifts) + 2 }}" class="text-center">-</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <!-- Grids in modals -->
    <div class="modal fade" id="shiftModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="shiftModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftModalLabel">Shift Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="shiftForm">
                        @csrf
                        <input type="hidden" name="office_id" value="{{ old('office_id', @$_GET['office_id']) }}">
                        <input type="hidden" name="shift_id" value="">
                        <input type="hidden" name="date" value="">
                        <div class="row mb-3">
                            <div class="col-12">
                                <select class="form-select" id="employee_id" name="employee_id[]" data-placeholder="Pilih Karyawan" multiple required>
                                    @foreach ($employee as $e)
                                        <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <a href="{{ route('sift.delete') }}" class="btn btn-soft-danger btn-delete">Hapus</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
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

    <!-- Grids in modals -->
    <div class="modal fade" id="copyModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="copyModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="copyModalLabel">Salin Shift Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="copyForm">
                        @csrf
                        <input type="hidden" name="office_id" value="{{ old('office_id', @$_GET['office_id']) }}">
                        <input type="hidden" name="date" value="">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="leave_date" class="form-label">Salin ke tanggal</label>
                                <input type="text" class="form-control" name="to_date" placeholder="Pilih tanggal" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-allow-input="true" required>
                            </div>
                        </div>
                        
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div id="copyloading" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
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
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            // init select2
            $('#office_id').select2();
            const employeeChoices = new Choices($('#employee_id')[0], {
                removeItemButton: true,
            });

            // schedule date
            // const dateInput = document.querySelector('input[name="date_period"]');
            // const startInput = document.getElementById('start_date');
            // const endInput = document.getElementById('end_date');

            // dateInput.addEventListener('change', function () {
            //     const dates = dateInput.value.split(' to ');
            //     if (dates.length === 2) {
            //         const startDate = flatpickr.parseDate(dates[0], "Y-m-d");
            //         const endDate = flatpickr.parseDate(dates[1], "Y-m-d");
            //         const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            //         startInput.value = dates[0];
            //         endInput.value = dates[1];
            //     } else {
            //         startInput.value = dates[0];
            //         endInput.value = dates[0];
            //     }
            // });

            // Show modal on button click
            $(document).on('click', '.btn-add-employee', function() {
                $('#loading').hide();
                var shiftId = $(this).data('shift-id');
                var date = $(this).data('date');
                var shiftName = $(this).closest('table').find('th[data-shift-id="' + shiftId + '"]').text();
                var dateName = $(this).closest('tr').find('.date-label').text();
                $('#shiftForm').find('input[name="shift_id"]').val(shiftId);
                $('#shiftForm').find('input[name="date"]').val(date);
                $('#shiftModalLabel').html(shiftName + '<br /> (' + dateName + ')');
                $('#shiftForm').find('select[name="employee_id[]"]').val(null).trigger('change');
                employeeChoices.removeActiveItems();

                // Fetch employees for the selected date and shift_id
                $.ajax({
                    url: '{{ route('sift.get.employee') }}/' + date + '/' + shiftId,
                    type: 'GET',
                    success: function(response) {
                        var employeeIds = response.map(function(employee) {
                            return employee.id.toString();
                        });
                        employeeChoices.setChoiceByValue(employeeIds);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });

                $('#shiftModal').modal('show');
            });

            // Submit form
            $('#shiftForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = '{{ route('sift.store') }}';
                var data = form.serialize();
                var loading = $('#loading');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        loading.show();
                    },
                    success: function(response) {
                        loading.hide();
                        $('#shiftModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        loading.hide();
                    }
                });
            });

            // Delete schedule
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var form = $('#shiftForm');
                var url = $(this).attr('href');
                var data = form.serialize();
                var loading = $('#loading');

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, hapus shift ini!",
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButton: 'btn btn-danger w-xs mt-2'
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: data,
                            beforeSend: function() {
                                loading.show();
                            },
                            success: function(response) {
                                loading.hide();
                                $('#shiftModal').modal('hide');
                                location.reload();
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                loading.hide();
                            }
                        });
                    }
                });
            });

            // Show modal on button click
            $(document).on('click', '.btn-clone-employee', function() {
                $('#copyloading').hide();
                var date = $(this).data('date');
                var dateName = $(this).closest('tr').find('.date-label').text();
                $('#copyForm').find('input[name="date"]').val(date);
                $('#copyModalLabel').html('Salin Shift Karyawan (' + dateName + ')');

                $('#copyModal').modal('show');
            });

            // Submit form
            $('#copyForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = '{{ route('sift.copy') }}';
                var data = form.serialize();
                var loading = $('#copyloading');

                // reset date
                form.find('input[name="to_date"]').val('');

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data akan disalin ke tanggal tujuan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, salin shift ini!",
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButton: 'btn btn-danger w-xs mt-2'
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: data,
                            beforeSend: function() {
                                loading.show();
                            },
                            success: function(response) {
                                loading.hide();
                                $('#copyModal').modal('hide');
                                location.reload();
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                loading.hide();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush