// ===============================
// FORMAT RUPIAH
// ===============================
function formatRupiah(angka) {

    angka = parseInt(angka) || 0;

    return 'Rp ' + angka.toLocaleString('id-ID');
}


// ===============================
// AMBIL ANGKA ASLI
// ===============================
function getNumber(value) {

    return parseInt(
        String(value).replace(/[^0-9]/g, '')
    ) || 0;
}


// ===============================
// OPEN MODAL BOOKING
// ===============================
function openBookingModal(data = null) {

    // sembunyikan header datatable sementara
    $('.dataTables_wrapper').css('z-index', '0');

    // RESET FORM (FIX UTAMA)
    $('#bookingModalForm').trigger('reset');

    $('#booking_id').val('');

    // reset select2
    $('#customerSelect').empty().trigger('change');
    $('#carSelect').empty().trigger('change');

    // reset field
    $('#nik,#phone,#address,#destination').val('');
    $('#gender').val('').trigger('change');

    $('#price').val(0);
    $('#price_text').val(formatRupiah(0));

    $('#price_driver').val(formatRupiah(0));
    $('#total_price').val(0);
    $('#total_price_text').val(formatRupiah(0));

    // EDIT MODE
    if (data) {

        $('#booking_id').val(data.id);

        $('#start_date').val(data.start_date);
        $('#end_date').val(data.end_date);

        $('#price').val(data.price || 0);
        $('#price_text').val(formatRupiah(data.price || 0));

        $('#price_driver').val(formatRupiah(data.price_driver || 0));

        $('#total_price').val(data.total_price || 0);
        $('#total_price_text').val(formatRupiah(data.total_price || 0));

        $('#destination').val(data.destination || '');
        $('#pickup').val(data.pickup || '');

        // CUSTOMER
        if (data.customer_id) {
            let opt = new Option(data.customer_name, data.customer_id, true, true);
            $('#customerSelect').append(opt).trigger('change');
            $('#customer_id').val(data.customer_id || '');
            $('#customer_name').val(data.customer_name || '');
            $('#nik').val(data.nik || '');
            $('#gender').val(data.gender || '').trigger('change');
            $('#phone').val(data.phone || '');
            $('#address').val(data.address || '');
        }

        // CAR
        if (data.car_id) {
            let opt = new Option(data.car_name, data.car_id, true, true);
            $('#carSelect').append(opt).trigger('change');
            $('#car_id').val(data.car_id || '');
        }

        if (data.driver_id) {
            let opt = new Option(data.driver_name, data.driver_id, true, true);
            $('#driverSelect').append(opt).trigger('change');
            $('#driver_id').val(data.driver_id || '');
        }
    }

    hitungTotalPembayaran();

    // FIX BOOTSTRAP 5 MODAL
    let modal = new bootstrap.Modal(document.getElementById('bookingModalForm'));
    modal.show();
}
// ===============================
// SELECT2 CUSTOMER
// ===============================
$('#customerSelect').select2({

    placeholder: 'Cari nama penyewa...',
    allowClear: true,
    tags: true,
    width: '100%',
    dropdownParent: $('#bookingModalForm'),
    minimumInputLength: 1,

    // customer baru
    createTag: function(params) {

        let term = $.trim(params.term);

        if (term === '') {
            return null;
        }

        return {
            id: term,
            text: term,
            newTag: true
        };
    },

    ajax: {

        url: '/customers/search',

        dataType: 'json',

        delay: 250,

        data: function(params) {

            return {
                q: params.term
            };
        },

        processResults: function(data) {

            return {
                results: data
            };
        },

        cache: true
    }
});


