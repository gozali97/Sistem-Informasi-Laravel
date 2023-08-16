<x-app-layout>
    @push('css')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="float-right">
                @include('layouts.alerts')
            </div>
        </div>
    </div>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konfigurasi /</span> <a
            href="{{ route('roles.index') }}" class="text-secondary">Roles</a></h4>


    <div class="card">
        <div class="card-header">

            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addModal">
                Tambah
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <div class="p-4">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Permission</th>
                        <th>Nama Role</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($data->count() == 0)
                        <tr>
                            <td colspan="4" style="text-align: center">No data found</td>
                        </tr>
                    @else
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->name_role }}</td>
                                <td>
                                    <div class="btn-group">
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
                                                {{ $d->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('roles.update', $d->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameWithTitle" class="form-label">Nama
                                                            Role</label>
                                                        <input type="text" id="nameWithTitle"
                                                               value="{{ $d->name }}" name="name"
                                                               class="form-control" placeholder="Enter Name">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col mb-0">
                                                        <label for="emailWithTitle" class="form-label">Akses</label>
                                                        <input type="text" id="emailWithTitle"
                                                               value="{{ $d->guard_name }}" name="guard_name"
                                                               class="form-control" placeholder="xxxx@xxx.xx">
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

                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Insert Permission Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('roles.addPermission') }}">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameWithTitle" class="form-label">Nama
                                    Permission</label>
                                <select name="permission" class="form-control" id="permission">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($permission as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->name }}</option>
                                    @endforeach
                                </select>
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
            message: '{{ session('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            toast_success ') }}',
            position: 'topRight'
        });
        @elseif (session('toast_failed'))
        iziToast.error({
            title: 'Failed',
            message: '{{ session('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            toast_failed ') }}',
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
                    window.location.href = "/konfigurasi/roles/deletePermission/" + id;
                }
            });
        }
    </script>
</x-app-layout>
