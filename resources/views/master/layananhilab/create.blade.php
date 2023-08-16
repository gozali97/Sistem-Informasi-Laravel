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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Tambah Layanan Hilab</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('layananhilab.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('layananhilab.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nama" class="form-label">Nama Layanan<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="nama" value="{{ old('nama') }}" name="nama"
                                   class="form-control" placeholder="Nama" required>
                        </div>

                        <div class="mt-2">
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
                    <div class="col-md-6">
                        <div>
                            <label for="keterangan" class="form-label">Keterangan<sup
                                    class="text-danger">*</sup></label>
                            <textarea class="form-control" name="keterangan" id="keterangan" rows="4"
                                      required>{{ old('keterangan') }}</textarea>
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
</x-app-layout>
