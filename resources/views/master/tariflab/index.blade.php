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
                    <div class="row">
                        <div class="col-md-5 mb-3">
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
                        <div class="col-md-5 mb-3">
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
                        <div class="col-md-2 mt-2">
                            <Button type="submit" class="btn btn-primary mt-4">Tampil</Button>
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->tarif_kode }}</td>
                                <td>{{ $d->tarif_nama }}</td>
                                <td>{{ $d->tarif_jalan }}</td>
                                @php
                                    $status = 'Tidak Aktif';
                                    
                                    if ($d->tarif_status == 'A') {
                                        $status = 'Aktif';
                                    }
                                @endphp
                                <td>{{ $status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tariflab.edit', $d->id) }}" type="button"
                                            class="btn btn-icon btn-outline-warning">
                                            <span class="tf-icons bx bx-edit"></span>
                                        </a>
                                        <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->id }}');">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
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
    </script>
    <script>
        $(document).ready(function() {
            $('#table1').DataTable();
        });

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
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
