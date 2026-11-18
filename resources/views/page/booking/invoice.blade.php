<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Invoice {{ $booking->booking_code }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
            font-size: 14px;
        }

        .invoice-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
        }

        .table th {
            background: #f8f9fa;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 1px;
        }

        .invoice-title {
            font-size: 14px;
            color: #6c757d;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 12px;
            color: #212529;
        }

        .logo {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }

        @media print {

            .no-print {
                display: none;
            }

            body {
                background: #fff;
            }

            .invoice-box {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>

</head>

<body>

    <div class="container py-4">

        <div class="invoice-box shadow-sm">

            <!-- =========================
             HEADER
        ========================== -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div class="d-flex align-items-center gap-3">

                    <div>

                        <!-- LOGO -->
                        <img
                            src="{{ asset('assets/images/logo.png') }}"
                            alt="Logo"
                            class="logo">

                        <div class="invoice-title" style="font-weight: bold; color:black; font-size:16px">
                            Invoice Booking Rental Mobil
                        </div>

                    </div>

                </div>

                <div class="text-end">

                    <h5 class="fw-bold mb-1">
                        {{ $booking->booking_code }}
                    </h5>

                    <small class="text-muted d-block mb-2">
                        {{ date('d M Y') }}
                    </small>

                    <button
                        onclick="printInvoice()"
                        class="btn btn-primary no-print">

                        Print

                    </button>

                </div>

            </div>


            <!-- =========================
             CUSTOMER & BOOKING
        ========================== -->
            <div class="row mb-4">

                <!-- CUSTOMER -->
                <div class="col-md-6">

                    <h6 class="section-title">
                        Data Penyewa
                    </h6>

                    <table class="table table-borderless">

                        <tr>
                            <td width="120">Nama</td>
                            <td>: {{ $booking->customer->name }}</td>
                        </tr>

                        <tr>
                            <td>Telepon</td>
                            <td>: {{ $booking->customer->phone }}</td>
                        </tr>

                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $booking->customer->address }}</td>
                        </tr>

                    </table>

                </div>


                <!-- BOOKING -->
                <div class="col-md-6">

                    <h6 class="section-title">
                        Detail Booking
                    </h6>

                    <table class="table table-borderless">

                        <tr>
                            <td width="120">Mobil</td>
                            <td>: {{ $booking->car->unit }}</td>
                        </tr>

                        <tr>
                            <td>Tanggal</td>
                            <td>
                                :
                                {{ $booking->start_date }}
                                s/d
                                {{ $booking->end_date }}
                            </td>
                        </tr>

                        <tr>
                            <td>Status</td>
                            <td>: {{ $booking->payment_status }}</td>
                        </tr>

                        <tr>
                            <td>Penjemputan</td>
                            <td>: {{ $booking->pickup }}</td>
                        </tr>

                        <tr>
                            <td>Tujuan</td>
                            <td>: {{ $booking->destination }}</td>
                        </tr>

                    </table>

                </div>

            </div>


            <!-- =========================
             TABLE
        ========================== -->
            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Deskripsi
                            </th>

                            <th width="120">
                                Qty
                            </th>

                            <th width="180">
                                Harga
                            </th>

                            <th width="180">
                                Subtotal
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                        $grandTotal = 0;
                        @endphp

                        @foreach($booking->bookingPrices as $key => $item)

                        @php
                        $grandTotal += $item->subtotal;
                        @endphp

                        <tr>

                            <td>
                                {{ $key + 1 }}
                            </td>

                            <td>
                                {{ $item->name }}
                            </td>

                            <td>
                                {{ $item->qty }}
                            </td>

                            <td>
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>

                            <td>
                                Rp {{ number_format($item->subtotal,0,',','.') }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="4" class="text-end">
                                DP
                            </th>

                            <th>
                                Rp {{ number_format($booking->dp_amount,0,',','.') }}
                            </th>

                        </tr>

                        <tr>

                            <th colspan="4" class="text-end">
                                Total
                            </th>

                            <th>
                                Rp {{ number_format($grandTotal,0,',','.') }}
                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>


            <!-- =========================
             FOOTER
        ========================== -->
            <div class="mt-5 text-center text-muted">

                <small>
                    Terima kasih telah menggunakan layanan
                    <strong>AMII TRANS</strong>
                </small>

            </div>

        </div>

    </div>

    <script src="{{ asset('assets/js/invoice.js') }}"></script>

</body>

</html>