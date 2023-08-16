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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah Perusahaan</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('perusahaan.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('perusahaan.update', $data->prsh_kode) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="kode" class="form-label">Jenis Perusahaan<sup
                                    class="text-danger">*</sup></label>
                            <select name="kode" id="kode" class="form-control" required>
                                <option value="">--Pilih--</option>
                                @foreach ($pengirim as $p)
                                    <option value="{{ $p->grup_kode }}"
                                        {{ (explode('-', $data->prsh_kode)[0] == $p->grup_kode) == $p->grup_kode ? 'selected' : '' }}>
                                        {{ $p->grup_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="nama" class="form-label">Nama Perusahaan<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama" value="{{ $data->prsh_nama }}" name="nama"
                                   class="form-control" placeholder="Nama Perusahaan" required>
                        </div>
                        <div class="mt-3">
                            <label for="no_hp" class="form-label">No Handphone<sup
                                    class="text-danger">*</sup></label>
                            <input type="number" id="no_hp" value="{{ $data->prsh_kontak }}" name="no_hp"
                                   class="form-control" placeholder="No Handphone" required>
                        </div>

                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="alamat" class="form-label">Alamat<sup class="text-danger">*</sup></label>
                            <textarea id="alamat" name="alamat" class="form-control" placeholder="Tulis alamat disini"
                                      rows="4" required>{{ $data->prsh_alamat_1 }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->prsh_status == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ $data->prsh_status == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
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
