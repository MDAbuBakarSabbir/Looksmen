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
        Schema::create('child_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
          ->constrained('categories')
          ->onDelete('cascade');
            $table->foreignId('subcategory_id')
          ->constrained('sub_categories') // 'sub_categories' টেবিলের সাথে ফরেন কি সেট করুন
          ->onDelete('cascade');
            $table->string('level')->default(1);
            $table->string('name');
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
        Schema::dropIfExists('child_categories');
    }
};
