@extends('master.dashboard-master')
@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                

                <div class="card-body">
                    <div class="align-items-center d-flex flex-column flex-md-row mb-3">
                        <h4 class="card-title mb-0 flex-grow-1">Semua Pemberitahuan</h4>
                        <div class="d-flex mt-3 mt-md-0">
                            <a href="{{ route('notification.readAll') }}" class="btn btn-primary btn-label text-nowrap waves-effect waves-light">
                                <i class="bx bx-check-double label-icon align-middle fs-4"></i> Tandai semua dibaca
                            </a>
                        </div>
                    </div><!-- end card header -->

                    <div class="live-preview">
                        <div class="table-responsive">
                            <table id="dtx" class="table table-hover align-middle w-100">
                                <thead class="table-light d-none">
                                    <tr>
                                        {{-- <th scope="col" style="width: 0px;">No</th> --}}
                                        <th scope="col">Judul</th>
                                        <th scope="col">Dari</th>
                                        <th scope="col">Pesan</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($notifications->count() > 0)
                                        @foreach ($notifications as $notification)
                                            <tr class="position-relative {{ $notification->status == 'unread' ? 'fw-medium' : 'text-muted' }}">
                                                {{-- <td>{{ $loop->iteration }}</td> --}}
                                                <td>{{ $notification->title }}</td>
                                                <td><a href="{{ route('notification.read', ['id' => $notification->id]) }}" class="stretched-link text-reset">{{ @$notification->from->employee->name ?: Str::ucfirst($notification->from->username) }}</a></td>
                                                <td>{{ $notification->message }}</td>
                                                <td>
                                                    <span class="text-muted">{{ $notification->time_ago }}</span>
                                                </td>
                                                <td>
                                                    @if ($notification->status == 'unread')
                                                        <span
                                                            class="badge badge-border bg-info-subtle text-info">Belum
                                                            Dibaca</span>
                                                    @else
                                                        <span
                                                            class="badge badge-border bg-dark-subtle text-muted">Sudah
                                                            Dibaca</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada pemberitahuan.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            var table = $('#dtx').DataTable({
                // processing: true,
                // serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                info: false,
                // ajax: "",
                // columns: [
                //     // {
                //     //     data: 'DT_RowIndex',
                //     //     name: 'DT_RowIndex',
                //     //     searchable: false
                //     // },
                //     {
                //         data: 'from.username',
                //         name: 'from.username',
                //         render: function(data, type, row) {
                //             return '<b>' + data + '</b>';
                //         }
                //     },
                //     {
                //         data: 'title',
                //         name: 'title'
                //     },
                //     {
                //         data: 'message',
                //         name: 'message'
                //     },
                //     {
                //         data: 'time_ago',
                //         name: 'time_ago',
                //         render: function(data, type, row) {
                //             return '<span class="text-muted">' + data + '</span>';
                //         }
                //     },
                //     {
                //         data: 'status',
                //         name: 'status',
                //         render: function(data, type, row) {
                //             if (data == 'unread') {
                //                 return '<span class="badge badge-border bg-info-subtle text-info">Belum Dibaca</span>';
                //             } else {
                //                 return '<span class="badge badge-border bg-dark-subtle text-muted">Sudah Dibaca</span>';
                //             }
                //         }
                //     }
                // ],
            });
        });
    </script>
@endpush
