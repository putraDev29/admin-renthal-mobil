$(document).ready(function () {
    // =========================
    // DESTROY JIKA SUDAH ADA
    // =========================
    if ($.fn.DataTable.isDataTable('#table-customer')) {

        $('#table-customer').DataTable().clear().destroy();

    }

    // =========================
    // INIT DATATABLE
    // =========================
    const table = $('#table-customer').DataTable({

        processing: true,

        serverSide: false,

        responsive: false,

        autoWidth: false,

        deferRender: true,

        ajax: {

            url: window.customerUrl.data,

            dataSrc: 'data',

        },

        columns: [

            {
                data: 'DT_RowIndex',
                className: 'text-center',
                width: '5%'
            },

            {
                data: 'nik'
            },

            {
                data: 'name'
            },

            {
                data: 'gender'
            },

            {
                data: 'phone',
            },
            {
                data: 'address',
            },
            {
                data: 'action',
                orderable: false,
                searchable: false
            }

        ],

        columnDefs: [

            {
                targets: 2,
                render: function (data) {

                    return data;

                }
            }

        ],

        dom:
            "<'row align-items-center mb-3'" +
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

    $('#formCustomer').submit(function (e) {

        e.preventDefault();

        let id = $('#customer_id').val();

        let url = '';

        let method = '';

        if (id == '') {

            url = '/customer/store';

            method = 'POST';

        } else {

            url = '/customer/update/' + id;

            method = 'PUT';

        }

        $.ajax({

            url: url,

            type: method,

            data: {

                _token: $('meta[name="csrf-token"]').attr('content'),

                nik: $('#nik').val(),

                name: $('#name').val(),

                gender: $('#gender').val(),

                phone: $('#phone').val(),

                address: $('#address').val()

            },

            success: function () {

                $('#modalCustomer').modal('hide');

                $('#formCustomer')[0].reset();

                $('#customer_id').val('');

                table.ajax.reload();

            }

        });

    });

    // =========================
    // EDIT
    // =========================
    $(document).on('click', '.btn-edit', function () {

        let id = $(this).data('id');

        $.get('/customer/edit/' + id, function (response) {

            $('#customer_id').val(response.id);

            $('#nik').val(response.nik);

            $('#name').val(response.name);

            $('#gender').val(response.gender);

            $('#phone').val(response.phone);

            $('#address').val(response.address);

            $('#modalCustomer').modal('show');

        });

    });

    // =========================
    // DELETE
    // =========================
    $(document).on('click', '.btn-delete', function () {

        if (confirm('Yakin hapus data?')) {

            let id = $(this).data('id');

            $.ajax({

                url: '/customer/delete/' + id,

                type: 'DELETE',

                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function () {

                    table.ajax.reload()

                }

            });

        }

    });

});
