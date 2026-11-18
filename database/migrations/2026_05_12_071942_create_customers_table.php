<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 30)->unique(); // 👈 tambahan NIK
            $table->string('name', 100);
            $table->string('gender', 10); // 👈 L / P
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('customers');

        Schema::enableForeignKeyConstraints();
    }
}
