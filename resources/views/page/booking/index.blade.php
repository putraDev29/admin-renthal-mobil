@extends('layouts.app')

@section('content')

{{-- CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/form.css') }}?v={{ time() }}">

<div class="w-100 px-0 py-2">

    <div class="card border-0 shadow-sm rounded-4 w-100">

        <div class="card-body p-2.5 p-md-3">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h3 class="fw-bold mb-1 fs-5">Data Booking</h3>
                    <small class="text-muted" style="font-size: 12px;">List booking rental mobil</small>
                </div>

                <button class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs" onclick="openBookingModal()">
                    <i class="bi bi-plus-lg me-1"></i>Booking
                </button>

            </div>

            <div class="table-responsive">

                <table id="bookingTable" class="table table-hover align-middle w-100">

                    <thead class="table-light">

                        <tr>
                            <th>Kode</th>
                            <th>Penyewa</th>
                            <th>Unit</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@include('page.booking.form-booking')
@include('page.booking.form-pembayaran-booking')
@include('modal.customer-booking-modal')

@endsection

@push('styles')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

@endpush

@push('scripts')

<script>

$(document).ready(function () {

    window.tableBooking = $('#bookingTable').DataTable({

        processing: true,
        serverSide: true,
        fixedHeader: false,
        scrollX: false,
        responsive: false,
        autoWidth: false,
        destroy: true,

        dom:
        "<'row align-items-center mb-2 g-2'" +
            "<'col-12 col-sm-6 col-lg-4'l>" +
            "<'col-12 col-sm-6 col-lg-4'>" +
            "<'col-12 col-sm-6 col-lg-4 d-flex justify-content-start justify-content-sm-end justify-content-lg-end'f>" +
        ">" +
        "rt" +
        "<'row mt-3 g-2'" +
            "<'col-12 col-sm-6'i>" +
            "<'col-12 col-sm-6 d-flex justify-content-start justify-content-sm-end'p>" +
        ">",

        ajax: {
            url: "{{ url('/bookings/data') }}",
            type: "GET"
        },

        columns: [
            {data:'booking_code',name:'booking_code'},
            {data:'customer',name:'customer'},
            {data:'car',name:'car'},
            {data:'start_date',name:'start_date'},
            {data:'end_date',name:'end_date'},
            {data:'status',name:'status'},
            {
                data:'action',
                name:'action',
                orderable:false,
                searchable:false
            }
        ],

        order: [[0,'desc']],

        pageLength: 10,

        language: {

            search: "",
            searchPlaceholder: "Cari booking...",

            processing: "Loading...",

            lengthMenu: "_MENU_",

            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada data booking",

            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "›",
                previous: "‹"
            }
        }

    });

});

</script>
<script src="{{ asset('assets/js/payment.js') }}"></script>
@endpush