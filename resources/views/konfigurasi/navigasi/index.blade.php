<x-app-layout>
    @include('layouts.alerts')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Konfigurasi /</span> Menu</h4>

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
                            <th>Nama Menu</th>
                            <th>Link</th>
                            <th>Icon</th>
                            <th>Menu Utama</th>
                            <th>Urutan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->url }}</td>
                                <td>{{ $d->icon }}</td>
                                @php
                                    $name = '-';
                                    if (isset($d->main_menu)) {
                                        $menu = \App\Models\Navigation::query()
                                            ->where('navigations.id', $d->main_menu)
                                            ->first();
                                        $name = $menu->name;
                                    }
                                    
                                @endphp
                                <td>{{ $name }}</td>
                                <td>{{ $d->sort }}</td>
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
                                            <h5 class="modal-title" id="modalCenterTitle">Update Role
                                                {{ $d->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('navigasi.update', $d->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div>
                                                            <label for="nameWithTitle" class="form-label">Nama
                                                                Menu</label>
                                                            <input type="text" id="nameWithTitle"
                                                                value="{{ $d->name }}" name="name"
                                                                class="form-control" placeholder="Nama Menu">
                                                        </div>
                                                        <div>
                                                            <label for="nameWithTitle" class="form-label">Icon
                                                                Menu</label>
                                                            <input type="text" id="nameWithTitle"
                                                                value="{{ $d->icon }}" name="icon"
                                                                class="form-control" placeholder="Icon Menu">
                                                        </div>
                                                        <div>
                                                            <label for="nameWithTitle" class="form-label">Sort</label>
                                                            <input type="text" id="nameWithTitle"
                                                                value="{{ $d->sort }}" name="sort"
                                                                class="form-control" placeholder="Sort">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div>

                                                            <label for="nameWithTitle" class="form-label">Link
                                                                Menu</label>
                                                            <input type="text" id="nameWithTitle"
                                                                value="{{ $d->url }}" name="url"
                                                                class="form-control" placeholder="Link Menu">
                                                        </div>
                                                        <div>

                                                            <label for="nameWithTitle" class="form-label">Main
                                                                Menu</label>
                                                            <select name="main_menu" class="form-control"
                                                                id="">
                                                                <option value="">-- Pilih --</option>
                                                                @foreach ($data as $nav)
                                                                    <option value="{{ $nav->id }}"
                                                                        {{ $nav->id == $d->main_menu ? 'selected' : '' }}>
                                                                        {{ $nav->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('navigasi.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div>
                                    <label for="nameWithTitle" class="form-label">Nama Menu</label>
                                    <input type="text" id="nameWithTitle" value="{{ old('name') }}"
                                        name="name" class="form-control" placeholder="Nama Menu">
                                </div>
                                <div>
                                    <label for="nameWithTitle" class="form-label">Icon Menu</label>
                                    <input type="text" id="nameWithTitle" value="{{ old('icon') }}"
                                        name="icon" class="form-control" placeholder="Icon Menu">
                                </div>
                                <div>
                                    <label for="nameWithTitle" class="form-label">Sort</label>
                                    <input type="text" id="nameWithTitle" value="{{ old('sort') }}"
                                        name="sort" class="form-control" placeholder="Sort">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div>

                                    <label for="nameWithTitle" class="form-label">Link Menu</label>
                                    <input type="text" id="nameWithTitle" value="{{ old('url') }}"
                                        name="url" class="form-control" placeholder="Link Menu">
                                </div>
                                <div>

                                    <label for="nameWithTitle" class="form-label">Main Menu</label>
                                    <select name="main_menu" class="form-control" id="">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($data as $nav)
                                            <option value="{{ $nav->id }}">{{ $nav->name }}</option>
                                        @endforeach
                                    </select>
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
                    window.location.href = "/konfigurasi/navigasi/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
