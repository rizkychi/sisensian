@extends('master.dashboard-master')
@section('title', 'Kantor')
@section('content')
    
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Radius dan Lokasi Kantor</h4>

            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 46px;">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Radius (m)</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>William Elmore</td>
                                    <td>100</td>
                                    <td>-7.123123, 110.123123</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Georgie Winters</td>
                                    <td>20</td>
                                    <td>-7.123123, 110.123123</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Whitney Meier</td>
                                    <td>50</td>
                                    <td>-7.123123, 110.123123</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Justin Maier</td>
                                    <td>40</td>
                                    <td>-7.123123, 110.123123</td>
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