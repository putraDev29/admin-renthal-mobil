<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->foreignId('car_id')
                ->constrained('cars')
                ->onDelete('cascade');

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->nullOnDelete();

            $table->string('booking_code', 50);

            $table->date('start_date');

            $table->date('end_date');

            $table->string('description', 50)->nullable();

            $table->decimal('total_price', 15, 2)->default(0);

            $table->decimal('dp_amount', 15, 2)->default(0);

            $table->enum('status', [
                'Belum Bayar',
                'DP',
                'Selesai'
            ])->default('Belum Bayar');

            $table->text('destination')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('bookings');

        Schema::enableForeignKeyConstraints();
    }
}
