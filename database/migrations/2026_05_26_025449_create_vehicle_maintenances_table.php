<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_maintenances', function (Blueprint $table) {

            $table->id();

            $table->foreignId('car_id')
                ->constrained('cars')
                ->cascadeOnDelete();

            $table->date('maintenance_date');

            $table->bigInteger('km_vehicle')->nullable();

            $table->string('oil_spec')->nullable();

            $table->bigInteger('previous_oil_km')->nullable();

            $table->bigInteger('next_oil_km')->nullable();

            $table->string('front_right_tire')->nullable();

            $table->string('front_left_tire')->nullable();

            $table->string('rear_right_tire')->nullable();

            $table->string('rear_left_tire')->nullable();

            $table->string('workshop_name')->nullable();

            $table->bigInteger('cost')->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
}
