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
            $table->string('user_id')->default('0');
            $table->string('order_amount_id')->nullable();
            $table->string('order_payment_id')->nullable();
            $table->string('created_by')->default('customer');
            $table->string('courier_updated_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('phone');
            $table->string('alt_phone')->nullable();
            $table->string('name');
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('address');
            $table->string('delivery_status')->default('new');
            $table->string('payment_type')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('return_status')->nullable();
            $table->string('total_amount');
            $table->string('paid_amount')->nullable();
            $table->string('admin_discount')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->string('delivery_charge');
            $table->string('grand_total');
            $table->string('coupon_code')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('consignment_id')->nullable();
            $table->boolean('courier_popup_shown')->default(0);
            $table->string('commission_calculated')->nullable();
            $table->string('shipping_type')->nullable();
            $table->string('refund_status')->nullable();
            $table->string('comments')->nullable();
            $table->string('note')->nullable();
            $table->string('courier_history')->nullable();
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
