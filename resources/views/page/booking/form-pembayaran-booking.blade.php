<!-- MODAL PEMBAYARAN -->
<div class="modal fade" id="modalPayment" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 rounded-4 shadow-sm">

            <!-- HEADER -->
            <div class="modal-header border-0 pb-0 px-4 pt-4">

                <div>
                    <h4 class="fw-bold mb-1">
                        Pembayaran Booking
                    </h4>

                    <small class="text-muted">
                        Form pembayaran booking rental mobil
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="formPayment">

                @csrf

                <div class="modal-body px-4 py-4">

                    <!-- INFO BOOKING -->
                    <div class="card border-0 bg-light rounded-4 mb-4">

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Booking ID
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control rounded-3 bg-white"
                                        name="booking_id"
                                        id="payment_booking_id" readonly>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Nama Customer
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control rounded-3 bg-white"
                                        id="payment_customer"
                                        readonly>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Nama Mobil
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control rounded-3 bg-white"
                                        id="payment_car"
                                        readonly>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Status Booking
                                    </label>

                                    <div
                                        id="payment_status_value"
                                        class="form-control rounded-3">
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Tujuan Perjalanan
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control rounded-3 bg-white"
                                        id="payment_destination"
                                        readonly>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold text-muted small">
                                        Tanggal Sewa / Perjalanan
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control rounded-3 bg-white"
                                        id="payment_dates"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- HARGA LAIN -->
                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <div>
                                    <h6 class="fw-bold mb-0">
                                        Detail Harga
                                    </h6>

                                    <small class="text-muted">
                                        Tambahkan biaya tambahan jika ada
                                    </small>
                                </div>

                                <button
                                    type="button"
                                    id="addOtherPrice"
                                    class="btn btn-primary btn-sm rounded-3">
                                    + Tambah Harga
                                </button>

                            </div>

                            <div id="otherPriceWrapper"></div>

                        </div>

                    </div>

                    <div class="mt-4 mb-4">

                        <label class="form-label fw-semibold">
                            Sisa Pembayaran
                        </label>
                        <input type="hidden" id="payment_remaining_base" value="0">
                        <input type="hidden" id="payment_dp_amount" value="0">
                        <input
                            type="text"
                            class="form-control rounded-3 bg-white fw-bold"
                            id="payment_remaining"
                            readonly>

                    </div>

                    <!-- PEMBAYARAN -->
                    <div class="mt-4 mb-4">

                        <label class="form-label fw-semibold">
                            Nominal Pembayaran
                        </label>

                        <input
                            type="text"
                            name="payment_amount"
                            id="payment_amount"
                            class="form-control form-control-lg rounded-3"
                            placeholder="Masukkan nominal pembayaran">

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0">

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3 px-4">

                        Simpan Pembayaran

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>