<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Paket Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <h2>Daftar Paket Lab</h2>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Paket</th>
                        <th>Nama Paket</th>
                        <th>Harga Paket</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->paket_kode }}</td>
                            <td>{{ $d->paket_nama }}</td>
                            <td>{{ $d->paket_jalan }}</td>
                            <td>
                                @if ($d->path_gambar)
                                    <img src="{{ url($d->path_gambar) }}" alt class="w-px-50 h-auto rounded"/>
                                @else
                                    <img src="https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster" alt
                                         class="w-px-50 h-auto rounded"/>
                                @endif
                            </td>
                            @php
                                $status = 'Tidak Aktif';

                                if ($d->paket_status == 'A') {
                                    $status = 'Aktif';
                                }
                            @endphp
                            <td>{{ $status }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-icon btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $d->paket_kode }}">
                                        <span class="tf-icons bx bx-info-circle"></span>
                                    </button>
                                </div>
                            </td>
                            <div class="modal fade" id="detailModal{{ $d->paket_kode }}" tabindex="-1"
                                 aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel4">Detail Tarif Lab</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <label for="group_paket" class="form-label">Group Paket</label>
                                                        <select name="group_paket" class="form-control" id="group_paket"
                                                                disabled>
                                                            <option value="">--Pilih--</option>
                                                            @foreach ($groups as $group)
                                                                <option value="{{ $group->paket_kelompok }}"
                                                                    {{ $d->paket_kelompok == $group->paket_kelompok ? 'selected' : '' }}>
                                                                    {{ $group->nama_group }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="paket_kode" class="form-label">Kode Paket</label>
                                                        <input type="text" id="paket_kode" value="{{ $d->paket_kode }}"
                                                               name="paket_kode"
                                                               class="form-control" placeholder="Paket Kode" readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="nama_paket" class="form-label">Nama Paket</label>
                                                        <input type="text" id="nama_paket" value="{{ $d->paket_nama }}"
                                                               name="nama_paket"
                                                               class="form-control" placeholder="Nama Paket" readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="tarif" class="form-label">Tarif</label>
                                                        <input type="number" id="tarif"
                                                               value="{{ number_format($d->paket_jalan, 0, '.', '') }}"
                                                               name="tarif"
                                                               class="form-control" placeholder="Tarif" readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" class="form-control" id="status" readonly>
                                                            <option value="">--Pilih--</option>
                                                            <option
                                                                value="A" {{ $d->paket_status == 'A' ? 'selected' : '' }}>
                                                                Aktif
                                                            </option>
                                                            <option
                                                                value="N" {{ $d->paket_status == 'N' ? 'selected' : '' }}>
                                                                Tidak Aktif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <div class="row">
                                                            <label for="formFile" class="form-label">Gambar</label>
                                                            @if ($d->path_gambar)
                                                                <img id="preview" src="{{ url($d->path_gambar) }}"
                                                                     alt class="w-75 h-auto rounded"/>
                                                            @else
                                                                <img id="preview"
                                                                     src="https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster"
                                                                     alt
                                                                     class="w-75 h-auto rounded"/>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="catatan" class="form-label">Catatan</label>
                                                        <textarea id="catatan" name="catatan" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->catatan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="manfaat" class="form-label">Manfaat</label>
                                                        <textarea id="manfaat" name="manfaat" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->manfaat }}</textarea>
                                                    </div>
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
                    window.location.href = "/master/paketlab/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
