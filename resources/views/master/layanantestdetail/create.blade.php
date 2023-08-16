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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Layanan Test Detail
        Laboratorium
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('layanantestdetail.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('layanantestdetail.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body ml-2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nama" class="form-label">Nama Layanan<sup
                                    class="text-danger">*</sup></label>
                            <select name="nama" id="nama" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($layanan as $l)
                                    <option value="{{ $l->var_kode }}"
                                        {{ old('nama') == $l->var_kode ? 'selected' : '' }}>{{ $l->var_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="detail" class="form-label">Nama Tes Detail<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="detail" value="{{ old('detail') }}" name="detail"
                                   class="form-control" placeholder="Nama test detail" required>
                        </div>
                        <div class="mt-2">
                            <label for="tipe" class="form-label">Tipe Layanan<sup
                                    class="text-danger">*</sup></label>
                            <select name="tipe" class="form-control" id="tipe" required>
                                <option value="">--Pilih--</option>
                                <option value="Single" {{ old('tipe') == 'Single' ? 'selected' : '' }}>Single
                                </option>
                                <option value="Multiple" {{ old('tipe') == 'Multiple' ? 'selected' : '' }}>
                                    Multiple
                                </option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="tarif" class="form-label">Tarif Layanan<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="tarif" value="{{ old('tarif') }}" name="tarif"
                                   class="form-control" placeholder="Tarif" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <label for="formFile" class="form-label">Gambar<sup
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="file" name="gambar" id="formFile"
                                           accept=".jpg, .png" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label for="status" class="form-label">Status<sup
                                            class="text-danger">*</sup></label>
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

                        <img id="preview" class="mt-4 rounded"
                             src="https://fakeimg.pl/320x200/45CFDD/FFF/?text=Preview&font=lobster" alt=""
                             style="width: 320px; height: 200px;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="deskripsi" class="form-label">Deskripsi<sup class="text-danger">*</sup></label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      required>{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="catatan" class="form-label">Catatan<sup class="text-danger">*</sup></label>
                            <textarea id="catatan" name="catatan" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      required>{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="manfaat" class="form-label">Manfaat<sup class="text-danger">*</sup></label>
                            <textarea id="manfaat" name="manfaat" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      required>{{ old('manfaat') }}</textarea>
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

</x-app-layout>
