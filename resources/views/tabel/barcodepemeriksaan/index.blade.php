<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Barcode Pemeriksaan</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <h2>Barcode Pemeriksaan</h2>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Sign</th>
                        <th>Instrument</th>
                        <th>Copy</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->kode }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->sign }}</td>
                            <td>{{ $d->inst }}</td>
                            <td>{{ $d->kopi }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-icon btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $d->id }}">
                                        <span class="tf-icons bx bx-info-circle"></span>
                                    </button>
                                </div>
                            </td>
                            <div class="modal fade" id="detailModal{{ $d->id }}" tabindex="-1"
                                 aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel4">Barcode Pemeriksaan
                                                Lab</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="d-inline-flex justify-content-center w-full text-center">
                                                        <?php
                                                        $barcode = new \Milon\Barcode\DNS2D;
                                                        echo $barcode->getBarcodeHTML($d->nama . '-' . $d->sign . '-' . $d->kode . '-' . $d->inst, 'QRCODE', 8, 8);
                                                        ?>
                                                </div>
                                                <h4 class="text-center mt-1">{{$d->nama }}- {{$d->kode}}</h4>

                                            </div>
                                            <div
                                                class="card-footer d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-dark"
                                                        data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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
                    window.location.href = "/master/barcodepemeriksaan/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
