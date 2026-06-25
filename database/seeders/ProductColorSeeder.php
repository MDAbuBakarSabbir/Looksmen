<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_colors = [
            [
                'product_id' => '1',
                'color_id' => '1',
            ],
            [
                'product_id' => '2',
                'color_id' => '2',
            ],
            [
                'product_id' => '3',
                'color_id' => '3',
            ],
        ];
        DB::table('product_colors')->insert($product_colors);
    }
}
