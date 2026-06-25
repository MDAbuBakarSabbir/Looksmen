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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('created_by')->default(1);
            $table->string('title');
            $table->string('slug');
            $table->string('category_id');
            $table->string('subcategory_id')->nullable();
            $table->string('childcategory_id')->nullable();
            $table->longText('description');
            $table->longText('tags')->nullable();
            $table->string('brand_id')->nullable();
            $table->string('code')->unique();
            $table->string('old_price');
            $table->string('new_price');
            $table->string('stock');
            $table->string('video')->nullable();
            $table->string('cod')->default(1);
            $table->string('advance_amount')->nullable();
            $table->string('todays_deal')->default(0);
            $table->string('status')->default(1);
            $table->string('num_of_sale')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
