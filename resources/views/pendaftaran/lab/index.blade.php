<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pendaftaran /</span> Data Pasien</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <a href="{{ route('pendaftaran.pasien.add') }}" type="button" class="btn btn-primary mt-3">
                Tambah
            </a>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Nomor RM</th>
                            <th>Nama </th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->pasien_nomor_rm }}</td>
                                <td>{{ $d->pasien_nama }}</td>
                                <td>{{ date('d-m-Y', strtotime($d->pasien_tgl_lahir)) }}</td>
                                <td>{{ $d->pasien_alamat }}</td>
                                <td>{{ $d->pasien_hp }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('pendaftaran.pasien.edit', $d->pasien_nomor_rm) }}"
                                            type="button" class="btn btn-icon btn-outline-warning">
                                            <span class="tf-icons bx bx-edit"></span>
                                        </a>
                                        <button type="button" class="btn btn-icon btn-outline-info"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $no }}">
                                            <span class="tf-icons bx bx-info-circle"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->pasien_nomor_rm }}');">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>

                                <div class="modal fade" id="detailModal{{ $no }}" tabindex="-1"
                                    aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel4">Modal title</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div>
                                                            <label class="form-label">Nomor RM<sup
                                                                    class="text-danger">*</sup></label>
                                                            <input type="text" id="NomorRM" class="form-control"
                                                                name="NomorRM" value="{{ $d->pasien_nomor_rm }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="ktp" class="form-label">No KTP<sup
                                                                    class="text-danger">*</sup></label>
                                                            <input id="ktp" class="form-control" type="number"
                                                                name="ktp" placeholder="KTP"
                                                                value="{{ $d->pasien_no_id }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label" for="nama">Nama<sup
                                                                    class="text-danger">*</sup></label>
                                                            <input type="text" id="nama" class="form-control"
                                                                name="nama" placeholder="Nama Pasien"
                                                                value="{{ $d->pasien_nama }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label" for="panggilan">Nama Panggilan<sup
                                                                    class="text-danger">*</sup>
                                                            </label>
                                                            <input type="text" id="panggilan" name="panggilan"
                                                                placeholder="Panggilan" class="form-control"
                                                                value="{{ $d->pasien_panggilan }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label" for="alamat">Alamat<sup
                                                                    class="text-danger">*</sup></label>
                                                            <textarea type="text" id="alamat" class="form-control" name="alamat" rows="3" required>{{ $d->pasien_alamat }}</textarea>

                                                        </div>

                                                        <div class="mt-2">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">RT<sup
                                                                            class="text-danger">*</sup></label>
                                                                    <input type="number" id="rt"
                                                                        class="form-control" name="rt"
                                                                        value="{{ $d->pasien_rt }}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">RW<sup
                                                                            class="text-danger">*</sup></label>
                                                                    <input type="number" id="rw"
                                                                        class="form-control" name="rw"
                                                                        value="{{ $d->pasien_rt }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label class="form-label" for="Tmplahir">Tempat
                                                                lahir<sup class="text-danger">*</sup></label>
                                                            <input type="text" id="tmplahir" name="tmplahir"
                                                                placeholder="Tempat lahir" class="form-control"
                                                                value="{{ $d->pasien_tmp_lahir }}" required>

                                                        </div>

                                                        <div class="mt-2">

                                                            <label class="form-label">Tanggal Lahir <sup
                                                                    class="text-danger">*</sup></label>
                                                            <input type="date" name="tgllahir"
                                                                class="form-control pull-right" id="single_cal1"
                                                                value="{{ $d->pasien_tgl_lahir }}" required />
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label d-block">Jenis Kelamin <sup
                                                                    class="text-danger">*</sup></label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mt-3">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="gender" id="jk"
                                                                            value="L"
                                                                            {{ $d->pasien_gender === 'L' ? 'checked' : '' }}
                                                                            required>
                                                                        <label class="form-check-label"
                                                                            for="inlineRadio1">Laki-laki</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mt-3">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="gender" id="jk"
                                                                            value="P"
                                                                            {{ $d->pasien_gender === 'P' ? 'checked' : '' }}
                                                                            required>
                                                                        <label class="form-check-label"
                                                                            for="inlineRadio1">Perempuan</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">

                                                        <div>
                                                            <label for="telepon" class="form-label">Telepon<sup
                                                                    class="text-danger">*</sup></label>
                                                            <input id="telepon" class="form-control" type="text"
                                                                name="telepon" placeholder="Telepon"
                                                                value="{{ $d->pasien_telp }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="telepon" class="form-label">No. HP<sup
                                                                    class="text-danger">*</sup></label>
                                                            <input id="HP" class="form-control" type="number"
                                                                name="HP" placeholder="HP"
                                                                value="{{ $d->pasien_hp }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label">Email <sup
                                                                    class="text-danger">*</sup></label>
                                                            <input type="email" name="email"
                                                                class="form-control pull-right" id="email"
                                                                value="{{ $d->pasien_email }}" required />
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="middle-name" class="form-label">Nama
                                                                Pekerjaan<sup class="text-danger">*</sup></label>
                                                            <input id="subkerja" class="form-control" type="text"
                                                                name="subkerja" placeholder="Nama Pekerjaan"
                                                                value="{{ $d->pasien_kerja }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label class="form-label" for="alamatkerja">Alamat
                                                                Pekerjaan<sup class="text-danger">*</sup></label>
                                                            <textarea type="text" id="alamatkerja" class="form-control" name="alamatkerja" rows="4" required>{{ $d->pasien_kerja_alamat }}</textarea>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="middle-name" class="form-label">Nama
                                                                Keluarga<sup class="text-danger">*</sup></label>
                                                            <input id="namakeluarga" class="form-control"
                                                                type="text" name="namakeluarga"
                                                                placeholder="Nama Keluarga"
                                                                value="{{ $d->pasien_klg_nama }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="middle-name" class="form-label">Alamat
                                                                Keluarga<sup class="text-danger">*</sup></label>
                                                            <input id="alamatkeluarga" class="form-control"
                                                                type="text" name="alamatkeluarga"
                                                                placeholder="Alamat Keluarga"
                                                                value="{{ $d->pasien_klg_alamat }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="telponkel" class="form-label">Telepon
                                                                Keluarga<sup class="text-danger">*</sup></label>
                                                            <input id="telponkel" class="form-control" type="number"
                                                                name="telponkel" placeholder="Telepon Keluarga"
                                                                value="{{ $d->pasien_klg_tlp }}" required>
                                                        </div>

                                                        <div class="mt-2">
                                                            <label for="telponkel" class="form-label">Kontak
                                                                Darurat<sup class="text-danger">*</sup></label>
                                                            <input id="kontakdarurat" class="form-control"
                                                                type="number" name="kontakdarurat"
                                                                placeholder="Telepon Keluarga"
                                                                value="{{ $d->pasien_kontak_darurat }}" required>

                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mt-2">
                                                            <label for="telponkel" class="form-label">Catatan
                                                                Khusus<sup class="text-danger">*</sup></label>
                                                            <textarea id="catatanKhusus" class="form-control" name="catatankhusus" placeholder="Catatan Khusus" rows="3"
                                                                required> {{ $d->pasien_catatan }}</textarea>

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
    </script>
    <script>
        $(document).ready(function() {
            $('#table1').DataTable();
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
                    window.location.href = "/pendaftaran/pasien/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
