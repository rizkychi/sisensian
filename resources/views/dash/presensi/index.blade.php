@extends('master.dashboard-master')
@section('title', 'Presensi')
@section('content')
    
<div class="row justify-content-center">
    <div class="col-auto">
        <div class="card">
            <div class="card-body">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2715.5560969842013!2d110.39100341370602!3d-7.799589208407602!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5764aed6dc47%3A0xdaa512ed06ede32a!2sKantor%20Walikota%20Yogyakarta!5e0!3m2!1sid!2sid!4v1736492932971!5m2!1sid!2sid" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-auto" style="width: 300px;">
                <div class="card card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-auto text-center">
                            <button class="btn btn-outline-success rounded-pill"><i class="las la-fingerprint display-3"></i></button>
                        </div>
                    </div>
                    <h4 class="card-title mt-3">Presensi</h4>
                    <a href="javascript:void(0);" class="btn btn-success m-2">Datang 07:00</a>
                    <a href="javascript:void(0);" class="btn btn-light m-2">Pulang 16:00</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection