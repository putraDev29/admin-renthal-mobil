<style>
    /* ===============================
   SELECT2 DRIVER
=============================== */

    .select2-container--default .select2-selection--single {
        position: relative !important;
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 8px !important;
        padding-right: 55px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        right: 28px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        margin-right: 0 !important;
        font-size: 18px !important;
        z-index: 2;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        height: auto !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        margin-top: 0 !important;
    }
</style>
<div class="modal fade" id="bookingModalForm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Form Booking Rental Mobil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="bookingForm" autocomplete="off">
                    @csrf

                    <input type="hidden" name="booking_id" id="booking_id">
                    <input type="text" style="display:none">
                    <input type="password" style="display:none">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nama Penyewa</label>
                            <select id="customerSelect" select2 class="form-control" required></select>
                            <input type="hidden" name="customer_name" id="customer_name" class="form-control"
                                autocomplete="off">
                            <input type="hidden" name="customer_id" id="customer_id" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>NIK</label>
                            <input type="number" name="nik" id="nik" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>No HP</label>
                            <input type="number" name="phone" id="phone" class="form-control"
                                autocomplete="new-password" autocorrect="off" autocapitalize="off" spellcheck="false"
                                inputmode="numeric" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label>Alamat</label>
                            <textarea name="address" id="address" class="form-control" rows="2" autocomplete="new-password"
                                autocorrect="off" autocapitalize="off" spellcheck="false" inputmode="text" required></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Pilih Mobil</label>
                            <select id="carSelect" class="form-control" required></select>
                            <input type="hidden" name="car_id" id="car_id" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3" style="display: none;">
                            <label>Harga/Hari</label>
                            <input type="hidden" name="price" id="price" value="0" class="form-control"
                                readonly>
                            <input type="text" id="price_text" value="0" class="form-control" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Nama Driver</label>
                            <select id="driverSelect" class="form-control" select2 required></select>
                            <input type="hidden" name="driver_id" id="driver_id" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3" style="display: none;">
                            <label>Harga Driver</label>
                            <input type="text" name="price_driver" id="price_driver" value="0"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3" style="display: none;">
                            <label>Total Pembayaran</label>
                            <input type="hidden" name="total_price" id="total_price" class="form-control" required>
                            <input type="text" id="total_price_text" class="form-control" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label>Tujuan Perjalanan</label>
                            <textarea name="destination" id="destination" class="form-control" required></textarea>
                        </div>

                        <div class="col-6 mb-3">
                            <label>Titik Penjemputan</label>
                            <textarea name="pickup" id="pickup" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="Belum Bayar">Belum Bayar</option>
                                <option value="DP">DP</option>
                                <option value="Lunas">Lunas</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Jumlah DP</label>
                            <input type="text" name="dp_amount" id="dp_amount" value="0"
                                class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn-reservasi w-100">
                        Simpan Booking
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>