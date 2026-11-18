<div class="tab-pane fade" id="tabFinanceDashboard" role="tabpanel" aria-labelledby="tab-finance-btn">

    {{-- HEADER KEUANGAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="fs-5 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-wallet2 text-primary"></i>
                <span>Keuangan & Arus Kas</span>
            </h2>
            <small class="text-muted" style="font-size: 12px;">Periode Pembukuan: <strong>{{ $currentDate->translatedFormat('F Y') }}</strong></small>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- MONTH PICKER PILL UNTUK KEUANGAN KAS --}}
            <div class="calendar-navigation d-flex align-items-center bg-white border rounded-pill p-1 shadow-xs flex-fill flex-md-grow-0 justify-content-between">
                <a href="{{ route('kalendarBooking', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}#keuangan" class="btn-nav-compact" title="Bulan Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div class="calendar-date-compact fw-semibold px-2 text-dark text-center" style="font-size: 12.5px;">
                    <i class="bi bi-calendar3 me-1 text-primary"></i>
                    <span>{{ $currentDate->translatedFormat('F Y') }}</span>
                </div>
                <a href="{{ route('kalendarBooking', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}#keuangan" class="btn-nav-compact" title="Bulan Selanjutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <a href="{{ route('cashflow.export.excel', ['start_date' => $currentDate->copy()->startOfMonth()->toDateString(), 'end_date' => $currentDate->copy()->endOfMonth()->toDateString()]) }}"
                class="btn btn-outline-success btn-sm rounded-3 px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-sm flex-fill flex-md-grow-0 justify-content-center" style="font-size: 12px;">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </a>

            <a href="{{ route('cashflow.index') }}"
                class="btn btn-primary btn-sm rounded-3 px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 shadow-sm flex-fill flex-md-grow-0 justify-content-center" style="font-size: 12px;">
                <i class="bi bi-wallet2"></i>
                <span>Kelola Kas</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI STATS CARDS (CLEAN 2x2 ON MOBILE, 4 IN A ROW ON DESKTOP) --}}
    <div class="row g-2 g-md-3 mb-3">
        {{-- 1. PEMASUKAN --}}
        <div class="col-6 col-xl-3">
            <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #10b981;">
                <div class="card-body p-2.5 p-md-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="kpi-label text-truncate">Pemasukan</span>
                        <div class="kpi-icon-box bg-success-subtle text-success">
                            <i class="bi bi-arrow-down-left"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-success mb-1" style="font-size: 16px; line-height: 1.2;">
                        Rp {{ number_format($incomeMonth, 0, ',', '.') }}
                    </div>
                    <div class="d-flex align-items-center gap-1 small" style="font-size: 11px;">
                        @php
                            $growthIncome = $incomePrevMonth > 0 ? round((($incomeMonth - $incomePrevMonth) / $incomePrevMonth) * 100, 1) : 0;
                        @endphp
                        @if($growthIncome >= 0)
                            <span class="text-success fw-medium">
                                <i class="bi bi-arrow-up-right me-0"></i>+{{ $growthIncome }}%
                            </span>
                        @else
                            <span class="text-danger fw-medium">
                                <i class="bi bi-arrow-down-right me-0"></i>{{ $growthIncome }}%
                            </span>
                        @endif
                        <span class="text-muted d-none d-sm-inline">vs bln lalu</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PENGELUARAN --}}
        <div class="col-6 col-xl-3">
            <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #ef4444;">
                <div class="card-body p-2.5 p-md-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="kpi-label text-truncate">Pengeluaran</span>
                        <div class="kpi-icon-box bg-danger-subtle text-danger">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-danger mb-1" style="font-size: 16px; line-height: 1.2;">
                        Rp {{ number_format($expenseMonth, 0, ',', '.') }}
                    </div>
                    <div class="text-muted text-truncate" style="font-size: 11px;">
                        Gaji: <strong>Rp {{ number_format($salaryExpense, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. LABA BERSIH / ARUS KAS BERSIH --}}
        <div class="col-6 col-xl-3">
            <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid {{ $netProfitMonth >= 0 ? '#2563eb' : '#ef4444' }};">
                <div class="card-body p-2.5 p-md-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="kpi-label text-truncate">Laba Bersih Kas</span>
                        <div class="kpi-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div class="fw-bold {{ $netProfitMonth >= 0 ? 'text-primary' : 'text-danger' }} mb-1" style="font-size: 16px; line-height: 1.2;">
                        Rp {{ number_format($netProfitMonth, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 11px;">
                        <span class="badge {{ $netProfitMonth >= 0 ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} fw-medium px-2 py-0.5">
                            {{ $netProfitMonth >= 0 ? 'Surplus' : 'Defisit' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. PIUTANG / BELUM LUNAS --}}
        <div class="col-6 col-xl-3">
            <div class="card summary-kpi-card h-100" style="border-left: 3.5px solid #f59e0b;">
                <div class="card-body p-2.5 p-md-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="kpi-label text-truncate">Piutang Sewa</span>
                        <div class="kpi-icon-box bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="fw-bold text-warning-emphasis mb-1" style="font-size: 16px; line-height: 1.2;">
                        Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                    </div>
                    <div class="text-muted text-truncate" style="font-size: 11px;">
                        <span class="badge bg-warning-subtle text-warning-emphasis fw-medium px-2 py-0.5">
                            {{ $pendingPayments->count() }} Booking DP
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INTERACTIVE CHARTS ROW --}}
    <div class="row g-3 mb-4">
        {{-- CHART 1: TREN 6 BULAN ARUS KAS --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Tren Arus Kas (6 Bulan Terakhir)</h6>
                        <small class="text-muted" style="font-size: 11px;">Perbandingan pemasukan vs pengeluaran per bulan</small>
                    </div>
                    <div class="d-flex align-items-center gap-3 small pt-1 pt-sm-0">
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                            <span class="text-muted" style="font-size: 11px; font-weight: 500;">Pemasukan</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                            <span class="text-muted" style="font-size: 11px; font-weight: 500;">Pengeluaran</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div style="height: 250px; position: relative;">
                        <canvas id="monthlyCashflowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART 2: DISTRIBUSI PENGELUARAN --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4 pb-0">
                    <h6 class="fw-bold mb-0 text-dark">Komposisi Pengeluaran Bulan Ini</h6>
                    <small class="text-muted" style="font-size: 11px;">Rincian alokasi biaya pengeluaran</small>
                </div>
                <div class="card-body p-3 p-md-4 d-flex flex-column align-items-center justify-content-center">
                    <div style="height: 190px; width: 100%; position: relative;">
                        <canvas id="expenseDistributionChart"></canvas>
                    </div>
                    <div class="w-100 mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span><i class="bi bi-circle-fill text-primary me-1" style="font-size: 8px;"></i>Gaji Driver/Staf:</span>
                            <strong class="text-dark">Rp {{ number_format($salaryExpense, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span><i class="bi bi-circle-fill text-danger me-1" style="font-size: 8px;"></i>Biaya Operasional:</span>
                            <strong class="text-dark">Rp {{ number_format($otherExpense, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span><i class="bi bi-circle-fill text-warning me-1" style="font-size: 8px;"></i>Biaya Lainnya:</span>
                            <strong class="text-dark">Rp {{ number_format($directExpense, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS & PENDING PAYMENTS ROW --}}
    <div class="row g-3 mb-4">
        {{-- TRANSAKSI TERAKHIR CASHFLOW --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4 pb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0">Transaksi Kas Terbaru</h6>
                        <small class="text-muted" style="font-size: 11px;">Catatan arus kas riil terbaru</small>
                    </div>
                    <a href="{{ route('cashflow.index') }}" class="btn btn-light btn-sm rounded-3 py-1 px-2 text-primary fw-medium" style="font-size: 11.5px;">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    {{-- DESKTOP TABLE VIEW --}}
                    <div class="d-none d-md-block table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 border-0">Tanggal</th>
                                    <th class="border-0">Uraian / Transaksi</th>
                                    <th class="border-0">Mobil</th>
                                    <th class="border-0">Tipe</th>
                                    <th class="pe-3 border-0 text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCashflows as $cf)
                                <tr>
                                    <td class="ps-3 text-muted" style="white-space: nowrap;">
                                        {{ date('d M Y', strtotime($cf->transaction_date)) }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $cf->title }}</div>
                                        @if($cf->description && $cf->description !== '-')
                                            <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ $cf->description }}</small>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ optional(optional($cf->booking)->car)->unit ?? optional($cf->car)->unit ?? '-' }}
                                    </td>
                                    <td>
                                        @if($cf->type === 'income')
                                            <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1">
                                                <i class="bi bi-arrow-down-left me-1"></i>Pemasukan
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1">
                                                <i class="bi bi-arrow-up-right me-1"></i>Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end fw-bold {{ $cf->type === 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ $cf->type === 'income' ? '+' : '-' }}Rp {{ number_format($cf->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi kas bulan ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE LIST VIEW (NO HORIZONTAL CUTOFF) --}}
                    <div class="d-md-none p-3 d-flex flex-column gap-2">
                        @forelse($recentCashflows as $cf)
                            <div class="p-2.5 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                                <div style="min-width: 0;" class="pe-2">
                                    <div class="d-flex align-items-center gap-1.5 mb-1">
                                        <span class="badge {{ $cf->type === 'income' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-2 py-0.5" style="font-size: 9.5px;">
                                            {{ $cf->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                        <span class="text-muted" style="font-size: 10.5px;">
                                            {{ date('d M Y', strtotime($cf->transaction_date)) }}
                                        </span>
                                    </div>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 13px;">{{ $cf->title }}</div>
                                    @if(optional(optional($cf->booking)->car)->unit ?? optional($cf->car)->unit)
                                        <small class="text-muted d-block" style="font-size: 11px;">
                                            Armada: <strong>{{ optional(optional($cf->booking)->car)->unit ?? optional($cf->car)->unit }}</strong>
                                        </small>
                                    @endif
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold {{ $cf->type === 'income' ? 'text-success' : 'text-danger' }}" style="font-size: 13px;">
                                        {{ $cf->type === 'income' ? '+' : '-' }}Rp {{ number_format($cf->amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted small">Belum ada transaksi kas bulan ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- BOOKING MENUNGGU PEMBAYARAN / DP --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4 pb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0">Booking Belum Lunas / DP</h6>
                        <small class="text-muted" style="font-size: 11px;">Tagihan pending yang perlu diproses</small>
                    </div>
                    <a href="{{ route('bookings') }}" class="btn btn-light btn-sm rounded-3 py-1 px-2 text-primary fw-medium" style="font-size: 11.5px;">
                        Semua Booking <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-2">
                        @forelse($pendingPayments as $item)
                            @php
                                $sisa = (float)$item->total_price - (float)$item->dp_amount;
                            @endphp
                            <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold" style="font-size: 10px;">{{ $item->booking_code }}</span>
                                        <span class="badge {{ $item->payment_status === 'DP' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger' }}" style="font-size: 10px;">
                                            {{ $item->payment_status }}
                                        </span>
                                    </div>
                                    <div class="fw-bold text-dark small">{{ optional($item->customer)->name ?? 'Customer' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        {{ optional($item->car)->unit ?? 'Armada' }} &bull; Sisa: <strong class="text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-warning btn-sm btn-payment rounded-3 px-3 py-1 fw-semibold shadow-sm"
                                        data-id="{{ $item->id }}" onclick="openPaymentModal({{ $item->id }})" style="font-size: 11px;">
                                        <i class="bi bi-cash me-1"></i>Bayar
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                                Semua booking saat ini sudah lunas!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS CARDS FOR FINANCE --}}
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <a href="{{ route('finance.salary.index') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100 hover-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Kelola Gaji Driver & Staf</h6>
                        <small class="text-muted">Pencatatan upah harian / bulanan armada</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('finance.expense.index') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100 hover-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-danger-subtle text-danger">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Pengeluaran Operasional</h6>
                        <small class="text-muted">BBM, servis rutin, cuci mobil, dsb.</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('cashflow.index') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100 hover-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-success-subtle text-success">
                        <i class="bi bi-table fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Buku Kas & Filter Tanggal</h6>
                        <small class="text-muted">Filter kas berdasarkan mobil dan periode</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Chart 1: Monthly Cashflow Trend
        const ctxCashflow = document.getElementById('monthlyCashflowChart');
        if (ctxCashflow) {
            new Chart(ctxCashflow, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: {!! json_encode($chartIncome) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderRadius: 6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($chartExpense) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.85)',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let val = context.parsed.y || 0;
                                    return context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) {
                                    if (val >= 1000000) return (val / 1000000) + ' Jt';
                                    if (val >= 1000) return (val / 1000) + ' Rb';
                                    return val;
                                },
                                font: { size: 10.5 },
                                color: '#64748b'
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, weight: '500' },
                                color: '#475569',
                                maxRotation: 0,
                                autoSkip: false,
                                callback: function(val, index) {
                                    let rawLabel = this.getLabelForValue(val);
                                    if (window.innerWidth < 640 && typeof rawLabel === 'string') {
                                        return rawLabel.split(' ')[0];
                                    }
                                    return rawLabel;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize Chart 2: Expense Distribution Doughnut
        const ctxExpense = document.getElementById('expenseDistributionChart');
        if (ctxExpense) {
            const salary = {{ $salaryExpense }};
            const other = {{ $otherExpense }};
            const direct = {{ $directExpense }};
            const total = salary + other + direct;

            new Chart(ctxExpense, {
                type: 'doughnut',
                data: {
                    labels: ['Gaji Driver/Staf', 'Operasional', 'Biaya Lainnya'],
                    datasets: [{
                        data: total > 0 ? [salary, other, direct] : [1, 0, 0],
                        backgroundColor: [
                            '#2563eb',
                            '#ef4444',
                            '#f59e0b'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (total === 0) return 'Belum ada pengeluaran';
                                    let val = context.raw || 0;
                                    return context.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });

    // ============================================================
    // MODAL PEMBAYARAN - GLOBAL DEFINITIONS LANGSUNG DARI BLADE
    // ============================================================
    function formatRupiah(angka) {
        angka = parseInt(angka) || 0;
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
    window.formatRupiah = formatRupiah;

    function cleanNumber(value) {
        return parseInt(String(value || '').replace(/[^0-9]/g, '')) || 0;
    }
    window.cleanNumber = cleanNumber;

    function updateRemainingPayment() {
        let dpAmount = cleanNumber($('#payment_dp_amount').val());
        let totalItems = 0;
        $('.other-price').each(function() {
            totalItems += cleanNumber($(this).val());
        });
        let remaining = totalItems - dpAmount;
        if (remaining < 0) remaining = 0;
        $('#payment_remaining').val(formatRupiah(remaining));
        $('#payment_remaining_base').val(remaining);

        let currentAmount = cleanNumber($('#payment_amount').val());
        let prevRemaining = cleanNumber($('#payment_amount').data('prev-remaining'));
        if (!currentAmount || currentAmount === prevRemaining) {
            $('#payment_amount').val(remaining > 0 ? remaining.toLocaleString('id-ID') : '0');
            $('#payment_amount').data('prev-remaining', remaining);
        }
    }
    window.updateRemainingPayment = updateRemainingPayment;

    window.openPaymentModal = function(id) {
        if (!id) return;

        // Reset dan loading state
        $('#otherPriceWrapper').html('<div class="text-center py-2 text-muted small"><span class="spinner-border spinner-border-sm me-1"></span>Memuat rincian sewa...</div>');
        $('#payment_amount').val('');
        $('#payment_amount').removeData('prev-remaining');
        $('#payment_booking_id').val('Memuat...');
        $('#payment_customer').val('Memuat customer...');
        $('#payment_car').val('Memuat armada...');
        $('#payment_destination').val('Memuat tujuan...');
        $('#payment_dates').val('Memuat tanggal...');
        $('#payment_status_value').html('<span class="spinner-border spinner-border-sm me-1 text-primary"></span>Memuat status...');
        $('#payment_remaining').val('Rp 0');

        const modalEl = document.getElementById('modalPayment');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $ !== 'undefined' && $.fn && $.fn.modal) {
            $('#modalPayment').modal('show');
        }

        $.ajax({
            url: '/booking/payment/' + id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let dpAmount = Number(response.dp_amount || 0);
                let totalPrice = Number(response.total_price || 0);

                $('#payment_dp_amount').val(dpAmount);
                $('#payment_booking_id').val(response.booking_code || '');

                let custName = (response.customer && response.customer.name) ? response.customer.name : '-';
                let carUnit = (response.car && response.car.unit) ? response.car.unit : '-';

                $('#payment_customer').val(custName);
                $('#payment_car').val(carUnit);
                $('#payment_destination').val(response.destination || '-');
                let datesText = response.dates || ((response.start_date || '-') + ' s/d ' + (response.end_date || '-'));
                $('#payment_dates').val(datesText);

                if (response.payment_status === 'DP') {
                    $('#payment_status_value').html(
                        '<span class="badge bg-warning text-dark me-1">DP</span> (<strong>' + formatRupiah(dpAmount) + '</strong>)'
                    );
                } else {
                    $('#payment_status_value').html(
                        '<span class="badge bg-danger">' + (response.payment_status || 'Belum Lunas') + '</span>'
                    );
                }

                // Baris harga sewa yang bisa diedit
                $('#otherPriceWrapper').html(`
                    <div class="row other-price-item mb-2 align-items-center">
                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <label class="small text-muted d-md-none mb-1">Keterangan</label>
                            <input type="text"
                                name="other_name[]"
                                class="form-control"
                                value="Sewa Mobil"
                                placeholder="Keterangan sewa">
                        </div>
                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <label class="small text-muted d-md-none mb-1">Harga Sewa (Bisa Diedit)</label>
                            <input type="text"
                                name="other_price[]"
                                class="form-control other-price fw-semibold"
                                value="${formatRupiah(totalPrice)}"
                                placeholder="Harga Sewa Mobil">
                        </div>
                        <div class="col-12 col-md-2 mb-3 mb-md-0">
                            <button type="button"
                                class="btn btn-outline-danger w-100 removeOtherPrice"
                                title="Hapus baris biaya ini">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `);

                updateRemainingPayment();
            },
            error: function(xhr) {
                console.error('Error fetching payment data:', xhr);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data booking pembayaran gagal diambil dari server'
                    });
                } else {
                    alert('Data booking pembayaran gagal diambil dari server');
                }
            }
        });
    };

    // Event listener dinamis untuk perubahan harga sewa / input biaya tambahan
    $(document).on('input', '.other-price', function() {
        let value = cleanNumber($(this).val());
        $(this).val(value ? formatRupiah(value) : '');
        updateRemainingPayment();
    });

    $(document).on('click', '.removeOtherPrice', function() {
        $(this).closest('.other-price-item').remove();
        updateRemainingPayment();
    });
</script>
@endpush
