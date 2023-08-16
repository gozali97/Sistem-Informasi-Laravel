<x-app-layout>
    <style>
        .hide {
            display: none;
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Syarat & Ketentuan</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('syaratketentuan.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('syaratketentuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body ml-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="judul" class="form-label">Judul<sup class="text-danger">*</sup></label>
                            <input type="text" id="judul" value="{{ old('judul') }}" name="judul"
                                   class="form-control" placeholder="Judul" required>
                        </div>
                        <div class="mt-2">
                            <label for="url" class="form-label">Kategori<sup class="text-danger">*</sup></label>
                            <select name="kategori" id="kategori" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="0">Akun</option>
                                <option value="1">Pembayaran</option>
                                <option value="2">Profil</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="formFile" class="form-label">Gambar<sup class="text-danger">*</sup></label>
                            <input class="form-control" type="file" name="gambar" id="formFile" accept=".jpg, .png"
                                   required/>
                        </div>

                        <div class="mt-2">
                            <label for="konten" class="form-label">Syarat dan Ketentuan<sup
                                    class="text-danger">*</sup></label>
                            <textarea type="text" id="konten" name="konten" class="form-control"
                                      placeholder="Syarat dan Ketentuan" rows="4"
                                      required>{{ old('konten') }}</textarea>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div>
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ old('status') == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ old('status') == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                        <img id="preview" class="mt-4 rounded"
                             src="https://fakeimg.pl/350x200/45CFDD/FFF/?text=Preview&font=lobster" alt=""
                             style="width: 390px; height: 230px;">
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button id="submitBtn" type="submit" class="btn btn-primary">Save changes</button>
            </div>

        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("myForm").addEventListener("submit", function () {
                document.getElementById("submitBtn").classList.add("hide");
            });
        });
    </script>

    <script>
        // Membuat event listener change pada input file
        document.getElementById("formFile").addEventListener("change", function (event) {
            // Mendapatkan file yang diupload
            let file = event.target.files[0];

            // Membuat objek FileReader
            let reader = new FileReader();

            // Membuat event listener untuk ketika file selesai dibaca
            reader.addEventListener("load", function () {
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
                    window.location.href = "/account/user/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
