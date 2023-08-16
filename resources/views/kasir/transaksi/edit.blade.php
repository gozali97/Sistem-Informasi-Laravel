<x-app-layout>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Tarif Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('tariflab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form action="{{ route('tariflab.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="tarif_kelompok" class="form-label">Nama Kategori Pemeriksaan</label>
                            <select name="tarif_kelompok" class="form-control" id="tarif_kelompok" required>
                                <option value="">--Pilih--</option>
                                @foreach ($tarif as $t)
                                    <option value="{{ $t->var_kode }}"
                                        {{ $data->tarif_kelompok == $t->var_kode ? 'selected' : '' }}>
                                        {{ $t->var_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="tarif_nama" class="form-label">Nama Test Pemeriksaan</label>
                            <input type="text" id="tarif_nama" value="{{ $data->tarif_nama }}" name="tarif_nama"
                                class="form-control" placeholder="Nama Test Pemeriksaan" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label for="tarif_jalan" class="form-label">Tarif</label>
                                    <input type="number" id="tarif_jalan" value="{{ $data->tarif_jalan }}"
                                        name="tarif_jalan" class="form-control" placeholder="Tarif" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div>
                                    <label for="tarif_status" class="form-label">Status</label>
                                    <select name="tarif_status" class="form-control" id="tarif_status" required>
                                        <option value="">--Pilih--</option>
                                        <option value="A" {{ $data->tarif_status == 'A' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="N" {{ $data->tarif_status == 'N' ? 'selected' : '' }}>Tidak
                                            Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="formFile" class="form-label">Gambar</label>
                                    <input class="form-control" type="file" name="gambar" id="formFile"
                                        accept=".jpg, .png" />
                                </div>
                                <div class="col-md-6">
                                    <img id="preview" src="" alt=""
                                        style="max-width: 125%; max-height: 65px;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ $data->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea id="catatan" name="catatan" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ $data->catatan }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="manfaat" class="form-label">Manfaat</label>
                            <textarea id="manfaat" name="manfaat" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ $data->manfaat }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>

        </form>
    </div>

    <script>
        // Membuat event listener change pada input file
        document.getElementById("formFile").addEventListener("change", function(event) {
            // Mendapatkan file yang diupload
            let file = event.target.files[0];

            // Membuat objek FileReader
            let reader = new FileReader();

            // Membuat event listener untuk ketika file selesai dibaca
            reader.addEventListener("load", function() {
                // Mengganti sumber gambar pada elemen img dengan gambar yang sudah dipilih
                document.getElementById("preview").src = reader.result;
            }, false);

            // Membaca file yang dipilih sebagai data URL
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>

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
        $(document).ready(function() {
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
                    window.location.href = "/account/user/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
