// ===============================
// FORMAT RUPIAH (GLOBAL HELPER)
// ===============================
function formatRupiah(angka) {
    angka = parseInt(angka) || 0;
    return 'Rp ' + angka.toLocaleString('id-ID');
}
window.formatRupiah = formatRupiah;

// ===============================
// CLEAN NUMBER (GLOBAL HELPER)
// ===============================
function cleanNumber(value) {
    return parseInt(
        String(value || '').replace(/[^0-9]/g, '')
    ) || 0;
}
window.cleanNumber = cleanNumber;

// ===============================
// HITUNG SISA PEMBAYARAN (DENGAN HARGA SEWA MOBIL DAPAT DI-EDIT)
// ===============================
function updateRemainingPayment() {
    let dpAmount = cleanNumber($('#payment_dp_amount').val());
    let totalItems = 0;

    $('.other-price').each(function() {
        totalItems += cleanNumber($(this).val());
    });

    let remaining = totalItems - dpAmount;
    if (remaining < 0) remaining = 0;

    $('#payment_remaining').val(formatRupiah(remaining));
    $('#payment_remaining_base').val(remaining);

    // Auto update nominal pembayaran jika kosong atau sama dengan sisa sebelumnya
    let currentAmount = cleanNumber($('#payment_amount').val());
    let prevRemaining = cleanNumber($('#payment_amount').data('prev-remaining'));
    if (!currentAmount || currentAmount === prevRemaining) {
        $('#payment_amount').val(remaining > 0 ? remaining.toLocaleString('id-ID') : '0');
        $('#payment_amount').data('prev-remaining', remaining);
    }
}
window.updateRemainingPayment = updateRemainingPayment;

// ===============================
// GLOBAL OPEN MODAL PAYMENT
// ===============================
window.openPaymentModal = function(id) {
    if (!id) return;

    // Reset dan tampilkan status loading
    $('#otherPriceWrapper').html('<div class="text-center py-2 text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Memuat rincian sewa...</div>');
    $('#payment_amount').val('');
    $('#payment_amount').removeData('prev-remaining');
    $('#payment_booking_id').val('Memuat...');
    $('#payment_customer').val('Memuat customer...');
    $('#payment_car').val('Memuat armada...');
    $('#payment_destination').val('Memuat tujuan...');
    $('#payment_dates').val('Memuat tanggal...');
    $('#payment_status_value').html('<span class="spinner-border spinner-border-sm me-1 text-primary"></span>Memuat status...');
    $('#payment_remaining').val('Rp 0');

    // Buka modal secara aman (langsung buka agar responsif tanpa lag)
    const modalEl = document.getElementById('modalPayment');
    if (modalEl) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $ !== 'undefined' && $.fn && $.fn.modal) {
            $('#modalPayment').modal('show');
        }
    }

    $.ajax({
        url: '/booking/payment/' + id,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            let dpAmount = Number(response.dp_amount || 0);
            let totalPrice = Number(response.total_price || 0);

            $('#payment_dp_amount').val(dpAmount);
            $('#payment_booking_id').val(response.booking_code || '');

            let custName = (response.customer && response.customer.name) ? response.customer.name : '-';
            let carUnit = (response.car && response.car.unit) ? response.car.unit : '-';

            $('#payment_customer').val(custName);
            $('#payment_car').val(carUnit);
            $('#payment_destination').val(response.destination || '-');
            let datesText = response.dates || ((response.start_date || '-') + ' s/d ' + (response.end_date || '-'));
            $('#payment_dates').val(datesText);

            if (response.payment_status === 'DP') {
                $('#payment_status_value').html(
                    '<span class="badge bg-warning text-dark me-1">DP</span> (<strong>' + formatRupiah(dpAmount) + '</strong>)'
                );
            } else {
                $('#payment_status_value').html(
                    '<span class="badge bg-danger">' + (response.payment_status || 'Belum Lunas') + '</span>'
                );
            }

            // ===============================
            // DEFAULT PRICE (HARGA SEWA MOBIL - BISA DI-EDIT!)
            // ===============================
            $('#otherPriceWrapper').html(`
                <div class="row other-price-item mb-2 align-items-center">
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="small text-muted d-md-none mb-1">Keterangan</label>
                        <input type="text"
                            name="other_name[]"
                            class="form-control"
                            value="Sewa Mobil"
                            placeholder="Keterangan sewa">
                    </div>
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="small text-muted d-md-none mb-1">Harga Sewa (Bisa Diedit)</label>
                        <input type="text"
                            name="other_price[]"
                            class="form-control other-price fw-semibold"
                            value="${formatRupiah(totalPrice)}"
                            placeholder="Harga Sewa Mobil">
                    </div>
                    <div class="col-12 col-md-2 mb-3 mb-md-0">
                        <button type="button"
                            class="btn btn-outline-danger w-100 removeOtherPrice"
                            title="Hapus baris biaya ini">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `);

            updateRemainingPayment();
        },
        error: function(xhr) {
            console.error('Error fetching payment data:', xhr.responseText);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Data booking pembayaran gagal diambil dari server'
                });
            } else {
                alert('Data booking pembayaran gagal diambil');
            }
        }
    });
};

