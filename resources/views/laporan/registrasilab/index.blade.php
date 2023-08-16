<x-app-layout>
    @include('layouts.alerts')
    <style>
        .custom-select.select2-container .select2-selection--single {
            padding: 0.625rem;

        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Kunjungan Pasien
    </h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div>
                        <label class="form-label">Nomor RM</label>
                        <input type="text" id="NomorRM" class="form-control" name="NomorRM"
                               placeholder="No RM">
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Pengirim</label>
                        <select name="pengirim" id="pengirim"
                                class="js-example-basic-single custom-selectform-control">
                            <option value="">-- Pilih--</option>
                            @foreach ($pengirim as $p)
                                <option value="{{ $p->grup_kode }}">{{ $p->grup_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div>
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" id="nama" class="form-control" name="nama"
                               placeholder="Nama Pasien">
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Layanan Tes</label>
                        <select name="layanan" id="layanan"
                                class="js-example-basic-single custom-select form-control">
                            <option value="">-- Pilih--</option>
                            <option value="0">Datang ke Hi-LAB</option>
                            <option value="1">Home Service</option>
                        </select>

                    </div>

                </div>
                <div class="col-md-4 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" id="start" class="form-control" name="start">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" id="end" class="form-control" name="end">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Penjamin</label>
                        <select name="penjamin" id="penjamin"
                                class="js-example-basic-single custom-select form-control">
                            <option value="">-- Pilih--</option>
                            @foreach ($penjamin as $pj)
                                <option value="{{ $pj->prsh_kode }}">{{ $pj->prsh_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>Nomor RM</th>
                        <th>NO KTP</th>
                        <th>Nama Pasien</th>
                        <th>Jenis kelamin</th>
                        <th>alamat</th>
                        <th>umur</th>
                        <th>tgl daftar</th>
                        <th>pengirim</th>
                        <th>penjamin</th>
                        <th>layanan</th>
                    </tr>
                    </thead>
                    <tbody id="tabelRegis">
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->pasien_nomor_rm }}</td>
                            <td>{{ $d->pasien_no_id }}</td>
                            <td>{{ $d->pasien_nama }}</td>
                            @php
                                $gender = 'Laki-Laki';
                                if ($d->pasien_gender == 'P') {
                                    $gender = 'Perempuan';
                                }
                            @endphp
                            <td>{{ $gender }}</td>
                            <td>{{ $d->pasien_alamat }}</td>
                            @php
                                $tglLahir = new DateTime($d->pasien_tgl_lahir);
                                $tglSekarang = new DateTime();
                                $umurInterval = $tglLahir->diff($tglSekarang);
                                $umur = $umurInterval->y;
                            @endphp
                            <td>{{ $umur }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->jalan_tanggal)) }}</td>
                            <td>{{ $d->grup_nama }}</td>
                            <td>{{ $d->prsh_nama }}</td>
                            @php
                                $layanan = 'Home Service';

                                if ($d->jenis_layanan == '0') {
                                    $layanan = 'Datang ke Hi-LAB';
                                }
                            @endphp
                            <td>{{ $layanan }}</td>
                        </tr>

                        @php
                            $no++;
                        @endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
            $('.js-example-basic-single').select2();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#table1').DataTable();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#NomorRM, #nama, #start, #end, #layanan, #pengirim, #penjamin').on('change', function () {
                fetchFilteredData();
            });

            function fetchFilteredData() {
                Swal.fire({
                    title: 'Loading...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('rekapkunjunganlab.getData') }}",
                    type: "GET",
                    data: {
                        NomorRM: $('#NomorRM').val(),
                        nama: $('#nama').val(),
                        start: $('#start').val(),
                        end: $('#end').val(),
                        layanan: $('#layanan').val(),
                        pengirim: $('#pengirim').val(),
                        penjamin: $('#penjamin').val()
                    },
                    success: function (data) {
                        updateTable(data);
                        Swal.close();
                    },
                    error: function (error) {
                        console.error(error);
                        Swal.close();
                    }
                });
            }

            function updateTable(data) {
                var tableBody = $('#tabelRegis');
                tableBody.empty();

                $.each(data, function (index, d) {
                    var tglLahir = new Date(d.pasien_tgl_lahir);
                    var tglSekarang = new Date();
                    var umurInterval = tglSekarang.getFullYear() - tglLahir.getFullYear();
                    var bulanSekarang = tglSekarang.getMonth();
                    var bulanLahir = tglLahir.getMonth();

                    if (bulanLahir > bulanSekarang || (bulanLahir === bulanSekarang && tglLahir.getDate() >
                        tglSekarang.getDate())) {
                        umurInterval--;
                    }

                    var gender = (d.pasien_gender === 'P') ? 'Perempuan' : 'Laki-Laki';
                    var layanan = (d.jenis_layanan === 0) ? 'Datang ke Hi-LAB' : 'Home Service';

                    var formattedTanggal = new Date(d.jalan_tanggal).toLocaleDateString('en-GB');

                    var newRow = "<tr>" +
                        "<td>" + d.pasien_nomor_rm + "</td>" +
                        "<td>" + d.pasien_no_id + "</td>" +
                        "<td>" + d.pasien_nama + "</td>" +
                        "<td>" + gender + "</td>" +
                        "<td>" + d.pasien_alamat + "</td>" +
                        "<td>" + umurInterval + "</td>" +
                        "<td>" + formattedTanggal + "</td>" +
                        "<td>" + d.grup_nama + "</td>" +
                        "<td>" + d.prsh_nama + "</td>" +
                        "<td>" + layanan + "</td>" +

                        "</tr>";

                    tableBody.append(newRow);
                });
            }
        });
    </script>
</x-app-layout>
