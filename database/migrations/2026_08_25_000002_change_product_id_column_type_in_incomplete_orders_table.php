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
        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                $table->longText('product_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('incomplete_orders')) {
            Schema::table('incomplete_orders', function (Blueprint $table) {
                $table->string('product_id')->nullable()->change();
            });
        }
    }
};
