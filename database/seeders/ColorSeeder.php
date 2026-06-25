<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example for a PHP/Laravel seeder
        $colors =
            [
                [
                    "color_name"=> "Black",
                    "color_code"=> "#000000"
                ],
                [
                    "color_name"=> "Dark Brown",
                    "color_code"=> "#5C4033"
                ],
                [
                    "color_name"=> "Brown",
                    "color_code"=> "#A52A2A"
                ],
                [
                    "color_name"=> "Light Brown",
                    "color_code"=> "#C4A484"
                ],
                [
                    "color_name"=> "Beige",
                    "color_code"=> "#F5F5DC"
                ],
                [
                    "color_name"=> "Cream",
                    "color_code"=> "#FFFDD0"
                ],
                [
                    "color_name"=> "White",
                    "color_code"=> "#FFFFFF"
                ],
                [
                    "color_name"=> "Yellow",
                    "color_code"=> "#FFFF00"
                ],
                [
                    "color_name"=> "Dark Yellow",
                    "color_code"=> "#FFC90A"
                ],
                [
                    "color_name"=> "Green",
                    "color_code"=> "#008000"
                ],
                [
                    "color_name"=> "Light Green",
                    "color_code"=> "#90EE90"
                ],
                [
                    "color_name"=> "Red",
                    "color_code"=> "#FF0000"
                ],
                [
                    "color_name"=> "Maroon",
                    "color_code"=> "#800000"
                ],
                [
                    "color_name"=> "Dark Purple",
                    "color_code"=> "#301934"
                ]
            ];
        DB::table('colors')->insert($colors);
    }
}
