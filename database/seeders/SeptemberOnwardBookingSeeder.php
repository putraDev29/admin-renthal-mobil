<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\BookingPrice;
use App\Models\CashFlow;

class SeptemberOnwardBookingSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // 1. Temporarily detach cash_flows.booking_id so cascade delete does not remove financial records
            DB::statement('UPDATE cash_flows SET booking_id = NULL WHERE booking_id IN (SELECT id FROM bookings WHERE start_date >= "2026-09-01")');

            // 2. Delete existing booking_prices for bookings >= 2026-09-01
            DB::statement('DELETE FROM booking_prices WHERE booking_id IN (SELECT id FROM bookings WHERE start_date >= "2026-09-01")');

            // 3. Delete existing bookings >= 2026-09-01
            DB::table('bookings')->where('start_date', '>=', '2026-09-01')->delete();

            // Find current highest booking code number
            $lastBooking = DB::table('bookings')
                ->where('start_date', '<', '2026-09-01')
                ->orderBy('id', 'desc')
                ->first();

            $nextCodeNumber = 52; // default after BK0051
            if ($lastBooking && preg_match('/BK(\d+)/', $lastBooking->booking_code, $matches)) {
                $nextCodeNumber = max($nextCodeNumber, intval($matches[1]) + 1);
            }

            // Customer mapping:
            // 1: iis, 6: firdaus, 8: owner, 15: Customer, 17: hid, 21: munif, 22: ruhin, 23: anis
            // Driver mapping:
            // 1: Firdaus, 3: HID

            $bookingsData = [
                // =========================================================================
                // SEPTEMBER 2026
                // =========================================================================
                // AM-04
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-05',
                    'end_date' => '2026-09-05',
                    'destination' => 'Tanggul',
                    'total_price' => 400000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 4,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-09-07',
                    'end_date' => '2026-09-07',
                    'destination' => 'Surabaya / Bandara',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-10',
                    'end_date' => '2026-09-10',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-12',
                    'end_date' => '2026-09-12',
                    'destination' => 'Madura',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-17',
                    'end_date' => '2026-09-17',
                    'destination' => 'Bandara',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-19',
                    'end_date' => '2026-09-19',
                    'destination' => 'Wali 8',
                    'total_price' => 1200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-04
                [
                    'car_id' => 4,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-09-19',
                    'end_date' => '2026-09-19',
                    'destination' => 'Wali 8',
                    'total_price' => 1200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-21',
                    'end_date' => '2026-09-21',
                    'destination' => 'Situbondo',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-23',
                    'end_date' => '2026-09-23',
                    'destination' => 'Tiris',
                    'total_price' => 200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-23',
                    'end_date' => '2026-09-23',
                    'destination' => 'Besuki',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-04
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-23',
                    'end_date' => '2026-09-23',
                    'destination' => 'Madura',
                    'total_price' => 1100000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-24',
                    'end_date' => '2026-09-24',
                    'destination' => 'Madura',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-25',
                    'end_date' => '2026-09-25',
                    'destination' => 'Madura',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-25',
                    'end_date' => '2026-09-25',
                    'destination' => 'Ziaroh Pamekasan',
                    'total_price' => 900000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-27',
                    'end_date' => '2026-09-27',
                    'destination' => 'Paciran',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-27',
                    'end_date' => '2026-09-27',
                    'destination' => 'Probolinggo',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-04
                [
                    'car_id' => 4,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-27',
                    'end_date' => '2026-09-27',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-28',
                    'end_date' => '2026-09-28',
                    'destination' => 'Lirboyo',
                    'total_price' => 800000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-09-30',
                    'end_date' => '2026-09-30',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],

                // =========================================================================
                // OCTOBER 2026
                // =========================================================================
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-01',
                    'end_date' => '2026-10-01',
                    'destination' => 'Sidoarjo',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-01',
                    'end_date' => '2026-10-01',
                    'destination' => 'Mojokerto',
                    'total_price' => 0,
                    'status' => 'Selesai',
                    'payment_status' => 'Belum Bayar',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-02',
                    'end_date' => '2026-10-02',
                    'destination' => 'Solo',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-03',
                    'end_date' => '2026-10-03',
                    'destination' => 'Jombang',
                    'total_price' => 400000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-04',
                    'end_date' => '2026-10-04',
                    'destination' => 'Gresik - Bangkalan',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-04',
                    'end_date' => '2026-10-04',
                    'destination' => 'Besuki',
                    'total_price' => 200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-08',
                    'end_date' => '2026-10-08',
                    'destination' => 'Kediri',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-08',
                    'end_date' => '2026-10-08',
                    'destination' => 'Madura',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-10',
                    'end_date' => '2026-10-10',
                    'destination' => 'Probolinggo',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-11',
                    'end_date' => '2026-10-11',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-13',
                    'end_date' => '2026-10-13',
                    'destination' => 'Jember',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-15',
                    'end_date' => '2026-10-15',
                    'destination' => 'Jember',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-16',
                    'end_date' => '2026-10-16',
                    'destination' => 'Bandara',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-17',
                    'end_date' => '2026-10-17',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-17',
                    'end_date' => '2026-10-17',
                    'destination' => 'Tuban',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-17',
                    'end_date' => '2026-10-17',
                    'destination' => 'Jember',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-18',
                    'end_date' => '2026-10-18',
                    'destination' => 'Sidoarjo (Ara)',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 21,
                    'driver_id' => 1,
                    'start_date' => '2026-10-19',
                    'end_date' => '2026-10-20',
                    'destination' => 'Malang',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-20',
                    'end_date' => '2026-10-20',
                    'destination' => 'Probolinggo',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-21',
                    'end_date' => '2026-10-21',
                    'destination' => 'Klaseman',
                    'total_price' => 200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-24',
                    'end_date' => '2026-10-24',
                    'destination' => 'Surabaya',
                    'total_price' => 550000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-24',
                    'end_date' => '2026-10-24',
                    'destination' => 'Situbondo',
                    'total_price' => 400000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 22,
                    'driver_id' => 1,
                    'start_date' => '2026-10-25',
                    'end_date' => '2026-10-25',
                    'destination' => 'Unuja',
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-25',
                    'end_date' => '2026-10-25',
                    'destination' => 'Kraksaan',
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-25',
                    'end_date' => '2026-10-25',
                    'destination' => 'Pamekasan',
                    'total_price' => 600000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-26',
                    'end_date' => '2026-10-26',
                    'destination' => 'Bondowoso',
                    'total_price' => 400000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-10-28',
                    'end_date' => '2026-10-28',
                    'destination' => 'Pakuniran Lamaran',
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-28',
                    'end_date' => '2026-10-28',
                    'destination' => 'Lamaran',
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-28',
                    'end_date' => '2026-10-28',
                    'destination' => 'Lamaran',
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-29',
                    'end_date' => '2026-10-29',
                    'destination' => 'Bentar',
                    'total_price' => 200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-29',
                    'end_date' => '2026-10-29',
                    'destination' => 'Bentar',
                    'total_price' => 200000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-30',
                    'end_date' => '2026-10-30',
                    'destination' => 'Madura',
                    'total_price' => 1000000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-30',
                    'end_date' => '2026-10-30',
                    'destination' => 'Krucil',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-31',
                    'end_date' => '2026-10-31',
                    'destination' => 'Wali 5',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-10-31',
                    'end_date' => '2026-10-31',
                    'destination' => 'Madura',
                    'total_price' => 700000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],

                // =========================================================================
                // NOVEMBER 2026
                // =========================================================================
                // AM-03 (2 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-02',
                    'end_date' => '2026-11-02',
                    'destination' => 'Situbondo',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02 (3 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-03',
                    'end_date' => '2026-11-03',
                    'destination' => 'Probolinggo',
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03 (4 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-04',
                    'end_date' => '2026-11-04',
                    'destination' => 'Bangkalan',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01 (7 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 21,
                    'driver_id' => 1,
                    'start_date' => '2026-11-07',
                    'end_date' => '2026-11-07',
                    'destination' => 'Bandara',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02 (7 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-07',
                    'end_date' => '2026-11-07',
                    'destination' => 'Lumajang',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03 (7 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-11-07',
                    'end_date' => '2026-11-07',
                    'destination' => 'Jember',
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01 (8 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-08',
                    'end_date' => '2026-11-08',
                    'destination' => 'Lembah Pandawa',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-02 (8 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-08',
                    'end_date' => '2026-11-08',
                    'destination' => 'Lembah Pandawa',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-03 (8 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-08',
                    'end_date' => '2026-11-08',
                    'destination' => 'Lembah Pandawa',
                    'total_price' => 350000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01 (9 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-09',
                    'end_date' => '2026-11-09',
                    'destination' => 'Kencong',
                    'total_price' => 400000,
                    'status' => 'Selesai',
                    'payment_status' => 'Lunas',
                ],
                // AM-01 (14 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 8,
                    'driver_id' => 1,
                    'start_date' => '2026-11-14',
                    'end_date' => '2026-11-14',
                    'destination' => 'Acara Sendiri',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-02 (14 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 8,
                    'driver_id' => 1,
                    'start_date' => '2026-11-14',
                    'end_date' => '2026-11-14',
                    'destination' => 'Acara Sendiri',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (14 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-11-14',
                    'end_date' => '2026-11-14',
                    'destination' => 'Malang',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (15 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-11-15',
                    'end_date' => '2026-11-15',
                    'destination' => 'Bangkalan',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-01 (16 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 8,
                    'driver_id' => 1,
                    'start_date' => '2026-11-16',
                    'end_date' => '2026-11-16',
                    'destination' => 'Acara Sendiri',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-02 (16 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 8,
                    'driver_id' => 1,
                    'start_date' => '2026-11-16',
                    'end_date' => '2026-11-16',
                    'destination' => 'Acara Sendiri',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (16 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 8,
                    'driver_id' => 1,
                    'start_date' => '2026-11-16',
                    'end_date' => '2026-11-16',
                    'destination' => 'Acara Sendiri',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (17 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-17',
                    'end_date' => '2026-11-17',
                    'destination' => 'Madura',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-02 (18 Nov)
                [
                    'car_id' => 2,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-11-18',
                    'end_date' => '2026-11-18',
                    'destination' => 'Probolinggo',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-01 (20 Nov)
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-20',
                    'end_date' => '2026-11-20',
                    'destination' => 'Probolinggo',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (21 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-11-21',
                    'end_date' => '2026-11-21',
                    'destination' => 'Malang',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (28-29 Nov)
                [
                    'car_id' => 3,
                    'customer_id' => 17,
                    'driver_id' => 3,
                    'start_date' => '2026-11-28',
                    'end_date' => '2026-11-29',
                    'destination' => 'Wali 5 + Bangkalan',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],

                // =========================================================================
                // DECEMBER 2026
                // =========================================================================
                // AM-01 (5 Des)
                [
                    'car_id' => 1,
                    'customer_id' => 22,
                    'driver_id' => 1,
                    'start_date' => '2026-12-05',
                    'end_date' => '2026-12-05',
                    'destination' => 'Cak Ruhin',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-02 (5 Des)
                [
                    'car_id' => 2,
                    'customer_id' => 22,
                    'driver_id' => 1,
                    'start_date' => '2026-12-05',
                    'end_date' => '2026-12-05',
                    'destination' => 'Cak Ruhin',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (5 Des)
                [
                    'car_id' => 3,
                    'customer_id' => 22,
                    'driver_id' => 3,
                    'start_date' => '2026-12-05',
                    'end_date' => '2026-12-05',
                    'destination' => 'Cak Ruhin',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (8 Des)
                [
                    'car_id' => 3,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-12-08',
                    'end_date' => '2026-12-08',
                    'destination' => 'Bangil',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-01 (14 Des)
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-12-14',
                    'end_date' => '2026-12-14',
                    'destination' => 'Bandara',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-01 (25 Des)
                [
                    'car_id' => 1,
                    'customer_id' => 6,
                    'driver_id' => 1,
                    'start_date' => '2026-12-25',
                    'end_date' => '2026-12-25',
                    'destination' => 'Cilacap',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],

                // =========================================================================
                // JANUARY 2027
                // =========================================================================
                // AM-01 (13 Jan)
                [
                    'car_id' => 1,
                    'customer_id' => 23,
                    'driver_id' => 1,
                    'start_date' => '2027-01-13',
                    'end_date' => '2027-01-13',
                    'destination' => 'Jember',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
                // AM-03 (13 Jan)
                [
                    'car_id' => 3,
                    'customer_id' => 23,
                    'driver_id' => 1,
                    'start_date' => '2027-01-13',
                    'end_date' => '2027-01-13',
                    'destination' => 'Jember',
                    'total_price' => 0,
                    'status' => 'Booking',
                    'payment_status' => 'Belum Bayar',
                ],
            ];

            $createdBookings = [];

            foreach ($bookingsData as $data) {
                $code = sprintf('BK%04d', $nextCodeNumber++);
                $booking = Booking::create([
                    'customer_id'    => $data['customer_id'],
                    'car_id'         => $data['car_id'],
                    'driver_id'      => $data['driver_id'],
                    'booking_code'   => $code,
                    'start_date'     => $data['start_date'],
                    'end_date'       => $data['end_date'],
                    'total_price'    => $data['total_price'],
                    'dp_amount'      => 0,
                    'status'         => $data['status'],
                    'open_status'    => 1,
                    'payment_status' => $data['payment_status'],
                    'destination'    => $data['destination'],
                    'pickup'         => '-',
                ]);

                $createdBookings[] = $booking;

                // Create BookingPrice item if price > 0
                if ($data['total_price'] > 0) {
                    BookingPrice::create([
                        'booking_id' => $booking->id,
                        'name'       => 'Sewa Mobil - ' . $data['destination'],
                        'price'      => $data['total_price'],
                        'qty'        => 1,
                        'subtotal'   => $data['total_price'],
                    ]);
                }
            }

            // 4. Re-link October & other cash flows to the corresponding bookings where applicable
            // For each income cashflow in cash_flows, try to find matching booking
            $incomes = CashFlow::where('type', 'income')->whereNotNull('car_id')->get();
            foreach ($incomes as $cf) {
                // Find a booking for this car around or before the transaction date
                // Match based on car_id and amount, and closest start_date
                $matched = Booking::where('car_id', $cf->car_id)
                    ->where('total_price', $cf->amount)
                    ->where('start_date', '<=', $cf->transaction_date)
                    ->orderBy('start_date', 'desc')
                    ->first();

                if ($matched) {
                    $cf->update(['booking_id' => $matched->id]);
                }
            }

            DB::commit();
            echo "Successfully seeded " . count($createdBookings) . " bookings from September 2026 onwards." . PHP_EOL;

        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error seeding bookings: " . $e->getMessage() . PHP_EOL;
            throw $e;
        }
    }
}

