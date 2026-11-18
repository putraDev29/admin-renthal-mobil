<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KalendarBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login-process', [AuthController::class, 'loginProcess'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'nocache'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Shared Routes: Super Admin & Admin Keuangan
    | (Dashboard, Booking Input & Payment, Cash Flow, Keuangan)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin,admin_keuangan'])->group(function () {

        // Dashboard (Kalender Booking & Dashboard Keuangan Tabs)
        Route::get('/', [KalendarBookingController::class, 'index'])
            ->name('kalendarBooking');

        Route::get('/kalendar-booking/show/{id}', [KalendarBookingController::class, 'showDetail']);

        // Quick Autocomplete & Search for Booking Form
        Route::get('/customers/search', [BookingController::class, 'search'])
            ->name('customers.search');

        Route::get('/drivers/search', [BookingController::class, 'searchDriver']);

        Route::get('/cars/search', [BookingController::class, 'searchCar']);

        // Quick Customer Store from Booking Form
        Route::post('/customer/store', [CustomerController::class, 'store'])
            ->name('customer.store');

        // Bookings (Input, List, Payment, Invoice)
        Route::get('/bookings', [BookingController::class, 'index'])
            ->name('bookings');

        Route::get('/bookings/data', [BookingController::class, 'data'])
            ->name('bookingsData');

        Route::post('/booking/storeBooking', [BookingController::class, 'storeBooking'])
            ->name('booking.store');

        Route::put('/booking/{id}', [BookingController::class, 'updateBooking'])
            ->name('booking.update');

        Route::get('/booking/{id}', [BookingController::class, 'show'])
            ->name('booking.show');

        Route::get('/booking/payment/{id}', [BookingController::class, 'paymentShow'])
            ->name('booking.payment.show');

        Route::post('/booking/payment/store', [BookingController::class, 'paymentBooking'])
            ->name('booking.payment.store');

        Route::get('/booking/invoice/{id}', [BookingController::class, 'invoice'])
            ->name('booking.invoice');

        Route::get('/booking/detail/{id}', [BookingController::class, 'detail'])
            ->name('booking.detail');

        // Cash Flow
        Route::get('/cashFlow', [CashFlowController::class, 'index'])
            ->name('cashflow.index');

        Route::get('/data', [CashFlowController::class, 'data'])
            ->name('cashflow.data');

        Route::get('/summary', [CashFlowController::class, 'summary'])
            ->name('cashflow.summary');

        Route::post('/cashflow/store', [CashFlowController::class, 'store'])
            ->name('cashflow.store');

        Route::get('/cashflow/edit/{id}', [CashFlowController::class, 'edit'])
            ->name('cashflow.edit');

        Route::put('/cashflow/update/{id}', [CashFlowController::class, 'update'])
            ->name('cashflow.update');

        Route::delete('/cashflow/delete/{id}', [CashFlowController::class, 'destroy'])
            ->name('cashflow.delete');

        Route::get('/cashflow/export-excel', [CashFlowController::class, 'exportExcel'])
            ->name('cashflow.export.excel');

        Route::get('/cashflow/export-pdf', [CashFlowController::class, 'exportPdf'])
            ->name('cashflow.export.pdf');

        Route::get('/cashflow/print', [CashFlowController::class, 'printView'])
            ->name('cashflow.print');

        // Keuangan (Gaji & Pengeluaran)
        Route::get('/keuangan/gaji', [SalaryController::class, 'index'])->name('finance.salary.index');
        Route::post('/keuangan/gaji', [SalaryController::class, 'store'])->name('finance.salary.store');
        Route::get('/keuangan/gaji/{salary}/edit', [SalaryController::class, 'edit'])->name('finance.salary.edit');
        Route::put('/keuangan/gaji/{salary}', [SalaryController::class, 'update'])->name('finance.salary.update');
        Route::delete('/keuangan/gaji/{salary}', [SalaryController::class, 'destroy'])->name('finance.salary.destroy');

        Route::get('/keuangan/pengeluaran', [ExpenseController::class, 'index'])->name('finance.expense.index');
        Route::post('/keuangan/pengeluaran', [ExpenseController::class, 'store'])->name('finance.expense.store');
        Route::get('/keuangan/pengeluaran/{expense}/edit', [ExpenseController::class, 'edit'])->name('finance.expense.edit');
        Route::put('/keuangan/pengeluaran/{expense}', [ExpenseController::class, 'update'])->name('finance.expense.update');
        Route::delete('/keuangan/pengeluaran/{expense}', [ExpenseController::class, 'destroy'])->name('finance.expense.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Only Routes
    | (Delete Booking, Master Customer, Master Car, Master Driver, Maintenance, Users)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin'])->group(function () {

        // Delete Booking
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])
            ->name('booking.destroy');

        // Master Customer Management
        Route::get('/customer', [CustomerController::class, 'index'])
            ->name('customer.index');

        Route::get('/customer-data', [CustomerController::class, 'data'])
            ->name('customer.data');

        Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])
            ->name('customer.edit');

        Route::put('/customer/update/{id}', [CustomerController::class, 'update'])
            ->name('customer.update');

        Route::delete('/customer/delete/{id}', [CustomerController::class, 'destroy'])
            ->name('customer.delete');

        // Master Car Management
        Route::get('/car', [CarController::class, 'index'])
            ->name('car.index');

        Route::get('/car-data', [CarController::class, 'data'])
            ->name('car.data');

        Route::post('/car/store', [CarController::class, 'store'])
            ->name('car.store');

        Route::get('/car/edit/{id}', [CarController::class, 'edit'])
            ->name('car.edit');

        Route::put('/car/update/{id}', [CarController::class, 'update'])
            ->name('car.update');

        Route::delete('/car/delete/{id}', [CarController::class, 'destroy'])
            ->name('car.delete');

        // Master Driver Management
        Route::get('/driver', [DriverController::class, 'index'])
            ->name('driver.index');

        Route::get('/driver-data', [DriverController::class, 'data'])
            ->name('driver.data');

        Route::post('/driver/store', [DriverController::class, 'store'])
            ->name('driver.store');

        Route::get('/driver/edit/{id}', [DriverController::class, 'edit'])
            ->name('driver.edit');

        Route::put('/driver/update/{id}', [DriverController::class, 'update'])
            ->name('driver.update');

        Route::delete('/driver/delete/{id}', [DriverController::class, 'destroy'])
            ->name('driver.delete');

        // Vehicle Maintenance
        Route::get('/maintenance/{id}', [MaintenanceController::class, 'index'])
            ->name('maintenance.index');

        Route::get('/maintenance-history/{id}', [MaintenanceController::class, 'history'])
            ->name('maintenance.history');

        Route::post('/maintenance/store', [MaintenanceController::class, 'store'])
            ->name('maintenance.store');

        Route::get('/maintenance-last/{id}', [MaintenanceController::class, 'lastMaintenance'])
            ->name('maintenance.last');

        // User & Role Management
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{id}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('users.destroy');
    });
});
