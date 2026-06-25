<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductimgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_img = [
            [
                'product_id' => '1',
                'image' => 'P1img1.jpg'
            ],
            [
                'product_id' => '1',
                'image' => 'P1img2.jpg'
            ],

            [
                'product_id' => '2',
                'image' => 'P2img1.jpg'
            ],
            [
                'product_id' => '2',
                'image' => 'P2img2.jpg'
            ],

            [
                'product_id' => '3',
                'image' => 'P3img1.jpg'
            ],
            [
                'product_id' => '3',
                'image' => 'P3img2.jpg'
            ],
        ];
        DB::table('product_images')->insert($product_img);
    }
}