$(document).ready(function() {

    // Trigger open modal via class .btn-payment
    $(document).on('click', '.btn-payment', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        window.openPaymentModal(id);
    });

    // ===============================
    // ADD OTHER PRICE
    // ===============================
    $('#addOtherPrice').click(function() {
        $('#otherPriceWrapper').append(`
            <div class="row other-price-item mb-2 align-items-center">
                <div class="col-12 col-md-5 mb-2 mb-md-0">
                    <input type="text"
                        name="other_name[]"
                        class="form-control"
                        placeholder="Nama biaya (misal: Overtime, Supir, Tol)">
                </div>
                <div class="col-12 col-md-5 mb-2 mb-md-0">
                    <input type="text"
                        name="other_price[]"
                        class="form-control other-price"
                        placeholder="Nominal"
                        value="0">
                </div>
                <div class="col-12 col-md-2 mb-3 mb-md-0">
                    <button type="button"
                        class="btn btn-outline-danger w-100 removeOtherPrice">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `);

        updateRemainingPayment();
    });

    // ===============================
    // REMOVE OTHER PRICE
    // ===============================
    $(document).on('click', '.removeOtherPrice', function() {
        $(this).closest('.other-price-item').remove();
        updateRemainingPayment();
    });

    // ===============================
    // INPUT FORMAT RUPIAH PADA SEMUA .other-price (TERMASUK SEWA MOBIL)
    // ===============================
    $(document).on('input', '.other-price', function() {
        let value = cleanNumber($(this).val());
        $(this).val(value ? formatRupiah(value) : '');
        updateRemainingPayment();
    });

    // ===============================
    // FORMAT PAYMENT AMOUNT INPUT
    // ===============================
    $('#payment_amount').on('keyup input', function() {
        let angka = $(this).val().replace(/[^,\d]/g, '');
        let split = angka.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        $(this).val(rupiah);
    });

    // ===============================
    // SUBMIT PAYMENT
    // ===============================
    $('#formPayment').submit(function(e) {
        e.preventDefault();

        let bookingId = $('#payment_booking_id').val();
        let paymentAmount = $('#payment_amount').val();

        if (!cleanNumber(paymentAmount)) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Harap masukkan nominal pembayaran yang valid!'
            });
            return;
        }

        $.ajax({
            url: '/booking/payment/store',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                booking_id: bookingId,
                payment_amount: paymentAmount,
                other_name: $('input[name="other_name[]"]').map(function() {
                    return $(this).val();
                }).get(),
                other_price: $('input[name="other_price[]"]').map(function() {
                    return cleanNumber($(this).val());
                }).get()
            },
            beforeSend: function() {
                $('#formPayment button[type="submit"]')
                    .html('<span class="spinner-border spinner-border-sm me-1"></span>Memproses...')
                    .attr('disabled', true);
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                });

                const modalEl = document.getElementById('modalPayment');
                if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                } else if ($.fn.modal) {
                    $('#modalPayment').modal('hide');
                }

                $('#formPayment')[0].reset();
                $('#otherPriceWrapper').html('');

                if (window.tableBooking) {
                    window.tableBooking.ajax.reload(null, false);
                } else {
                    // Refresh halaman dashboard keuangan / kalender setelah bayar
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                let message = 'Pembayaran gagal disimpan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            },
            complete: function() {
                $('#formPayment button[type="submit"]')
                    .html('Simpan Pembayaran')
                    .attr('disabled', false);
            }
        });
    });

});