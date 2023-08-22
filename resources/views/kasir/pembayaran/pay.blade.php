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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kasir /</span> Bayar Transaksi Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <form id="form-id" action="{{ route('pembayaran.storePay') }}" method="POST" enctype="multipart/form-data">

        <div class="card">
            <div class="card-header">

                <a href="{{ route('transaksi.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                    Kembali
                </a>
            </div>

            @csrf
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="labnomor" id="labnomor" value="{{$data->lab_nomor}}">
                    <input type="hidden" name="mobile" id="mobile" value="{{$data->user_mobile_id}}">
                    <input type="hidden" name="asuransi" id="asuransi" value="{{$data->lab_asuransi}}">
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <label class="form-label">Tanggal Trans</label>
                                    <input type="date" name="tgltrans" id="tgltrans" value="{{ date('Y-m-d') }}"
                                           class="form-control">
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Nomor RM</label>
                                    <input type="text" name="pasiennorm" value="{{$data->pasien_nomor_rm}}"
                                           id="pasiennorm" class="form-control" readonly>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Nama Pasien</label>
                                    <input type="text" name="nama" id="nama" value="{{$data->pasien_nama}}"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2">
                                    <label class="form-label">Nomor Transaksi</label>
                                    <input type="text" name="notrans" id="notrans" class="form-control"
                                           value="{{ $autoNumbers }}" readonly>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Nomor Reg</label>
                                    <input type="text" name="nomorreg" value="{{$data->jalan_no_reg}}" id="nomorreg"
                                           class="form-control" readonly>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="1"
                                              readonly>{{$data->pasien_alamat}}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="m-0 mt-3">
                        <div class="table-responsive text-nowrap">
                            <div class="p-4">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Banyak</th>
                                        <th>Tarif</th>
                                        <th>Diskon</th>
                                        <th>Jumlah</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($detail as $d)
                                        <tr>
                                            <td>{{ $d->lab_nama }}</td>
                                            <td>{{ $d->lab_banyak }}</td>
                                            <td>RP. {{ number_format($d->lab_tarif, 0, ',', '.') }}</td>
                                            <td>
                                                @if($d->lab_diskon == 'NaN')
                                                    RP. 0
                                                @else
                                                    RP. {{ number_format( $d->lab_diskon, 0, ',', '.') }}</td>
                                            @endif

                                            <td>
                                                @if($d->lab_pribadi == 'NaN')
                                                    RP. 0
                                                @else
                                                    RP. {{ number_format($d->lab_pribadi, 0, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="mt-2">
                            <label class="form-label">Jumlah Transaksi</label>
                            <input type="text" id="jml" name="jml"
                                   value="RP. {{ number_format($data->lab_jumlah, 0, ',', '.') }}"
                                   class="form-control" readonly>

                            <input type="hidden" id="jml_total" name="jml_total"
                                   value="{{round($data->lab_jumlah)}}">
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-10">
                                <label class="form-label">Jenis Potongan </label>
                                <input type="text" id="namapot" name="namapot" class="form-control" readonly>
                                <input type="hidden" id="kodepot" name="kodepot" class="form-control">
                            </div>
                            <div class="col-md-2 mt-4">
                                <button type="button" class="btn mt-1 btn-icon btn-info btn-md" data-bs-toggle="modal"
                                        data-bs-target="#modalDiskon"><span
                                        class="tf-icons bx bxs-plus-square"></span>
                                </button>
                            </div>

                        </div>
                        <div class="mt-2">
                            <label class="form-label">Potongan (%)</label>
                            <input type="text" id="potongan" name="potongan" class="form-control" max="99" readonly>
                            <input type="hidden" id="potonganVal" name="potonganVal" class="form-control">
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Penjamin</label>
                            <input type="text" name="penjamin" id="penjamin" value="{{$data->prsh_nama}}"
                                   class="form-control" readonly/>
                            <input type="hidden" name="prshkode" id="prshkode" value="{{$data->prsh_kode}}"/>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p class="fw-bold mt-1">Jumlah Yang Harus dibayar : </p>
                            </div>
                            <div class="col-md-6">
                                <input type="text"
                                       class="form-control text-center bg-transparent border-0 fw-bold fs-2"
                                       value="Rp. {{ number_format($data->lab_jumlah, 0, ',', '.') }}" id="jumlahTotal">
                            </div>
                            <input type="hidden" name="totbayar" id="totbayar" value="{{$data->lab_jumlah}}"
                                   class="form-control"/>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Tunai</label>
                                <input type="number" name="tunai" id="tunai" placeholder="0" class="form-control"
                                       onchange="calculateChange()" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kembali</label>
                                <input type="number" name="kembali" id="kembali" value="0"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-10">
                                <label class="form-label">Kartu Kredit/Debit</label>
                                <input type="text" id="namakartu" name="namakartu" class="form-control" readonly>
                                <input type="hidden" id="kodekartu" name="kodekartu" class="form-control">
                            </div>
                            <div class="col-md-2 mt-4">
                                <button type="button" class="btn mt-1 btn-icon btn-warning btn-md"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKartu"><span
                                        class="tf-icons bx bxs-credit-card"></span>
                                </button>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Kartu</label>
                                <input type="text" id="cardnomor" name="cardnomor" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik Kartu</label>
                                <input type="text" id="cardatasnama" name="cardatasnama" class="form-control">
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-center align-items-center">
            <button type="submit" class="btn btn-success">Save changes</button>
        </div>
    </form>
    @include('kasir.pembayaran.modalDiskon')
    @include('kasir.pembayaran.modalKartu')

    <script>
        $(document).ready(function () {
            $('#table1').DataTable();

            var total = parseInt($('#jml_total').val());

            $('#potongan').on('input', function () {
                var value = $(this).val();
                if (value.length > 2) {
                    $(this).val(value.slice(0, 2));
                }
            });

            $('#potongan').on('change', function () {
                var potonganPersen = parseFloat($(this).val()) || 0;
                var potongan = (potonganPersen / 100) * total;
                var newTotal = total - potongan;

                $('#jumlahTotal').val('Rp. ' + newTotal.toLocaleString('id-ID'));
                $('#totbayar').val(newTotal);
                $('#potonganVal').val(potongan);
            });

            $('form').on('submit', function (event) {
                var kembali = parseInt($('#kembali').val()) || 0;
                if (kembali < 0) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pembayaran harus lunas',
                    });
                }
            });
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

        function calculateChange() {
            var tunai = parseInt($('#tunai').val()) || 0;
            var totbayar = parseInt($('#totbayar').val()) || 0;
            var kembali = tunai - totbayar;

            $('#kembali').val(kembali);

        }
    </script>

    <script>
        $(document).ready(function () {
            var data;

            $("#modalDiskon").on("show.bs.modal", function () {
                $('#tbpotongan').empty();

                $.ajax({
                    url: "/kasir/pembayaran/getDiskon",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (diskon, index) {
                            var row = '<tr>' +
                                '<td><input type="radio" name="diskon_radio" value="' +
                                diskon.var_kode + '"></td>' +
                                '<td>' + diskon.var_nama + '</td>' +
                                '</tr>';
                            $('#tbpotongan').append(row);
                        });

                        $('#table2').DataTable().draw();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // Event listener for the "Pilih" button click
            $('#insert').click(function () {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedDiskon = $('input[name="diskon_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedDiskon = data.find(function (diskon) {
                    return diskon.var_kode === selectedDiskon;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedDiskon) {
                    $('#kodepot').val(selectedDiskon.var_kode);
                    $('#namapot').val(selectedDiskon.var_nama);
                    $('#potongan').val(selectedDiskon.var_kode);

                    if (selectedDiskon.var_kode !== '0') {
                        $('#potongan').removeAttr('readonly');
                    } else {
                        $('#potongan').attr('readonly', 'readonly');
                    }

                    $('#modalDiskon').modal('hide');
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#modalDiskon').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function () {
            var data;

            $("#modalKartu").on("show.bs.modal", function () {
                $('#tbkartu').empty();

                $.ajax({
                    url: "/kasir/pembayaran/getKartu",
                    type: "GET",
                    success: function (result) {

                        data = result;
                        data.forEach(function (kartu, index) {
                            var row = '<tr>' +
                                '<td><input type="radio" name="kartu_radio" value="' +
                                kartu.kartukrdkode + '"></td>' +
                                '<td>' + kartu.kartukrdkode + '</td>' +
                                '<td>' + kartu.kartukrdnama + '</td>' +
                                '</tr>';
                            $('#tbkartu').append(row);
                        });

                        $('#table3').DataTable().draw();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // Event listener for the "Pilih" button click
            $('#simpan').click(function () {
                // Get the selected pasien_nomor_rm from the radio buttons
                var selectedKartu = $('input[name="kartu_radio"]:checked').val();

                // Find the selected pasien in the data array
                var selectedKartu = data.find(function (kartu) {
                    return kartu.kartukrdkode === selectedKartu;
                });

                // If a pasien is selected, populate the form inputs with their information
                if (selectedKartu) {
                    $('#kodekartu').val(selectedKartu.kartukrdkode);
                    $('#namakartu').val(selectedKartu.kartukrdnama);
                    $('#potongan').val(selectedKartu.var_kode);

                    $('#modalKartu').modal('hide');
                } else {
                    // Show an alert using SweetAlert if no pasien is selected
                    $('#modalKartu').modal('hide');
                    Swal.fire('Error', 'Pilih salah satu pasien', 'error');
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
