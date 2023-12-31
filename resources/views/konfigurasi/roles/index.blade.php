<x-app-layout>
    @push('css')
    @endpush
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konfigurasi /</span> Roles</h4>

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
                        <th>Nama Role</th>
                        <th>Akses</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->guard_name }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-icon btn-outline-warning"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $d->id }}">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </button>
                                    <a type="button" class="btn btn-icon btn-outline-info"
                                       href="{{ route('roles.view', $d->id) }}">
                                        <span class="tf-icons bx bx-info-circle"></span>
                                    </a>
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
                                        <h5 class="modal-title" id="modalCenterTitle">Update Role
                                            {{ $d->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('roles.update', $d->id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Nama Role<sup
                                                            class="text-danger">*</sup></label>
                                                    <input type="text" id="nameWithTitle"
                                                           value="{{ $d->name }}" name="name"
                                                           class="form-control" placeholder="Enter Name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('roles.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameWithTitle" class="form-label">Nama Role<sup
                                        class="text-danger">*</sup></label>
                                <input type="text" id="nameWithTitle" value="{{ old('name') }}" name="name"
                                       class="form-control" placeholder="nama role" required>
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
                    window.location.href = "/konfigurasi/roles/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
