<div class="modal fade" id="modalCar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0">

                <h5 class="modal-title fw-bold">
                    Form Mobil
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form id="formCar">

                <input type="hidden" id="car_id">

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label>Unit</label>

                            <input
                                type="text"
                                id="unit"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Nama Mobil</label>

                            <input
                                type="text"
                                id="name"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Nomor Polisi</label>

                            <input
                                type="text"
                                id="plate_number"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Total Unit</label>

                            <input
                                type="number"
                                id="total_unit"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Harga Rental</label>

                            <input
                                type="number"
                                id="price"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Tanggal Pajak</label>

                            <input
                                type="date"
                                id="tax"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Tanggal KIR</label>

                            <input
                                type="date"
                                id="kir"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Status</label>

                            <select
                                id="status"
                                class="form-select"
                            >

                                <option value="available">
                                    Available
                                </option>

                                <option value="maintenance">
                                    Maintenance
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Tutup
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>