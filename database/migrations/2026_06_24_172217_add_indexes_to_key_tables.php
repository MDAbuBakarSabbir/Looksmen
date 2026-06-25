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
        // 1. Products Table
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('childcategory_id');
            $table->index('brand_id');
        });

        // 2. Categories Table
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('category_id');
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('category_id');
            $table->index('subcategory_id');
        });

        // 3. Orders & Order Details
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        // 4. Carts & Addresses
        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('temp_user_id');
            $table->index('cart_id');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->index('user_id');
        });

        // 5. Product Relations
        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('product_attributes', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['subcategory_id']);
            $table->dropIndex(['childcategory_id']);
            $table->dropIndex(['brand_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['subcategory_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['temp_user_id']);
            $table->dropIndex(['cart_id']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_colors', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
