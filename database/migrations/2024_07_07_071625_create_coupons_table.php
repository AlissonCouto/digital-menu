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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('validity_type', ['usage_limit', 'deadline'])->default('usage_limit');
            $table->enum('discount_type', ['value', 'percent'])->value('percent');
            $table->decimal('value', 10, 2);
            $table->integer('active')->default(1);

            /* Quantidade total de aplicações permitidas */
            $table->integer('usage_limit')->nullable();

            /* Quantidade de vezes que foi usado */
            $table->integer('applications')->default(0);

            $table->date('expiration_date')->nullable();
            $table->time('expiry_time')->nullable();

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
        Schema::dropIfExists('coupons');
    }
};
