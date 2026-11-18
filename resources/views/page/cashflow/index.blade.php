@extends('layouts.app')

@section('content')

{{-- CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/cashflow.css') }}?v={{ time() }}">
<style>
    .nav-tabs-sheet {
        border-bottom: 2px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 12px 12px 0 0;
        padding: 6px 10px 0 10px;
    }
    .nav-tabs-sheet .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        padding: 9px 16px;
        border-radius: 8px 8px 0 0;
        margin-right: 4px;
        transition: all 0.2s ease;
    }
    .nav-tabs-sheet .nav-link:hover {
        color: #1e293b;
        background: #f1f5f9;
    }
    .nav-tabs-sheet .nav-link.active {
        color: #166534;
        background: #ffffff;
        border-bottom: 3px solid #16a34a;
        box-shadow: 0 -2px 6px rgba(0,0,0,0.03);
    }
    .nav-tabs-sheet .nav-link.active-kelola {
        color: #1d4ed8;
        border-bottom: 3px solid #2563eb;
    }
    .table-sheet {
        font-size: 13px;
    }
    .table-sheet th {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
    }
    .badge-unit {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .quick-month-btn {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
    }
</style>

<div class="w-100 px-0 py-2">

    <!-- HEADER & ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
        <div>
            <h3 class="fw-bold mb-1 fs-5 text-dark">
                <i class="bi bi-wallet2 text-success me-2"></i>Cash Flow & Laporan Keuangan
            </h3>
            <p class="text-muted mb-0" style="font-size: 12.5px;">
                Laporan arus kas dan keuangan operasional rental mobil <span class="badge bg-light text-dark border ms-1">{{ $report['periode_label'] }}</span>
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- EXPORT PDF DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-danger btn-sm rounded-3 shadow-sm dropdown-toggle px-3 py-2 fw-semibold" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Export PDF
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                    <li><h6 class="dropdown-header text-muted">Pilihan Format PDF</h6></li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'all']) }}" target="_blank">
                            <i class="bi bi-collection me-2 text-danger"></i> <strong>Semua Sheet (Lengkap)</strong>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-1.5" href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'keseluruhan']) }}" target="_blank">
                            <i class="bi bi-table me-2 text-primary"></i> Total Keseluruhan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-1.5" href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'garasi']) }}" target="_blank">
                            <i class="bi bi-tools me-2 text-warning"></i> Garasi
                        </a>
                    </li>
                    @foreach($cars as $c)
                    <li>
                        <a class="dropdown-item py-1.5" href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => $c->unit]) }}" target="_blank">
                            <i class="bi bi-truck me-2 text-success"></i> Unit {{ $c->unit }} ({{ $c->name }})
                        </a>
                    </li>
                    @endforeach
                    <li>
                        <a class="dropdown-item py-1.5" href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'lain']) }}" target="_blank">
                            <i class="bi bi-receipt me-2 text-secondary"></i> Lain - Lain
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('cashflow.print', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'all']) }}" target="_blank">
                            <i class="bi bi-printer me-2 text-dark"></i> Preview & Print Langsung
                        </a>
                    </li>
                </ul>
            </div>

            <!-- EXPORT EXCEL -->
            <button id="btnExportExcel" class="btn btn-outline-success btn-sm rounded-3 shadow-sm px-3 py-2 fw-semibold">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </button>

            <!-- TAMBAH TRANSAKSI -->
            <button class="btn btn-primary btn-sm rounded-3 shadow-sm px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCashFlow">
                <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <!-- FILTER PERIODE -->
    <div class="card border-0 shadow-sm rounded-4 mb-3 w-100">
        <div class="card-body p-2.5 p-md-3">
            <form id="filterFormPeriod" method="GET" action="{{ route('cashflow.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-auto text-muted small fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i> Periode Laporan:
                    </div>

                    <div class="col-6 col-md-3">
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm rounded-3" value="{{ $startDate }}">
                    </div>

                    <div class="col-6 col-md-3">
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm rounded-3" value="{{ $endDate }}">
                    </div>

                    <div class="col-12 col-md-auto d-flex gap-2">
                        <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i> Terapkan
                        </button>
                    </div>

                    <div class="col-12 col-md d-flex flex-wrap gap-1 justify-content-md-end mt-2 mt-md-0">
                        <span class="text-muted small align-self-center me-1">Cepat:</span>
                        <a href="{{ route('cashflow.index', ['start_date' => '2026-10-01', 'end_date' => '2026-10-31']) }}" class="btn btn-outline-secondary quick-month-btn {{ $startDate == '2026-10-01' ? 'active bg-secondary text-white' : '' }}">
                            Okt 2026
                        </a>
                        <a href="{{ route('cashflow.index', ['start_date' => '2026-11-01', 'end_date' => '2026-11-30']) }}" class="btn btn-outline-secondary quick-month-btn {{ $startDate == '2026-11-01' ? 'active bg-secondary text-white' : '' }}">
                            Nov 2026
                        </a>
                        <a href="{{ route('cashflow.index', ['start_date' => '2026-09-01', 'end_date' => '2026-09-30']) }}" class="btn btn-outline-secondary quick-month-btn {{ $startDate == '2026-09-01' ? 'active bg-secondary text-white' : '' }}">
                            Sep 2026
                        </a>
                        <a href="{{ route('cashflow.index', ['start_date' => '2026-01-01', 'end_date' => '2026-12-31']) }}" class="btn btn-outline-secondary quick-month-btn {{ $startDate == '2026-01-01' ? 'active bg-secondary text-white' : '' }}">
                            Tahun 2026
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-2 g-md-3 mb-3">
        <!-- TOTAL PEMASUKAN -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Pemasukan Periode Ini</small>
                            <h4 class="fw-bold mt-1 mb-0 text-success" id="income_total">
                                Rp {{ number_format($report['total_income'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; background:#e8fff1;">
                            <i class="bi bi-arrow-down-circle-fill" style="font-size:24px; color:#16a34a;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL PENGELUARAN -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Pengeluaran Periode Ini</small>
                            <h4 class="fw-bold mt-1 mb-0 text-danger" id="expense_total">
                                Rp {{ number_format($report['total_expense'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; background:#fff1f2;">
                            <i class="bi bi-arrow-up-circle-fill" style="font-size:24px; color:#dc2626;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SALDO AKHIR -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Saldo Akhir Kumulatif</small>
                            <h4 class="fw-bold mt-1 mb-0 text-primary" id="profit_total">
                                Rp {{ number_format($report['saldo_akhir'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; background:#eef4ff;">
                            <i class="bi bi-wallet2" style="font-size:24px; color:#2563eb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABS CONTAINER -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <!-- SHEET TABS NAVIGATION -->
        <ul class="nav nav-tabs nav-tabs-sheet" id="cashflowTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-keseluruhan-btn" data-bs-toggle="tab" data-bs-target="#tab-keseluruhan" type="button" role="tab">
                    <i class="bi bi-table me-1 text-success"></i> Total Keseluruhan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-garasi-btn" data-bs-toggle="tab" data-bs-target="#tab-garasi" type="button" role="tab">
                    <i class="bi bi-wrench-adjustable me-1"></i> Garasi
                </button>
            </li>
            @foreach($report['cars_report'] as $uKey => $uData)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-unit-{{ Str::slug($uKey) }}-btn" data-bs-toggle="tab" data-bs-target="#tab-unit-{{ Str::slug($uKey) }}" type="button" role="tab">
                    <i class="bi bi-truck me-1"></i> {{ $uKey }} {{ $uKey == 'AM-01' || $uKey == 'AM-04' ? 'Hiace' : ($uKey == 'AM-02' ? 'Dutro' : ($uKey == 'AM-03' ? 'Giga' : '')) }}
                </button>
            </li>
            @endforeach
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-lain-btn" data-bs-toggle="tab" data-bs-target="#tab-lain" type="button" role="tab">
                    <i class="bi bi-receipt me-1"></i> Lain Lain
                </button>
            </li>
            <li class="nav-item ms-auto" role="presentation">
                <button class="nav-link active-kelola" id="tab-kelola-btn" data-bs-toggle="tab" data-bs-target="#tab-kelola" type="button" role="tab">
                    <i class="bi bi-sliders me-1 text-primary"></i> Data Transaksi (Kelola)
                </button>
            </li>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content card-body p-3" id="cashflowTabContent">

            <!-- ============================================================= -->
            <!-- 1. TOTAL KESELURUHAN -->
            <!-- ============================================================= -->
            <div class="tab-pane fade show active" id="tab-keseluruhan" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            Laporan Kas Total Keseluruhan
                        </h6>
                        <small class="text-muted">Buku kas umum dengan running balance periode {{ $report['periode_label'] }}</small>
                    </div>
                    <a href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'keseluruhan']) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-3">
                        <i class="bi bi-file-pdf me-1"></i> Download PDF
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sheet align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center align-middle">
                                <th style="width: 14%;">Tanggal</th>
                                <th style="width: 28%;">Keterangan</th>
                                <th style="width: 13%;">Tanggal Keluar</th>
                                <th style="width: 15%;">Tujuan</th>
                                <th style="width: 15%;" class="text-success">Pemasukan</th>
                                <th style="width: 15%;" class="text-danger">Pengeluaran</th>
                                <th style="width: 15%;" class="text-primary">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report['keseluruhan'] as $row)
                            <tr class="{{ $row['keterangan'] == 'Saldo Sebelumnya' ? 'table-warning fw-semibold' : '' }}">
                                <td class="text-nowrap">{{ $row['tanggal'] }}</td>
                                <td>{{ $row['keterangan'] }}</td>
                                <td class="text-center">{{ $row['tgl_keluar'] }}</td>
                                <td>{{ $row['tujuan'] }}</td>
                                <td class="text-end fw-semibold text-success">
                                    {{ $row['pemasukan'] > 0 ? 'Rp ' . number_format($row['pemasukan'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end fw-semibold text-danger">
                                    {{ $row['pengeluaran'] > 0 ? 'Rp ' . number_format($row['pengeluaran'], 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end fw-bold text-dark bg-light">
                                    Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data transaksi pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end">TOTAL PERIODE INI</td>
                                <td class="text-end text-success">Rp {{ number_format($report['total_income'], 0, ',', '.') }}</td>
                                <td class="text-end text-danger">Rp {{ number_format($report['total_expense'], 0, ',', '.') }}</td>
                                <td class="text-end text-primary">Rp {{ number_format($report['saldo_akhir'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- 2. GARASI -->
            <!-- ============================================================= -->
            <div class="tab-pane fade" id="tab-garasi" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            Pengeluaran Garasi & Fasilitas
                        </h6>
                        <small class="text-muted">Biaya operasional, listrik, perlengkapan garasi, dll.</small>
                    </div>
                    <a href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'garasi']) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-3">
                        <i class="bi bi-file-pdf me-1"></i> Download PDF
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sheet align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 25%;">Tanggal</th>
                                        <th style="width: 50%;">Kebutuhan</th>
                                        <th style="width: 25%;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($report['garasi'] as $g)
                                    <tr>
                                        <td>{{ $g['tanggal'] }}</td>
                                        <td>{{ $g['kebutuhan'] }}</td>
                                        <td class="text-end fw-semibold text-danger">Rp {{ number_format($g['jumlah'], 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada pengeluaran dengan kategori Garasi pada periode ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr class="table-warning">
                                        <td colspan="2" class="text-end">TOTAL GARASI</td>
                                        <td class="text-end text-danger">Rp {{ number_format($report['total_garasi'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0">
                        <div class="card border-0 bg-light rounded-4 p-3">
                            <h6 class="fw-bold text-dark mb-2">Ringkasan Biaya Garasi</h6>
                            <p class="text-muted small mb-3">Kategori "Garasi" mencatat biaya operasional, tagihan listrik, sewa, serta perlengkapan dan kebersihan fasilitas armada.</p>
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted">Total Transaksi</span>
                                <span class="fw-bold">{{ count($report['garasi']) }} transaksi</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted">Total Pengeluaran</span>
                                <span class="fw-bold text-danger fs-6">Rp {{ number_format($report['total_garasi'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- 3. PER UNIT (AM-01, AM-02, AM-03, AM-04, etc.) -->
            <!-- ============================================================= -->
            @foreach($report['cars_report'] as $uKey => $uData)
            @php
                $pemasukanCount = count($uData['pemasukan']);
                $pengeluaranCount = count($uData['pengeluaran']);
                $maxRows = max($pemasukanCount, $pengeluaranCount, 1);
            @endphp
            <div class="tab-pane fade" id="tab-unit-{{ Str::slug($uKey) }}" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            Rincian Arus Kas: <span class="text-success">{{ $uData['label'] }}</span>
                        </h6>
                        <small class="text-muted">Pemasukan (setoran customer/order) vs Pengeluaran perbaikan/operasional unit</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-3">
                            Saldo Unit: <strong>Rp {{ number_format($uData['saldo_unit'], 0, ',', '.') }}</strong>
                        </span>
                        <a href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => $uKey]) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-3">
                            <i class="bi bi-file-pdf me-1"></i> Download PDF
                        </a>
                    </div>
                </div>

                <!-- SIDE BY SIDE TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-sheet align-middle mb-0">
                        <thead>
                            <tr class="text-center align-middle">
                                <th colspan="5" class="bg-success text-white py-2" style="width: 55%;">
                                    <i class="bi bi-arrow-down-circle me-1"></i> PEMASUKAN / SETORAN
                                </th>
                                <th colspan="3" class="bg-danger text-white py-2" style="width: 45%;">
                                    <i class="bi bi-arrow-up-circle me-1"></i> PENGELUARAN / PERBAIKAN
                                </th>
                            </tr>
                            <tr class="text-center align-middle table-light" style="font-size: 11.5px;">
                                <th style="width: 10%;">Jadwal</th>
                                <th style="width: 15%;">Order</th>
                                <th style="width: 13%;">Tujuan</th>
                                <th style="width: 9%;">Tgl</th>
                                <th style="width: 13%;">Setoran</th>
                                <th style="width: 11%;">Tanggal</th>
                                <th style="width: 22%;">Keterangan</th>
                                <th style="width: 12%;">Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($pemasukanCount == 0 && $pengeluaranCount == 0)
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada data pemasukan untuk unit ini.</td>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data pengeluaran untuk unit ini.</td>
                            </tr>
                            @else
                                @for($i = 0; $i < $maxRows; $i++)
                                @php
                                    $in = $uData['pemasukan'][$i] ?? null;
                                    $ex = $uData['pengeluaran'][$i] ?? null;
                                @endphp
                                <tr>
                                    <!-- PEMASUKAN COLUMNS -->
                                    <td class="text-center">{{ $in['jadwal'] ?? '-' }}</td>
                                    <td>{{ $in['order'] ?? '-' }}</td>
                                    <td>{{ $in['tujuan'] ?? '-' }}</td>
                                    <td class="text-center">{{ $in['tgl_setor'] ?? '-' }}</td>
                                    <td class="text-end fw-semibold text-success">
                                        {{ isset($in['setoran']) && $in['setoran'] > 0 ? 'Rp ' . number_format($in['setoran'], 0, ',', '.') : '-' }}
                                    </td>

                                    <!-- PENGELUARAN COLUMNS -->
                                    <td class="text-center">{{ $ex['tanggal'] ?? '-' }}</td>
                                    <td>{{ $ex['keterangan'] ?? '-' }}</td>
                                    <td class="text-end fw-semibold text-danger">
                                        {{ isset($ex['pengeluaran']) && $ex['pengeluaran'] > 0 ? 'Rp ' . number_format($ex['pengeluaran'], 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endfor
                            @endif
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end">TOTAL PEMASUKAN</td>
                                <td class="text-end text-success">Rp {{ number_format($uData['total_pemasukan'], 0, ',', '.') }}</td>
                                <td colspan="2" class="text-end">TOTAL PENGELUARAN</td>
                                <td class="text-end text-danger">Rp {{ number_format($uData['total_pengeluaran'], 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="5" class="text-end">SALDO SISA UNIT (PEMASUKAN - PENGELUARAN)</td>
                                <td colspan="3" class="text-center fs-6 text-primary">
                                    Rp {{ number_format($uData['saldo_unit'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endforeach

            <!-- ============================================================= -->
            <!-- 4. LAIN LAIN -->
            <!-- ============================================================= -->
            <div class="tab-pane fade" id="tab-lain" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            Pengeluaran Lain - Lain
                        </h6>
                        <small class="text-muted">Biaya non-armada: Wifi, admin, transfer owner, pulsa, konsumsi umum, dll.</small>
                    </div>
                    <a href="{{ route('cashflow.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'tab' => 'lain']) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-3">
                        <i class="bi bi-file-pdf me-1"></i> Download PDF
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sheet align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 25%;">Tanggal</th>
                                        <th style="width: 50%;">Keterangan</th>
                                        <th style="width: 25%;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($report['lain_lain'] as $l)
                                    <tr>
                                        <td>{{ $l['tanggal'] }}</td>
                                        <td>{{ $l['keterangan'] }}</td>
                                        <td class="text-end fw-semibold text-danger">Rp {{ number_format($l['jumlah'], 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Tidak ada data pengeluaran lain-lain pada periode ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr class="table-warning">
                                        <td colspan="2" class="text-end">TOTAL LAIN - LAIN</td>
                                        <td class="text-end text-danger">Rp {{ number_format($report['total_lain_lain'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0">
                        <div class="card border-0 bg-light rounded-4 p-3">
                            <h6 class="fw-bold text-dark mb-2">Ringkasan Lain-Lain</h6>
                            <p class="text-muted small mb-3">Kategori ini mencakup seluruh biaya operasional kantor dan umum yang tidak dialokasikan ke unit kendaraan tertentu maupun fasilitas garasi.</p>
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted">Total Transaksi</span>
                                <span class="fw-bold">{{ count($report['lain_lain']) }} transaksi</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                                <span class="text-muted">Total Pengeluaran</span>
                                <span class="fw-bold text-danger fs-6">Rp {{ number_format($report['total_lain_lain'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================= -->
            <!-- 5. KELOLA DATA TRANSAKSI (Interactive DataTable) -->
            <!-- ============================================================= -->
            <div class="tab-pane fade" id="tab-kelola" role="tabpanel">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-sliders me-1"></i> Manajemen Data Kas Transaksi
                        </h6>
                        <small class="text-muted">Cari, filter per unit mobil, serta edit atau hapus pencatatan cash flow.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm rounded-3 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalCashFlow">
                            <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
                        </button>
                    </div>
                </div>

                <!-- FILTER MOBIL -->
                <div class="card border-0 bg-light rounded-3 mb-3 p-2.5">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold mb-1">Filter Berdasarkan Unit Mobil:</label>
                            <select id="car_id" class="form-select form-select-sm rounded-3">
                                <option value="">Semua Mobil / Biaya Umum</option>
                                @foreach($cars as $car)
                                <option value="{{ $car->id }}">
                                    {{ $car->unit }} - {{ $car->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="button" id="btnFilter" class="btn btn-primary btn-sm rounded-3 w-100">
                                <i class="bi bi-funnel me-1"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table-cashflow" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th width="15%">Unit</th>
                                <th>Jenis</th>
                                <th>Title</th>
                                <th>Keterangan</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="6" class="text-end fw-bold">
                                    Total Nominal
                                </td>
                                <td id="footer_total_amount" class="fw-bold text-end">
                                    Rp 0
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

@include('page.cashflow.form-cashflow')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    window.cashflowUrl = {
        data: "{{ route('cashflow.data') }}",
        summary: "{{ route('cashflow.summary') }}"
    };

    // Keep active tab state across page reload or anchor
    document.addEventListener("DOMContentLoaded", function() {
        const hash = window.location.hash;
        if (hash) {
            const triggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (triggerEl) {
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }

        // When switching tabs, update URL hash without scrolling
        const tabBtns = document.querySelectorAll('#cashflowTab button[data-bs-toggle="tab"]');
        tabBtns.forEach(btn => {
            btn.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                if (history.pushState) {
                    history.pushState(null, null, target);
                } else {
                    location.hash = target;
                }
            });
        });
    });
</script>

<script src="{{ asset('assets/js/cashflow.js') }}"></script>
@endpush
