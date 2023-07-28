<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account /</span> User</h4>

    <!-- Basic Bootstrap Table -->

    <div class="card">
        <div class="card-header">

            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addModal">
                Tambah
            </button>
        </div>
        <hr class="m-0">
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Foto</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->first_name }} {{ $d->last_name }}</td>
                                <td>{{ $d->username }}</td>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->email }}</td>
                                <td>
                                    @if ($d->foto)
                                        <img src="{{ url('/assets/img/profile/', $d->foto) }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    @else
                                        <img src="{{ url('/assets/img/profile/user-default.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    @endif

                                </td>
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
                                            <h5 class="modal-title" id="modalCenterTitle">Update User
                                                {{ $d->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('account.user.update', $d->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div>
                                                            <label for="firstname" class="form-label">First Name</label>
                                                            <input type="text" id="firstname"
                                                                value="{{ $d->first_name }}" name="firstname"
                                                                class="form-control" placeholder="First Name">
                                                        </div>
                                                        <div>
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" id="username"
                                                                value="{{ $d->username }}" name="username"
                                                                class="form-control" placeholder="Username">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="formFile" class="form-label">Foto</label>
                                                            <input class="form-control" type="file" name="gambar"
                                                                id="formFile1" accept=".jpg,.png" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div>

                                                            <label for="lastname" class="form-label">Last Name</label>
                                                            <input type="text" id="lastname"
                                                                value="{{ $d->last_name }}" name="lastname"
                                                                class="form-control" placeholder="Last Name">
                                                        </div>
                                                        <div>
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="text" id="email"
                                                                value="{{ $d->email }}" name="email"
                                                                class="form-control" placeholder="Email">
                                                        </div>
                                                        <div class="mt-2">

                                                            @if ($d->foto)
                                                                <img id="preview1"
                                                                    src="{{ url('/assets/img/profile/' . $d->foto) }}"
                                                                    alt=""
                                                                    style="max-width: 100%; max-height: 100px;">
                                                            @else
                                                                <img id="preview1"
                                                                    src="{{ url('/assets/img/profile/user-default.png') }}"
                                                                    alt class="w-px-40 h-auto rounded-circle" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        changes</button>
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
                <form action="{{ route('account.user.store') }}" method="POST" enctype="multipart/form-data">
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
                    window.location.href = "/account/user/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
