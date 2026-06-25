<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tshirt',
                'type' => 'Physical',
                'banner' => 'TshirtBanner.jpg',
                'icon' => 'Tshirt',
                'slug' => 'Tshirt',
            ],
            [
                'name' => 'SSD',
                'type' => 'Physical',
                'banner' => 'SsdBanner.jpg',
                'icon' => 'SSD',
                'slug' => 'SSD',
            ],
            [
                'name' => 'RAM',
                'type' => 'Physical',
                'banner' => 'RamBanner.jpg',
                'icon' => 'RAM',
                'slug' => 'RAM',
            ],

        ];
        DB::table('categories')->insert($categories);
    }
}
