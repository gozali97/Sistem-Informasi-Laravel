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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span>Ubah Promo Layanan Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('layananpromo.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('layananpromo.update', $data->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="tarif_kelompok" class="form-label">Nama Kategori Pemeriksaan<sup
                                    class="text-danger">*</sup></label>
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
                        <div class="mt-2">
                            <label for="tarif_kode" class="form-label">Nama Test Pemeriksaan<sup
                                    class="text-danger">*</sup></label>
                            <select class="form-control" id="lab" name="tarif_kode">
                                <option value="">--Pilih--</option>
                                @foreach ($lab as $l)
                                    <option value="{{ $l->tarif_kode }}"
                                        {{ $data->tarif_kode == $l->tarif_kode ? 'selected' : '' }}>{{ $l->tarif_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div>
                                    <label for="promo_start" class="form-label">Promo Start<sup
                                            class="text-danger">*</sup></label>
                                    <input type="date" id="promo_start" name="promo_start"
                                           value="{{ date('Y-m-d', strtotime($data->periode_start)) }}"
                                           class="form-control" placeholder="dd/mm/yyyy" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div>
                                    <label for="promo_end" class="form-label">Promo End<sup
                                            class="text-danger">*</sup></label>
                                    <input type="date" id="promo_end"
                                           value="{{ date('Y-m-d', strtotime($data->periode_end)) }}" name="promo_end"
                                           class="form-control" placeholder="dd/mm/yyyy" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->status_promo == 'A' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="N" {{ $data->status_promo == 'N' ? 'selected' : '' }}>
                                    Tidak
                                    Aktif
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="tarif_jalan" class="form-label">Tarif<sup class="text-danger">*</sup></label>
                            <input type="number" id="tarif"
                                   value="{{ number_format($data->tarif_jalan, 0, ',', '.') }}" name="tarif_jalan"
                                   class="form-control" placeholder="Tarif" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="tarif_promo" class="form-label">Harga Promo<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="vpromo"
                                   value="{{ number_format($data->promo_value, 0, ',', '.') }}" name="tarif_promo"
                                   class="form-control" placeholder="Harga Promo" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="persen_promo" class="form-label">Besar Promo %<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="persenpromo" value="{{ $data->promo_percent }}"
                                   name="persen_promo" class="form-control" placeholder="Jumlah %" required>
                        </div>
                        <div class="mt-2">
                            <label for="harga_akhir" class="form-label">Harga Akhir<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="hargapromo"
                                   value="{{ number_format($data->fix_value, 0, ',', '.') }}" name="harga_akhir"
                                   class="form-control" placeholder="Harga Akhir" readonly>
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
        $(document).ready(function () {
            $('#tarif_kelompok').change(function () {
                var var_kode = $(this).val();
                Swal.fire({
                    title: 'Loading...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/master/layananpromo/get-data',
                    data: {
                        var_kode: var_kode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        Swal.close();
                        $("#lab").html('');
                        if (data.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data tidak ditemukan untuk kategori yang dipilih.',
                            });
                        } else {
                            $("#lab").append('<option value="">-- Pilih --</option>');
                            $.each(data, function (index, value) {
                                $("#lab").append('<option value="' + value.tarif_kode + '">' + value.tarif_nama + '</option>');
                            });
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memuat data.',
                        });
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#lab').on('change', function () {
                var tarifKode = $(this).val();

                Swal.fire({
                    title: 'Loading...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '/master/layananpromo/get-data-by-kode/' + tarifKode,
                    success: function (data) {
                        Swal.close();
                        $('#tarif').val(Number(data.tarif_jalan));
                    }
                });
            });
        });
    </script>

    <script>
        function convertToRupiah(angka) {
            var rupiah = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for (var i = 0; i < angkarev.length; i++)
                if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
            return rupiah.split('', rupiah.length - 1).reverse().join('');
        }

        function formatRupiah(angka) {
            var number_string = angka.replace(/[^,\d]/g, '').toString();
            return number_string;
        }

        var valuepromo = document.getElementById('vpromo');
        valuepromo.addEventListener('keyup', function (e) {
            valuepromo.value = formatRupiah(this.value);
        });

        var hargapromo = document.getElementById('hargapromo');
        hargapromo.addEventListener('keyup', function (e) {
            hargapromo.value = formatRupiah(this.value);
        });

        $(document).ready(function () {

            $("#vpromo").keyup(function () {
                var vpromo = parseInt($("#vpromo").val().replace(/,.*|[^0-9]/g, ''), 10);
                var persenpromo = $("#persenpromo").val();
                var tarif = parseInt($("#tarif").val().replace(/,.*|[^0-9]/g, ''), 10);

                var hargapromoakhir = tarif - vpromo;
                var persenpromoakhir = (vpromo / tarif) * 100;

                $("#hargapromo").val(convertToRupiah(hargapromoakhir));
                $("#persenpromo").val(persenpromoakhir.toFixed(2));
            });

            $("#persenpromo").keyup(function () {
                var vpromo = parseInt($("#vpromo").val().replace(/,.*|[^0-9]/g, ''), 10);
                var persenpromo = parseFloat($("#persenpromo").val());
                var tarif = parseInt($("#tarif").val().replace(/,.*|[^0-9]/g, ''), 10);

                var vpromoakhir = (persenpromo * tarif) / 100;
                var hargapromoakhir = tarif - vpromoakhir;

                $("#hargapromo").val(convertToRupiah(hargapromoakhir));
                $("#vpromo").val(convertToRupiah(vpromoakhir));
            });

            $("#hargapromo").keyup(function () {
                var vpromo = parseInt($("#vpromo").val().replace(/,.*|[^0-9]/g, ''), 10);
                var persenpromo = parseFloat($("#persenpromo").val());
                var hargapromo = parseInt($("#hargapromo").val().replace(/,.*|[^0-9]/g, ''), 10);
                var tarif = parseInt($("#tarif").val().replace(/,.*|[^0-9]/g, ''), 10);

                var valpromo = tarif - hargapromo;
                var persenpromoakhir = (valpromo / tarif) * 100;

                $("#persenpromo").val(persenpromoakhir.toFixed(2));
                $("#vpromo").val(convertToRupiah(valpromo));
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
