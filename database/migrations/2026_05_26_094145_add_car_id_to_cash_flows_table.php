<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_flows', function (Blueprint $table) {

            $table->foreignId('car_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('cars')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('cash_flows', function (Blueprint $table) {

            $table->dropForeign(['car_id']);
            $table->dropColumn('car_id');

        });
    }
};