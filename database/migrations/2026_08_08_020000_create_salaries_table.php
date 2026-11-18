<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            $table->string('recipient_name');
            $table->date('transaction_date');
            $table->unsignedBigInteger('amount')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