// ===============================
// CHANGE CUSTOMER
// ===============================
$('#customerSelect').on('change', function() {

    let selectedData =
        $(this).select2('data');

    // reset jika kosong
    if (!selectedData || !selectedData.length) {

        $('#customer_id').val('');
        $('#customer_name').val('');

        $('#nik').val('');

        $('#gender')
            .val('')
            .trigger('change');

        $('#phone').val('');

        $('#address').val('');

        return;
    }

    let selected = selectedData[0];

    console.log(selected);

    // ==================================
    // FORCE CUSTOMER NAME
    // ==================================
    let customerName =
        selected.text || '';

    $('#customer_name').val(
        customerName
    );

    // ==================================
    // CUSTOMER LAMA
    // ==================================
    if (
        selected.nik ||
        selected.phone ||
        selected.address
    ) {

        $('#customer_id').val(
            selected.id
        );

        $('#nik').val(
            selected.nik || ''
        );

        $('#gender')
            .val(selected.gender || '')
            .trigger('change');

        $('#phone').val(
            selected.phone || ''
        );

        $('#address').val(
            selected.address || ''
        );

    } else {

        // ==================================
        // CUSTOMER BARU
        // ==================================
        $('#customer_id').val('');

        $('#nik').val('');

        $('#gender')
            .val('')
            .trigger('change');

        $('#phone').val('');

        $('#address').val('');
    }

    // debug
    console.log(
        'CUSTOMER NAME:',
        $('#customer_name').val()
    );
});


// ===============================
// FIX CUSTOMER BARU ENTER CEPAT
// ===============================
$('#customerSelect').on(
    'select2:close',
    function() {

        let selectedData =
            $(this).select2('data');

        if (
            selectedData &&
            selectedData.length
        ) {

            let selected = selectedData[0];

            $('#customer_name').val(
                selected.text || ''
            );
        }
    }
);

// ===============================
// SELECT2 MOBIL
// ===============================
$('#carSelect').select2({
    placeholder: 'Cari nama mobil...',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#bookingModalForm'),
    minimumInputLength: 1,

    ajax: {
        url: '/cars/search',
        dataType: 'json',
        delay: 250,

        processResults: function(data) {

            return {
                results: data
            };
        }
    }
});


// pilih mobil
$('#carSelect').on('select2:select', function(e) {

    let data = e.params.data;

    let price = data.price || 0;

    $('#car_id').val(data.id);

    // harga asli
    $('#price').val(price);

    // harga format rupiah
    $('#price_text').val(
        formatRupiah(price)
    );

    hitungTotalPembayaran();
});

// ===============================
// SELECT2 DRIVER
// ===============================
$('#driverSelect').select2({
    placeholder: 'Cari nama driver...',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#bookingModalForm'),
    minimumInputLength: 1,

    ajax: {
        url: '/drivers/search',
        dataType: 'json',
        delay: 250,

        processResults: function(data) {

            return {
                results: data
            };

        }
    }
});

// pilih driver
$('#driverSelect').on('select2:select', function(e) {

    let data = e.params.data;

    $('#driver_id').val(data.id);

    $('#no_hp_driver').val(data.no_hp || '');

    $('#no_sim_driver').val(data.no_sim || '');

});

// ===============================
// DRIVER PRICE
// ===============================
function toggleDriverPrice() {

    let description = $('#description').val();

    if (description === 'Plus Sopir') {

        $('#price_driver')
            .prop('readonly', false)
            .prop('required', true);

        if (
            getNumber($('#price_driver').val()) === 0
        ) {
            $('#price_driver').val('');
        }

    } else {

        $('#price_driver')
            .prop('readonly', true)
            .prop('required', false)
            .val(formatRupiah(0));

        hitungTotalPembayaran();
    }
}

toggleDriverPrice();

$('#description').on('change', toggleDriverPrice);

// ===============================
// DRIVER PRICE
// ===============================
function toggleStatus() {

    let status = $('#payment_status').val();

    if (status === 'DP') {

        $('#dp_amount')
            .prop('readonly', false)
            .prop('required', true);

        if (
            getNumber($('#dp_amount').val()) === 0
        ) {
            $('#dp_amount').val('');
        }

    } else {

        $('#dp_amount')
            .prop('readonly', true)
            .prop('required', false)
            .val(formatRupiah(0));

        hitungTotalPembayaran();
    }
}

toggleStatus();

$('#payment_status').on('change', toggleStatus);

// ===============================
// FORMAT INPUT DRIVER
// ===============================
$('#dp_amount').on('input', function() {

    let angka = getNumber($(this).val());

    $(this).val(
        formatRupiah(angka)
    );

    hitungTotalPembayaran();
});


