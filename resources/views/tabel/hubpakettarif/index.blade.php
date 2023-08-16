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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Hubungan Tarif Dan Paket
        Laboratorium
    </h4>
    <div class="card">

        <div class="card-body">
            <div class="mx-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="paket_kode" class="form-label">Paket Laboratorium</label>
                            <select name="paket_kode" class="js-example-basic-single custom-select form-control"
                                    id="paket_kode">
                                <option value="">--Pilih--</option>
                                @foreach ($paket as $p)
                                    <option value="{{ $p->paket_kode }}">{{ $p->paket_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Paket Kode</th>
                        <th>Tarif Kode</th>
                        <th>Nama</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tabelData">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('master.hubtarifpaket.modalinfo')
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
        function editForm(url) {
            $('#modalInfo').modal('show');
            $('#modalInfo .modal-title').text('Detail Pemeriksaan Lab');

            $.get(url).done((response) => {
                $('#modalInfo [name=paket_nama]').val(response.paket_nama);
                $('#modalInfo [name=tarif_nama]').val(response.tarif_nama);
                $('#modalInfo [name=tarif_jalan]').val(response.tarif_jalan);
                var status = 'Tidak Aktif';
                if (response.tarif_status == 'A') {
                    status = 'Aktif'
                }
                $('#modalInfo [name=tarif_status]').val(status);
                $('#modalInfo [name=catatan]').val(response.catatan);
                $('#modalInfo [name=deskripsi]').val(response.deskripsi);
                $('#modalInfo [name=manfaat]').val(response.manfaat);
            }).fail((errors) => {
                alert('tidak ada  data');
                return;
            })
        }

        $(document).ready(function () {
            $('.js-example-basic-single').select2();

            dataTable = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: '{{ route('tabel.hubpakettarif.getData') }}',
                    data: function (d) {
                        d.paket_kode = $('#paket_kode').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex', searchable: false, sortable: false
                    },
                    {
                        data: 'paket_kode'
                    },
                    {
                        data: 'tarif_kode'
                    },
                    {
                        data: 'tarif_nama'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#paket_kode').on('change', function () {
                dataTable.ajax.reload();
            });

        });
    </script>


</x-app-layout>
