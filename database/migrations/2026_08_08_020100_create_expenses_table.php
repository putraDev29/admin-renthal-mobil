<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            $table->date('transaction_date');
            $table->string('category')->default('Lainnya');
            $table->string('title');
            $table->unsignedBigInteger('amount')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
