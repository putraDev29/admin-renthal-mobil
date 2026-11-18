<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_prices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();

            $table->string('name');

            $table->decimal('price', 15, 2)->default(0);

            $table->integer('qty')->default(1);

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_prices');
    }
};