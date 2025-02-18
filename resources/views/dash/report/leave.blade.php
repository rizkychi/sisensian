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
                            <table id="dtx" class="table table-sm table-bordered w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" rowspan="2" class="text-center align-middle" style="width: 0px">No</th>
                                        <th scope="col" rowspan="2" class="text-center align-middle">Nama</th>
                                        <th scope="col" colspan="{{ count($date_range) }}" class="text-center align-middle">{{ $start_date->translatedFormat('F Y') }}</th>
                                    </tr>
                                    <tr>
                                        @foreach ($date_range as $range)
                                            <th scope="col" class="text-center">{{ $range->format('d') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-nowrap cnt">{{ $employee->name }}</td>
                                            @foreach ($date_range as $range)
                                                @php
                                                    $x = $leaves->where('employee_id', $employee->id)
                                                        ->where('start_date', '<=', $range->format('Y-m-d'))
                                                        ->where('end_date', '>=', $range->format('Y-m-d'))
                                                        ->first();
                                                    $tag = $x->leave_type ?? '-';

                                                    if ($employee->category == 'regular') {
                                                        $schedule = $employee->schedule->where('day_of_week', Str::lower($range->format('l')))->first();
                                                        if (!$schedule) {
                                                            $tag = 'L';
                                                        }
                                                    } else if ($employee->category == 'shift') {
                                                        $schedule = $employee->schedule->where('date', $range->format('Y-m-d'))->first();
                                                        if (!$schedule) {
                                                            $tag = 'L';
                                                        }
                                                    }
                                                @endphp
                                                <td scope="col" class="text-center">{{ $tag }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ count($date_range) + 2 }}">
                                            <div class="d-flex flex-column">
                                                <span>Keterangan:</span>
                                                <span>L = Libur</span>
                                                @foreach ($leave_type as $key => $type)
                                                    <span>{{ $key }} = {{ $type }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
                "responsive": true,
                "columnDefs": [{
                    "targets": 0,
                    "width": "10px"
                }],
                dom: "Bfrtip",
                buttons: {
                    dom: {
                        button: {
                            tag: 'button',
                            className: ''
                        }
                    },
                    buttons: [
                        // {
                        //     extend: 'excel',
                        //     className: 'btn btn-sm btn-soft-success',
                        //     text: '<span class=""><i class="mdi mdi-file-excel"></i>  Excel</span>',
                        //     titleAttr: 'Excel',
                        // },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-soft-danger',
                            text: '<span class=""><i class="mdi mdi-file-pdf-box"></i>  PDF</span>',
                            titleAttr: 'PDF',
                            orientation: 'landscape',
                            customize: function (doc) {
                                var tbl = "dtx";
                                var foot = $('#' + tbl + ' tfoot').text();
                                doc.content[1].table.body.push([{ text: foot, colSpan: doc.content[1].table.body[0].length, alignment: 'left' }]);
                            },
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-soft-warning',
                            text: '<span class=""><i class="mdi mdi-printer"></i>  Print</span>',
                            titleAttr: 'Print',
                            customize: function (win) {
                                $(win.document.body).find('table').addClass('display').css('font-size', '9pt');
                                $(win.document.body).find('table').css('width', '100%');
                                // $(win.document.body).find('table').css('text-align', 'center');
                                $(win.document.body).css('text-align', 'center');
                                $(win.document.body).css('width', '100%');
                                $(win.document.body).css('margin', '0 auto');
                                var tbl = "dtx";
                                var headrows = $('#'+tbl+' thead:first tr').length;
                                var footrows = $('#'+tbl+' tfoot:first tr').length;
                                if (headrows > 1) {
                                    $(win.document.body).find("table").find("thead").empty();
                                    $(win.document.body).find("table").find("thead").append($('#'+tbl+' thead:first').html());
                                }
                                $(win.document.body).find("table").find("tbody").append($('#'+tbl+' tfoot:first').html());
                            }
                        }
                    ],
                }
            });
            $(".tanggal").flatpickr({
                defaultDate: "{{ old('date_period', @$_GET['date_period']) }}",
                disableMobile: true,
                altInput: true,
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
