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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('quantity');
            $table->decimal('unitary_value', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('border_id')->nullable();
            $table->foreign('border_id')->references('id')->on('border_options');

            $table->unsignedBigInteger('pizza_size_id')->nullable();
            $table->foreign('pizza_size_id')->references('id')->on('pizza_sizes');

            $table->unsignedBigInteger('pasta_id')->nullable();
            $table->foreign('pasta_id')->references('id')->on('pasta_options');

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
        Schema::dropIfExists('order_items');
    }
};
