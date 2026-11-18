<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cars')->insert([

            [
                'name' => 'Toyota Avanza',
                'plate_number' => 'B 1234 ABC',
                'total_unit' => 6,
                'image' => 'avanza.png',
                'unit' => 'AM-01',
                'kir' => '2026-12-12',
                'tax' => '2026-12-12',
                'status' => 'available'
            ],

            [
                'name' => 'Daihatsu Xenia',
                'plate_number' => 'B 2345 DEF',
                'total_unit' => 4,
                'image' => 'xenia.png',
                'unit' => 'AM-02',
                'kir' => '2026-11-10',
                'tax' => '2026-11-10',
                'status' => 'available'
            ],

            [
                'name' => 'Toyota Innova Reborn',
                'plate_number' => 'B 3456 GHI',
                'total_unit' => 3,
                'image' => 'innova.png',
                'unit' => 'AM-03',
                'kir' => '2026-10-15',
                'tax' => '2026-10-15',
                'status' => 'available'
            ],

            [
                'name' => 'Toyota Fortuner',
                'plate_number' => 'B 4567 JKL',
                'total_unit' => 2,
                'image' => 'fortuner.png',
                'unit' => 'AM-04',
                'kir' => '2026-09-20',
                'tax' => '2026-09-20',
                'status' => 'available'
            ],

            [
                'name' => 'Mitsubishi Pajero Sport',
                'plate_number' => 'B 5678 MNO',
                'total_unit' => 2,
                'image' => 'pajero.png',
                'unit' => 'AM-05',
                'kir' => '2026-08-18',
                'tax' => '2026-08-18',
                'status' => 'available'
            ],

            [
                'name' => 'Toyota Hiace',
                'plate_number' => 'B 6789 PQR',
                'total_unit' => 2,
                'image' => 'hiace.png',
                'unit' => 'AM-06',
                'kir' => '2026-07-25',
                'tax' => '2026-07-25',
                'status' => 'available'
            ],

            [
                'name' => 'Honda Brio',
                'plate_number' => 'B 7890 STU',
                'total_unit' => 3,
                'image' => 'brio.png',
                'unit' => 'AM-07',
                'kir' => '2026-06-30',
                'tax' => '2026-06-30',
                'status' => 'available'
            ]

        ]);
    }
}