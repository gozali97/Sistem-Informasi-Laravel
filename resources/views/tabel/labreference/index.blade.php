<x-app-layout>
    @include('layouts.alerts')
    <style>
        .custom-select.select2-container .select2-selection--single {
            padding: 0.625rem;

        }

        .select2-container {
            width: 100% !important;
        }

        #table1_info {
            display: none !important;
        }
    </style>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tabel /</span> Lab Reference
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h3 class="ml-4 mt-3 fw-bold">List Lab Reference</h3>
                <div class="table-responsive text-nowrap">
                    <div class="p-4">
                        <table class="table table-striped" id="table1">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Kode Paket</th>
                                <th>Nama Paket</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-outline-info btn-info"
                                                data-lab-kode="{{ $d->lab_kode }}">
                                            <span class="tf-icons bx bx-info-circle"></span>
                                        </button>
                                    </td>
                                    <td>{{ $d->lab_kode }}</td>
                                    <td>{{ $d->lab_nama }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <h3 class="ml-4 mt-3 fw-bold">Detail Pemeriksaan</h3>
                {{--                <div class="card-body">--}}
                {{--                    <div class="row">--}}
                {{--                        <div class="col-md-3">--}}
                {{--                            <label for="nilai" class="form-label">Nilai Normal</label>--}}
                {{--                        </div>--}}
                {{--                        <div class="col-md-6">--}}
                {{--                            <div>--}}
                {{--                                <input type="hidden" name="id" class="form-control" id="id_nilai" />--}}
                {{--                                <input type="hidden" name="lab_kode" class="form-control" id="lab_kode" />--}}
                {{--                                <input type="text" name="nilai" class="form-control" id="nilai" required />--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        <div class="col-md-3">--}}
                {{--                            <button id="updateReference" type="submit" class="btn btn-primary" disabled>Simpan</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <hr class="m-0">--}}
                <div class="table-responsive text-nowrap">
                    <div class="p-4">
                        <table class="table table-striped" id="table2">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Lab</th>
                                <th>RefSex</th>
                                <th>RefBeginAge</th>
                                <th>RefEndAge</th>
                                <th>Ref</th>
                                <th>Ref SIValue</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>RefRange</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="labReferenceTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const infoButtons = document.querySelectorAll('.btn-info');
        infoButtons.forEach(button => {
            button.addEventListener('click', () => {
                const lab_kode = button.dataset.labKode;
                // document.getElementById("updateReference").disabled = true;
                // document.getElementById("nilai").value = '';

                Swal.fire({
                    title: 'Loading...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                showData(lab_kode);
            });
        });
    </script>

    <script>
        document.getElementById('updateReference').addEventListener('click', function () {
            const idNilai = document.getElementById('id_nilai').value;
            const nilai = document.getElementById('nilai').value;

            // Tampilkan loading spinner
            Swal.fire({
                title: 'Menyimpan...',
                showConfirmButton: false,
                allowOutsideClick: false, // Mencegah klik di luar alert saat loading
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            saveData(idNilai, nilai);
        });

        function saveData(id, nilai) {
            fetch(`tabel/labreference/updatereference`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: id,
                    nilai: nilai
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message
                        }).then(() => {
                            // Matikan loading setelah pesan berhasil muncul
                            Swal.close();

                            document.getElementById('id_nilai').value = '';
                            document.getElementById('nilai').value = '';
                            document.getElementById('updateReference').disabled =
                                true; // Menonaktifkan tombol setelah simpan berhasil

                            labKode = document.getElementById('lab_kode').value;

                            showData(labKode);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message
                        }).then(() => {
                            // Matikan loading setelah pesan gagal muncul
                            Swal.close();
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.close(); // Matikan loading jika terjadi kesalahan
                });
        }
    </script>

    <script>
        function showData(lab_kode) {
            // document.getElementById("updateReference").disabled = true;
            // document.getElementById("nilai").value = '';

            fetch(`/master/labreference/getreference/${lab_kode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        const noDataRow = `
                        <tr>
                            <td colspan="11" class="text-center">Data tidak tersedia</td>
                        </tr>
                    `;
                        document.getElementById("labReferenceTableBody").innerHTML = noDataRow;
                    } else {
                        const rows = data.map(item => {
                            return `
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.lab_kode}</td>
                                <td>${item.ref_sex}</td>
                                <td>${item.ref_begin_age}</td>
                                <td>${item.ref_end_age}</td>
                                <td>${item.ref_value}</td>
                                <td>${item.ref_si_value}</td>
                                <td>${item.start}</td>
                                <td>${item.end}</td>
                                <td>${item.ref_range}</td>
                                <td>
                                    <button type="button" id="fill" class="btn btn-icon btn-outline-warning fill-button">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });
                        document.getElementById("labReferenceTableBody").innerHTML = rows.join("");

                        // Assign event listener to "Fill" button
                        const fillButtons = document.querySelectorAll(".fill-button");
                        fillButtons.forEach(fillButton => {
                            fillButton.addEventListener('click', () => {
                                // Get data from table row
                                const row = fillButton.closest("tr");
                                const id = row.querySelector("td:nth-child(1)").innerText;
                                const labKode = row.querySelector("td:nth-child(2)").innerText;
                                const refValue = row.querySelector("td:nth-child(6)").innerText;

                                // Fill the form input
                                document.getElementById("id_nilai").value = id;
                                document.getElementById("nilai").value = refValue;
                                document.getElementById("lab_kode").value = labKode;

                                // Enable the "Simpan" button
                                document.getElementById("updateReference").disabled = false;
                            });
                        });
                    }
                    Swal.close();
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.close();
                });
        }
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
            $('#table2').DataTable();
        });

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
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
                    window.location.href = "/master/tariflab/destroy/" + id;
                }
            });
        }
    </script>
</x-app-layout>
