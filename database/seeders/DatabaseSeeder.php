<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CarSeeder::class,
            CustomerSeeder::class,
            October2026DataSeeder::class,
        ]);
    }
}