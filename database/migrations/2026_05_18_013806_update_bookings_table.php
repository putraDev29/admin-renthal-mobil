<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateBookingsTable extends Migration
{
    public function up()
    {
        // 1. UBAH ENUM STATUS DULU
        DB::statement("
            ALTER TABLE bookings 
            MODIFY status ENUM(
                'Belum Bayar',
                'DP',
                'Booking',
                'Selesai'
            ) NOT NULL DEFAULT 'Belum Bayar'
        ");

        // 2. UPDATE DATA LAMA
        DB::table('bookings')
            ->whereIn('status', ['Belum Bayar', 'DP'])
            ->update([
                'status' => 'Booking'
            ]);

        // 3. HAPUS ENUM LAMA
        DB::statement("
            ALTER TABLE bookings 
            MODIFY status ENUM(
                'Booking',
                'Selesai'
            ) NOT NULL DEFAULT 'Selesai'
        ");

        // 4. HAPUS DESCRIPTION
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn('description');
        });

        // 5. TAMBAH KOLOM BARU
        Schema::table('bookings', function (Blueprint $table) {

            $table->text('pickup')->nullable();

            $table->enum('payment_status', [
                'Belum Bayar',
                'DP',
                'Lunas'
            ])->default('Belum Bayar')
              ->after('status');
        });
    }

    public function down()
    {
        // HAPUS KOLOM BARU
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn('pickup');

            $table->dropColumn('payment_status');

            $table->string('description', 50)
                ->nullable()
                ->after('end_date');
        });

        // KEMBALIKAN ENUM STATUS
        DB::statement("
            ALTER TABLE bookings 
            MODIFY status ENUM(
                'Belum Bayar',
                'DP',
                'Selesai'
            ) NOT NULL DEFAULT 'Belum Bayar'
        ");
    }
}