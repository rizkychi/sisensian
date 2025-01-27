@extends('master.dashboard-master')
@section('title', 'Presensi')

@section('content')
<div class="position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg profile-setting-img">
        <div id="maps" style="height: 260px; position: relative; z-index: 1;">
            <button id="locate-btn" type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                <i class="bx bxs-map label-icon align-middle rounded-pill fs-16 me-2"></i> Lokasi Saya
            </button>
        </div>
    </div>
</div>

<div class="row" style="position: relative; z-index: 2;">
    <div class="col-xxl-3">
        <div class="card mt-n5">
            <div class="card-body p-3">
                <div class="text-center">
                    <h5 class="fs-2 mb-1"><span id="currentTime"></span> WIB</h5>
                    <p class="text-muted mb-0">{{ $today->translatedFormat('l, d F Y') }}</p>
                    <span class="badge border border-{{ $label->color }} text-{{ $label->color }} mt-2">{{ $label->text }}</span>
                </div>
            </div>
        </div>
        <!--end card-->

        <div class="row mb-2">
            <div class="col-12 text-center mb-3">
                <h5 id="locationLabel" class="fs-6"></h5>
                <p id="address" class="text-muted mb-0" style="font-size: 8pt"></p>
            </div>
            @if ($label->is_visible)
                <div class="col-12 justify-content-center d-flex mb-3">
                    <div id="button-background" style="display: none;">
                        <span class="slide-text">Geser untuk presensi</span>
                        <div id="slider">
                            <i id="locker" class="mdi mdi-fingerprint"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="col p-1">
                        <div class="row align-items-center justify-content-center flex-nowrap g-3">
                            <div class="col-auto">
                                <i class="{{ $login->text_color }} bx bx-log-in fs-1"></i>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col text-center">
                                        <h5 class="{{ $login->text_color }} mb-0 fs-6 text-nowrap">{{ $schedule->shift->time_in ?? '00:00' }} | <span class="fw-normal">{{ $today->translatedFormat('l, d F Y') }}</span></h5>
                                        <p class="{{ $login->text_color }} mb-0" style="font-size: 10px">Belum melakukan presensi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="{{ $login->text_color }} las {{ $login->icon }} fs-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="border-top"></div>
                    <div class="col p-1">
                        <div class="row align-items-center justify-content-center flex-nowrap g-3">
                            <div class="col-auto">
                                <i class="{{ $logout->text_color }} bx bx-log-out fs-1"></i>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col text-center">
                                        <h5 class="{{ $logout->text_color }} mb-0 fs-6 text-nowrap">{{ $schedule->shift->time_out ?? '00:00' }} | <span class="fw-normal">{{ $today->translatedFormat('l, d F Y') }}</span></h5>
                                        <p class="{{ $logout->text_color }} mb-0" style="font-size: 10px">Belum melakukan presensi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="{{ $logout->text_color }} las {{ $logout->icon }} fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end card-->

        <div class="row mb-4 justify-content-center">
            <div class="col-auto hstack gap-4">
                <a href="#" class="btn btn-soft-primary btn-label waves-effect waves-light rounded-pill">
                    <i class="ri-history-line label-icon align-middle rounded-pill fs-4 me-2"></i> Riwayat
                </a>
                <a href="#" class="btn btn-soft-primary btn-label waves-effect waves-light rounded-pill">
                    <i class="ri-calendar-line label-icon align-middle rounded-pill fs-4 me-2"></i> Jadwal
                </a>
            </div>
        </div>

        <form action="{{ route('attendance.store') }}" method="post">
            @csrf
            <input type="hidden" name="att_lat" id="att_lat" value="{{ old('att_lat') }}">
            <input type="hidden" name="att_long" id="att_long" value="{{ old('att_long') }}">
            <input type="hidden" name="att_address" id="att_address" value="{{ old('att_address') }}">
            <input type="hidden" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->id) }}">
            <input type="hidden" name="schedule_id" id="schedule_id" value="{{ old('schedule_id', $schedule->id) }}">
        </form>
    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection

