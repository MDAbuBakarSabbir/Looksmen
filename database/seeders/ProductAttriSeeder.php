<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductAttriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_attributes = [
            [
                'product_id' => '1',
                'attribute_id' => '1',
                'attribute_value' => 'M,L'
            ],
            [
                'product_id' => '2',
                'attribute_id' => '2',
                'attribute_value' => '128GB,256GB'
            ],
            [
                'product_id' => '3',
                'attribute_id' => '3',
                'attribute_value' => '8GB,16GB'
            ],
        ];
        DB::table('product_attributes')->insert($product_attributes);
    }
}
