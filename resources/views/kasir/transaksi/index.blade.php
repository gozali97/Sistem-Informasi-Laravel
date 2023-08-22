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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kasir /</span> Transaksi Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('transaksi.add') }}" type="button" class="btn btn-primary mt-3">
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
                        <th>Tanggal Pemeriksaan</th>
                        <th>Item Tes</th>
                        <th>Jenis Layanan</th>
                        <th>Status Pemeriksaan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->lab_nomor }}</td>
                            <td>{{ $d->pasien_nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->lab_tanggal)->format('d-m-Y') }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm me-1 collapsed" data-bs-toggle="collapse"
                                   href="#item{{$d->transaksi_id}}" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Item Pemeriksaan
                                </a>
                                <div class="collapse" id="item{{$d->transaksi_id}}">
                                    <div class="d-grid d-sm-flex mt-1">
                                        <ol>
                                            @foreach($d->detail as $details)
                                                <li>{{$details->lab_nama}}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </td>
                            @php
                                $layanan = 'Home Service';

                                if ($d->jenis_layanan == 0) {
                                    $layanan = 'Datang ke Hi-LAB';
                                }
                            @endphp
                            <td>{{ $layanan }}</td>
                            <td>
                                @if($d->lab_byr_ket == 'Lunas')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-warning">Prosess</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->transaksi_id }}');">
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
                    window.location.href = "/kasir/transaksi/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
