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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laboratorium /</span> Hasil Lab
    </h4>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div>
                        <label class="form-label">Nomor RM</label>
                        <input type="text" id="NomorRM" class="form-control" name="NomorRM"
                               placeholder="No RM">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div>
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" id="nama" class="form-control" name="nama"
                               placeholder="Nama Pasien">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Bulan</label>
                            <select id="bulan" class="form-control" name="bulan">
                                <option value="">-- Pilih Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    @php
                                        $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <option value="{{ $bulan }}">
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <select id="tahun" class="form-control" name="tahun">
                                <option value="">-- Pilih Tahun --</option>
                                @php
                                    $tahunSekarang = date('Y');
                                    $tahunMulai = $tahunSekarang - 5;
                                    $tahunAkhir = $tahunSekarang + 5;
                                @endphp
                                @for ($tahun = $tahunMulai; $tahun <= $tahunAkhir; $tahun++)
                                    <option value="{{ $tahun }}">{{ $tahun }}
                                    </option>
                                @endfor
                            </select>
                        </div>
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
                        <th>#</th>
                        <th>Lab Nomor</th>
                        <th>Nomor RM</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Pemeriksaan</th>
                        <th>Item Tes</th>
                        <th>penjamin</th>
                        <th>layanan</th>
                    </tr>
                    </thead>
                    <tbody id="tabelRegis">

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
            dataTable = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: '{{ route('inputhasillab.getData') }}',
                    data: function (d) {
                        console.log(d);
                        d.NomorRM = $('#NomorRM').val();
                        d.nama = $('#nama').val();
                        d.bulan = $('#bulan').val();
                        d.tahun = $('#tahun').val();
                    }
                },
                columns: [
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'lab_nomor'
                    },
                    {
                        data: 'pasien_nomor_rm'
                    },
                    {
                        data: 'pasien_nama'
                    },
                    {
                        data: 'lab_tanggal'
                    },
                    {
                        data: 'item_periksa'
                    },
                    {
                        data: 'prsh_nama'
                    },
                    {
                        data: 'layanan'
                    },

                ]
            });

            $('#NomorRM, #nama, #bulan, #tahun').on('change', function () {
                dataTable.ajax.reload();
            });

        });
    </script>

</x-app-layout>
