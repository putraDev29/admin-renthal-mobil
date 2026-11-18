<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookings')->insert([

            [
                'customer_id' => 1,
                'car_id' => 1,
                'booking_code' => 'BK001',

                'start_date' => '2026-05-01',
                'end_date' => '2026-05-05',

                'description' => 'plus_sopir',
                'driver_price' => 500000,

                'total_price' => 2500000,
                'dp_amount' => 1000000,

                'status' => 'DP',

                'destination' => 'Surabaya',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 2,
                'car_id' => 1,
                'booking_code' => 'BK002',

                'start_date' => '2026-05-07',
                'end_date' => '2026-05-10',

                'description' => 'lepas_kunci',
                'driver_price' => 0,

                'total_price' => 1800000,
                'dp_amount' => 500000,

                'status' => 'DP',

                'destination' => 'Malang',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 3,
                'car_id' => 1,
                'booking_code' => 'BK003',

                'start_date' => '2026-05-12',
                'end_date' => '2026-05-18',

                'description' => 'plus_sopir',
                'driver_price' => 800000,

                'total_price' => 3200000,
                'dp_amount' => 1500000,

                'status' => 'Selesai',

                'destination' => 'Jakarta',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 4,
                'car_id' => 2,
                'booking_code' => 'BK004',

                'start_date' => '2026-05-01',
                'end_date' => '2026-05-03',

                'description' => 'lepas_kunci',
                'driver_price' => 0,

                'total_price' => 1200000,
                'dp_amount' => 500000,

                'status' => 'DP',

                'destination' => 'Sidoarjo',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 5,
                'car_id' => 2,
                'booking_code' => 'BK005',

                'start_date' => '2026-05-05',
                'end_date' => '2026-05-07',

                'description' => 'plus_sopir',
                'driver_price' => 300000,

                'total_price' => 1000000,
                'dp_amount' => 0,

                'status' => 'Belum Bayar',

                'destination' => 'Mojokerto',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 6,
                'car_id' => 2,
                'booking_code' => 'BK006',

                'start_date' => '2026-05-11',
                'end_date' => '2026-05-14',

                'description' => 'plus_sopir',
                'driver_price' => 400000,

                'total_price' => 1500000,
                'dp_amount' => 700000,

                'status' => 'DP',

                'destination' => 'Batu',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 7,
                'car_id' => 3,
                'booking_code' => 'BK007',

                'start_date' => '2026-05-22',
                'end_date' => '2026-05-24',

                'description' => 'plus_sopir',
                'driver_price' => 500000,

                'total_price' => 2000000,
                'dp_amount' => 1000000,

                'status' => 'Selesai',

                'destination' => 'Bandung',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 8,
                'car_id' => 4,
                'booking_code' => 'BK008',

                'start_date' => '2026-05-27',
                'end_date' => '2026-05-30',

                'description' => 'plus_sopir',
                'driver_price' => 1000000,

                'total_price' => 4000000,
                'dp_amount' => 2000000,

                'status' => 'DP',

                'destination' => 'Yogyakarta',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 9,
                'car_id' => 5,
                'booking_code' => 'BK009',

                'start_date' => '2026-05-16',
                'end_date' => '2026-05-18',

                'description' => 'lepas_kunci',
                'driver_price' => 0,

                'total_price' => 3500000,
                'dp_amount' => 0,

                'status' => 'Belum Bayar',

                'destination' => 'Semarang',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'customer_id' => 10,
                'car_id' => 7,
                'booking_code' => 'BK010',

                'start_date' => '2026-05-19',
                'end_date' => '2026-05-21',

                'description' => 'plus_sopir',
                'driver_price' => 250000,

                'total_price' => 1300000,
                'dp_amount' => 500000,

                'status' => 'Selesai',

                'destination' => 'Pasuruan',

                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }
}