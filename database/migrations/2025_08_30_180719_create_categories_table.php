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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('level')->default(1);
            $table->string('name');
            $table->string('type');
            $table->string('commission_rate')->nullable();
            $table->string('banner');
            $table->string('icon');
            $table->string('featured')->default(0);
            $table->string('slug');
            $table->string('meta_title')->nullable();
            $table->string('meta_descritption')->nullable();
            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
