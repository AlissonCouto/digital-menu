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
        Schema::create('price_pizza_sizes', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('pizza_size_id')->nullable();
            $table->foreign('pizza_size_id')->references('id')->on('pizza_sizes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_pizza_sizes');
    }
};
