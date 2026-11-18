function rupiah(number) {

    return 'Rp ' + Number(number)
        .toLocaleString('id-ID');

}

$(document).ready(function() {

    // =========================
    // LOAD SUMMARY
    // =========================
    function loadSummary() {

        $.ajax({

            url: window.cashflowUrl.summary,

            data: {

                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                car_id: $('#car_id').val()

            },

            success: function(res) {

                $('#income_total').html(
                    'Rp ' + Number(res.income).toLocaleString('id-ID')
                );

                $('#expense_total').html(
                    'Rp ' + Number(res.expense).toLocaleString('id-ID')
                );

                $('#profit_total').html(
                    'Rp ' + Number(res.profit).toLocaleString('id-ID')
                );

            }

        });
    }

    // =========================
    // DESTROY JIKA SUDAH ADA
    // =========================
    if ($.fn.DataTable.isDataTable('#table-cashflow')) {

        $('#table-cashflow').DataTable().clear().destroy();

    }

    // =========================
    // INIT DATATABLE
    // =========================
    const table = $('#table-cashflow').DataTable({

        processing: true,

        serverSide: false,

        responsive: false,

        autoWidth: false,

        deferRender: true,

        ajax: {

            url: window.cashflowUrl.data,

            data: function(d) {

                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.car_id = $('#car_id').val();

            },

            dataSrc: function(json) {

                $('#footer_total_amount').html(
                    'Rp ' + Number(
                        json.total_amount
                    ).toLocaleString('id-ID')
                );

                return json.data;
            }

        },

        columns: [

            {
                data: 'DT_RowIndex',
                className: 'text-center',
                width: '5%'
            },

            {
                data: 'transaction_date'
            },

            {
                data: 'car'
            },

            {
                data: 'type_badge',
                orderable: false,
                searchable: false
            },

            {
                data: 'title'
            },

            {
                data: 'description'
            },

            {
                data: 'amount_format',
                className: 'fw-semibold text-nowrap text-end'
            },

            {
                data: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }

        ],

        columnDefs: [

            {
                targets: [3, 7],
                render: function(data) {

                    return data;

                }
            }

        ],

        dom: "<'row align-items-center mb-3'" +
            "<'col-md-6'l>" +
            "<'col-md-6 d-flex justify-content-md-end pe-4'f>" +
            ">" +
            "rt" +
            "<'row align-items-center mt-3'" +
            "<'col-md-6'i>" +
            "<'col-md-6 d-flex justify-content-md-end'p>" +
            ">",

        language: {

            processing: "Loading...",
            search: "Cari:",
            lengthMenu: "_MENU_",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",

            paginate: {

                first: "Awal",
                last: "Akhir",
                next: "›",
                previous: "‹"

            }

        }

    });

    // =========================
    // LOAD AWAL
    // =========================
    loadSummary();

    // =========================
    // FILTER
    // =========================
    $('#btnFilter').on('click', function() {

        table.ajax.reload();

        loadSummary();

    });

    $('#formCashFlow').submit(function(e) {

        e.preventDefault();

        let id = $('#cashflow_id').val();

        let url = '';

        let method = '';

        if (id == '') {

            url = '/cashflow/store';

            method = 'POST';

        } else {

            url = '/cashflow/update/' + id;

            method = 'PUT';

        }

        $.ajax({

            url: url,

            type: method,

            data: {

                _token: $('meta[name="csrf-token"]').attr('content'),

                transaction_date: $('#transaction_date').val(),

                type: $('#type').val(),

                title: $('#title').val(),

                description: $('#description').val(),

                amount: $('#amount').val()

            },

            success: function() {

                $('#modalCashFlow').modal('hide');

                $('#formCashFlow')[0].reset();

                $('#cashflow_id').val('');

                table.ajax.reload();

                loadSummary();

            }

        });

    });

    // =========================
    // EDIT
    // =========================
    $(document).on('click', '.btn-edit', function() {

        let id = $(this).data('id');

        $.get('/cashflow/edit/' + id, function(response) {

            $('#cashflow_id').val(response.id);

            $('#transaction_date').val(response.transaction_date);

            $('#type').val(response.type);

            $('#title').val(response.title);

            $('#description').val(response.description);

            $('#amount').val(response.amount);

            $('#modalCashFlow').modal('show');

        });

    });

    // =========================
    // DELETE
    // =========================
    $(document).on('click', '.btn-delete', function() {

        if (confirm('Yakin hapus data?')) {

            let id = $(this).data('id');

            $.ajax({

                url: '/cashflow/delete/' + id,

                type: 'DELETE',

                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function() {

                    table.ajax.reload();

                    loadSummary();

                }

            });

        }

    });

    $('#btnExportExcel').on('click', function() {

        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        let car_id = $('#car_id').val();

        let url =
            '/cashflow/export-excel?' +
            $.param({
                start_date: start_date,
                end_date: end_date,
                car_id: car_id
            });

        window.open(url, '_blank');
    });

});