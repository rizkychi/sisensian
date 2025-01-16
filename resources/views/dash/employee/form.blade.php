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
                        <!-- Ribbon Shape -->
                        <div class="card ribbon-box border shadow-none mb-lg-0 material-shadow">
                            <div class="card-body mt-2">
                                <div class="ribbon ribbon-info ribbon-shape">Data User</div>
                                <div class="ribbon-content mt-4">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Username" value="{{ old('username', @$data->user->username) }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Password" value="{{ old('password') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                                value="{{ old('email', @$data->user->email) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ribbon Shape -->
                        <div class="card ribbon-box border shadow-none mb-lg-0 mt-3 material-shadow">
                            <div class="card-body mt-2">
                                <div class="ribbon ribbon-info ribbon-shape">Data Karyawan</div>
                                <div class="ribbon-content mt-4">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="id_number" class="form-label">ID Karyawan</label>
                                            <input type="text" class="form-control" id="id_number" name="id_number"
                                                placeholder="ID Karyawan" value="{{ old('id_number', @$data->id_number) }}" required>
                                        </div>
            
                                        <div class="col-md-8 mb-3">
                                            <label for="name" class="form-label">Nama Karyawan</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Nama Karyawan" value="{{ old('name', @$data->name) }}" required>
                                        </div>
            
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Alamat">{{ old('address', @$data->address) }}</textarea>
                                        </div>
            
                                        <div class="col-md-4 mb-3">
                                            <label for="phone" class="form-label">No. HP</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="No. HP" value="{{ old('phone', @$data->phone) }}">
                                        </div>
            
                                        <div class="col-md-8 mb-3">
                                            <label for="position" class="form-label">Jabatan/Posisi</label>
                                            <input type="text" class="form-control" id="position" name="position"
                                                placeholder="Jabatan/Posisi" value="{{ old('position', @$data->position) }}">
                                        </div>
            
                                        <div class="col-md-12 mb-3">
                                            <label for="office_id" class="form-label">Kantor</label>
                                            <select class="form-select" id="office_id" name="office_id" data-choices required>
                                                <option value="">Pilih Kantor</option>
                                                @foreach ($office as $ofc)
                                                    <option value="{{ $ofc->id }}"
                                                        {{ old('office_id', @$data->office_id) == $ofc->id ? 'selected' : '' }}>
                                                        {{ $ofc->name }}</option>
                                                @endforeach
                                            </select>
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
                            <a href="{{ route('employee.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div><!-- end card-footer -->
                </div><!-- end card -->
            </form>
        </div><!-- end col -->
    </div>
@endsection

@push('scripts')
    <!-- cleave.js -->
    <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
    <script>
        document.querySelector("#phone") && (cleaveNumeral = new Cleave("#phone", {
            delimiters: ["(", ") ", "-"],
            blocks: [0, 4, 4, 5],
        }));
    </script>
@endpush
