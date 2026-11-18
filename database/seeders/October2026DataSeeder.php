<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingPrice;
use App\Models\Car;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class October2026DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // 1. Resolve or Create Customers
            $custFirdaus = Customer::firstOrCreate(
                ['name' => 'firdaus'],
                ['gender' => 'Laki-laki', 'phone' => '082337996773', 'address' => 'sogaan']
            );
            $custHid = Customer::firstOrCreate(
                ['name' => 'hid'],
                ['gender' => 'Laki-laki', 'phone' => '085232097092', 'address' => 'pandean']
            );
            $custMunif = Customer::firstOrCreate(
                ['name' => 'munif'],
                ['gender' => 'Laki-laki', 'phone' => '-', 'address' => '-']
            );
            $custRuhin = Customer::firstOrCreate(
                ['name' => 'ruhin'],
                ['gender' => 'Laki-laki', 'phone' => '-', 'address' => '-']
            );
            $custAnis = Customer::firstOrCreate(
                ['name' => 'anis'],
                ['gender' => 'Perempuan', 'phone' => '-', 'address' => '-']
            );

            // 2. Resolve Drivers & Cars
            $driverFirdaus = Driver::where('nama', 'Firdaus')->first();
            $driverHid     = Driver::where('nama', 'HID')->first();

            $car1 = Car::where('unit', 'AM-01')->first();
            $car2 = Car::where('unit', 'AM-02')->first();
            $car3 = Car::where('unit', 'AM-03')->first();
            $car4 = Car::where('unit', 'AM-04')->first();

            // 3. Clean up existing October & November Bookings
            $octBookingIds = Booking::where(function ($q) {
                $q->whereBetween('start_date', ['2026-10-01', '2026-11-30'])
                  ->orWhereBetween('end_date', ['2026-10-01', '2026-11-30']);
            })->pluck('id');

            BookingPrice::whereIn('booking_id', $octBookingIds)->delete();
            CashFlow::whereIn('booking_id', $octBookingIds)->delete();
            Booking::whereIn('id', $octBookingIds)->delete();

            // Clean up existing October Expenses and their synced Cashflows
            $octExpenseIds = Expense::whereBetween('transaction_date', ['2026-10-01', '2026-10-31'])->pluck('id');
            CashFlow::where('source_type', 'expense')->whereIn('source_id', $octExpenseIds)->delete();
            Expense::whereIn('id', $octExpenseIds)->delete();

            // Clean up any remaining October CashFlows
            CashFlow::whereBetween('transaction_date', ['2026-10-01', '2026-10-31'])->delete();

            // 4. Update Paciran Booking (2026-09-27)
            $paciranBooking = Booking::where('start_date', '2026-09-27')
                ->where('car_id', $car2->id)
                ->where('destination', 'like', '%Paciran%')
                ->first();

            if ($paciranBooking) {
                $paciranBooking->update([
                    'customer_id'    => $custFirdaus->id,
                    'driver_id'      => $driverFirdaus->id,
                    'total_price'    => 350000,
                    'payment_status' => 'Lunas',
                    'status'         => 'Selesai'
                ]);

                BookingPrice::updateOrCreate(
                    ['booking_id' => $paciranBooking->id],
                    ['name' => 'Sewa AM-02 - Paciran', 'price' => 350000, 'qty' => 1, 'subtotal' => 350000]
                );
            }

            // 5. Create All Bookings for October & November from Excel
            $currentBkNum = 74;
            $getNextBkCode = function () use (&$currentBkNum) {
                $currentBkNum++;
                return 'BK' . str_pad($currentBkNum, 4, '0', STR_PAD_LEFT);
            };

            $bookingsData = [
                // AM-02: 2026-10-01 Sidoarjo
                'am02_sidoarjo' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-01', 'end_date' => '2026-10-01', 'destination' => 'Sidoarjo',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                // AM-03: 2026-10-02 Solo
                'am03_solo' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-02', 'end_date' => '2026-10-02', 'destination' => 'Solo',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-03 Jombang
                'am02_jombang' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-03', 'end_date' => '2026-10-03', 'destination' => 'Jombang',
                    'total_price' => 400000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-04 Gresik - Bangkalan
                'am01_gresik' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-04', 'end_date' => '2026-10-04', 'destination' => 'Gresik - Bangkalan',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-04 Besuki
                'am02_besuki' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-04', 'end_date' => '2026-10-04', 'destination' => 'Besuki',
                    'total_price' => 200000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-08 Kediri
                'am01_kediri' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-08', 'end_date' => '2026-10-08', 'destination' => 'Kediri',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-08 Madura
                'am03_madura_1' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-08', 'end_date' => '2026-10-08', 'destination' => 'Madura',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-10 Probolinggo
                'am03_probolinggo_1' => [
                    'car_id' => $car3->id, 'customer_id' => $custHid->id, 'driver_id' => $driverHid->id,
                    'start_date' => '2026-10-10', 'end_date' => '2026-10-10', 'destination' => 'Probolinggo',
                    'total_price' => 300000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-11 Malang
                'am01_malang_1' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-11', 'end_date' => '2026-10-11', 'destination' => 'Malang',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-13 Jember
                'am01_jember' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-13', 'end_date' => '2026-10-13', 'destination' => 'Jember',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-15 Jember
                'am03_jember_1' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-15', 'end_date' => '2026-10-15', 'destination' => 'Jember',
                    'total_price' => 500000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-16 Bandara
                'am01_bandara' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-16', 'end_date' => '2026-10-16', 'destination' => 'Bandara',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-17 Malang
                'am01_malang_2' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-17', 'end_date' => '2026-10-17', 'destination' => 'Malang',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-17 Tuban
                'am02_tuban' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-17', 'end_date' => '2026-10-17', 'destination' => 'Tuban',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                // AM-03: 2026-10-17 Jember
                'am03_jember_2' => [
                    'car_id' => $car3->id, 'customer_id' => $custHid->id, 'driver_id' => $driverHid->id,
                    'start_date' => '2026-10-17', 'end_date' => '2026-10-17', 'destination' => 'Jember',
                    'total_price' => 500000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-19 s/d 2026-10-20 Malang
                'am01_malang_3' => [
                    'car_id' => $car1->id, 'customer_id' => $custMunif->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-19', 'end_date' => '2026-10-20', 'destination' => 'Malang',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-20 Probolinggo
                'am03_probolinggo_2' => [
                    'car_id' => $car3->id, 'customer_id' => $custHid->id, 'driver_id' => $driverHid->id,
                    'start_date' => '2026-10-20', 'end_date' => '2026-10-20', 'destination' => 'Probolinggo',
                    'total_price' => 300000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-21 Klaseman
                'am03_klaseman' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-21', 'end_date' => '2026-10-21', 'destination' => 'Klaseman',
                    'total_price' => 200000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-24 Surabaya
                'am01_surabaya' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-24', 'end_date' => '2026-10-24', 'destination' => 'Surabaya',
                    'total_price' => 550000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-24 Situbondo
                'am03_situbondo_1' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-24', 'end_date' => '2026-10-24', 'destination' => 'Situbondo',
                    'total_price' => 400000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-25 Unuja
                'am01_unuja' => [
                    'car_id' => $car1->id, 'customer_id' => $custRuhin->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-25', 'end_date' => '2026-10-25', 'destination' => 'Unuja',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                // AM-02: 2026-10-25 Kraksaan
                'am02_kraksaan' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-25', 'end_date' => '2026-10-25', 'destination' => 'Kraksaan',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                // AM-03: 2026-10-25 Pamekasan
                'am03_pamekasan' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-25', 'end_date' => '2026-10-25', 'destination' => 'Pamekasan',
                    'total_price' => 600000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-26 Bondowoso
                'am03_bondowoso' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-26', 'end_date' => '2026-10-26', 'destination' => 'Bondowoso',
                    'total_price' => 400000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-28 Lamaran
                'am02_lamaran' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-28', 'end_date' => '2026-10-28', 'destination' => 'Lamaran',
                    'total_price' => 150000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-28 Lamaran
                'am03_lamaran' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-28', 'end_date' => '2026-10-28', 'destination' => 'Lamaran',
                    'total_price' => 150000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-29 Bentar
                'am02_bentar' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-29', 'end_date' => '2026-10-29', 'destination' => 'Bentar',
                    'total_price' => 200000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-29 Bentar
                'am03_bentar' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-29', 'end_date' => '2026-10-29', 'destination' => 'Bentar',
                    'total_price' => 200000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-01: 2026-10-30 Madura
                'am01_madura' => [
                    'car_id' => $car1->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-30', 'end_date' => '2026-10-30', 'destination' => 'Madura',
                    'total_price' => 1000000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-30 Krucil
                'am02_krucil' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-30', 'end_date' => '2026-10-30', 'destination' => 'Krucil',
                    'total_price' => 300000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-02: 2026-10-31 Wali 5
                'am02_wali5' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-31', 'end_date' => '2026-10-31', 'destination' => 'Wali 5',
                    'total_price' => 500000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],
                // AM-03: 2026-10-31 Madura
                'am03_madura_2' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-10-31', 'end_date' => '2026-10-31', 'destination' => 'Madura',
                    'total_price' => 700000, 'status' => 'Selesai', 'payment_status' => 'Lunas'
                ],

                // November Bookings from sheets:
                'am02_situbondo_nov' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-02', 'end_date' => '2026-11-02', 'destination' => 'Situbondo',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                'am02_lembah_nov' => [
                    'car_id' => $car2->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-08', 'end_date' => '2026-11-08', 'destination' => 'Lembah Pendawa',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                'am03_lembah_nov' => [
                    'car_id' => $car3->id, 'customer_id' => $custFirdaus->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-08', 'end_date' => '2026-11-08', 'destination' => 'Lembah Pendawa',
                    'total_price' => 0, 'status' => 'Selesai', 'payment_status' => 'Belum Bayar'
                ],
                'am01_acara_14' => [
                    'car_id' => $car1->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-14', 'end_date' => '2026-11-14', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
                'am02_acara_14' => [
                    'car_id' => $car2->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-14', 'end_date' => '2026-11-14', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
                'am03_acara_14' => [
                    'car_id' => $car3->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-14', 'end_date' => '2026-11-14', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
                'am01_acara_16' => [
                    'car_id' => $car1->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-16', 'end_date' => '2026-11-16', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
                'am02_acara_16' => [
                    'car_id' => $car2->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-16', 'end_date' => '2026-11-16', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
                'am03_acara_16' => [
                    'car_id' => $car3->id, 'customer_id' => $custAnis->id, 'driver_id' => $driverFirdaus->id,
                    'start_date' => '2026-11-16', 'end_date' => '2026-11-16', 'destination' => 'Acara Sendiri',
                    'total_price' => 0, 'status' => 'Booking', 'payment_status' => 'Belum Bayar'
                ],
            ];

            $createdBookings = [];
            foreach ($bookingsData as $key => $bData) {
                $booking = Booking::create(array_merge($bData, [
                    'booking_code' => $getNextBkCode(),
                    'dp_amount'    => 0,
                    'pickup'       => '-',
                    'open_status'  => 1
                ]));

                if ($booking->total_price > 0) {
                    BookingPrice::create([
                        'booking_id' => $booking->id,
                        'name'       => 'Sewa ' . $booking->car->unit . ' (' . $booking->destination . ')',
                        'price'      => $booking->total_price,
                        'qty'        => 1,
                        'subtotal'   => $booking->total_price
                    ]);
                }

                $createdBookings[$key] = $booking;
            }

            // 6. CashFlow: Incomes
            // Saldo Sebelumnya
            CashFlow::create([
                'booking_id'       => null,
                'car_id'           => null,
                'source_type'      => null,
                'source_id'        => null,
                'type'             => 'income',
                'title'            => 'Saldo Sebelumnya',
                'amount'           => 1437500,
                'description'      => 'Saldo Kas Sebelumnya',
                'transaction_date' => '2026-10-06'
            ]);

            // 29 Setorans from Excel Table 1
            $setoranList = [
                ['date' => '2026-10-06', 'booking' => $paciranBooking, 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Paciran', 'amount' => 350000],
                ['date' => '2026-10-06', 'booking' => $createdBookings['am01_gresik'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Gresik', 'amount' => 600000],
                ['date' => '2026-10-06', 'booking' => $createdBookings['am02_jombang'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Jombang', 'amount' => 400000],
                ['date' => '2026-10-06', 'booking' => $createdBookings['am02_besuki'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Besuki', 'amount' => 200000],
                ['date' => '2026-10-06', 'booking' => $createdBookings['am03_solo'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Solo', 'amount' => 600000],

                ['date' => '2026-10-10', 'booking' => $createdBookings['am03_madura_1'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Madura', 'amount' => 600000],
                ['date' => '2026-10-10', 'booking' => $createdBookings['am03_probolinggo_1'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Probolinggo', 'amount' => 300000],
                ['date' => '2026-10-10', 'booking' => $createdBookings['am01_kediri'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Kediri', 'amount' => 600000],

                ['date' => '2026-10-13', 'booking' => $createdBookings['am01_malang_1'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Malang', 'amount' => 600000],

                ['date' => '2026-10-15', 'booking' => $createdBookings['am01_jember'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Jember', 'amount' => 600000],

                ['date' => '2026-10-18', 'booking' => $createdBookings['am01_bandara'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Bandara', 'amount' => 600000],
                ['date' => '2026-10-18', 'booking' => $createdBookings['am01_malang_2'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Malang', 'amount' => 600000],

                ['date' => '2026-10-20', 'booking' => $createdBookings['am03_jember_1'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Jember', 'amount' => 500000],
                ['date' => '2026-10-20', 'booking' => $createdBookings['am03_jember_2'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Jember', 'amount' => 500000],

                ['date' => '2026-10-21', 'booking' => $createdBookings['am03_probolinggo_2'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Probolinggo', 'amount' => 300000],
                ['date' => '2026-10-21', 'booking' => $createdBookings['am01_malang_3'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Malang', 'amount' => 600000],

                ['date' => '2026-10-24', 'booking' => $createdBookings['am03_klaseman'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Klaseman', 'amount' => 200000],
                ['date' => '2026-10-24', 'booking' => $createdBookings['am01_surabaya'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Surabaya', 'amount' => 550000],

                ['date' => '2026-10-26', 'booking' => $createdBookings['am03_situbondo_1'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Situbondo', 'amount' => 400000],
                ['date' => '2026-10-26', 'booking' => $createdBookings['am03_pamekasan'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Pamekasan', 'amount' => 600000],

                ['date' => '2026-10-30', 'booking' => $createdBookings['am02_lamaran'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Lamaran', 'amount' => 150000],
                ['date' => '2026-10-30', 'booking' => $createdBookings['am02_bentar'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Bentar', 'amount' => 200000],
                ['date' => '2026-10-30', 'booking' => $createdBookings['am03_bondowoso'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Bondowoso', 'amount' => 400000],
                ['date' => '2026-10-30', 'booking' => $createdBookings['am03_lamaran'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Lamaran', 'amount' => 150000],
                ['date' => '2026-10-30', 'booking' => $createdBookings['am03_bentar'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Bentar', 'amount' => 200000],

                ['date' => '2026-10-31', 'booking' => $createdBookings['am01_madura'], 'car' => $car1, 'title' => 'Setoran AM 01', 'desc' => 'Madura', 'amount' => 1000000],
                ['date' => '2026-10-31', 'booking' => $createdBookings['am02_krucil'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Krucil', 'amount' => 300000],
                ['date' => '2026-10-31', 'booking' => $createdBookings['am02_wali5'], 'car' => $car2, 'title' => 'Setoran AM 02', 'desc' => 'Wali 5', 'amount' => 500000],
                ['date' => '2026-10-31', 'booking' => $createdBookings['am03_madura_2'], 'car' => $car3, 'title' => 'Setoran AM 03', 'desc' => 'Madura', 'amount' => 700000],
            ];

            foreach ($setoranList as $s) {
                CashFlow::create([
                    'booking_id'       => $s['booking']->id,
                    'car_id'           => $s['car']->id,
                    'source_type'      => null,
                    'source_id'        => null,
                    'type'             => 'income',
                    'title'            => $s['title'],
                    'amount'           => $s['amount'],
                    'description'      => $s['desc'],
                    'transaction_date' => $s['date']
                ]);
            }

            // 7. Expenses (`expenses` and synced `cash_flows`)
            $addExpense = function ($date, $carId, $cat, $title, $amount, $desc = '') {
                $exp = Expense::create([
                    'transaction_date' => $date,
                    'car_id'           => $carId,
                    'category'         => $cat,
                    'title'            => $title,
                    'amount'           => $amount,
                    'description'      => $desc
                ]);

                CashFlow::create([
                    'booking_id'       => null,
                    'car_id'           => $carId,
                    'source_type'      => 'expense',
                    'source_id'        => $exp->id,
                    'type'             => 'expense',
                    'title'            => $cat . ' - ' . $title,
                    'amount'           => $amount,
                    'description'      => $desc,
                    'transaction_date' => $date
                ]);

                return $exp;
            };

            // Fleet Expenses
            $addExpense('2026-10-06', $car2->id, 'Operasional', 'Filter Solar Dutro', 120000, 'Nota 27-09-2026');
            $addExpense('2026-10-06', $car2->id, 'Operasional', 'Selang Turbo Dutro', 170000, 'Nota 27-09-2026');
            $addExpense('2026-10-08', $car3->id, 'Operasional', 'Kekurangan AC Giga', 700000);
            $addExpense('2026-10-10', $car2->id, 'Operasional', 'Handle Dutro', 109000);
            $addExpense('2026-10-16', $car2->id, 'Operasional', 'Perbaikan Audio Dutro', 200000);
            $addExpense('2026-10-16', $car2->id, 'Operasional', 'Ngasik Orang Bank', 200000);
            $addExpense('2026-10-16', $car3->id, 'Lainnya', 'Ban Giga', 3075000);
            $addExpense('2026-10-17', $car3->id, 'BBM', 'Solar Giga', 100000);
            $addExpense('2026-10-18', $car3->id, 'Lainnya', 'Freon AC Giga', 300000);
            $addExpense('2026-10-20', $car3->id, 'BBM', 'Solar Giga', 100000);
            $addExpense('2026-10-20', $car3->id, 'Operasional', 'Rokok dan makan', 50000);
            $addExpense('2026-10-21', $car1->id, 'Administrasi', 'GPS 01', 22000);
            $addExpense('2026-10-21', $car2->id, 'Administrasi', 'GPS 02', 22000);
            $addExpense('2026-10-21', $car3->id, 'Administrasi', 'GPS 03', 22000);
            $addExpense('2026-10-21', $car4->id, 'Administrasi', 'GPS 04', 22000);
            $addExpense('2026-10-31', $car2->id, 'Operasional', 'Cover Stir Dutro', 35000);
            $addExpense('2026-10-31', $car2->id, 'Operasional', 'Peredam mesin Dutro', 130000);
            $addExpense('2026-10-31', $car2->id, 'Operasional', '2 Liter Oli Meditran Dutro', 130000);
            $addExpense('2026-10-03', $car2->id, 'Operasional', 'Bensin Rokok Dutro', 60000);

            // Lain-Lain Expenses (Section Lain-Lain - Total Rp 3.990.000)
            $addExpense('2026-10-06', null, 'Lainnya', 'Wifi 3 Bulan', 330000);
            $addExpense('2026-10-14', null, 'Lainnya', 'Anis', 34000);
            $addExpense('2026-10-17', null, 'Lainnya', 'Listrik Pak Mardi', 59000);
            $addExpense('2026-10-20', null, 'Lainnya', 'Paketan Anis', 58000);
            $addExpense('2026-10-20', null, 'Lainnya', 'Nambah Nun Hafidz', 500000);
            $addExpense('2026-10-20', null, 'Lainnya', 'Nasi Sate 7 x 25.000', 175000);
            $addExpense('2026-10-20', null, 'Lainnya', 'Es Blewah 7 x 25.000', 35000);
            $addExpense('2026-10-20', null, 'Lainnya', 'Pak Mardi', 50000);
            $addExpense('2026-10-21', null, 'Lainnya', 'Wiwik', 500000);
            $addExpense('2026-10-21', null, 'Lainnya', 'Tf Moh Yudianto', 600000);
            $addExpense('2026-10-27', null, 'Lainnya', 'Mbak Mutik', 1000000);
            $addExpense('2026-10-29', null, 'Lainnya', 'Anis', 300000);
            $addExpense('2026-10-29', null, 'Lainnya', 'Undangan Aqiqoh+Walimah', 125000);
            $addExpense('2026-10-29', null, 'Lainnya', 'Rokok Surya 8 x 28', 224000);
        });
    }
}
