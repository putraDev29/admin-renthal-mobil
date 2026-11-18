$(document).ready(function() {

    if ($.fn.DataTable.isDataTable('#table-driver')) {
        $('#table-driver').DataTable().clear().destroy();
    }

    const table = $('#table-driver').DataTable({

        processing: true,
        serverSide: false,
        responsive: true,
        autoWidth: false,
        deferRender: true,

        ajax: {
            url: window.driverUrl.data,
            dataSrc: 'data',
        },

        columns: [{
                data: 'DT_RowIndex',
                className: 'text-center',
                width: '5%'
            },
            {
                data: 'kode_driver'
            },
            {
                data: 'nama'
            },
            {
                data: 'no_hp'
            },
            {
                data: 'email'
            },
            {
                data: 'no_sim'
            },
            {
                data: 'masa_berlaku_sim'
            },
            {
                data: 'status',
                render: function(data) {

                    if (data == 'aktif') {

                        return `<span class="badge bg-success">Aktif</span>`;

                    } else {

                        return `<span class="badge bg-danger">Nonaktif</span>`;

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

    $('#formDriver').submit(function(e) {

        e.preventDefault();

        let id = $('#driver_id').val();

        let url = '';
        let method = '';

        if (id == '') {

            url = '/driver/store';
            method = 'POST';

        } else {

            url = '/driver/update/' + id;
            method = 'PUT';

        }

        $.ajax({

            url: url,
            type: method,

            data: {

                _token: $('meta[name="csrf-token"]').attr('content'),

                nama: $('#nama').val(),
                no_hp: $('#no_hp').val(),
                email: $('#email').val(),
                alamat: $('#alamat').val(),
                no_sim: $('#no_sim').val(),
                masa_berlaku_sim: $('#masa_berlaku_sim').val(),
                status: $('#status').val()

            },

            success: function() {

                $('#modalDriver').modal('hide');

                $('#formDriver')[0].reset();

                $('#driver_id').val('');

                $('#kode_driver').val('');

                table.ajax.reload();

            }

        });

    });

    $(document).on('click', '.btn-edit', function() {

        let id = $(this).data('id');

        $.get('/driver/edit/' + id, function(response) {

            $('#driver_id').val(response.id);

            $('#kode_driver').val(response.kode_driver);

            $('#nama').val(response.nama);

            $('#no_hp').val(response.no_hp);

            $('#email').val(response.email);

            $('#alamat').val(response.alamat);

            $('#no_sim').val(response.no_sim);

            $('#masa_berlaku_sim').val(
                response.masa_berlaku_sim ?
                response.masa_berlaku_sim.substring(0, 10) :
                ''
            );

            $('#status').val(response.status);

            $('#modalDriver').modal('show');

        });

    });

    $(document).on('click', '.btn-delete', function() {

        if (confirm('Yakin hapus data?')) {

            let id = $(this).data('id');

            $.ajax({

                url: '/driver/delete/' + id,

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