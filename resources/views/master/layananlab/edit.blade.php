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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Layanan Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('layananlab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('layananlab.update', $data->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body ml-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nama" class="form-label">Nama Layanan<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama" value="{{ $data->var_nama }}" name="nama"
                                   class="form-control" placeholder="Nama" required>
                        </div>
                        <div class="mt-2">
                            <label for="nilai" class="form-label">Nilai Layanan<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="nilai"
                                   value="{{ str_replace([',', '.00'], ['', ''], $data->var_nilai) }}" name="nilai"
                                   class="form-control" placeholder="Nilai" required>
                        </div>
                        <div class="mt-2">
                            <label for="nilai_lama" class="form-label">Nilai Lama Layanan<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="nilai_lama"
                                   value="{{ str_replace([',', '.00'], ['', ''], $data->var_nilai_lama) }}"
                                   name="nilai_lama" class="form-control" placeholder="Nilai lama" required>
                        </div>
                        <div class="mt-2">
                            <label for="formFile" class="form-label">Gambar<sup class="text-danger">*</sup></label>
                            <input class="form-control" type="file" name="gambar" id="formFile"
                                   accept=".jpg, .png"/>
                        </div>

                    </div>
                    <div class="col-md-5">
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
                        <img id="preview" class="mt-4 rounded"
                             src="{{ url($data->path_gambar) }}" alt=""
                             style="width: 320px; height: 200px;">
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

</x-app-layout>
