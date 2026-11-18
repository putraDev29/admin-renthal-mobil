<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CashFlow;
use App\Models\Driver;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with(['driver', 'car'])->latest('transaction_date')->latest('id')->get();
        $drivers = Driver::orderBy('nama')->get();
        $cars = Car::orderBy('unit')->get();
        $totalSalary = Salary::sum('amount');
        $totalOtherExpense = \App\Models\Expense::sum('amount');
        $totalExpense = $totalSalary + $totalOtherExpense;

        return view('page.finance.salary.index', compact(
            'salaries', 'drivers', 'cars', 'totalSalary', 'totalOtherExpense', 'totalExpense'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data) {
            $salary = Salary::create($data);
            $this->syncCashFlow($salary);
        });

        return redirect()->route('finance.salary.index')->with('success', 'Pengeluaran gaji berhasil ditambahkan.');
    }

    public function edit(Salary $salary)
    {
        $drivers = Driver::orderBy('nama')->get();
        $cars = Car::orderBy('unit')->get();

        return view('page.finance.salary.edit', compact('salary', 'drivers', 'cars'));
    }

    public function update(Request $request, Salary $salary)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($salary, $data) {
            $salary->update($data);
            $this->syncCashFlow($salary->fresh());
        });

        return redirect()->route('finance.salary.index')->with('success', 'Pengeluaran gaji berhasil diperbarui.');
    }

    public function destroy(Salary $salary)
    {
        DB::transaction(function () use ($salary) {
            CashFlow::where('source_type', 'salary')->where('source_id', $salary->id)->delete();
            $salary->delete();
        });

        return redirect()->route('finance.salary.index')->with('success', 'Pengeluaran gaji berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'car_id' => 'nullable|exists:cars,id',
            'recipient_name' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'amount' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['amount'] = (int) preg_replace('/[^0-9]/', '', $validated['amount']);

        return $validated;
    }

    private function syncCashFlow(Salary $salary): void
    {
        CashFlow::updateOrCreate(
            ['source_type' => 'salary', 'source_id' => $salary->id],
            [
                'booking_id' => null,
                'car_id' => $salary->car_id,
                'type' => 'expense',
                'title' => 'Gaji - ' . $salary->recipient_name,
                'amount' => $salary->amount,
                'description' => $salary->description,
                'transaction_date' => $salary->transaction_date,
            ]
        );
    }
}
