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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kasir /</span> Pembayaran Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <h3>List Transaksi</h3>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>No Registrasi</th>
                        <th>Jenis Layanan</th>
                        <th>No RM</th>
                        <th>Nama</th>
                        <th>No Kwitansi</th>
                        <th>Tanggal Bayar</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->lab_nomor }}</td>
                            <td>{{ $d->lab_no_reg }}</td>
                            @php
                                $layanan = 'Home Service';

                                if ($d->jenis_layanan == 0) {
                                    $layanan = 'Datang ke Hi-LAB';
                                }
                            @endphp
                            <td>{{ $layanan }}</td>
                            <td>{{ $d->pasien_nomor_rm}}</td>
                            <td>{{ $d->pasien_nama}}</td>
                            <td>{{ $d->kasir_nomor}}</td>
                            <td>{{ \Carbon\Carbon::parse($d->trans_tanggal)->format('d-m-Y') }}</td>
                            <td>
                                @if($d->kasir_nomor)
                                    <a type="button" target="_blank"
                                       href="{{ route('pembayaran.print', $d->lab_nomor) }}"
                                       class="btn btn-secondary">
                                        <span class="tf-icons bx bx-printer mb-1"></span>&nbsp
                                    </a>
                                @else
                                    <a type="button" href="{{ route('pembayaran.pay', $d->transaksi_id) }}"
                                       class="btn rounded-pill btn-success">
                                        <span class="tf-icons bx bx-cart mb-1"></span>&nbsp; Bayar
                                    </a>
                                @endif
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
