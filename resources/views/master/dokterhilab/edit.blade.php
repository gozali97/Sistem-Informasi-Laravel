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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Data Dokter</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('dokterhilab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('dokterhilab.update', $data->dokter_kode) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body ml-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="dokter_kode" class="form-label">ID Dokter<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="dokter_kode" value="{{ $data->dokter_kode }}" name="dokter_kode"
                                   class="form-control" placeholder="Judul" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="nama_dokter" class="form-label">Nama Dokter<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama_dokter" value="{{ $data->dokter_nama }}" name="nama_dokter"
                                   class="form-control" placeholder="Nama Dokter" required>
                        </div>
                        <div class="mt-2">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap Dokter<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama_lengkap" value="{{ $data->dokter_nama_lengkap }}"
                                   name="nama_lengkap"
                                   class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mt-2">
                            <label for="formFile" class="form-label">Alamat Dokter<sup
                                    class="text-danger">*</sup></label>
                            <textarea class="form-control" name="alamat" id="alamat"
                                      rows="3">{{$data->dokter_alamat}}</textarea>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div>
                            <label for="kota" class="form-label">Dokter Kota<sup
                                    class="text-danger">*</sup></label>
                            <select name="kota" class="form-control js-example-basic-single" id="kota" required>
                                <option value="">--Pilih--</option>
                                @foreach($kota as $k)
                                    <option
                                        value="{{$k->city_name}}" {{ $data->dokter_kota == $k->city_name ? 'selected' : '' }}>{{$k->city_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="no_hp" class="form-label">No Telepon<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="no_hp" value="{{ $data->dokter_telepon }}" name="no_hp"
                                   class="form-control" placeholder="No HP" required>
                        </div>
                        <div class="mt-2">
                            <label for="dokter_tarif" class="form-label">Tarif Dokter<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="dokter_tarif"
                                   value="{{ number_format($data->dokter_tarif, 0, '.', '') }}" name="dokter_tarif"
                                   class="form-control" placeholder="Tarif" required>
                        </div>
                        <div class="mt-2">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->dokter_status == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ $data->dokter_status == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
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
