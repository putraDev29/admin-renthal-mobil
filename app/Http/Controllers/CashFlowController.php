<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CashFlow;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CashFlowExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::orderBy('unit')->get();

        // Default date range: if provided, use it; otherwise default to current month
        // If current month has no data, default to October 2026 where data exists
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if (!$startDate || !$endDate) {
            $currentMonthCount = CashFlow::whereBetween('transaction_date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString()
            ])->count();

            if ($currentMonthCount > 0) {
                $startDate = now()->startOfMonth()->toDateString();
                $endDate = now()->endOfMonth()->toDateString();
            } else {
                // Fallback to latest transaction month
                $latestCf = CashFlow::orderBy('transaction_date', 'desc')->first();
                if ($latestCf) {
                    $dt = Carbon::parse($latestCf->transaction_date);
                    $startDate = $dt->copy()->startOfMonth()->toDateString();
                    $endDate = $dt->copy()->endOfMonth()->toDateString();
                } else {
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                }
            }
        }

        $activeTab = $request->tab ?? 'keseluruhan';
        $report = $this->getReportData($startDate, $endDate);

        return view('page.cashflow.index', compact('cars', 'report', 'startDate', 'endDate', 'activeTab'));
    }

    public function data(Request $request)
    {
        $query = CashFlow::with([
            'booking.car',
            'booking.customer',
            'car'
        ]);

        // Filter tanggal
        if (
            $request->filled('start_date') &&
            $request->filled('end_date')
        ) {
            $query->whereBetween(
                'transaction_date',
                [
                    $request->start_date,
                    $request->end_date
                ]
            );
        }

        // Filter mobil
        if ($request->filled('car_id')) {
            $query->where(function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->whereNotNull('booking_id')
                        ->whereHas('booking', function ($booking) use ($request) {
                            $booking->where(
                                'car_id',
                                $request->car_id
                            );
                        });
                })
                ->orWhere(function ($sub) use ($request) {
                    $sub->whereNull('booking_id')
                        ->where(
                            'car_id',
                            $request->car_id
                        );
                });
            });
        }

        // Total nominal sesuai filter
        $totalAmount = (clone $query)->sum('amount');

        $cashflows = $query
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $data = [];

        foreach ($cashflows as $index => $item) {
            $badge = $item->type == 'income'
                ? '<span class="badge bg-success rounded-pill px-3 py-2">Pemasukan</span>'
                : '<span class="badge bg-danger rounded-pill px-3 py-2">Pengeluaran</span>';

            if ($item->source_type) {
                $action = '<span class="badge bg-secondary">Dari Keuangan</span>';
            } else {
                $action = '
                <div class="d-flex gap-2">
                    <button class="btn btn-warning btn-sm btn-edit" data-id="' . $item->id . '">
                        <i class="bi bi-pencil-square" style="font-size:11px;"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="' . $item->id . '">
                        <i class="bi bi-trash" style="font-size:11px;"></i>
                    </button>
                </div>';
            }

            $data[] = [
                'DT_RowIndex' => $index + 1,
                'transaction_date' => date(
                    'd M Y',
                    strtotime($item->transaction_date)
                ),
                'car' => optional(
                    optional($item->booking)->car
                )->unit
                    ??
                    optional($item->car)->unit
                    ??
                    '-',
                'type_badge' => $badge,
                'title' => $item->title,
                'description' => $item->description ?? '-',
                'amount_format' => 'Rp ' .
                    number_format(
                        $item->amount,
                        0,
                        ',',
                        '.'
                    ),
                'action' => $action
            ];
        }

        return response()->json([
            'data' => $data,
            'total_amount' => $totalAmount
        ]);
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required',
            'type' => 'required',
            'title' => 'required',
            'amount' => 'required'
        ]);

        CashFlow::create([
            'transaction_date' => $request->transaction_date,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => (int) preg_replace('/[^0-9]/', '', $request->amount)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $cashflow = CashFlow::findOrFail($id);

        if ($cashflow->source_type) {
            return response()->json(['message' => 'Transaksi ini dikelola dari menu Keuangan.'], 422);
        }

        return response()->json($cashflow);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $cashflow = CashFlow::findOrFail($id);

        if ($cashflow->source_type) {
            return response()->json(['message' => 'Transaksi ini dikelola dari menu Keuangan.'], 422);
        }

        $cashflow->update([
            'transaction_date' => $request->transaction_date,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => (int) preg_replace('/[^0-9]/', '', $request->amount)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $cashflow = CashFlow::findOrFail($id);

        if ($cashflow->source_type) {
            return response()->json(['message' => 'Transaksi ini dikelola dari menu Keuangan.'], 422);
        }

        $cashflow->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // SUMMARY
    // =========================
    public function summary(Request $request)
    {
        $query = CashFlow::query();

        // Filter tanggal
        if (
            $request->filled('start_date') &&
            $request->filled('end_date')
        ) {
            $query->whereBetween(
                'transaction_date',
                [
                    $request->start_date,
                    $request->end_date
                ]
            );
        }

        // Filter mobil
        if ($request->filled('car_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('car_id', $request->car_id)
                    ->orWhereHas('booking', function ($booking) use ($request) {
                        $booking->where('car_id', $request->car_id);
                    });
            });
        }

        $income = (clone $query)
            ->where('type', 'income')
            ->sum('amount');

        $expense = (clone $query)
            ->where('type', 'expense')
            ->sum('amount');

        $profit = $income - $expense;

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit
        ]);
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'Laporan_CashFlow_AmiiTrans_' . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new CashFlowExport(
                $request->start_date,
                $request->end_date,
                $request->car_id
            ),
            $fileName
        );
    }

    // =========================================================================
    // REPORT DATA HELPER (Used by Tabs, PDF, Print View)
    // =========================================================================
    public function getReportData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 1. Prior balance (Saldo sebelum start_date)
        $priorIncome = CashFlow::where('transaction_date', '<', $startDate)
            ->where('type', 'income')
            ->sum('amount');
        $priorExpense = CashFlow::where('transaction_date', '<', $startDate)
            ->where('type', 'expense')
            ->sum('amount');
        $priorBalance = $priorIncome - $priorExpense;

        // 2. Total Keseluruhan
        $rawCashflows = CashFlow::with(['booking.customer', 'booking.car', 'car'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $runningSaldo = $priorBalance;
        $keseluruhanRows = [];
        $totalIncomePeriod = 0;
        $totalExpensePeriod = 0;

        $hasExplicitSaldoSebelumnya = false;
        foreach ($rawCashflows as $cf) {
            if (stripos($cf->title, 'Saldo Sebelumnya') !== false) {
                $hasExplicitSaldoSebelumnya = true;
                break;
            }
        }

        if (!$hasExplicitSaldoSebelumnya && $priorBalance != 0) {
            $keseluruhanRows[] = [
                'id' => null,
                'tanggal' => Carbon::parse($startDate)->isoFormat('dddd, D MMMM Y'),
                'raw_date' => $startDate,
                'keterangan' => 'Saldo Sebelumnya',
                'tgl_keluar' => '-',
                'tujuan' => '-',
                'pemasukan' => $priorBalance > 0 ? $priorBalance : 0,
                'pengeluaran' => $priorBalance < 0 ? abs($priorBalance) : 0,
                'saldo' => $priorBalance,
                'type' => $priorBalance >= 0 ? 'income' : 'expense',
            ];
            $totalIncomePeriod += ($priorBalance > 0 ? $priorBalance : 0);
        } elseif ($hasExplicitSaldoSebelumnya) {
            $runningSaldo = 0;
        }

        foreach ($rawCashflows as $cf) {
            $income = 0;
            $expense = 0;

            if ($cf->type === 'income') {
                $income = (float) $cf->amount;
                $runningSaldo += $income;
                $totalIncomePeriod += $income;
            } else {
                $expense = (float) $cf->amount;
                $runningSaldo -= $expense;
                $totalExpensePeriod += $expense;
            }

            $tglKeluar = '-';
            $tujuan = '-';

            if ($cf->booking) {
                $tglKeluar = $cf->booking->start_date ? Carbon::parse($cf->booking->start_date)->isoFormat('D MMMM Y') : '-';
                $tujuan = $cf->booking->destination ?? '-';
            }

            $cleanTitle = preg_replace('/^(Operasional|Perawatan|BBM|Tol \/ Parkir|Administrasi|Lainnya)\s*-\s*/i', '', $cf->title);

            $keseluruhanRows[] = [
                'id' => $cf->id,
                'tanggal' => Carbon::parse($cf->transaction_date)->isoFormat('dddd, D MMMM Y'),
                'raw_date' => $cf->transaction_date,
                'keterangan' => $cleanTitle,
                'tgl_keluar' => $tglKeluar,
                'tujuan' => $tujuan,
                'pemasukan' => $income,
                'pengeluaran' => $expense,
                'saldo' => $runningSaldo,
                'type' => $cf->type,
            ];
        }

        // 3. Garasi
        $garasiCashflows = CashFlow::with('expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'expense')
            ->where(function ($q) {
                $q->where('title', 'like', '%garasi%')
                  ->orWhere('description', 'like', '%garasi%')
                  ->orWhereHas('expense', function ($e) {
                      $e->where('category', 'Garasi');
                  });
            })
            ->orderBy('transaction_date', 'asc')
            ->get();

        $garasiRows = [];
        $totalGarasi = 0;
        foreach ($garasiCashflows as $g) {
            $totalGarasi += (float) $g->amount;
            $cleanKeterangan = preg_replace('/^(Operasional|Garasi|Lainnya)\s*-\s*/i', '', $g->title);
            $garasiRows[] = [
                'tanggal' => Carbon::parse($g->transaction_date)->isoFormat('D MMMM Y'),
                'kebutuhan' => $cleanKeterangan,
                'jumlah' => (float) $g->amount,
            ];
        }

        // 4. Per Unit
        $cars = Car::orderBy('unit')->get();
        $carsReport = [];

        foreach ($cars as $car) {
            // PEMASUKAN
            $carIncomes = CashFlow::with(['booking.customer'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->where('type', 'income')
                ->where('car_id', $car->id)
                ->orderBy('transaction_date', 'asc')
                ->get();

            $pemasukanRows = [];
            $totalPemasukanCar = 0;

            foreach ($carIncomes as $ci) {
                $totalPemasukanCar += (float) $ci->amount;
                $jadwal = '-';
                $order = '-';
                $tujuan = '-';

                if ($ci->booking) {
                    $jadwal = Carbon::parse($ci->booking->start_date)->format('Y-m-d');
                    $order = optional($ci->booking->customer)->name ?? '-';
                    $tujuan = $ci->booking->destination ?? '-';
                }

                $pemasukanRows[] = [
                    'jadwal' => $jadwal,
                    'order' => ucwords($order),
                    'tujuan' => $tujuan,
                    'tgl_setor' => $ci->transaction_date,
                    'setoran' => (float) $ci->amount,
                ];
            }

            // Also check uncollected bookings for this car in date range
            $carBookings = Booking::with('customer')
                ->where('car_id', $car->id)
                ->whereBetween('start_date', [$startDate, $endDate])
                ->whereNotIn('id', $carIncomes->pluck('booking_id')->filter())
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($carBookings as $cb) {
                $pemasukanRows[] = [
                    'jadwal' => $cb->start_date,
                    'order' => ucwords(optional($cb->customer)->name ?? '-'),
                    'tujuan' => $cb->destination ?? '-',
                    'tgl_setor' => '-',
                    'setoran' => 0,
                ];
            }

            // PENGELUARAN
            $carExpenses = CashFlow::whereBetween('transaction_date', [$startDate, $endDate])
                ->where('type', 'expense')
                ->where('car_id', $car->id)
                ->orderBy('transaction_date', 'asc')
                ->get();

            $pengeluaranRows = [];
            $totalPengeluaranCar = 0;

            foreach ($carExpenses as $ce) {
                $totalPengeluaranCar += (float) $ce->amount;
                $cleanKeterangan = preg_replace('/^(Operasional|Perawatan|BBM|Tol \/ Parkir|Administrasi|Lainnya)\s*-\s*/i', '', $ce->title);
                $pengeluaranRows[] = [
                    'tanggal' => $ce->transaction_date,
                    'keterangan' => $cleanKeterangan,
                    'pengeluaran' => (float) $ce->amount,
                ];
            }

            $carsReport[$car->unit] = [
                'car' => $car,
                'unit' => $car->unit,
                'name' => $car->name,
                'label' => $car->unit . ($car->unit == 'AM-01' || $car->unit == 'AM-04' ? ' ( Hiace )' : ($car->unit == 'AM-02' ? ' ( Dutro )' : ($car->unit == 'AM-03' ? ' ( Giga )' : ''))),
                'pemasukan' => $pemasukanRows,
                'pengeluaran' => $pengeluaranRows,
                'total_pemasukan' => $totalPemasukanCar,
                'total_pengeluaran' => $totalPengeluaranCar,
                'saldo_unit' => $totalPemasukanCar - $totalPengeluaranCar,
            ];
        }

        // 5. Lain-Lain
        $lainLainCashflows = CashFlow::with('expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'expense')
            ->whereNull('car_id')
            ->where('title', 'not like', '%garasi%')
            ->where('description', 'not like', '%garasi%')
            ->whereDoesntHave('expense', function ($e) {
                $e->where('category', 'Garasi');
            })
            ->orderBy('transaction_date', 'asc')
            ->get();

        $lainLainRows = [];
        $totalLainLain = 0;
        foreach ($lainLainCashflows as $l) {
            $totalLainLain += (float) $l->amount;
            $cleanKeterangan = preg_replace('/^(Operasional|Lainnya|Administrasi)\s*-\s*/i', '', $l->title);
            $lainLainRows[] = [
                'tanggal' => Carbon::parse($l->transaction_date)->isoFormat('D MMMM Y'),
                'keterangan' => $cleanKeterangan,
                'jumlah' => (float) $l->amount,
            ];
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'periode_label' => Carbon::parse($startDate)->isoFormat('D MMMM Y') . ' s/d ' . Carbon::parse($endDate)->isoFormat('D MMMM Y'),
            'prior_balance' => $priorBalance,
            'keseluruhan' => $keseluruhanRows,
            'total_income' => $totalIncomePeriod,
            'total_expense' => $totalExpensePeriod,
            'saldo_akhir' => $runningSaldo,
            'garasi' => $garasiRows,
            'total_garasi' => $totalGarasi,
            'cars_report' => $carsReport,
            'lain_lain' => $lainLainRows,
            'total_lain_lain' => $totalLainLain,
        ];
    }

    // =========================================================================
    // EXPORT PDF
    // =========================================================================
    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();
        $tab = $request->tab ?? 'all';

        $report = $this->getReportData($startDate, $endDate);

        $pdf = Pdf::loadView('page.cashflow.pdf', compact('report', 'tab'))
            ->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Keuangan_AmiiTrans_' . ($tab !== 'all' ? strtoupper($tab) . '_' : '') . date('Ymd_His') . '.pdf';

        if ($request->has('preview') || $request->preview == 1) {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    // =========================================================================
    // PRINT VIEW (Direct in-browser printable view)
    // =========================================================================
    public function printView(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();
        $tab = $request->tab ?? 'all';

        $report = $this->getReportData($startDate, $endDate);

        return view('page.cashflow.print', compact('report', 'tab'));
    }
}
