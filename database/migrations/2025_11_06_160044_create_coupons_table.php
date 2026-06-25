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
            $table->string('user_type')->default('all');
            $table->string('use_type')->default('single');
            $table->string('coupon_type')->default('total_order');
            $table->string('code')->unique();
            $table->longText('details')->nullable();
            $table->string('discount_type');
            $table->string('discount');
            $table->string('min_cart_amount')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('used')->default(0);
            $table->string('start_date');
            $table->string('end_date');
            $table->string('status')->default(1);
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
