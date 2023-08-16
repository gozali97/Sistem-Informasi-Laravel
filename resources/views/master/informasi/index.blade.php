<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Informasi</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('informasi.add') }}" type="button" class="btn btn-primary mt-3">
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
                        <th>Judul Informasi</th>
                        <th>Informasi</th>
                        <th>Alamat URL</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->judul_informasi }}</td>
                            <td>{{substr($d->informasi, 0, 50) . '...'  }}</td>
                            <td><a href="{{ $d->alamat_url }}" target="_blank">{{ $d->judul_informasi }}</a></td>
                            <td>
                                <img src="{{ url($d->path_gambar) }}" alt class="w-px-50 h-auto rounded"/>

                            </td>
                            @php
                                $status = 'Tidak Aktif';

                                if ($d->status == 'A') {
                                    $status = 'Aktif';
                                }
                            @endphp
                            <td>{{ $status }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('informasi.edit', $d->id) }}" type="button"
                                       class="btn btn-icon btn-outline-warning">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->id }}');">
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
                    window.location.href = "/master/informasi/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
