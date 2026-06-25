<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                "title"=>"Test Product 1",
                "slug"=>"Test-Product-1",
                "category_id"=>"1",
                "description"=>"Test Product 1",
                "code"=>"1",
                "old_price"=>"100",
                "new_price"=>"50",
                "stock"=>"100"
            ],

            [
                "title"=>"Test Product 2",
                "slug"=>"Test-Product-2",
                "category_id"=>"2",
                "description"=>"Test Product 2",
                "code"=>"2",
                "old_price"=>"100",
                "new_price"=>"50",
                "stock"=>"100"
            ],

            [
                "title"=>"Test Product 3",
                "slug"=>"Test-Product-3",
                "category_id"=>"3",
                "description"=>"Test Product 3",
                "code"=>"3",
                "old_price"=>"100",
                "new_price"=>"50",
                "stock"=>"100"
            ],


        ];
        DB::table('products')->insert($products);
    }
}
