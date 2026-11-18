$(document).ready(function() {

    loadMaintenanceInfo();

    loadHistory();

    $('#maintenanceForm').on('submit', function(e) {

        e.preventDefault();

        $.ajax({

            url: storeUrl,

            type: 'POST',

            data: $(this).serialize(),

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function() {

                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

            },

            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message ||
                        'Maintenance berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });

                $('#maintenanceForm')[0].reset();

                loadMaintenanceInfo();

                loadHistory();

            },

            error: function(xhr) {

                let message = 'Terjadi kesalahan';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });

            }

        });

    });

});

/*
|--------------------------------------------------------------------------
| LOAD LAST MAINTENANCE
|--------------------------------------------------------------------------
*/

function loadMaintenanceInfo() {
    $.ajax({

        url: lastMaintenanceUrl,

        type: 'GET',

        success: function(item) {

            let html = '';

            if (!item) {

                html = `
                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h4>${carName}</h4>

                            <div class="text-muted">
                                ${plateNumber}
                            </div>

                            <hr>

                            <div class="text-center text-muted">

                                Belum ada data maintenance

                            </div>

                        </div>

                    </div>
                `;

            } else {

                html = `
                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h4>
                                ${carName}
                            </h4>

                            <div class="text-muted">
                                ${plateNumber}
                            </div>

                            <hr>

                            <table class="table table-sm">

                                <tr>
                                    <td>KM Saat Ini</td>
                                    <td>${numberFormat(item.km_vehicle)}</td>
                                </tr>

                                <tr>
                                    <td>Spek Oli</td>
                                    <td>${item.oil_spec ?? '-'}</td>
                                </tr>

                                <tr>
                                    <td>KM Ganti Sebelumnya</td>
                                    <td>${item.previous_oil_km ?? '-'}</td>
                                </tr>

                                <tr>
                                    <td>KM Ganti Selanjutnya</td>
                                    <td>${item.next_oil_km ?? '-'}</td>
                                </tr>

                            </table>

                            <hr>

                            <h6>Kode Ban</h6>

                            <table class="table table-sm">

                                <tr>
                                    <td>Ban Depan Kanan</td>
                                    <td>${item.front_right_tire ?? '-'}</td>
                                </tr>

                                <tr>
                                    <td>Ban Depan Kiri</td>
                                    <td>${item.front_left_tire ?? '-'}</td>
                                </tr>

                                <tr>
                                    <td>Ban Belakang Kanan</td>
                                    <td>${item.rear_right_tire ?? '-'}</td>
                                </tr>

                                <tr>
                                    <td>Ban Belakang Kiri</td>
                                    <td>${item.rear_left_tire ?? '-'}</td>
                                </tr>

                            </table>

                        </div>

                    </div>
                `;
            }

            $('#maintenanceInfo').html(html);
            $('#previous_oil_km').val(item.next_oil_km);

        }

    });
}

/*
|--------------------------------------------------------------------------
| LOAD HISTORY
|--------------------------------------------------------------------------
*/

function loadHistory() {
    $.ajax({

        url: historyUrl,

        type: 'GET',

        success: function(data) {

            let html = '';

            if (!data || data.length === 0) {

                html = `
                    <div class="text-center text-muted">
                        Belum ada data maintenance
                    </div>
                `;

            } else {

                $.each(data, function(index, item) {

                    html += `
                        <div class="card border-0 shadow-sm mb-3">

                            <div class="card-body">

                                <div class="d-flex justify-content-between">

                                    <strong>
                                        ${formatDate(item.maintenance_date)}
                                    </strong>

                                    <span class="badge bg-primary">
                                        KM ${numberFormat(item.km_vehicle)}
                                    </span>

                                </div>

                                <hr>

                                <div class="mb-1">
                                    <strong>Spek Oli :</strong>
                                    ${item.oil_spec ?? '-'}
                                </div>

                                <div class="mb-1">
                                    <strong>Nama Maintenance :</strong>
                                    ${item.workshop_name ?? '-'}
                                </div>

                                <div class="mb-1">
                                    <strong>Biaya :</strong>
                                    Rp ${rupiah(item.cost)}
                                </div>

                                <div>
                                    <strong>Catatan :</strong>
                                    ${item.notes ?? '-'}
                                </div>

                            </div>

                        </div>
                    `;
                });
            }

            $('#historyList').html(html);

        }

    });
}

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

function rupiah(angka) {
    return parseInt(angka || 0)
        .toLocaleString('id-ID');
}

function numberFormat(angka) {
    return parseInt(angka || 0)
        .toLocaleString('id-ID');
}

function formatDate(dateString) {
    if (!dateString) return '-';

    const date = new Date(dateString);

    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}