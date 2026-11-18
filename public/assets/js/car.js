$(document).ready(function() {

    // =========================
    // DESTROY JIKA SUDAH ADA
    // =========================
    if ($.fn.DataTable.isDataTable('#table-car')) {

        $('#table-car').DataTable().clear().destroy();

    }

    // =========================
    // INIT DATATABLE
    // =========================
    const table = $('#table-car').DataTable({

        processing: true,

        serverSide: false,

        responsive: false,

        autoWidth: false,

        deferRender: true,

        ajax: {

            url: window.carUrl.data,

            dataSrc: 'data',

        },

        columns: [

            {
                data: 'DT_RowIndex',
                className: 'text-center',
                width: '5%'
            },

            {
                data: 'unit'
            },

            {
                data: 'name'
            },

            {
                data: 'plate_number'
            },

            {
                data: 'total_unit',
                className: 'text-center'
            },

            {
                data: 'price',
                className: 'text-end'
            },

            {
                data: 'tax'
            },

            {
                data: 'kir'
            },

            {
                data: 'status',
                render: function(data) {

                    if (data == 'available') {

                        return `
                            <span class="badge bg-success">
                                Available
                            </span>
                        `;

                    } else {

                        return `
                            <span class="badge bg-danger">
                                Maintenance
                            </span>
                        `;

                    }

                }
            },

            {
                data: 'action',
                orderable: false,
                searchable: false
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
    // STORE & UPDATE
    // =========================
    $('#formCar').submit(function(e) {

        e.preventDefault();

        let id = $('#car_id').val();

        let url = '';

        let method = '';

        if (id == '') {

            url = '/car/store';

            method = 'POST';

        } else {

            url = '/car/update/' + id;

            method = 'PUT';

        }

        $.ajax({

            url: url,

            type: method,

            data: {

                _token: $('meta[name="csrf-token"]').attr('content'),

                unit: $('#unit').val(),

                name: $('#name').val(),

                plate_number: $('#plate_number').val(),

                total_unit: $('#total_unit').val(),

                price: $('#price').val(),

                tax: $('#tax').val(),

                kir: $('#kir').val(),

                status: $('#status').val()

            },

            success: function() {

                $('#modalCar').modal('hide');

                $('#formCar')[0].reset();

                $('#car_id').val('');

                table.ajax.reload();

            }

        });

    });

    // =========================
    // EDIT
    // =========================
    $(document).on('click', '.btn-edit', function() {

        let id = $(this).data('id');

        $.get('/car/edit/' + id, function(response) {

            $('#car_id').val(response.id);

            $('#unit').val(response.unit);

            $('#name').val(response.name);

            $('#plate_number').val(response.plate_number);

            $('#total_unit').val(response.total_unit);

            $('#price').val(response.price);

            $('#tax').val(response.tax);

            $('#kir').val(response.kir);

            $('#status').val(response.status);

            $('#modalCar').modal('show');

        });

    });

    // =========================
    // DELETE
    // =========================
    $(document).on('click', '.btn-delete', function() {

        if (confirm('Yakin hapus data?')) {

            let id = $(this).data('id');

            $.ajax({

                url: '/car/delete/' + id,

                type: 'DELETE',

                data: {

                    _token: $('meta[name="csrf-token"]').attr('content')

                },

                success: function() {

                    table.ajax.reload();

                }

            });

        }

    });

});