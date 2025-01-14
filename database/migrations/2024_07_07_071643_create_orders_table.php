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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('comments')->nullable();
            $table->text('comments_deliveryman')->nullable();
            $table->datetime('date');
            $table->enum('origin', ['attendant', 'client'])->default('client');
            $table->enum('payment', ['credit', 'debit', 'cash', 'pix'])->default('credit');
            $table->enum('status', ['realized', 'inanalysis', 'inproduction', 'ready', 'closed', 'rejected', 'canceled'])->default('realized');
            $table->enum('delivery_method', ['delivery', 'withdrawal', 'comeget'])->default('delivery');
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('rating', 2, 2)->nullable();
            $table->text('note')->nullable();

            /* Campos referentes ao endereço */
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('reference')->nullable();
            $table->string('city')->nullable();
            $table->string('uf')->nullable();
            /* Campos referentes ao endereço */

            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->foreign('deliveryman_id')->references('id')->on('delivery_drivers');

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');

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
        Schema::dropIfExists('orders');
    }
};
