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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Rawat Jalan</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('lab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="form-id" action="{{ route('lab.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label class="form-label">Nomor Trans</label>
                            <input type="text" name="notrans" id="notrans" class="form-control"
                                value="{{ $autoNumbers }}" readonly>
                        </div>
                        <div class="mt-2">
                            <label class="form-label"></label>
                            <div class="row">
                                <div class="col-md-3 ml-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#pasienModal">
                                        Pasien Lama
                                    </button>
                                </div>
                                <div class="col-md-3 ml-3">
                                    <a href="{{ route('pendaftaran.pasien.add') }}" class="btn btn-success"> Pasien
                                        Baru</a>
                                </div>
                                <div class="col-md-3 ml-3">
                                    <a href="#formsearch" id="caripasienapp" onclick="cariPasienApp();"
                                        class="btn btn-info" data-toggle="modal"> Pasien Appointment</a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Nomor RM</label>
                            <input type="hidden" name="pasien_rm" id="pasien_rm" class="form-control" required>
                            <input type="text" name="pasiennorm" id="pasiennorm" class="form-control" readonly>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Akun Mobile</label>
                            <input type="text" id="nama_mobile" class="form-control" readonly>
                            <input type="hidden" id="mobile_id" name="user_mobile_id">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" name="nama" id="nama" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Panggilan</label>
                            <input type="text" name="panggilan" id="panggilan" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Jenis Kelamin</label>
                            <input type="text" id="nama_jk" class="form-control" readonly>
                            <input type="hidden" name="jk" id="jk">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Umur</label>
                            <input type="hidden" id="umur" value="">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="pasienumurthn" id="pasienumurthn" class="form-control"
                                        placeholder="0" readonly> Tahun
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="pasienumurbln" id="pasienumurbln" class="form-control"
                                        placeholder="0" readonly> Bulan
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="pasienumurhari" id="pasienumurhari" class="form-control"
                                        placeholder="0" readonly> Hari
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Provinsi</label>
                            <input type="text" id="nama_provinsi" class="form-control" readonly>
                            <input type="hidden" name="provinsi" id="provinsi">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Kota</label>
                            <input type="text" id="nama_kota" class="form-control" readonly>
                            <input type="hidden" name="kota" id="kota">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" id="nama_kecamatan" class="form-control" readonly>
                            <input type="hidden" name="kecamatan" id="kecamatan">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" id="nama_kelurahan" class="form-control" readonly>
                            <input type="hidden" name="kelurahan" id="kelurahan">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" readonly></textarea>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Jenis Pasien</label>
                            <input type="text" id="nama_jenispas" class="form-control" readonly>
                            <input type="hidden" name="jenispas" id="jenispas">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Agama</label>
                            <input type="text" id="nama_agama" class="form-control" readonly>
                            <input type="hidden" name="agama" id="agama">
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Pendidikan</label>
                            <input type="text" id="nama_pendidikan" class="form-control" readonly>
                            <input type="hidden" name="pendidikan" id="pendidikan">
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" id="nama_pekerjaan" class="form-control" readonly>
                            <input type="hidden" name="pekerjaan" id="pekerjaan">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Keluarga</label>
                            <input type="text" name="keluarga" id="keluarga" class="form-control" readonly>
                        </div>

                        {{-- <div class="mt-2">
                            <a href="{{route('pendaftaran.pasien.edit')}}" type="button" id="edit-pasien-button" class="btn btn-primary pull-right" disabled>
                                Edit Pasien</a>
                        </div> --}}
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label class="form-label">Unit Dituju</label>
                            <select name="unit" id="unit" class="form-control" readonly>
                                @foreach ($unit as $unit)
                                    <option value="{{ $unit->unit_kode }}">{{ $unit->unit_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Pengirim</label>
                            <select name="pengirim" id="pengirim" class="form-control" required>
                                <option value="">-- Pilih--</option>
                                @foreach ($pengirim as $p)
                                    <option value="{{ $p->grup_kode }}">{{ $p->grup_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Nama</label>
                            <select name="namapengirim" id="namapengirim" class="form-control" required>
                                <option value="">-- Pilih--</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Penjamin</label>
                            <select name="penjamin" id="penjamin" class="form-control">
                                <option value="">-- Pilih--</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Dokter</label>
                            <select name="dokter" id="dokter" class="form-control" required>
                                <option value="">-- Pilih--</option>
                                @foreach ($dokter as $d)
                                    <option value="{{ $d->dokter_kode }}">{{ $d->dokter_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-footer d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>


    @include('pendaftaran.lab.modalPasienLama')

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
            var data; // Declare data variable outside the AJAX call

            $("#pasienModal").on("show.bs.modal", function() {
                $('#tablepasien').empty();

                $.ajax({
                    url: "/pendaftaran/lab/getPasien",
                    type: "GET",
                    success: function(result) {

                        data = result;
                        data.forEach(function(pasien, index) {
                            var row = '<tr>' +
                                '<td><input type="radio" name="pasien_radio" value="' +
                                pasien.pasien_nomor_rm + '"></td>' +
                                '<td>' + pasien.pasien_nomor_rm + '</td>' +
                                '<td>' + pasien.pasien_nama + '</td>' +
                                '<td>' + pasien.pasien_alamat + '</td>' +
                                '</tr>';
                            $('#tablepasien').append(row);
                        });
                        $('#table1').DataTable();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // Event listener for the "Pilih" button click
            $('#simpan').click(function() {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedPasienRm = $('input[name="pasien_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedPasien = data.find(function(pasien) {
                    return pasien.pasien_nomor_rm === selectedPasienRm;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedPasien) {
                    $('#pasien_rm').val(selectedPasien.pasien_nomor_rm);
                    $('#pasiennorm').val(selectedPasien.pasien_nomor_rm);
                    $('#nama_mobile').val(selectedPasien.nama_lengkap);
                    $('#mobile_id').val(selectedPasien.user_mobile_id);
                    $('#nama').val(selectedPasien.pasien_nama);
                    $('#panggilan').val(selectedPasien.pasien_panggilan);
                    $('#nama_jk').val(selectedPasien.gender);
                    $('#nama_provinsi').val(selectedPasien.prov_name);
                    $('#nama_kota').val(selectedPasien.city_name);
                    $('#nama_kecamatan').val(selectedPasien.dis_name);
                    $('#nama_kelurahan').val(selectedPasien.subdis_name);
                    $('#alamat').val(selectedPasien.pasien_alamat);
                    $('#telepon').val(selectedPasien.pasien_telp);
                    $('#nama_jenispas').val(selectedPasien.jenis);
                    $('#nama_agama').val(selectedPasien.agama);
                    $('#nama_pendidikan').val(selectedPasien.pndk);
                    $('#nama_pekerjaan').val(selectedPasien.pasien_kerja);
                    $('#keluarga').val(selectedPasien.pasien_klg_nama);

                    var birthDate = new Date(selectedPasien.pasien_tgl_lahir);
                    var today = new Date();
                    var ageInMilliseconds = today - birthDate;

                    var ageDate = new Date(ageInMilliseconds);
                    var ageYear = ageDate.getUTCFullYear() - 1970;
                    var ageMonth = ageDate.getUTCMonth();
                    var ageDay = ageDate.getUTCDate() - 1;

                    $('#pasienumurthn').val(ageYear);
                    $('#pasienumurbln').val(ageMonth);
                    $('#pasienumurhari').val(ageDay);

                    // document.getElementById("edit-pasien-button").disabled = false;
                    $('#pasienModal').modal('hide');
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#pasienModal').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
                }
            });

            $('#pengirim').change(function() {
                var kdpengirim = $(this).val();
                kdpengirim = kdpengirim.substr(0, 1);
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
                    url: '/pendaftaran/lab/getPengirim',
                    data: {
                        kdpengirim: kdpengirim,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        Swal.close();
                        $("#namapengirim").html('');
                        if (data.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data tidak ditemukan untuk perusahaan yang dipilih.',
                            });
                        } else {
                            $.each(data, function(index, value) {
                                $("#namapengirim").append('<option value="' + value
                                    .prsh_kode +
                                    '">' + value.prsh_nama + '</option>');
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memuat data.',
                        });
                    }
                });
            });

            $('#namapengirim').change(function() {
                var kdpengirim = $(this).val();
                kdpengirim = kdpengirim.substr(0, 1);
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
                    url: '/pendaftaran/lab/getPenjamin',
                    data: {
                        kdpengirim: kdpengirim,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        Swal.close();
                        $("#penjamin").html('');
                        if (data.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Data Tidak Ditemukan',
                                text: 'Data tidak ditemukan untuk perusahaan yang dipilih.',
                            });
                        } else {
                            $.each(data, function(index, value) {
                                $("#penjamin").append('<option value="' + value
                                    .prsh_kode +
                                    '">' + value.prsh_nama + '</option>');
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memuat data.',
                        });
                    }
                });
            });

            function submitForm() {
                var pasien = document.getElementById('pasien_rm');
                if (pasien.value == '') {
                    // Jika jumlah angka di input KTP kurang dari 16, tampilkan SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Data pasien kosong',
                        text: 'Anda harus memilih pasien dulu!',
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
