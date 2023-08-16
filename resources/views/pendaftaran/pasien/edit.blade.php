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

    <style>
        .custom-select.select2-container .select2-selection--single {
            padding: 0.625rem;

        }

        .select2-container {
            width: 100% !important;
        }

        .form-control.invalid {
            border-color: red;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Pasien</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('pendaftaran.pasien.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="form-id" action="{{ route('pendaftaran.pasien.update', $data->pasien_nomor_rm) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label class="form-label">Nomor RM<sup class="text-danger">*</sup></label>
                            <input type="text" id="NomorRM" class="form-control" name="NomorRM"
                                value="{{ $data->pasien_nomor_rm }}" readonly>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Title<sup class="text-danger">*</sup></label>
                            <select name="title" id="title" class="form-control" required>
                                @foreach ($Title as $title)
                                    <option value="{{ $title->var_kode }}"
                                        {{ $data->pasien_title == $title->var_kode ? 'selected' : '' }}>
                                        {{ $title->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label for="ktp" class="form-label">No KTP<sup class="text-danger">*</sup></label>
                            <input id="ktp" class="form-control" type="number" name="ktp" placeholder="KTP"
                                value="{{ $data->pasien_no_id }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="nama">Nama<sup class="text-danger">*</sup></label>
                            <input type="text" id="nama" class="form-control" name="nama"
                                placeholder="Nama Pasien" value="{{ $data->pasien_nama }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="panggilan">Nama Panggilan<sup class="text-danger">*</sup>
                            </label>
                            <input type="text" id="panggilan" name="panggilan" placeholder="Panggilan"
                                class="form-control" value="{{ $data->pasien_panggilan }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="alamat">Alamat<sup class="text-danger">*</sup></label>
                            <textarea type="text" id="alamat" class="form-control" name="alamat" rows="3" required>{{ $data->pasien_alamat }}</textarea>

                        </div>

                        <div class="mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">RT<sup class="text-danger">*</sup></label>
                                    <input type="number" id="rt" class="form-control" name="rt"
                                        value="{{ $data->pasien_rt }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">RW<sup class="text-danger">*</sup></label>
                                    <input type="number" id="rw" class="form-control" name="rw"
                                        value="{{ $data->pasien_rt }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="wilayah">Provinsi<sup class="text-danger">*</sup></label>
                            <select name="provinsi" id="provinsi" class="form-control js-example-basic-single"
                                required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->prov_id }}"
                                        {{ $data->pasien_prov == $p->prov_id ? 'selected' : '' }}>{{ $p->prov_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="wilayah2">Kota<sup class="text-danger">*</sup></label>
                            <select name="kota" id="kota" class="form-control js-example-basic-single"
                                required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($kota as $city)
                                    <option value="{{ $city->city_id }}"
                                        {{ $data->pasien_kota == $city->city_id ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="wilayah3">Kecamatan<sup
                                    class="text-danger">*</sup></label>
                            <select name="kecamatan" id="kecamatan" class="form-control js-example-basic-single"
                                required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->dis_id }}"
                                        {{ $data->pasien_kecamatan == $kec->dis_id ? 'selected' : '' }}>
                                        {{ $kec->dis_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="kelurahan">Kelurahan<sup
                                    class="text-danger">*</sup></label>
                            <select name="kelurahan" id="kelurahan" class="form-control js-example-basic-single"
                                required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($kelurahan as $kel)
                                    <option value="{{ $kel->subdis_id }}"
                                        {{ $data->pasien_kelurahan == $kel->subdis_id ? 'selected' : '' }}>
                                        {{ $kel->subdis_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="Tmplahir">Tempat
                                lahir<sup class="text-danger">*</sup></label>
                            <input type="text" id="tmplahir" name="tmplahir" placeholder="Tempat lahir"
                                class="form-control" value="{{ $data->pasien_tmp_lahir }}" required>

                        </div>

                        <div class="mt-2">

                            <label class="form-label">Tanggal Lahir <sup class="text-danger">*</sup></label>
                            <input type="date" name="tgllahir" class="form-control pull-right" id="single_cal1"
                                value="{{ $data->pasien_tgl_lahir }}" required />
                        </div>

                        <div class="mt-2">
                            <label class="form-label d-block">Jenis Kelamin <sup class="text-danger">*</sup></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="gender" id="jk"
                                            value="L" {{ $data->pasien_gender === 'L' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label" for="inlineRadio1">Laki-laki</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="gender" id="jk"
                                            value="P" {{ $data->pasien_gender === 'P' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label" for="inlineRadio1">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="jenispasien">Jenis
                                Pasien <sup class="text-danger">*</sup></label>
                            <select name="jenispasien" id="jenispasien" class="form-control" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($JenisPasien as $jenispasien)
                                    <option value="{{ $jenispasien->var_kode }}"
                                        {{ $data->pasien_jenis == $jenispasien->var_kode ? 'selected' : '' }}>
                                        {{ $jenispasien->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="jenispasien">Nama Akun
                                Mobile</label>
                            <select name="user_mobile_id" id="user_mobile_id" class="form-control">
                                <option value=""> -- Pilih --</option>
                                @foreach ($user_mobile as $mobile)
                                    <option value="{{ $mobile->id }}"
                                        {{ $data->user_mobile_id == $mobile->id ? 'selected' : '' }}>
                                        {{ $mobile->nama_lengkap }}</option>
                                @endforeach
                                <option value="0">Tidak ada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">

                        <div>
                            <label for="telepon" class="form-label">Telepon<sup class="text-danger">*</sup></label>
                            <input id="telepon" class="form-control" type="text" name="telepon"
                                placeholder="Telepon" value="{{ $data->pasien_telp }}" required>
                        </div>

                        <div class="mt-2">
                            <label for="telepon" class="form-label">No. HP<sup class="text-danger">*</sup></label>
                            <input id="HP" class="form-control" type="number" name="HP"
                                placeholder="HP" value="{{ $data->pasien_hp }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Email <sup class="text-danger">*</sup></label>
                            <input type="email" name="email" class="form-control pull-right" id="email"
                                value="{{ $data->pasien_email }}" required />
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="kawin">Kawin<sup class="text-danger">*</sup></label>
                            <select name="kawin" id="kawin" class="form-control" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($StatusKwn as $AdmVarsKwn)
                                    <option value="{{ $AdmVarsKwn->var_kode }}"
                                        {{ $data->pasien_status_kw == $AdmVarsKwn->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsKwn->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="item form-group">
                            <label class="form-label" for="agama">Agama<sup class="text-danger">*</sup></label>
                            <select name="agama" class="form-control" id="agama" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($Religion as $AdmVarsAgama)
                                    <option value="{{ $AdmVarsAgama->var_kode }}"
                                        {{ $data->pasien_agama == $AdmVarsAgama->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsAgama->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="darah">Darah<sup class="text-danger">*</sup></label>
                            <select name="darah" id="darah" class="form-control" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($GolDarah as $AdmVarsDarah)
                                    <option value="{{ $AdmVarsDarah->var_kode }}"
                                        {{ $data->pasien_gol_darah == $AdmVarsDarah->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsDarah->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="pendidikan">Pendidikan<sup
                                    class="text-danger">*</sup></label>
                            <select name="pendidikan" class="form-control" id="pendidikan" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($Pendidikan as $AdmVarsPendidikan)
                                    <option value="{{ $AdmVarsPendidikan->var_kode }}"
                                        {{ $data->pasien_pdk == $AdmVarsPendidikan->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsPendidikan->var_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="pekerjaan">Pekerjaan<sup
                                    class="text-danger">*</sup></label>
                            <select name="pekerjaan" class="form-control" id="pekerjaan" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($Pekerjaan as $AdmVarsPekerjaan)
                                    <option value="{{ $AdmVarsPekerjaan->var_kode }}"
                                        {{ $data->pasien_kerja_kode == $AdmVarsPekerjaan->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsPekerjaan->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label for="middle-name" class="form-label">Nama
                                Pekerjaan<sup class="text-danger">*</sup></label>
                            <input id="subkerja" class="form-control" type="text" name="subkerja"
                                placeholder="Nama Pekerjaan" value="{{ $data->pasien_kerja }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="alamatkerja">Alamat
                                Pekerjaan<sup class="text-danger">*</sup></label>
                            <textarea type="text" id="alamatkerja" class="form-control" name="alamatkerja" rows="4" required>{{ $data->pasien_kerja_alamat }}</textarea>
                        </div>

                        <div class="mt-2">
                            <label for="middle-name" class="form-label">Nama
                                Keluarga<sup class="text-danger">*</sup></label>
                            <input id="namakeluarga" class="form-control" type="text" name="namakeluarga"
                                placeholder="Nama Keluarga" value="{{ $data->pasien_klg_nama }}" required>
                        </div>

                        <div class="mt-2">
                            <label for="middle-name" class="form-label">Alamat
                                Keluarga<sup class="text-danger">*</sup></label>
                            <input id="alamatkeluarga" class="form-control" type="text" name="alamatkeluarga"
                                placeholder="Alamat Keluarga" value="{{ $data->pasien_klg_alamat }}" required>
                        </div>

                        <div class="mt-2">
                            <label class="form-label" for="hubungankel">Hubungan
                                Keluarga<sup class="text-danger">*</sup></label>
                            <select name="hubungankel" id="hubungankel" class="form-control" required>
                                <option value=""> -- Pilih --</option>
                                @foreach ($Family as $AdmVarsKeluarga)
                                    <option value="{{ $AdmVarsKeluarga->var_kode }}"
                                        {{ $data->pasien_klg_hub == $AdmVarsKeluarga->var_kode ? 'selected' : '' }}>
                                        {{ $AdmVarsKeluarga->var_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label for="telponkel" class="form-label">Telepon
                                Keluarga<sup class="text-danger">*</sup></label>
                            <input id="telponkel" class="form-control" type="number" name="telponkel"
                                placeholder="Telepon Keluarga" value="{{ $data->pasien_klg_tlp }}" required>
                        </div>

                        <div class="mt-2">
                            <label for="telponkel" class="form-label">Kontak Darurat<sup
                                    class="text-danger">*</sup></label>
                            <input id="kontakdarurat" class="form-control" type="number" name="kontakdarurat"
                                placeholder="Telepon Keluarga" value="{{ $data->pasien_kontak_darurat }}" required>

                        </div>

                        <div class="mt-2">
                            <label for="telponkel" class="form-label">Catatan Khusus<sup
                                    class="text-danger">*</sup></label>
                            <textarea id="catatanKhusus" class="form-control" name="catatankhusus" placeholder="Catatan Khusus" rows="3"
                                required> {{ $data->pasien_catatan }}</textarea>

                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script></script>

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
        function validateKTP() {
            var ktpInput = document.getElementById('ktp');
            if (ktpInput.value.length < 16) {
                // Jika panjang input KTP kurang dari 16 angka, tambahkan class "invalid"
                ktpInput.classList.add('invalid');
            } else {
                // Jika input KTP sudah sesuai, hapus class "invalid"
                ktpInput.classList.remove('invalid');
            }
        }

        // Event listener untuk memanggil fungsi validateKTP saat input berfokus atau nilai berubah
        var ktpInput = document.getElementById('ktp');
        ktpInput.addEventListener('input', validateKTP);
        ktpInput.addEventListener('focus', validateKTP);

        function submitForm() {
            var ktpInput = document.getElementById('ktp');
            if (ktpInput.value.length < 16) {
                // Jika jumlah angka di input KTP kurang dari 16, tampilkan SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'No KTP Tidak Valid',
                    text: 'Jumlah angka pada No KTP harus minimal 16 digit!',
                    confirmButtonText: 'OK'
                });
                return false; // Jangan submit form
            }
            return true; // Submit form jika valid
        }

        // Event listener untuk memanggil fungsi submitForm saat form disubmit
        var form = document.getElementById('form-id');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit otomatis
            if (submitForm()) {
                form.submit(); // Submit form jika valid
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#table1').DataTable();

            $('.js-example-basic-single').select2();

            $('#provinsi').change(function() {
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

                $.ajax({
                    url: '/pendaftaran/pasien/getKota',
                    type: 'POST',
                    data: {
                        prov_id: prov_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Tampilkan sweetalert loading
                        Swal.close();

                        // Tambahkan data kota ke dalam select kota
                        data.forEach(function(kota) {
                            $('#kota').append('<option value="' + kota.city_id + '">' +
                                kota.city_name + '</option>');
                        });

                        // Refresh tampilan select kota dengan plugin select2
                        $('#kota').trigger('change.select2');
                    },
                    error: function(xhr, status, error) {
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

            $('#kota').change(function() {
                var city_id = $(this).val();

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
                $('#kecamatan').empty();

                // Tambahkan option default
                $('#kecamatan').append('<option value="">--Pilih--</option>');

                $.ajax({
                    url: '/pendaftaran/pasien/getKecamatan',
                    type: 'POST',
                    data: {
                        city_id: city_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Tampilkan sweetalert loading
                        Swal.close();

                        // Tambahkan data kota ke dalam select kota
                        data.forEach(function(kec) {
                            $('#kecamatan').append('<option value="' + kec.dis_id +
                                '">' +
                                kec.dis_name + '</option>');
                        });

                        // Refresh tampilan select kota dengan plugin select2
                        $('#kecamatan').trigger('change.select2');
                    },
                    error: function(xhr, status, error) {
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

            $('#kecamatan').change(function() {
                var dis_id = $(this).val();

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
                $('#kelurahan').empty();

                // Tambahkan option default
                $('#kelurahan').append('<option value="">--Pilih--</option>');

                $.ajax({
                    url: '/pendaftaran/pasien/getKelurahan',
                    type: 'POST',
                    data: {
                        dis_id: dis_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Tampilkan sweetalert loading
                        Swal.close();

                        // Tambahkan data kota ke dalam select kota
                        data.forEach(function(kel) {
                            $('#kelurahan').append('<option value="' + kel.subdis_id +
                                '">' +
                                kel.subdis_name + '</option>');
                        });

                        // Refresh tampilan select kota dengan plugin select2
                        $('#kelurahan').trigger('change.select2');
                    },
                    error: function(xhr, status, error) {
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
