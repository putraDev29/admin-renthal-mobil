<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CashFlow;
use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('car')->latest('transaction_date')->latest('id')->get();
        $cars = Car::orderBy('unit')->get();
        $totalSalary = Salary::sum('amount');
        $totalOtherExpense = Expense::sum('amount');
        $totalExpense = $totalSalary + $totalOtherExpense;

        return view('page.finance.expense.index', compact(
            'expenses', 'cars', 'totalSalary', 'totalOtherExpense', 'totalExpense'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data) {
            $expense = Expense::create($data);
            $this->syncCashFlow($expense);
        });

        return redirect()->route('finance.expense.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Expense $expense)
    {
        $cars = Car::orderBy('unit')->get();
        return view('page.finance.expense.edit', compact('expense', 'cars'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($expense, $data) {
            $expense->update($data);
            $this->syncCashFlow($expense->fresh());
        });

        return redirect()->route('finance.expense.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            CashFlow::where('source_type', 'expense')->where('source_id', $expense->id)->delete();
            $expense->delete();
        });

        return redirect()->route('finance.expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'car_id' => 'nullable|exists:cars,id',
            'transaction_date' => 'required|date',
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'amount' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['amount'] = (int) preg_replace('/[^0-9]/', '', $validated['amount']);
        return $validated;
    }

    private function syncCashFlow(Expense $expense): void
    {
        CashFlow::updateOrCreate(
            ['source_type' => 'expense', 'source_id' => $expense->id],
            [
                'booking_id' => null,
                'car_id' => $expense->car_id,
                'type' => 'expense',
                'title' => $expense->category . ' - ' . $expense->title,
                'amount' => $expense->amount,
                'description' => $expense->description,
                'transaction_date' => $expense->transaction_date,
            ]
        );
    }
}
