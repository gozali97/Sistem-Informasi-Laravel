<x-app-layout>
    <style>
        .custom-select.select2-container .select2-selection--single {
            padding: 0.625rem;

        }

        .select2-container {
            width: 100% !important;
        }

        .hide {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Lokasi Hilab</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('lokasihilab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="form-id" action="{{ route('lokasihilab.update', $data->lokasi_id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nama" class="form-label">Nama Lokasi<sup class="text-danger">*</sup></label>
                            <input type="text" id="nama" value="{{ $data->nama_lokasi }}" name="nama"
                                   class="form-control" placeholder="Nama lokasi" required>
                        </div>
                        <div class="mt-2">
                            <label for="provinsi" class="form-label">Provinsi<sup class="text-danger">*</sup></label>
                            <select name="provinsi" class="js-example-basic-single custom-select form-control"
                                    id="provinsi" required>
                                <option value="">--Pilih--</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->prov_id }}"
                                        {{ $data->provinsi == $p->prov_name ? 'selected' : '' }}>
                                        {{ $p->prov_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="kota" class="form-label">Kota<sup class="text-danger">*</sup></label>
                            <select name="kota" class="js-example-basic-single custom-select form-control"
                                    id="kota" required>
                                <option value="">--Pilih--</option>
                                @foreach ($kota as $k)
                                    <option value="{{ $k->city_id }}"
                                        {{ $data->kota == $k->city_name ? 'selected' : '' }}>
                                        {{ $k->city_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="alamat" class="form-label">Alamat<sup class="text-danger">*</sup></label>
                            <textarea id="alamat" class="form-control" name="alamat"
                                      required>{{ $data->alamat }}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->status == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ $data->status == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <div>
                                    <input type="text" name="longitude" id="longitude" class="form-control"
                                           value="{{ $data->longitude }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control"
                                           value="{{ $data->latitude }}">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="box" id="map"
                                 style="height: 350px; width: 100% ; border:4px solid rgb(5, 171, 248)"></div>
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
            document.getElementById("form-id").addEventListener("submit", function () {
                document.getElementById("submitBtn").classList.add("hide");
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();

            $('#provinsi').change(function () {
                var prov_id = $(this).val();

                // Tampilkan sweetalert loading sebelum melakukan pemanggilan AJAX
                Swal.fire({
                    title: 'Loading...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Bersihkan select kota
                $('#kota').empty();

                // Tambahkan option default
                $('#kota').append('<option value="">--Pilih--</option>');

                // Lakukan AJAX request untuk mengambil data kota berdasarkan provinsi
                $.ajax({
                    url: '/master/lokasihilab/getKota',
                    type: 'POST',
                    data: {
                        prov_id: prov_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        // Tampilkan sweetalert loading
                        Swal.close();

                        // Tambahkan data kota ke dalam select kota
                        data.forEach(function (kota) {
                            $('#kota').append('<option value="' + kota.city_id + '">' +
                                kota.city_name + '</option>');
                        });

                        // Refresh tampilan select kota dengan plugin select2
                        $('#kota').trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        // Tampilkan pesan error jika terjadi masalah saat request AJAX
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi masalah saat memuat data kota. Silakan coba lagi nanti.'
                        });
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <script>
        var map = L.map('map').setView([-7.7970, 110.3708], 13);

        // Tambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(map);

        // Buat marker pin dan tambahkan ke peta
        var marker = L.marker([-7.7970, 110.3708]).addTo(map);

        // Tambahkan listener untuk mengubah posisi marker saat peta diklik
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
        });

        marker.on('click', function (e) {
            var lng = e.latlng.lat;
            var lat = e.latlng.lng;

            document.getElementById("alamat").value = '';
            document.getElementById("provinsi").value = '';
            document.getElementById("latitude").value = '';
            document.getElementById("longitude").value = '';
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + lat + "&lon=" + lng,
                true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var result = JSON.parse(xhr.responseText);
                    if (result.address.road) {
                        document.getElementById("alamat").value = result.address.road + ', ' + result.address
                            .town + ', ' + result.address.state;
                        document.getElementById("provinsi").value = result.address.state;
                    } else {
                        console.error("Data state tidak ditemukan");
                    }
                }
            };
            xhr.send();
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

</x-app-layout>
