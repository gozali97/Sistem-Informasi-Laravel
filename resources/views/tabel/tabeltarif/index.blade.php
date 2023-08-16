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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Tarif Laboratorium</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">
            <h2>Daftar Tarif Lab</h2>
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
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->tarif_kode }}</td>
                            <td>{{ $d->tarif_nama }}</td>
                            <td>{{ $d->tarif_jalan }}</td>
                            <td>
                                @if ($d->path_gambar)
                                    <img src="{{ url($d->path_gambar) }}" alt class="w-px-50 h-auto rounded"/>
                                @else
                                    <img src="https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster" alt
                                         class="w-px-50 h-auto rounded"/>
                                @endif
                            </td>
                            @php
                                $status = 'Tidak Aktif';

                                if ($d->tarif_status == 'A') {
                                    $status = 'Aktif';
                                }
                            @endphp
                            <td>{{ $status }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-icon btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $d->id }}">
                                        <span class="tf-icons bx bx-info-circle"></span>
                                    </button>
                                </div>
                            </td>
                            <div class="modal fade" id="detailModal{{ $d->id }}" tabindex="-1"
                                 aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel4">Detail Tarif Lab</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <label for="tarif_kelompok" class="form-label">Nama Kategori
                                                            Pemeriksaan</label>
                                                        <select name="tarif_kelompok" class="form-control"
                                                                id="tarif_kelompok" disabled>
                                                            <option value="">--Pilih--</option>
                                                            @foreach ($tarif as $t)
                                                                <option value="{{ $t->var_kode }}"
                                                                    {{ $d->tarif_kelompok == $t->var_kode ? 'selected' : '' }}>
                                                                    {{ $t->var_nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="tarif_nama" class="form-label">Nama Test
                                                            Pemeriksaan</label>
                                                        <input type="text" id="tarif_nama" value="{{ $d->tarif_nama }}"
                                                               name="tarif_nama"
                                                               class="form-control" placeholder="Nama Test Pemeriksaan"
                                                               readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="tarif_jalan"
                                                               class="form-label">Tarif</label>
                                                        <input type="number" id="tarif_jalan"
                                                               value="{{ $d->tarif_jalan }}"
                                                               name="tarif_jalan" class="form-control"
                                                               placeholder="Tarif" readonly>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label for="tarif_status"
                                                               class="form-label">Status</label>
                                                        <select name="tarif_status" class="form-control"
                                                                id="tarif_status" readonly>
                                                            <option value="">--Pilih--</option>
                                                            <option
                                                                value="A" {{ $d->tarif_status == 'A' ? 'selected' : '' }}>
                                                                Aktif
                                                            </option>
                                                            <option
                                                                value="N" {{ $d->tarif_status == 'N' ? 'selected' : '' }}>
                                                                Tidak
                                                                Aktif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <div class="row">
                                                            <label for="formFile" class="form-label">Gambar</label>
                                                            @if ($d->path_gambar)
                                                                <img id="preview" src="{{ url($d->path_gambar) }}"
                                                                     alt class="w-75 h-auto rounded"/>
                                                            @else
                                                                <img id="preview"
                                                                     src="https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster"
                                                                     alt
                                                                     class="w-75 h-auto rounded"/>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="catatan" class="form-label">Catatan</label>
                                                        <textarea id="catatan" name="catatan" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->catatan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mt-2">
                                                        <label for="manfaat" class="form-label">Manfaat</label>
                                                        <textarea id="manfaat" name="manfaat" class="form-control"
                                                                  placeholder="Tulis keterangan disini" rows="3"
                                                                  readonly>{{ $d->manfaat }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="card-footer d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-dark"
                                                        data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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
        $(document).ready(function () {
            $('#table1').DataTable();
        });

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        $(document).ready(function () {

            var number = 1;

            $('#tarif, #paket').on('change', function () {
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
                    url: "{{ route('tariflab.getData') }}",
                    type: "GET",
                    data: {
                        tarif: $('#tarif').val(),
                        paket: $('#paket').val(),
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
                var tableBody = $('#tabelData');
                tableBody.empty();

                $.each(data, function (index, d) {
                    console.log(data);
                    var imagePath = d.path_gambar ? d.path_gambar : "https://fakeimg.pl/300x200/071952/FFF/?text=Sample&font=lobster";
                    var status = d.tarif_status == "A" ? "Aktif" : "Tidak Aktif";

                    var editLink = "{{ route('tariflab.edit', ':id') }}".replace(':id', d.id);

                    var newRow = "<tr>" +
                        "<td>" + (index + 1) + "</td>" +
                        "<td>" + d.tarif_kode + "</td>" +
                        "<td>" + d.tarif_nama + "</td>" +
                        "<td>" + d.tarif_jalan + "</td>" +
                        "<td><img src='" + imagePath + "' alt='Image' class='w-px-50 h-auto rounded'></td>" +
                        "<td>" + status + "</td>" +
                        "<td><div class='btn-group'>" +
                        "<a href='" + editLink + "' type='button' class='btn btn-icon btn-outline-warning'> <span class='tf-icons bx bx-edit'></span> </a> " +
                        "<button type='button' class='btn btn-icon btn-outline-danger' onclick='event.preventDefault(); confirmDelete(" + d.id + ");'> <span class='tf-icons bx bx-trash'></span> </button>" +
                        "</div></td>" +
                        "</tr>";

                    tableBody.append(newRow);
                });

            }
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
