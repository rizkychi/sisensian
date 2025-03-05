@extends('master.dashboard-master')
@section('title', $title)
@section('content')
    
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-success-subtle border-success border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users icon-dual-success"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_employee }}</h5>
                    <p class="mb-0 text-muted">Karyawan</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-warning-subtle border-warning border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text icon-dual-warning"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_leave }}</h5>
                    <p class="mb-0 text-muted">Pengajuan Cuti</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-danger-subtle border-danger border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin icon-dual-danger"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_office }}</h5>
                    <p class="mb-0 text-muted">Lokasi Kantor</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body d-flex gap-3 align-items-center">
                <div class="avatar-sm">
                    <div class="avatar-title border bg-primary-subtle border-primary border-opacity-25 rounded-2 fs-17">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart icon-dual-primary"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fs-15">{{ $c_attendance }}</h5>
                    <p class="mb-0 text-muted">Presensi Bulan Ini</p>
                </div>
            </div>
        </div>
    </div><!--end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div id="external-events">
                            <p class="text-muted">Keterangan warna dalam kalender</p>
                            <div class="external-event bg-info-subtle text-info" data-class="bg-info-subtle">
                                <i class="mdi mdi-checkbox-blank-circle me-2"></i>Hari Kerja
                            </div>
                            <div class="external-event bg-danger-subtle text-danger" data-class="bg-danger-subtle">
                                <i class="mdi mdi-checkbox-blank-circle me-2"></i>Hari Libur
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <h5 class="mb-1">Event Mendatang</h5>
                    <p class="text-muted">Event beberapa hari ke depan</p>
                    <div class="pe-2 me-n1 mb-3" data-simplebar style="height: 400px">
                        <div id="upcoming-event-list">
                            @foreach ($upcoming as $up)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex mb-1">
                                            <div class="flex-grow-1"><i
                                                    class="mdi mdi-checkbox-blank-circle me-2 {{ $up->is_day_off ? 'text-danger' : 'text-info' }}"></i>
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($up->date)->translatedFormat('l, d F Y') }}</span>
                                            </div>
                                        </div>
                                        <p class="mb-0">{{ $up->name }}</p>
                                        <p class="text-muted text-truncate-two-lines mb-0"> </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->

            <div class="col-xl-9">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!--end row-->
    </div>
</div> <!-- end row-->

@endsection

@push('styles')
    <style>
        .fc-event {
            cursor: pointer;
        }
    </style>
@endpush

@push('modals')
    

<!-- Default Modals -->
    <div id="eventModal" class="modal fade" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <h5 class="fs-15" id="event-title">
                        Event Name
                    </h5>
                    <p class="text-muted mb-1" id="event-date"></p>
                    <p class="text-muted mb-1" id="event-holiday"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endpush

@push('scripts')
    <!-- calendar min js -->
    <script src="{{ asset('assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script>
        $(function() {
            var holidays = @json($holidays);

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: 'dayGridMonth',
                events: holidays,
                eventClick: function(info) {
                    $('#event-title').text(info.event.title);
                    $('#event-date').html('<i class="mdi mdi-calendar me-1"></i>' + info.event.start.toDateString());
                    if (info.event.classNames[0] == 'bg-danger-subtle') {
                        $('#event-holiday').html('<i class="mdi mdi-calendar-remove me-1"></i>Hari Libur');
                    } else {
                        $('#event-holiday').html('<i class="mdi mdi-calendar-check me-1"></i>Hari Kerja');
                    }
                    $('#eventModal').modal('show');
                }
            });

            calendar.render();
        })
    </script>
@endpush