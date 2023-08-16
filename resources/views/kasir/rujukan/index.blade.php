<x-app-layout>
    @include('layouts.alerts')
    <style>
        .custom-select.select2-container .select2-selection--single {
            padding: 0.625rem;

        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kasir /</span> Rujukan Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('rujukan.add') }}" type="button" class="btn btn-primary mt-3">
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
                        <th>Nama</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Dokter</th>
                        <th>Rumah Sakit</th>
                        <th>File Rujukan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->rujukan_id }}</td>
                            <td>{{ $d->nama_pasien }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('d-m-Y') }}</td>
                            <td>{{ $d->nama_dokter }}</td>
                            <td>{{ $d->rumah_sakit }}</td>
                            <td>
                                @if ($d->file_rujukan)
                                    <img src="{{ url($d->file_rujukan) }}" alt class="w-px-50 h-auto rounded"/>
                                @else
                                    <img src="https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster" alt
                                         class="w-px-50 h-auto rounded"/>
                                @endif
                            </td>
                            <td>
                                @if($d->status != 'N')
                                    <span class="badge bg-primary">Proses</span>
                                @else
                                    <span class="badge bg-warning">Belum Diperiksa</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if ($d->detail->count() == 0)
                                        <a href="{{ route('rujukan.edit', $d->rujukan_id) }}" type="button"
                                           class="btn btn-icon btn-outline-info">
                                            <span class="tf-icons bx bx-edit"></span>
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->rujukan_id }}');">
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

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
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
                    window.location.href = "/kasir/rujukan/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
