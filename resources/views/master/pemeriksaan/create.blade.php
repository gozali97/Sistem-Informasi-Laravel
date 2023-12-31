<x-app-layout>
    <style>
        .hide {
            display: none;
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Pemeriksaan Laboratorium</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('pemeriksaan.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('pemeriksaan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="grup_nama" class="form-label">Nama Pemeriksaan<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="grup_nama" value="{{ old('grup_nama') }}" name="grup_nama"
                                   class="form-control" placeholder="Nama Pemeriksaan" required>
                        </div>
                        <div class="mt-3">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ old('status') == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ old('status') == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi<sup class="text-danger">*</sup></label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                                  placeholder="Tulis keterangan disini" rows="5"
                                  required>{{ old('deskripsi') }}</textarea>
                        <span id="deskripsi-error" class="text-danger"></span>

                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button id="submitBtn" type="submit" class="btn btn-primary">Save changes</button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("myForm").addEventListener("submit", function () {
                document.getElementById("submitBtn").classList.add("hide");
            });

            document.getElementById('grup_nama').addEventListener('input', function () {
                if (this.value.length > 49) {
                    this.value = this.value.substring(0, 49);
                }
            });
            document.getElementById('deskripsi').addEventListener('input', function () {
                if (this.value.length > 254) {
                    this.value = this.value.substring(0, 254);
                }
            });

        });
    </script>

    <script>
        document.getElementById('deskripsi').addEventListener('input', function () {
            var input = this.value;
            var minLength = 10;
            var errorElement = document.getElementById('deskripsi-error');

            if (input.length >= minLength) {
                errorElement.textContent = '';
            } else {
                errorElement.textContent = 'Minimal 10 karakter diperlukan.';
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
                    window.location.href = "/account/user/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
