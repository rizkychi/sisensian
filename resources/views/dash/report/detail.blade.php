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
                                <select class="form-select" id="office_id" name="office_id" required>
                                    <option value="" disabled selected>Pilih Kantor</option>
                                    @foreach ($offices as $ofc)
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
                            <div class="col-md-12 mb-3">
                                <label for="employee_id" class="form-label">Karyawan</label>
                                <select class="form-select" id="employee_id" name="employee_id" required>
                                    <option value="" disabled selected>Pilih Karyawan</option>
                                    @if ($employees)
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}"
                                                {{ old('employee_id', @$_GET['employee_id']) == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                            <table id="dtx" class="table table-bordered w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center align-middle" style="width: 0px">No</th>
                                        <th scope="col" class="text-center align-middle">ID</th>
                                        <th scope="col" class="text-center align-middle">Nama</th>
                                        <th scope="col" class="text-center align-middle">Tanggal</th>
                                        <th scope="col" class="text-center align-middle">Hari</th>
                                        <th scope="col" class="text-center align-middle" style="width: 200px">Jam Masuk</th>
                                        <th scope="col" class="text-center align-middle" style="width: 200px">Jam Pulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{ $attendance->employee->id_number }}</td>
                                            <td class="text-center align-middle">{{ $attendance->employee->name }}</td>
                                            <td class="text-center align-middle">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}</td>
                                            <td class="text-center align-middle">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l') }}</td>
                                            <td class="text-center align-middle">
                                                <p class="m-0">{{ $attendance->check_in_time ?? '-' }}</p>
                                                <p class="m-0">{{ $attendance->check_in_address}}</p>
                                            </td>
                                            <td class="text-center align-middle">
                                                <p class="m-0">{{ $attendance->check_out_time ?? '-' }}</p>
                                                <p class="m-0">{{ $attendance->check_out_address}}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $(".tanggal").flatpickr({
                defaultDate: "{{ old('date_period', @$_GET['date_period']) }}",
                disableMobile: true,
                altInput: true,
                allowInput: true,
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        dateFormat: "Y-m", //defaults to "F Y"
                        altFormat: "F Y", //defaults to "F Y"
                    })
                ]
            });

            $('#office_id').select2({
                placeholder: 'Pilih Kantor',
            });

            $('#employee_id').select2({
                placeholder: 'Pilih Karyawan',
            });

            $('#office_id').on('change', function() {
                var officeId = this.value;
                $.ajax({
                    url: '{{ route('report.getemployee') }}',
                    type: 'GET',
                    data: { office_id: officeId },
                    success: function(response) {
                        var employeeSelect = $('#employee_id');
                        employeeSelect.empty();
                        employeeSelect.append('<option value="" disabled selected>Pilih Karyawan</option>');
                        $.each(response, function(key, employee) {
                            employeeSelect.append('<option value="' + employee.id + '">' + employee.name + '</option>');
                        });
                    }
                });
            });

            @if ($show)
            $('#dtx').DataTable({
                // "paging": false,
                // "ordering": false,
                "info": false,
                // "autoWidth": false,
                // "responsive": true,
                "columnDefs": [{
                    "targets": 0,
                    "width": "10px"
                }],
                // scrollX: true,
                dom: "Bfrtip",
                buttons: {
                    dom: {
                        button: {
                            tag: 'button',
                            className: ''
                        }
                    },
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-soft-success',
                            text: '<span class=""><i class="mdi mdi-file-excel"></i>  Excel</span>',
                            titleAttr: 'Excel',
                            title: 'Detail Presensi Karyawan',
                            messageTop: 'Periode: {{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}',
                            filename: function() {
                                return 'Detail Presensi Karyawan Periode ' + '{{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}';
                            },
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                $('row c[r^="F"] t', sheet).each(function() {
                                    var text = $(this).text();
                                    $(this).text(text.replace(/                                                 /g, '\n'));
                                });
                                $('row c[r^="G"] t', sheet).each(function() {
                                    var text = $(this).text();
                                    $(this).text(text.replace(/                                                 /g, '\n'));
                                });
                                $('row c[r^="F"], row c[r^="G"]', sheet).attr('s', '55'); // Apply wrap text style

                                // Apply bold to F3 and G3
                                $('row c[r="F3"]', sheet).attr('s', '2');
                                $('row c[r="G3"]', sheet).attr('s', '2');
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-soft-danger',
                            text: '<span class=""><i class="mdi mdi-file-pdf-box"></i>  PDF</span>',
                            titleAttr: 'PDF',
                            title: 'Detail Presensi Karyawan',
                            orientation: 'landscape',
                            messageTop: 'Periode: {{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}',
                            filename: function() {
                                return 'Detail Presensi Karyawan Periode ' + '{{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}';
                            },
                            customize: function(doc) {
                                var tableBody = doc.content.find(function(content) {
                                    return content.table;
                                }).table.body;
                                tableBody.forEach(function(row, index) {
                                    if (index > 0) { // Skip the header row
                                        row[5].text = row[5].text.replace(' ', '\n');
                                        row[6].text = row[6].text.replace(' ', '\n');
                                    }
                                });
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-soft-warning',
                            text: '<span class=""><i class="mdi mdi-printer"></i>  Print</span>',
                            titleAttr: 'Print',
                            title: 'Detail Presensi Karyawan',
                            messageTop: 'Periode: {{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}',
                            filename: function() {
                                return 'Detail Presensi Karyawan Periode ' + '{{ $date_range->first()->translatedFormat('d F Y') }} - {{ $date_range->last()->translatedFormat('d F Y') }}';
                            },
                            customize: function(win) {
                                $(win.document.body).find('td:nth-child(6)').each(function() {
                                    var text = $(this).text();
                                    $(this).html(text.replace(' ', '<br>'));
                                });
                                $(win.document.body).find('td:nth-child(7)').each(function() {
                                    var text = $(this).text();
                                    $(this).html(text.replace(' ', '<br>'));
                                });
                            }
                        }
                    ],
                }
            });
            @endif
        });
    </script>
@endpush
