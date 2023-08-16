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
            {{--            <a href="{{ route('inputhasillab.add') }}" type="button" class="btn btn-primary mb-2">--}}
            {{--                Tambah--}}
            {{--            </a>--}}
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
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" id="start" class="form-control" name="start">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" id="end" class="form-control" name="end">
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
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($data as $d)
                        <tr>
                            <td>
                                @if($d->hasil->count() == 0)
                                    <a type="button" href="{{ route('inputhasillab.add', $d->transaksi_id) }}"
                                       class="btn rounded btn-xs btn-info">
                                        <span class="tf-icons bx bxs-pencil mb-1"></span>
                                    </a>
                                @else
                                    <div class="btn-group">
                                        <a type="button" target="_blank"
                                           href="{{ route('inputhasillab.print', $d->lab_nomor) }}"
                                           class="btn btn-outline-warning btn-sm">
                                            <span class="tf-icons bx bx-printer"></span>
                                        </a>
                                        <a type="button" href="{{ route('inputhasillab.sendEmail', $d->lab_nomor) }}"
                                           class="btn btn-outline-danger btn-sm">
                                            <span class="tf-icons bx bx-mail-send"></span>
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $d->lab_nomor }}</td>
                            <td>{{ $d->pasien_nomor_rm }}</td>
                            <td>{{ $d->pasien_nama }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->lab_tanggal)) }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm me-1 collapsed" data-bs-toggle="collapse"
                                   href="#item{{$d->transaksi_id}}" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Item Pemeriksaan
                                </a>
                                <div class="collapse" id="item{{$d->transaksi_id}}">
                                    <div class="d-grid d-sm-flex mt-1">
                                        <ol>
                                            @foreach($d->detail as $details)
                                                <li>{{$details->lab_nama}}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </td>
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
            $('#NomorRM, #nama, #start, #end').on('change', function () {
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
                    url: "{{ route('inputhasillab.getData') }}",
                    type: "GET",
                    data: {
                        NomorRM: $('#NomorRM').val(),
                        nama: $('#nama').val(),
                        start: $('#start').val(),
                        end: $('#end').val()
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
                    var layanan = (d.jenis_layanan === 0) ? 'Datang ke Hi-LAB' : 'Home Service';

                    var formattedTanggal = new Date(d.lab_tanggal).toLocaleDateString('en-GB');

                    var newRow = "<tr>" +
                        "<td>" + d.lab_nomor + "</td>" +
                        "<td>" + d.pasien_nomor_rm + "</td>" +
                        "<td>" + d.pasien_nama + "</td>" +
                        "<td>" + formattedTanggal + "</td>" +
                        "<td>" +
                        "<a class=\"btn btn-primary btn-sm me-1 collapsed\" data-bs-toggle=\"collapse\" href=\"#item" + d.transaksi_id + "\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample\">" +
                        "Item Pemeriksaan" +
                        "</a>" +
                        "<div class=\"collapse\" id=\"item" + d.transaksi_id + "\">" +
                        "<div class=\"d-grid d-sm-flex mt-1\">" +
                        "<ol>";

                    $.each(d.detail, function (indexDetail, detail) {
                        newRow += "<li>" + detail.lab_nama + "</li>";
                    });

                    newRow += "</ol>" +
                        "</div>" +
                        "</div>" +
                        "</td>" +
                        "<td>" + d.prsh_nama + "</td>" +
                        "<td>" + layanan + "</td>" +
                        "</tr>";

                    tableBody.append(newRow);
                });
            }
        });
    </script>
</x-app-layout>
