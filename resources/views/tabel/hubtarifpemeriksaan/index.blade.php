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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Hubungan Tarif Dan Pemeriksaan
        Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">

        <div class="card-body">
            <div class="mx-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div>
                            <label for="kode" class="form-label">Kelompok Tarif</label>
                            <select name="kode" class="js-example-basic-single custom-select form-control"
                                    id="kode">
                                <option value="">--Pilih--</option>
                                @foreach ($kode as $k)
                                    <option value="{{ $k->var_kode }}">{{ $k->var_nama }}</option>
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tarif Lab</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tabelData">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('tabel.hubtarifpemeriksaan.modalinfo')

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
                $('#modalInfo [name=tarif_tipe]').val(response.tarif_tipe);
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
                    url: '{{ route('tabel.hubtarifpemeriksaan.getData') }}',
                    data: function (d) {
                        d.kode = $('#kode').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex', searchable: false, sortable: false
                    },
                    {
                        data: 'tarif_kode'
                    },
                    {
                        data: 'tarif_nama'
                    }, {
                        data: 'tarif_jalan'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#kode').on('change', function () {
                dataTable.ajax.reload();
            });

        });
    </script>
</x-app-layout>
