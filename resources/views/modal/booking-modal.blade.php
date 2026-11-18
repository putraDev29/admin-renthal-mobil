<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    Detail Booking
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Customer</small>
                    <div class="fw-semibold" id="modalCustomer" style="font-size: 14px"></div>
                </div>

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Unit</small>
                    <div class="fw-semibold" id="modalCar" style="font-size: 14px"></div>
                </div>

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Tanggal</small>
                    <div class="fw-semibold" id="modalDate" style="font-size: 14px"></div>
                </div>

                <div class="mb-3">
                <small class="text-muted" style="font-size: 12px">Penjemputan</small>
                    <div>
                        <span class="badge rounded-pill px-3 py-2 bg-primary fw-semibold"
                            id="modalPickup"
                            style="font-size: 14px">
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Tujuan</small>
                    <div>
                        <span class="badge rounded-pill px-3 py-2 bg-info fw-semibold"
                            id="modalDestination" style="font-size: 14px">
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted" id="modalDriverLabel">Driver</small>
                    <div class="border rounded-3 p-2 bg-light fw-semibold" style="font-size: 14px"
                        id="modalDriver">
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Total Harga</small>
                    <div class="fw-semibold" id="modalTotalprice" style="font-size: 14px"></div>
                    
                </div>

                <div class="mb-3">
                    <small class="text-muted" style="font-size: 12px">Status</small>

                    <div>
                        <span class="badge rounded-pill px-3 py-2 fw-semibold" style="font-size: 14px"
                            id="modalStatus">
                        </span>
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button"
                    class="btn btn-secondary rounded-pill px-4"
                    data-bs-dismiss="modal">

                    Tutup

                </button>
            </div>

        </div>
    </div>
</div>