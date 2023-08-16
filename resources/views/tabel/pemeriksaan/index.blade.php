<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Data Pemeriksaan</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <h2>Daftar Pemeriksaan Lab</h2>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pemeriksaan</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->grup_nama }}</td>
                            <td>{{ $d->deskripsi }}</td>
                            @php
                                $status = 'Tidak Aktif';

                                if ($d->status == 'A') {
                                    $status = 'Aktif';
                                }
                            @endphp
                            <td>{{ $status }}</td>
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
                                            <h5 class="modal-title" id="exampleModalLabel4">Detail Pemeriksaan Lab</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <label for="grup_nama" class="form-label">Nama
                                                            Pemeriksaan</label>
                                                        <input type="text" id="grup_nama" value="{{ $d->grup_nama }}"
                                                               name="grup_nama"
                                                               class="form-control" placeholder="Nama Pemeriksaan"
                                                               readonly>
                                                    </div>
                                                    <div class="mt-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" class="form-control" id="status" disabled>
                                                            <option value="">--Pilih--</option>
                                                            <option
                                                                value="A" {{ $d->status == 'A' ? 'selected' : '' }}>
                                                                Aktif
                                                            </option>
                                                            <option
                                                                value="N" {{ $d->status == 'N' ? 'selected' : '' }}>
                                                                Tidak Aktif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                                    <textarea id="deskripsi" name="deskripsi" class="form-control"
                                                              placeholder="Tulis keterangan disini" rows="5"
                                                              readonly>{{ $d->deskripsi }}</textarea>
                                                    <span id="deskripsi-error" class="text-danger"></span>

                                                </div>
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
                    window.location.href = "/master/pemeriksaan/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
