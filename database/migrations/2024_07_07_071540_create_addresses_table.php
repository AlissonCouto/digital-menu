<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('street');
            $table->string('number')->default(0);
            $table->string('neighborhood')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('main')->default(false);

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->foreign('deliveryman_id')->references('id')->on('delivery_drivers');

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('neighborhood_id')->nullable();
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
