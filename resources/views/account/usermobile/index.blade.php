<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account /</span> User Mobile</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        {{-- <div class="card-header">

            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addModal">
                Tambah
            </button>
        </div>
        <hr class="m-0"> --}}
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>No KTP</th>
                            <th>No HP</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama_lengkap }}</td>
                                <td>{{ $d->no_ktp }}</td>
                                <td>{{ $d->telepon }}</td>
                                <td>{{ $d->email }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-icon btn-outline-warning"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $d->id }}">
                                            <span class="tf-icons bx bx-edit"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-danger"
                                            onclick="event.preventDefault(); confirmDelete('{{ $d->id }}');">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1"
                                style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Update User Mobile
                                                {{ $d->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('account.usermobile.update', $d->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div>
                                                            <label for="nama_lengkap" class="form-label">Nama
                                                                Lengkap</label>
                                                            <input type="text" id="nama_lengkap"
                                                                value="{{ $d->nama_lengkap }}" name="nama_lengkap"
                                                                class="form-control" placeholder="First Name" required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="telepon" class="form-label">Telepon</label>
                                                            <input type="number" id="telepon"
                                                                value="{{ $d->telepon }}" name="telepon"
                                                                class="form-control" placeholder="telepon" required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Tanggal
                                                                Lahir</label>
                                                            <input type="date" id="tanggal_lahir"
                                                                value="{{ $d->tanggal_lahir }}" name="tanggal_lahir"
                                                                class="form-control" placeholder="tanggal_lahir"
                                                                required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Alamat</label>
                                                            <input type="text" id="alamat"
                                                                value="{{ $d->alamat }}" name="alamat"
                                                                class="form-control" placeholder="alamat" required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Jenis
                                                                Kelamin</label>
                                                            <select type="text" id="jenis_kelamin"
                                                                value="{{ $d->jenis_kelamin }}" name="jenis_kelamin"
                                                                class="form-control" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="Laki-Laki"
                                                                    {{ $d->jenis_kelamin == 'Laki-Laki' ? 'selected' : '' }}>
                                                                    Laki-Laki</option>
                                                                <option
                                                                    value="Perempuan"{{ $d->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                                    Perempuan</option>

                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Agama</label>
                                                            <select type="text" id="agama"
                                                                value="{{ $d->agama }}" name="agama"
                                                                class="form-control" required>
                                                                <option value="">-- Pilih --</option>
                                                                @foreach ($agama as $a)
                                                                    <option value="{{ $a->var_kode }}"
                                                                        {{ $a->var_kode == $d->agama ? 'selected' : '' }}>
                                                                        {{ $a->var_nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div>
                                                            <label for="no_ktp" class="form-label">No KTP</label>
                                                            <input type="number" id="no_ktp"
                                                                value="{{ $d->no_ktp }}" name="no_ktp"
                                                                class="form-control" placeholder="Last Name">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="text" id="email"
                                                                value="{{ $d->email }}" name="email"
                                                                class="form-control" placeholder="Email">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Tempat
                                                                Lahir</label>
                                                            <input type="text" id="tempat_lahir"
                                                                value="{{ $d->tempat_lahir }}" name="tempat_lahir"
                                                                class="form-control" placeholder="tempat_lahir"
                                                                required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Domisili</label>
                                                            <input type="text" id="domisili"
                                                                value="{{ $d->domisili }}" name="domisili"
                                                                class="form-control" placeholder="domisili" required>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="status_user" class="form-label">Status</label>
                                                            <select type="text" id="status_user"
                                                                value="{{ $d->status_user }}" name="status_user"
                                                                class="form-control" required>
                                                                <option value="">-- Pilih --</option>
                                                                <option value="Aktif"
                                                                    {{ $d->status_user == 'Aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option
                                                                    value="Tidak Aktif"{{ $d->status_user == 'Tidak Aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>

                                                            </select>
                                                            <div class="mt-4">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary mt-3"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit"
                                                                    class="btn btn-primary mt-3">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('account.usermobile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" id="firstname" value="{{ old('firstname') }}"
                                        name="firstname" class="form-control" placeholder="First Name">
                                </div>
                                <div>
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" value="{{ old('username') }}"
                                        name="username" class="form-control" placeholder="Username">
                                </div>
                                <div class="mt-2">
                                    <label for="formFile" class="form-label">Foto</label>
                                    <input class="form-control" type="file" name="foto" id="formFile2"
                                        accept=".jpg,.png" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div>

                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" id="lastname" value="{{ old('lastname') }}"
                                        name="lastname" class="form-control" placeholder="Last Name">
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" value="{{ old('email') }}" name="email"
                                        class="form-control" placeholder="Email">
                                </div>
                                <div class="mt-2">
                                    <img id="preview2" src="{{ url('/assets/img/profile/user-default.png') }}"
                                        alt="" style="max-width: 100%; max-height: 100px;">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Membuat event listener change pada input file
        document.getElementById("formFile1").addEventListener("change", function(event) {
            // Mendapatkan file yang diupload
            let file = event.target.files[0];

            // Membuat objek FileReader
            let reader = new FileReader();

            // Membuat event listener untuk ketika file selesai dibaca
            reader.addEventListener("load", function() {
                // Mengganti sumber gambar pada elemen img dengan gambar yang sudah dipilih
                document.getElementById("preview1").src = reader.result;
            }, false);

            // Membaca file yang dipilih sebagai data URL
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        // Membuat event listener change pada input file
        document.getElementById("formFile2").addEventListener("change", function(event) {
            // Mendapatkan file yang diupload
            let file = event.target.files[0];

            // Membuat objek FileReader
            let reader = new FileReader();

            // Membuat event listener untuk ketika file selesai dibaca
            reader.addEventListener("load", function() {
                // Mengganti sumber gambar pada elemen img dengan gambar yang sudah dipilih
                document.getElementById("preview2").src = reader.result;
            }, false);

            // Membaca file yang dipilih sebagai data URL
            if (file) {
                reader.readAsDataURL(file);
            }
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
                    window.location.href = "/account/usermobile/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
