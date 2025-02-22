@extends('master.dashboard-master')
@section('title', $title)
@section('content')
    
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card card-bg-fill">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Buat password baru</h5>
                    <p class="text-muted">Kata sandi baru Anda harus berbeda dari kata sandi yang digunakan sebelumnya.</p>
                </div>

                <div class="p-2">
                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="password-input">Password</label>
                            <div class="position-relative auth-pass-inputgroup">
                                <input type="password" name="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                            <div id="passwordInput" class="form-text">Minimal 8 karakter.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="confirm-password-input">Konfirmasi Password</label>
                            <div class="position-relative auth-pass-inputgroup mb-3">
                                <input type="password" name="confirm_password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Konfirmasi password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="confirm-password-input" required>
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="confirm-password-addon"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                        </div>

                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                            <h5 class="fs-13">Kata sandi harus berisi:</h5>
                            <p id="pass-length" class="invalid fs-12 mb-2">Minimal <b>8 karakter</b></p>
                            <p id="pass-lower" class="invalid fs-12 mb-2">Huruf <b>kecil</b> (a-z)</p>
                            <p id="pass-upper" class="invalid fs-12 mb-2">Huruf <b>kapital</b> letter (A-Z)</p>
                            <p id="pass-number" class="invalid fs-12 mb-0"><b>Angka</b> (0-9)</p>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">Ubah Password</button>
                        </div>

                    </form>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

    </div>
</div>
<!-- end row -->

@endsection

@push('scripts')
    <script>
        // password addon
        Array.from(document.querySelectorAll("form .auth-pass-inputgroup")).forEach(function (item) {
            Array.from(item.querySelectorAll(".password-addon")).forEach(function (subitem) {
                    subitem.addEventListener("click", function (event) {
                        var passwordInput = item.querySelector(".password-input");
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                        } else {
                            passwordInput.type = "password";
                        }
                    });
                });
            });

        // passowrd match
        var password = document.getElementById("password-input"),
            confirm_password = document.getElementById("confirm-password-input");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity("");
            }
        }

        //Password validation
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        var myInput = document.getElementById("password-input");
        var letter = document.getElementById("pass-lower");
        var capital = document.getElementById("pass-upper");
        var number = document.getElementById("pass-number");
        var length = document.getElementById("pass-length");

        // When the user clicks on the password field, show the message box
        myInput.onfocus = function () {
            document.getElementById("password-contain").style.display = "block";
        };

        // When the user clicks outside of the password field, hide the password-contain box
        myInput.onblur = function () {
            document.getElementById("password-contain").style.display = "none";
        };

        // When the user starts to type something inside the password field
        myInput.onkeyup = function () {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        };
    </script>
@endpush