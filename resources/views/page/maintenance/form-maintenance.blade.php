<div class="card border-0 shadow-sm">

    <div class="card-header">
        Tambah Maintenance
    </div>

    <div class="card-body">

        <form id="maintenanceForm">

            @csrf

            <input type="hidden"
                name="car_id"
                value="{{ $car->id }}">

            <input type="hidden"
                name="unit"
                value="{{ $car->unit }}">

            <div class="mb-3">
                <label class="form-label">
                    Tanggal Maintenance
                </label>

                <input
                    type="date"
                    name="maintenance_date"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">

                <label>
                    Nama Maintenance
                </label>

                <input
                    type="text"
                    name="workshop_name"
                    class="form-control" required>

            </div>

            <div class="mb-3">
                <label class="form-label">
                    KM Kendaraan
                </label>

                <input
                    type="number"
                    name="km_vehicle"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Spek Oli
                </label>

                <input
                    type="text"
                    name="oil_spec"
                    class="form-control">
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        KM Ganti Oli Sebelumnya
                    </label>
                    
                    <input
                        id="previous_oil_km"
                        type="number"
                        name="previous_oil_km"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        KM Ganti Oli Selanjutnya
                    </label>

                    <input
                        type="number"
                        name="next_oil_km"
                        class="form-control">

                </div>

            </div>

            <h6 class="mt-3 mb-2">
                Kode Ban
            </h6>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>
                        Ban Depan Kanan
                    </label>

                    <input
                        type="text"
                        name="front_right_tire"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>
                        Ban Depan Kiri
                    </label>

                    <input
                        type="text"
                        name="front_left_tire"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>
                        Ban Belakang Kanan
                    </label>

                    <input
                        type="text"
                        name="rear_right_tire"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>
                        Ban Belakang Kiri
                    </label>

                    <input
                        type="text"
                        name="rear_left_tire"
                        class="form-control">

                </div>

            </div>

            <div class="mb-3">

                <label>
                    Biaya Maintenance
                </label>

                <input
                    type="number"
                    name="cost"
                    class="form-control">

            </div>

            <div class="mb-3">

                <label>
                    Catatan
                </label>

                <textarea
                    name="notes"
                    rows="3"
                    class="form-control"></textarea>

            </div>

            <button
                type="submit"
                class="btn btn-primary w-100">

                Simpan Maintenance

            </button>

        </form>

    </div>

</div>