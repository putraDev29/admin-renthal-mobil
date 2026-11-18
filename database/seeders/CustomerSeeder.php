<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([

            [
                'nik' => '3201010101010001',
                'name' => 'Budi Santoso',
                'gender' => 'Laki-laki',
                'phone' => '081234567890',
                'address' => 'Jakarta'
            ],

            [
                'nik' => '3201010101010002',
                'name' => 'Andi Wijaya',
                'gender' => 'Laki-laki',
                'phone' => '081234567891',
                'address' => 'Bandung'
            ],

            [
                'nik' => '3201010101010003',
                'name' => 'Rina Wulandari',
                'gender' => 'Perempuan',
                'phone' => '081234567892',
                'address' => 'Surabaya'
            ],

            [
                'nik' => '3201010101010004',
                'name' => 'Fajar Setiawan',
                'gender' => 'Laki-laki',
                'phone' => '081234567893',
                'address' => 'Bekasi'
            ],

            [
                'nik' => '3201010101010005',
                'name' => 'Lina Marina',
                'gender' => 'Perempuan',
                'phone' => '081234567894',
                'address' => 'Depok'
            ],

            [
                'nik' => '3201010101010006',
                'name' => 'Rahmat Hidayat',
                'gender' => 'Laki-laki',
                'phone' => '081234567895',
                'address' => 'Bogor'
            ],

            [
                'nik' => '3201010101010007',
                'name' => 'Siti Aisyah',
                'gender' => 'Perempuan',
                'phone' => '081234567896',
                'address' => 'Tangerang'
            ],

            [
                'nik' => '3201010101010008',
                'name' => 'Doddy Kurniawan',
                'gender' => 'Laki-laki',
                'phone' => '081234567897',
                'address' => 'Semarang'
            ],

            [
                'nik' => '3201010101010009',
                'name' => 'Michael T.',
                'gender' => 'Laki-laki',
                'phone' => '081234567898',
                'address' => 'Yogyakarta'
            ],

            [
                'nik' => '3201010101010010',
                'name' => 'Putri Ayu',
                'gender' => 'Perempuan',
                'phone' => '081234567899',
                'address' => 'Malang'
            ]

        ]);
    }
}