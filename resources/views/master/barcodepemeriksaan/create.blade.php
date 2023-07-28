<x-app-layout>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Barcode Pemeriksaan</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('barcodepemeriksaan.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form action="{{ route('barcodepemeriksaan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nama" class="form-label">Nama<sup class="text-danger">*</sup></label>
                            <input type="text" id="nama" value="{{ old('nama') }}" name="nama"
                                class="form-control" placeholder="Nama" required>
                        </div>
                        <div class="mt-2">
                            <label for="sign" class="form-label">Sign<sup class="text-danger">*</sup></label>
                            <input type="text" id="sign" value="{{ old('sign') }}" name="sign"
                                class="form-control" placeholder="Sign" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="instrument" class="form-label">Tnstrument<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="instrument" value="{{ old('instrument') }}" name="instrument"
                                class="form-control" placeholder="instrument" required>
                        </div>
                        <div class="mt-2">
                            <label for="copy" class="form-label">Copy<sup class="text-danger">*</sup></label>
                            <input type="text" id="copy" value="{{ old('copy') }}" name="copy"
                                class="form-control" placeholder="Copy" required>
                        </div>

                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>

        </form>
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
                    window.location.href = "/account/user/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
