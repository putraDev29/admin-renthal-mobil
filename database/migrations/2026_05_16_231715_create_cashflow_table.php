<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {

            $table->id();

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->string('type')->default('income');

            $table->string('title');

            $table->bigInteger('amount')->default(0);

            $table->text('description')->nullable();

            $table->date('transaction_date');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};