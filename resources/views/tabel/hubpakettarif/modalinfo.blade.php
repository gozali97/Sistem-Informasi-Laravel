<div class="modal fade" id="modalInfo" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Informasi Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div>
                            <div>
                                <label for="formFile" class="form-label">Nama Paket</label>
                                <input type="text" id="paket_nama" value="" name="paket_nama"
                                       class="form-control" placeholder="" disabled>

                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="tarif_jalan" class="form-label">Tarif</label>
                            <input type="number" id="tarif_jalan" value="" name="tarif_jalan"
                                   class="form-control" placeholder="Tarif" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="tarif_nama" class="form-label">Nama Test Pemeriksaan</label>
                            <input type="text" id="tarif_nama" value="" name="tarif_nama" class="form-control"
                                   placeholder="Nama Test Pemeriksaan" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="tarif_status" class="form-label">Status</label>
                            <input type="text" id="status" value="" name="tarif_status"
                                   class="form-control" placeholder="Tarif" disabled>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea id="catatan" name="catatan" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-2">
                            <label for="manfaat" class="form-label">Manfaat</label>
                            <textarea id="manfaat" name="manfaat" class="form-control"
                                      placeholder="Tulis keterangan disini" rows="3"
                                      disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center align-items-center">
                <button type="button" class="btn btn-dark ml-3" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>