// ===============================
// HITUNG TOTAL
// ===============================
function hitungTotalPembayaran() {

    let hargaHari =
        parseInt($('#price').val()) || 0;

    let hargaDriver =
        getNumber($('#price_driver').val());

    let tanggalMulai =
        $('#start_date').val();

    let tanggalSelesai =
        $('#end_date').val();

    let totalHari = 1;

    if (tanggalMulai && tanggalSelesai) {

        let start = new Date(tanggalMulai);
        let end = new Date(tanggalSelesai);

        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);

        let selisih =
            end.getTime() - start.getTime();

        totalHari = Math.floor(
            selisih / (1000 * 60 * 60 * 24)
        ) + 1;

        if (totalHari <= 0) {
            totalHari = 1;
        }
    }

    let total =
        (hargaHari * totalHari) + hargaDriver;

    // simpan angka asli
    $('#total_price').val(total);

    // tampil rupiah
    $('#total_price_text').val(
        formatRupiah(total)
    );
}


// ===============================
// AUTO UPDATE
// ===============================
$('#start_date').on(
    'change',
    hitungTotalPembayaran
);

$('#end_date').on(
    'change',
    hitungTotalPembayaran
);

$(document).on('click', '.btn-edit-booking', function() {

    let id = $(this).data('id');

    $.get(`/booking/${id}`, function(res) {
        openBookingModal(res.data);
    });

});

// ===============================
// SUBMIT FORM
// ===============================
$('#bookingForm').on('submit', function(e) {

    e.preventDefault();

    let form = $(this);
    let btn = form.find('button[type="submit"]');

    btn.prop('disabled', true).html('Menyimpan...');

    let id = $('#booking_id').val();

    let url = id ? `/booking/${id}` : `/booking/storeBooking`;

    let data = form.serialize();

    if (id) {
        data += '&_method=PUT';
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: data,

        success: function(res) {

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message,
                confirmButtonText: 'OK'
            }).then(() => {

                $('#bookingModalForm').modal('hide');

                if (typeof reloadCalendar === 'function') {
                    reloadCalendar();
                }

                location.reload();
            });
        },

        error: function(xhr) {

            let message = 'Terjadi kesalahan';

            if (xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Booking Gagal',
                text: message
            });
        },

        complete: function() {
            btn.prop('disabled', false).html('Simpan Booking');
        }
    });
});

// hapus input
$(document).on('click', '.removeOtherPrice', function() {

    $(this).closest('.other-price-item').remove();

    calculateTotal();
});

// realtime hitung total
$(document).on('input', '.other-price, #price_driver', function() {
    calculateTotal();
});

function calculateTotal() {

    let hargaMobil = parseInt($('#price').val()) || 0;
    let hargaDriver = parseInt($('#price_driver').val()) || 0;

    let totalLain = 0;

    $('.other-price').each(function() {
        totalLain += parseInt($(this).val()) || 0;
    });

    let total = hargaMobil + hargaDriver + totalLain;

    $('#total_price').val(total);

    $('#total_price_text').val(
        new Intl.NumberFormat('id-ID').format(total)
    );
}

$(document).on('click', '.btn-detail-booking', function() {

    let id = $(this).data('id');

    $.ajax({

        url: '/booking/detail/' + id,
        type: 'GET',

        success: function(res) {

            $('#detailBookingCode').text(res.booking_code);

            $('#detailStatus').html(
                res.status === 'Booking' ?
                '<span class="badge bg-success">Booking</span>' :
                '<span class="badge bg-danger">Selesai</span>'
            );

            $('#detailCustomer').text(
                res.customer.name
            );

            $('#detailCar').text(res.car.unit);

            $('#detailStartDate').text(
                res.start_date
            );

            $('#detailEndDate').text(
                res.end_date
            );

            $('#detailTotal').text(
                'Rp ' + Number(res.total_price)
                .toLocaleString('id-ID')
            );

            $('#detailDestination').text(res.destination);
            $('#detailPickup').text(res.pickup);
            $('#modalDetailBooking').modal('show');

        },

        error: function() {

            alert('Detail booking gagal diambil');

        }

    });

});

$('#modalDetailBooking').on('hidden.bs.modal', function() {

    if (window.tableBooking) {

        window.tableBooking.ajax.reload(null, false);

    }

});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click', '.btn-delete-booking', function() {

    let id = $(this).data('id');

    if (!confirm('Yakin ingin menghapus booking ini?')) {
        return;
    }

    $.ajax({

        url: "/bookings/" + id,

        type: "DELETE",

        success: function(response) {

            alert(response.message);

            tableBooking.ajax.reload(null, false);
        },

        error: function(xhr) {

            alert(xhr.responseJSON.message);
        }

    });

});