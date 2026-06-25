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
        Schema::create('affiliate_stats', function (Blueprint $table) {
            $table->id();
            $table->string('affiliate_user_id');
            $table->string('no_of_click')->default(0);
            $table->string('no_of_order_item')->default(0);
            $table->string('no_of_delivered')->default(0);
            $table->string('no_of_cancel')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_stats');
    }
};
