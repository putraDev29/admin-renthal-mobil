<div class="modal fade" id="modalCashFlow" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-0">

                <h5 class="modal-title fw-bold">
                    Form Cash Flow
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <form id="formCashFlow">

                <input type="hidden" id="cashflow_id">

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label>Tanggal</label>

                            <input
                                type="date"
                                id="transaction_date"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label>Jenis</label>

                            <select
                                id="type"
                                class="form-select"
                            >

                                <option value="income">
                                    Pemasukan
                                </option>
                                <option value="expense">
                                    Pengeluaran
                                </option>
                            </select>

                        </div>

                        <div class="col-md-12">

                            <label>Title</label>

                            <input
                                type="text"
                                id="title"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-12">

                            <label>Keterangan</label>

                            <textarea
                                id="description"
                                class="form-control"
                            ></textarea>

                        </div>

                        <div class="col-md-12">

                            <label>Nominal</label>

                            <input
                                type="text"
                                id="amount"
                                class="form-control"
                            >

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