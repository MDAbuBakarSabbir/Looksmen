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
        Schema::create('courier_apis', function (Blueprint $table) {
            $table->id();
            $table->string('courier_name');
            $table->longText('api_key')->nullable();
            $table->longText('secret_key')->nullable();
            $table->longText('base_url')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_apis');
    }
};
