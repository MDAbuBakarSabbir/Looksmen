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
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'free_delivery_qty')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedInteger('free_delivery_qty')->nullable()->after('status');
            });
        }

        if (Schema::hasTable('sub_categories') && !Schema::hasColumn('sub_categories', 'free_delivery_qty')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->unsignedInteger('free_delivery_qty')->nullable()->after('status');
            });
        }

        if (Schema::hasTable('child_categories') && !Schema::hasColumn('child_categories', 'free_delivery_qty')) {
            Schema::table('child_categories', function (Blueprint $table) {
                $table->unsignedInteger('free_delivery_qty')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'free_delivery_qty')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('free_delivery_qty');
            });
        }

        if (Schema::hasTable('sub_categories') && Schema::hasColumn('sub_categories', 'free_delivery_qty')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->dropColumn('free_delivery_qty');
            });
        }

        if (Schema::hasTable('child_categories') && Schema::hasColumn('child_categories', 'free_delivery_qty')) {
            Schema::table('child_categories', function (Blueprint $table) {
                $table->dropColumn('free_delivery_qty');
            });
        }
    }
};
