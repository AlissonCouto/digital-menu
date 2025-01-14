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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('uf', 2);
            $table->integer('codigo_ibge')->nullable()->default(null);
            $table->string('gentilico', 40)->nullable()->default(null);
            $table->integer('populacao')->nullable()->default(null);
            $table->decimal('area', 11, 2)->nullable()->default(null);
            $table->decimal('densidade_demografica', 11, 2)->nullable()->default(null);
            $table->integer('pib')->nullable()->default(null);
            $table->string('lat', 20)->nullable()->default(null);
            $table->string('lng', 20)->nullable()->default(null);
            $table->integer('capital')->nullable()->default(null);

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
