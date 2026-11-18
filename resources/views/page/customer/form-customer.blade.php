<div class="modal fade" id="modalCustomer" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0">

                <h5 class="modal-title fw-bold">
                    Form Customer
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form id="formCustomer">

                <input type="hidden" id="customer_id">

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label>NIK</label>

                            <input
                                type="number"
                                id="nik"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Name</label>

                            <input
                                type="text"
                                id="name"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Jenis Kelamin</label>

                            <select
                                id="gender"
                                class="form-select"
                            >

                                <option value="Laki-laki">
                                    Laki-laki
                                </option>

                                <option value="Perempuan">
                                    Perempuan
                                </option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label>Nomor Hp</label>

                            <input
                                type="number"
                                id="phone"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-12">

                            <label>Address</label>

                            <textarea
                                id="address"
                                class="form-control"
                            ></textarea>

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