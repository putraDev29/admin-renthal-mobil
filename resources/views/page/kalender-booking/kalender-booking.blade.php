@extends('layouts.app')

@section('content')

{{-- CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('assets/css/form.css') }}?v={{ time() }}">

<style>
    /* ============================================================
       ULTRA-MODERN DASHBOARD SEGMENTED TABS
    ============================================================ */
    .dashboard-tabs-wrapper {
        margin-bottom: 18px;
    }

    .dashboard-tabs-wrapper .nav-pills {
        background: #f8fafc;
        border-radius: 16px;
        padding: 6px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03), inset 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .dashboard-tabs-wrapper .nav-pills .nav-item {
        width: 100%;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link {
        color: #64748b;
        background: #ffffff;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid #e2e8f0;
        font-size: 13.5px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 12px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        cursor: pointer;
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        width: 100%;
        user-select: none;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link:hover {
        color: #1e293b;
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1.5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link:active {
        transform: translateY(0) scale(0.99);
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link .tab-icon {
        font-size: 16px;
        transition: transform 0.2s ease;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link:hover .tab-icon {
        transform: scale(1.15);
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: #ffffff !important;
        border-color: #1d4ed8;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        font-weight: 700;
        transform: translateY(-1px);
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link.active .tab-icon {
        color: #ffffff !important;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link .tab-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        transition: all 0.2s ease;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link.active .tab-badge {
        background: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .dashboard-tabs-wrapper .nav-pills .nav-link:not(.active) .tab-badge {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }



    /* Summary Metric Cards */
    .summary-kpi-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: border-color 0.15s ease;
    }

    .summary-kpi-card:hover {
        border-color: #cbd5e1;
    }

    .kpi-icon-box {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .kpi-label {
        font-size: 11.5px;
        font-weight: 500;
        color: #64748b;
        line-height: 1.2;
    }

    .kpi-value {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.1;
    }

    .kpi-unit {
        font-size: 11px;
        font-weight: 500;
    }

    /* Date Navigation & Actions */
    .btn-nav-compact {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-nav-compact:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* ============================================================
       CALENDAR TABLE STYLES & HORIZONTAL TOUCH SCROLLING
    ============================================================ */
    .calendar-wrapper {
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .calendar-wrapper .table-responsive {
        overflow-x: auto !important;
        overflow-y: auto !important;
        max-height: 75vh;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-x pan-y;
        overscroll-behavior: contain;
        position: relative;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }

    .calendar-wrapper .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .calendar-wrapper .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .calendar-wrapper .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .calendar-table {
        min-width: 1280px;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    /* Pinned Left Car Column (Lebar Menyesuaikan Isi TD) */
    .calendar-table .sticky-col-1,
    .calendar-table .car-column,
    .calendar-table .car-info {
        position: sticky;
        left: 0;
        z-index: 30;
        background: #ffffff;
        box-shadow: 3px 0 10px rgba(0, 0, 0, 0.06);
        width: 1% !important;
        min-width: auto !important;
        max-width: none !important;
        white-space: nowrap !important;
        padding: 8px 10px;
        text-align: center;
    }

    thead .sticky-col-1,
    thead .car-column {
        z-index: 35 !important;
        background: #f8fafc !important;
        white-space: nowrap !important;
    }

    .calendar-table .armada-header {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .calendar-table .unit-column,
    .calendar-table tbody tr td:nth-child(2) {
        width: 50px;
        min-width: 50px;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        background: #fafbfc;
    }

    .calendar-table .date-column {
        width: 35px;
        min-width: 35px;
        max-width: 35px;
        padding: 6px 2px;
        text-align: center;
        background: #f8fafc;
    }

    .calendar-table .date-number {
        font-size: 11px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    .calendar-table .date-day {
        font-size: 9.5px;
        color: #64748b;
        line-height: 1.2;
    }

    .calendar-table .calendar-cell {
        width: 35px;
        min-width: 35px;
        height: 52px;
        padding: 0 !important;
        position: relative;
        background: #ffffff;
    }

    .calendar-table .calendar-cell:hover {
        background: #f8fafc;
    }

    /* Booking Bar */
    .calendar-table .booking-bar {
        height: 36px;
        min-height: 36px;
        top: 8px;
        border-radius: 6px;
        padding: 2px 6px;
        position: absolute;
        z-index: 10;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .calendar-table .booking-bar:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        z-index: 20;
    }

    .calendar-table .booking-confirmed {
        background: #ef4444 !important;
        color: #ffffff !important;
    }

    .calendar-table .booking-plan {
        background: #10b981 !important;
        color: #ffffff !important;
    }

    .calendar-table .booking-title {
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ffffff;
        line-height: 1.2;
    }

    .calendar-table .booking-date {
        font-size: 9px;
        opacity: 0.9;
        white-space: nowrap;
        color: #ffffff;
        line-height: 1.2;
    }


</style>


@php

use Carbon\Carbon;

Carbon::setLocale('id');

$confirmed = $bookings
    ->where('status', 'Booking')
    ->count();

$pending = $bookings
    ->where('status', 'DP')
    ->count();

$plan = $bookings
    ->where('status', 'Selesai')
    ->count();

$booking = $bookings->count();

$totalCars = $cars
    ->where(
        'status',
        'available'
    )
    ->count();

$occupancy =
    $totalCars > 0
        ? round(
            ($confirmed / $totalCars)
            * 100
        )
        : 0;

@endphp

{{-- ============================================================
     MODERN DASHBOARD TABS PILLS
============================================================ --}}
<div class="dashboard-tabs-wrapper">
    <ul class="nav nav-pills" id="dashboardTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active"
                id="tab-booking-btn" data-bs-toggle="pill" data-bs-target="#tabBookingDashboard" type="button" role="tab"
                aria-controls="tabBookingDashboard" aria-selected="true" title="Klik untuk melihat Kalender Jadwal Booking">
                <i class="bi bi-calendar3 tab-icon text-primary"></i>
                <span>Jadwal</span>
                <span class="tab-badge ms-1">{{ $booking }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                id="tab-finance-btn" data-bs-toggle="pill" data-bs-target="#tabFinanceDashboard" type="button" role="tab"
                aria-controls="tabFinanceDashboard" aria-selected="false" title="Klik untuk melihat Laporan Keuangan Kas">
                <i class="bi bi-wallet2 tab-icon text-success"></i>
                <span>Keuangan Kas</span>
                <span class="tab-badge ms-1 d-none d-md-inline">
                    Rp {{ number_format($netProfitMonth, 0, ',', '.') }}
                </span>
            </button>
        </li>
    </ul>
</div>

<div class="tab-content" id="dashboardTabContent">
    {{-- ============================================================
         TAB 1: DASHBOARD JADWAL BOOKING
    ============================================================ --}}
    <div class="tab-pane fade show active" id="tabBookingDashboard" role="tabpanel" aria-labelledby="tab-booking-btn">

{{-- ============================================================
     HEADER
============================================================ --}}

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 dashboard-header">
    <div class="d-flex justify-content-between align-items-center w-100 w-md-auto">
        <div>
            <h1 class="page-title fs-5 fw-bold text-dark mb-0">Kalender Booking</h1>
            <small class="text-muted d-none d-md-block" style="font-size: 12px;">Monitoring jadwal armada sewa</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-semibold d-md-none shadow-xs" onclick="openBookingModal()">
            <i class="bi bi-plus-lg me-1"></i>Booking
        </button>
    </div>

    <div class="top-action d-flex align-items-center justify-content-between justify-content-md-end gap-2 w-100 w-md-auto">
        {{-- MONTH PICKER PILL --}}
        <div class="calendar-navigation d-flex align-items-center bg-white border rounded-pill p-1 shadow-xs flex-fill flex-md-grow-0 justify-content-between">
            <a href="{{ route('kalendarBooking', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="btn-nav-compact" title="Bulan Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div class="calendar-date-compact fw-semibold px-2 text-dark text-center" style="font-size: 12.5px;">
                <i class="bi bi-calendar3 me-1 text-primary"></i>
                <span>{{ $currentDate->translatedFormat('F Y') }}</span>
            </div>
            <a href="{{ route('kalendarBooking', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="btn-nav-compact" title="Bulan Selanjutnya">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        {{-- DESKTOP BOOKING BUTTON --}}
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-semibold d-none d-md-flex align-items-center gap-1 shadow-xs" onclick="openBookingModal()">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Booking</span>
        </button>
    </div>
</div>

{{-- ============================================================
     SUMMARY METRIC CARDS (CLEAN 2x2 ON MOBILE, 4 IN A ROW ON DESKTOP)
============================================================ --}}
<div class="row g-2 g-md-3 mb-3">
    {{-- UNIT TERISI --}}
    <div class="col-6 col-lg-3">
        <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #10b981;">
            <div class="card-body p-2.5 p-md-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="kpi-label">Unit Disewa</span>
                    <div class="kpi-icon-box bg-success-subtle text-success">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="kpi-value text-dark">{{ $confirmed }}</span>
                    <span class="kpi-unit text-muted">unit</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SELESAI --}}
    <div class="col-6 col-lg-3">
        <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #ef4444;">
            <div class="card-body p-2.5 p-md-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="kpi-label">Selesai</span>
                    <div class="kpi-icon-box bg-danger-subtle text-danger">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="kpi-value text-dark">{{ $plan }}</span>
                    <span class="kpi-unit text-muted">unit</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL BOOKING --}}
    <div class="col-6 col-lg-3">
        <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #2563eb;">
            <div class="card-body p-2.5 p-md-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="kpi-label">Total Booking</span>
                    <div class="kpi-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="kpi-value text-dark">{{ $booking }}</span>
                    <span class="kpi-unit text-muted">booking</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL UNIT --}}
    <div class="col-6 col-lg-3">
        <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #64748b;">
            <div class="card-body p-2.5 p-md-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="kpi-label">Total Armada</span>
                    <div class="kpi-icon-box bg-secondary-subtle text-dark">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="kpi-value text-dark">{{ $totalCars }}</span>
                    <span class="kpi-unit text-muted">unit ready</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MOBILE SWIPE HINT --}}
<div class="d-flex d-md-none justify-content-between align-items-center mb-2 px-1 text-muted" style="font-size: 11px;">
    <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel untuk cek tanggal</span>
    <span class="badge bg-light text-secondary border fw-medium">{{ $currentDate->translatedFormat('F Y') }}</span>
</div>



{{-- ============================================================
     CALENDAR TABLE
============================================================ --}}

<div class="calendar-wrapper">

    <div class="table-responsive">

        <table class="table calendar-table">


            {{-- =================================================
                 HEADER
            ================================================= --}}

            <thead>

                <tr>


                   {{-- CAR --}}
                    <th class="text-center car-column sticky-col-1 sticky-header armada-header">
                        <i class="fa-solid fa-car me-1 text-primary"></i> Armada
                    </th>


                    {{-- TOTAL BOOKING --}}
                    <th
                        class="
                            text-center
                            unit-column
                        "

                        style="
                            font-size:
                            12px
                        "
                    >

                        Total

                    </th>



                    {{-- =========================================
                         DATE HEADER
                    ========================================= --}}

                    @for (
                        $i = 1;
                        $i <=
                        $currentDate->daysInMonth;
                        $i++
                    )

                        @php

                            $date =
                                Carbon::create(
                                    $currentDate->year,
                                    $currentDate->month,
                                    $i
                                );

                        @endphp


                        <th
                            class="
                                date-column
                            "
                        >

                            {{-- DATE NUMBER --}}
                            <div
                                class="
                                    date-number

                                    {{
                                        $date
                                            ->isWeekend()
                                            ?
                                            'weekend'
                                            :
                                            ''
                                    }}
                                "
                            >

                                {{ $i }}

                            </div>


                            {{-- DATE DAY --}}
                            <div class="date-day {{ $date->isWeekend() ? 'weekend' : '' }}">
                                {{ $date->translatedFormat('D') }}
                            </div>

                        </th>

                    @endfor

                </tr>

            </thead>



            {{-- =================================================
                 BODY
            ================================================= --}}

            <tbody>

                @foreach (
                    $cars
                    as
                    $car
                )

                    @php

                        $totalBookingPerCar =
                            $bookings
                                ->where(
                                    'car_id',
                                    $car->id
                                )
                                ->count();

                    @endphp


                    <tr>


                        {{-- =====================================
                             CAR
                        ===================================== --}}

                        <td class="car-info sticky-col-1 text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="car-unit-badge bg-primary text-white rounded-2 px-2 py-1 fw-bold text-center flex-shrink-0" style="font-size: 11px; min-width: 44px; letter-spacing: 0.2px;">
                                    {{ $car->unit }}
                                </div>
                            </div>
                        </td>



                        {{-- =====================================
                             TOTAL BOOKING
                        ===================================== --}}

                        <td>

                            {{
                                $totalBookingPerCar
                            }}

                        </td>



                        {{-- =====================================
                             DATE CELL
                        ===================================== --}}

                        @for (
                            $d = 1;
                            $d <=
                            $currentDate->daysInMonth;
                            $d++
                        )

                            @php

                                $bookingFound =
                                    null;

                                $isStart =
                                    false;

                                $isEnd =
                                    false;


                                foreach (
                                    $bookings
                                    as
                                    $bookingItem
                                ) {

                                    if (
                                        $bookingItem
                                            ->car_id
                                        ==
                                        $car->id
                                    ) {

                                        $start =
                                            Carbon::parse(
                                                $bookingItem
                                                    ->start_date
                                            )->day;

                                        $end =
                                            Carbon::parse(
                                                $bookingItem
                                                    ->end_date
                                            )->day;


                                        if (
                                            $d >=
                                            $start
                                            &&
                                            $d <=
                                            $end
                                        ) {

                                            $bookingFound =
                                                $bookingItem;

                                            $isStart =
                                                $d ==
                                                $start;

                                            $isEnd =
                                                $d ==
                                                $end;

                                            break;
                                        }
                                    }
                                }

                            @endphp


                            <td
                                class="
                                    calendar-cell
                                "
                            >

                                @if (
                                    $bookingFound
                                )

                                    @php

                                        $totalDays =
                                            Carbon::parse(
                                                $bookingFound
                                                    ->start_date
                                            )
                                            ->diffInDays(
                                                Carbon::parse(
                                                    $bookingFound
                                                        ->end_date
                                                )
                                            )
                                            + 1;


                                        $isSingleDay =
                                            $totalDays <= 2;

                                    @endphp


                                    <div
                                        onclick="
                                            showBooking(
                                                {{
                                                    $bookingFound
                                                        ->id
                                                }}
                                            )
                                        "

                                        class="
                                            booking-bar
                                            cursor-pointer

                                            @if (
                                                $bookingFound
                                                    ->status
                                                ==
                                                'Booking'
                                            )

                                                booking-confirmed

                                            @else

                                                booking-plan

                                            @endif

                                            {{
                                                $isStart
                                                    ?
                                                    'booking-start'
                                                    :
                                                    ''
                                            }}

                                            {{
                                                $isEnd
                                                    ?
                                                    'booking-end'
                                                    :
                                                    ''
                                            }}
                                        "

                                        title="
Customer : {{
    optional(
        $bookingFound
            ->customer
    )->name
    ??
    '-'
}}
Tanggal : {{
    Carbon::parse(
        $bookingFound
            ->start_date
    )
    ->translatedFormat(
        'd F Y'
    )
}}
Sampai : {{
    Carbon::parse(
        $bookingFound
            ->end_date
    )
    ->translatedFormat(
        'd F Y'
    )
}}
Status : {{
    $bookingFound
        ->status
}}
                                        "

                                        @if (
                                            $isStart
                                        )

                                            style="
                                                width:
                                                calc(
                                                    {{
                                                        $totalDays
                                                    }}
                                                    * 103%
                                                );
                                            "

                                        @else

                                            style="
                                                display:
                                                none;
                                            "

                                        @endif
                                    >


                                        {{-- =====================
                                             LABEL ONLY FIRST CELL
                                        ===================== --}}

                                        @if (
                                            $isStart
                                        )

                                            <div
                                                class="
                                                    booking-label
                                                    cursor-pointer
                                                "

                                                style="
                                                    width:
                                                    calc(
                                                        {{
                                                            $totalDays
                                                        }}
                                                        * 26px
                                                    );
                                                "
                                            >

                                                {{-- CUSTOMER --}}
                                                <div
                                                    class="
                                                        booking-title
                                                        fw-bold
                                                    "
                                                >

                                                    @if (
                                                        $isSingleDay
                                                    )

                                                        {{
                                                            \Illuminate\Support\Str::limit(
                                                                optional(
                                                                    $bookingFound
                                                                        ->customer
                                                                )->name
                                                                ??
                                                                '-',
                                                                3,
                                                                '...'
                                                            )
                                                        }}

                                                    @else

                                                        {{
                                                            \Illuminate\Support\Str::limit(
                                                                optional(
                                                                    $bookingFound
                                                                        ->customer
                                                                )->name
                                                                ??
                                                                '-',
                                                                8,
                                                                '...'
                                                            )
                                                        }}

                                                    @endif

                                                </div>



                                                {{-- DATE --}}
                                                <div
                                                    class="
                                                        booking-date
                                                        cursor-pointer
                                                    "
                                                >

                                                    @if (
                                                        $isSingleDay
                                                    )

                                                        {{
                                                            \Illuminate\Support\Str::limit(
                                                                Carbon::parse(
                                                                    $bookingFound
                                                                        ->start_date
                                                                )
                                                                ->translatedFormat(
                                                                    'd M'
                                                                ),
                                                                3,
                                                                '...'
                                                            )
                                                        }}

                                                    @else

                                                        {{
                                                            Carbon::parse(
                                                                $bookingFound
                                                                    ->start_date
                                                            )
                                                            ->translatedFormat(
                                                                'd'
                                                            )
                                                        }}

                                                        -

                                                        {{
                                                            Carbon::parse(
                                                                $bookingFound
                                                                    ->end_date
                                                            )
                                                            ->translatedFormat(
                                                                'd M'
                                                            )
                                                        }}

                                                    @endif

                                                </div>



                                                {{-- STATUS --}}
                                                <div
                                                    class="
                                                        booking-date
                                                        fw-semibold
                                                    "
                                                >

                                                    @if (
                                                        $bookingFound
                                                            ->status
                                                        ==
                                                        'Booking'
                                                    )

                                                        @if (
                                                            $isSingleDay
                                                        )

                                                            {{
                                                                \Illuminate\Support\Str::limit(
                                                                    '(Booking)',
                                                                    3,
                                                                    '...'
                                                                )
                                                            }}

                                                        @else

                                                            (Booking)

                                                        @endif

                                                    @else

                                                        (Selesai)

                                                    @endif

                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                @endif

                            </td>

                        @endfor

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
{{-- END OF CALENDAR TABLE WRAPPER --}}

    </div>
    {{-- ============================================================
         END TAB 1: DASHBOARD DAFTAR BOOKING
    ============================================================ --}}

    {{-- ============================================================
         TAB 2: DASHBOARD KEUANGAN
    ============================================================ --}}
    @include('page.kalender-booking.tab-finance')

</div>
{{-- END DASHBOARD TAB CONTENT --}}

{{-- ============================================================
     MODALS
============================================================ --}}
@include('page.booking.form-booking')
@include('page.booking.form-pembayaran-booking')
@include('modal.customer-booking-modal')
@include('modal.booking-modal')

@endsection

@push('scripts')
<script src="{{ asset('assets/js/payment.js') }}?v={{ time() }}"></script>
<script>
    // Tab switching with URL hash (#booking vs #keuangan)
    $(document).ready(function() {
        const hash = window.location.hash;
        if (hash === '#keuangan' || hash === '#finance') {
            const triggerEl = document.querySelector('#tab-finance-btn');
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }

        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            if (e.target.id === 'tab-finance-btn') {
                history.replaceState(null, null, '#keuangan');
            } else {
                history.replaceState(null, null, '#booking');
            }
        });
    });
</script>



{{-- ============================================================
     SCRIPT
============================================================ --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | FORMAT TANGGAL INDONESIA
    |--------------------------------------------------------------------------
    */

    function formatTanggalIndonesia(
        dateString
    ) {

        const bulan = [

            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'

        ];


        const parts =
            dateString.split(
                '-'
            );


        const tahun =
            parts[0];


        const bulanIndex =
            parseInt(
                parts[1]
            ) - 1;


        const hari =
            parseInt(
                parts[2]
            );


        return (
            hari
            +
            ' '
            +
            bulan[bulanIndex]
            +
            ' '
            +
            tahun
        );

    }



    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(
        angka
    ) {

        angka =
            parseInt(
                angka
            )
            ||
            0;


        return (
            'Rp '
            +
            angka
                .toLocaleString(
                    'id-ID'
                )
        );

    }



    /*
    |--------------------------------------------------------------------------
    | SHOW BOOKING
    |--------------------------------------------------------------------------
    */

    window.showBooking =
        function(id) {

            $.get(
                '/kalendar-booking/show/'
                +
                id,

                function(res) {


                    /*
                    |----------------------------------------------------------
                    | CUSTOMER
                    |----------------------------------------------------------
                    */

                    $('#modalCustomer')
                        .text(
                            res
                                ?.customer
                                ?.name
                            ||
                            '-'
                        );



                    /*
                    |----------------------------------------------------------
                    | CAR
                    |----------------------------------------------------------
                    */

                    $('#modalCar')
                        .text(
                            res
                                ?.car
                                ?.unit
                            ||
                            '-'
                        );



                    /*
                    |----------------------------------------------------------
                    | DATE
                    |----------------------------------------------------------
                    */

                    const startDate =
                        formatTanggalIndonesia(
                            res.start_date
                        );


                    const endDate =
                        formatTanggalIndonesia(
                            res.end_date
                        );


                    $('#modalDate')
                        .text(
                            startDate
                            +
                            ' - '
                            +
                            endDate
                        );



                    /*
                    |----------------------------------------------------------
                    | STATUS
                    |----------------------------------------------------------
                    */

                    let statusText =
                        '';


                    let statusClass =
                        '';


                    if (
                        res.status
                        ===
                        'Booking'
                    ) {

                        statusText =
                            'Booking';


                        statusClass =
                            'bg-success';

                    } else {

                        statusText =
                            'Selesai';


                        statusClass =
                            'bg-danger';

                    }



                    /*
                    |----------------------------------------------------------
                    | DETAIL
                    |----------------------------------------------------------
                    */

                    $('#modalPickup')
                        .text(
                            res.pickup
                            ||
                            '-'
                        );


                    $('#modalDestination')
                        .text(
                            res.destination
                            ||
                            '-'
                        );


                    $('#modalTotalprice')
                        .text(
                            formatRupiah(
                                res.total_price
                            )
                        );



                    /*
                    |----------------------------------------------------------
                    | DRIVER
                    |----------------------------------------------------------
                    */

                    const driverName =

                        res
                            ?.driver
                            ?.nama_lengkap

                        ||

                        res
                            ?.driver
                            ?.nama;


                    if (
                        driverName
                    ) {

                        $('#modalDriver')
                            .text(
                                driverName
                            )
                            .show();


                        $('#modalDriverLabel')
                            .show();

                    } else {

                        $('#modalDriver')
                            .hide();


                        $('#modalDriverLabel')
                            .hide();

                    }



                    /*
                    |----------------------------------------------------------
                    | STATUS BADGE
                    |----------------------------------------------------------
                    */

                    $('#modalStatus')
                        .removeClass()
                        .addClass(
                            'badge rounded-pill px-3 py-2 '
                            +
                            statusClass
                        )
                        .text(
                            statusText
                        );



                    /*
                    |----------------------------------------------------------
                    | MODAL
                    |----------------------------------------------------------
                    */

                    const modalElement =
                        document
                            .getElementById(
                                'bookingModal'
                            );


                    if (
                        !modalElement
                    ) {

                        return;

                    }


                    const modal =
                        bootstrap
                            .Modal
                            .getOrCreateInstance(
                                modalElement
                            );


                    modal.show();

                }
            );

        };

</script>


<script>

    /*
    |--------------------------------------------------------------------------
    | CLEAN MODAL BACKDROP
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',

        function() {

            document
                .querySelectorAll(
                    '.modal-backdrop'
                )
                .forEach(
                    function(el) {

                        el.remove();

                    }
                );


            document.body
                .classList
                .remove(
                    'modal-open'
                );


            document.body
                .style
                .overflow =
                'auto';


            document.body
                .style
                .paddingRight =
                '0px';


            document.body
                .style
                .pointerEvents =
                'auto';

        }
    );

</script>
@endpush

