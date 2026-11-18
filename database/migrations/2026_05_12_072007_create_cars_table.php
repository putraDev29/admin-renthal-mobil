<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('unit', 100);
            $table->string('name', 100);
            $table->string('plate_number', 50);
            $table->integer('total_unit')->default(1);
            $table->string('image')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->date('tax')->nullable();
            $table->date('kir')->nullable();
            $table->enum('status', ['available', 'maintenance'])
                ->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('cars');

        Schema::enableForeignKeyConstraints();
    }
}
