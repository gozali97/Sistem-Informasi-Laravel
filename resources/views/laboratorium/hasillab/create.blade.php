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

        .hide {
            display: none;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Input Hasil Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <form id="form-id" action="{{ route('inputhasillab.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <a href="{{ route('inputhasillab.index') }}" type="button" class="btn btn-outline-secondary mb-2">
            Kembali
        </a>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div>
                                <h4>Input Hasil Pemeriksaan Lab</h4>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <div class="mt-3">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Jenis Pasien</th>
                                            <th>Umur</th>
                                            <th>Nama Pemeriksaan</th>
                                            <th>Hasil</th>
                                            <th>Satuan</th>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $pasien)
                                            <tr>
                                                <input type="hidden" id="labnomor" name="labnomor[]"
                                                       value="{{$pasien->lab_nomor}}">
                                                <input type="hidden" id="pasiennomor" name="pasiennomor[]"
                                                       value="{{$pasien->pasien_nomor_rm}}">
                                                <input type="hidden" id="mobile" name="mobile[]"
                                                       value="{{$pasien->user_mobile_id}}">
                                                <input type="hidden" id="labkode" name="labkode[]"
                                                       value="{{$pasien->lab_kode}}">
                                                <input type="hidden" id="labnama" name="labnama[]"
                                                       value="{{$pasien->lab_nama}}">
                                                <input type="hidden" id="labsatuan" name="labsatuan[]"
                                                       value="{{$pasien->lab_satuan}}">
                                                <input type="hidden" id="labtarif" name="labtarif"
                                                       value="{{$pasien->lab_jumlah}}">

                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$pasien->pasien_nomor_rm}}</td>
                                                <td>{{$pasien->pasien_nama}}</td>
                                                <td>{{$pasien->pasien_gender}}</td>
                                                <td>{{$pasien->pasien_umur_thn}}</td>
                                                <td>{{$pasien->lab_nama}}</td>
                                                <td><input type="text" name="labhasil[]"
                                                           class="form-control text-center bg-transparent mt-2"
                                                           required>
                                                </td>
                                                <td>{{$pasien->lab_satuan}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body" id="konten">
                <div class="row">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" required></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center align-items-center">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

    </form>
    @include('laboratorium.hasillab.modalPasien')

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
        $(document).ready(function () {
            $('.table').DataTable({
                "scrollY": "350px",
                "scrollCollapse": true,
                "paging": false
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            var data;

            $("#pasienModal").on("show.bs.modal", function () {
                $('#tablepasien').empty();

                $.ajax({
                    url: "/laboratorium/inputhasillab/getRawatJalan",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (pasien, index) {
                            var formattedTanggal = new Date(pasien.jalan_tanggal).toLocaleDateString('en-GB');
                            var row = '<tr>' +
                                '<td><input type="radio" name="pasien_radio" value="' +
                                pasien.pasien_nomor_rm + '"></td>' +
                                '<td>' + pasien.pasien_nomor_rm + '</td>' +
                                '<td>' + pasien.pasien_nama + '</td>' +
                                '<td>' + pasien.pasien_alamat + '</td>' +
                                '<td>' + formattedTanggal + '</td>' +
                                '</tr>';
                            $('#tablepasien').append(row);
                        });

                        $('#table1').DataTable();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // Event listener for the "Pilih" button click
            $('#simpan').click(function () {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedPasienRm = $('input[name="pasien_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedPasien = data.find(function (pasien) {
                    return pasien.pasien_nomor_rm === selectedPasienRm;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedPasien) {
                    $('#pasien_rm').val(selectedPasien.pasien_nomor_rm);
                    $('#pasiennorm').val(selectedPasien.pasien_nomor_rm);
                    $('#tgllahir').val(selectedPasien.pasien_tgl_lahir);
                    $('#nama').val(selectedPasien.pasien_nama);
                    $('#nama_jk').val(selectedPasien.gender);
                    $('#alamat').val(selectedPasien.pasien_alamat);
                    $('#telepon').val(selectedPasien.pasien_telp);
                    $('#nama_jenispas').val(selectedPasien.jenis);
                    $('#penjamin').val(selectedPasien.prsh_nama);

                    $('#pasienumurthn').val(selectedPasien.pasien_umur_thn)
                    $('#pasienumurbln').val(selectedPasien.pasien_umur_bln)
                    $('#pasienumurhari').val(selectedPasien.pasien_umur_hr)

                    $('#labnoreg').val(selectedPasien.jalan_no_reg)
                    $('#jenispasienbarulama').val(selectedPasien.jalan_pas_baru)
                    $('#dokterkode').val(selectedPasien.dokter_kode)
                    $('#prshkode').val(selectedPasien.prsh_kode)

                    // document.getElementById("edit-pasien-button").disabled = false;
                    $('#pasienModal').modal('hide');
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#pasienModal').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
                }
            });

        });
    </script>

</x-app-layout>
