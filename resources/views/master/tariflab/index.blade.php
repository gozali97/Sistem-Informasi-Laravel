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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tarif Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('tariflab.add') }}" type="button" class="btn btn-primary mt-3">
                Tambah
            </a>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <div class="mx-4">
                <form action="{{ route('tariflab.index') }}" method="GET">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="tarif" class="form-label">Kelompok Tarif</label>
                                <select name="tarif" class="js-example-basic-single custom-select form-control"
                                    id="tarif">
                                    <option value="">--Pilih--</option>
                                    @foreach ($tarif as $t)
                                        <option value="{{ $t->var_kode }}">{{ $t->var_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="paket" class="form-label">Group Paket</label>
                                <select name="paket" class="js-example-basic-single custom-select form-control"
                                    id="paket">
                                    <option value="">--Pilih--</option>
                                    @foreach ($paket as $p)
                                        <option value="{{ $p->paket_kode }}">{{ $p->paket_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
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
                            <th>Harga Tarif</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tabelData">

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
    </script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            dataTable = $('.table').DataTable({
                processing: true,
                autoWidth: false,
                serverSide: true,
                ajax: {
                    url: '{{ route('tariflab.getData') }}',
                    data: function(d) {
                        d.paket = $('#paket').val();
                        d.tarif = $('#tarif').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tarif_kode'
                    },
                    {
                        data: 'tarif_nama'
                    },
                    {
                        data: 'tarif_jalan'
                    },
                    {
                        data: 'path_gambar'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#tarif, #paket').on('change', function() {
                dataTable.ajax.reload();
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
                    window.location.href = "/master/tariflab/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
