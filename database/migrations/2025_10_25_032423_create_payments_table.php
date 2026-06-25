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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->default(0);
            $table->string('amount')->nullable();
            $table->string('currency')->default('BDT');
            $table->string('payerReference')->nullable();
            $table->string('paymentID')->nullable();
            $table->string('merchantInvoiceNumber')->nullable();
            $table->string('trxID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
