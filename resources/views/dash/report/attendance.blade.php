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
                                <select class="form-select" id="office_id" name="office_id" data-choices data-choices-sorting-false required>
                                    <option value="" disabled selected>Pilih Kantor</option>
                                    <option value="all" {{ old('office_id', @$_GET['office_id']) == 'all' ? 'selected' : '' }} >- Semua Kantor -</option>
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
                            <table id="dtx" class="table table-sm nowrap table-bordered w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center align-middle" style="width: 0px">No</th>
                                        <th scope="col" class="text-center align-middle">Nama</th>
                                        <th scope="col" class="text-center align-middle">Kantor</th>
                                        @foreach ($date_range as $range)
                                            <th scope="col" class="text-center">{{ $range->format('d') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->office->name }}</td>
                                            @foreach ($date_range as $date)
                                                {{-- <td>{{ $attendances[$employee->id][$date->format('Y-m-d')] }}</td> --}}
                                                @if ($attendances[$employee->id][$date->format('Y-m-d')] == 'V' && $attendances_data[$employee->id][$date->format('Y-m-d')]->note != null)
                                                    <td class="text-center">{{ implode(', ', unserialize($attendances_data[$employee->id][$date->format('Y-m-d')]->note)) }}</td>                                                    
                                                @else
                                                    <td class="text-center">{{ $attendances[$employee->id][$date->format('Y-m-d')] }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-1 mt-3"><b>Keterangan</b></p>
                        <div class="row">
                            <div class="col-auto">
                                <table>
                                    <tr>
                                        <td style="width: 50px">V</td>
                                        <td>: Hadir Tepat Waktu</td>
                                    </tr>
                                    <tr>
                                        <td>L/LN</td>
                                        <td>: Libur/Libur Nasional</td>
                                    </tr>
                                    <tr>
                                        <td>TK</td>
                                        <td>: Tanpa Keterangan</td>
                                    </tr>
                                    @foreach ($leave_type as $key => $leave)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>: {{ $leave }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="col-auto">
                                <table>
                                    @foreach ($late_type as $key => $late)
                                        <tr>
                                            <td style="width: 50px">{{ $key }}</td>
                                            <td>: {{ $late }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="col-auto">
                                <table>
                                    @foreach ($early_type as $key => $early)
                                        <tr>
                                            <td style="width: 50px">{{ $key }}</td>
                                            <td class="text-nowrap">: {{ $early }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#dtx').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                // "responsive": true,
                "columnDefs": [{
                    "targets": 0,
                    "width": "10px"
                }],
                scrollX: true,
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
                            customize: function (xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                var lastRow = $('row', sheet).last();
                                var footer = '<row><c t="inlineStr"><is><t>Keterangan:</t></is></c></row>';
                                footer += '<row><c t="inlineStr"><is><t>V: Hadir Tepat Waktu</t></is></c></row>';
                                footer += '<row><c t="inlineStr"><is><t>L/LN: Libur/Libur Nasional</t></is></c></row>';
                                @foreach ($leave_type as $key => $leave)
                                    footer += '<row><c t="inlineStr"><is><t>{{ $key }}: {{ $leave }}</t></is></c></row>';
                                @endforeach
                                @foreach ($late_type as $key => $late)
                                    footer += '<row><c t="inlineStr"><is><t>{{ $key }}: {{ $late }}</t></is></c></row>';
                                @endforeach
                                @foreach ($early_type as $key => $early)
                                    footer += '<row><c t="inlineStr"><is><t>{{ $key }}: {{ $early }}</t></is></c></row>';
                                @endforeach
                                lastRow.after(footer);
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-soft-danger',
                            text: '<span class=""><i class="mdi mdi-file-pdf-box"></i>  PDF</span>',
                            titleAttr: 'PDF',
                            orientation: 'landscape',
                            customize: function (doc) {
                                doc.content.push({
                                    text: 'Keterangan:',
                                    margin: [0, 10, 0, 0]
                                });
                                doc.content.push({
                                    text: 'V: Hadir Tepat Waktu\nL/LN: Libur/Libur Nasional',
                                    margin: [0, 0, 0, 10]
                                });
                                @foreach ($leave_type as $key => $leave)
                                    doc.content.push({
                                        text: '{{ $key }}: {{ $leave }}',
                                        margin: [0, 0, 0, 10]
                                    });
                                @endforeach
                                @foreach ($late_type as $key => $late)
                                    doc.content.push({
                                        text: '{{ $key }}: {{ $late }}',
                                        margin: [0, 0, 0, 10]
                                    });
                                @endforeach
                                @foreach ($early_type as $key => $early)
                                    doc.content.push({
                                        text: '{{ $key }}: {{ $early }}',
                                        margin: [0, 0, 0, 10]
                                    });
                                @endforeach
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-soft-warning',
                            text: '<span class=""><i class="mdi mdi-printer"></i>  Print</span>',
                            titleAttr: 'Print',
                            customize: function (win) {
                                $(win.document.body).append('<div><b>Keterangan:</b></div>');
                                $(win.document.body).append('<div>V: Hadir Tepat Waktu</div>');
                                $(win.document.body).append('<div>L/LN: Libur/Libur Nasional</div>');
                                @foreach ($leave_type as $key => $leave)
                                    $(win.document.body).append('<div>{{ $key }}: {{ $leave }}</div>');
                                @endforeach
                                @foreach ($late_type as $key => $late)
                                    $(win.document.body).append('<div>{{ $key }}: {{ $late }}</div>');
                                @endforeach
                                @foreach ($early_type as $key => $early)
                                    $(win.document.body).append('<div>{{ $key }}: {{ $early }}</div>');
                                @endforeach
                            }
                        }
                    ],
                }
            });
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
        });
    </script>
@endpush
