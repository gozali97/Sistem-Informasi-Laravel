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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Paket Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('paketlab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form action="{{ route('paketlab.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="group_paket" class="form-label">Group Paket<sup
                                    class="text-danger">*</sup></label>
                            <select name="group_paket" class="form-control" id="group_paket" required>
                                <option value="">--Pilih--</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->paket_kelompok }}"
                                        {{ old('group_paket') == $group->paket_kelompok ? 'selected' : '' }}>
                                        {{ $group->nama_group }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="paket_kode" class="form-label">Kode Paket</label>
                            <input type="text" id="paket_kode" value="{{ $paket_kode }}" name="paket_kode"
                                class="form-control" placeholder="Paket Kode" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="nama_paket" class="form-label">Nama Paket<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama_paket" value="{{ old('nama_paket') }}" name="nama_paket"
                                class="form-control" placeholder="Nama Paket" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="tarif" class="form-label">Tarif<sup class="text-danger">*</sup></label>
                            <input type="number" id="tarif" value="{{ old('tarif') }}" name="tarif"
                                class="form-control" placeholder="Tarif" required>
                        </div>

                        <div class="mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="formFile" class="form-label">Gambar<sup
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="file" name="gambar" id="formFile"
                                        accept=".jpg, .png" required />
                                </div>
                                <div class="col-md-6">
                                    <img id="preview" src="" alt=""
                                        style="max-width: 125%; max-height: 65px;">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ old('status') == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ old('status') == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="deskripsi" class="form-label">Deskripsi<sup class="text-danger">*</sup></label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="catatan" class="form-label">Catatan<sup class="text-danger">*</sup></label>
                            <textarea id="catatan" name="catatan" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="manfaat" class="form-label">Manfaat<sup class="text-danger">*</sup></label>
                            <textarea id="manfaat" name="manfaat" class="form-control" placeholder="Tulis keterangan disini" rows="3"
                                required>{{ old('manfaat') }}</textarea>
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
