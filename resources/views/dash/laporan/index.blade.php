@extends('master.dashboard-master')
@section('title', 'Laporan')
@section('content')
    
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Laporan Presensi Karyawan</h4>

            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 46px;">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Datang</th>
                                    <th scope="col">Pulang</th>
                                    <th scope="col" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>William Elmore</td>
                                    <td>07 Oct, 2021</td>
                                    <td>07:00</td>
                                    <td>16:00</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Georgie Winters</td>
                                    <td>07 Oct, 2021</td>
                                    <td>07:00</td>
                                    <td>16:00</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Whitney Meier</td>
                                    <td>06 Oct, 2021</td>
                                    <td>08:00</td>
                                    <td>16:00</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Justin Maier</td>
                                    <td>05 Oct, 2021</td>
                                    <td>07:00</td>
                                    <td>16:00</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>

@endsection