@push('styles')
    <!-- leaflet plugin -->
    <link href="{{ asset('assets/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #button-background {
            position: relative;
            background-color: #e2e5ed;
            width: 240px;
            height: 50px;
            border: white;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #slider {
            transition: width 0.3s, border-radius 0.3s, height 0.3s;
            position: absolute;
            left: -10px;
            background-color: #405189;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #slider.unlocked {
            transition: all 0.3s;
            width: inherit;
            left: 0 !important;
            height: inherit;
            border-radius: inherit;
        }

        .material-icons {
            color: black;
            font-size: 50px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: default;
        }

        .slide-text {
            color: #3a3d55;
            font-size: 12px;
            text-transform: uppercase;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: default;
            position: relative;
            right: -10px;
        }

        .bottom {
            position: fixed;
            bottom: 0;
            font-size: 14px;
            color: white;
        }
        .bottom a {
            color: white;
        }

        #locker {
            font-size: 24px;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="{{ asset('assets/libs/leaflet/leaflet.js') }}"></script>
    <script>
        $(function() {
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', { hour12: false });
                document.getElementById('currentTime').textContent = timeString;
            }

            setInterval(updateTime, 1000);
            updateTime(); // initial call to set the time immediately

            function initMap() {
                var officeLocation = [{{ $office->lat }}, {{ $office->long }}];
                var map = L.map('maps', {
                    center: officeLocation,
                    zoom: 16,
                    zoomControl: false,
                    scrollWheelZoom: false,
                    dragging: false,
                    doubleClickZoom: false,
                    boxZoom: false,
                    keyboard: false,
                    tap: false,
                    touchZoom: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var officeIcon = L.icon({
                    iconUrl: '{{ asset("assets/images/map-building.png") }}',
                    iconSize: [32, 32], // size of the icon
                    iconAnchor: [16, 24], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -32] // point from which the popup should open relative to the iconAnchor
                });

                var userIcon = L.icon({
                    iconUrl: '{{ asset("assets/images/map-user.png") }}',
                    iconSize: [32, 32], // size of the icon
                    iconAnchor: [16, 24], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -32] // point from which the popup should open relative to the iconAnchor
                });

                var officeCircle = L.circle(officeLocation, {
                    color: '#4cd137',
                    fillColor: '#4cd137',
                    fillOpacity: 0.5,
                    radius: {{ $office->radius }}
                }).addTo(map);

                var officeMarker = L.marker(officeLocation, { icon: officeIcon }).addTo(map)
                    .bindPopup('{{ $office->name }}');

                function checkProximity(userLocation) {
                    var distance = map.distance(userLocation, officeLocation);
                    getAddress(userLocation[0], userLocation[1]);
                    document.getElementById('att_lat').value = userLocation[0];
                    document.getElementById('att_long').value = userLocation[1];

                    if (distance <= {{ $office->radius }}) {
                        document.getElementById('locationLabel').textContent = 'Anda berada DI DALAM AREA presensi';
                        document.getElementById('locationLabel').classList.add('text-success');
                        document.getElementById('locationLabel').classList.remove('text-danger');
                        $('#button-background').show();
                    } else {
                        document.getElementById('locationLabel').textContent = 'Anda berada DI LUAR AREA presensi';
                        document.getElementById('locationLabel').classList.add('text-danger');
                        document.getElementById('locationLabel').classList.remove('text-success');
                    }
                }

                // Display user's current location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLocation = [position.coords.latitude, position.coords.longitude];
                        var userMarker = L.marker(userLocation, { icon: userIcon }).addTo(map)
                            .bindPopup('Lokasimu saat ini');
                        map.setView(userLocation, 16);
                        checkProximity(userLocation);
                    });
                }

                document.getElementById('locate-btn').addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var userLocation = [position.coords.latitude, position.coords.longitude];
                            map.setView(userLocation, 16);
                            var userMarker = L.marker(userLocation, { icon: userIcon }).addTo(map)
                                .bindPopup('Lokasimu saat ini');
                            checkProximity(userLocation);
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                });
            }

            function getAddress(lat, long) {
                const geocodeUrl = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${long}&accept-language=id`;
                fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.address;
                        const addressParts = [
                            address.road,
                            address.suburb,
                            address.city_district,
                            address.city,
                            address.state,
                            address.postcode
                        ].filter(Boolean);
                        document.getElementById('address').textContent = addressParts.join(', ');
                        document.getElementById('att_address').value = addressParts.join(', ') ?? null;
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        document.getElementById('address').textContent = 'Unable to retrieve address';
                    });
            }

            initMap();

            // swipe to unlock
            var initialMouse = 0;
            var slideMovementTotal = 0;
            var mouseIsDown = false;
            var slider = $('#slider');

            slider.on('mousedown touchstart', function(event){
                mouseIsDown = true;
                slideMovementTotal = $('#button-background').width() - $(this).width() + 10;
                initialMouse = event.clientX || event.originalEvent.touches[0].pageX;
            });

            $(document.body, '#slider').on('mouseup touchend', function (event) {
                if (!mouseIsDown)
                    return;
                mouseIsDown = false;
                var currentMouse = event.clientX || event.changedTouches[0].pageX;
                var relativeMouse = currentMouse - initialMouse;

                if (relativeMouse < slideMovementTotal) {
                    $('.slide-text').fadeTo(300, 1);
                    slider.animate({
                        left: "-10px"
                    }, 300);
                    return;
                }
                slider.addClass('unlocked');
                $('#locker').css('font-size', 'inherit');
                $('#locker').removeClass('mdi mdi-fingerprint').addClass('d-flex align-middle').html('<div class="spinner-border text-light" role="status"><span class="sr-only">Loading...</span></div>');

                // ajax request to server
                // attendanceStore();
            });

            $(document.body).on('mousemove touchmove', function(event){
                if (!mouseIsDown)
                    return;

                var currentMouse = event.clientX || event.originalEvent.touches[0].pageX;
                var relativeMouse = currentMouse - initialMouse;
                var slidePercent = 1 - (relativeMouse / slideMovementTotal);
                
                $('.slide-text').fadeTo(0, slidePercent);

                if (relativeMouse <= 0) {
                    slider.css({'left': '-10px'});
                    return;
                }
                if (relativeMouse >= slideMovementTotal + 10) {
                    slider.css({'left': slideMovementTotal + 'px'});
                    return;
                }
                slider.css({'left': relativeMouse - 10});
            });
        });
    </script>
@endpush