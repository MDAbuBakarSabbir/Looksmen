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
        Schema::create('payment_apis', function (Blueprint $table) {
            $table->id();
            $table->string('paymentapi_name');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('base_url')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('api_key')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_apis');
    }
};
