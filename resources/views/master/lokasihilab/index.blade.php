<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Lokasi Hilab</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('lokasihilab.add') }}" type="button" class="btn btn-primary mt-3">
                Tambah
            </a>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th>Provinsi</th>
                        <th>Kota</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->nama_lokasi }}</td>
                            <td>{{ $d->alamat }}</td>
                            <td>{{ $d->provinsi }}</td>
                            <td>{{ $d->kota }}</td>
                            <td>{{ $d->longitude }}</td>
                            <td>{{ $d->latitude }}</td>

                            @php
                                $status = 'Tidak Aktif';

                                if ($d->status == 'A') {
                                    $status = 'Aktif';
                                }
                            @endphp
                            <td>{{ $status }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('lokasihilab.edit', $d->lokasi_id) }}" type="button"
                                       class="btn btn-icon btn-outline-warning">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->lokasi_id }}');">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        @if (session('toast_success'))
        iziToast.success({
            title: 'Success',
            message: '{{ session('toast_success') }}',
            position: 'topRight'
        });
        @elseif (session('toast_failed'))
        iziToast.error({
            title: 'Failed',
            message: '{{ session('toast_failed') }}',
            position: 'topRight'
        });
        @endif
    </script>
    <script>
        $(document).ready(function () {
            $('#table1').DataTable();
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/master/lokasihilab/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
