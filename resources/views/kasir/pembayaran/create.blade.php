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

        .hide {
            display: none;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Transaksi Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <form id="form-id" action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">

        <div class="card">
            <div class="card-header">

                <a href="{{ route('transaksi.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                    Kembali
                </a>
            </div>

            @csrf
            <div class="card-body">
                <div>
                    <button type="button" class="btn rounded-pill btn-success" data-bs-toggle="modal"
                            data-bs-target="#pasienModal">
                        <span class="tf-icons bx bx-user-circle"></span>
                        Cari Pasien
                    </button>
                </div>
                <div class="row">
                    <input type="hidden" name="labnoreg" id="labnoreg">
                    <input type="hidden" name="jenispasienbarulama" id="jenispasienbarulama">
                    <input type="hidden" name="dokterkode" id="dokterkode">
                    <input type="hidden" name="prshkode" id="prshkode">
                    <input type="hidden" name="item" id="item">
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <label class="form-label">Nomor Transaksi</label>
                            <input type="text" name="notrans" id="notrans" class="form-control"
                                   value="{{ $autoNumbers }}" readonly>

                        </div>
                        <div class="mt-2">
                            <label class="form-label">Nomor RM</label>
                            <input type="hidden" name="pasien_rm" id="pasien_rm" class="form-control" required>
                            <input type="text" name="pasiennorm" id="pasiennorm" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" name="nama" id="nama" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgllahir" id="tgllahir" class="form-control" readonly>
                        </div>


                        <div class="mt-2">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <label class="form-label">Jenis Kelamin</label>
                            <input type="text" id="nama_jk" class="form-control" readonly>
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
                            <label class="form-label">Penjamin</label>
                            <input type="text" id="penjamin" name="penjamin" class="form-control" readonly>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Jenis Layanan<sup class="text-danger">*</sup></label>
                            <select name="jenis_layanan" id="jenis_layanan" class="form-control" required>
                                <option value="">-- Pilih--</option>
                                <option value="0">Datang ke Hi-Lab</option>
                                <option value="1">Home Service</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Jam Periksa<sup class="text-danger">*</sup></label>
                            <input type="time" name="jamperiksa" id="jamperiksa" class="form-control" required/>
                        </div>

                    </div>

                </div>
            </div>


        </div>

        <div class="card mt-4">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                        data-bs-target="#pasienSingle">
                    Single
                </button>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#pasienPaket">
                    Paket
                </button>
            </div>
            <div class="card-body" id="konten">
                <div class="row">
                    <div class="col-md-3">
                        <label for="form-label">Kode Tarif</label>
                        <input type="text" placeholder="Kode Tarif" class="form-control form-control-sm"
                               name="" id="kode_tarif" value="" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="">Nama Tarif</label>
                        <input type="text" placeholder="Nama Layanan" class="form-control form-control-sm"
                               name="" id="nama_pemeriksaan" value="" readonly>
                    </div>

                    <div class="col-md-3">
                        <label for="">Tarif Periksa</label>
                        <input type="text" placeholder="Tarif Periksa" class="form-control form-control-sm"
                               name="" id="harga_pemeriksaan" value="" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="">Bayar Sendiri</label>
                        <input type="number" placeholder="Bayar Sendiri" class="form-control form-control-sm"
                               name="" id="bayarsendiri" value="" readonly>
                    </div>

                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="">Disc %</label>
                        <input type="number" placeholder="Diskon %" class="form-control form-control-sm"
                               name="" id="disc" value="0" onchange="calculateBayarSendiri()">
                    </div>
                    <div class="col-md-3">
                        <label for="">Disc (Rp.)</label>
                        <input type="number" placeholder="Harga Diskon" class="form-control form-control-sm"
                               name="" id="disc_rp" value="" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="">Asuransi</label>
                        <input type="number" placeholder="Asuransi" class="form-control form-control-sm"
                               name="" id="asuransi" value="" readonly>
                    </div>
                    <div class="col-md-3" style="margin-top:30px;">
                        <button type="button" id="addMore" class="btn btn-success btn-sm" disabled>Add More
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <div class="card mt-4 hide">
            <div class="col-md-12 col-sm-9 col-xs-9 p-4">
                <table class="table responstable" id="layananTable">
                    <thead>
                    <tr>
                        <th>Kode Tarif</th>
                        <th>Nama Tarif</th>
                        <th>Tarif Periksa</th>
                        <th>Disc %</th>
                        <th>Disc (Rp.)</th>
                        <th>Asuransi</th>
                        <th>Bayar Sendiri</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody id="addlayanan" class="addlayanan">

                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="6" class="align-right ">
                            <p class="text-right">
                            <h5 class="text-right fw-bold fs-4">Total:</h5><strong></strong></p>
                        </td>
                        <td colspan="2">
                            <input style="background-color: #d0e2e6; border-color: transparent;"
                                   class="form-control text-center bg-transparent fw-bold mt-2 fs-4" type="text"
                                   id="total_tarif"
                                   name="total_tarif" value="0" readonly>

                            <input type="hidden" name="totalLab" id="totalLab">
                            <input type="hidden" name="totalHarga" id="totalHarga">
                        </td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center align-items-center">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
    @include('kasir.transaksi.modalPasienLama')
    @include('kasir.transaksi.modalSingle')
    @include('kasir.transaksi.modalPaket')

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

        function calculateBayarSendiri() {
            const discPercentage = parseFloat($('#disc').val());
            const bayarSendiri = parseFloat($('#bayarsendiri').val());

            if (!isNaN(discPercentage) && !isNaN(bayarSendiri)) {
                const discRp = (discPercentage / 100) * bayarSendiri;
                const bayarAkhir = bayarSendiri - discRp;

                $('#disc_rp').val(discRp.toFixed(2));
                $('#bayarsendiri').val(bayarAkhir.toFixed(2));
                $('#asuransi').val(bayarAkhir.toFixed(2));
            }
        }
    </script>


    <script>
        $(document).ready(function () {
            var data;

            $("#pasienModal").on("show.bs.modal", function () {
                $('#tablepasien').empty();

                $.ajax({
                    url: "/kasir/transaksi/getRawatJalan",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (pasien, index) {
                            console.log(data);
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

    <script>
        $(document).ready(function () {
            var data;

            $("#pasienSingle").on("show.bs.modal", function () {
                $('#tableTarif').empty();

                $.ajax({
                    url: "/kasir/transaksi/getPemeriksaan",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (tarif, index) {
                            var row = '<tr>' +
                                '<td><input type="radio" name="pasien_radio" value="' +
                                tarif.tarif_kode + '"></td>' +
                                '<td>' + tarif.tarif_kode + '</td>' +
                                '<td>' + tarif.tarif_nama + '</td>' +
                                '</tr>';
                            $('#tableTarif').append(row);
                        });
                        $('#table2').DataTable().draw();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#insert').click(function () {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedSingle = $('input[name="pasien_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedTarif = data.find(function (tarif) {
                    return tarif.tarif_kode === selectedSingle;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedTarif) {
                    $('#kode_tarif').val(selectedTarif.tarif_kode);
                    $('#nama_pemeriksaan').val(selectedTarif.tarif_nama);
                    $('#harga_pemeriksaan').val(selectedTarif.tarif_jalan);
                    $('#bayarsendiri').val(selectedTarif.tarif_jalan);
                    $('#disc').val(selectedTarif.promo_percent);
                    $('#disc_rp').val(selectedTarif.promo_value);
                    $('#asuransi').val(selectedTarif.tarif_jalan);

                    // document.getElementById("edit-pasien-button").disabled = false;
                    $('#pasienSingle').modal('hide');
                    $('#pasienSingle').prop('disabled', true);
                    $('#addMore').prop('disabled', false);
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#pasienSingle').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function () {
            var data;

            $("#pasienPaket").on("show.bs.modal", function () {
                $('#tablePaket').empty();

                $.ajax({
                    url: "/kasir/transaksi/getPaket",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (paket, index) {
                            var row = '<tr>' +
                                '<td><input type="radio" name="pasien_radio" value="' +
                                paket.paket_kode + '"></td>' +
                                '<td>' + paket.paket_nama + '</td>' +
                                '<td>' + paket.paket_jalan + '</td>' +
                                '</tr>';
                            $('#tablePaket').append(row);
                        });
                        $('#table3').DataTable().draw();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $('#insertPaket').click(function () {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedPaket = $('input[name="pasien_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedTarif = data.find(function (paket) {
                    return paket.paket_kode === selectedPaket;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedTarif) {
                    $('#kode_tarif').val(selectedTarif.paket_kode);
                    $('#nama_pemeriksaan').val(selectedTarif.paket_nama);
                    $('#harga_pemeriksaan').val(selectedTarif.paket_jalan);
                    $('#bayarsendiri').val(selectedTarif.paket_jalan);
                    $('#disc').val(selectedTarif.persen_diskon);
                    $('#disc_rp').val(selectedTarif.paket_diskon);
                    $('#asuransi').val(selectedTarif.paket_jalan);

                    // document.getElementById("edit-pasien-button").disabled = false;
                    $('#pasienPaket').modal('hide');
                    $('#pasienPaket').prop('disabled', true);
                    $('#addMore').prop('disabled', false);
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#pasienPaket').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
                }
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            let dataRows = [];

            function checkIfKodeTarifExists(kodeTarif) {
                return dataRows.some(row => row.kodeTarif === kodeTarif);
            }


            $('#addMore').on('click', function () {

                $('.card').removeClass('hide');

                const kodeTarif = $('#kode_tarif').val();
                const namaTarif = $('#nama_pemeriksaan').val();
                const tarifPeriksa = parseFloat($('#harga_pemeriksaan').val());
                const discPercentage = $('#disc').val();
                const discRp = parseFloat($('#disc_rp').val());
                const asuransi = parseFloat($('#asuransi').val());
                const bayarSendiri = parseFloat($('#bayarsendiri').val());


                if (checkIfKodeTarifExists(kodeTarif)) {
                    Swal.fire({
                        title: "Anda sudah menambahkan layanan ini",
                        icon: "error",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK",
                    });
                    return;
                }

                const rowData = {
                    kodeTarif,
                    namaTarif,
                    tarifPeriksa,
                    discPercentage,
                    discRp,
                    asuransi,
                    bayarSendiri
                };

                dataRows.push(rowData);

                $('#kode_tarif').val('');
                $('#nama_pemeriksaan').val('');
                $('#harga_pemeriksaan').val('');
                $('#disc').val('');
                $('#disc_rp').val('');
                $('#asuransi').val('');
                $('#bayarsendiri').val('');

                $('#item').val(kodeTarif);

                $('#addMore').prop('disabled', true);

                $('#addlayanan').empty();

                dataRows.forEach(row => {
                    const newRow = `
                                    <tr>
                                        <td><input type="hidden" value="${row.kodeTarif}" name="kodeTarif[]">${row.kodeTarif}</td>
                                        <td><input type="hidden" value="${row.namaTarif}" name="namaTarif[]">${row.namaTarif}</td>
                                        <td><input type="hidden" value="${row.tarifPeriksa}" name="tarifPeriksa[]">${row.tarifPeriksa}</td>
                                        <td><input type="hidden" value="${row.discPercentage}" name="discPercentage[]">${row.discPercentage}</td>
                                        <td><input type="hidden" value="${row.discRp}" name="discRp[]">${row.discRp}</td>
                                        <td><input type="hidden" value="${row.asuransi}" name="asuransi[]">${row.asuransi}</td>
                                        <td><input type="hidden" value="${row.bayarSendiri}" name="bayarSendiri[]">${row.bayarSendiri}</td>
                                        <td><button type="button" id="removeLayanan" class="btn btn-danger btn-sm removeLayanan">Remove</button></td>
                                    </tr>
                                `;
                    $('#addlayanan').append(newRow);
                });

                updateTotalTarif();
                $('#layananTable').DataTable()
            });

            function formatRupiah(number) {
                return "Rp. " + new Intl.NumberFormat('id-ID').format(number);
            }

            function updateTotalTarif() {
                let total = 0;
                let totalNormal = 0;
                dataRows.forEach(row => {
                    total += row.bayarSendiri;
                });
                dataRows.forEach(row => {
                    totalNormal += row.tarifPeriksa;
                });
                const formattedTotal = formatRupiah(total);
                $('#total_tarif').val(formattedTotal);

                $('#totalLab').val(total);
                $('#totalHarga').val(totalNormal);
            }

            $('#addlayanan').on('click', '.removeLayanan', function () {
                const index = $(this).closest('tr').index();

                Swal.fire({
                    title: "Apakah Anda yakin ingin menghapus layanan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        dataRows.splice(index, 1);
                        $(this).closest('tr').remove();
                        updateTotalTarif();
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
