<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{route('hubtarifpaket.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Tambah Relasi Tarif & Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <div>
                                    <label for="tarif_kode" class="form-label">Nama Tarif</label>
                                    <select name="tarif_kode" class="form-control" id="tarif_kode">
                                        <option value="">--Pilih--</option>
                                        @foreach ($lab as $lab)
                                            <option value="{{ $lab->tarif_kode }}">
                                                {{ $lab->tarif_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="paket_kode" class="form-label">Nama Paket</label>
                                <select name="paket_kode" class="form-control" id="paket_kode">
                                    <option value="">--Pilih--</option>
                                    @foreach ($paket as $p)
                                        <option value="{{ $p->paket_kode }}">
                                            {{ $p->paket_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="card-footer d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-dark mr-3" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
