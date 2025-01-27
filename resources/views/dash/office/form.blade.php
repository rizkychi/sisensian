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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kantor</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Nama Kantor" value="{{ old('name', @$data->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Deskripsi" value="{{ old('description', @$data->description) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Kantor</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Alamat Kantor" value="{{ old('address', @$data->address) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="radius" class="form-label">Radius Kantor (m)</label>
                                            <input type="text" class="form-control" placeholder="Radius Kantor" name="radius"
                                                id="radius" value="{{ old('radius', @$data->radius) ?? 100 }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="lat" class="form-label">Latitude</label>
                                            <input type="text" class="form-control" id="lat" name="lat"
                                                placeholder="Latitude" value="{{ old('lat', @$data->lat) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="long" class="form-label">Longitude</label>
                                            <input type="text" class="form-control" id="long" name="long"
                                                placeholder="Longitude" value="{{ old('long', @$data->long) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="map" style="height: 400px; position: relative;">
                                            <button id="locate-btn" type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                                                <i class="bx bxs-map label-icon align-middle rounded-pill fs-16 me-2"></i> Get Current Location
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="form-check form-switch form-switch-md" dir="ltr">
                            <input type="checkbox" class="form-check-input" id="customSwitchsizemd" name="is_active"
                            {{ old('is_active', @$data->is_active) ?? !isset($data) ? 'checked' : '' }}>
                            <label class="form-check-label" for="customSwitchsizemd">Aktif?</label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan
                            </button>
                            <a href="{{ route('office.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('styles')
    <!-- leaflet plugin -->
    <link href="{{ asset('assets/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <!-- cleave.js -->
    <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
    <script>
        document.querySelector("#radius") && (cleaveNumeral = new Cleave("#radius", {
            numeral: !0,
            numeralThousandsGroupStyle: "thousand"
        }))
    </script>
    <!-- leaflet plugin -->
    <script src="{{ asset('assets/libs/leaflet/leaflet.js') }}"></script>
    <script>
        var defaultLat = {{ env('DEFAULT_LAT', -7.79985) }};
        var defaultLng = {{ env('DEFAULT_LONG', 110.39115) }};

        var map = L.map('map').setView([{{ old('lat', @$data->lat) ?? 'null' }} ?? defaultLat, {{ old('long', @$data->long) ?? 'null' }} ?? defaultLng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        var marker = L.marker([{{ old('lat', @$data->lat) ?? 'null' }} ?? defaultLat, {{ old('long', @$data->long) ?? 'null' }} ?? defaultLng], {
            draggable: true
        }).addTo(map);

        var circle = L.circle([{{ old('lat', @$data->lat) ?? 'null' }} ?? defaultLat, {{ old('long', @$data->long) ?? 'null' }} ?? defaultLng], {
            color: '#4cd137',
            fillColor: '#4cd137',
            fillOpacity: 0.5,
            radius: {{ old('radius', @$data->radius) ?? 100 }}
        }).addTo(map);

        function setLatLng(lat, lng) {
            lat = parseFloat(lat).toFixed(5);
            lng = parseFloat(lng).toFixed(5);
            document.getElementById('lat').value = lat;
            document.getElementById('long').value = lng;
            var latlng = [lat, lng];
            marker.setLatLng(latlng);
            circle.setLatLng(latlng);
            map.setView(latlng, 17);
        }

        marker.on('dragend', function (e) {
            var latlng = marker.getLatLng();
            setLatLng(latlng.lat, latlng.lng);
        });

        map.on('click', function (e) {
            var latlng = e.latlng;
            setLatLng(latlng.lat, latlng.lng);
        });

        document.getElementById('locate-btn').addEventListener('click', function (e) {
            e.stopPropagation();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latlng = [position.coords.latitude, position.coords.longitude];
                    setLatLng(latlng[0], latlng[1]);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        document.getElementById('lat').addEventListener('input', function (e) {
            var lat = e.target.value;
            var lng = document.getElementById('long').value;
            setLatLng(lat, lng);
        });

        document.getElementById('long').addEventListener('input', function (e) {
            var lng = e.target.value;
            var lat = document.getElementById('lat').value;
            setLatLng(lat, lng);
        });
    </script>
@endpush
