<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use App\Models\CashFlow;
use Carbon\Carbon;

class KalendarBookingController extends Controller
{
    public function index(Request $request)
    {
        // Auto update booking yang sudah lewat
        Booking::whereIn('status', ['Booking', 'DP'])
            ->whereDate('end_date', '<', today())
            ->update([
                'status' => 'Selesai'
            ]);

        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $currentDate = Carbon::create($year, $month, 1);

        $startMonth = $currentDate->copy()->startOfMonth()->toDateString();
        $endMonth = $currentDate->copy()->endOfMonth()->toDateString();

        $cars = Car::all();

        $bookings = Booking::with(['customer', 'car', 'driver'])
            ->where(function ($q) use ($startMonth, $endMonth) {
                $q->whereBetween('start_date', [$startMonth, $endMonth])
                  ->orWhereBetween('end_date', [$startMonth, $endMonth]);
            })
            ->get();

        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        // ============================================================
        // FINANCIAL DASHBOARD TAB DATA
        // ============================================================
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $prevStart = $startOfMonth->copy()->subMonth();
        $prevEnd = $endOfMonth->copy()->subMonth();

        // Income & Expense this month
        $incomeMonth = CashFlow::whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('type', 'income')
            ->sum('amount');

        $expenseMonth = CashFlow::whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('type', 'expense')
            ->sum('amount');

        $netProfitMonth = $incomeMonth - $expenseMonth;

        // Income & Expense last month (for growth trend)
        $incomePrevMonth = CashFlow::whereBetween('transaction_date', [$prevStart->toDateString(), $prevEnd->toDateString()])
            ->where('type', 'income')
            ->sum('amount');

        $expensePrevMonth = CashFlow::whereBetween('transaction_date', [$prevStart->toDateString(), $prevEnd->toDateString()])
            ->where('type', 'expense')
            ->sum('amount');

        // All-time totals
        $incomeAllTime = CashFlow::where('type', 'income')->sum('amount');
        $expenseAllTime = CashFlow::where('type', 'expense')->sum('amount');
        $netProfitAllTime = $incomeAllTime - $expenseAllTime;

        // Total Piutang (Pending / DP unpaid remainder)
        $unpaidBookings = Booking::with(['customer', 'car'])
            ->where('payment_status', '!=', 'Lunas')
            ->latest()
            ->get();

        $totalPiutang = $unpaidBookings->sum(function ($b) {
            $rem = (float)$b->total_price - (float)$b->dp_amount;
            return $rem > 0 ? $rem : 0;
        });

        // 6-Month Monthly Trend for Interactive Chart
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 5; $i >= 0; $i--) {
            $mDate = $currentDate->copy()->subMonths($i);
            $mStart = $mDate->copy()->startOfMonth()->toDateString();
            $mEnd = $mDate->copy()->endOfMonth()->toDateString();

            $chartLabels[] = $mDate->translatedFormat('M Y');
            $chartIncome[] = (float) CashFlow::whereBetween('transaction_date', [$mStart, $mEnd])->where('type', 'income')->sum('amount');
            $chartExpense[] = (float) CashFlow::whereBetween('transaction_date', [$mStart, $mEnd])->where('type', 'expense')->sum('amount');
        }

        // Recent cashflows (latest 8 transactions)
        $recentCashflows = CashFlow::with(['booking.customer', 'booking.car', 'car'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(8)
            ->get();

        // Recent Pending Bookings needing payment (top 5)
        $pendingPayments = $unpaidBookings->take(6);

        // Expense breakdown
        $salaryExpense = CashFlow::whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('type', 'expense')
            ->where('source_type', 'salary')
            ->sum('amount');

        $otherExpense = CashFlow::whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('type', 'expense')
            ->where('source_type', 'expense')
            ->sum('amount');

        $directExpense = $expenseMonth - ($salaryExpense + $otherExpense);
        if ($directExpense < 0) {
            $directExpense = 0;
        }

        return view('page.kalender-booking.kalender-booking', compact(
            'cars',
            'bookings',
            'currentDate',
            'prevMonth',
            'nextMonth',
            'incomeMonth',
            'expenseMonth',
            'netProfitMonth',
            'incomePrevMonth',
            'expensePrevMonth',
            'incomeAllTime',
            'expenseAllTime',
            'netProfitAllTime',
            'totalPiutang',
            'chartLabels',
            'chartIncome',
            'chartExpense',
            'recentCashflows',
            'pendingPayments',
            'salaryExpense',
            'otherExpense',
            'directExpense'
        ));
    }

    public function showDetail($id)
    {
        return Booking::with('customer', 'car', 'driver')->findOrFail($id);
    }
}
