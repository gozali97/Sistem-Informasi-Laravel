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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Tentang Kami</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('tentangkami.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('tentangkami.update', $data->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="group_paket" class="form-label">Judul<sup class="text-danger">*</sup></label>
                            <input type="text" id="judul" value="{{ $data->judul }}" name="judul"
                                   class="form-control" placeholder="Judul" required>
                        </div>
                        <div class="mt-2">
                            <label for="deskripsi" class="form-label">Deskripsi<sup class="text-danger">*</sup></label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      required>{{ $data->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->status == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ $data->status == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>

                        </div>
                        <div class="mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="formFile" class="form-label">Gambar<sup
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="file" name="gambar" id="formFile"
                                           accept=".jpg, .png"/>
                                </div>
                                <div class="col-md-6">
                                    <img id="preview" class="mt-2"
                                         src="{{ url('/images/tentangkami', $data->gambar) }}" alt=""
                                         style="max-width: 250%; max-height: 100px;">
                                </div>
                            </div>
                        </div>

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
