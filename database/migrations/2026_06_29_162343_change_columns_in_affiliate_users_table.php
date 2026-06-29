<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliate_users', function (Blueprint $table) {
            $table->text('informations')->nullable()->change();
            $table->text('bank_information')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_users', function (Blueprint $table) {
            $table->string('informations')->nullable()->change();
            $table->string('bank_information')->nullable()->change();
        });
    }
};
