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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Ubah TTD Dokter</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header">

            <a href="{{ route('ttddokter.index') }}" type="button" class="btn btn-outline-secondary mt-3">
                Kembali
            </a>
        </div>

        <form id="myForm" action="{{ route('ttddokter.update', $data->id) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="dokter" class="form-label">Nama Dokter<sup class="text-danger">*</sup></label>
                            <select class="form-control" id="dokter" name="dokter" required>
                                <option value="">--Pilih--</option>
                                @foreach ($dokter as $d)
                                    <option value="{{ $d->dokter_kode }}"
                                        {{ $data->dokter_id == $d->dokter_kode ? 'selected' : '' }}>
                                        {{ $d->dokter_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="keterangan" class="form-label">Keterangan<sup
                                    class="text-danger">*</sup></label>
                            <input type="text" id="keterangan" value="{{ $data->keterangan }}" name="keterangan"
                                   class="form-control" placeholder="keterangan" required>
                        </div>
                        <div class="mt-2">
                            <label for="status" class="form-label">Status<sup class="text-danger">*</sup></label>
                            <select name="status" class="form-control" id="status" required>
                                <option value="">--Pilih--</option>
                                <option value="A" {{ $data->status == 'A' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="N" {{ $data->status == 'N' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                        <div class="mt-2 row">
                            <div class="col-md-8">
                                <label for="formFile" class="form-label">Tanda Tangan Baru<sup
                                        class="text-danger">*</sup></label>
                                <div id="defaultSignature"
                                     style="border-style: solid!important; border-color:darkgray!important;"></div>
                                <button id="clear" class="btn btn-danger btn-sm mt-2">Clear Signature</button>
                                <textarea id="signature64" name="signed"
                                          style="display: none">{{ old('signed') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ml-5">
                        <div>
                            <label for="formFile" class="form-label">Tanda Tangan Lama</label>
                            <img id="preview" class="rounded" src="{{ url('/images/ttd/' . $data->ttd) }}"
                                 alt="" width="400px" height="200" style="border:4px solid grey">
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

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
          rel="stylesheet">
    <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{ url('/assets/js/jquery.signature.js') }}"></script>

    <script type="text/javascript">
        var sig = $('#defaultSignature').signature({
            syncField: '#signature64',
            syncFormat: 'PNG'
        });

        $('#clear').click(function (e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });

        // Submit form validation
        $('form').submit(function (e) {
            if ($("#signature64").val() === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tanda tangan dokter wajib diisi!',
                });
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

</x-app-layout>
