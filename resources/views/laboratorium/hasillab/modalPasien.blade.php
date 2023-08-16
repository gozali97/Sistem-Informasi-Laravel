<div class="modal fade" id="pasienModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Data Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="table1">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor RM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                    </thead>
                    <tbody id="tablepasien">
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button class="btn btn-info" id="simpan">Pilih</button>
                <button type="button" class="btn btn-dark ml-3" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>
