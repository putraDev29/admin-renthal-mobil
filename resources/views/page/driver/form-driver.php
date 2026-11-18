<div class="modal fade" id="modalDriver" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0">

                <h5 class="modal-title fw-bold">
                    Form Driver
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>

            </div>

            <form id="formDriver">

                <input type="hidden" id="driver_id">

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6" style="display: none;">

                            <label>Kode Driver</label>

                            <input
                                type="text"
                                id="kode_driver"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-12">

                            <label>Nama Driver</label>

                            <input
                                type="text"
                                id="nama"
                                class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label>No HP</label>

                            <input
                                type="number"
                                id="no_hp"
                                class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label>Email</label>

                            <input
                                type="email"
                                id="email"
                                class="form-control">

                        </div>

                        <div class="col-md-12">

                            <label>Alamat</label>

                            <textarea
                                id="alamat"
                                class="form-control"
                                rows="3"></textarea>

                        </div>

                        <div class="col-md-6">

                            <label>Nomor SIM</label>

                            <input
                                type="text"
                                id="no_sim"
                                class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label>Masa Berlaku SIM</label>

                            <input
                                type="date"
                                id="masa_berlaku_sim"
                                class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label>Status</label>

                            <select
                                id="status"
                                class="form-select">

                                <option value="aktif">
                                    Aktif
                                </option>

                                <option value="nonaktif">
                                    Nonaktif
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Tutup
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>