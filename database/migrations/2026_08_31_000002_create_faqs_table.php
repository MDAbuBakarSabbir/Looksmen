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
        if (! Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('question');
                $table->longText('answer');
                $table->string('category')->default('general'); // shipping, orders, payments, returns, account, general
                $table->integer('order')->default(0);
                $table->tinyInteger('status')->default(1); // 1 = Active, 0 = Inactive
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
