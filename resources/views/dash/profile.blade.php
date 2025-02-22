@extends('master.dashboard-master')
@section('title', $title)
@section('content')
    
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="{{ $profilepic }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow" alt="user-profile-image">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <h5 class="fs-16 mb-1">{{ @$data->name }}</h5>
                    <p class="text-muted mb-0">{{ @$data->position }}{{ $data->is_admin ? 'Administrator':'' }}</p>
                    <p class="text-muted mb-0">{{ @$data->office->name }}</p>
                </div>
            </div>
        </div>
        <!--end card-->
        
    </div>
    <!--end col-->
    <div class="col-md-8">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <h4 class="card-title mb-0 flex-grow-1">Informasi Personal</h4>
            </div>
            <div class="card-body p-4 pt-3">
                <div class="tab-content">
                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                        <form id="fieldForm" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="id_number" class="form-label">ID</label>
                                        <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Nomor ID" value="{{ old('id_number', @$data->id_number) }}" disabled>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{ old('username', @$data->user->username) }}" required>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name', @$data->name) }}" required {{ @$data->is_admin ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="No. HP" value="{{ old('phone', @$data->phone) }}" {{ @$data->is_admin ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', @$data->user->email) }}" required>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Alamat" {{ @$data->is_admin ? 'disabled' : '' }}>{{ old('address', @$data->address) }}</textarea>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="office" class="form-label">Kantor</label>
                                        <input type="text" class="form-control" id="office" name="office" placeholder="Kantor" value="{{ old('office', @$data->office->name) }}" disabled>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Jabatan/Posisi</label>
                                        <input type="text" class="form-control" id="position" name="position" placeholder="Jabatan/Posisi" value="{{ old('position', @$data->position) }}" disabled>
                                    </div>
                                </div>
                                <!--end col-->
                                
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-start">
                                        <button type="submit" class="btn btn-primary">Perbarui</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection

@push('modals')
    <!-- Modal for cropping image -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModalLabel">Unggah Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="imageToCrop" src="" alt="Foto Profil">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropButton">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('styles')
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
@endpush
@push('scripts')
    <!-- cleave.js -->
    <script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
    <script>
        // Phone Number
        document.querySelector("#phone") && (cleaveNumeral = new Cleave("#phone", {
            delimiters: ["(", ") ", "-"],
            blocks: [0, 4, 4, 5],
        }));

        document.querySelector('#fieldForm').addEventListener('submit', function(e) {
            var phoneField = document.getElementById('phone');
            phoneField.value = phoneField.value.replace(/\D/g, '');
        });
    </script>

    <!-- Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;
        const profileImgInput = document.querySelector("#profile-img-file-input");
        const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
        const imageToCrop = document.getElementById('imageToCrop');
        const cropButton = document.getElementById('cropButton');

        profileImgInput.addEventListener("change", function () {
            const file = profileImgInput.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                imageToCrop.src = e.target.result;
                cropperModal.show();
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
            });
        });

        document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        cropButton.addEventListener('click', function () {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
            });
            canvas.toBlob(function (blob) {
                const formData = new FormData();
                formData.append('avatar', blob);

                fetch('{{ route("profile.uploadAvatar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(".user-profile-image").src = data.avatar_url;
                        document.querySelector(".header-profile-user").src = data.avatar_url;
                        cropperModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Foto profil berhasil diperbarui',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error ?? 'Gagal mengunggah foto',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengunggah foto',
                    });
                });
            });
        });
    </script>
@endpush